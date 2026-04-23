@php
	$user = auth()->user();
	$userName = optional(auth()->user())->name ?? session('user_name') ?? 'Pengguna';
	$userEmail = optional($user)->email ?? '';
	$userPhoto = optional($user)->profile_photo_url;
	$userInitial = strtoupper(substr($userName, 0, 1));
@endphp

<header class="app-header d-flex align-items-center justify-content-between gap-3 px-4">
	<button type="button" class="hamburger-btn d-lg-none" aria-label="Buka menu" onclick="toggleSidebar()">
		<i class="bi bi-list"></i>
	</button>

	<div class="ms-auto d-flex align-items-center gap-3">
		<div class="dropdown">
			<button class="user-pill dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
				@if ($userPhoto)
					<img src="{{ $userPhoto }}" alt="Foto profil {{ $userName }}" class="user-avatar-image">
				@else
					<div class="user-avatar">
						{{ $userInitial }}
					</div>
				@endif
				<span class="user-name d-none d-sm-inline">{{ $userName }}</span>
				<i class="bi bi-chevron-down chevron-icon"></i>
			</button>
			<ul class="dropdown-menu dropdown-menu-end user-dropdown shadow-sm border-0 mt-2">
				<li class="dropdown-header-info px-3 py-2">
					<p class="mb-0 fw-semibold text-dark" style="font-size: 0.875rem;">{{ $userName }}</p>
					<p class="mb-0 text-muted" style="font-size: 0.75rem;">{{ $userEmail }}</p>
				</li>
				<li><hr class="dropdown-divider my-1"></li>
				<li>
					<a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('profile') }}">
						<i class="bi bi-person text-muted"></i>
						<span>Profile</span>
					</a>
				</li>
				<li><hr class="dropdown-divider my-1"></li>
				<li>
					<button type="button" class="dropdown-item d-flex align-items-center gap-2 text-danger" onclick="confirmLogout(event)">
						<i class="bi bi-box-arrow-right"></i>
						<span>Logout</span>
					</button>
				</li>
			</ul>
		</div>
	</div>
</header>

<style>
	.app-header {
		height: 64px;
		background: #ffffff;
		border-bottom: 1px solid #EAECF0;
		position: sticky;
		top: 0;
		z-index: 100;
	}

	.hamburger-btn {
		border: none;
		background: #F3F4F6;
		color: #374151;
		width: 36px;
		height: 36px;
		border-radius: 8px;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 1.1rem;
		transition: background-color 0.2s;
	}

	.hamburger-btn:hover {
		background: #E5E7EB;
	}

	.user-pill {
		border: 1px solid #E5E7EB;
		background: #F9FAFB;
		border-radius: 40px;
		padding: 0.35rem 0.85rem 0.35rem 0.4rem;
		display: flex;
		align-items: center;
		gap: 0.5rem;
		transition: all 0.2s ease;
		text-decoration: none;
		color: #374151;
	}

	.user-pill:hover,
	.user-pill[aria-expanded="true"] {
		border-color: #90AB8B;
		background: #F0F7F2;
		color: #2D6F4E;
	}

	.user-pill::after {
		display: none;
	}

	.user-avatar {
		width: 28px;
		height: 28px;
		border-radius: 50%;
		background: linear-gradient(135deg, #2D6F4E, #90AB8B);
		color: white;
		font-size: 0.75rem;
		font-weight: 700;
		display: flex;
		align-items: center;
		justify-content: center;
		flex-shrink: 0;
	}

	.user-avatar-image {
		width: 28px;
		height: 28px;
		border-radius: 50%;
		object-fit: cover;
		flex-shrink: 0;
	}

	.user-name {
		font-size: 0.875rem;
		font-weight: 500;
		max-width: 140px;
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
	}

	.chevron-icon {
		font-size: 0.7rem;
		color: #9CA3AF;
	}

	.user-dropdown {
		min-width: 200px;
		border-radius: 0.75rem;
		overflow: hidden;
	}

	.dropdown-header-info {
		background: #F9FAFB;
	}

	.user-dropdown .dropdown-item {
		font-size: 0.875rem;
		padding: 0.5rem 1rem;
		transition: background-color 0.15s;
	}

	.user-dropdown .dropdown-item:hover {
		background-color: #F0F7F2;
	}

	.user-dropdown .dropdown-item.text-danger:hover {
		background-color: #FEF2F2;
	}
</style>