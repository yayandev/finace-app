<?php

namespace App\Http\Controllers;

use App\Exports\TemplateExport;
use App\Exports\TransactionsExport;
use App\Imports\TransactionsImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class TransactionExportImportController extends Controller
{
    //
    public function export()
    {
        return Excel::download(new TransactionsExport, 'transactions.xlsx');
    }

    // Import Excel
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        Excel::import(new TransactionsImport, $request->file('file'));

        return redirect()->back()->with('success', 'Data berhasil diimport!');
    }

    public function downloadTemplate()
    {
        return Excel::download(new TemplateExport, 'template_transaksi.xlsx');
    }
}
