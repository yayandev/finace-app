<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pakets', function (Blueprint $table) {
            //
            $table->string('konsultan')->nullable();
            $table->string('kontruksi')->nullable();
            $table->string('pengadaan')->nullable();
            $table->string('uraian')->nullable();
            $table->string('periode')->nullable();
            $table->string('no_kontrak')->nullable();
            $table->string('tanggal_kontrak')->nullable();
            $table->string('no_bastp')->nullable();
            $table->string('penerima')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pakets', function (Blueprint $table) {
            //
        });
    }
};
