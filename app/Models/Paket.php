<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paket extends Model
{
    //

    protected $fillable = ['name', 'nilai','konsultan','kontruksi','pengadaan','uraian','periode','no_kontrak','tanggal_kontrak','no_bastp','penerima'];
}
