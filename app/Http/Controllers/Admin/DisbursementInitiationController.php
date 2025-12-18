<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Disbursement;
use App\Models\LoanApplication;
use App\Notifications\DisbursementOtpNotification;
use App\Services\B2cPaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class DisbursementInitiationController extends Controller
{
    public function __construct(private B2cPaymentService $b2cPaymentService)
    {
        // Authorization is handled at route level
    }
    
    /**
     * Check if user can access disbursement
     */
    private function authorizeAccess(): void
    {
        $user = auth()->user();
        if (!$user || !$user->hasAnyRole(['Admin', 'Finance Officer', 'Director'])) {
            abort(403, 'Unauthorized. Only Admin, Finance Officer, and Director can initiate disbursements.');
        }
    }

    /**
     * Generate and send OTP for disbursement
     */
    public function generateOtp(Request $request, $disbursementId): JsonResponse
    {
        $this->authorizeAccess();
        
        try {
            $user = auth()->user();
            $disbursement = Disbursement::findOrFail($disbursementId);
            
            // Load relationship
            $disbursement->load('loanApplication');

            // Allow OTP only for pending or failed disbursements (failed can be retried)
            if (!in_array($disbursement->status, ['pending', 'failed'], true)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Disbursement is not in a retryable state.',
                ], 400);
            }

            // If it previously failed, reset OTP-related fields for a fresh attempt
            if ($disbursement->status === 'failed') {
                $disbursement->update([
                    'status' => 'pending',
                    'otp_code_hash' => null,
                    'otp_expires_at' => null,
                    'otp_verified_at' => null,
                    'otp_attempts' => 0,
                    'otp_sent_at' => null,
                ]);
            }

            // Enforce a 2-minute cooldown between OTP generations
            if ($disbursement->otp_sent_at) {
                $secondsSinceLast = now()->diffInSeconds($disbursement->otp_sent_at);
                $minInterval = 120;

                if ($secondsSinceLast < $minInterval) {
                    $retryAfter = $minInterval - $secondsSinceLast;

                    return response()->json([
                        'success' => false,
                        'message' => 'You can request a new OTP after ' . $retryAfter . ' seconds.',
                        'step' => 'otp_generation',
                        'retry_after_seconds' => $retryAfter,
                    ], 429);
                }
            }

            // Generate 6-digit OTP
            $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $otpHash = Hash::make($otp);
            $expiresAt = now()->addMinutes(10);

            // Store OTP in disbursement
            $disbursement->update([
                'otp_code_hash' => $otpHash,
                'otp_expires_at' => $expiresAt,
                'otp_sent_at' => now(),
                'otp_attempts' => 0,
            ]);

            // Send OTP to user's email
            $user->notify(new DisbursementOtpNotification(
                $disbursement,
                $otp
            ));

            // Log OTP so it can be retrieved when mailer is not working (useful in local/sandbox)
            Log::info('Disbursement OTP generated', [
                'disbursement_id' => $disbursement->id,
                'loan_application_id' => $disbursement->loan_application_id ?? $disbursement->loanApplication?->id,
                'user_id' => $user->id,
                'user_email' => $user->email,
                'otp' => $otp,
                'environment' => app()->environment(),
            ]);

            $response = [
                'success' => true,
                'message' => 'OTP sent to your email.',
                'step' => 'otp_sent',
            ];

            // In local / sandbox environments, also return OTP in the JSON response for easier testing
            if (app()->environment('local') || config('app.sandbox_mode')) {
                $response['otp'] = $otp;
            }

            return response()->json($response);
        } catch (\Throwable $e) {
            Log::error('Disbursement OTP Generation Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate OTP: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Abort the disbursement initiation process
     */
    public function abort(Request $request, $disbursementId): JsonResponse
    {
        $this->authorizeAccess();

        try {
            $disbursement = Disbursement::findOrFail($disbursementId);

            // Allow aborting when disbursement is still pending or has failed
            if (!in_array($disbursement->status, ['pending', 'failed'], true)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only pending or failed disbursements can be aborted.',
                ], 400);
            }

            // Reset OTP fields and mark as pending again, but record that user aborted
            $disbursement->update([
                'status' => 'pending',
                'otp_code_hash' => null,
                'otp_expires_at' => null,
                'otp_verified_at' => null,
                'otp_attempts' => 0,
                'otp_sent_at' => null,
                'mpesa_response_description' => 'Aborted by user',
            ]);

            Log::info('Disbursement aborted', [
                'disbursement_id' => $disbursement->id,
                'loan_application_id' => $disbursement->loan_application_id,
                'aborted_by' => auth()->id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Disbursement process aborted.',
                'step' => 'aborted',
            ]);
        } catch (\Throwable $e) {
            Log::error('Disbursement Abort Error: ' . $e->getMessage(), [
                'disbursement_id' => $disbursementId ?? null,
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to abort disbursement: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Verify OTP and initiate B2C payment
     */
    public function verifyOtpAndDisburse(Request $request, $disbursementId): JsonResponse
    {
        $this->authorizeAccess();
        
        $validated = $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        try {
            $disbursement = Disbursement::findOrFail($disbursementId);
            DB::beginTransaction();

            // Check if disbursement is pending
            if ($disbursement->status !== 'pending') {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Disbursement is not in pending status.',
                ], 400);
            }

            // Verify OTP
            if (!$disbursement->otp_code_hash) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'OTP not found. Please generate a new OTP.',
                    'step' => 'otp_not_found',
                ], 400);
            }

            if (!Hash::check($validated['otp'], $disbursement->otp_code_hash)) {
                $disbursement->increment('otp_attempts');
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid OTP. Please check your email and try again.',
                    'step' => 'otp_verification',
                ], 400);
            }

            // Check if OTP expired
            if ($disbursement->otp_expires_at && now()->greaterThan($disbursement->otp_expires_at)) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'OTP has expired. Please generate a new OTP.',
                    'step' => 'otp_expired',
                ], 400);
            }

            // Mark OTP as verified
            $disbursement->update([
                'otp_verified_at' => now(),
                'disbursed_by' => auth()->id(),
            ]);

            // Initiate B2C payment if method is mpesa_b2c
            $step = 'payment_initiated';
            $paymentResult = null;

            if ($disbursement->method === 'mpesa_b2c' && $disbursement->recipient_phone) {
                $paymentResult = $this->b2cPaymentService->initiate($disbursement, 'Loan disbursement');

                if ($paymentResult['success']) {
                    $disbursement->update([
                        'mpesa_request_id' => $paymentResult['request_id'] ?? null,
                        'mpesa_response_code' => $paymentResult['response_code'] ?? null,
                        'mpesa_response_description' => $paymentResult['response_description'] ?? null,
                        'mpesa_originator_conversation_id' => $paymentResult['originator_conversation_id'] ?? null,
                        'status' => 'processing', // Change to processing while waiting for callback
                    ]);
                    $step = 'payment_sent';
                } else {
                    $disbursement->update([
                        'status' => 'failed',
                        'mpesa_response_description' => $paymentResult['error'] ?? 'Failed to initiate payment',
                    ]);
                    $step = 'payment_failed';
                }
            } else {
                // For non-M-Pesa methods, mark as success
                $disbursement->update([
                    'status' => 'success',
                ]);
                $step = 'completed';
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $step === 'payment_sent' 
                    ? 'Disbursement initiated successfully. Waiting for M-Pesa confirmation...' 
                    : ($step === 'payment_failed' 
                        ? 'Payment initiation failed: ' . ($paymentResult['error'] ?? 'Unknown error')
                        : 'Disbursement completed successfully.'),
                'step' => $step,
                'disbursement' => $disbursement->fresh(),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Disbursement Initiation Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to initiate disbursement: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get disbursement status
     */
    public function getStatus(Request $request, $disbursementId): JsonResponse
    {
        $this->authorizeAccess();
        
        try {
            $disbursement = Disbursement::findOrFail($disbursementId);
            $disbursement->refresh();
            $disbursement->load('loanApplication');

            $hasSentOtp = !is_null($disbursement->otp_sent_at);
            $hasVerifiedOtp = !is_null($disbursement->otp_verified_at);

            $steps = [
                'otp_generation' => [
                    'label' => 'Generate and Send OTP',
                    // We only consider an OTP "generated" when we've actually sent one in this flow.
                    'status' => $hasSentOtp ? 'completed' : 'pending',
                ],
                'otp_verification' => [
                    'label' => 'Verify OTP',
                    'status' => $hasVerifiedOtp
                        ? 'completed'
                        : ($hasSentOtp ? 'in_progress' : 'pending'),
                ],
                'payment_initiation' => [
                    'label' => 'Initiate B2C Payment',
                    'status' => $disbursement->mpesa_request_id 
                        ? 'completed' 
                        : ($hasVerifiedOtp ? 'in_progress' : 'pending'),
                ],
                'payment_confirmation' => [
                    'label' => 'Confirm Payment',
                    'status' => $disbursement->status === 'success' 
                        ? 'completed' 
                        : ($disbursement->status === 'processing' 
                            ? 'in_progress' 
                            : ($disbursement->status === 'failed' ? 'failed' : 'pending')),
                ],
            ];

            return response()->json([
                'success' => true,
                'disbursement' => $disbursement,
                'steps' => $steps,
                'current_step' => $this->getCurrentStep($steps),
            ]);
        } catch (\Throwable $e) {
            Log::error('Disbursement Status Error: ' . $e->getMessage(), [
                'disbursement_id' => $disbursementId ?? null,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to load disbursement status: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function getCurrentStep(array $steps): ?string
    {
        foreach ($steps as $key => $step) {
            if ($step['status'] === 'in_progress' || ($step['status'] === 'pending' && !$this->hasCompletedPreviousSteps($steps, $key))) {
                return $key;
            }
        }
        return null;
    }

    private function hasCompletedPreviousSteps(array $steps, string $currentKey): bool
    {
        $keys = array_keys($steps);
        $currentIndex = array_search($currentKey, $keys);
        
        for ($i = 0; $i < $currentIndex; $i++) {
            if ($steps[$keys[$i]]['status'] !== 'completed') {
                return false;
            }
        }
        return true;
    }
}

