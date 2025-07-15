<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\AppointmentRequest;
use App\Models\PatientRequest;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Set timezone ke WIB
        Carbon::setLocale('id');
        
        // Check user role for different dashboard views
        if (Auth::user()->role === 'admin') {
            return $this->adminDashboard();
        } else {
            return $this->userDashboard();
        }
    }

    private function adminDashboard()
    {
        // Statistics untuk dashboard admin
        $stats = [
            'total_doctors' => Doctor::active()->count(),
            'total_patients' => Patient::active()->count(),
            'total_appointments_today' => Appointment::whereDate('appointment_date', Carbon::today('Asia/Jakarta'))->count(),
            'total_appointments_month' => Appointment::whereMonth('appointment_date', Carbon::now('Asia/Jakarta')->month)->count(),
            'pending_appointment_requests' => AppointmentRequest::where('status', 'pending')->count(),
        ];

        // Appointment hari ini
        $todayAppointments = Appointment::with(['patient', 'doctor'])
            ->whereDate('appointment_date', Carbon::today('Asia/Jakarta'))
            ->orderBy('appointment_time')
            ->get();

        // Appointment mendatang (7 hari ke depan)
        $upcomingAppointments = Appointment::with(['patient', 'doctor'])
            ->whereBetween('appointment_date', [Carbon::tomorrow('Asia/Jakarta'), Carbon::now('Asia/Jakarta')->addWeek()])
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->limit(10)
            ->get();

        // Dokter terpopuler (berdasarkan jumlah appointment)
        $popularDoctors = Doctor::withCount('appointments')
            ->orderBy('appointments_count', 'desc')
            ->limit(5)
            ->get();

        // Recent appointment requests
        $recentRequests = AppointmentRequest::with(['user', 'doctor'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.admin', compact(
            'stats',
            'todayAppointments', 
            'upcomingAppointments',
            'popularDoctors',
            'recentRequests'
        ));
    }

    private function userDashboard()
    {
        $userId = Auth::id();
        $userName = Auth::user()->name;
        
        // Check patient request status
        $patientRequest = PatientRequest::where('user_id', $userId)
            ->latest()
            ->first();
            
        // Get user's patient data if approved
        $userPatient = Auth::user()->patient;
        
        // Appointment hari ini hanya untuk user yang login
        $todayAppointments = collect();
        $upcomingAppointments = collect();
        
        if ($userPatient) {
            $todayAppointments = Appointment::with(['patient', 'doctor'])
                ->whereDate('appointment_date', Carbon::today('Asia/Jakarta'))
                ->where('patient_id', $userPatient->id)
                ->orderBy('appointment_time')
                ->get();

            // Appointment mendatang (7 hari ke depan) hanya untuk user yang login
            $upcomingAppointments = Appointment::with(['patient', 'doctor'])
                ->whereBetween('appointment_date', [Carbon::tomorrow('Asia/Jakarta'), Carbon::now('Asia/Jakarta')->addWeek()])
                ->where('patient_id', $userPatient->id)
                ->orderBy('appointment_date')
                ->orderBy('appointment_time')
                ->limit(10)
                ->get();
        }

        // Dokter terpopuler (berdasarkan jumlah appointment)
        $popularDoctors = Doctor::withCount('appointments')
            ->orderBy('appointments_count', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.user', compact(
            'todayAppointments', 
            'upcomingAppointments',
            'popularDoctors',
            'patientRequest',
            'userPatient'
        ));
    }
}
