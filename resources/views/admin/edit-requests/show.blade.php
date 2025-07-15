@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Detail Edit Request</h4>
                    <a href="{{ route('admin.edit-requests.index') }}" class="btn btn-secondary btn-sm float-end">Kembali</a>
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

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Informasi Request</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Pasien:</strong></td>
                                            <td>{{ $request->patient->name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Diminta oleh:</strong></td>
                                            <td>{{ $request->requestedBy->name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tanggal Request:</strong></td>
                                            <td>{{ $request->created_at->format('d M Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Status:</strong></td>
                                            <td>{!! $request->status_badge !!}</td>
                                        </tr>
                                        @if($request->reviewed_at)
                                        <tr>
                                            <td><strong>Direview oleh:</strong></td>
                                            <td>{{ $request->reviewedBy->name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tanggal Review:</strong></td>
                                            <td>{{ $request->reviewed_at->format('d M Y H:i') }}</td>
                                        </tr>
                                        @endif
                                        @if($request->admin_notes)
                                        <tr>
                                            <td><strong>Catatan Admin:</strong></td>
                                            <td>{{ $request->admin_notes }}</td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            @if($request->status === 'pending')
                            <div class="card">
                                <div class="card-header">
                                    <h5>Aksi</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <form action="{{ route('admin.edit-requests.approve', $request) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-block" onclick="return confirm('Apakah Anda yakin ingin menyetujui request ini?')">
                                                Setujui
                                            </button>
                                        </form>
                                        
                                        <button type="button" class="btn btn-danger btn-block" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                            Tolak
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Perubahan Data</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Field</th>
                                                    <th>Data Saat Ini</th>
                                                    <th>Data yang Diminta</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($request->requested_changes as $field => $newValue)
                                                <tr>
                                                    <td><strong>{{ ucfirst(str_replace('_', ' ', $field)) }}</strong></td>
                                                    <td>{{ $request->patient->$field ?? '-' }}</td>
                                                    <td class="text-primary"><strong>{{ $newValue }}</strong></td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">Tolak Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.edit-requests.reject', $request) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="admin_notes" class="form-label">Alasan Penolakan</label>
                        <textarea class="form-control" id="admin_notes" name="admin_notes" rows="4" placeholder="Masukkan alasan penolakan..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak Request</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
