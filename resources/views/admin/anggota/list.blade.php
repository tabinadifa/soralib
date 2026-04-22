@extends('layouts.layout')

@section('title', 'Daftar Anggota - SoraLib')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
        <h2 class="fw-bold mb-0">Daftar Anggota</h2>
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
                        placeholder="Cari nama, NISN, kelas, email..."
                        onkeydown="if(event.key==='Enter'){this.form.submit()}">
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>NISN</th>
                            <th>Kelas</th>
                            <th>Email</th>
                            <th>No. Telepon</th>
                            <th>Terakhir Aktif</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($siswas as $siswa)
                            <tr>
                                <td>{{ $siswas->firstItem() + $loop->index }}</td>
                                <td>{{ $siswa->name }}</td>
                                <td>{{ $siswa->nisn ?? '-' }}</td>
                                <td>{{ $siswa->kelas ?? '-' }}</td>
                                <td>{{ $siswa->email }}</td>
                                <td>{{ $siswa->phone ?? '-' }}</td>
                                <td>{{ $siswa->last_active }}</td>
                                <td class="text-center">
                                    <div class="d-inline-flex gap-2">
                                        <a href="{{ route('admin.anggota.detail', $siswa->id) }}"
                                            class="btn btn-sm btn-outline-secondary" title="Detail" aria-label="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.anggota.edit', $siswa->id) }}"
                                            class="btn btn-sm btn-outline-primary" title="Edit" aria-label="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.anggota.destroy', $siswa->id) }}" method="POST"
                                            class="form-hapus" data-title="Yakin ingin menghapus?"
                                            data-text="Data anggota ini akan dihapus secara permanen.">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus" aria-label="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">Data tidak ditemukan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">
                    Menampilkan {{ $siswas->firstItem() }} – {{ $siswas->lastItem() }} dari {{ $siswas->total() }} data
                </small>
                {{ $siswas->links('pagination::bootstrap-5') }}
            </div>

        </div>
    </div>
@endsection