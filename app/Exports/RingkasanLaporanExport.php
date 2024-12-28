<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RingkasanLaporanExport implements WithMultipleSheets
{
    protected $paket;

    public function __construct($paket)
    {
        $this->paket = $paket;
    }

    public function sheets(): array
    {
        return [
            new RincianTransaksiMasukExport($this->paket),
            new RincianTransaksiKeluarExport($this->paket),
        ];
    }
}
