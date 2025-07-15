<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PatientEditRequest;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientEditRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $requests = PatientEditRequest::with(['patient', 'requestedBy', 'reviewedBy'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.edit-requests.index', compact('requests'));
    }

    /**
     * Display the specified resource.
     */
    public function show(PatientEditRequest $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $request->load(['patient', 'requestedBy', 'reviewedBy']);
        return view('admin.edit-requests.show', compact('request'));
    }

    /**
     * Approve the edit request and update patient data.
     */
    public function approve(PatientEditRequest $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        if ($request->status !== 'pending') {
            return redirect()->back()->with('error', 'Request sudah diproses sebelumnya.');
        }

        $patient = $request->patient;
        $changes = $request->requested_changes;

        // Update patient data
        $patient->update($changes);

        // Update request status
        $request->update([
            'status' => 'approved',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'admin_notes' => 'Approved by ' . Auth::user()->name
        ]);

        return redirect()->route('admin.edit-requests.index')
            ->with('success', 'Request berhasil disetujui dan data pasien telah diperbarui.');
    }

    /**
     * Reject the edit request.
     */
    public function reject(Request $request, PatientEditRequest $editRequest)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        if ($editRequest->status !== 'pending') {
            return redirect()->back()->with('error', 'Request sudah diproses sebelumnya.');
        }

        $request->validate([
            'admin_notes' => 'required|string|max:1000'
        ]);

        $editRequest->update([
            'status' => 'rejected',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'admin_notes' => $request->admin_notes
        ]);

        return redirect()->route('admin.edit-requests.index')
            ->with('success', 'Request berhasil ditolak.');
    }
}
