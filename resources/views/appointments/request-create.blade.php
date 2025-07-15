@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Request Appointment Baru</h4>
                    <small class="text-muted">Request ini akan direview oleh admin</small>
                </div>

                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(!$patient)
                        <div class="alert alert-warning">
                            <strong>Perhatian:</strong> Anda belum memiliki data pasien. Silakan buat data pasien terlebih dahulu.
                            <a href="{{ route('patients.create') }}" class="btn btn-sm btn-primary ms-2">Buat Data Pasien</a>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <strong>Pasien:</strong> {{ $patient->name }} ({{ $patient->email }})
                            <br><strong>Status:</strong> Akan otomatis dijadwalkan setelah disetujui admin
                        </div>

                        <form action="{{ route('appointments.store') }}" method="POST" id="appointmentForm">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="doctor_id" class="form-label">Dokter</label>
                                        <select class="form-control @error('doctor_id') is-invalid @enderror" 
                                                id="doctor_id" name="doctor_id" required>
                                            <option value="">Pilih Dokter</option>
                                            @foreach($doctors as $doctor)
                                                <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                                    Dr. {{ $doctor->name }} - {{ $doctor->specialty }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('doctor_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="schedule_id" class="form-label">Jadwal</label>
                                        <select class="form-control @error('schedule_id') is-invalid @enderror" 
                                                id="schedule_id" name="schedule_id" required>
                                            <option value="">Pilih Jadwal</option>
                                            @foreach($schedules as $schedule)
                                                <option value="{{ $schedule->id }}" data-doctor="{{ $schedule->doctor_id }}" {{ old('schedule_id') == $schedule->id ? 'selected' : '' }}>
                                                    {{ $schedule->day }} ({{ $schedule->start_time }} - {{ $schedule->end_time }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('schedule_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="appointment_date" class="form-label">Tanggal Appointment</label>
                                        <input type="date" class="form-control @error('appointment_date') is-invalid @enderror" 
                                               id="appointment_date" name="appointment_date" value="{{ old('appointment_date') }}" 
                                               min="{{ date('Y-m-d') }}" required>
                                        @error('appointment_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="appointment_time" class="form-label">Waktu Appointment</label>
                                        <input type="time" class="form-control @error('appointment_time') is-invalid @enderror" 
                                               id="appointment_time" name="appointment_time" value="{{ old('appointment_time') }}" required>
                                        @error('appointment_time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="symptoms" class="form-label">Keluhan/Gejala</label>
                                <textarea class="form-control @error('symptoms') is-invalid @enderror" 
                                          id="symptoms" name="symptoms" rows="4" placeholder="Jelaskan keluhan atau gejala yang dialami...">{{ old('symptoms') }}</textarea>
                                @error('symptoms')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="alert alert-warning">
                                <strong>Catatan:</strong>
                                <ul class="mb-0">
                                    <li>Request appointment akan direview oleh admin</li>
                                    <li>Status akan otomatis "Dijadwalkan" setelah disetujui</li>
                                    <li>Anda akan mendapat notifikasi setelah request diproses</li>
                                </ul>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="{{ route('appointments.index') }}" class="btn btn-secondary me-md-2">
                                    Kembali
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    Ajukan Request
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const doctorSelect = document.getElementById('doctor_id');
    const scheduleSelect = document.getElementById('schedule_id');
    const scheduleOptions = Array.from(scheduleSelect.querySelectorAll('option'));
    
    doctorSelect.addEventListener('change', function() {
        const selectedDoctorId = this.value;
        
        // Reset schedule options
        scheduleSelect.innerHTML = '<option value="">Pilih Jadwal</option>';
        
        if (selectedDoctorId) {
            // Filter schedules by selected doctor
            const filteredSchedules = scheduleOptions.filter(option => 
                option.getAttribute('data-doctor') === selectedDoctorId
            );
            
            filteredSchedules.forEach(option => {
                scheduleSelect.appendChild(option.cloneNode(true));
            });
        }
    });
});
</script>
@endsection
