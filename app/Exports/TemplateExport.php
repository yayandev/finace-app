<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class TemplateExport implements FromArray
{
    /**
     * Mengembalikan array untuk template yang diinginkan
     *
     * @return array
     */
    public function array(): array
    {
        return [
            ['Type', 'Category ID', 'Amount', 'Transaction Date', 'User ID', 'Description'],
            // Baris kedua ini adalah template kosong
            ['', '', '', 'Y-m-d', '', '']
        ];
    }
}

