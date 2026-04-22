@php
    $currentRoute = request()->route()?->getName();
    $role = auth()->user()->role ?? null;
@endphp

<div class="sidebar p-3">
    <div class="sidebar-brand d-flex align-items-center gap-2 mb-5 px-2">
        <div class="brand-logo d-flex align-items-center justify-content-center">
            <i class="bi bi-book-fill text-white"></i>
        </div>
        <h5 class="mb-0 fw-bold flex-grow-1 brand-name">SoraLib</h5>
        <button type="button" class="btn btn-sm close-btn d-lg-none" aria-label="Tutup menu"
            onclick="toggleSidebar(false)">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <p class="section-label px-2 mb-2">MENU</p>
    <nav class="nav flex-column gap-1 mb-4">
        <a class="nav-link {{ $currentRoute === 'dashboard' ? 'active' : '' }}" href="{{ route('dashboard') }}"
            data-nav-link>
            <span class="nav-icon"><i class="bi bi-speedometer2"></i></span>
            <span>Dashboard</span>
        </a>

        @if ($role === 'admin')
            <a class="nav-link {{ $currentRoute === 'admin.anggota.list' ? 'active' : '' }}"
                href="{{ route('admin.anggota.list') }}" data-nav-link>
                <span class="nav-icon"><i class="bi bi-people"></i></span>
                <span>Kelola Anggota</span>
            </a>
            <a class="nav-link {{ $currentRoute === 'admin.administrator.list' ? 'active' : '' }}"
                href="{{ route('admin.administrator.list') }}" data-nav-link>
                <span class="nav-icon"><i class="bi bi-person-badge"></i></span>
                <span>Administrator</span>
            </a>
            <a class="nav-link {{ $currentRoute === 'admin.kategori.list' ? 'active' : '' }}"
                href="{{ route('admin.kategori.list') }}" data-nav-link>
                <span class="nav-icon"><i class="bi bi-tags"></i></span>
                <span>Kategori</span>
            </a>
            <a class="nav-link {{ $currentRoute === 'admin.buku.list' ? 'active' : '' }}"
                href="{{ route('admin.buku.list') }}" data-nav-link>
                <span class="nav-icon"><i class="bi bi-book-half"></i></span>
                <span>Buku</span>
            </a>
            <a class="nav-link {{ $currentRoute === 'admin.peminjaman.list' ? 'active' : '' }}"
                href="{{ route('admin.peminjaman.list') }}" data-nav-link>
                <span class="nav-icon"><i class="bi bi-clipboard-check"></i></span>
                <span>Peminjaman</span>
            </a>
            <a class="nav-link" href="#" data-nav-link>
                <span class="nav-icon"><i class="bi bi-arrow-return-left"></i></span>
                <span>Pengembalian</span>
            </a>
        @elseif($role === 'siswa')
            <a class="nav-link {{ $currentRoute === 'siswa.peminjaman.list' ? 'active' : '' }}"
                href="{{ route('siswa.peminjaman.list') }}" data-nav-link>
                <span class="nav-icon"><i class="bi bi-plus-circle"></i></span>
                <span>Pinjam Buku</span>
            </a>
            <a class="nav-link" href="#" data-nav-link>
                <span class="nav-icon"><i class="bi bi-clipboard-check"></i></span>
                <span>Riwayat Peminjaman</span>
            </a>
            <a class="nav-link" href="#" data-nav-link>
                <span class="nav-icon"><i class="bi bi-arrow-return-left"></i></span>
                <span>Riwayat Pengembalian</span>
            </a>
        @endif
    </nav>

    <p class="section-label px-2 mb-2">GENERAL</p>
    <nav class="nav flex-column gap-1">
        <a class="nav-link" href="#" data-nav-link>
            <span class="nav-icon"><i class="bi bi-person-circle"></i></span>
            <span>Profil</span>
        </a>

        <form id="logoutForm" method="POST" action="{{ route('auth.logout') }}" class="d-none">
            @csrf
        </form>
        <a class="nav-link nav-link-danger" href="#" onclick="confirmLogout(event)" data-ignore-active>
            <span class="nav-icon"><i class="bi bi-box-arrow-right"></i></span>
            <span>Logout</span>
        </a>
    </nav>
</div>

<style>
    .sidebar {
        background: linear-gradient(180deg, #1E4D35 0%, #2D6F4E 100%);
        min-height: 100vh;
        width: 260px;
        flex-shrink: 0;
        border-right: none;
        position: relative;
        z-index: 999;
        transition: transform 0.3s ease;
        display: flex;
        flex-direction: column;
    }

    .brand-logo {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        color: #ffffff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.25);
    }

    .brand-logo img {
        width: 36px;
        height: 36px;
        object-fit: cover;
    }

    .brand-name {
        font-size: 1.05rem;
        letter-spacing: -0.3px;
        color: #ffffff;
    }

    .close-btn {
        border: none;
        color: rgba(255, 255, 255, 0.5);
        padding: 0.25rem 0.5rem;
        background: transparent;
    }

    .close-btn:hover {
        color: #ffffff;
        background: rgba(255, 255, 255, 0.1);
    }

    .section-label {
        font-size: 0.68rem;
        font-weight: 600;
        letter-spacing: 0.08em;
        color: rgba(255, 255, 255, 0.4);
        text-transform: uppercase;
    }

    .nav-link {
        color: rgba(255, 255, 255, 0.7);
        padding: 0.6rem 1rem;
        border-radius: 0.625rem;
        transition: background-color 0.2s ease, color 0.2s ease;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .nav-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 7px;
        background: transparent;
        transition: background-color 0.2s ease;
        font-size: 0.95rem;
        flex-shrink: 0;
    }

    .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.1);
        color: #ffffff;
    }

    .nav-link:hover .nav-icon {
        background-color: rgba(255, 255, 255, 0.12);
        color: #ffffff;
    }

    .nav-link.active {
        background-color: rgba(255, 255, 255, 0.18);
        color: #ffffff;
        font-weight: 600;
    }

    .nav-link.active .nav-icon {
        background-color: rgba(255, 255, 255, 0.2);
        color: #ffffff;
    }

    .nav-link-danger {
        color: rgba(255, 180, 180, 0.8);
    }

    .nav-link-danger:hover {
        background-color: rgba(220, 38, 38, 0.2);
        color: #FCA5A5;
    }

    .nav-link-danger:hover .nav-icon {
        background-color: rgba(220, 38, 38, 0.2);
        color: #FCA5A5;
    }

    @media (max-width: 991.98px) {
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            transform: translateX(-100%);
            overflow-y: auto;
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.2);
        }
    }
</style>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.sidebar');
            if (!sidebar) return;
            const navLinks = sidebar.querySelectorAll('[data-nav-link]');
            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    if (this.hasAttribute('data-ignore-active')) return;

                    navLinks.forEach(item => item.classList.remove('active'));
                    this.classList.add('active');

                    if (window.innerWidth < 992 && typeof window.toggleSidebar === 'function') {
                        window.toggleSidebar(false);
                    }

                    if (this.getAttribute('href') === '#') {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
@endpush
