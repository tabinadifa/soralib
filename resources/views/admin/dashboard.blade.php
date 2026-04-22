@extends('layouts.layout')

@section('title', 'Dashboard Admin - SoraLib')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    .dashboard-wrapper {
        font-family: 'Poppins', sans-serif;
    }

    /* Page Header */
    .page-header {
        margin-bottom: 2rem;
        margin-top: 0.5rem;
    }
    .page-header h2 {
        font-family: 'Poppins', serif;
        font-size: 1.75rem;
        font-weight: 700;
        color: #1a2e22;
        margin-bottom: 0.25rem;
        letter-spacing: -0.3px;
    }
    .page-header p {
        color: #7a9485;
        font-size: 0.875rem;
        margin: 0;
    }

    /* Metric Cards */
    .stat-card {
        border: none;
        border-radius: 1.125rem;
        padding: 1.5rem;
        position: relative;
        overflow: hidden;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        animation: fadeSlideUp 0.5s ease both;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.1) !important;
    }
    .stat-card.primary {
        background: linear-gradient(135deg, #1E4D35 0%, #2D6F4E 60%, #3a8a62 100%);
        color: white;
        box-shadow: 0 8px 24px rgba(30,77,53,0.35);
    }
    .stat-card.soft {
        background: #ffffff;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    }
    .stat-card .deco-circle {
        position: absolute;
        border-radius: 50%;
        opacity: 0.08;
        background: white;
    }
    .stat-card.primary .deco-circle.c1 {
        width: 100px; height: 100px;
        top: -30px; right: -20px;
    }
    .stat-card.primary .deco-circle.c2 {
        width: 60px; height: 60px;
        bottom: -15px; right: 50px;
    }
    .stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        margin-bottom: 1rem;
    }
    .stat-card.primary .stat-icon {
        background: rgba(255,255,255,0.2);
        color: white;
    }
    .stat-icon.green {
        background: #E6F4EC;
        color: #2D6F4E;
    }
    .stat-icon.amber {
        background: #FEF3C7;
        color: #D97706;
    }
    .stat-icon.rose {
        background: #FEE2E2;
        color: #DC2626;
    }
    .stat-value {
        font-family: 'Poppins', serif;
        font-size: 2.25rem;
        font-weight: 700;
        line-height: 1;
        margin-bottom: 0.35rem;
    }
    .stat-card.primary .stat-value { color: white; }
    .stat-card.soft .stat-value { color: #1a2e22; }
    .stat-label {
        font-size: 0.8rem;
        font-weight: 500;
        margin-bottom: 0.75rem;
    }
    .stat-card.primary .stat-label { color: rgba(255,255,255,0.75); }
    .stat-card.soft .stat-label { color: #7a9485; }
    .stat-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        font-size: 0.75rem;
        font-weight: 500;
        padding: 0.25rem 0.6rem;
        border-radius: 20px;
    }
    .stat-card.primary .stat-badge {
        background: rgba(255,255,255,0.2);
        color: rgba(255,255,255,0.9);
    }
    .badge-green { background: #E6F4EC; color: #2D6F4E; }
    .badge-amber { background: #FEF3C7; color: #B45309; }
    .badge-rose  { background: #FEE2E2; color: #DC2626; }

    /* Chart Card */
    .chart-card {
        background: white;
        border-radius: 1.125rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        padding: 1.5rem;
        height: 100%;
        animation: fadeSlideUp 0.5s ease 0.1s both;
    }
    .card-title-sm {
        font-family: 'Poppins', sans-serif;
        font-size: 0.9rem;
        font-weight: 600;
        color: #1a2e22;
        margin-bottom: 0;
    }
    .chart-wrap {
        display: flex;
        align-items: flex-end;
        gap: 6px;
        height: 160px;
        padding-bottom: 4px;
    }
    .bar-col {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
        height: 100%;
        justify-content: flex-end;
    }
    .bar-fill {
        width: 100%;
        border-radius: 6px 6px 0 0;
        background: linear-gradient(180deg, #3a8a62 0%, #2D6F4E 100%);
        transition: opacity 0.2s;
        min-height: 8px;
    }
    .bar-fill:hover {
        opacity: 0.8;
    }
    .bar-fill.today {
        background: linear-gradient(180deg, #90AB8B 0%, #2D6F4E 100%);
        box-shadow: 0 4px 12px rgba(45,111,78,0.3);
    }
    .bar-num {
        font-size: 0.65rem;
        font-weight: 600;
        color: #7a9485;
    }
    .bar-day {
        font-size: 0.7rem;
        color: #b0bec5;
        font-weight: 500;
    }
    .bar-day.today-label {
        color: #2D6F4E;
        font-weight: 700;
    }

    /* Reminder */
    .reminder-item {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 0.75rem;
        border-radius: 0.75rem;
        margin-bottom: 0.5rem;
        transition: background 0.2s;
    }
    .reminder-item:hover {
        background: #F9FAFB;
    }
    .reminder-item.due { background: #FFFBEB; }
    .reminder-item.late { background: #FFF5F5; }
    .reminder-dot {
        width: 8px; height: 8px;
        border-radius: 50%;
        flex-shrink: 0;
        margin-top: 5px;
    }
    .dot-amber { background: #F59E0B; }
    .dot-red   { background: #EF4444; }
    .reminder-book {
        font-size: 0.85rem;
        font-weight: 600;
        color: #1a2e22;
        margin-bottom: 1px;
    }
    .reminder-meta {
        font-size: 0.75rem;
        color: #9CA3AF;
    }
    .reminder-tag {
        font-size: 0.7rem;
        font-weight: 600;
        padding: 0.15rem 0.5rem;
        border-radius: 20px;
        flex-shrink: 0;
    }
    .tag-due  { background: #FEF3C7; color: #B45309; }
    .tag-late { background: #FEE2E2; color: #DC2626; }

    /* Donut */
    .donut-wrap {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 2rem;
        flex-wrap: wrap;
    }
    .donut-ring {
        position: relative;
        width: 130px;
        height: 130px;
        flex-shrink: 0;
    }
    .donut-svg {
        transform: rotate(-90deg);
    }
    .donut-track {
        fill: none;
        stroke: #E5E7EB;
        stroke-width: 12;
    }
    .donut-fill {
        fill: none;
        stroke: url(#greenGrad);
        stroke-width: 12;
        stroke-linecap: round;
        stroke-dasharray: 282.6;
        transition: stroke-dashoffset 1s ease;
    }
    .donut-center {
        position: absolute;
        inset: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .donut-pct {
        font-family: 'Playfair Display', serif;
        font-size: 1.6rem;
        font-weight: 700;
        color: #1a2e22;
        line-height: 1;
    }
    .donut-sub {
        font-size: 0.65rem;
        color: #90AB8B;
        font-weight: 500;
        margin-top: 2px;
    }
    .donut-legend {
        display: flex;
        flex-direction: column;
        gap: 0.6rem;
    }
    .legend-row {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.8rem;
    }
    .legend-dot {
        width: 10px; height: 10px;
        border-radius: 50%;
        flex-shrink: 0;
    }
    .legend-val {
        font-weight: 700;
        color: #1a2e22;
        margin-left: auto;
        padding-left: 0.5rem;
    }

    /* Recent Loans */
    .loan-row {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.65rem 0;
        border-bottom: 1px solid #F3F4F6;
    }
    .loan-row:last-child { border-bottom: none; }
    .loan-avatar {
        width: 36px; height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        font-weight: 700;
        flex-shrink: 0;
        color: white;
    }
    .loan-title {
        font-size: 0.82rem;
        font-weight: 600;
        color: #1a2e22;
        margin-bottom: 1px;
    }
    .loan-member {
        font-size: 0.72rem;
        color: #9CA3AF;
    }
    .loan-status {
        margin-left: auto;
        font-size: 0.7rem;
        font-weight: 600;
        padding: 0.2rem 0.6rem;
        border-radius: 20px;
        flex-shrink: 0;
    }
    .status-active  { background: #E6F4EC; color: #2D6F4E; }
    .status-due     { background: #FEF3C7; color: #B45309; }
    .status-late    { background: #FEE2E2; color: #DC2626; }

    .section-divider {
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: #b0bec5;
        margin-bottom: 0.5rem;
        margin-top: 0.75rem;
    }

    @keyframes fadeSlideUp {
        from { opacity: 0; transform: translateY(16px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .stat-card:nth-child(1) { animation-delay: 0.0s; }
    .stat-card:nth-child(2) { animation-delay: 0.08s; }
    .stat-card:nth-child(3) { animation-delay: 0.16s; }
    .stat-card:nth-child(4) { animation-delay: 0.24s; }
</style>
@endpush

@section('content')
<div class="dashboard-wrapper">
    <div class="page-header">
        <h2>Dashboard Perpustakaan</h2>
        <p>Selamat datang kembali — ringkasan aktivitas hari ini, {{ now()->translatedFormat('l, d F Y') }}</p>
    </div>

    <!-- Metric Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card primary h-100">
                <div class="deco-circle c1"></div>
                <div class="deco-circle c2"></div>
                <div class="stat-icon"><i class="bi bi-book-half"></i></div>
                <div class="stat-label">Total Buku</div>
                <div class="stat-value">{{ number_format($totalBuku) }}</div>
                <span class="stat-badge mt-2"><i class="bi bi-arrow-up-short"></i> koleksi terbaru</span>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card soft h-100">
                <div class="stat-icon green"><i class="bi bi-clipboard-check"></i></div>
                <div class="stat-label">Sedang Dipinjam</div>
                <div class="stat-value">{{ $sedangDipinjam }}</div>
                <span class="stat-badge badge-green"><i class="bi bi-dot"></i> {{ round(($sedangDipinjam / max($totalBuku,1)) * 100, 1) }}% koleksi</span>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card soft h-100">
                <div class="stat-icon amber"><i class="bi bi-hourglass-split"></i></div>
                <div class="stat-label">Jatuh Tempo Hari Ini</div>
                <div class="stat-value">{{ $jatuhTempoHariIni }}</div>
                <span class="stat-badge badge-amber"><i class="bi bi-exclamation-circle"></i> Segera kembalikan</span>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card soft h-100">
                <div class="stat-icon rose"><i class="bi bi-x-circle"></i></div>
                <div class="stat-label">Terlambat</div>
                <div class="stat-value">{{ $terlambat }}</div>
                <span class="stat-badge badge-rose"><i class="bi bi-arrow-up-short"></i> perlu tindakan</span>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <!-- Bar Chart Mingguan -->
        <div class="col-md-6 col-lg-5">
            <div class="chart-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <span class="card-title-sm">Peminjaman Minggu Ini</span>
                    <span class="stat-badge badge-green"><i class="bi bi-bar-chart-fill"></i> {{ $totalMingguIni }} total</span>
                </div>
                <div class="chart-wrap">
                    @foreach($chartData as $bar)
                    <div class="bar-col">
                        <span class="bar-num">{{ $bar['val'] }}</span>
                        <div class="bar-fill {{ $bar['today'] ? 'today' : '' }}"
                             style="height: {{ $maxVal > 0 ? round(($bar['val'] / $maxVal) * 100) : 0 }}%">
                        </div>
                        <span class="bar-day {{ $bar['today'] ? 'today-label' : '' }}">{{ $bar['day'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Pengingat Pengembalian (DIPERBAIKI) -->
        <div class="col-md-6 col-lg-4">
            <div class="chart-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="card-title-sm">Pengingat Pengembalian</span>
                    <span class="stat-badge badge-amber">{{ $dueReminders->count() + $lateReminders->count() }} buku</span>
                </div>

                @if($dueReminders->count())
                <p class="section-divider">Jatuh Tempo</p>
                @foreach($dueReminders as $loan)
                <div class="reminder-item due">
                    <div class="reminder-dot dot-amber mt-1"></div>
                    <div class="flex-grow-1">
                        <div class="reminder-book">{{ $loan->buku->judul_buku ?? 'Buku' }}</div>
                        <div class="reminder-meta">{{ $loan->peminjam->name }} · {{ \Carbon\Carbon::parse($loan->tanggal_kembali)->translatedFormat('d M Y') }}</div>
                    </div>
                    <span class="reminder-tag tag-due">
                        @php
                            // Gunakan nilai sisa_hari dari controller (sudah dibulatkan)
                            $sisa = $loan->sisa_hari ?? '';
                            // Jika belum ada properti sisa_hari (fallback), hitung manual dengan pembulatan
                            if (empty($sisa)) {
                                $diff = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($loan->tanggal_kembali), false);
                                if ($diff == 0) $sisa = 'Hari Ini';
                                elseif ($diff == 1) $sisa = 'Besok';
                                else $sisa = round($diff, 2) . ' hari';
                            } else {
                                // Ubah "0.00 hari" menjadi "Hari Ini"
                                if (trim($sisa) == '0.00 hari' || trim($sisa) == '0 hari') $sisa = 'Hari Ini';
                                // Ubah "1.00 hari" menjadi "Besok"
                                elseif (trim($sisa) == '1.00 hari' || trim($sisa) == '1 hari') $sisa = 'Besok';
                            }
                        @endphp
                        {{ $sisa }}
                    </span>
                </div>
                @endforeach
                @endif

                @if($lateReminders->count())
                <p class="section-divider">Terlambat</p>
                @foreach($lateReminders as $loan)
                <div class="reminder-item late">
                    <div class="reminder-dot dot-red mt-1"></div>
                    <div class="flex-grow-1">
                        <div class="reminder-book">{{ $loan->buku->judul_buku ?? 'Buku' }}</div>
                        <div class="reminder-meta">
                            {{ $loan->peminjam->name }} · 
                            @php
                                $lateText = $loan->sisa_hari ?? '';
                                if (empty($lateText)) {
                                    $lateText = 'Terlambat ' . round(\Carbon\Carbon::parse($loan->tanggal_kembali)->diffInDays(\Carbon\Carbon::now()), 2) . ' hari';
                                }
                            @endphp
                            {{ $lateText }}
                        </div>
                    </div>
                    <span class="reminder-tag tag-late">
                        @php
                            if (preg_match('/[\d\.]+/', $lateText, $matches)) {
                                echo '+' . $matches[0] . ' hari';
                            } else {
                                echo 'Terlambat';
                            }
                        @endphp
                    </span>
                </div>
                @endforeach
                @endif

                @if($dueReminders->isEmpty() && $lateReminders->isEmpty())
                <div class="text-center text-muted py-4">Semua pengembalian tepat waktu</div>
                @endif
            </div>
        </div>

        <!-- Donut Ringkasan -->
        <div class="col-md-6 col-lg-3">
            <div class="chart-card">
                <span class="card-title-sm d-block mb-4">Tingkat Pengembalian</span>
                <div class="donut-wrap">
                    <div class="donut-ring">
                        <svg class="donut-svg" width="130" height="130" viewBox="0 0 100 100">
                            <defs>
                                <linearGradient id="greenGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" style="stop-color:#3a8a62"/>
                                    <stop offset="100%" style="stop-color:#1E4D35"/>
                                </linearGradient>
                            </defs>
                            <circle class="donut-track" cx="50" cy="50" r="45"/>
                            <circle class="donut-fill" cx="50" cy="50" r="45" style="stroke-dashoffset: {{ 282.6 - (282.6 * $returnRate / 100) }}"/>
                        </svg>
                        <div class="donut-center">
                            <span class="donut-pct">{{ $returnRate }}%</span>
                            <span class="donut-sub">kembali</span>
                        </div>
                    </div>
                    <div class="donut-legend">
                        <div class="legend-row"><span class="legend-dot" style="background:#2D6F4E"></span><span class="text-muted">Dikembalikan</span><span class="legend-val">{{ $totalPeminjamanSelesai }}</span></div>
                        <div class="legend-row"><span class="legend-dot" style="background:#E5E7EB"></span><span class="text-muted">Belum Kembali</span><span class="legend-val">{{ $totalPeminjamanAktif }}</span></div>
                        <div class="legend-row"><span class="legend-dot" style="background:#EF4444"></span><span class="text-muted">Terlambat</span><span class="legend-val">{{ $terlambat }}</span></div>
                        <div style="margin-top:0.5rem; padding-top:0.5rem; border-top:1px solid #F3F4F6; font-size:0.75rem; color:#7a9485;">{{ $totalPeminjamanSelesai + $totalPeminjamanAktif }} total peminjaman</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Peminjaman Terbaru -->
        <div class="col-12">
            <div class="chart-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="card-title-sm">Peminjaman Terbaru</span>
                    <a href="{{ route('admin.peminjaman.list') }}" class="text-decoration-none" style="font-size:0.8rem; color:#2D6F4E; font-weight:600;">Lihat semua <i class="bi bi-arrow-right"></i></a>
                </div>
                @forelse($recentLoans as $loan)
                <div class="loan-row">
                    <div class="loan-avatar" style="background: {{ $loop->iteration % 2 == 0 ? '#2563EB' : '#2D6F4E' }};">
                        {{ strtoupper(substr($loan->buku->judul_buku ?? 'B', 0, 1)) }}
                    </div>
                    <div class="flex-grow-1">
                        <div class="loan-title">{{ $loan->buku->judul_buku ?? 'Buku tidak ditemukan' }}</div>
                        <div class="loan-member">{{ $loan->peminjam->name }} · {{ $loan->peminjam->kelas ?? 'Anggota' }}</div>
                    </div>
                    @php
                        $isLate = \Carbon\Carbon::parse($loan->tanggal_kembali)->isPast();
                    @endphp
                    <span class="loan-status 
                        @if($isLate) status-late
                        @elseif(\Carbon\Carbon::parse($loan->tanggal_kembali)->isToday()) status-due
                        @else status-active @endif">
                        @if($isLate) Terlambat
                        @elseif(\Carbon\Carbon::parse($loan->tanggal_kembali)->isToday()) Jatuh Tempo
                        @else Aktif @endif
                    </span>
                </div>
                @empty
                <div class="text-center text-muted py-4">Belum ada data peminjaman.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection