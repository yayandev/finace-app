<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TransactionController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $query = Transaction::query()->with('category', 'user');

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

            return DataTables::of($query)
                ->make();
        }

        $total = Transaction::query()->sum('amount');

        return view('transactions.index', compact('total'));
    }

    public function create()
    {
        $categories = auth()->user()->categories;
        return view('transactions.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:masuk,keluar',
            'description' => 'required|string',
            'transaction_date' => 'required|date'
        ]);

        auth()->user()->transactions()->create($validated);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil ditambahkan');
    }

    public function edit(Transaction $transaction)
    {
        $categories = auth()->user()->categories;
        return view('transactions.edit', compact('transaction', 'categories'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:masuk,keluar',
            'description' => 'required|string',
            'transaction_date' => 'required|date'
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
            $query = Transaction::query()->where('type', 'masuk')->with('category', 'user');

            // Filter by date range
            if ($start = request('date_start')) {
                $query->whereDate('transaction_date', '>=', $start);
            }
            if ($end = request('date_end')) {
                $query->whereDate('transaction_date', '<=', $end);
            }

            return DataTables::of($query)
                ->make();
        }

        $total = Transaction::query()->where('type', 'masuk')->sum('amount');

        return view('transactions.income_report', compact('total'));
    }

    public function expenseReport()
    {
        if (request()->ajax()) {
            $query = Transaction::query()->where('type', 'keluar')->with('category', 'user');

            // Filter by date range
            if ($start = request('date_start')) {
                $query->whereDate('transaction_date', '>=', $start);
            }
            if ($end = request('date_end')) {
                $query->whereDate('transaction_date', '<=', $end);
            }

            return DataTables::of($query)
                ->make();
        }

        $total = Transaction::query()->where('type', 'keluar')->sum('amount');

        return view('transactions.expense_report', compact('total'));
    }


}