<?php

namespace App\Http\Controllers;

use App\Models\AppointmentRequest;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentRequestController extends Controller
{
    public function __construct()
    {
        // Middleware will be handled by routes
    }

    public function index()
    {
        // Only admin can access this
        if(Auth::user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Akses ditolak.');
        }

        $requests = AppointmentRequest::with(['user', 'doctor', 'schedule', 'appointment'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.appointment-requests.index', compact('requests'));
    }

    public function show(AppointmentRequest $request)
    {
        // Only admin can access this
        if(Auth::user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Akses ditolak.');
        }

        $request->load(['user', 'doctor', 'schedule', 'appointment']);
        $appointmentRequest = $request; // Create alias for the view
        return view('admin.appointment-requests.show', compact('appointmentRequest'));
    }

    public function approve(AppointmentRequest $request, Request $requestData)
    {
        // Only admin can access this
        if(Auth::user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Akses ditolak.');
        }

        $validatedData = $requestData->validate([
            'admin_notes' => 'nullable|string|max:500'
        ]);

        if($request->request_type === 'create') {
            // Create new appointment
            $appointment = Appointment::create([
                'doctor_id' => $request->doctor_id,
                'schedule_id' => $request->schedule_id,
                'patient_name' => $request->patient_name,
                'appointment_date' => $request->appointment_date,
                'appointment_time' => $request->appointment_time,
                'symptoms' => $request->symptoms,
                'status' => 'scheduled'
            ]);

            // Update request status
            $request->update([
                'status' => 'approved',
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
                'admin_notes' => $validatedData['admin_notes'] ?? null,
                'appointment_id' => $appointment->id
            ]);

            return redirect()->route('appointment-requests.index')
                ->with('success', 'Request janji temu berhasil disetujui dan janji temu telah dibuat.');

        } else if($request->request_type === 'edit') {
            // Update existing appointment
            $appointment = $request->appointment;
            $changes = $request->requested_changes;
            
            if(isset($changes['doctor_id'])) {
                $appointment->doctor_id = $changes['doctor_id'];
            }
            if(isset($changes['schedule_id'])) {
                $appointment->schedule_id = $changes['schedule_id'];
            }
            if(isset($changes['appointment_date'])) {
                $appointment->appointment_date = $changes['appointment_date'];
            }
            if(isset($changes['appointment_time'])) {
                $appointment->appointment_time = $changes['appointment_time'];
            }
            if(isset($changes['symptoms'])) {
                $appointment->symptoms = $changes['symptoms'];
            }
            
            $appointment->save();

            // Update request status
            $request->update([
                'status' => 'approved',
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
                'admin_notes' => $validatedData['admin_notes'] ?? null
            ]);

            return redirect()->route('appointment-requests.index')
                ->with('success', 'Request edit janji temu berhasil disetujui dan janji temu telah diupdate.');
        }

        return redirect()->route('appointment-requests.index')
            ->with('error', 'Tipe request tidak valid.');
    }

    public function reject(AppointmentRequest $request, Request $requestData)
    {
        // Only admin can access this
        if(Auth::user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Akses ditolak.');
        }

        $validatedData = $requestData->validate([
            'admin_notes' => 'required|string|max:500'
        ]);

        $request->update([
            'status' => 'rejected',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'admin_notes' => $validatedData['admin_notes']
        ]);

        return redirect()->route('appointment-requests.index')
            ->with('success', 'Request janji temu berhasil ditolak.');
    }
}
