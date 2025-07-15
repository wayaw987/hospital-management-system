@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Detail Jadwal</h4>
                    <a href="{{ route('schedules.index') }}" class="btn btn-secondary btn-sm float-end">Kembali</a>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Dokter:</strong></td>
                                    <td>{{ $schedule->doctor->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Spesialis:</strong></td>
                                    <td>{{ $schedule->doctor->specialist }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Hari:</strong></td>
                                    <td>{{ $schedule->day_name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Waktu:</strong></td>
                                    <td>{{ $schedule->start_time }} - {{ $schedule->end_time }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Ruangan:</strong></td>
                                    <td>{{ $schedule->room_number }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        @if($schedule->is_available)
                                            <span class="badge bg-success">Tersedia</span>
                                        @else
                                            <span class="badge bg-danger">Tidak Tersedia</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Dibuat:</strong></td>
                                    <td>{{ $schedule->created_at->format('d F Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Diperbarui:</strong></td>
                                    <td>{{ $schedule->updated_at->format('d F Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('schedules.edit', $schedule->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('schedules.destroy', $schedule->id) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
