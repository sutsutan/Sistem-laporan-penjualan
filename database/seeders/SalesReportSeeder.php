<?php

namespace Database\Seeders;

use App\Models\SalesReport;
use Illuminate\Database\Seeder;

class SalesReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SalesReport::create([
            'name' => 'Laporan Penjualan Retail Pusat',
            'report_date' => now()->toDateString(),
            'category' => 'cash',
        ]);

        SalesReport::create([
            'name' => 'Laporan Penjualan Angsuran Elektronik',
            'report_date' => now()->subDay()->toDateString(),
            'category' => 'kredit',
        ]);

        SalesReport::create([
            'name' => 'Laporan Pengadaan Perangkat Dinas Pendidikan',
            'report_date' => now()->subDays(2)->toDateString(),
            'category' => 'instansi',
        ]);
    }
}
