<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Akun Administrator
        User::updateOrCreate(
            ['email' => 'admin@laporan.com'],
            [
                'name' => 'Administrator',
                'password' => bcrypt('admin123'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        // Akun Staff
        User::updateOrCreate(
            ['email' => 'staff@laporan.com'],
            [
                'name' => 'Staff Penjualan',
                'password' => bcrypt('staff123'),
                'role' => 'staff',
                'is_active' => true,
            ]
        );

        // Akun Contoh Nonaktif (Hanya orang tertentu yang aktif yang bisa login)
        User::updateOrCreate(
            ['email' => 'nonaktif@laporan.com'],
            [
                'name' => 'User Nonaktif',
                'password' => bcrypt('staff123'),
                'role' => 'staff',
                'is_active' => false,
            ]
        );

        // Perbarui test@example.com yang sudah ada jika ada di database
        User::where('email', 'test@example.com')->update([
            'password' => bcrypt('admin123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->call([
            SalesReportSeeder::class,
        ]);
    }
}
