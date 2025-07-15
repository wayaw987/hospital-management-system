@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h4>Detail Request Janji Temu</h4>
                    <a href="{{ route('appointment-requests.index') }}" class="btn btn-secondary btn-sm float-end">Kembali</a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Informasi Request</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td><strong>User:</strong></td>
                                            <td>{{ $appointmentRequest->user->name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Email:</strong></td>
                                            <td>{{ $appointmentRequest->user->email }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tipe Request:</strong></td>
                                            <td>
                                                @if($appointmentRequest->request_type === 'create')
                                                    <span class="badge bg-primary">Buat Baru</span>
                                                @else
                                                    <span class="badge bg-warning">Edit</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Status:</strong></td>
                                            <td>
                                                @if($appointmentRequest->status === 'pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @elseif($appointmentRequest->status === 'approved')
                                                    <span class="badge bg-success">Disetujui</span>
                                                @else
                                                    <span class="badge bg-danger">Ditolak</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tanggal Request:</strong></td>
                                            <td>{{ $appointmentRequest->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                        @if($appointmentRequest->reviewed_at)
                                            <tr>
                                                <td><strong>Tanggal Review:</strong></td>
                                                <td>{{ $appointmentRequest->reviewed_at->format('d/m/Y H:i') }}</td>
                                            </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Detail Janji Temu</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td><strong>Nama Pasien:</strong></td>
                                            <td>{{ $appointmentRequest->patient_name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Dokter:</strong></td>
                                            <td>{{ $appointmentRequest->doctor->name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Spesialisasi:</strong></td>
                                            <td>{{ $appointmentRequest->doctor->specialization }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Jadwal:</strong></td>
                                            <td>{{ $appointmentRequest->schedule->day }} ({{ $appointmentRequest->schedule->start_time }} - {{ $appointmentRequest->schedule->end_time }})</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tanggal:</strong></td>
                                            <td>{{ $appointmentRequest->appointment_date }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Jam:</strong></td>
                                            <td>{{ $appointmentRequest->appointment_time }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Keluhan:</strong></td>
                                            <td>{{ $appointmentRequest->symptoms }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($appointmentRequest->request_type === 'edit' && $appointmentRequest->requested_changes)
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5>Perubahan yang Diminta</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Field</th>
                                                <th>Nilai Lama</th>
                                                <th>Nilai Baru</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($appointmentRequest->requested_changes as $field => $newValue)
                                                <tr>
                                                    <td>
                                                        @switch($field)
                                                            @case('doctor_id')
                                                                Dokter
                                                                @break
                                                            @case('schedule_id')
                                                                Jadwal
                                                                @break
                                                            @case('appointment_date')
                                                                Tanggal
                                                                @break
                                                            @case('appointment_time')
                                                                Jam
                                                                @break
                                                            @case('symptoms')
                                                                Keluhan
                                                                @break
                                                            @default
                                                                {{ $field }}
                                                        @endswitch
                                                    </td>
                                                    <td>
                                                        @switch($field)
                                                            @case('doctor_id')
                                                                {{ $appointmentRequest->appointment->doctor->name }}
                                                                @break
                                                            @case('schedule_id')
                                                                {{ $appointmentRequest->appointment->schedule->day }} ({{ $appointmentRequest->appointment->schedule->start_time }} - {{ $appointmentRequest->appointment->schedule->end_time }})
                                                                @break
                                                            @case('appointment_date')
                                                                {{ $appointmentRequest->appointment->appointment_date }}
                                                                @break
                                                            @case('appointment_time')
                                                                {{ $appointmentRequest->appointment->appointment_time }}
                                                                @break
                                                            @case('symptoms')
                                                                {{ $appointmentRequest->appointment->symptoms }}
                                                                @break
                                                            @default
                                                                {{ $appointmentRequest->appointment->$field }}
                                                        @endswitch
                                                    </td>
                                                    <td>
                                                        @switch($field)
                                                            @case('doctor_id')
                                                                {{ \App\Models\Doctor::find($newValue)->name }}
                                                                @break
                                                            @case('schedule_id')
                                                                @php
                                                                    $schedule = \App\Models\Schedule::find($newValue);
                                                                @endphp
                                                                {{ $schedule->day }} ({{ $schedule->start_time }} - {{ $schedule->end_time }})
                                                                @break
                                                            @default
                                                                {{ $newValue }}
                                                        @endswitch
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($appointmentRequest->admin_notes)
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5>Catatan Admin</h5>
                            </div>
                            <div class="card-body">
                                <p>{{ $appointmentRequest->admin_notes }}</p>
                            </div>
                        </div>
                    @endif

                    @if($appointmentRequest->status === 'pending')
                        <div class="mt-3">
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveModal">
                                Setujui Request
                            </button>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                Tolak Request
                            </button>
                        </div>

                        <!-- Approve Modal -->
                        <div class="modal fade" id="approveModal" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Setujui Request</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('appointment-requests.approve', $appointmentRequest->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <p>Apakah Anda yakin ingin menyetujui request ini?</p>
                                            <div class="mb-3">
                                                <label for="admin_notes" class="form-label">Catatan Admin (Opsional)</label>
                                                <textarea class="form-control" id="admin_notes" name="admin_notes" rows="3" placeholder="Masukkan catatan..."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-success">Setujui</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Reject Modal -->
                        <div class="modal fade" id="rejectModal" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Tolak Request</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('appointment-requests.reject', $appointmentRequest->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <p>Apakah Anda yakin ingin menolak request ini?</p>
                                            <div class="mb-3">
                                                <label for="admin_notes" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                                                <textarea class="form-control" id="admin_notes" name="admin_notes" rows="3" placeholder="Masukkan alasan penolakan..." required></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-danger">Tolak</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
