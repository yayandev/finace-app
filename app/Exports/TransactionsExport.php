<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TransactionsExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $start_date;
    protected $end_date;
    protected $type;
    protected $category_id;
    protected $paket_id;

    // Constructor untuk menerima parameter filter
    public function __construct($start_date, $end_date, $type, $category_id, $paket_id)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->type = $type;
        $this->category_id = $category_id;
        $this->paket_id = $paket_id;
    }

    // Mengambil data yang akan diekspor
    public function collection()
    {
        $query = Transaction::query()->with(['category', 'paket', 'user']);

        // Filter berdasarkan tanggal
        if (!empty($this->start_date) && !empty($this->end_date)) {
            $query->whereBetween('transaction_date', [$this->start_date, $this->end_date]);
        }

        // Filter berdasarkan tipe
        if ($this->type) {
            $query->where('type', $this->type);
        }

        // Filter berdasarkan kategori
        if ($this->category_id) {
            $query->where('category_id', $this->category_id);
        }

        // Filter berdasarkan paket
        if ($this->paket_id) {
            $query->where('paket_id', $this->paket_id);
        }

        // Ambil data transaksi
        $transactions = $query->get();

        // Format hasil untuk ekspor
        $formattedTransactions = $transactions->map(function ($transaction) {
            return [
                'id' => $transaction->id,
                'type' => ucfirst($transaction->type),
                'paket' => $transaction->paket->name ?? '-',
                'category' => $transaction->category->name ?? '-',
                'amount' => number_format($transaction->amount, 2), // Format angka
                'transaction_date' => $transaction->transaction_date->format('Y-m-d'),
            ];
        });

        // Hitung total amount
        $totalAmount = $transactions->sum('amount');

        // Tambahkan baris total ke hasil
        $formattedTransactions->push([
            'id' => '',
            'type' => '',
            'paket' => '',
            'category' => 'Total',
            'amount' => number_format($totalAmount, 2),
            'transaction_date' => '',
        ]);

        return $formattedTransactions;
    }

    // Menambahkan header kolom
    public function headings(): array
    {
        return [
            'ID',
            'Type',
            'Paket',
            'Category',
            'Amount',
            'Transaction Date',
        ];
    }

    // Menambahkan style untuk header dan data
    public function styles(Worksheet $sheet)
    {
        // Styling header
        $sheet->getStyle('A1:F1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => 'solid',
                'startColor' => ['argb' => '1F4E78'],
            ],
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center',
            ],
        ]);

        // Auto size rows
        $sheet->getDefaultRowDimension()->setRowHeight(20);

        // Center align semua kolom kecuali amount
        $sheet->getStyle('A2:E1000')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A2:E1000')->getAlignment()->setVertical('center');

        // Format angka amount
        $sheet->getStyle('E2:E1000')->getNumberFormat()->setFormatCode('#,##0.00');

        return [
            // Style untuk header
            1 => ['font' => ['bold' => true]],
        ];
    }
}
