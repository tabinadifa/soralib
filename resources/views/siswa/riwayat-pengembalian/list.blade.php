@extends('layouts.layout')

@section('title', 'Riwayat Pengembalian - Siswa')

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

		.badge-soft {
			font-size: 0.73rem;
			font-weight: 600;
			text-transform: uppercase;
			border-radius: 20px;
			padding: 0.35rem 0.6rem;
		}

		.badge-soft-success {
			background: #e8f5e9;
			color: #2e7d32;
		}

		.badge-soft-warning {
			background: #fff8e1;
			color: #ef6c00;
		}

		.badge-soft-secondary {
			background: #f3f4f6;
			color: #4b5563;
		}

		.book-meta {
			color: #6b7280;
			font-size: 0.8rem;
		}
	</style>
@endpush

@section('content')
	<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
		<div>
			<p class="text-uppercase text-muted mb-1 small">Pengembalian Buku</p>
			<h1 class="page-title fw-bold mb-0">Riwayat Pengembalian</h1>
		</div>
		<div class="text-end">
			<span class="text-muted small">Total {{ number_format($pengembalians->total()) }} data pengembalian</span>
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
					<label for="status" class="form-label text-uppercase text-muted small">Status Pembayaran</label>
					<select id="status" name="status" class="form-select" onchange="this.form.submit()">
						<option value="">Semua Status</option>
						<option value="lunas" @selected(request('status') === 'lunas')>Lunas</option>
						<option value="belum_lunas" @selected(request('status') === 'belum_lunas')>Belum Lunas</option>
					</select>
				</div>
				<div class="col-md-3 ms-md-auto">
					<a href="{{ route('siswa.pengembalian.list') }}" class="btn btn-light w-100">Reset Filter</a>
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
							<th>Tanggal Pengembalian</th>
							<th>Denda</th>
							<th>Status Bayar</th>
							<th class="text-center">Aksi</th>
						</tr>
					</thead>
					<tbody>
						@forelse ($pengembalians as $item)
							@php
								$statusText = $item->status === 'lunas' ? 'Lunas' : ($item->status === 'belum_lunas' ? 'Belum Lunas' : 'Tidak Ada Denda');
								$statusClass = $item->status === 'lunas' ? 'badge-soft-success' : ($item->status === 'belum_lunas' ? 'badge-soft-warning' : 'badge-soft-secondary');
							@endphp
							<tr>
								<td>{{ $pengembalians->firstItem() + $loop->index }}</td>
								<td>
									<div class="fw-semibold">{{ $item->peminjaman->buku->judul_buku ?? '-' }}</div>
									<div class="book-meta">{{ $item->peminjaman->buku->penulis ?? 'Penulis tidak diketahui' }}</div>
								</td>
								<td>{{ \Carbon\Carbon::parse($item->tanggal_pengembalian)->translatedFormat('d M Y') }}</td>
								<td>
									@if (($item->denda ?? 0) > 0)
										<span class="text-danger fw-semibold">Rp {{ number_format($item->denda, 0, ',', '.') }}</span>
									@else
										<span class="text-muted">-</span>
									@endif
								</td>
								<td><span class="badge-soft {{ $statusClass }}">{{ $statusText }}</span></td>
								<td class="text-center">
									<a href="{{ route('siswa.pengembalian.show', $item->id) }}" class="btn btn-sm btn-outline-secondary" title="Detail" aria-label="Detail">
										<i class="bi bi-eye"></i>
									</a>
								</td>
							</tr>
						@empty
							<tr>
								<td colspan="6" class="text-center text-muted py-5">
									<i class="bi bi-inbox d-block mb-2" style="font-size:2rem;"></i>
									Belum ada riwayat pengembalian.
								</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>
		</div>

		@if ($pengembalians->hasPages())
			<div class="card-footer bg-white d-flex flex-column flex-md-row justify-content-between align-items-center">
				<small class="text-muted mb-2 mb-md-0">
					Menampilkan {{ $pengembalians->firstItem() }} - {{ $pengembalians->lastItem() }} dari {{ $pengembalians->total() }} data
				</small>
				{{ $pengembalians->onEachSide(1)->links('pagination::bootstrap-5') }}
			</div>
		@endif
	</div>
@endsection

