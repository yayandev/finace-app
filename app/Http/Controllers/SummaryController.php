<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class SummaryController extends Controller
{
    // Menampilkan ringkasan laporan
    public function index(Request $request)
    {
        // Ambil data transaksi untuk periode tertentu, jika tidak ada tanggal, ambil semua data
        $start_date = $request->input('start_date'); // Dapatkan start_date dari request
        $end_date = $request->input('end_date');     // Dapatkan end_date dari request

        // Mulai query untuk transaksi
        $query = Transaction::query();

        // Filter berdasarkan start_date dan end_date jika keduanya ada
        if ($start_date && $end_date) {
            $query->whereBetween('transaction_date', [$start_date, $end_date]);
        }

        // Ambil data transaksi yang sesuai dengan filter
        $transactions = $query->get();

        // Hitung total pemasukan dan pengeluaran
        $income = $transactions->where('type', 'masuk')->sum('amount');
        $expense = $transactions->where('type', 'keluar')->sum('amount');
        $balance = $income - $expense; // Saldo = Pemasukan - Pengeluaran

        // Kirim data ke view
        return view('reports.summary', compact('start_date', 'end_date', 'income', 'expense', 'balance'));
    }
}