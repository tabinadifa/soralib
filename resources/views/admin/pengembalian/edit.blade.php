@extends('layouts.layout')

@section('title', 'Edit Pengembalian Buku - Admin')

@section('content')
@php
	$today = now()->format('Y-m-d');
	$dendaPerHari = 1000;

	$selectedPeminjamanId = old('peminjaman_id', $pengembalian->peminjaman_id);
	$selectedPeminjaman = $peminjamans->firstWhere('id', (int) $selectedPeminjamanId) ?? $pengembalian->peminjaman;
	$selectedDueDate = $selectedPeminjaman?->tanggal_kembali;
	$selectedPinjamDate = $selectedPeminjaman?->tanggal_pinjam;

	$dendaTelatAwal = 0;
	if ($selectedDueDate && $pengembalian->tanggal_pengembalian) {
		$due = \Carbon\Carbon::parse($selectedDueDate)->startOfDay();
		$actual = \Carbon\Carbon::parse($pengembalian->tanggal_pengembalian)->startOfDay();
		if ($actual->gt($due)) {
			$dendaTelatAwal = $due->diffInDays($actual) * $dendaPerHari;
		}
	}

	$defaultDendaKondisi = max(0, (float) $pengembalian->denda - $dendaTelatAwal);
@endphp

<div class="d-flex flex-wrap justify-content-between gap-3 align-items-start mb-4">
	<div>
		<h2 class="fw-bold mb-1">Edit Pengembalian Buku</h2>
		<p class="text-muted mb-0">Perbarui detail data pengembalian dan status pembayaran denda.</p>
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

