@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Detail Pasien</h4>
                    <a href="{{ route('patients.index') }}" class="btn btn-secondary btn-sm float-end">Kembali</a>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Nama:</strong></td>
                                    <td>{{ $patient->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $patient->email }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Telepon:</strong></td>
                                    <td>{{ $patient->phone }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Lahir:</strong></td>
                                    <td>{{ \Carbon\Carbon::parse($patient->birth_date)->format('d F Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Jenis Kelamin:</strong></td>
                                    <td>{{ $patient->gender == 'male' ? 'Laki-laki' : 'Perempuan' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Golongan Darah:</strong></td>
                                    <td>{{ $patient->blood_type }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Nama Kontak Darurat:</strong></td>
                                    <td>{{ $patient->emergency_contact_name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Telepon Kontak Darurat:</strong></td>
                                    <td>{{ $patient->emergency_contact_phone }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Nomor Asuransi:</strong></td>
                                    <td>{{ $patient->insurance_number ?? 'Tidak ada' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Alamat:</strong></td>
                                    <td>{{ $patient->address }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Riwayat Medis:</strong></td>
                                    <td>{{ $patient->medical_history ?? 'Tidak ada' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Alergi:</strong></td>
                                    <td>{{ $patient->allergies ?? 'Tidak ada' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('patients.destroy', $patient->id) }}" method="POST" style="display: inline-block;">
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
