@extends('layouts.layout')

@section('title', 'Detail Anggota - SoraLib')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
        <h2 class="fw-bold mb-0">Detail Anggota</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.anggota.edit', $user->id) }}" class="btn btn-success">
                <i class="bi bi-pencil-square me-1"></i>Edit
            </a>
            <a href="{{ route('admin.anggota.list') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>

    <div class="row g-4">

        {{-- Profile Card --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4 text-center d-flex flex-column align-items-center justify-content-center">

                    {{-- Avatar --}}
                    <div class="position-relative mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white"
                            style="width: 90px; height: 90px; font-size: 2rem; background: linear-gradient(135deg, #198754, #20c997);">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        {{-- Online indicator --}}
                        <span class="position-absolute bottom-0 end-0 rounded-circle border border-2 border-white"
                            style="width: 18px; height: 18px; background-color: {{ $user->isOnline() ? '#198754' : '#adb5bd' }};"
                            title="{{ $user->isOnline() ? 'Online' : 'Offline' }}">
                        </span>
                    </div>

                    <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                    <p class="text-muted mb-2" style="font-size: 0.875rem;">{{ '@' . $user->username }}</p>

                    {{-- Role Badge --}}
                    <span class="badge rounded-pill px-3 py-2 mb-3
                        {{ $user->role === 'admin' ? 'bg-danger' : 'bg-success bg-opacity-10 text-success border border-success border-opacity-25' }}"
                        style="font-size: 0.78rem;">
                        <i class="bi {{ $user->role === 'admin' ? 'bi-shield-fill' : 'bi-person-fill' }} me-1"></i>
                        {{ ucfirst($user->role) }}
                    </span>

                    {{-- Status Online --}}
                    <div class="text-muted" style="font-size: 0.8rem;">
                        <i class="bi bi-clock me-1"></i>
                        @if($user->isOnline())
                            <span class="text-success fw-semibold">Sedang Online</span>
                        @else
                            Terakhir aktif:
                            {{ $user->last_active_at ? $user->last_active_at->diffForHumans() : 'Belum pernah aktif' }}
                        @endif
                    </div>

                    <hr class="w-100 my-3">

                    {{-- Stats Row --}}
                    <div class="row w-100 text-center g-2">
                        <div class="col-6">
                            <div class="bg-light rounded-3 py-2 px-1">
                                <div class="fw-bold text-success" style="font-size: 1.2rem;">
                                    {{ $user->peminjaman_count ?? 0 }}
                                </div>
                                <div class="text-muted" style="font-size: 0.75rem;">Total Pinjam</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light rounded-3 py-2 px-1">
                                <div class="fw-bold text-warning" style="font-size: 1.2rem;">
                                    {{ $user->peminjaman_aktif ?? 0 }}
                                </div>
                                <div class="text-muted" style="font-size: 0.75rem;">Sedang Dipinjam</div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- Detail Info Card --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">

                    <h6 class="fw-bold text-muted text-uppercase mb-3" style="font-size: 0.75rem; letter-spacing: 0.08em;">
                        <i class="bi bi-person-lines-fill me-1"></i> Informasi Anggota
                    </h6>

                    <div class="row g-3">

                        {{-- Nama Lengkap --}}
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3 h-100">
                                <div class="text-muted mb-1" style="font-size: 0.75rem; letter-spacing: 0.04em;">NAMA LENGKAP</div>
                                <div class="fw-semibold">{{ $user->name }}</div>
                            </div>
                        </div>

                        {{-- Username --}}
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3 h-100">
                                <div class="text-muted mb-1" style="font-size: 0.75rem; letter-spacing: 0.04em;">USERNAME</div>
                                <div class="fw-semibold">{{ $user->username }}</div>
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3 h-100">
                                <div class="text-muted mb-1" style="font-size: 0.75rem; letter-spacing: 0.04em;">EMAIL</div>
                                <div class="fw-semibold">
                                    <a href="mailto:{{ $user->email }}" class="text-decoration-none text-dark">
                                        {{ $user->email }}
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- No. Telepon --}}
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3 h-100">
                                <div class="text-muted mb-1" style="font-size: 0.75rem; letter-spacing: 0.04em;">NO. TELEPON</div>
                                <div class="fw-semibold">
                                    {{ $user->phone ?? '-' }}
                                </div>
                            </div>
                        </div>

                        {{-- NISN --}}
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3 h-100">
                                <div class="text-muted mb-1" style="font-size: 0.75rem; letter-spacing: 0.04em;">NISN</div>
                                <div class="fw-semibold">{{ $user->nisn ?? '-' }}</div>
                            </div>
                        </div>

                        {{-- Kelas --}}
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3 h-100">
                                <div class="text-muted mb-1" style="font-size: 0.75rem; letter-spacing: 0.04em;">KELAS</div>
                                <div class="fw-semibold">{{ $user->kelas ?? '-' }}</div>
                            </div>
                        </div>

                        {{-- Alamat --}}
                        <div class="col-12">
                            <div class="p-3 bg-light rounded-3">
                                <div class="text-muted mb-1" style="font-size: 0.75rem; letter-spacing: 0.04em;">ALAMAT</div>
                                <div class="fw-semibold">{{ $user->address ?? '-' }}</div>
                            </div>
                        </div>

                    </div>

                    <hr class="my-4">

                    <h6 class="fw-bold text-muted text-uppercase mb-3" style="font-size: 0.75rem; letter-spacing: 0.08em;">
                        <i class="bi bi-calendar3 me-1"></i> Informasi Akun
                    </h6>

                    <div class="row g-3">

                        {{-- Tanggal Daftar --}}
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3 h-100">
                                <div class="text-muted mb-1" style="font-size: 0.75rem; letter-spacing: 0.04em;">TANGGAL DAFTAR</div>
                                <div class="fw-semibold">
                                    {{ $user->created_at->format('d F Y') }}
                                </div>
                                <div class="text-muted" style="font-size: 0.8rem;">
                                    {{ $user->created_at->format('H:i') }} WIB
                                </div>
                            </div>
                        </div>

                        {{-- Terakhir Diperbarui --}}
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3 h-100">
                                <div class="text-muted mb-1" style="font-size: 0.75rem; letter-spacing: 0.04em;">TERAKHIR DIPERBARUI</div>
                                <div class="fw-semibold">
                                    {{ $user->updated_at->format('d F Y') }}
                                </div>
                                <div class="text-muted" style="font-size: 0.8rem;">
                                    {{ $user->updated_at->format('H:i') }} WIB
                                </div>
                            </div>
                        </div>

                        {{-- Terakhir Aktif --}}
                        <div class="col-12">
                            <div class="p-3 rounded-3 h-100 d-flex align-items-center gap-3
                                {{ $user->isOnline() ? 'bg-success bg-opacity-10 border border-success border-opacity-25' : 'bg-light' }}">
                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                    style="width: 40px; height: 40px; background-color: {{ $user->isOnline() ? '#d1e7dd' : '#e9ecef' }};">
                                    <i class="bi bi-activity {{ $user->isOnline() ? 'text-success' : 'text-muted' }}"></i>
                                </div>
                                <div>
                                    <div class="text-muted mb-0" style="font-size: 0.75rem; letter-spacing: 0.04em;">TERAKHIR AKTIF</div>
                                    <div class="fw-semibold {{ $user->isOnline() ? 'text-success' : '' }}">
                                        @if($user->isOnline())
                                            Sedang Online Sekarang
                                        @else
                                            {{ $user->last_active_at ? $user->last_active_at->format('d F Y, H:i') . ' WIB' : 'Belum pernah aktif' }}
                                        @endif
                                    </div>
                                    @if($user->last_active_at && !$user->isOnline())
                                        <div class="text-muted" style="font-size: 0.8rem;">
                                            {{ $user->last_active_at->diffForHumans() }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>

    </div>

    {{-- Riwayat Peminjaman --}}
    @if(isset($riwayat) && $riwayat->count() > 0)
    <div class="card border-0 shadow-sm rounded-4 mt-4">
        <div class="card-body p-4">
            <h6 class="fw-bold text-muted text-uppercase mb-3" style="font-size: 0.75rem; letter-spacing: 0.08em;">
                <i class="bi bi-journal-bookmark-fill me-1"></i> Riwayat Peminjaman Terbaru
            </h6>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="fw-semibold" style="font-size: 0.85rem;">Judul Buku</th>
                            <th class="fw-semibold" style="font-size: 0.85rem;">Tanggal Pinjam</th>
                            <th class="fw-semibold" style="font-size: 0.85rem;">Tanggal Kembali</th>
                            <th class="fw-semibold" style="font-size: 0.85rem;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($riwayat as $item)
                        <tr>
                            <td>
                                <div class="fw-semibold" style="font-size: 0.9rem;">{{ $item->buku->judul ?? '-' }}</div>
                                <div class="text-muted" style="font-size: 0.78rem;">{{ $item->buku->pengarang ?? '-' }}</div>
                            </td>
                            <td style="font-size: 0.875rem;">{{ $item->tanggal_pinjam->format('d M Y') }}</td>
                            <td style="font-size: 0.875rem;">
                                {{ $item->tanggal_kembali ? $item->tanggal_kembali->format('d M Y') : '-' }}
                            </td>
                            <td>
                                @if($item->status === 'dikembalikan')
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3">
                                        Dikembalikan
                                    </span>
                                @elseif($item->status === 'dipinjam')
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill px-3">
                                        Dipinjam
                                    </span>
                                @elseif($item->status === 'terlambat')
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill px-3">
                                        Terlambat
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- Delete Modal --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-body p-4 text-center">
                    <div class="mb-3">
                        <div class="rounded-circle bg-danger bg-opacity-10 d-inline-flex align-items-center justify-content-center"
                            style="width: 60px; height: 60px;">
                            <i class="bi bi-trash3 text-danger fs-4"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-1">Hapus Anggota?</h5>
                    <p class="text-muted mb-4" style="font-size: 0.9rem;">
                        Data anggota <strong>{{ $user->name }}</strong> akan dihapus secara permanen dan tidak dapat dikembalikan.
                    </p>
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Batal</button>
                        <form action="{{ route('admin.anggota.destroy', $user->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger px-4">Ya, Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection