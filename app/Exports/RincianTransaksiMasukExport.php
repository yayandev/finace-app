<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RincianTransaksiMasukExport implements FromArray, WithHeadings, WithStyles, WithCustomStartCell
{
    protected $paket;

    public function __construct($paket)
    {
        $this->paket = $paket;
    }

    /**
     * Set heading untuk tabel.
     */
    public function headings(): array
    {
        return ['Uraian Kegiatan', 'Keluar', 'Saldo'];
    }

    /**
     * Menentukan data yang akan dimasukkan ke dalam tabel.
     */
    public function array(): array
    {
        $data = [];
        $saldo = $this->paket->nilai;

        // Tambahkan header saldo awal
        $data[] = ['', '', number_format($saldo, 0, ',', '.')];

        $transaksiMasuk = Transaction::where('paket_id', $this->paket->id)
            ->where('type', 'masuk')
            ->get();

        foreach ($transaksiMasuk as $item) {
            $saldo -= $item->amount;
            $data[] = [
                $item->description,
                number_format($item->amount, 0, ',', '.'),
                number_format($saldo, 0, ',', '.'),
            ];
        }

        return $data;
    }

    /**
     * Tentukan cell awal untuk data dan heading.
     */
    public function startCell(): string
    {
        return 'A2';
    }

    /**
     * Menambahkan style pada sheet.
     */
    public function styles(Worksheet $sheet)
    {
        // Tambahkan judul laporan
        $sheet->mergeCells('A1:C1');
        $sheet->setCellValue('A1', 'Ringkasan Laporan Masuk ' . $this->paket->name);
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

    //title sheet
    public function title(): string
    {
        return 'Rincian Transaksi Masuk';
    }
}