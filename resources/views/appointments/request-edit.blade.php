@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Request Edit Appointment</h4>
                    <small class="text-muted">Request ini akan direview oleh admin</small>
                </div>

                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6>Data Saat Ini</h6>
                                </div>
                                <div class="card-body">
                                    <p><strong>Pasien:</strong> {{ $appointment->patient->name }}</p>
                                    <p><strong>Dokter:</strong> Dr. {{ $appointment->doctor->name }}</p>
                                    <p><strong>Jadwal:</strong> {{ $appointment->schedule->day }} ({{ $appointment->schedule->start_time }} - {{ $appointment->schedule->end_time }})</p>
                                    <p><strong>Tanggal:</strong> {{ $appointment->appointment_date->format('d/m/Y') }}</p>
                                    <p><strong>Waktu:</strong> {{ $appointment->appointment_time }}</p>
                                    <p><strong>Status:</strong> {{ $appointment->status }}</p>
                                    @if($appointment->symptoms)
                                        <p><strong>Keluhan:</strong> {{ $appointment->symptoms }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6>Request Perubahan</h6>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('appointments.request.update', $appointment->id) }}" method="POST">
                                        @csrf
                                        
                                        <div class="mb-3">
                                            <label for="doctor_id" class="form-label">Dokter</label>
                                            <select class="form-control @error('doctor_id') is-invalid @enderror" 
                                                    id="doctor_id" name="doctor_id" required>
                                                <option value="">Pilih Dokter</option>
                                                @foreach($doctors as $doctor)
                                                    <option value="{{ $doctor->id }}" 
                                                            {{ old('doctor_id', $appointment->doctor_id) == $doctor->id ? 'selected' : '' }}>
                                                        Dr. {{ $doctor->name }} - {{ $doctor->specialty }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('doctor_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="schedule_id" class="form-label">Jadwal</label>
                                            <select class="form-control @error('schedule_id') is-invalid @enderror" 
                                                    id="schedule_id" name="schedule_id" required>
                                                <option value="">Pilih Jadwal</option>
                                                @foreach($schedules as $schedule)
                                                    <option value="{{ $schedule->id }}" data-doctor="{{ $schedule->doctor_id }}"
                                                            {{ old('schedule_id', $appointment->schedule_id) == $schedule->id ? 'selected' : '' }}>
                                                        {{ $schedule->day }} ({{ $schedule->start_time }} - {{ $schedule->end_time }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('schedule_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="appointment_date" class="form-label">Tanggal Appointment</label>
                                            <input type="date" class="form-control @error('appointment_date') is-invalid @enderror" 
                                                   id="appointment_date" name="appointment_date" 
                                                   value="{{ old('appointment_date', $appointment->appointment_date->format('Y-m-d')) }}" 
                                                   min="{{ date('Y-m-d') }}" required>
                                            @error('appointment_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="appointment_time" class="form-label">Waktu Appointment</label>
                                            <input type="time" class="form-control @error('appointment_time') is-invalid @enderror" 
                                                   id="appointment_time" name="appointment_time" 
                                                   value="{{ old('appointment_time', substr($appointment->appointment_time, 0, 5)) }}" required>
                                            @error('appointment_time')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="symptoms" class="form-label">Keluhan/Gejala</label>
                                            <textarea class="form-control @error('symptoms') is-invalid @enderror" 
                                                      id="symptoms" name="symptoms" rows="3" 
                                                      placeholder="Jelaskan keluhan atau gejala yang dialami...">{{ old('symptoms', $appointment->symptoms) }}</textarea>
                                            @error('symptoms')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="reason" class="form-label">Alasan Perubahan</label>
                                            <textarea class="form-control @error('reason') is-invalid @enderror" 
                                                      id="reason" name="reason" rows="3" 
                                                      placeholder="Jelaskan alasan mengapa ingin mengubah appointment..." required>{{ old('reason') }}</textarea>
                                            @error('reason')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                            <a href="{{ route('appointments.index') }}" class="btn btn-secondary me-md-2">
                                                Kembali
                                            </a>
                                            <button type="submit" class="btn btn-primary">
                                                Ajukan Request Edit
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
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
