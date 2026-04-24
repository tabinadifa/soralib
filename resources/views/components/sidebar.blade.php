@php
    $currentRoute = request()->route()?->getName();
    $role = auth()->user()->role ?? null;
@endphp

<button type="button" class="sidebar-toggle-btn d-lg-none" aria-label="Buka menu" aria-expanded="false"
    onclick="toggleSidebar()">
    <i class="bi bi-list"></i>
</button>

<div class="sidebar p-3">
    <div class="sidebar-brand d-flex align-items-center gap-2 mb-5 px-2">
        <div class="brand-logo d-flex align-items-center justify-content-center">
            <i class="bi bi-book-fill text-white"></i>
        </div>
        <h5 class="mb-0 fw-bold flex-grow-1 brand-name">SoraLib</h5>
        <button type="button" class="btn btn-sm collapse-btn d-none d-lg-inline-flex" aria-label="Kecilkan sidebar"
            onclick="toggleSidebarCollapse()">
            <i class="bi bi-chevron-left"></i>
        </button>
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
            <a class="nav-link {{ $currentRoute === 'admin.pengembalian.list' ? 'active' : '' }}" href="{{ route('admin.pengembalian.list') }}" data-nav-link>
                <span class="nav-icon"><i class="bi bi-arrow-return-left"></i></span>
                <span>Pengembalian</span>
            </a>
        @elseif($role === 'siswa')
            <a class="nav-link {{ $currentRoute === 'siswa.peminjaman.list' ? 'active' : '' }}"
                href="{{ route('siswa.peminjaman.list') }}" data-nav-link>
                <span class="nav-icon"><i class="bi bi-plus-circle"></i></span>
                <span>Pinjam Buku</span>
            </a>
            <a class="nav-link {{ $currentRoute === 'siswa.peminjaman.riwayat' ? 'active' : '' }}" href="{{ route('siswa.peminjaman.riwayat') }}" data-nav-link>
                <span class="nav-icon"><i class="bi bi-clipboard-check"></i></span>
                <span>Riwayat Peminjaman</span>
            </a>
            <a class="nav-link {{ str_starts_with((string) $currentRoute, 'siswa.pengembalian.') ? 'active' : '' }}" href="{{ route('siswa.pengembalian.list') }}" data-nav-link>
                <span class="nav-icon"><i class="bi bi-arrow-return-left"></i></span>
                <span>Riwayat Pengembalian</span>
            </a>
        @endif
    </nav>

    <p class="section-label px-2 mb-2">GENERAL</p>
    <nav class="nav flex-column gap-1">
        <a class="nav-link {{ $currentRoute === 'profile' ? 'active' : '' }}" href="{{ route('profile') }}" data-nav-link>
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
    .sidebar-toggle-btn {
        position: fixed;
        top: 12px;
        left: 12px;
        width: 40px;
        height: 40px;
        border: none;
        border-radius: 10px;
        background: #F3F4F6;
        color: #374151;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1001;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
        transition: background-color 0.2s ease, opacity 0.2s ease;
    }

    .sidebar-toggle-btn:hover {
        background: #E5E7EB;
    }

    body.sidebar-open .sidebar-toggle-btn {
        opacity: 0;
        pointer-events: none;
    }

    .sidebar {
        background: linear-gradient(180deg, #1E4D35 0%, #2D6F4E 100%);
        min-height: 100vh;
        width: 260px;
        flex-shrink: 0;
        border-right: none;
        position: relative;
        z-index: 999;
        transition: transform 0.3s ease, width 0.25s ease, padding 0.25s ease;
        display: flex;
        flex-direction: column;
    }

    .collapse-btn {
        border: none;
        color: rgba(255, 255, 255, 0.7);
        width: 30px;
        height: 30px;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.08);
        align-items: center;
        justify-content: center;
        padding: 0;
        transition: background-color 0.2s ease, color 0.2s ease;
    }

    .collapse-btn:hover {
        background: rgba(255, 255, 255, 0.18);
        color: #ffffff;
    }

    body.sidebar-collapsed .sidebar {
        width: 88px;
        padding-left: 0.5rem !important;
        padding-right: 0.5rem !important;
    }

    body.sidebar-collapsed .brand-name,
    body.sidebar-collapsed .section-label,
    body.sidebar-collapsed .nav-link > span:last-child {
        display: none;
    }

    body.sidebar-collapsed .sidebar-brand {
        justify-content: center;
        gap: 0 !important;
    }

    body.sidebar-collapsed .brand-logo {
        margin-right: 0 !important;
    }

    body.sidebar-collapsed .collapse-btn {
        position: absolute;
        right: 8px;
        transform: rotate(180deg);
    }

    body.sidebar-collapsed .nav-link {
        justify-content: center;
        padding-left: 0.5rem;
        padding-right: 0.5rem;
        gap: 0;
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

    @media (min-width: 992px) {
        .sidebar-toggle-btn {
            display: none;
        }
    }
</style>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.sidebar');
            if (!sidebar) return;

            const toggleButton = document.querySelector('.sidebar-toggle-btn');
            const collapseButton = document.querySelector('.collapse-btn');
            const collapseStorageKey = 'sidebar-collapsed';

            const applyCollapsedState = (collapsed) => {
                document.body.classList.toggle('sidebar-collapsed', collapsed);
                if (!collapseButton) return;

                collapseButton.setAttribute('aria-expanded', String(!collapsed));
                collapseButton.setAttribute('aria-label', collapsed ? 'Lebarkan sidebar' : 'Kecilkan sidebar');
            };

            window.toggleSidebarCollapse = function(forceState = null) {
                if (window.innerWidth < 992) return;

                const isCollapsed = document.body.classList.contains('sidebar-collapsed');
                const shouldCollapse = forceState ?? !isCollapsed;
                applyCollapsedState(shouldCollapse);
                localStorage.setItem(collapseStorageKey, shouldCollapse ? '1' : '0');
            };

            const syncToggleState = () => {
                if (!toggleButton) return;
                const isOpen = document.body.classList.contains('sidebar-open');
                toggleButton.setAttribute('aria-expanded', String(isOpen));
            };

            syncToggleState();

            const savedCollapsedState = localStorage.getItem(collapseStorageKey) === '1';
            if (window.innerWidth >= 992) {
                applyCollapsedState(savedCollapsedState);
            }

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

                    syncToggleState();
                });
            });

            document.addEventListener('click', function(e) {
                if (e.target.closest('[onclick*="toggleSidebar"]')) {
                    requestAnimationFrame(syncToggleState);
                }
            });

            window.addEventListener('resize', syncToggleState);
            window.addEventListener('resize', () => {
                const savedState = localStorage.getItem(collapseStorageKey) === '1';
                if (window.innerWidth >= 992) {
                    applyCollapsedState(savedState);
                } else {
                    applyCollapsedState(false);
                }
            });
        });
    </script>
@endpush
