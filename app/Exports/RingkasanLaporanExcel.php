<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;

class RingkasanLaporanExcel implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithMapping, WithEvents
{
    protected $transactions;
    protected $paket;

    public function __construct($transactions, $paket)
    {
        $this->transactions = $transactions;
        $this->paket = $paket;
    }

    public function collection()
    {
        return $this->transactions;
    }

    public function headings(): array
    {
        return [
            [$this->paket->name, '', number_format($this->paket->nilai, 0, ',', '.')],
            ['TANGGAL', 'URAIAN KEGIATAN', 'MASUK', 'KELUAR'],
        ];
    }

    public function map($transaction): array
    {
        return [
            $transaction->transaction_date->format('d/m/Y'),
            $transaction->description,
            $transaction->type == 'masuk' ? number_format($transaction->amount, 0, ',', '.') : '',
            $transaction->type == 'keluar' ? number_format($transaction->amount, 0, ',', '.') : '',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                $lastRow = $sheet->getHighestRow();

                // Format cell alignments
                $sheet->getStyle('A2:D' . $lastRow)->getAlignment()->setVertical('center');
                $sheet->getStyle('C2:D' . $lastRow)->getAlignment()->setHorizontal('right');

                // Add totals row
                $totalRow = $lastRow + 1;
                $sheet->setCellValue('B' . $totalRow, 'Total');
                $sheet->setCellValue('C' . $totalRow, number_format($this->transactions->where('type', 'masuk')->sum('amount'), 0, ',', '.'));
                $sheet->setCellValue('D' . $totalRow, number_format($this->transactions->where('type', 'keluar')->sum('amount'), 0, ',', '.'));
                $sheet->getStyle('C' . $totalRow . ':D' . $totalRow)->getAlignment()->setHorizontal('right');


                // Add saldo information
                $saldoStartRow = $totalRow + 2;
                $sheet->setCellValue('A' . $saldoStartRow, 'SISA SALDO TAGIHAN');
                $sheet->setCellValue('D' . $saldoStartRow, number_format($this->paket->nilai - $this->transactions->where('type', 'masuk')->sum('amount'), 0, ',', '.'));

                $sheet->setCellValue('A' . ($saldoStartRow + 1), 'SISA SALDO PAKET PEKERJAAN');
                $sheet->setCellValue('D' . ($saldoStartRow + 1), number_format($this->paket->nilai - $this->transactions->where('type', 'keluar')->sum('amount'), 0, ',', '.'));

                // Style the saldo rows
                $sheet->getStyle('A' . $saldoStartRow . ':D' . ($saldoStartRow + 1))->applyFromArray([
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                        ],
                    ]
                ]);

                // Right align saldo amounts
                $sheet->getStyle('D' . $saldoStartRow . ':D' . ($saldoStartRow + 1))->getAlignment()->setHorizontal('right');
            }
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 11],
                'alignment' => ['vertical' => 'center'],
            ],
            2 => [
                'font' => ['bold' => true],
                'alignment' => ['vertical' => 'center'],
            ],
            'A1:D2' => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ],
        ];
    }
}
