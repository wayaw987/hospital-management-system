@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h4>Ajukan Edit Data Pasien</h4>
                    <a href="{{ route('patients.index') }}" class="btn btn-secondary btn-sm float-end">Kembali</a>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="alert alert-info">
                        Anda dapat mengajukan perubahan data pasien di bawah ini. Admin akan mereview permintaan Anda.
                    </div>

                    <form method="POST" action="{{ route('patients.store-edit-request', $patient->id) }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="mb-3">Data Saat Ini</h5>
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <p><strong>Nama:</strong> {{ $patient->name }}</p>
                                        <p><strong>Email:</strong> {{ $patient->email }}</p>
                                        <p><strong>Telepon:</strong> {{ $patient->phone }}</p>
                                        <p><strong>Alamat:</strong> {{ $patient->address }}</p>
                                        <p><strong>Tanggal Lahir:</strong> {{ $patient->birth_date->format('d/m/Y') }}</p>
                                        <p><strong>Jenis Kelamin:</strong> {{ $patient->gender_text }}</p>
                                        <p><strong>Golongan Darah:</strong> {{ $patient->blood_type }}</p>
                                        <p><strong>Kontak Darurat:</strong> {{ $patient->emergency_contact_name }} ({{ $patient->emergency_contact_phone }})</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h5 class="mb-3">Perubahan yang Diinginkan</h5>
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="name" name="requested_changes[name]" 
                                           value="{{ old('requested_changes.name', $patient->name) }}">
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="requested_changes[email]" 
                                           value="{{ old('requested_changes.email', $patient->email) }}">
                                </div>

                                <div class="mb-3">
                                    <label for="phone" class="form-label">Telepon</label>
                                    <input type="text" class="form-control" id="phone" name="requested_changes[phone]" 
                                           value="{{ old('requested_changes.phone', $patient->phone) }}">
                                </div>

                                <div class="mb-3">
                                    <label for="address" class="form-label">Alamat</label>
                                    <textarea class="form-control" id="address" name="requested_changes[address]" rows="3">{{ old('requested_changes.address', $patient->address) }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="birth_date" class="form-label">Tanggal Lahir</label>
                                    <input type="date" class="form-control" id="birth_date" name="requested_changes[birth_date]" 
                                           value="{{ old('requested_changes.birth_date', $patient->birth_date->format('Y-m-d')) }}">
                                </div>

                                <div class="mb-3">
                                    <label for="gender" class="form-label">Jenis Kelamin</label>
                                    <select class="form-select" id="gender" name="requested_changes[gender]">
                                        <option value="male" {{ old('requested_changes.gender', $patient->gender) == 'male' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="female" {{ old('requested_changes.gender', $patient->gender) == 'female' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="blood_type" class="form-label">Golongan Darah</label>
                                    <select class="form-select" id="blood_type" name="requested_changes[blood_type]">
                                        <option value="A" {{ old('requested_changes.blood_type', $patient->blood_type) == 'A' ? 'selected' : '' }}>A</option>
                                        <option value="B" {{ old('requested_changes.blood_type', $patient->blood_type) == 'B' ? 'selected' : '' }}>B</option>
                                        <option value="AB" {{ old('requested_changes.blood_type', $patient->blood_type) == 'AB' ? 'selected' : '' }}>AB</option>
                                        <option value="O" {{ old('requested_changes.blood_type', $patient->blood_type) == 'O' ? 'selected' : '' }}>O</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="emergency_contact_name" class="form-label">Nama Kontak Darurat</label>
                                    <input type="text" class="form-control" id="emergency_contact_name" name="requested_changes[emergency_contact_name]" 
                                           value="{{ old('requested_changes.emergency_contact_name', $patient->emergency_contact_name) }}">
                                </div>

                                <div class="mb-3">
                                    <label for="emergency_contact_phone" class="form-label">Telepon Kontak Darurat</label>
                                    <input type="text" class="form-control" id="emergency_contact_phone" name="requested_changes[emergency_contact_phone]" 
                                           value="{{ old('requested_changes.emergency_contact_phone', $patient->emergency_contact_phone) }}">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="reason" class="form-label">Alasan Perubahan *</label>
                            <textarea class="form-control @error('reason') is-invalid @enderror" 
                                      id="reason" name="reason" rows="3" required 
                                      placeholder="Jelaskan alasan mengapa data perlu diubah...">{{ old('reason') }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Ajukan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
