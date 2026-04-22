@extends('layouts.layout')

@section('title', 'Ajukan Peminjaman Buku - Peminjam')

@push('styles')
    <style>
        /* Seragam untuk semua cover buku */
        .buku-card-img,
        .buku-img-box img {
            width: 100%;
            aspect-ratio: 2 / 3;
            /* Rasio 2:3 seperti cover buku */
            object-fit: cover;
            object-position: center;
            display: block;
            background-color: #f8f9fa;
        }

        /* Placeholder seragam */
        .buku-card-img-placeholder,
        .buku-img-placeholder {
            width: 100%;
            aspect-ratio: 2 / 3;
            background: linear-gradient(145deg, #f0f2f5 0%, #e9ecef 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #adb5bd;
            font-size: 3rem;
        }

        /* Hover efek sedikit zoom (opsional) */
        .buku-card-img {
            transition: transform 0.3s ease;
        }

        .buku-card:hover .buku-card-img {
            transform: scale(1.02);
        }
    </style>
@endpush

@section('content')
    @php
        $today = now()->format('Y-m-d');
        $tomorrow = now()->addDay()->format('Y-m-d');
        $gambar = $buku->gambar;
        $gambarUrl = $gambar ? asset($gambar->file_path) : null;
        $stokTersedia = $buku->jumlah_stok;
    @endphp

    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">Ajukan Peminjaman Buku</h2>
            <p class="text-muted mb-0">Lengkapi formulir di bawah untuk meminjam buku pilihan Anda.</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3">Informasi Buku</h5>

                    <div class="buku-img-box">
                        @if ($gambarUrl)
                            <img src="{{ $gambarUrl }}" alt="{{ $buku->judul_buku }}">
                        @else
                            <div class="buku-img-placeholder">
                                <i class="bi bi-journal-bookmark-fill"></i>
                            </div>
                        @endif
                    </div>

                    <table class="table table-borderless mb-0">
                        <tr>
                            <th class="text-muted" width="40%">Judul Buku</th>
                            <td class="fw-semibold">{{ $buku->judul_buku }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Penulis</th>
                            <td>{{ $buku->penulis ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Penerbit</th>
                            <td>{{ $buku->penerbit ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Tahun Terbit</th>
                            <td>{{ $buku->tahun_terbit ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">ISBN</th>
                            <td>{{ $buku->isbn ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Kategori</th>
                            <td>{{ $buku->kategori->nama_kategori ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Stok Tersedia</th>
                            <td>
                                <span class="badge text-bg-{{ $stokTersedia > 0 ? 'success' : 'danger' }}">
                                    {{ number_format($stokTersedia) }} eksemplar
                                </span>
                            </td>
                        </tr>
                        @if ($buku->deskripsi)
                            <tr>
                                <th class="text-muted">Sinopsis</th>
                                <td class="text-muted small">{{ $buku->deskripsi }}</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <form action="{{ route('siswa.peminjaman.store') }}" method="POST" class="row g-4">
                        @csrf
                        <input type="hidden" name="buku_id" value="{{ $buku->id }}">

                        <div class="col-md-6">
                            <label for="total_buku" class="form-label">Jumlah Buku</label>
                            <input type="number" id="total_buku" name="total_buku" class="form-control" min="1"
                                max="{{ $stokTersedia }}" value="{{ old('total_buku', 1) }}" required>
                            <small class="text-muted">Maksimal {{ number_format($stokTersedia) }} eksemplar.</small>
                        </div>

                        <div class="col-md-6">
                            <label for="tanggal_pinjam" class="form-label">Tanggal Pinjam</label>
                            <input type="date" id="tanggal_pinjam" name="tanggal_pinjam" class="form-control"
                                min="{{ $today }}" value="{{ old('tanggal_pinjam', $today) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label for="tanggal_kembali" class="form-label">Tanggal Kembali</label>
                            <input type="date" id="tanggal_kembali" name="tanggal_kembali" class="form-control"
                                min="{{ $tomorrow }}" value="{{ old('tanggal_kembali', $tomorrow) }}" required>
                        </div>

                        <div class="col-12">
                            <div class="alert alert-info border-0 rounded-4">
                                <i class="bi bi-info-circle me-2"></i>
                                Setelah diajukan, petugas akan meninjau permintaan Anda. Status peminjaman
                                dapat dipantau pada halaman riwayat peminjaman.
                            </div>
                        </div>

                        <div class="col-12 d-flex justify-content-end gap-2">
                            <a href="{{ route('siswa.peminjaman.list') }}" class="btn btn-light">Batal</a>
                            <button type="submit" class="btn btn-success">Ajukan Sekarang</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