<form action="{{ route('admin.pengembalian.update', $pengembalian->id) }}" method="POST" class="row g-4 align-items-start" id="formPengembalianEdit">
	@csrf
	@method('PUT')
	<input type="hidden" name="peminjaman_id" value="{{ $pengembalian->peminjaman_id }}">
	<input type="hidden" name="tanggal_pengembalian" id="tanggal_pengembalian_hidden" value="{{ old('tanggal_pengembalian', $pengembalian->tanggal_pengembalian ? \Carbon\Carbon::parse($pengembalian->tanggal_pengembalian)->format('Y-m-d') : $today) }}">

	<div class="col-lg-8">
		<div class="card border-0 shadow-sm rounded-4 mb-4">
			<div class="card-body">
				<h5 class="fw-semibold mb-3">Data Peminjaman</h5>
				<div class="row g-3">
					<div class="col-md-8">
						<label class="form-label">Data Peminjaman</label>
						<input type="text" class="form-control" value="{{ ($selectedPeminjaman?->peminjam?->name ?? '-') . ' - ' . ($selectedPeminjaman?->buku?->judul_buku ?? '-') }}" readonly>
					</div>
					<div class="col-md-4 d-flex align-items-end">
						<a href="{{ route('admin.pengembalian.show', $pengembalian->id) }}" class="btn btn-outline-secondary w-100">
							Lihat Detail Saat Ini
						</a>
					</div>
				</div>

				<div class="selected-summary border rounded-4 p-3 mt-4">
					<h6 class="fw-semibold mb-3">Ringkasan Peminjaman</h6>
					<div class="row g-3 small">
						<div class="col-md-6">
							<p class="text-muted mb-1">Peminjam</p>
							<p class="fw-semibold mb-0" data-summary="peminjam">{{ $selectedPeminjaman?->peminjam?->name ?? '-' }}</p>
						</div>
						<div class="col-md-6">
							<p class="text-muted mb-1">Buku</p>
							<p class="fw-semibold mb-0" data-summary="buku">{{ $selectedPeminjaman?->buku?->judul_buku ?? '-' }}</p>
						</div>
						<div class="col-md-6">
							<p class="text-muted mb-1">Tanggal Pinjam</p>
							<p class="fw-semibold mb-0" data-summary="pinjam">{{ $selectedPinjamDate ? \Carbon\Carbon::parse($selectedPinjamDate)->translatedFormat('d M Y') : '-' }}</p>
						</div>
						<div class="col-md-6">
							<p class="text-muted mb-1">Batas Kembali</p>
							<p class="fw-semibold mb-0" data-summary="kembali">{{ $selectedDueDate ? \Carbon\Carbon::parse($selectedDueDate)->translatedFormat('d M Y') : '-' }}</p>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="card border-0 shadow-sm rounded-4">
			<div class="card-body">
				<h5 class="fw-semibold mb-3">Informasi Pengembalian</h5>
				<div class="row g-4">
					<div class="col-md-6">
						<label for="tanggal_pengembalian_display" class="form-label">Tanggal Pengembalian</label>
						<input type="date" id="tanggal_pengembalian_display" class="form-control"
							value="{{ old('tanggal_pengembalian', \Carbon\Carbon::parse($pengembalian->tanggal_pengembalian)->format('Y-m-d')) }}" required>
					</div>
					<div class="col-md-6">
						<label for="kondisi_buku" class="form-label">Kondisi Buku</label>
						<select name="kondisi_buku" id="kondisi_buku" class="form-select" required>
							<option value="">Pilih kondisi</option>
							<option value="baik" {{ old('kondisi_buku', $pengembalian->kondisi_buku) == 'baik' ? 'selected' : '' }}>Baik</option>
							<option value="rusak_ringan" {{ old('kondisi_buku', $pengembalian->kondisi_buku) == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
							<option value="rusak_berat" {{ old('kondisi_buku', $pengembalian->kondisi_buku) == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
							<option value="hilang" {{ old('kondisi_buku', $pengembalian->kondisi_buku) == 'hilang' ? 'selected' : '' }}>Hilang</option>
						</select>
					</div>
					<div class="col-md-6">
						<label for="denda_kondisi" class="form-label">Denda Kondisi Buku</label>
						<div class="input-group">
							<span class="input-group-text">Rp</span>
							<input type="number" id="denda_kondisi" name="denda_kondisi" class="form-control"
								value="{{ old('denda_kondisi', (int) $defaultDendaKondisi) }}" min="0" step="1000">
						</div>
						<small class="text-muted">Denda karena kerusakan/kehilangan (isi sesuai kondisi).</small>
					</div>
					<div class="col-md-6">
						<label for="status" class="form-label">Status Pembayaran Denda</label>
						<select name="status" id="status" class="form-select" required>
							<option value="">Pilih status</option>
							<option value="pending" {{ old('status', $pengembalian->status) == 'pending' ? 'selected' : '' }}>Pending</option>
							<option value="lunas" {{ old('status', $pengembalian->status) == 'lunas' ? 'selected' : '' }}>Lunas</option>
							<option value="belum_lunas" {{ old('status', $pengembalian->status) == 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
						</select>
					</div>
					<div class="col-md-6">
						<label for="metode_pembayaran" class="form-label">Metode Pembayaran</label>
						<select name="metode_pembayaran" id="metode_pembayaran" class="form-select">
							<option value="">Pilih metode</option>
							<option value="tidak_denda" {{ old('metode_pembayaran', $pengembalian->metode_pembayaran) == 'tidak_denda' ? 'selected' : '' }}>Tidak Denda</option>
							<option value="belum_ditentukan" {{ old('metode_pembayaran', $pengembalian->metode_pembayaran) == 'belum_ditentukan' ? 'selected' : '' }}>Belum Ditentukan</option>
							<option value="tunai" {{ old('metode_pembayaran', $pengembalian->metode_pembayaran) == 'tunai' ? 'selected' : '' }}>Tunai</option>
							<option value="QRIS" {{ old('metode_pembayaran', $pengembalian->metode_pembayaran) == 'QRIS' ? 'selected' : '' }}>QRIS</option>
						</select>
					</div>
					<div class="col-12" id="qrisContainer" style="display: none;">
						<div class="alert alert-info text-center p-3">
							<img src="{{ asset('storage/uploads/qris.jpg') }}" alt="QRIS Code" style="max-width: 200px;" class="img-fluid rounded">
							<p class="mt-2 mb-0">Scan QRIS untuk melakukan pembayaran</p>
						</div>
					</div>
					<div class="col-md-6">
						<label class="form-label">Estimasi Total Denda</label>
						<div class="input-group">
							<span class="input-group-text">Rp</span>
							<input type="text" id="estimasi_total_denda" class="form-control" readonly disabled>
						</div>
						<small class="text-muted" id="info_denda_telat">Denda telat dihitung otomatis @ Rp{{ number_format($dendaPerHari, 0, ',', '.') }}/hari.</small>
					</div>
					<div class="col-12">
						<label for="catatan" class="form-label">Catatan (opsional)</label>
						<textarea name="catatan" id="catatan" rows="4" class="form-control"
							placeholder="Catatan tambahan mengenai pengembalian">{{ old('catatan', $pengembalian->catatan) }}</textarea>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-4">
		<div class="card border-0 shadow-sm rounded-4">
			<div class="card-body d-flex flex-column gap-4">
				<div>
					<div class="d-flex justify-content-between align-items-center mb-2">
						<label class="form-label mb-0">Gambar Bukti</label>
						<div class="btn-group">
							<button type="button" class="btn btn-sm btn-outline-primary" data-open-file-modal>Buka Direktori</button>
						</div>
					</div>
					<select name="file_bukti_pembayaran_id" id="file_bukti_pembayaran_id" class="form-select d-none" aria-hidden="true">
						<option value="" {{ old('file_bukti_pembayaran_id', $pengembalian->file_bukti_pembayaran_id) ? '' : 'selected' }}>Pilih file</option>
						@foreach ($files as $file)
						@php
							$previewPath = asset($file->file_path ?? $file->path);
							$fileName = $file->file_name ?? ($file->nama_file ?? 'Tanpa nama');
						@endphp
						<option value="{{ $file->id }}" data-preview="{{ $previewPath }}"
							data-name="{{ $fileName }}"
							{{ (string) old('file_bukti_pembayaran_id', $pengembalian->file_bukti_pembayaran_id) === (string) $file->id ? 'selected' : '' }}>
							{{ $fileName }}
						</option>
						@endforeach
					</select>
					<div class="selected-preview" data-file-preview>
						<span class="text-muted">Belum ada gambar dipilih</span>
					</div>
					<p class="small text-muted mt-2" data-file-name>Belum ada gambar dipilih</p>
				</div>

				<div class="border rounded-4 p-3 bg-light-subtle">
					<p class="small text-muted mb-1">Total denda tersimpan saat ini</p>
					<p class="fw-semibold mb-0">Rp {{ number_format($pengembalian->denda ?? 0, 0, ',', '.') }}</p>
				</div>

				<div class="d-flex justify-content-end gap-2">
					<a href="{{ route('admin.pengembalian.list') }}" class="btn btn-outline-secondary">
						Batal
					</a>
					<button type="submit" class="btn btn-primary">
						Perbarui
					</button>
				</div>
			</div>
		</div>
	</div>
</form>

@include('admin.pengembalian.partials.file-picker-modal', ['files' => $files])
@endsection

@push('styles')
<style>
	.selected-summary {
		background: #f9fafb;
	}
	.selected-preview {
		width: 100%;
		height: 180px;
		border: 1px dashed #d1d5db;
		border-radius: 1rem;
		display: flex;
		align-items: center;
		justify-content: center;
		overflow: hidden;
	}
	.selected-preview img {
		width: 100%;
		height: 100%;
		object-fit: cover;
	}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
	const dendaPerHari = {{ $dendaPerHari }};
	const selectedDueDate = '{{ $selectedDueDate ?? '' }}';
	const tanggalHidden = document.getElementById('tanggal_pengembalian_hidden');
	const tanggalDisplay = document.getElementById('tanggal_pengembalian_display');
	const dendaKondisiInput = document.getElementById('denda_kondisi');
	const metodeBayarSelect = document.getElementById('metode_pembayaran');
	const qrisContainer = document.getElementById('qrisContainer');
	const estimasiTotalInput = document.getElementById('estimasi_total_denda');
	const infoDendaTelat = document.getElementById('info_denda_telat');
	const fileSelect = document.getElementById('file_bukti_pembayaran_id');
	const fileNameTarget = document.querySelector('[data-file-name]');
	const filePreviewTarget = document.querySelector('[data-file-preview]');

	function formatRupiah(angka) {
		return (angka || 0).toLocaleString('id-ID');
	}

	function hitungDendaTelat() {
		const dueDateStr = selectedDueDate;
		const actualDateStr = tanggalDisplay?.value ?? '';
		if (!dueDateStr || !actualDateStr) return 0;

		const dueDate = new Date(dueDateStr);
		const actualDate = new Date(actualDateStr);
		if (Number.isNaN(dueDate.getTime()) || Number.isNaN(actualDate.getTime())) return 0;
		if (actualDate <= dueDate) return 0;

		const diffDays = Math.ceil((actualDate - dueDate) / (1000 * 60 * 60 * 24));
		return diffDays > 0 ? diffDays * dendaPerHari : 0;
	}

	function updateEstimasiTotalDenda() {
		const dendaTelat = hitungDendaTelat();
		const dendaKondisi = parseFloat(dendaKondisiInput?.value) || 0;
		const total = dendaTelat + dendaKondisi;

		if (estimasiTotalInput) estimasiTotalInput.value = formatRupiah(total);

		if (infoDendaTelat) {
			if (dendaTelat > 0) {
				const hariTelat = dendaTelat / dendaPerHari;
				infoDendaTelat.textContent = `Denda telat: Rp ${formatRupiah(dendaTelat)} (${hariTelat} hari x Rp${formatRupiah(dendaPerHari)})`;
				infoDendaTelat.classList.add('text-danger');
			} else {
				infoDendaTelat.textContent = `Denda telat dihitung otomatis @ Rp${formatRupiah(dendaPerHari)}/hari.`;
				infoDendaTelat.classList.remove('text-danger');
			}
		}
	}

	function syncTanggal() {
		if (!tanggalDisplay || !tanggalHidden) return;
		tanggalHidden.value = tanggalDisplay.value;
		updateEstimasiTotalDenda();
	}

	function toggleQRIS() {
		if (!metodeBayarSelect || !qrisContainer) return;
		qrisContainer.style.display = metodeBayarSelect.value === 'QRIS' ? 'block' : 'none';
	}

	function updateFilePreview() {
		const option = fileSelect?.selectedOptions?.[0];
		if (!option || !option.value) {
			if (filePreviewTarget) filePreviewTarget.innerHTML = '<span class="text-muted">Belum ada gambar dipilih</span>';
			if (fileNameTarget) fileNameTarget.textContent = 'Belum ada gambar dipilih';
			return;
		}
		if (filePreviewTarget) {
			const img = document.createElement('img');
			img.src = option.dataset.preview ?? '';
			img.alt = option.dataset.name ?? 'Preview file';
			filePreviewTarget.innerHTML = '';
			filePreviewTarget.appendChild(img);
		}
		if (fileNameTarget) fileNameTarget.textContent = option.dataset.name ?? 'File terpilih';
	}

	if (tanggalDisplay) {
		const todayValue = '{{ $today }}';
		tanggalDisplay.max = todayValue;
		syncTanggal();
		tanggalDisplay.addEventListener('change', syncTanggal);
	}

	if (dendaKondisiInput) dendaKondisiInput.addEventListener('input', updateEstimasiTotalDenda);

	toggleQRIS();
	if (metodeBayarSelect) metodeBayarSelect.addEventListener('change', toggleQRIS);

	if (fileSelect) fileSelect.addEventListener('change', updateFilePreview);

	updateEstimasiTotalDenda();
	updateFilePreview();

	const fileModalElement = document.getElementById('filePickerModal');
	let fileModal = null;
	if (fileModalElement && window.bootstrap) {
		fileModal = new bootstrap.Modal(fileModalElement);
	}
	document.querySelectorAll('[data-open-file-modal]').forEach((btn) => {
		btn.addEventListener('click', () => fileModal && fileModal.show());
	});

	const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? document.querySelector('input[name="_token"]')?.value ?? '';
	const deleteRouteTemplate = @json(route('filemanager.delete', ['id' => '__ID__']));
	const uploadUrl = @json(route('filemanager.upload'));

	function hideFileAlerts() {
		const uploadSuccess = document.getElementById('uploadSuccess');
		const uploadError = document.getElementById('uploadError');
		if (uploadSuccess) uploadSuccess.classList.add('d-none');
		if (uploadError) uploadError.classList.add('d-none');
	}

	function showSuccessMessage(msg) {
		const uploadSuccess = document.getElementById('uploadSuccess');
		if (!uploadSuccess) return;
		uploadSuccess.textContent = msg;
		uploadSuccess.classList.remove('d-none');
		setTimeout(() => uploadSuccess.classList.add('d-none'), 3000);
	}

	function showErrorMessage(msg) {
		const uploadError = document.getElementById('uploadError');
		if (!uploadError) return;
		uploadError.textContent = msg;
		uploadError.classList.remove('d-none');
	}

	function selectFileOption(id) {
		if (!fileSelect) return;
		fileSelect.value = id;
		fileSelect.dispatchEvent(new Event('change'));
	}

	function addFileToSelect(file) {
		if (!fileSelect) return;
		const option = document.createElement('option');
		const previewPath = file.file_path || file.path;
		const fileName = file.file_name || file.nama_file || 'Tanpa nama';
		option.value = file.id;
		option.dataset.preview = previewPath;
		option.dataset.name = fileName;
		option.textContent = fileName;
		fileSelect.appendChild(option);
	}

	function attachFileActions(row) {
		const pickBtn = row.querySelector('[data-file-pick]');
		if (pickBtn) {
			pickBtn.addEventListener('click', () => {
				selectFileOption(pickBtn.dataset.fileId);
				if (fileModal) fileModal.hide();
			});
		}

		const deleteBtn = row.querySelector('[data-file-delete]');
		if (deleteBtn) {
			deleteBtn.addEventListener('click', () => handleFileDelete(deleteBtn.dataset.fileId, deleteBtn));
		}
	}

	function addFileToTable(file) {
		const tbody = document.getElementById('filesTableBody');
		if (!tbody) return;
		const row = document.createElement('tr');
		row.dataset.fileRow = String(file.id);
		const previewPath = file.file_path || file.path;
		const fileName = file.file_name || file.nama_file || 'Tanpa nama';
		row.innerHTML = `
			<td><div class="rounded overflow-hidden border" style="width:64px;height:64px;cursor:zoom-in;" data-file-preview data-file-url="${previewPath}" data-file-name="${fileName}"><img src="${previewPath}" class="w-100 h-100 object-fit-cover" alt="${fileName}"></div></td>
			<td><div class="fw-semibold">${fileName}</div><div class="text-muted small">ID: ${file.id}</div></td>
			<td class="text-end"><div class="d-flex justify-content-end gap-2"><button type="button" class="btn btn-sm btn-outline-secondary" data-file-preview data-file-url="${previewPath}" data-file-name="${fileName}">Lihat</button><button type="button" class="btn btn-sm btn-outline-primary" data-file-pick data-file-id="${file.id}">Gunakan</button><button type="button" class="btn btn-sm btn-outline-danger" data-file-delete data-file-id="${file.id}" data-file-name="${fileName}">Hapus</button></div></td>
		`;
		tbody.insertBefore(row, tbody.firstChild);
		attachFileActions(row);
	}

	async function handleFileDelete(fileId, btn) {
		if (!csrfToken) {
			showErrorMessage('Token CSRF tidak ditemukan.');
			return;
		}

		const confirmed = confirm('Hapus file ini?');
		if (!confirmed) return;

		btn.disabled = true;
		const originalHtml = btn.innerHTML;
		btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

		try {
			const res = await fetch(deleteRouteTemplate.replace('__ID__', fileId), {
				method: 'DELETE',
				headers: {
					'X-CSRF-TOKEN': csrfToken,
					'X-Requested-With': 'XMLHttpRequest',
					'Accept': 'application/json',
				},
			});
			const data = await res.json();
			if (!res.ok || !data.success) {
				throw new Error(data.message || 'Gagal hapus');
			}

			const row = btn.closest('[data-file-row]');
			if (row) row.remove();

			if (fileSelect) {
				const option = Array.from(fileSelect.options).find((opt) => opt.value === String(fileId));
				if (option) option.remove();
				if (fileSelect.value === String(fileId)) {
					fileSelect.value = '';
					fileSelect.dispatchEvent(new Event('change'));
				}
			}

			showSuccessMessage('File berhasil dihapus');
		} catch (err) {
			showErrorMessage(err.message || 'Terjadi kesalahan saat menghapus file.');
		} finally {
			btn.disabled = false;
			btn.innerHTML = originalHtml;
		}
	}

	const uploadForm = document.getElementById('modalUploadForm');
	if (uploadForm) {
		uploadForm.addEventListener('submit', async (event) => {
			event.preventDefault();
			const uploadButton = document.getElementById('uploadButton');
			const formData = new FormData(uploadForm);
			hideFileAlerts();

			if (uploadButton) {
				uploadButton.disabled = true;
				uploadButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Uploading...';
			}

			try {
				const res = await fetch(uploadUrl, {
					method: 'POST',
					body: formData,
					headers: {
						'X-Requested-With': 'XMLHttpRequest',
						'Accept': 'application/json',
					},
				});
				const data = await res.json();

				if (!res.ok || !data.success) {
					throw new Error(data.message || 'Upload gagal');
				}

				showSuccessMessage('Upload berhasil');
				uploadForm.reset();

				if (data.file) {
					addFileToTable(data.file);
					addFileToSelect(data.file);
					selectFileOption(data.file.id);
				}
			} catch (err) {
				showErrorMessage(err.message || 'Terjadi kesalahan saat upload file.');
			} finally {
				if (uploadButton) {
					uploadButton.disabled = false;
					uploadButton.innerHTML = '<i class="bi bi-cloud-upload me-2"></i>Upload';
				}
			}
		});
	}

	document.querySelectorAll('[data-file-row]').forEach((row) => attachFileActions(row));
});
</script>
@endpush
