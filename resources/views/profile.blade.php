@extends('layouts.layout')

@section('title', 'Profil Saya')

@section('content')
@php
	$passwordTabHasErrors = $errors->has('current_password') || $errors->has('new_password') || $errors->has('new_password_confirmation');
@endphp
<div class="container py-4">
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

	<div class="row">
		<div class="col-lg-4 mb-4">
			<div class="card shadow-sm border-0 rounded-4">
				<div class="card-body text-center p-4">
					@if ($user->profile_photo_url)
						<img src="{{ $user->profile_photo_url }}" alt="Foto profil {{ $user->name }}" class="avatar-image mx-auto mb-3">
					@else
						<div class="avatar-circle bg-primary text-white mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 100px; height: 100px; border-radius: 50%; font-size: 2.5rem; font-weight: 500;">
							{{ strtoupper(substr($user->name, 0, 1)) }}
						</div>
					@endif
					<h5 class="fw-bold mb-1">{{ $user->name }}</h5>
					<p class="text-muted small">@ {{ $user->username }}</p>
					<span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill">{{ ucfirst($user->role) }}</span>
					<hr class="my-3">
					<div class="text-start small">
						<div class="mb-2"><i class="bi bi-card-text me-2"></i> NISN: {{ $user->nisn ?? '-' }}</div>
						<div class="mb-2"><i class="bi bi-mortarboard me-2"></i> Kelas: {{ $user->kelas ?? '-' }}</div>
						<div class="mb-2"><i class="bi bi-envelope me-2"></i> {{ $user->email }}</div>
						<div class="mb-2"><i class="bi bi-telephone me-2"></i> {{ $user->phone ?? '-' }}</div>
						<div class="mb-2"><i class="bi bi-geo-alt me-2"></i> {{ $user->address ?? '-' }}</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-lg-8">
			<ul class="nav nav-tabs mb-4" id="profileTab" role="tablist">
				<li class="nav-item" role="presentation">
					<button class="nav-link {{ $passwordTabHasErrors ? '' : 'active' }}" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab">Informasi Profil</button>
				</li>
				<li class="nav-item" role="presentation">
					<button class="nav-link {{ $passwordTabHasErrors ? 'active' : '' }}" id="password-tab" data-bs-toggle="tab" data-bs-target="#password" type="button" role="tab">Ubah Password</button>
				</li>
			</ul>

			<div class="tab-content">
				<div class="tab-pane fade {{ $passwordTabHasErrors ? '' : 'show active' }}" id="info" role="tabpanel">
					<div class="card shadow-sm border-0 rounded-4">
						<div class="card-body p-4">
							<h5 class="card-title mb-4">Edit Informasi Profil</h5>
							<form method="POST" action="{{ route('profile.update') }}" id="profileForm" enctype="multipart/form-data">
								@csrf
								@method('PUT')

								<div class="mb-3">
									<label class="form-label">Foto Profil</label>
									<div class="d-flex align-items-center gap-3">
										<img
											id="profilePhotoPreview"
											src="{{ $user->profile_photo_url ?? 'https://placehold.co/80x80/e2e8f0/64748b?text=' . urlencode(strtoupper(substr($user->name, 0, 1))) }}"
											alt="Preview foto profil"
											class="profile-photo-preview"
										>
										<div class="w-100">
											<input type="file" name="profile_photo" id="profile_photo" class="form-control @error('profile_photo') is-invalid @enderror" accept="image/png,image/jpeg,image/webp">
											<small class="text-muted">Format JPG, JPEG, PNG, WEBP. Maksimal 2MB.</small>
										</div>
									</div>
									@error('profile_photo')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
								</div>

								<div class="mb-3">
									<label class="form-label">Nama Lengkap</label>
									<input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
									@error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
								</div>

								<div class="mb-3">
									<label class="form-label">Username</label>
									<input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username', $user->username) }}" required>
									@error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
								</div>

								<div class="mb-3">
									<label class="form-label">Email</label>
									<input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
									@error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
								</div>

								<div class="mb-3">
									<label class="form-label">NISN</label>
									<input type="text" name="nisn" class="form-control @error('nisn') is-invalid @enderror" value="{{ old('nisn', $user->nisn) }}" maxlength="20">
									@error('nisn')<div class="invalid-feedback">{{ $message }}</div>@enderror
								</div>

								<div class="mb-3">
									<label class="form-label">Kelas</label>
									<input type="text" name="kelas" class="form-control @error('kelas') is-invalid @enderror" value="{{ old('kelas', $user->kelas) }}" maxlength="50">
									@error('kelas')<div class="invalid-feedback">{{ $message }}</div>@enderror
								</div>

								<div class="mb-3">
									<label class="form-label">No. Telepon</label>
									<input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}" maxlength="13">
									@error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
								</div>

								<div class="mb-3">
									<label class="form-label">Alamat</label>
									<textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="3">{{ old('address', $user->address) }}</textarea>
									@error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
								</div>

								<button type="submit" class="btn btn-primary px-4">Simpan Perubahan</button>
							</form>
						</div>
					</div>
				</div>

				<div class="tab-pane fade {{ $passwordTabHasErrors ? 'show active' : '' }}" id="password" role="tabpanel">
					<div class="card shadow-sm border-0 rounded-4">
						<div class="card-body p-4">
							<h5 class="card-title mb-4">Ganti Password</h5>
							<form method="POST" action="{{ route('profile.password') }}" id="passwordForm">
								@csrf
								@method('PUT')

								<div class="mb-3">
									<label class="form-label">Password Saat Ini</label>
									<input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
									@error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
								</div>

								<div class="mb-3">
									<label class="form-label">Password Baru</label>
									<input type="password" name="new_password" class="form-control @error('new_password') is-invalid @enderror" required>
									@error('new_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
								</div>

								<div class="mb-3">
									<label class="form-label">Konfirmasi Password Baru</label>
									<input type="password" name="new_password_confirmation" class="form-control @error('new_password_confirmation') is-invalid @enderror" required>
									@error('new_password_confirmation')<div class="invalid-feedback">{{ $message }}</div>@enderror
								</div>

								<button type="submit" class="btn btn-primary px-4">Update Password</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@push('styles')
<style>
	.avatar-circle {
		background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
		box-shadow: 0 10px 20px rgba(0,0,0,0.1);
	}
	.avatar-image,
	.profile-photo-preview {
		width: 100px;
		height: 100px;
		border-radius: 50%;
		object-fit: cover;
		box-shadow: 0 10px 20px rgba(0,0,0,0.1);
		border: 3px solid #fff;
	}
	.profile-photo-preview {
		width: 80px;
		height: 80px;
	}
	.nav-tabs .nav-link {
		color: #4b5563;
		font-weight: 500;
		border: none;
		padding: 0.75rem 1.25rem;
	}
	.nav-tabs .nav-link.active {
		color: #4f46e5;
		border-bottom: 2px solid #4f46e5;
		background: transparent;
	}
	.card {
		border-radius: 1rem;
	}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
	document.addEventListener('DOMContentLoaded', function() {
		const alert = document.querySelector('.alert');
		if (alert) {
			setTimeout(() => alert.remove(), 3000);
		}

			const profilePhotoInput = document.getElementById('profile_photo');
			const profilePhotoPreview = document.getElementById('profilePhotoPreview');
			if (profilePhotoInput && profilePhotoPreview) {
				profilePhotoInput.addEventListener('change', function(e) {
					const [file] = e.target.files;
					if (!file) return;

					const reader = new FileReader();
					reader.onload = function(event) {
						profilePhotoPreview.src = event.target.result;
					};
					reader.readAsDataURL(file);
				});
			}

		const passwordForm = document.getElementById('passwordForm');
		if (passwordForm) {
			passwordForm.addEventListener('submit', function(e) {
				const newPass = document.querySelector('input[name="new_password"]').value;
				const confirmPass = document.querySelector('input[name="new_password_confirmation"]').value;
				if (newPass !== confirmPass) {
					e.preventDefault();
					if (window.Swal) {
						Swal.fire({
							icon: 'error',
							title: 'Password tidak cocok',
							text: 'Pastikan password baru dan konfirmasi sama.',
							confirmButtonColor: '#4f46e5'
						});
					}
				} else if (newPass.length < 8) {
					e.preventDefault();
					if (window.Swal) {
						Swal.fire({
							icon: 'error',
							title: 'Password terlalu pendek',
							text: 'Password minimal 8 karakter.',
							confirmButtonColor: '#4f46e5'
						});
					}
				}
			});
		}
	});
</script>
@endpush
