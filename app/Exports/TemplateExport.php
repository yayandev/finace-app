<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TemplateExport implements FromArray, WithHeadings, WithStyles
{
    /**
     * Mengembalikan array untuk template yang diinginkan
     *
     * @return array
     */
    public function array(): array
    {
        return [
            // Baris contoh pengisian
            ['', '', '', '', 'Y-m-d', '', ''],
        ];
    }

    /**
     * Menambahkan heading untuk kolom template
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'Type',           // Jenis transaksi, seperti income atau expense
            'Category ID',    // ID kategori transaksi
            'Paket ID',       // ID paket terkait transaksi
            'Amount',         // Nominal transaksi
            'Transaction Date', // Tanggal transaksi dalam format Y-m-d
            'User ID',        // ID pengguna
            'Description',    // Deskripsi tambahan
        ];
    }

    /**
     * Menambahkan style pada worksheet
     *
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        // Styling untuk header
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => 'solid',
                'startColor' => ['argb' => '1F4E78'], // Warna biru tua
            ],
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center',
                'wrapText' => true, // Bungkus teks agar tidak terpotong
            ],
        ]);

        // Set kolom dengan alignment tengah untuk data
        $sheet->getStyle('A2:G100')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A2:G100')->getAlignment()->setVertical('center');

        // Menyesuaikan ukuran kolom agar responsif dengan panjang data
        foreach (range('A', 'G') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        return [
            // Tinggi baris header
            1 => ['font' => ['size' => 12]],
        ];
    }
}
