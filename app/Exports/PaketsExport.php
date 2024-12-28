<?php

namespace App\Exports;

use App\Models\Paket;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PaketsExport implements FromCollection, WithHeadings, WithStyles
{
    /**
     * Mengambil data dari tabel pakets
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Paket::select('id', 'name', 'nilai', 'created_at', 'updated_at')->get();
    }

    /**
     * Menambahkan header untuk kolom export
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',           // ID Paket
            'Name',         // Nama Paket
            'Nilai',  // Deskripsi Paket
            'Created At',   // Tanggal dibuat
            'Updated At',   // Tanggal diperbarui
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
        $sheet->getStyle('A1:E1')->applyFromArray([
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
        $sheet->getStyle('A2:E100')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A2:E100')->getAlignment()->setVertical('center');

        // Menyesuaikan ukuran kolom agar responsif dengan panjang data
        foreach (range('A', 'E') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        return [
            // Tinggi baris header
            1 => ['font' => ['size' => 12]],
        ];
    }
}
