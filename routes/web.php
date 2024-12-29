<?php

use App\Exports\CategoriesExport;
use App\Exports\PaketsExport;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransactionExportImportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransactionsExport;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\SummaryController;
use App\Http\Controllers\UserController;
use App\Models\Paket;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;

Route::middleware(['auth'])->group(function () {
    Route::post('/change-password', function (Request $request) {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed|different:current_password|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
        ]);

        if (!auth()->validate([
            'email' => auth()->user()->email,
            'password' => $request->current_password,
        ])) {
            return back()->with('error', 'Invalid current password');
        }

        auth()->user()->update([
            'password' => bcrypt($request->new_password),
        ]);

        return back()->with('success', 'Password changed successfully');
    })->name('change.password');

    Route::get('/finance/data', [TransactionController::class, 'getFinanceData'])->name('finance.data');

    Route::get('/reports/summary', [SummaryController::class, 'index'])->name('reports.summary');
    Route::get('/transactions/summary', [SummaryController::class, 'getSummary'])->name('transactions.summary');

    Route::resource('master/categories', CategoryController::class);
    Route::resource('transactions', TransactionController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('home');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/reports/income', [TransactionController::class, 'incomeReport'])->name('reports.income');
    Route::get('/reports/expense', [TransactionController::class, 'expenseReport'])->name('reports.expense');

    Route::get('/transactions/export', function (Request $request) {
        $start_date = $request->input('date_start');
        $end_date = $request->input('date_end');
        $type = $request->input('type');
        $category_id = $request->input('category_id');
        $paket_id = $request->input('paket_id');

        // Return the export file with filters applied
        return Excel::download(new TransactionsExport($start_date, $end_date, $type, $category_id, $paket_id), 'transactions.xlsx');
    })->name('transactions.export');
    Route::post('/transactions/import', [TransactionExportImportController::class, 'import'])->name('transactions.import');
    Route::get('/download-template', [TransactionExportImportController::class, 'downloadTemplate'])->name('transactions.downloadTemplate');

    Route::resource('master/roles', RolesController::class);
    Route::resource('master/permissions', PermissionController::class);
    Route::resource('master/users', UserController::class);
    Route::resource('master/pakets', PaketController::class);

    Route::get('/settings/account', function () {
        return view('settings.account');
    })->name('settings.account');
    Route::get('/settings/security', function () {
        return view('settings.security');
    })->name('settings.security');

    Route::get('/categories/export', function () {
        return Excel::download(new CategoriesExport, 'categories.xlsx');
    });
    Route::get('/pakets/export', function () {
        return Excel::download(new PaketsExport, 'pakets.xlsx');
    });


    Route::get('/ringkasan-transaksi-paket', [ReportController::class, 'exportExcel'])->name('ringkasan-transaksi-paket');
    Route::get('/pdf-ringkasan-transaksi-paket', function (Request $request) {
        $paket = Paket::findOrFail($request->paket_id);
        $transactions = Transaction::where('paket_id', $request->paket_id)
            ->orderBy('transaction_date', 'asc')
            ->get();

        $pdf = Pdf::loadView('myPDF', compact('paket', 'transactions'));
        return $pdf->stream('ringkasan-transaksi.pdf'); // atau gunakan ->download('ringkasan-transaksi.pdf') untuk unduhan
    })->name('pdf.ringkasan-transaksi-paket');
});

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'loginPage'])->name('login.post');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});


Route::get('/', function () {
    return redirect()->route('home');
});
