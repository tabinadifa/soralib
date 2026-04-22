@extends('layouts.layout')

@section('title', 'Detail Pengembalian Buku - Admin')

@section('content')
@php
    $kondisiMap = [
        'baik' => ['label' => 'Baik', 'class' => 'success'],
        'rusak_ringan' => ['label' => 'Rusak Ringan', 'class' => 'warning'],
        'rusak_berat' => ['label' => 'Rusak Berat', 'class' => 'danger'],
        'hilang' => ['label' => 'Hilang', 'class' => 'dark'],
    ];

    $statusMap = [
        'pending' => ['label' => 'Pending', 'class' => 'secondary'],
        'lunas' => ['label' => 'Lunas', 'class' => 'success'],
        'belum_lunas' => ['label' => 'Belum Lunas', 'class' => 'warning'],
    ];

    $kondisiData = $kondisiMap[$pengembalian->kondisi_buku] ?? [
        'label' => ucfirst($pengembalian->kondisi_buku ?? '-'),
        'class' => 'secondary',
    ];

    $statusData = $statusMap[$pengembalian->status] ?? [
        'label' => $pengembalian->status
            ? \Illuminate\Support\Str::of($pengembalian->status)->replace('_', ' ')->title()->toString()
            : 'Tidak Ada Denda',
        'class' => 'secondary',
    ];

    $metodePembayaranLabel = $pengembalian->metode_pembayaran
        ? \Illuminate\Support\Str::of($pengembalian->metode_pembayaran)->replace('_', ' ')->title()->toString()
        : null;

    $peminjaman = $pengembalian->peminjaman;
    $peminjam = $peminjaman?->peminjam;
    $buku = $peminjaman?->buku;
    $bukti = $pengembalian->fileBuktiPembayaran;

    $statusPeminjamanMap = [
        'pending' => 'Menunggu Persetujuan',
        'approve' => 'Disetujui',
        'approved' => 'Disetujui',
        'rejected' => 'Ditolak',
        'returned' => 'Dikembalikan',
        'borrowed' => 'Sedang Dipinjam',
    ];

    $statusPeminjamanLabel = $statusPeminjamanMap[$peminjaman?->status ?? '']
        ?? (($peminjaman?->status)
            ? \Illuminate\Support\Str::of($peminjaman->status)->replace('_', ' ')->title()->toString()
            : '-');
@endphp

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4 mt-3">
    <div>
        <h2 class="fw-bold mb-1">Detail Pengembalian Buku</h2>
        <p class="text-muted mb-0">Informasi lengkap pengembalian buku dan pembayaran denda.</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('admin.pengembalian.edit', $pengembalian->id) }}" class="btn btn-primary">
            <i class="bi bi-pencil me-1"></i>Edit
        </a>
        <a href="{{ route('admin.pengembalian.list') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>
</div>

