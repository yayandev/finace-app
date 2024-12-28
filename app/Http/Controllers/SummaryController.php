<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use App\Models\Transaction;
use Illuminate\Http\Request;

class SummaryController extends Controller
{
    // Menampilkan ringkasan laporan
    public function index(Request $request)
    {
        $pakets = Paket::all();

        $paket_id = $request->input('paket_id');

        $transaksiKeluar = [];
        $transaksiMasuk = [];
        $paketSelected = [];

        if ($paket_id) {
            $transaksiKeluar = Transaction::where('paket_id', $paket_id)->where('type', 'keluar')->get();
            $transaksiMasuk = Transaction::where('paket_id', $paket_id)->where('type', 'masuk')->get();
            $paketSelected = Paket::find($paket_id);
        }

        return view('reports.summary', compact('paketSelected', 'transaksiKeluar', 'transaksiMasuk', 'pakets'));
    }
}
