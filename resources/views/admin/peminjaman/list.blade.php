@extends('layouts.layout')

@section('title', 'Daftar Peminjaman Buku - Perpustakaan')

@push('styles')
<style>
    .badge-status {
        font-weight: 600;
        text-transform: uppercase;
    }
    .badge-status-pending {
        background-color: #FFF3E0;
        color: #E65100;
    }
    .badge-status-approve {
        background-color: #E8F5E9;
        color: #2E7D32;
    }
    .badge-status-rejected {
        background-color: #FFEBEE;
        color: #C62828;
    }
    .badge-status-returned {
        background-color: #E3F2FD;
        color: #0D47A1;
    }
    .btn-outline-success-custom {
        border-color: #2E7D32;
        color: #2E7D32;
    }
    .btn-outline-success-custom:hover {
        background-color: #2E7D32;
        color: white;
    }
    .btn-outline-warning-custom {
        border-color: #F57C00;
        color: #F57C00;
    }
    .btn-outline-warning-custom:hover {
        background-color: #F57C00;
        color: white;
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 mt-3">
    <h2 class="fw-bold mb-0">Daftar Peminjaman Buku</h2>
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
            <div class="col-md-4 ms-auto">
                <input type="text" name="search" value="{{ request('search') }}"
                    class="form-control" placeholder="Cari nama peminjam, judul buku, atau penulis..."
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
                        <th>Total</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($peminjaman as $item)
                        <tr>
                            <td>{{ $peminjaman->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="fw-semibold">{{ $item->peminjam->name }}</div>
                                <div class="small text-muted">{{ $item->peminjam->email }}</div>
                                <a href="{{ route('admin.peminjaman.show', $item->id) }}" class="btn btn-sm btn-outline-secondary mt-1" title="Detail" aria-label="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $item->buku->judul_buku }}</div>
                                <div class="small text-muted">{{ $item->buku->penulis ?? '-' }}</div>
                            </td>
                            <td class="fw-semibold">{{ $item->total_buku }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->translatedFormat('d M Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal_kembali)->translatedFormat('d M Y') }}</td>
                            <td>
                                @php
                                    $statusText = [
                                        'pending' => 'Menunggu Persetujuan',
                                        'approve' => 'Disetujui',
                                        'rejected' => 'Ditolak',
                                        'returned' => 'Dikembalikan',
                                    ][$item->status] ?? ucfirst($item->status);
                                    $badgeClass = match($item->status) {
                                        'pending' => 'badge-status-pending',
                                        'approve' => 'badge-status-approve',
                                        'rejected' => 'badge-status-rejected',
                                        'returned' => 'badge-status-returned',
                                        default => 'bg-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }} badge-status">{{ $statusText }}</span>
                            </td>
                            <td class="text-center">
                                @if ($item->status === 'returned')
                                    <span class="text-muted" title="Sudah dikembalikan">
                                        <i class="bi bi-check2-circle"></i>
                                    </span>
                                @elseif ($item->status === 'approve')
                                    {{-- Tombol Kembalikan langsung ke halaman pengembalian --}}
                                    <a href="{{ route('admin.pengembalian.create', ['peminjaman_id' => $item->id]) }}"
                                       class="btn btn-sm btn-outline-warning-custom" title="Kembalikan" aria-label="Kembalikan">
                                        <i class="bi bi-arrow-return-left"></i>
                                    </a>
                                @else
                                    {{-- Tombol Ubah Status untuk pending / rejected --}}
                                    <button type="button" class="btn btn-sm btn-outline-success-custom"
                                        data-bs-toggle="modal" data-bs-target="#statusModal-{{ $item->id }}" title="Ubah Status" aria-label="Ubah Status">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                Data peminjaman tidak ditemukan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <small class="text-muted">
                Menampilkan {{ $peminjaman->firstItem() }} –
                {{ $peminjaman->lastItem() }} dari
                {{ $peminjaman->total() }} data
            </small>
            {{ $peminjaman->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

{{-- Modal Ubah Status untuk pending/rejected (tanpa returned & approve) --}}
@foreach ($peminjaman as $item)
    @continue($item->status === 'returned' || $item->status === 'approve')

    @php
        $isModalReopened = old('peminjaman_id') && (int) old('peminjaman_id') === $item->id;
        $selectedStatus = $isModalReopened ? old('status') : $item->status;
        $reasonValue = $isModalReopened ? old('alasan_ditolak') : $item->alasan_ditolak;
        $shouldShowReason = $selectedStatus === 'rejected';
        $statusLabels = [
            'pending' => 'Menunggu Persetujuan',
            'approve' => 'Disetujui',
            'rejected' => 'Ditolak',
        ];
    @endphp

    <div class="modal fade" id="statusModal-{{ $item->id }}" tabindex="-1" aria-labelledby="statusModalLabel-{{ $item->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <form method="POST" action="{{ route('admin.peminjaman.update-status', $item) }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="peminjaman_id" value="{{ $item->id }}">

                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-semibold" id="statusModalLabel-{{ $item->id }}">
                            Ubah Status Peminjaman
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="statusSelect-{{ $item->id }}" class="form-label fw-semibold">Status</label>
                            <select id="statusSelect-{{ $item->id }}" name="status" class="form-select"
                                data-reason-toggle="reasonField-{{ $item->id }}">
                                @foreach ($allowedStatuses as $status)
                                    @if ($status !== 'returned')
                                        <option value="{{ $status }}" @selected($selectedStatus === $status)>
                                            {{ $statusLabels[$status] ?? ucfirst($status) }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3 {{ $shouldShowReason ? '' : 'd-none' }}" id="reasonField-{{ $item->id }}">
                            <label for="reasonTextarea-{{ $item->id }}" class="form-label fw-semibold">Alasan Penolakan</label>
                            <textarea class="form-control" name="alasan_ditolak" id="reasonTextarea-{{ $item->id }}" rows="3"
                                maxlength="255" placeholder="Tuliskan alasan penolakan secara singkat">{{ $reasonValue }}</textarea>
                            <div class="form-text text-muted">Wajib diisi jika status Ditolak (maks. 255 karakter).</div>
                        </div>

                        <div class="alert alert-info small">
                            <i class="bi bi-info-circle-fill me-2"></i>
                            Menyetujui peminjaman akan mengurangi stok buku. Menolak akan mengembalikan stok jika sebelumnya sudah disetujui.
                        </div>
                    </div>

                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Toggle alasan penolakan pada modal ubah status
    const toggleReasonField = (select) => {
        const targetId = select.getAttribute('data-reason-toggle');
        if (!targetId) return;
        const wrapper = document.getElementById(targetId);
        if (!wrapper) return;
        const textarea = wrapper.querySelector('textarea');
        const shouldShow = select.value === 'rejected';
        wrapper.classList.toggle('d-none', !shouldShow);
        if (textarea) textarea.required = shouldShow;
    };

    document.querySelectorAll('[data-reason-toggle]').forEach((select) => {
        const modal = select.closest('.modal');
        select.addEventListener('change', () => toggleReasonField(select));
        if (modal) modal.addEventListener('shown.bs.modal', () => toggleReasonField(select));
        toggleReasonField(select);
    });

    // Jika ada error validasi, buka modal kembali (untuk ubah status)
    const failedModalId = @json(old('peminjaman_id'));
    if (failedModalId) {
        const modalEl = document.getElementById(`statusModal-${failedModalId}`);
        if (modalEl) {
            const modalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
            modalInstance.show();
        }
    }
});
</script>
@endpush