<?php

namespace App\Exports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CategoriesExport implements FromCollection, WithHeadings, WithStyles
{
    /**
     * Mengambil data dari tabel categories
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Category::select('id', 'name', 'type', 'user_id', 'created_at', 'updated_at')->get();
    }

    /**
     * Menambahkan header untuk kolom template
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',           // ID kategori
            'Name',         // Nama kategori
            'Type',         // Jenis kategori (masuk atau keluar)
            'User ID',      // ID pengguna yang membuat kategori
            'Created At',   // Tanggal kategori dibuat
            'Updated At',   // Tanggal kategori diperbarui
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
        $sheet->getStyle('A1:F1')->applyFromArray([
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
        $sheet->getStyle('A2:F100')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A2:F100')->getAlignment()->setVertical('center');

        // Menyesuaikan ukuran kolom agar responsif dengan panjang data
        foreach (range('A', 'F') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        return [
            // Tinggi baris header
            1 => ['font' => ['size' => 12]],
        ];
    }
}
