@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Request Janji Temu</h4>
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
                                    <th>User</th>
                                    <th>Nama Pasien</th>
                                    <th>Dokter</th>
                                    <th>Tanggal</th>
                                    <th>Jam</th>
                                    <th>Tipe</th>
                                    <th>Status</th>
                                    <th>Tanggal Request</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($requests as $request)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $request->user->name }}</td>
                                        <td>{{ $request->patient_name }}</td>
                                        <td>{{ $request->doctor->name }}</td>
                                        <td>{{ $request->appointment_date->format('d/m/Y') }}</td>
                                        <td>{{ substr($request->appointment_time, 0, 5) }}</td>
                                        <td>
                                            @if($request->request_type === 'create')
                                                <span class="badge bg-primary">Buat Baru</span>
                                            @else
                                                <span class="badge bg-warning">Edit</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($request->status === 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($request->status === 'approved')
                                                <span class="badge bg-success">Disetujui</span>
                                            @else
                                                <span class="badge bg-danger">Ditolak</span>
                                            @endif
                                        </td>
                                        <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <a href="{{ route('appointment-requests.show', $request->id) }}" class="btn btn-info btn-sm">Detail</a>
                                            @if($request->status === 'pending')
                                                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#approveModal{{ $request->id }}">
                                                    Setujui
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $request->id }}">
                                                    Tolak
                                                </button>
                                            @endif
                                        </td>
                                    </tr>

                                    <!-- Approve Modal -->
                                    <div class="modal fade" id="approveModal{{ $request->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Setujui Request</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('appointment-requests.approve', $request->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <p>Apakah Anda yakin ingin menyetujui request ini?</p>
                                                        <div class="mb-3">
                                                            <label for="admin_notes" class="form-label">Catatan Admin (Opsional)</label>
                                                            <textarea class="form-control" id="admin_notes" name="admin_notes" rows="3" placeholder="Masukkan catatan..."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-success">Setujui</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Reject Modal -->
                                    <div class="modal fade" id="rejectModal{{ $request->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Tolak Request</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('appointment-requests.reject', $request->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <p>Apakah Anda yakin ingin menolak request ini?</p>
                                                        <div class="mb-3">
                                                            <label for="admin_notes" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                                                            <textarea class="form-control" id="admin_notes" name="admin_notes" rows="3" placeholder="Masukkan alasan penolakan..." required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-danger">Tolak</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">Tidak ada request</td>
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
