@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Detail Dokter</h4>
                    <a href="{{ route('doctors.index') }}" class="btn btn-secondary btn-sm float-end">Kembali</a>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Nama:</strong></td>
                                    <td>{{ $doctor->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $doctor->email }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Telepon:</strong></td>
                                    <td>{{ $doctor->phone }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Spesialis:</strong></td>
                                    <td>{{ $doctor->specialist }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Lahir:</strong></td>
                                    <td>{{ \Carbon\Carbon::parse($doctor->birth_date)->format('d F Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Jenis Kelamin:</strong></td>
                                    <td>{{ $doctor->gender == 'male' ? 'Laki-laki' : 'Perempuan' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Nomor Lisensi:</strong></td>
                                    <td>{{ $doctor->license_number }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Pengalaman:</strong></td>
                                    <td>{{ $doctor->experience_years }} tahun</td>
                                </tr>
                                <tr>
                                    <td><strong>Biaya Konsultasi:</strong></td>
                                    <td>Rp {{ number_format($doctor->consultation_fee, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Pendidikan:</strong></td>
                                    <td>{{ $doctor->education }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Alamat:</strong></td>
                                    <td>{{ $doctor->address }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('doctors.edit', $doctor->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('doctors.destroy', $doctor->id) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
