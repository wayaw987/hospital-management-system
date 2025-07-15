<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PatientRequest;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $requests = PatientRequest::with(['user', 'reviewedBy'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.patient-requests.index', compact('requests'));
    }

    /**
     * Display the specified resource.
     */
    public function show(PatientRequest $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        return view('admin.patient-requests.show', compact('request'));
    }

    /**
     * Approve the patient request and create patient.
     */
    public function approve(PatientRequest $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        if ($request->status !== 'pending') {
            return redirect()->back()->with('error', 'Request sudah diproses sebelumnya.');
        }

        // Create patient from request data
        $patientData = [
            'user_id' => $request->user_id,
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
            'patient_number' => 'P' . date('Ymd') . str_pad(Patient::count() + 1, 4, '0', STR_PAD_LEFT),
            'is_active' => true
        ];

        Patient::create($patientData);

        // Update request status
        $request->update([
            'status' => 'approved',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'admin_notes' => 'Approved by ' . Auth::user()->name
        ]);

        return redirect()->route('admin.patient-requests.index')
            ->with('success', 'Request berhasil disetujui dan pasien telah dibuat.');
    }

    /**
     * Reject the patient request.
     */
    public function reject(Request $request, PatientRequest $patientRequest)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        if ($patientRequest->status !== 'pending') {
            return redirect()->back()->with('error', 'Request sudah diproses sebelumnya.');
        }

        $request->validate([
            'admin_notes' => 'required|string|max:1000'
        ]);

        $patientRequest->update([
            'status' => 'rejected',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'admin_notes' => $request->admin_notes
        ]);

        return redirect()->route('admin.patient-requests.index')
            ->with('success', 'Request berhasil ditolak.');
    }
}
