<?php
namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TransactionsExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $start_date;
    protected $end_date;
    protected $type;

    // Constructor untuk menerima parameter filter
    public function __construct($start_date, $end_date, $type)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->type = $type;
    }

    // Mengambil data yang akan diekspor
    public function collection()
    {
        $query = Transaction::query();

        $start_date = $this->start_date;
        $end_date = $this->end_date;

        if (!empty($start_date) && !empty($end_date)) {
            $query->whereBetween('transaction_date', [$start_date, $end_date]);
        }

        if ($this->type) {
            $query->where('type', $this->type);
        }

        // Ambil data transaksi yang sesuai dengan filter
        $transactions = $query->get(['id', 'type', 'category_id', 'amount', 'transaction_date']);

        // Hitung total amount
        $totalAmount = $transactions->sum('amount');

        // Menambahkan total amount di bawah data transaksi
        $transactions->push([
            'id' => 'Total', // Label Total untuk ID
            'type' => '',    // Kosongkan tipe
            'category_id' => '',  // Kosongkan kategori
            'amount' => $totalAmount, // Total amount
            'transaction_date' => '', // Kosongkan tanggal
        ]);

        return $transactions;
    }

    // Menambahkan header kolom
    public function headings(): array
    {
        return [
            'ID',
            'Type',
            'Category',
            'Amount',
            'Transaction Date',
        ];
    }
}
