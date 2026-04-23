@extends('layouts.layout')

@section('title', 'Detail Pengembalian - Siswa')

@section('content')
    @php
        $kondisiMap = [
            'baik' => ['label' => 'Baik', 'class' => 'success'],
            'rusak_ringan' => ['label' => 'Rusak Ringan', 'class' => 'warning'],
            'rusak_berat' => ['label' => 'Rusak Berat', 'class' => 'danger'],
            'hilang' => ['label' => 'Hilang', 'class' => 'dark'],
        ];

        $statusMap = [
            'lunas' => ['label' => 'Lunas', 'class' => 'success'],
            'belum_lunas' => ['label' => 'Belum Lunas', 'class' => 'warning'],
            null => ['label' => 'Tidak Ada Denda', 'class' => 'secondary'],
        ];

        $kondisiData = $kondisiMap[$pengembalian->kondisi_buku] ?? ['label' => ucfirst($pengembalian->kondisi_buku ?? '-'), 'class' => 'secondary'];
        $statusData = $statusMap[$pengembalian->status] ?? ['label' => ucfirst($pengembalian->status), 'class' => 'secondary'];

        $peminjaman = $pengembalian->peminjaman;
        $buku = $peminjaman?->buku;
        $bukti = $pengembalian->fileBuktiPembayaran;
        $buktiUrl = $bukti?->file_path ? asset($bukti->file_path) : null;
        $hasDenda = (int) ($pengembalian->denda ?? 0) > 0;
    @endphp

    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
        <div>
            <p class="text-uppercase text-muted mb-1 small">Pengembalian Buku</p>
            <h2 class="fw-bold mb-1">Detail Pengembalian</h2>
            <p class="text-muted mb-0">Lengkapi pembayaran denda dan unggah bukti jika diperlukan.</p>
        </div>
        <div>
            <a href="{{ route('siswa.pengembalian.list') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>

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

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-semibold mb-3">Informasi Pengembalian</h5>
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th class="text-muted fw-normal" width="38%">Tanggal Pengembalian</th>
                            <td>{{ \Carbon\Carbon::parse($pengembalian->tanggal_pengembalian)->translatedFormat('d F Y') }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted fw-normal">Kondisi Buku</th>
                            <td><span class="badge bg-{{ $kondisiData['class'] }}">{{ $kondisiData['label'] }}</span></td>
                        </tr>
                        <tr>
                            <th class="text-muted fw-normal">Status Pembayaran</th>
                            <td><span class="badge bg-{{ $statusData['class'] }}">{{ $statusData['label'] }}</span></td>
                        </tr>
                        <tr>
                            <th class="text-muted fw-normal">Metode Pembayaran</th>
                            <td>{{ $pengembalian->metode_pembayaran ? \Illuminate\Support\Str::of($pengembalian->metode_pembayaran)->replace('_', ' ')->title() : '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted fw-normal">Total Denda</th>
                            <td>
                                @if ($hasDenda)
                                    <span class="text-danger fw-semibold fs-5">Rp {{ number_format($pengembalian->denda, 0, ',', '.') }}</span>
                                @else
                                    <span class="badge bg-success">Tidak Ada Denda</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="text-muted fw-normal">Buku</th>
                            <td>
                                <div class="fw-semibold">{{ $buku->judul_buku ?? '-' }}</div>
                                <div class="small text-muted">{{ $buku->penulis ?? '-' }}</div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-semibold mb-3">Update Pembayaran</h5>
                    <form action="{{ route('siswa.pengembalian.update-pembayaran', $pengembalian->id) }}" method="POST" enctype="multipart/form-data" class="row g-3">
                        @csrf
                        @method('PUT')

                        <div class="col-md-6">
                            <label for="metode_pembayaran" class="form-label">Metode Pembayaran</label>
                            <select name="metode_pembayaran" id="metode_pembayaran" class="form-select @error('metode_pembayaran') is-invalid @enderror" required>
                                <option value="">Pilih metode</option>
                                <option value="QRIS" @selected(old('metode_pembayaran', $pengembalian->metode_pembayaran) === 'QRIS')>QRIS</option>
                                <option value="tunai" @selected(old('metode_pembayaran', $pengembalian->metode_pembayaran) === 'tunai')>Tunai</option>
                                <option value="tidak_denda" @selected(old('metode_pembayaran', $pengembalian->metode_pembayaran) === 'tidak_denda')>Tidak Denda</option>
                            </select>
                            @error('metode_pembayaran')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label for="bukti_pembayaran" class="form-label">Upload Bukti Pembayaran</label>
                            <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" class="form-control @error('bukti_pembayaran') is-invalid @enderror" accept="image/png,image/jpeg,image/webp">
                            <small class="text-muted">Format JPG, JPEG, PNG, WEBP. Maksimal 2MB.</small>
                            @error('bukti_pembayaran')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-success">Simpan Pembayaran</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-semibold mb-3">Bukti Pembayaran</h5>
                    @if ($buktiUrl)
                        <div class="border rounded-4 overflow-hidden">
                            <img src="{{ $buktiUrl }}" alt="Bukti pembayaran" class="img-fluid w-100" style="max-height: 340px; object-fit: cover;">
                        </div>
                        <p class="small text-muted mt-2 mb-0">{{ $bukti->file_name ?? 'Bukti pembayaran' }}</p>
                        <a href="{{ $buktiUrl }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-primary mt-3">
                            <i class="bi bi-box-arrow-up-right me-1"></i>Buka Gambar
                        </a>
                    @else
                        <div class="text-center py-5 border rounded-4 bg-light">
                            <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                            <p class="text-muted mt-2 mb-0">Belum ada bukti pembayaran.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
