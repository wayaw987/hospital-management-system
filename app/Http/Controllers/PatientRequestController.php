<?php

namespace App\Http\Controllers;

use App\Models\PatientRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientRequestController extends Controller
{
    /**
     * Show the form for creating a new patient request.
     */
    public function create()
    {
        $user = Auth::user();
        
        // Check if user already has a pending or approved patient request
        $existingRequest = PatientRequest::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existingRequest) {
            if ($existingRequest->status === 'pending') {
                return redirect()->route('dashboard')
                    ->with('info', 'Permintaan data pasien Anda sedang diproses admin. Harap menunggu persetujuan.');
            } else if ($existingRequest->status === 'approved') {
                return redirect()->route('dashboard')
                    ->with('info', 'Data pasien Anda sudah disetujui. Anda dapat membuat appointment sekarang.');
            }
        }

        return view('patient-request.create', compact('user'));
    }

    /**
     * Store a newly created patient request.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Check if user already has a pending or approved patient request
        $existingRequest = PatientRequest::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existingRequest) {
            return redirect()->route('dashboard')
                ->with('error', 'Anda sudah memiliki permintaan data pasien yang aktif.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'birth_date' => 'required|date',
            'gender' => 'required|in:male,female',
            'blood_type' => 'nullable|string|max:5',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:20',
            'insurance_number' => 'nullable|string|max:100',
            'medical_history' => 'nullable|string|max:1000',
        ]);

        PatientRequest::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            'blood_type' => $request->blood_type,
            'emergency_contact_name' => $request->emergency_contact_name,
            'emergency_contact_phone' => $request->emergency_contact_phone,
            'insurance_number' => $request->insurance_number,
            'medical_history' => $request->medical_history,
            'status' => 'pending'
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Permintaan data pasien berhasil diajukan. Admin akan meninjau dan menyetujui data Anda.');
    }

    /**
     * Display the user's patient request status.
     */
    public function status()
    {
        $user = Auth::user();
        
        $patientRequest = PatientRequest::where('user_id', $user->id)
            ->with('reviewedBy')
            ->latest()
            ->first();

        return view('patient-request.status', compact('patientRequest'));
    }
}
