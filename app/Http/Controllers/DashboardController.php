<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        $start_date = now()->startOfMonth();
        $end_date = now()->endOfMonth();

        // total keseluruhan transaksi
        $transactions = Transaction::all();

        // Hitung total pemasukan dan pengeluaran
        $income = $transactions->where('type', 'masuk')->sum('amount');
        $expense = $transactions->where('type', 'keluar')->sum('amount');
        $balance = $income - $expense; // Saldo = Pemasukan - Pengeluaran

        // Ambil transaksi terakhir with pakets and kategori
        $latest_transactions = Transaction::with(['category', 'paket'])->latest()->limit(5)->get();

        $totalUangMasukTahunIni = Transaction::where('type', 'masuk')->whereYear('transaction_date', now()->year)->sum('amount');
        $totalUangKeluarTahunIni = Transaction::where('type', 'keluar')->whereYear('transaction_date', now()->year)->sum('amount');

        $totalUangMasukHariIni = Transaction::where('type', 'masuk')->whereDate('created_at', now())->sum('amount');
        $totalUangKeluarHariIni = Transaction::where('type', 'keluar')->whereDate('created_at', now())->sum('amount');

        $totalUangMasukBulanIni = Transaction::where('type', 'masuk')->whereBetween('transaction_date', [$start_date, $end_date])->sum('amount');
        $totalUangKeluarBulanIni = Transaction::where('type', 'keluar')->whereBetween('transaction_date', [$start_date, $end_date])->sum('amount');

        $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        $dataPemasukanTahunIniPerbulan = [];
        $dataPengeluaranTahunIniPerbulan = [];

        foreach (range(1, 12) as $month) {
            $dataPemasukanTahunIniPerbulan[] = DB::table('transactions')
                ->whereMonth('transaction_date', $month)
                ->whereYear('transaction_date', date('Y'))
                ->where('type', 'masuk') // tipe pemasukan
                ->sum('amount');

            $dataPengeluaranTahunIniPerbulan[] = DB::table('transactions')
                ->whereMonth('transaction_date', $month)
                ->whereYear('transaction_date', date('Y'))
                ->where('type', 'keluar') // tipe pengeluaran
                ->sum('amount');
        }
        // Kirim data ke view
        return view('welcome', compact('income', 'expense', 'balance', 'latest_transactions', 'totalUangMasukTahunIni', 'totalUangKeluarTahunIni', 'totalUangMasukHariIni', 'totalUangKeluarHariIni', 'totalUangMasukBulanIni', 'totalUangKeluarBulanIni', 'months', 'dataPemasukanTahunIniPerbulan', 'dataPengeluaranTahunIniPerbulan'));
    }
}
