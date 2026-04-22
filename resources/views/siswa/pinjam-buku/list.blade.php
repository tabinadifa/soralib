@extends('layouts.layout')

@section('title', 'Daftar Buku - Peminjam')

@push('styles')
    <style>
        .page-title {
            color: #1e4d35;
        }

        .filter-card,
        .catalog-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 4px 14px rgba(23, 56, 35, 0.08);
        }

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

        .buku-card-body {
            padding: 1.25rem;
        }

        .badge-tersedia {
            font-size: 0.75rem;
            padding: 0.25rem 0.6rem;
            background-color: #198754;
            color: white;
            border-radius: 20px;
        }

        .buku-meta {
            font-size: 0.8rem;
            color: #6c757d;
            margin-bottom: 0.5rem;
        }
    </style>
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <p class="text-uppercase text-muted mb-1 small">Peminjaman Buku</p>
            <h1 class="page-title fw-bold mb-0">Pilih Buku Untuk Dipinjam</h1>
        </div>
        <div class="text-end">
            <span class="text-muted small">Menampilkan {{ number_format($bukus->total()) }} buku tersedia</span>
        </div>
    </div>

    <div class="card filter-card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-2">
                    <label for="per_page" class="form-label text-uppercase text-muted small">Per Halaman</label>
                    <select id="per_page" name="per_page" class="form-select" onchange="this.form.submit()">
                        @foreach ([5, 10, 25, 50] as $option)
                            <option value="{{ $option }}" @selected((int) request('per_page', 10) === $option)>
                                {{ $option }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="search" class="form-label text-uppercase text-muted small">Cari Buku</label>
                    <input type="text" id="search" name="search" class="form-control"
                        placeholder="Masukkan judul, penulis, atau ISBN" value="{{ request('search') }}"
                        onkeydown="if(event.key==='Enter'){this.form.submit()}">
                </div>
            </form>
        </div>
    </div>

    <div class="card catalog-card">
        <div class="card-body">
            @if ($bukus->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-inbox mb-2" style="font-size: 2rem;"></i>
                    <p class="mb-0">Tidak ada buku yang tersedia untuk dipinjam saat ini.</p>
                </div>
            @else
                <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
                    @foreach ($bukus as $buku)
                        @php
                            $canBorrow = $buku->jumlah_stok > 0;
                            $gambar = $buku->gambar;
                            $gambarUrl = $gambar ? asset($gambar->file_path) : null;
                        @endphp
                        <div class="col">
                            <div class="buku-card d-flex flex-column">
                                @if ($gambarUrl)
                                    <img src="{{ $gambarUrl }}" alt="{{ $buku->judul_buku }}" class="buku-card-img">
                                @else
                                    <div class="buku-card-img-placeholder">
                                        <i class="bi bi-journal-bookmark-fill"></i>
                                    </div>
                                @endif
                                <div class="buku-card-body d-flex flex-column flex-grow-1">
                                    <div class="mb-2">
                                        <h5 class="fw-semibold mb-1">{{ $buku->judul_buku }}</h5>
                                        <p class="text-muted small mb-0">
                                            {{ $buku->penulis ?? 'Penulis tidak diketahui' }}
                                        </p>
                                    </div>

                                    <div class="buku-meta">
                                        <i class="bi bi-tag me-1"></i>
                                        {{ $buku->kategori->nama_kategori ?? 'Tanpa kategori' }}
                                    </div>

                                    <div class="mb-2">
                                        <span class="badge-tersedia">
                                            <i class="bi bi-check-circle-fill me-1"></i> Tersedia {{ $buku->jumlah_stok }}
                                        </span>
                                    </div>

                                    @if ($buku->deskripsi)
                                        <p class="text-muted small mb-3" style="max-height:3.75rem;overflow:hidden;">
                                            {{ Str::limit($buku->deskripsi, 80) }}
                                        </p>
                                    @endif

                                    <div class="mt-auto">
                                        @if ($canBorrow)
                                            <a href="{{ route('siswa.peminjaman.create', $buku) }}"
                                                class="btn btn-success w-100">
                                                Ajukan Pinjam ({{ $buku->jumlah_stok }} tersedia)
                                            </a>
                                        @else
                                            <button class="btn btn-secondary w-100" disabled>
                                                Stok Habis
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        @if ($bukus->hasPages())
            <div class="card-footer bg-white d-flex flex-column flex-md-row justify-content-between align-items-center">
                <small class="text-muted mb-2 mb-md-0">
                    Menampilkan {{ $bukus->firstItem() }} - {{ $bukus->lastItem() }} dari {{ $bukus->total() }} buku
                </small>
                {{ $bukus->onEachSide(1)->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endsection
