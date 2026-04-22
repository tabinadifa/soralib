@extends('layouts.layout')

@section('title', 'Daftar Administrator - SoraLib')

@push('styles')
    <style>
        .badge-role {
            background-color: #E8F5E9;
            color: #2D6F4E;
            font-weight: 600;
        }
    </style>
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
        <h2 class="fw-bold mb-0">Daftar Administrator</h2>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body">

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form method="GET" class="row g-2 mb-3 align-items-center mt-2">
                <div class="col-md-3">
                    <a href="{{ route('admin.administrator.create') }}" class="btn btn-success w-100">
                        <i class="bi bi-plus-circle me-1"></i>Tambah Administrator
                    </a>
                </div>

                <div class="col-md-2">
                    <select name="per_page" class="form-select" onchange="this.form.submit()">
                        @foreach ([5, 10, 25, 50] as $size)
                            <option value="{{ $size }}" {{ request('per_page', 10) == $size ? 'selected' : '' }}>
                                {{ $size }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 ms-auto">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                        placeholder="Cari nama, username, email..."
                        onkeydown="if(event.key==='Enter'){this.form.submit()}">
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Aktif Sejak</th>
                            <th>Terakhir Aktif</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($admins as $admin)
                            <tr>
                                <td>{{ $admins->firstItem() + $loop->index }}</td>
                                <td>{{ $admin->name }}</td>
                                <td>{{ $admin->username }}</td>
                                <td>{{ $admin->email }}</td>
                                <td>{{ $admin->active_since }}</td>
                                <td>{{ $admin->last_active }}</td>
                                <td class="text-center">
                                    <div class="d-inline-flex gap-2">
                                        <a href="{{ route('admin.administrator.edit', $admin->id) }}"
                                            class="btn btn-sm btn-outline-primary">Edit</a>
                                        @if(Auth::id() !== $admin->id)
                                            <form action="{{ route('admin.administrator.destroy', $admin->id) }}" method="POST"
                                                class="form-hapus" data-title="Yakin ingin menghapus?"
                                                data-text="Data administrator ini akan dihapus secara permanen.">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Data tidak ditemukan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">
                    Menampilkan {{ $admins->firstItem() }} – {{ $admins->lastItem() }} dari {{ $admins->total() }} data
                </small>
                {{ $admins->links('pagination::bootstrap-5') }}
            </div>

        </div>
    </div>
@endsection