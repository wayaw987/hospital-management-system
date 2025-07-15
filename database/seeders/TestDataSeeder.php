<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Schedule;
use App\Models\Appointment;

class TestDataSeeder extends Seeder
{
    public function run()
    {
        // Create test doctor
        $doctor = Doctor::create([
            'name' => 'Dr. Test',
            'email' => 'doctor@test.com',
            'phone' => '08123456789',
            'specialist' => 'Umum',
            'address' => 'Jl. Test No. 1',
            'birth_date' => '1980-01-01',
            'gender' => 'male',
            'license_number' => 'DOC001',
            'experience_years' => 10,
            'consultation_fee' => 150000,
            'education' => 'S1 Kedokteran',
        ]);

        // Create test patient
        $patient = Patient::create([
            'name' => 'Pasien Test',
            'email' => 'patient@test.com',
            'phone' => '08987654321',
            'address' => 'Jl. Patient No. 1',
            'birth_date' => '1990-01-01',
            'gender' => 'female',
            'blood_type' => 'A+',
            'emergency_contact' => 'Emergency Contact',
            'insurance_number' => 'INS001',
            'medical_history' => 'Tidak ada',
            'allergies' => 'Tidak ada',
        ]);

        // Create test schedule
        $schedule = Schedule::create([
            'doctor_id' => $doctor->id,
            'day_of_week' => 'Senin',
            'start_time' => '08:00',
            'end_time' => '12:00',
            'room_number' => 'R001',
            'is_active' => true,
        ]);

        // Create test appointment
        Appointment::create([
            'doctor_id' => $doctor->id,
            'patient_id' => $patient->id,
            'schedule_id' => $schedule->id,
            'appointment_date' => '2025-07-17',
            'appointment_time' => '09:00',
            'status' => 'scheduled',
            'notes' => 'Appointment test',
            'complaint' => 'Sakit kepala',
        ]);
    }
}
