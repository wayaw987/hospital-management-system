@extends('layouts.app')

@section('title', 'Import Data Excel')

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="h3 mb-4">
            Import Data dari Excel
        </h1>
    </div>
</div>

<div class="row">
    <!-- Import Doctors -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">
                    Import Data Dokter
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted">Upload file Excel untuk import data dokter.</p>
                
                <form action="{{ route('import.doctors') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="doctor_file" class="form-label">Pilih File Excel/CSV</label>
                        <input type="file" class="form-control" id="doctor_file" name="file" 
                               accept=".csv,.xlsx,.xls" required>
                        <div class="form-text">Format: .csv, .xlsx, .xls (Max: 2MB)</div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        Import Dokter
                    </button>
                </form>
                
                <hr>
                <h6>Format Template:</h6>
                <ol class="small">
                    <li>Nama</li>
                    <li>Email</li>
                    <li>Telepon</li>
                    <li>Spesialisasi</li>
                    <li>Alamat</li>
                    <li>Tanggal Lahir (YYYY-MM-DD)</li>
                    <li>Jenis Kelamin (male/female)</li>
                    <li>Nomor Izin Praktek</li>
                    <li>Tahun Pengalaman</li>
                    <li>Biaya Konsultasi</li>
                    <li>Pendidikan</li>
                </ol>
            </div>
        </div>
    </div>
    
    <!-- Import Patients -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">
                    Import Data Pasien
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted">Upload file Excel untuk import data pasien.</p>
                
                <form action="{{ route('import.patients') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="patient_file" class="form-label">Pilih File Excel/CSV</label>
                        <input type="file" class="form-control" id="patient_file" name="file" 
                               accept=".csv,.xlsx,.xls" required>
                        <div class="form-text">Format: .csv, .xlsx, .xls (Max: 2MB)</div>
                    </div>
                    
                    <button type="submit" class="btn btn-success">
                        Import Pasien
                    </button>
                </form>
                
                <hr>
                <h6>Format Template:</h6>
                <ol class="small">
                    <li>Nama</li>
                    <li>Email</li>
                    <li>Telepon</li>
                    <li>Alamat</li>
                    <li>Tanggal Lahir (YYYY-MM-DD)</li>
                    <li>Jenis Kelamin (male/female)</li>
                    <li>Golongan Darah</li>
                    <li>Alergi</li>
                    <li>Riwayat Penyakit</li>
                    <li>Nama Kontak Darurat</li>
                    <li>Telepon Kontak Darurat</li>
                    <li>Nomor Asuransi</li>
                </ol>
            </div>
        </div>
    </div>
    
    <!-- Import Schedules -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header bg-warning text-white">
                <h5 class="card-title mb-0">
                    Import Jadwal Dokter
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted">Upload file Excel untuk import jadwal dokter.</p>
                
                <form action="{{ route('import.schedules') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="schedule_file" class="form-label">Pilih File Excel/CSV</label>
                        <input type="file" class="form-control" id="schedule_file" name="file" 
                               accept=".csv,.xlsx,.xls" required>
                        <div class="form-text">Format: .csv, .xlsx, .xls (Max: 2MB)</div>
                    </div>
                    
                    <button type="submit" class="btn btn-warning">
                        Import Jadwal
                    </button>
                </form>
                
                <hr>
                <h6>Format Template:</h6>
                <ol class="small">
                    <li>Nama/Email Dokter</li>
                    <li>Hari (monday-sunday)</li>
                    <li>Jam Mulai (HH:MM)</li>
                    <li>Jam Selesai (HH:MM)</li>
                    <li>Nomor Ruang</li>
                    <li>Catatan (opsional)</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Instructions -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    Petunjuk Import
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Persyaratan File:</h6>
                        <ul>
                            <li>Format file yang didukung: .csv, .xlsx, .xls</li>
                            <li>Ukuran maksimal file: 2MB</li>
                            <li>Baris pertama harus berisi header/judul kolom</li>
                            <li>Data dimulai dari baris kedua</li>
                            <li>Untuk CSV: gunakan koma (,) sebagai separator</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>Tips Import:</h6>
                        <ul>
                            <li>Pastikan format tanggal: YYYY-MM-DD (contoh: 2023-12-31)</li>
                            <li>Jenis kelamin hanya: male atau female</li>
                            <li>Email harus unik untuk dokter dan pasien</li>
                            <li>Nomor izin praktek dokter harus unik</li>
                            <li>Untuk jadwal, pastikan dokter sudah ada di sistem</li>
                        </ul>
                    </div>
                </div>
                
                <div class="alert alert-info mt-3">
                    <!-- Icon removed -->
                    <strong>Tip:</strong> Buat data di Excel dan bisa langsung upload file .xlsx, 
                    atau save as CSV jika lebih nyaman. Sistem mendukung kedua format!
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Validate file size before upload
    document.querySelectorAll('input[type="file"]').forEach(function(input) {
        input.addEventListener('change', function() {
            const file = this.files[0];
            if (file && file.size > 2 * 1024 * 1024) {
                alert('Ukuran file terlalu besar. Maksimal 2MB.');
                this.value = '';
            }
        });
    });
</script>
@endsection
