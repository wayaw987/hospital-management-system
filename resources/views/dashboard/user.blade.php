@extends('layouts.app')

@section('title', 'Dashboard User')

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="h3 mb-4">
            Dashboard User
        </h1>
    </div>
</div>

<!-- Patient Request Status -->
<div class="row mb-4">
    <div class="col-12">
        @if(!$patientRequest)
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>Data Pasien Diperlukan!</strong> Anda belum mengajukan permintaan data pasien. 
                <a href="{{ route('patient.request.create') }}" class="btn btn-primary btn-sm ms-2">
                    <i class="fas fa-plus"></i> Lengkapi Data Pasien
                </a>
            </div>
        @elseif($patientRequest->status == 'pending')
            <div class="alert alert-info">
                <i class="fas fa-clock"></i>
                <strong>Menunggu Persetujuan!</strong> Permintaan data pasien Anda sedang ditinjau oleh admin.
                <a href="{{ route('patient.request.status') }}" class="btn btn-info btn-sm ms-2">
                    <i class="fas fa-eye"></i> Lihat Status
                </a>
            </div>
        @elseif($patientRequest->status == 'approved')
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <strong>Data Pasien Disetujui!</strong> Anda sekarang dapat membuat appointment.
                <a href="{{ route('appointments.request.create') }}" class="btn btn-success btn-sm ms-2">
                    <i class="fas fa-calendar-plus"></i> Buat Appointment
                </a>
            </div>
        @elseif($patientRequest->status == 'rejected')
            <div class="alert alert-danger">
                <i class="fas fa-times-circle"></i>
                <strong>Permintaan Ditolak!</strong> Silakan ajukan ulang dengan data yang benar.
                <a href="{{ route('patient.request.create') }}" class="btn btn-danger btn-sm ms-2">
                    <i class="fas fa-redo"></i> Ajukan Ulang
                </a>
            </div>
        @endif
    </div>
</div>

<div class="row">
    <!-- Today's Appointments -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    Janji Temu Hari Ini ({{ \Carbon\Carbon::now('Asia/Jakarta')->format('d F Y') }})
                </h5>
            </div>
            <div class="card-body">
                @if($todayAppointments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Waktu</th>
                                    <th>Pasien</th>
                                    <th>Dokter</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($todayAppointments as $appointment)
                                <tr>
                                    <td>{{ $appointment->formatted_time }}</td>
                                    <td>{{ $appointment->patient->name }}</td>
                                    <td>Dr. {{ $appointment->doctor->name }}</td>
                                    <td>
                                        <span class="badge bg-{{ $appointment->status == 'completed' ? 'success' : ($appointment->status == 'confirmed' ? 'primary' : 'warning') }}">
                                            {{ $appointment->status_text }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('appointments.show', $appointment->id) }}" class="btn btn-sm btn-outline-primary">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">Tidak ada janji temu hari ini.</p>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Popular Doctors -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    Dokter Terpopuler
                </h5>
            </div>
            <div class="card-body">
                @if($popularDoctors->count() > 0)
                    @foreach($popularDoctors as $doctor)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <strong>Dr. {{ $doctor->name }}</strong><br>
                            <small class="text-muted">{{ $doctor->specialist }}</small>
                        </div>
                        <span class="badge bg-primary">{{ $doctor->appointments_count }}</span>
                    </div>
                    <hr>
                    @endforeach
                @else
                    <p class="text-muted">Belum ada data dokter.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Upcoming Appointments -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    Janji Temu Mendatang (7 Hari Ke Depan)
                </h5>
            </div>
            <div class="card-body">
                @if($upcomingAppointments->count() > 0)
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No. Janji</th>
                                    <th>Tanggal & Waktu</th>
                                    <th>Pasien</th>
                                    <th>Dokter</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($upcomingAppointments as $appointment)
                                <tr>
                                    <td>{{ $appointment->appointment_number }}</td>
                                    <td>{{ $appointment->full_date_time }}</td>
                                    <td>{{ $appointment->patient->name }}</td>
                                    <td>Dr. {{ $appointment->doctor->name }}</td>
                                    <td>
                                        <span class="badge bg-{{ $appointment->status == 'completed' ? 'success' : ($appointment->status == 'confirmed' ? 'primary' : 'warning') }}">
                                            {{ $appointment->status_text }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('appointments.show', $appointment->id) }}" class="btn btn-sm btn-outline-primary">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">Tidak ada janji temu mendatang.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
