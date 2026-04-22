<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SoraLib — Perpustakaan Sekolah</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --green-dark:  #1E4D35;
            --green-mid:   #2D6F4E;
            --green-light: #90AB8B;
            --green-pale:  #E8F5E9;
            --cream:       #F7F5F0;
            --text-dark:   #1a2e22;
            --text-muted:  #7a9485;
        }

        html, body {
            height: 100%;
            font-family: 'Poppins', sans-serif;
            background-color: var(--cream);
            color: var(--text-dark);
            overflow-x: hidden;
        }

        /* ── NOISE TEXTURE OVERLAY ── */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.03'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 0;
        }

        /* ── NAVBAR ── */
        .navbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.25rem 3rem;
            background: rgba(247, 245, 240, 0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(144, 171, 139, 0.2);
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            text-decoration: none;
        }

        .brand-icon {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--green-dark), var(--green-mid));
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            color: white;
            font-size: 1rem;
        }

        .brand-text {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--green-dark);
            letter-spacing: -0.3px;
        }

        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .btn-ghost {
            padding: 0.5rem 1.25rem;
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--green-dark);
            background: transparent;
            border: 1.5px solid transparent;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .btn-ghost:hover {
            border-color: var(--green-mid);
            background: var(--green-pale);
        }

        .btn-solid {
            padding: 0.5rem 1.4rem;
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            font-size: 0.85rem;
            font-weight: 600;
            color: white;
            background: linear-gradient(135deg, var(--green-dark), var(--green-mid));
            border: none;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(30, 77, 53, 0.3);
        }

        .btn-solid:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(30, 77, 53, 0.4);
        }

        /* ── HERO ── */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 6rem 3rem 3rem;
            position: relative;
            overflow: hidden;
        }

        /* Decorative background blobs */
        .hero::after {
            content: '';
            position: absolute;
            width: 600px; height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(144,171,139,0.18) 0%, transparent 70%);
            top: -100px; right: -100px;
            pointer-events: none;
        }

        .hero-inner {
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
            position: relative;
            z-index: 1;
        }

        /* ── LEFT COPY ── */
        .hero-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--green-pale);
            color: var(--green-dark);
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            padding: 0.4rem 0.9rem;
            border-radius: 20px;
            margin-bottom: 1.5rem;
            border: 1px solid rgba(45, 111, 78, 0.2);
            animation: fadeUp 0.6s ease both;
        }

        .hero-eyebrow-dot {
            width: 7px; height: 7px;
            border-radius: 50%;
            background: var(--green-mid);
            animation: pulse 2s infinite;
        }

        .hero-title {
            font-size: clamp(2.4rem, 4vw, 3.5rem);
            font-weight: 800;
            line-height: 1.1;
            letter-spacing: -1px;
            color: var(--text-dark);
            margin-bottom: 1.25rem;
            animation: fadeUp 0.6s ease 0.1s both;
        }

        .hero-title .accent {
            color: var(--green-mid);
            position: relative;
            display: inline-block;
        }

        .hero-title .accent::after {
            content: '';
            position: absolute;
            bottom: 2px; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--green-mid), var(--green-light));
            border-radius: 2px;
        }

        .hero-desc {
            font-size: 1rem;
            font-weight: 400;
            color: var(--text-muted);
            line-height: 1.75;
            margin-bottom: 2.5rem;
            max-width: 440px;
            animation: fadeUp 0.6s ease 0.2s both;
        }

        .hero-cta {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            animation: fadeUp 0.6s ease 0.3s both;
        }

        .cta-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.85rem 2rem;
            background: linear-gradient(135deg, var(--green-dark), var(--green-mid));
            color: white;
            font-family: 'Poppins', sans-serif;
            font-size: 0.9rem;
            font-weight: 600;
            border-radius: 12px;
            text-decoration: none;
            box-shadow: 0 8px 24px rgba(30, 77, 53, 0.35);
            transition: all 0.25s ease;
        }

        .cta-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(30, 77, 53, 0.45);
        }

        .cta-secondary {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.85rem 1.75rem;
            background: white;
            color: var(--green-dark);
            font-family: 'Poppins', sans-serif;
            font-size: 0.9rem;
            font-weight: 500;
            border-radius: 12px;
            text-decoration: none;
            border: 1.5px solid rgba(45, 111, 78, 0.25);
            transition: all 0.25s ease;
        }

        .cta-secondary:hover {
            border-color: var(--green-mid);
            background: var(--green-pale);
        }

        /* ── STATS ROW ── */
        .stats-row {
            display: flex;
            gap: 2rem;
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(144,171,139,0.25);
            animation: fadeUp 0.6s ease 0.4s both;
        }

        .stat-item {
            display: flex;
            flex-direction: column;
        }

        .stat-num {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--green-dark);
            line-height: 1;
        }

        .stat-lbl {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-top: 3px;
        }

        /* ── RIGHT VISUAL ── */
        .hero-visual {
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            animation: fadeUp 0.7s ease 0.15s both;
        }

        /* Book stack illustration */
        .book-stack {
            position: relative;
            width: 320px;
            height: 380px;
        }

        .book {
            position: absolute;
            border-radius: 6px 12px 12px 6px;
            box-shadow: 4px 6px 20px rgba(0,0,0,0.12);
        }

        /* Book spine shadow */
        .book::before {
            content: '';
            position: absolute;
            left: 0; top: 0; bottom: 0;
            width: 16px;
            background: rgba(0,0,0,0.1);
            border-radius: 6px 0 0 6px;
        }

        .book-1 {
            width: 180px; height: 260px;
            background: linear-gradient(135deg, #1E4D35, #2D6F4E);
            top: 50px; left: 50px;
            transform: rotate(-4deg);
        }

        .book-2 {
            width: 160px; height: 230px;
            background: linear-gradient(135deg, #3a6b55, #5a9e7a);
            top: 70px; left: 120px;
            transform: rotate(3deg);
        }

        .book-3 {
            width: 150px; height: 210px;
            background: linear-gradient(135deg, #90AB8B, #a8c4a3);
            top: 100px; left: 170px;
            transform: rotate(6deg);
        }

        .book-4 {
            width: 140px; height: 195px;
            background: linear-gradient(135deg, #c8dfc5, #deeedd);
            top: 120px; left: 60px;
            transform: rotate(-8deg);
        }

        /* Floating cards */
        .float-card {
            position: absolute;
            background: white;
            border-radius: 14px;
            padding: 0.85rem 1.1rem;
            box-shadow: 0 8px 28px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 0.6rem;
            font-size: 0.78rem;
            white-space: nowrap;
            z-index: 10;
        }

        .float-card-icon {
            width: 32px; height: 32px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .float-card .fc-label { font-weight: 600; color: var(--text-dark); font-size: 0.8rem; }
        .float-card .fc-sub   { color: var(--text-muted); font-size: 0.7rem; }

        .fc-books {
            top: 10px; left: -30px;
            animation: float 4s ease-in-out infinite;
        }

        .fc-books .float-card-icon { background: var(--green-pale); }

        .fc-return {
            bottom: 30px; right: -20px;
            animation: float 4s ease-in-out 1.5s infinite;
        }

        .fc-return .float-card-icon { background: #FEF3C7; }

        /* ── FEATURES SECTION ── */
        .features {
            padding: 5rem 3rem;
            background: white;
            position: relative;
        }

        .features::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(144,171,139,0.4), transparent);
        }

        .features-inner {
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-label {
            text-align: center;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--green-mid);
            margin-bottom: 0.75rem;
        }

        .section-title {
            text-align: center;
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.75rem;
            letter-spacing: -0.5px;
        }

        .section-sub {
            text-align: center;
            color: var(--text-muted);
            font-size: 0.9rem;
            max-width: 480px;
            margin: 0 auto 3.5rem;
            line-height: 1.7;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
        }

        .feature-card {
            padding: 1.75rem;
            border-radius: 1.125rem;
            border: 1px solid #EAECF0;
            transition: all 0.25s ease;
        }

        .feature-card:hover {
            border-color: rgba(45,111,78,0.3);
            box-shadow: 0 8px 24px rgba(30,77,53,0.08);
            transform: translateY(-3px);
        }

        .feature-icon {
            width: 48px; height: 48px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem;
            margin-bottom: 1.25rem;
        }

        .feature-card h3 {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .feature-card p {
            font-size: 0.825rem;
            color: var(--text-muted);
            line-height: 1.7;
        }

        /* ── FOOTER ── */
        .footer {
            padding: 2rem 3rem;
            text-align: center;
            font-size: 0.8rem;
            color: var(--text-muted);
            border-top: 1px solid #EAECF0;
            background: var(--cream);
        }

        .footer span { color: var(--green-mid); font-weight: 500; }

        /* ── ANIMATIONS ── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50%       { transform: translateY(-8px); }
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50%       { opacity: 0.4; }
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 900px) {
            .navbar { padding: 1rem 1.5rem; }

            .hero {
                padding: 5rem 1.5rem 2rem;
            }

            .hero-inner {
                grid-template-columns: 1fr;
                gap: 3rem;
                text-align: center;
            }

            .hero-desc { margin-left: auto; margin-right: auto; }
            .hero-cta  { justify-content: center; }
            .stats-row { justify-content: center; }

            .hero-visual { display: none; }

            .features { padding: 3rem 1.5rem; }
            .features-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    {{-- NAVBAR --}}
    <nav class="navbar">
        <a href="/" class="navbar-brand">
            <div class="brand-icon">📚</div>
            <span class="brand-text">SoraLib</span>
        </a>

        <div class="navbar-actions">
            @if (Route::has('auth.login'))
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-solid">Dashboard</a>
                @else
                    <a href="{{ route('auth.login') }}" class="btn-ghost">Masuk</a>
                    @if (Route::has('auth.register'))
                        <a href="{{ route('auth.register') }}" class="btn-solid">Daftar</a>
                    @endif
                @endauth
            @endif
        </div>
    </nav>

    {{-- HERO --}}
    <section class="hero">
        <div class="hero-inner">

            {{-- Left: Copy --}}
            <div>
                <div class="hero-eyebrow">
                    <span class="hero-eyebrow-dot"></span>
                    Sistem Perpustakaan Sekolah
                </div>

                <h1 class="hero-title">
                    Kelola Buku,<br>
                    Mudah &amp; <span class="accent">Efisien</span>
                </h1>

                <p class="hero-desc">
                    SoraLib hadir untuk memudahkan pengelolaan peminjaman buku di sekolah Anda — dari pencatatan koleksi, peminjaman, hingga pengembalian, semua dalam satu platform.
                </p>

                <div class="hero-cta">
                    @if (Route::has('auth.register'))
                        <a href="{{ route('auth.register') }}" class="cta-primary">
                            Mulai Sekarang
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </a>
                    @endif
                    @if (Route::has('auth.login'))
                        <a href="{{ route('auth.login') }}" class="cta-secondary">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M15 12H3"/></svg>
                            Sudah Punya Akun
                        </a>
                    @endif
                </div>

                <div class="stats-row">
                    <div class="stat-item">
                        <span class="stat-num">1.200+</span>
                        <span class="stat-lbl">Koleksi Buku</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-num">350+</span>
                        <span class="stat-lbl">Anggota Aktif</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-num">98%</span>
                        <span class="stat-lbl">Tingkat Pengembalian</span>
                    </div>
                </div>
            </div>

            {{-- Right: Visual --}}
            <div class="hero-visual">
                <div class="book-stack">
                    <div class="book book-4"></div>
                    <div class="book book-1"></div>
                    <div class="book book-2"></div>
                    <div class="book book-3"></div>
                </div>
            </div>

        </div>
    </section>

    {{-- FEATURES --}}
    <section class="features">
        <div class="features-inner">
            <p class="section-label">Fitur Utama</p>
            <h2 class="section-title">Semua yang Anda Butuhkan</h2>
            <p class="section-sub">Dirancang khusus untuk perpustakaan sekolah agar proses pengelolaan buku menjadi lebih teratur dan transparan.</p>

            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon" style="background:#E6F4EC;">📚</div>
                    <h3>Manajemen Koleksi</h3>
                    <p>Catat dan kelola seluruh koleksi buku perpustakaan dengan mudah — lengkap dengan kategori, stok, dan detail buku.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon" style="background:#FEF3C7;">📋</div>
                    <h3>Peminjaman & Pengembalian</h3>
                    <p>Proses transaksi peminjaman dan pengembalian buku secara cepat dengan pencatatan otomatis dan notifikasi jatuh tempo.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon" style="background:#E6F4EC;">🔐</div>
                    <h3>Multi Pengguna</h3>
                    <p>Dukungan dua peran pengguna — Admin dan Siswa — dengan hak akses yang terpisah sesuai kebutuhan masing-masing.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="footer">
        <p>&copy; {{ date('Y') }} <span>SoraLib</span> — Sistem Perpustakaan Sekolah.</p>
    </footer>

</body>
</html>