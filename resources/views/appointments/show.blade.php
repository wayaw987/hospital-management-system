@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Detail Janji Temu</h4>
                    <a href="{{ route('appointments.index') }}" class="btn btn-secondary btn-sm float-end">Kembali</a>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Pasien:</strong></td>
                                    <td>{{ $appointment->patient->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Telepon Pasien:</strong></td>
                                    <td>{{ $appointment->patient->phone }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Dokter:</strong></td>
                                    <td>{{ $appointment->doctor->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Spesialis:</strong></td>
                                    <td>{{ $appointment->doctor->specialist }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Tanggal:</strong></td>
                                    <td>{{ $appointment->appointment_date->format('d F Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Waktu:</strong></td>
                                    <td>{{ $appointment->appointment_time }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        @if($appointment->status == 'scheduled')
                                            <span class="badge bg-warning">Terjadwal</span>
                                        @elseif($appointment->status == 'confirmed')
                                            <span class="badge bg-info">Dikonfirmasi</span>
                                        @elseif($appointment->status == 'completed')
                                            <span class="badge bg-success">Selesai</span>
                                        @else
                                            <span class="badge bg-danger">Dibatalkan</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Dibuat:</strong></td>
                                    <td>{{ $appointment->created_at->format('d F Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($appointment->symptoms)
                        <div class="mt-3">
                            <h6>Keluhan:</h6>
                            <p>{{ $appointment->symptoms }}</p>
                        </div>
                    @endif

                    @if($appointment->notes)
                        <div class="mt-3">
                            <h6>Catatan:</h6>
                            <p>{{ $appointment->notes }}</p>
                        </div>
                    @endif

                    <div class="mt-3">
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('appointments.edit', $appointment->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('appointments.destroy', $appointment->id) }}" method="POST" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus janji temu ini?')">Hapus</button>
                            </form>
                        @else
                            <a href="{{ route('appointments.request.edit', $appointment->id) }}" class="btn btn-warning">Request Edit</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
