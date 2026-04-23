@extends('layouts.layout')

@section('title', 'Daftar Pengembalian Buku - Admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
        <div>
            <h2 class="fw-bold mb-1">Daftar Pengembalian Buku</h2>
            <p class="text-muted mb-0">Riwayat pengembalian buku yang telah diproses.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body">

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if (session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
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
                <div class="col-md-3">
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Status Pembayaran</option>
                        <option value="lunas" @selected(request('status') === 'lunas')>Lunas</option>
                        <option value="belum_lunas" @selected(request('status') === 'belum_lunas')>Belum Lunas</option>
                    </select>
                </div>
                <div class="col-md-4 ms-auto">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                        placeholder="Cari nama peminjam atau judul buku..."
                        onkeydown="if(event.key==='Enter'){this.form.submit()}">
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Peminjam</th>
                            <th>Buku</th>
                            <th>Tgl Pengembalian</th>
                            <th>Kondisi Buku</th>
                            <th>Denda</th>
                            <th>Status Pembayaran</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pengembalians as $item)
                            <tr>
                                <td>{{ $pengembalians->firstItem() + $loop->index }}</td>

                                <td>
                                    <div class="fw-semibold">{{ $item->peminjaman->peminjam->name ?? '-' }}</div>
                                    <small class="text-muted">{{ $item->peminjaman->peminjam->email ?? '-' }}</small>
                                </td>

                                <td>
                                    <div class="fw-semibold">{{ $item->peminjaman->buku->judul_buku ?? '-' }}</div>
                                    <small class="text-muted">{{ $item->peminjaman->buku->penulis ?? '-' }}</small>
                                </td>

                                <td>
                                    {{ \Carbon\Carbon::parse($item->tanggal_pengembalian)->translatedFormat('d M Y') }}
                                </td>

                                <td>
                                    @php
                                        $kondisiMap = [
                                            'baik' => ['label' => 'Baik', 'class' => 'success'],
                                            'rusak_ringan' => ['label' => 'Rusak Ringan', 'class' => 'warning'],
                                            'rusak_berat' => ['label' => 'Rusak Berat', 'class' => 'danger'],
                                            'hilang' => ['label' => 'Hilang', 'class' => 'dark'],
                                        ];
                                        $kondisi = $item->kondisi_buku ?? 'baik';
                                        $kondisiData = $kondisiMap[$kondisi] ?? [
                                            'label' => ucfirst($kondisi),
                                            'class' => 'secondary',
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $kondisiData['class'] }}">
                                        {{ $kondisiData['label'] }}
                                    </span>
                                </td>

                                <td>
                                    @if ($item->denda > 0)
                                        <span class="text-danger fw-semibold">
                                            Rp {{ number_format($item->denda, 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                <td>
                                    @if (is_null($item->status))
                                        Tidak Ada Denda
                                    @else
                                        @php
                                            $statusLabel =
                                                $item->status === 'lunas'
                                                    ? 'Lunas'
                                                    : ($item->status === 'belum_lunas'
                                                        ? 'Belum Lunas'
                                                        : ucfirst($item->status));
                                            $statusClass =
                                                $item->status === 'lunas'
                                                    ? 'success'
                                                    : ($item->status === 'belum_lunas'
                                                        ? 'warning'
                                                        : 'secondary');
                                        @endphp
                                        <span class="badge bg-{{ $statusClass }}">{{ $statusLabel }}</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <div class="d-inline-flex gap-2">
                                        <a href="{{ route('admin.pengembalian.show', $item->id) }}"
                                            class="btn btn-sm btn-outline-secondary" title="Detail" aria-label="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.pengembalian.edit', $item->id) }}"
                                            class="btn btn-sm btn-outline-primary" title="Edit" aria-label="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.pengembalian.destroy', $item->id) }}" method="POST"
                                            class="form-hapus" data-title="Yakin ingin menghapus?"
                                            data-text="Data pengembalian ini akan dihapus secara permanen.">
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
                                <td colspan="8" class="text-center text-muted py-4">
                                    Data pengembalian tidak ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <small class="text-muted">
                    Menampilkan {{ $pengembalians->firstItem() }} –
                    {{ $pengembalians->lastItem() }} dari
                    {{ $pengembalians->total() }} data
                </small>
                {{ $pengembalians->links('pagination::bootstrap-5') }}
            </div>

        </div>
    </div>
@endsection
