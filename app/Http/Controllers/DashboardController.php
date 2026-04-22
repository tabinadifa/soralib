<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return $this->adminDashboard();
        }

        // Default untuk siswa (anggota)
        return $this->siswaDashboard();
    }

    /**
     * Dashboard untuk Admin
     */
    private function adminDashboard()
    {
        // Total Buku
        $totalBuku = Buku::count();

        // Buku yang sedang dipinjam (status approve dan belum dikembalikan)
        $sedangDipinjam = Peminjaman::where('status', 'approve')
            ->whereDoesntHave('pengembalian')
            ->count();

        // Jatuh tempo hari ini
        $jatuhTempoHariIni = Peminjaman::where('status', 'approve')
            ->whereDoesntHave('pengembalian')
            ->whereDate('tanggal_kembali', Carbon::today())
            ->count();

        // Terlambat (tanggal_kembali < hari ini dan belum dikembalikan)
        $terlambat = Peminjaman::where('status', 'approve')
            ->whereDoesntHave('pengembalian')
            ->whereDate('tanggal_kembali', '<', Carbon::today())
            ->count();

        // Data peminjaman per hari dalam 7 hari terakhir (untuk chart)
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $dailyLoans = Peminjaman::select(
                DB::raw('DATE(tanggal_pinjam) as date'),
                DB::raw('COUNT(*) as total')
            )
            ->whereBetween('tanggal_pinjam', [$startOfWeek, $endOfWeek])
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        $days = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
        $chartData = [];
        $maxVal = 0;

        foreach ($days as $index => $day) {
            $date = $startOfWeek->copy()->addDays($index);
            $val = $dailyLoans->get($date->toDateString())->total ?? 0;
            $chartData[] = [
                'day'   => $day,
                'val'   => $val,
                'today' => $date->isToday(),
            ];
            if ($val > $maxVal) $maxVal = $val;
        }

        // Total peminjaman minggu ini
        $totalMingguIni = collect($chartData)->sum('val');

        // Pengingat pengembalian (jatuh tempo 3 hari ke depan + terlambat)
        $dueReminders = Peminjaman::with(['buku', 'peminjam'])
            ->where('status', 'approve')
            ->whereDoesntHave('pengembalian')
            ->whereBetween('tanggal_kembali', [Carbon::today(), Carbon::today()->addDays(3)])
            ->orderBy('tanggal_kembali')
            ->limit(5)
            ->get();

        $lateReminders = Peminjaman::with(['buku', 'peminjam'])
            ->where('status', 'approve')
            ->whereDoesntHave('pengembalian')
            ->whereDate('tanggal_kembali', '<', Carbon::today())
            ->orderBy('tanggal_kembali')
            ->limit(5)
            ->get();

        // Statistik donut (tingkat pengembalian)
        $totalPeminjamanSelesai = Peminjaman::whereHas('pengembalian')->count();
        $totalPeminjamanAktif = Peminjaman::where('status', 'approve')
            ->whereDoesntHave('pengembalian')
            ->count();
        $totalPeminjamanAll = $totalPeminjamanSelesai + $totalPeminjamanAktif;

        $returnRate = $totalPeminjamanAll > 0 ? round(($totalPeminjamanSelesai / $totalPeminjamanAll) * 100) : 0;

        // Peminjaman terbaru (5 data)
        $recentLoans = Peminjaman::with(['buku', 'peminjam'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Kirim ke view admin dashboard
        return view('admin.dashboard', compact(
            'totalBuku',
            'sedangDipinjam',
            'jatuhTempoHariIni',
            'terlambat',
            'chartData',
            'maxVal',
            'totalMingguIni',
            'dueReminders',
            'lateReminders',
            'returnRate',
            'totalPeminjamanSelesai',
            'totalPeminjamanAktif',
            'totalPeminjamanAll',
            'recentLoans'
        ));
    }

    /**
     * Dashboard untuk Siswa (Anggota)
     */
    private function siswaDashboard()
    {
        $user = Auth::user();

        // Peminjaman aktif (status approve dan belum dikembalikan)
        $peminjamanAktif = Peminjaman::with('buku')
            ->where('peminjam_id', $user->id)
            ->where('status', 'approve')
            ->whereDoesntHave('pengembalian')
            ->orderBy('tanggal_pinjam', 'desc')
            ->get();

        // Riwayat peminjaman (sudah dikembalikan atau ditolak)
        $riwayat = Peminjaman::with('buku', 'pengembalian')
            ->where('peminjam_id', $user->id)
            ->where(function ($q) {
                $q->where('status', 'rejected')
                  ->orWhereHas('pengembalian');
            })
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Jumlah buku yang sedang dipinjam
        $jumlahDipinjam = $peminjamanAktif->sum('total_buku');

        // Jumlah buku yang pernah dipinjam (seluruh peminjaman yang disetujui)
        $totalPernahDipinjam = Peminjaman::where('peminjam_id', $user->id)
            ->where('status', 'approve')
            ->sum('total_buku');

        // Apakah ada peminjaman yang terlambat
        $adaTerlambat = Peminjaman::where('peminjam_id', $user->id)
            ->where('status', 'approve')
            ->whereDoesntHave('pengembalian')
            ->whereDate('tanggal_kembali', '<', Carbon::today())
            ->exists();

        // Data untuk chart peminjaman per bulan (6 bulan terakhir)
        $sixMonths = collect();
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $sixMonths->push($month);
        }

        $monthlyLoans = Peminjaman::where('peminjam_id', $user->id)
            ->where('status', 'approve')
            ->select(
                DB::raw('YEAR(tanggal_pinjam) as year'),
                DB::raw('MONTH(tanggal_pinjam) as month'),
                DB::raw('SUM(total_buku) as total')
            )
            ->groupBy('year', 'month')
            ->get()
            ->keyBy(function ($item) {
                return $item->year . '-' . str_pad($item->month, 2, '0', STR_PAD_LEFT);
            });

        $chartMonthly = [];
        foreach ($sixMonths as $month) {
            $key = $month->format('Y-m');
            $val = $monthlyLoans->get($key)->total ?? 0;
            $chartMonthly[] = [
                'month' => $month->translatedFormat('M'),
                'val'   => $val,
            ];
        }

        return view('siswa.dashboard', compact(
            'user',
            'peminjamanAktif',
            'riwayat',
            'jumlahDipinjam',
            'totalPernahDipinjam',
            'adaTerlambat',
            'chartMonthly'
        ));
    }
}