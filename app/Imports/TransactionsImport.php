<?php
namespace App\Imports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\ToModel;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;


class TransactionsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        try {
            // Menggunakan Carbon untuk mem-parsing tanggal dengan format yang tepat
            $transactionDate = Carbon::parse($row[3])->format('Y-m-d'); // Sesuaikan dengan posisi kolom tanggal

            return new Transaction([
                'type' => $row[0], // Sesuaikan dengan kolom pada file Excel
                'category_id' => $row[1],
                'amount' => $row[2],
                'transaction_date' => $transactionDate, // Pastikan tanggal dalam format yang benar
                'user_id' => $row[4],
                'description' => $row[5], // Kolom deskripsi
            ]);
        } catch (\Exception $e) {
            // Menangani error jika tanggal tidak valid
            // \Log::error('Error parsing date: ' . $e->getMessage());
            return null; // Atau bisa mengembalikan `false` untuk skip
        }
    }
}
