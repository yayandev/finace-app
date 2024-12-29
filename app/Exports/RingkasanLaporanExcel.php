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
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

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
            ['RINGKASAN LAPORAN ' . strtoupper($this->paket->name), '', $this->paket->nilai],
            ['TANGGAL', 'URAIAN KEGIATAN', 'MASUK', 'KELUAR'],
        ];
    }

    public function map($transaction): array
    {
        return [
            $transaction->transaction_date->format('d/m/Y'),
            $transaction->description,
            $transaction->type == 'masuk' ? $transaction->amount : '',
            $transaction->type == 'keluar' ? $transaction->amount : '',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                $lastRow = $sheet->getHighestRow();
                $lastColumn = 'D';

                // Set column widths
                $sheet->getColumnDimension('A')->setWidth(15);
                $sheet->getColumnDimension('B')->setWidth(50);
                $sheet->getColumnDimension('C')->setWidth(20);
                $sheet->getColumnDimension('D')->setWidth(20);

                // Style header rows (both rows)
                $sheet->mergeCells('A1:B1');
                $sheet->getStyle('A1:D2')->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'E2E8F0'], // Light gray background
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);

                // Format numbers in header
                $sheet->getStyle('C1')->getNumberFormat()->setFormatCode('#,##0');

                // Style the data
                $dataRange = 'A3:' . $lastColumn . $lastRow;
                $sheet->getStyle($dataRange)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);

                // Format numbers in data columns
                $sheet->getStyle('C3:D' . $lastRow)->getNumberFormat()->setFormatCode('#,##0');

                // Add totals row
                $totalRow = $lastRow + 1;
                $sheet->setCellValue('A' . $totalRow, 'TOTAL');
                $sheet->setCellValue('C' . $totalRow, $this->transactions->where('type', 'masuk')->sum('amount'));
                $sheet->setCellValue('D' . $totalRow, $this->transactions->where('type', 'keluar')->sum('amount'));

                // Style totals row
                $sheet->getStyle('A' . $totalRow . ':D' . $totalRow)->applyFromArray([
                    'font' => ['bold' => true],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);
                $sheet->getStyle('C' . $totalRow . ':D' . $totalRow)->getNumberFormat()->setFormatCode('#,##0');

                // Add saldo rows
                $saldoStartRow = $totalRow + 1;
                $sheet->setCellValue('A' . $saldoStartRow, 'SISA SALDO TAGIHAN');
                $sheet->setCellValue('D' . $saldoStartRow, $this->paket->nilai - $this->transactions->where('type', 'masuk')->sum('amount'));

                $sheet->setCellValue('A' . ($saldoStartRow + 1), 'SISA SALDO PAKET PEKERJAAN');
                $sheet->setCellValue('D' . ($saldoStartRow + 1), $this->paket->nilai - $this->transactions->where('type', 'keluar')->sum('amount'));

                // Style saldo rows
                $saldoRange = 'A' . $saldoStartRow . ':D' . ($saldoStartRow + 1);
                $sheet->getStyle($saldoRange)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                    'font' => ['bold' => true],
                ]);
                $sheet->getStyle('D' . $saldoStartRow . ':D' . ($saldoStartRow + 1))->getNumberFormat()->setFormatCode('#,##0');

                // Set text alignment
                $sheet->getStyle('A1:' . $lastColumn . ($saldoStartRow + 1))->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getStyle('C1:D' . ($saldoStartRow + 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                // Set left alignment for labels
                $sheet->getStyle('A' . $totalRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle('A' . $saldoStartRow . ':A' . ($saldoStartRow + 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            }
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            2 => ['font' => ['bold' => true]],
        ];
    }
}
