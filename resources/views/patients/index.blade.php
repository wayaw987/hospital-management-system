@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Data Pasien</h4>
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('patients.create') }}" class="btn btn-primary btn-sm float-end">Tambah Pasien</a>
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
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Telepon</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Golongan Darah</th>
                                    <th>Kontak Darurat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($patients as $patient)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $patient->name }}</td>
                                        <td>{{ $patient->email }}</td>
                                        <td>{{ $patient->phone }}</td>
                                        <td>{{ $patient->gender == 'male' ? 'Laki-laki' : 'Perempuan' }}</td>
                                        <td>{{ $patient->blood_type }}</td>
                                        <td>{{ $patient->emergency_contact_name }}</td>
                                        <td>
                                            <a href="{{ route('patients.show', $patient->id) }}" class="btn btn-info btn-sm">Lihat</a>
                                            
                                            @if(Auth::user()->role === 'admin')
                                                <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                                <form action="{{ route('patients.destroy', $patient->id) }}" method="POST" style="display: inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</button>
                                                </form>
                                            @else
                                                <a href="{{ route('patients.request-edit', $patient->id) }}" class="btn btn-success btn-sm">Request Edit</a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak ada data pasien</td>
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
