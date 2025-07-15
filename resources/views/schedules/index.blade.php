@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Jadwal Dokter</h4>
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('schedules.create') }}" class="btn btn-primary btn-sm float-end">Tambah Jadwal</a>
                    @endif
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Dokter</th>
                                    <th>Spesialis</th>
                                    <th>Hari</th>
                                    <th>Waktu</th>
                                    <th>Ruangan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($schedules as $schedule)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $schedule->doctor->name }}</td>
                                        <td>{{ $schedule->doctor->specialist }}</td>
                                        <td>{{ $schedule->day_name }}</td>
                                        <td>{{ $schedule->start_time }} - {{ $schedule->end_time }}</td>
                                        <td>{{ $schedule->room_number }}</td>
                                        <td>
                                            @if($schedule->is_available)
                                                <span class="badge bg-success">Tersedia</span>
                                            @else
                                                <span class="badge bg-danger">Tidak Tersedia</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('schedules.show', $schedule->id) }}" class="btn btn-info btn-sm">Lihat</a>
                                            @if(auth()->user()->role === 'admin')
                                                <a href="{{ route('schedules.edit', $schedule->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                                <form action="{{ route('schedules.destroy', $schedule->id) }}" method="POST" style="display: inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')">Hapus</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak ada jadwal</td>
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
