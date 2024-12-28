<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RincianTransaksiKeluarExport implements FromArray, WithHeadings, WithStyles, WithCustomStartCell
{
    protected $paket;

    public function __construct($paket)
    {
        $this->paket = $paket;
    }

    public function headings(): array
    {
        return ['Uraian Kegiatan', 'Keluar', 'Saldo'];
    }

    public function array(): array
    {
        $data = [];
        $saldo = $this->paket->nilai;

        // Tambahkan header saldo awal
        $data[] = ['', '', number_format($saldo, 0, ',', '.')];

        $transaksiKeluar = Transaction::where('paket_id', $this->paket->id)
            ->where('type', 'keluar')
            ->get();

        foreach ($transaksiKeluar as $item) {
            $saldo -= $item->amount;
            $data[] = [
                $item->description,
                number_format($item->amount, 0, ',', '.'),
                number_format($saldo, 0, ',', '.'),
            ];
        }

        return $data;
    }

    public function startCell(): string
    {
        return 'A2';
    }

    public function styles(Worksheet $sheet)
    {
        // Tambahkan judul laporan
        $sheet->mergeCells('A1:C1');
        $sheet->setCellValue('A1', 'Ringkasan Laporan Keluar ' . $this->paket->name);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1')->getAlignment()->setVertical('center');
        $sheet->getStyle('A1')->getAlignment()->setWrapText(true);


        // Styling untuk judul
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
                'color' => ['argb' => '1F4E78'], // Warna biru tua
            ],
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center',
            ],
        ]);

        // Pindahkan header ke baris kedua
        $sheet->getStyle('A2:C2')->applyFromArray([
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

        // Menyesuaikan ukuran kolom
        foreach (range('A', 'C') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        return [];
    }

    public function title(): string
    {
        return 'Rincian Transaksi Keluar';
    }
}
