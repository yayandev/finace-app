<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\RingkasanLaporanExcel;
use App\Models\Paket;
use App\Models\Transaction;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{

    public function exportExcel(Request $request)
    {
        $paket = Paket::findOrFail($request->paket_id);
        $transactions = Transaction::where('paket_id', $request->paket_id)
            ->orderBy('transaction_date', 'asc')
            ->get();

        return Excel::download(
            new RingkasanLaporanExcel($transactions, $paket),
            'ringkasan-laporan-' . $paket->name . '.xlsx'
        );
    }
}
