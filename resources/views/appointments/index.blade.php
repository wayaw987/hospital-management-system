@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Janji Temu</h4>
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('appointments.create') }}" class="btn btn-primary btn-sm float-end">Buat Janji Temu</a>
                    @else
                        <a href="{{ route('appointments.request.create') }}" class="btn btn-primary btn-sm float-end">Request Janji Temu</a>
                    @endif
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Pasien</th>
                                    <th>Dokter</th>
                                    <th>Tanggal</th>
                                    <th>Waktu</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($appointments as $appointment)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $appointment->patient->name }}</td>
                                        <td>{{ $appointment->doctor->name }}</td>
                                        <td>{{ $appointment->appointment_date->format('d F Y') }}</td>
                                        <td>{{ $appointment->appointment_time }}</td>
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
                                        <td>
                                            <a href="{{ route('appointments.show', $appointment->id) }}" class="btn btn-info btn-sm">Lihat</a>
                                            @if(auth()->user()->role === 'admin')
                                                <a href="{{ route('appointments.edit', $appointment->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                                <form action="{{ route('appointments.destroy', $appointment->id) }}" method="POST" style="display: inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus janji temu ini?')">Hapus</button>
                                                </form>
                                            @else
                                                <a href="{{ route('appointments.request.edit', $appointment->id) }}" class="btn btn-warning btn-sm">Request Edit</a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada janji temu</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
