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
        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if (!$user->hasAnyRole(['Admin', 'Finance Officer', 'Director'])) {
                abort(403, 'Unauthorized. Only Admin, Finance Officer, and Director can initiate disbursements.');
            }
            return $next($request);
        });
    }

    /**
     * Generate and send OTP for disbursement
     */
    public function generateOtp(Request $request, Disbursement $disbursement): JsonResponse
    {
        try {
            $user = auth()->user();
            
            // Load relationship
            $disbursement->load('loanApplication');

            // Check if disbursement is pending
            if ($disbursement->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Disbursement is not in pending status.',
                ], 400);
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

            return response()->json([
                'success' => true,
                'message' => 'OTP sent to your email.',
                'step' => 'otp_sent',
            ]);
        } catch (\Throwable $e) {
            Log::error('Disbursement OTP Generation Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate OTP: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Verify OTP and initiate B2C payment
     */
    public function verifyOtpAndDisburse(Request $request, Disbursement $disbursement): JsonResponse
    {
        $validated = $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        try {
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
    public function getStatus(Disbursement $disbursement): JsonResponse
    {
        $disbursement->refresh();
        $disbursement->load('loanApplication');

        $steps = [
            'otp_generation' => [
                'label' => 'Generate and Send OTP',
                'status' => $disbursement->otp_sent_at ? 'completed' : ($disbursement->otp_code_hash ? 'completed' : 'pending'),
            ],
            'otp_verification' => [
                'label' => 'Verify OTP',
                'status' => $disbursement->otp_verified_at ? 'completed' : ($disbursement->otp_sent_at ? 'in_progress' : 'pending'),
            ],
            'payment_initiation' => [
                'label' => 'Initiate B2C Payment',
                'status' => $disbursement->mpesa_request_id 
                    ? 'completed' 
                    : ($disbursement->otp_verified_at ? 'in_progress' : 'pending'),
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