<div class="row g-4 align-items-start">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <h5 class="fw-semibold mb-3 d-flex align-items-center gap-2">
                    <i class="bi bi-arrow-repeat text-primary"></i>Informasi Pengembalian
                </h5>

                <table class="table table-borderless mb-0">
                    <tr>
                        <th width="38%" class="text-muted fw-normal">ID Pengembalian</th>
                        <td>#{{ $pengembalian->id }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted fw-normal">Tanggal Pengembalian</th>
                        <td>{{ \Carbon\Carbon::parse($pengembalian->tanggal_pengembalian)->translatedFormat('d F Y') }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted fw-normal">Kondisi Buku</th>
                        <td>
                            <span class="badge bg-{{ $kondisiData['class'] }}">{{ $kondisiData['label'] }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted fw-normal">Status Pembayaran</th>
                        <td>
                            <span class="badge bg-{{ $statusData['class'] }}">{{ $statusData['label'] }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted fw-normal">Metode Pembayaran</th>
                        <td>
                            @if($metodePembayaranLabel)
                                <span class="badge bg-info text-dark">{{ $metodePembayaranLabel }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted fw-normal">Total Denda</th>
                        <td>
                            @if(($pengembalian->denda ?? 0) > 0)
                                <span class="fw-semibold text-danger fs-5">Rp {{ number_format($pengembalian->denda, 0, ',', '.') }}</span>
                            @else
                                <span class="badge bg-success">Tidak Ada Denda</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted fw-normal">Catatan</th>
                        <td>{{ $pengembalian->catatan ?: '-' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted fw-normal">Dibuat Pada</th>
                        <td>{{ $pengembalian->created_at?->translatedFormat('d F Y, H:i') ?? '-' }} WIB</td>
                    </tr>
                    <tr>
                        <th class="text-muted fw-normal">Diperbarui Pada</th>
                        <td>{{ $pengembalian->updated_at?->translatedFormat('d F Y, H:i') ?? '-' }} WIB</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <h5 class="fw-semibold mb-3 d-flex align-items-center gap-2">
                    <i class="bi bi-journal-text text-primary"></i>Detail Peminjaman
                </h5>

                <table class="table table-borderless mb-0">
                    <tr>
                        <th width="38%" class="text-muted fw-normal">ID Peminjaman</th>
                        <td>#{{ $peminjaman->id ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted fw-normal">Tanggal Pinjam</th>
                        <td>
                            @if($peminjaman?->tanggal_pinjam)
                                {{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->translatedFormat('d F Y') }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted fw-normal">Batas Kembali</th>
                        <td>
                            @if($peminjaman?->tanggal_kembali)
                                {{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->translatedFormat('d F Y') }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted fw-normal">Status Peminjaman</th>
                        <td>{{ $statusPeminjamanLabel }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <h5 class="fw-semibold mb-3 d-flex align-items-center gap-2">
                    <i class="bi bi-person-circle text-primary"></i>Data Peminjam
                </h5>

                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center"
                        style="width: 50px; height: 50px; flex-shrink: 0;">
                        <i class="bi bi-person-fill text-primary fs-4"></i>
                    </div>
                    <div>
                        <p class="mb-0 fw-semibold">{{ $peminjam->name ?? '-' }}</p>
                        <p class="mb-0 text-muted small">{{ $peminjam?->username ? '@' . $peminjam->username : '-' }}</p>
                    </div>
                </div>

                <ul class="list-unstyled mb-0 small">
                    <li class="d-flex gap-2 mb-2">
                        <i class="bi bi-envelope text-muted"></i>
                        <span>{{ $peminjam->email ?? '-' }}</span>
                    </li>
                    <li class="d-flex gap-2 mb-2">
                        <i class="bi bi-telephone text-muted"></i>
                        <span>{{ $peminjam->phone ?? '-' }}</span>
                    </li>
                    <li class="d-flex gap-2 mb-2">
                        <i class="bi bi-mortarboard text-muted"></i>
                        <span>Kelas: {{ $peminjam->kelas ?? '-' }}</span>
                    </li>
                    <li class="d-flex gap-2">
                        <i class="bi bi-card-text text-muted"></i>
                        <span>NISN: {{ $peminjam->nisn ?? '-' }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <h5 class="fw-semibold mb-3 d-flex align-items-center gap-2">
                    <i class="bi bi-book text-primary"></i>Data Buku
                </h5>

                <p class="fw-semibold mb-1">{{ $buku->judul_buku ?? '-' }}</p>
                <div class="small text-muted mb-2">Penulis: {{ $buku->penulis ?? '-' }}</div>

                <div class="small">
                    <div class="mb-1"><strong>Penerbit:</strong> {{ $buku->penerbit ?? '-' }}</div>
                    <div class="mb-1"><strong>Tahun Terbit:</strong> {{ $buku->tahun_terbit ?? '-' }}</div>
                    <div><strong>ISBN:</strong> {{ $buku->isbn ?? '-' }}</div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <h5 class="fw-semibold mb-3 d-flex align-items-center gap-2">
                    <i class="bi bi-image text-primary"></i>Bukti Pembayaran
                </h5>

                @if($bukti)
                    @php
                        $imagePath = asset($bukti->file_path ?? $bukti->path ?? '');
                        $imageName = $bukti->file_name ?? $bukti->nama_file ?? 'Bukti pembayaran';
                    @endphp
                    <div class="border rounded-4 overflow-hidden">
                        <img src="{{ $imagePath }}" alt="{{ $imageName }}" class="img-fluid w-100" style="max-height: 320px; object-fit: cover;">
                    </div>
                    <p class="small text-muted mt-2 mb-0">{{ $imageName }}</p>
                    <a href="{{ $imagePath }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-primary mt-3">
                        <i class="bi bi-box-arrow-up-right me-1"></i>Buka Gambar
                    </a>
                @else
                    <div class="text-center py-4 border rounded-4 bg-light">
                        <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                        <p class="text-muted mt-2 mb-0">Belum ada bukti pembayaran.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
