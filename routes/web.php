<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransactionExportImportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransactionsExport;
use App\Http\Controllers\SummaryController;
use App\Models\Transaction;

Route::middleware(['auth'])->group(function () {
    Route::get('/reports/summary', [SummaryController::class, 'index'])->name('reports.summary');
    Route::resource('categories', CategoryController::class);
    Route::resource('transactions', TransactionController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    Route::get('/dashboard', function () {
        $start_date = now()->startOfMonth();
        $end_date = now()->endOfMonth();

        // Filter transaksi berdasarkan bulan ini
        $transactions = Transaction::whereBetween('transaction_date', [$start_date, $end_date])->get();

        // Hitung total pemasukan dan pengeluaran
        $income = $transactions->where('type', 'masuk')->sum('amount');
        $expense = $transactions->where('type', 'keluar')->sum('amount');
        $balance = $income - $expense; // Saldo = Pemasukan - Pengeluaran

        // Ambil transaksi terakhir
        $latest_transactions = Transaction::latest()->take(5)->get();

        // Kirim data ke view
        return view('welcome', compact('income', 'expense', 'balance', 'latest_transactions'));
    })->name('home');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/reports/income',[TransactionController::class, 'incomeReport'])->name('reports.income');
    Route::get('/reports/expense',[TransactionController::class, 'expenseReport'])->name('reports.expense');

    Route::get('/transactions/export', function (Request $request) {
        $start_date = $request->input('date_start');
        $end_date = $request->input('date_end');
        $type = $request->input('type');

        // Return the export file with filters applied
        return Excel::download(new TransactionsExport($start_date, $end_date, $type), 'transactions.xlsx');
    })->name('transactions.export');
    Route::post('/transactions/import', [TransactionExportImportController::class, 'import'])->name('transactions.import');
    Route::get('/download-template', [TransactionExportImportController::class, 'downloadTemplate'])->name('transactions.downloadTemplate');
});

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'loginPage'])->name('login.post');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});


Route::get('/', function () {
    return redirect()->route('home');
});
