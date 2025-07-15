@extends('layouts.app')

@section('title', 'Detail Permintaan Pasien')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-file-medical-alt"></i> Detail Permintaan Pasien
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6><strong>Status Permintaan:</strong></h6>
                            <p>{!! $request->status_badge !!}</p>
                        </div>
                        <div class="col-md-6">
                            <h6><strong>Tanggal Permintaan:</strong></h6>
                            <p>{{ $request->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6><strong>Diajukan Oleh:</strong></h6>
                            <p>{{ $request->user->name }} ({{ $request->user->email }})</p>
                        </div>
                        <div class="col-md-6">
                            <h6><strong>User ID:</strong></h6>
                            <p>#{{ $request->user_id }}</p>
                        </div>
                    </div>

                    <hr>

                    <h5><i class="fas fa-user"></i> Data Pasien yang Diajukan</h5>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Nama Lengkap:</strong></td>
                                    <td>{{ $request->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $request->email }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Nomor Telepon:</strong></td>
                                    <td>{{ $request->phone }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Lahir:</strong></td>
                                    <td>{{ $request->birth_date->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Jenis Kelamin:</strong></td>
                                    <td>{{ $request->gender == 'male' ? 'Laki-laki' : 'Perempuan' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Golongan Darah:</strong></td>
                                    <td>{{ $request->blood_type ?: '-' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Alamat:</strong></td>
                                    <td>{{ $request->address }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Kontak Darurat:</strong></td>
                                    <td>{{ $request->emergency_contact_name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Telepon Darurat:</strong></td>
                                    <td>{{ $request->emergency_contact_phone }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Nomor Asuransi:</strong></td>
                                    <td>{{ $request->insurance_number ?: '-' }}</td>
                                </tr>
                                @if($request->medical_history)
                                <tr>
                                    <td><strong>Riwayat Medis:</strong></td>
                                    <td>{{ $request->medical_history }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    @if($request->status !== 'pending')
                        <hr>
                        <h5><i class="fas fa-clipboard-check"></i> Informasi Review</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Direview Oleh:</strong></td>
                                        <td>{{ $request->reviewedBy->name ?? 'Admin' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tanggal Review:</strong></td>
                                        <td>{{ $request->reviewed_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                @if($request->admin_notes)
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Catatan Admin:</strong></td>
                                        <td>{{ $request->admin_notes }}</td>
                                    </tr>
                                </table>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-cogs"></i> Aksi
                    </h5>
                </div>
                <div class="card-body">
                    @if($request->status == 'pending')
                        <form action="{{ route('admin.patient-requests.approve', $request->id) }}" method="POST" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm w-100" onclick="return confirm('Apakah Anda yakin ingin menyetujui permintaan ini?')">
                                <i class="fas fa-check"></i> Setujui Permintaan
                            </button>
                        </form>

                        <button type="button" class="btn btn-danger btn-sm w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            <i class="fas fa-times"></i> Tolak Permintaan
                        </button>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            Permintaan ini sudah diproses pada {{ $request->reviewed_at->format('d/m/Y H:i') }}
                        </div>
                    @endif

                    <hr>

                    <a href="{{ route('admin.patient-requests.index') }}" class="btn btn-secondary btn-sm w-100">
                        <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                    </a>
                </div>
            </div>

            <!-- Quick Info Card -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-info"></i> Informasi Singkat
                    </h6>
                </div>
                <div class="card-body">
                    <small>
                        <strong>ID Permintaan:</strong> #{{ $request->id }}<br>
                        <strong>Dibuat:</strong> {{ $request->created_at->diffForHumans() }}<br>
                        <strong>Status:</strong> {{ ucfirst($request->status) }}
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">Tolak Permintaan Pasien</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.patient-requests.reject', $request->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="admin_notes" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="admin_notes" name="admin_notes" rows="4" required 
                                  placeholder="Berikan alasan mengapa permintaan ini ditolak..."></textarea>
                    </div>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Perhatian:</strong> Setelah ditolak, user dapat mengajukan permintaan baru dengan data yang telah diperbaiki.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times"></i> Tolak Permintaan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
