<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Paket;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TransactionController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            'role_or_permission:admin|view-transactions|edit-transactions|delete-transactions|create-transactions',
        ];
    }

    public function index()
    {
        if (request()->ajax()) {
            $query = Transaction::query()->with('category', 'user', 'paket');

            if (auth()->user()->hasRole('user')) {
                $query->where('user_id', auth()->id());
            }

            // Filter by date range
            if ($start = request('date_start')) {
                $query->whereDate('transaction_date', '>=', $start);
            }
            if ($end = request('date_end')) {
                $query->whereDate('transaction_date', '<=', $end);
            }

            if ($type = request('type')) {
                $query->where('type', $type);
            }

            if ($category = request('category_id')) {
                $query->where('category_id', $category);
            }

            if ($paket = request('paket_id')) {
                $query->where('paket_id', $paket);
            }

            return DataTables::of($query)
                ->make();
        }

        $total = Transaction::query()->sum('amount');
        $categories = Category::all();
        $pakets = Paket::all();

        return view('transactions.index', compact('total', 'categories', 'pakets'));
    }

    public function create()
    {
        $categories = Category::all();
        $pakets = Paket::all();
        return view('transactions.create', compact('categories', 'pakets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:masuk,keluar',
            'description' => 'required|string',
            'transaction_date' => 'required|date',
            'paket_id' => 'required|exists:pakets,id',
        ]);

        auth()->user()->transactions()->create($validated);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil ditambahkan');
    }

    public function edit(Transaction $transaction)
    {
        $categories = Category::all();
        $pakets = Paket::all();
        return view('transactions.edit', compact('transaction', 'categories', 'pakets'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:masuk,keluar',
            'description' => 'required|string',
            'transaction_date' => 'required|date',
            'paket_id' => 'required|exists:pakets,id',
        ]);

        $transaction->update($validated);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil diupdate');
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil dihapus');
    }

    public function incomeReport()
    {
        if (request()->ajax()) {
            $query = Transaction::query()->with('category', 'user', 'paket');

            if (auth()->user()->hasRole('user')) {
                $query->where('user_id', auth()->id());
            }

            // Filter by date range
            if ($start = request('date_start')) {
                $query->whereDate('transaction_date', '>=', $start);
            }
            if ($end = request('date_end')) {
                $query->whereDate('transaction_date', '<=', $end);
            }

            if ($type = request('type')) {
                $query->where('type', $type);
            }

            if ($category = request('category_id')) {
                $query->where('category_id', $category);
            }

            if ($paket = request('paket_id')) {
                $query->where('paket_id', $paket);
            }

            return DataTables::of($query)
                ->make();
        }

        $total = Transaction::query()->where('type', 'masuk')->sum('amount');
        $categories = Category::all();
        $pakets = Paket::all();
        return view('transactions.income_report', compact('total', 'categories', 'pakets'));
    }

    public function expenseReport()
    {
        if (request()->ajax()) {
            $query = Transaction::query()->with('category', 'user', 'paket');

            if (auth()->user()->hasRole('user')) {
                $query->where('user_id', auth()->id());
            }

            // Filter by date range
            if ($start = request('date_start')) {
                $query->whereDate('transaction_date', '>=', $start);
            }
            if ($end = request('date_end')) {
                $query->whereDate('transaction_date', '<=', $end);
            }

            if ($type = request('type')) {
                $query->where('type', $type);
            }

            if ($category = request('category_id')) {
                $query->where('category_id', $category);
            }

            if ($paket = request('paket_id')) {
                $query->where('paket_id', $paket);
            }

            return DataTables::of($query)
                ->make();
        }

        $total = Transaction::query()->where('type', 'keluar')->sum('amount');
        $categories = Category::all();
        $pakets = Paket::all();
        return view('transactions.expense_report', compact('total', 'categories', 'pakets'));
    }

    public function getFinanceData(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $months = range(1, 12);

        $dataPemasukanTahunIniPerbulan = [];
        $dataPengeluaranTahunIniPerbulan = [];

        foreach ($months as $month) {
            $dataPemasukanTahunIniPerbulan[] = DB::table('transactions')
                ->whereMonth('transaction_date', $month)
                ->whereYear('transaction_date', $year)
                ->where('type', 'masuk')
                ->sum('amount');

            $dataPengeluaranTahunIniPerbulan[] = DB::table('transactions')
                ->whereMonth('transaction_date', $month)
                ->whereYear('transaction_date', $year)
                ->where('type', 'keluar')
                ->sum('amount');
        }

        return response()->json([
            'dataPemasukanTahunIniPerbulan' => $dataPemasukanTahunIniPerbulan,
            'dataPengeluaranTahunIniPerbulan' => $dataPengeluaranTahunIniPerbulan,
            'totalPemasukan' => array_sum($dataPemasukanTahunIniPerbulan),
            'totalPengeluaran' => array_sum($dataPengeluaranTahunIniPerbulan),
        ]);
    }
}
