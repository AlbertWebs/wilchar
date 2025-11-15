<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Client::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('client_code', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('id_number', 'like', "%{$search}%")
                    ->orWhere('business_name', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $clients = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.clients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::info('Client create form submitted', [
            'user_id' => auth()->id(),
            'payload' => $request->except(['_token']),
        ]);

        try {
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'middle_name' => 'nullable|string|max:255',
                'id_number' => 'required|string|unique:clients,id_number',
                'phone' => 'required|string|unique:clients,phone',
                'email' => 'nullable|email|unique:clients,email',
                'date_of_birth' => 'nullable|date',
                'gender' => 'nullable|string',
                'nationality' => 'nullable|string',
                'business_name' => 'required|string|unique:clients,business_name',
                'business_type' => 'required|string',
                'location' => 'required|string',
                'address' => 'nullable|string',
                'occupation' => 'nullable|string',
                'employer' => 'nullable|string',
                'mpesa_phone' => 'nullable|string',
                'alternate_phone' => 'nullable|string',
                'status' => 'nullable|in:active,inactive,blacklisted',
            ]);
        } catch (\Throwable $e) {
            Log::error('Client create validation failed', [
                'user_id' => auth()->id(),
                'errors' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : $e->getMessage(),
            ]);
            throw $e;
        }

        $validated['created_by'] = auth()->user()->name;
        $validated['created_by_user_id'] = auth()->id();
        $validated['status'] = $validated['status'] ?? 'active';

        try {
            Client::create($validated);
        } catch (\Throwable $e) {
            Log::error('Client record insert failed', [
                'user_id' => auth()->id(),
                'message' => $e->getMessage(),
            ]);
            throw $e;
        }

        return redirect()->route('clients.index')
            ->with('success', 'Client created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        return view('admin.clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        return view('admin.clients.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'id_number' => 'required|string|unique:clients,id_number,' . $client->id,
            'phone' => 'required|string|unique:clients,phone,' . $client->id,
            'email' => 'nullable|email|unique:clients,email,' . $client->id,
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string',
            'nationality' => 'nullable|string',
            'business_name' => 'required|string|unique:clients,business_name,' . $client->id,
            'business_type' => 'required|string',
            'location' => 'required|string',
            'address' => 'nullable|string',
            'occupation' => 'nullable|string',
            'employer' => 'nullable|string',
            'mpesa_phone' => 'nullable|string',
            'alternate_phone' => 'nullable|string',
            'status' => 'nullable|in:active,inactive,blacklisted',
        ]);

        $client->update($validated);

        return redirect()->route('clients.index')
            ->with('success', 'Client updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()->route('clients.index')
            ->with('success', 'Client deleted successfully.');
    }
}
