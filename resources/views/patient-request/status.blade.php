@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-file-medical-alt"></i> Status Permintaan Data Pasien
                    </h4>
                </div>
                <div class="card-body">
                    @if($patientRequest)
                        <div class="mb-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Status Permintaan:</h5>
                                    <p>{!! $patientRequest->status_badge !!}</p>
                                </div>
                                <div class="col-md-6">
                                    <h5>Tanggal Permintaan:</h5>
                                    <p>{{ $patientRequest->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>

                        @if($patientRequest->status == 'pending')
                            <div class="alert alert-warning">
                                <i class="fas fa-clock"></i>
                                <strong>Menunggu Persetujuan!</strong> Permintaan data pasien Anda sedang ditinjau oleh admin. 
                                Harap menunggu hingga admin menyetujui data Anda.
                            </div>
                        @elseif($patientRequest->status == 'approved')
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i>
                                <strong>Disetujui!</strong> Data pasien Anda telah disetujui oleh admin. 
                                Anda sekarang dapat membuat appointment.
                            </div>
                        @elseif($patientRequest->status == 'rejected')
                            <div class="alert alert-danger">
                                <i class="fas fa-times-circle"></i>
                                <strong>Ditolak!</strong> Permintaan data pasien Anda ditolak oleh admin.
                            </div>
                        @endif

                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Data yang Diajukan</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-borderless">
                                            <tr>
                                                <td><strong>Nama:</strong></td>
                                                <td>{{ $patientRequest->name }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Email:</strong></td>
                                                <td>{{ $patientRequest->email }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Telepon:</strong></td>
                                                <td>{{ $patientRequest->phone }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Tanggal Lahir:</strong></td>
                                                <td>{{ $patientRequest->birth_date->format('d/m/Y') }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Jenis Kelamin:</strong></td>
                                                <td>{{ $patientRequest->gender == 'male' ? 'Laki-laki' : 'Perempuan' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-borderless">
                                            <tr>
                                                <td><strong>Golongan Darah:</strong></td>
                                                <td>{{ $patientRequest->blood_type ?: '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Kontak Darurat:</strong></td>
                                                <td>{{ $patientRequest->emergency_contact_name }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Telepon Darurat:</strong></td>
                                                <td>{{ $patientRequest->emergency_contact_phone }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Asuransi:</strong></td>
                                                <td>{{ $patientRequest->insurance_number ?: '-' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-12">
                                        <table class="table table-borderless">
                                            <tr>
                                                <td><strong>Alamat:</strong></td>
                                                <td>{{ $patientRequest->address }}</td>
                                            </tr>
                                            @if($patientRequest->medical_history)
                                            <tr>
                                                <td><strong>Riwayat Medis:</strong></td>
                                                <td>{{ $patientRequest->medical_history }}</td>
                                            </tr>
                                            @endif
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($patientRequest->status !== 'pending')
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h5 class="mb-0">Informasi Review</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <td><strong>Direview Oleh:</strong></td>
                                                    <td>{{ $patientRequest->reviewedBy->name ?? 'Admin' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Tanggal Review:</strong></td>
                                                    <td>{{ $patientRequest->reviewed_at->format('d/m/Y H:i') }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            @if($patientRequest->admin_notes)
                                                <table class="table table-borderless">
                                                    <tr>
                                                        <td><strong>Catatan Admin:</strong></td>
                                                        <td>{{ $patientRequest->admin_notes }}</td>
                                                    </tr>
                                                </table>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($patientRequest->status == 'rejected')
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3">
                                <a href="{{ route('patient.request.create') }}" class="btn btn-primary">
                                    <i class="fas fa-redo"></i> Ajukan Ulang
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Belum Ada Permintaan!</strong> Anda belum mengajukan permintaan data pasien.
                        </div>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('patient.request.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Ajukan Permintaan
                            </a>
                        </div>
                    @endif

                    <div class="d-grid gap-2 d-md-flex justify-content-md-start mt-3">
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
