@extends('layouts.layout')

@section('title', 'Riwayat Peminjaman - Peminjam')

@push('styles')
	<style>
		.page-title {
			color: #1e4d35;
		}

		.filter-card,
		.history-card {
			border: none;
			border-radius: 1rem;
			box-shadow: 0 4px 14px rgba(23, 56, 35, 0.08);
		}

		.badge-status {
			font-size: 0.72rem;
			font-weight: 600;
			text-transform: uppercase;
			padding: 0.35rem 0.6rem;
			border-radius: 20px;
			letter-spacing: 0.02em;
		}

		.badge-status-pending {
			background-color: #fff3e0;
			color: #e65100;
		}

		.badge-status-approve {
			background-color: #e8f5e9;
			color: #2e7d32;
		}

		.badge-status-rejected {
			background-color: #ffebee;
			color: #c62828;
		}

		.badge-status-returned {
			background-color: #e3f2fd;
			color: #0d47a1;
		}

		.book-meta {
			font-size: 0.8rem;
			color: #6b7280;
		}

		.rejection-note {
			display: inline-block;
			margin-top: 0.35rem;
			font-size: 0.76rem;
			color: #b91c1c;
			background: #fee2e2;
			border-radius: 0.55rem;
			padding: 0.2rem 0.5rem;
		}
	</style>
@endpush

@section('content')
	<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
		<div>
			<p class="text-uppercase text-muted mb-1 small">Peminjaman Buku</p>
			<h1 class="page-title fw-bold mb-0">Riwayat Peminjaman</h1>
		</div>
		<div class="text-end">
			<span class="text-muted small">Total {{ number_format($peminjaman->total()) }} pengajuan</span>
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

	<div class="card filter-card mb-4">
		<div class="card-body">
			<form method="GET" class="row g-3 align-items-end">
				<div class="col-md-2">
					<label for="per_page" class="form-label text-uppercase text-muted small">Per Halaman</label>
					<select id="per_page" name="per_page" class="form-select" onchange="this.form.submit()">
						@foreach ([5, 10, 25, 50] as $size)
							<option value="{{ $size }}" @selected((int) request('per_page', 10) === $size)>
								{{ $size }}
							</option>
						@endforeach
					</select>
				</div>

				<div class="col-md-4">
					<label for="status" class="form-label text-uppercase text-muted small">Status</label>
					<select id="status" name="status" class="form-select" onchange="this.form.submit()">
						<option value="">Semua Status</option>
						<option value="pending" @selected(request('status') === 'pending')>Menunggu Persetujuan</option>
						<option value="approve" @selected(request('status') === 'approve')>Disetujui</option>
						<option value="rejected" @selected(request('status') === 'rejected')>Ditolak</option>
						<option value="returned" @selected(request('status') === 'returned')>Dikembalikan</option>
					</select>
				</div>

				<div class="col-md-3 ms-md-auto">
					<a href="{{ route('siswa.peminjaman.riwayat') }}" class="btn btn-light w-100">Reset Filter</a>
				</div>
			</form>
		</div>
	</div>

	<div class="card history-card">
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-hover align-middle mb-0">
					<thead class="table-light">
						<tr>
							<th>No</th>
							<th>Buku</th>
							<th>Total</th>
							<th>Tanggal Pinjam</th>
							<th>Batas Kembali</th>
							<th>Status</th>
							<th>Diajukan</th>
						</tr>
					</thead>
					<tbody>
						@forelse ($peminjaman as $item)
							@php
								$statusLabel = [
									'pending' => 'Menunggu Persetujuan',
									'approve' => 'Disetujui',
									'rejected' => 'Ditolak',
									'returned' => 'Dikembalikan',
								][$item->status] ?? ucfirst($item->status);

								$statusClass = match ($item->status) {
									'pending' => 'badge-status-pending',
									'approve' => 'badge-status-approve',
									'rejected' => 'badge-status-rejected',
									'returned' => 'badge-status-returned',
									default => 'bg-secondary text-white',
								};
							@endphp
							<tr>
								<td>{{ $peminjaman->firstItem() + $loop->index }}</td>
								<td>
									<div class="fw-semibold">{{ $item->buku->judul_buku ?? '-' }}</div>
									<div class="book-meta">{{ $item->buku->penulis ?? 'Penulis tidak diketahui' }}</div>
									@if ($item->status === 'rejected' && $item->alasan_ditolak)
										<span class="rejection-note">Alasan: {{ $item->alasan_ditolak }}</span>
									@endif
								</td>
								<td class="fw-semibold">{{ number_format($item->total_buku) }}</td>
								<td>{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->translatedFormat('d M Y') }}</td>
								<td>{{ \Carbon\Carbon::parse($item->tanggal_kembali)->translatedFormat('d M Y') }}</td>
								<td>
									<span class="badge {{ $statusClass }} badge-status">{{ $statusLabel }}</span>
								</td>
								<td>{{ $item->created_at->translatedFormat('d M Y, H:i') }}</td>
							</tr>
						@empty
							<tr>
								<td colspan="7" class="text-center py-5 text-muted">
									<i class="bi bi-inbox mb-2 d-block" style="font-size: 2rem;"></i>
									Belum ada riwayat peminjaman.
								</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>
		</div>

		@if ($peminjaman->hasPages())
			<div class="card-footer bg-white d-flex flex-column flex-md-row justify-content-between align-items-center">
				<small class="text-muted mb-2 mb-md-0">
					Menampilkan {{ $peminjaman->firstItem() }} - {{ $peminjaman->lastItem() }} dari {{ $peminjaman->total() }} data
				</small>
				{{ $peminjaman->onEachSide(1)->links('pagination::bootstrap-5') }}
			</div>
		@endif
	</div>
@endsection

