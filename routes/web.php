<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransactionExportImportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransactionsExport;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\SummaryController;
use App\Http\Controllers\UserController;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

Route::middleware(['auth'])->group(function () {
    Route::get('/finance/data', [TransactionController::class, 'getFinanceData'])->name('finance.data');

    Route::get('/reports/summary', [SummaryController::class, 'index'])->name('reports.summary');
    Route::get('/transactions/summary', [SummaryController::class, 'getSummary'])->name('transactions.summary');

    Route::resource('categories', CategoryController::class);
    Route::resource('transactions', TransactionController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    Route::get('/dashboard', function () {
        $start_date = now()->startOfMonth();
        $end_date = now()->endOfMonth();

        // total keseluruhan transaksi
        $transactions = Transaction::all();

        // Hitung total pemasukan dan pengeluaran
        $income = $transactions->where('type', 'masuk')->sum('amount');
        $expense = $transactions->where('type', 'keluar')->sum('amount');
        $balance = $income - $expense; // Saldo = Pemasukan - Pengeluaran

        // Ambil transaksi terakhir
        $latest_transactions = Transaction::latest()->take(5)->get();

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

        // if (auth()->user()->hasRole('user')) {
        //     // where user_id
        //     $transactions = Transaction::where('user_id', auth()->id())->get();
        //     $income = $transactions->where('type', 'masuk')->sum('amount');
        //     $expense = $transactions->where('type', 'keluar')->sum('amount');
        //     $balance = $income - $expense;
        //     $latest_transactions = Transaction::where('user_id', auth()->id())->latest()->take(5)->get();
        //     $totalUangMasukTahunIni = Transaction::where('type', 'masuk')->where('user_id', auth()->id())->whereYear('transaction_date', now()->year)->sum('amount');
        //     $totalUangKeluarTahunIni = Transaction::where('type', 'keluar')->where('user_id', auth()->id())->whereYear('transaction_date', now()->year)->sum('amount');
        //     $totalUangMasukHariIni = Transaction::where('type', 'masuk')->where('user_id', auth()->id())->whereDate('created_at', now())->sum('amount');
        //     $totalUangKeluarHariIni = Transaction::where('type', 'keluar')->where('user_id', auth()->id())->whereDate('created_at', now())->sum('amount');
        //     $totalUangMasukBulanIni = Transaction::where('type', 'masuk')->where('user_id', auth()->id())->whereBetween('transaction_date', [$start_date, $end_date])->sum('amount');
        //     $totalUangKeluarBulanIni = Transaction::where('type', 'keluar')->where('user_id', auth()->id())->whereBetween('transaction_date', [$start_date, $end_date])->sum('amount');
        //     $dataPemasukanTahunIniPerbulan = [];
        //     $dataPengeluaranTahunIniPerbulan = [];

        //     foreach (range(1, 12) as $month) {
        //         $dataPemasukanTahunIniPerbulan[] = DB::table('transactions')
        //             ->whereMonth('transaction_date', $month)
        //             ->whereYear('transaction_date', date('Y'))
        //             ->where('type', 'masuk')
        //             ->where('user_id', auth()->id())
        //             ->sum('amount');

        //         $dataPengeluaranTahunIniPerbulan[] = DB::table('transactions')
        //             ->whereMonth('transaction_date', $month)
        //             ->whereYear('transaction_date', date('Y'))
        //             ->where('type', 'keluar')
        //             ->where('user_id', auth()->id())
        //             ->sum('amount');
        //     }
        // }

        // Kirim data ke view
        return view('welcome', compact('income', 'expense', 'balance', 'latest_transactions', 'totalUangMasukTahunIni', 'totalUangKeluarTahunIni', 'totalUangMasukHariIni', 'totalUangKeluarHariIni', 'totalUangMasukBulanIni', 'totalUangKeluarBulanIni', 'months', 'dataPemasukanTahunIniPerbulan', 'dataPengeluaranTahunIniPerbulan'));
    })->name('home');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/reports/income', [TransactionController::class, 'incomeReport'])->name('reports.income');
    Route::get('/reports/expense', [TransactionController::class, 'expenseReport'])->name('reports.expense');

    Route::get('/transactions/export', function (Request $request) {
        $start_date = $request->input('date_start');
        $end_date = $request->input('date_end');
        $type = $request->input('type');

        // Return the export file with filters applied
        return Excel::download(new TransactionsExport($start_date, $end_date, $type), 'transactions.xlsx');
    })->name('transactions.export');
    Route::post('/transactions/import', [TransactionExportImportController::class, 'import'])->name('transactions.import');
    Route::get('/download-template', [TransactionExportImportController::class, 'downloadTemplate'])->name('transactions.downloadTemplate');

    Route::resource('roles', RolesController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('users', UserController::class);

    Route::get('/settings/account', function () {
        return view('settings.account');
    })->name('settings.account');
    Route::get('/settings/security', function () {
        return view('settings.security');
    })->name('settings.security');
});

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'loginPage'])->name('login.post');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});


Route::get('/', function () {
    return redirect()->route('home');
});
