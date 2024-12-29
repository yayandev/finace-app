<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        // Ambil tahun dari request atau gunakan tahun sekarang sebagai default
        $year = $request->input('year', now()->year);

        // Tentukan awal dan akhir bulan saat ini
        $start_date = now()->startOfMonth();
        $end_date = now()->endOfMonth();

        // Total keseluruhan transaksi
        $transactions = Transaction::whereYear('transaction_date', $year)->get();

        // Hitung total pemasukan dan pengeluaran
        $income = $transactions->where('type', 'masuk')->sum('amount');
        $expense = $transactions->where('type', 'keluar')->sum('amount');
        $balance = $income - $expense; // Saldo = Pemasukan - Pengeluaran

        // Ambil transaksi terakhir dengan relasi kategori dan paket
        $latest_transactions = Transaction::with(['category', 'paket'])
            ->whereYear('transaction_date', $year)
            ->latest()
            ->limit(5)
            ->get();

        // Total uang masuk dan keluar berdasarkan tahun
        $totalUangMasukTahunIni = Transaction::where('type', 'masuk')
            ->whereYear('transaction_date', $year)
            ->sum('amount');
        $totalUangKeluarTahunIni = Transaction::where('type', 'keluar')
            ->whereYear('transaction_date', $year)
            ->sum('amount');

        // Total uang masuk dan keluar hari ini
        $totalUangMasukHariIni = Transaction::where('type', 'masuk')
            ->whereDate('created_at', now())
            ->sum('amount');
        $totalUangKeluarHariIni = Transaction::where('type', 'keluar')
            ->whereDate('created_at', now())
            ->sum('amount');

        // Total uang masuk dan keluar bulan ini
        $totalUangMasukBulanIni = Transaction::where('type', 'masuk')
            ->whereBetween('transaction_date', [$start_date, $end_date])
            ->whereYear('transaction_date', $year)
            ->sum('amount');
        $totalUangKeluarBulanIni = Transaction::where('type', 'keluar')
            ->whereBetween('transaction_date', [$start_date, $end_date])
            ->whereYear('transaction_date', $year)
            ->sum('amount');

        // Bulan-bulan dalam setahun
        $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        $dataPemasukanTahunIniPerbulan = [];
        $dataPengeluaranTahunIniPerbulan = [];

        // Perbaiki perulangan untuk data pemasukan dan pengeluaran
        foreach (range(1, 12) as $month) {
            $dataPemasukanTahunIniPerbulan[] = Transaction::whereMonth('transaction_date', $month)
                ->whereYear('transaction_date', $year)
                ->where('type', 'masuk')
                ->sum('amount');

            $dataPengeluaranTahunIniPerbulan[] = Transaction::whereMonth('transaction_date', $month)
                ->whereYear('transaction_date', $year)
                ->where('type', 'keluar')
                ->sum('amount');
        }

        // Total pemasukan dan pengeluaran dari seluruh bulan
        $totalPemasukan = array_sum($dataPemasukanTahunIniPerbulan);
        $totalPengeluaran = array_sum($dataPengeluaranTahunIniPerbulan);

        // Kirim data ke view
        return view('welcome', compact(
            'income',
            'expense',
            'balance',
            'latest_transactions',
            'totalUangMasukTahunIni',
            'totalUangKeluarTahunIni',
            'totalUangMasukHariIni',
            'totalUangKeluarHariIni',
            'totalUangMasukBulanIni',
            'totalUangKeluarBulanIni',
            'months',
            'dataPemasukanTahunIniPerbulan',
            'dataPengeluaranTahunIniPerbulan',
            'year',
            'totalPemasukan',
            'totalPengeluaran'
        ));
    }
}
