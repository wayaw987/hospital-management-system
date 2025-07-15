@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="h3 mb-4">
            Dashboard
        </h1>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $stats['total_doctors'] }}</h4>
                        <p class="mb-0">Total Dokter</p>
                    </div>
                    <div class="align-self-center">
                        <!-- Icon removed -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $stats['total_patients'] }}</h4>
                        <p class="mb-0">Total Pasien</p>
                    </div>
                    <div class="align-self-center">
                        <!-- Icon removed -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $stats['total_appointments_today'] }}</h4>
                        <p class="mb-0">Janji Temu Hari Ini</p>
                    </div>
                    <div class="align-self-center">
                        <!-- Icon removed -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $stats['total_appointments_month'] }}</h4>
                        <p class="mb-0">Janji Temu Bulan Ini</p>
                    </div>
                    <div class="align-self-center">
                        <!-- Icon removed -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Today's Appointments -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-calendar-day"></i>                    Janji Temu Hari Ini ({{ \Carbon\Carbon::now('Asia/Jakarta')->format('d F Y') }})
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
