<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Schedule;
use App\Models\AppointmentRequest;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->role === 'admin') {
            // Admin bisa lihat semua appointment
            $appointments = Appointment::with(['doctor', 'patient'])->latest()->get();
        } else {
            // User hanya bisa lihat appointment dengan patient yang linked ke user
            $userPatient = Auth::user()->patient;
            if ($userPatient) {
                $appointments = Appointment::with(['doctor', 'patient'])
                    ->where('patient_id', $userPatient->id)
                    ->latest()
                    ->get();
            } else {
                $appointments = collect();
            }
        }
        return view('appointments.index', compact('appointments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $doctors = Doctor::all();
        $schedules = Schedule::with('doctor')->where('is_available', true)->get();
        
        if (Auth::user()->role === 'admin') {
            // Admin bisa pilih pasien mana saja
            $patients = Patient::all();
            return view('appointments.create', compact('doctors', 'patients', 'schedules'));
        } else {
            // User hanya bisa buat appointment untuk diri sendiri
            $patient = Auth::user()->patient;
            if (!$patient) {
                return redirect()->route('patient.request.create')
                    ->with('error', 'Anda harus melengkapi data pasien terlebih dahulu sebelum membuat appointment.');
            }
            return view('appointments.request-create', compact('doctors', 'schedules', 'patient'));
        }
    }

    /**
     * Show the form for creating a new appointment request (for non-admin users).
     */
    public function requestCreate()
    {
        // Only allow non-admin users to access this
        if (Auth::user()->role === 'admin') {
            return redirect()->route('appointments.create');
        }

        $doctors = Doctor::all();
        $schedules = Schedule::with('doctor')->where('is_available', true)->get();
        $patient = Auth::user()->patient;
        
        if (!$patient) {
            return redirect()->route('patient.request.create')
                ->with('error', 'Anda harus melengkapi data pasien terlebih dahulu sebelum membuat appointment.');
        }
        
        return view('appointments.request-create', compact('doctors', 'schedules', 'patient'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (Auth::user()->role === 'admin') {
            // Admin bisa langsung buat appointment
            $request->validate([
                'patient_id' => 'required|exists:patients,id',
                'doctor_id' => 'required|exists:doctors,id',
                'schedule_id' => 'required|exists:schedules,id',
                'appointment_date' => 'required|date|after_or_equal:today',
                'appointment_time' => 'required|date_format:H:i',
                'status' => 'required|in:scheduled,confirmed,completed,cancelled',
                'symptoms' => 'nullable|string',
                'notes' => 'nullable|string',
            ]);

            // Generate appointment number
            $appointmentNumber = 'APT-' . date('Y') . '-' . str_pad(Appointment::count() + 1, 6, '0', STR_PAD_LEFT);

            $appointmentData = $request->all();
            $appointmentData['appointment_number'] = $appointmentNumber;

            Appointment::create($appointmentData);

            return redirect()->route('appointments.index')
                ->with('success', 'Janji temu berhasil dibuat.');
        } else {
            // User harus request approval
            return $this->storeAppointmentRequest($request);
        }
    }

    /**
     * Store appointment request for approval
     */
    public function storeAppointmentRequest(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'schedule_id' => 'required|exists:schedules,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i',
            'symptoms' => 'nullable|string',
        ]);

        // Check if user has patient data
        $patient = Auth::user()->patient;
        if (!$patient) {
            return redirect()->route('patient.request.create')
                ->with('error', 'Anda harus melengkapi data pasien terlebih dahulu sebelum membuat appointment.');
        }

        // Cek apakah user sudah punya request pending
        $existingRequest = AppointmentRequest::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->where('request_type', 'create')
            ->first();

        if ($existingRequest) {
            return redirect()->back()->with('error', 'Anda sudah memiliki request appointment yang sedang menunggu persetujuan.');
        }

        AppointmentRequest::create([
            'user_id' => Auth::id(),
            'doctor_id' => $request->doctor_id,
            'schedule_id' => $request->schedule_id,
            'patient_name' => $patient->name,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'symptoms' => $request->symptoms,
            'request_type' => 'create',
        ]);

        return redirect()->route('appointments.index')
            ->with('success', 'Request appointment berhasil diajukan. Menunggu persetujuan admin.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        $appointment->load(['doctor', 'patient']);
        return view('appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appointment $appointment)
    {
        if (Auth::user()->role === 'admin') {
            // Admin bisa langsung edit
            $doctors = Doctor::all();
            $patients = Patient::all();
            $schedules = Schedule::with('doctor')->where('is_available', true)->get();
            return view('appointments.edit', compact('appointment', 'doctors', 'patients', 'schedules'));
        } else {
            // User harus request approval
            return redirect()->route('appointments.request-edit', $appointment->id);
        }
    }

    /**
     * Show form to request edit appointment
     */
    public function requestEdit(Appointment $appointment)
    {
        // Cek apakah user bisa edit appointment ini (hanya appointment dengan patient name = user name)
        if ($appointment->patient->name !== Auth::user()->name) {
            return redirect()->route('appointments.index')->with('error', 'Anda tidak bisa edit appointment ini.');
        }

        $doctors = Doctor::all();
        $schedules = Schedule::with('doctor')->where('is_available', true)->get();
        return view('appointments.request-edit', compact('appointment', 'doctors', 'schedules'));
    }

    /**
     * Store edit request for appointment
     */
    public function storeEditRequest(Request $request, Appointment $appointment)
    {
        // Cek apakah user bisa edit appointment ini
        if ($appointment->patient->name !== Auth::user()->name) {
            return redirect()->route('appointments.index')->with('error', 'Anda tidak bisa edit appointment ini.');
        }

        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'schedule_id' => 'required|exists:schedules,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i',
            'symptoms' => 'nullable|string',
            'reason' => 'required|string|max:500'
        ]);

        // Cek apakah sudah ada request pending untuk appointment ini
        $existingRequest = AppointmentRequest::where('user_id', Auth::id())
            ->where('appointment_id', $appointment->id)
            ->where('status', 'pending')
            ->where('request_type', 'edit')
            ->first();

        if ($existingRequest) {
            return redirect()->back()->with('error', 'Anda sudah memiliki request edit untuk appointment ini yang sedang menunggu persetujuan.');
        }

        $requestedChanges = [
            'doctor_id' => $request->doctor_id,
            'schedule_id' => $request->schedule_id,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'symptoms' => $request->symptoms,
            'reason' => $request->reason
        ];

        AppointmentRequest::create([
            'user_id' => Auth::id(),
            'doctor_id' => $request->doctor_id,
            'schedule_id' => $request->schedule_id,
            'patient_name' => Auth::user()->name,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'symptoms' => $request->symptoms,
            'request_type' => 'edit',
            'appointment_id' => $appointment->id,
            'requested_changes' => $requestedChanges,
        ]);

        return redirect()->route('appointments.index')
            ->with('success', 'Request edit appointment berhasil diajukan. Menunggu persetujuan admin.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('appointments.index')->with('error', 'Akses ditolak. Hanya admin yang dapat mengubah appointment.');
        }

        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'schedule_id' => 'required|exists:schedules,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|date_format:H:i',
            'status' => 'required|in:scheduled,confirmed,completed,cancelled',
            'symptoms' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $appointment->update($request->all());

        return redirect()->route('appointments.index')
            ->with('success', 'Janji temu berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return redirect()->route('appointments.index')
            ->with('success', 'Janji temu berhasil dihapus.');
    }
}
