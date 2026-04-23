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

        .buku-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .buku-card {
            border: 1px solid #edf1ee;
            border-radius: 1rem;
            background: #fff;
            padding: 1rem;
            display: flex;
            align-items: stretch;
            gap: 1rem;
            transition: box-shadow 0.2s ease, transform 0.2s ease;
        }

        .buku-card:hover {
            box-shadow: 0 8px 18px rgba(23, 56, 35, 0.1);
            transform: translateY(-1px);
        }

        .buku-card-media {
            width: 120px;
            flex: 0 0 120px;
            border-radius: 0.75rem;
            overflow: hidden;
            border: 1px solid #edf1ee;
        }

        .buku-card-img {
            width: 100%;
            height: 100%;
            min-height: 170px;
            object-fit: cover;
            object-position: center;
            display: block;
            background-color: #f8f9fa;
        }

        .buku-card-img-placeholder {
            width: 100%;
            min-height: 170px;
            background: linear-gradient(145deg, #f0f2f5 0%, #e9ecef 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #adb5bd;
            font-size: 2.2rem;
        }

        .buku-card-body {
            padding: 0.25rem 0;
            display: flex;
            flex: 1;
            gap: 1rem;
            justify-content: space-between;
        }

        .buku-card-main {
            min-width: 0;
            flex: 1;
        }

        .buku-card-action {
            display: flex;
            align-items: center;
            min-width: 220px;
        }

        .buku-card-action .btn {
            white-space: nowrap;
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

        @media (max-width: 767.98px) {
            .buku-card {
                flex-direction: column;
            }

            .buku-card-media {
                width: 100%;
                flex-basis: auto;
            }

            .buku-card-body {
                flex-direction: column;
            }

            .buku-card-action {
                min-width: 0;
                width: 100%;
            }
        }
    </style>
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <p class="text-uppercase text-muted mb-1 small">Peminjaman Buku</p>
            <h1 class="page-title fw-bold mb-0">Daftar Buku</h1>
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

                <div class="col-md-4">
                    <label for="kategori_id" class="form-label text-uppercase text-muted small">Kategori</label>
                    <select id="kategori_id" name="kategori_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Kategori</option>
                        @foreach ($kategoriBukus as $kategori)
                            <option value="{{ $kategori->id }}" @selected((string) request('kategori_id') === (string) $kategori->id)>
                                {{ $kategori->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
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
                <div class="buku-list">
                    @foreach ($bukus as $buku)
                        @php
                            $canBorrow = $buku->jumlah_stok > 0;
                            $gambar = $buku->gambar;
                            $gambarUrl = $gambar ? asset($gambar->file_path) : null;
                        @endphp
                        <div class="buku-card">
                            <div class="buku-card-media">
                                @if ($gambarUrl)
                                    <img src="{{ $gambarUrl }}" alt="{{ $buku->judul_buku }}" class="buku-card-img">
                                @else
                                    <div class="buku-card-img-placeholder">
                                        <i class="bi bi-journal-bookmark-fill"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="buku-card-body">
                                <div class="buku-card-main">
                                    <div class="mb-2 pe-md-3">
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
                                        <p class="text-muted small mb-0" style="max-height:3.75rem;overflow:hidden;">
                                            {{ Str::limit($buku->deskripsi, 80) }}
                                        </p>
                                    @endif
                                </div>

                                <div class="buku-card-action">
                                    @if ($canBorrow)
                                        <a href="{{ route('siswa.peminjaman.create', $buku) }}"
                                            class="btn btn-success w-100">
                                            Ajukan Pinjam 
                                        </a>
                                    @else
                                        <button class="btn btn-secondary w-100" disabled>
                                            Stok Habis
                                        </button>
                                    @endif
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
