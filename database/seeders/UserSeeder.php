<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Akun Administrator Utama
        User::updateOrCreate(
            ['email' => 'admin@laporan.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        // Akun Staff
        User::updateOrCreate(
            ['email' => 'staff@laporan.com'],
            [
                'name' => 'Staff Penjualan',
                'password' => Hash::make('staff123'),
                'role' => 'staff',
                'is_active' => true,
            ]
        );

        // Akun Contoh Nonaktif
        User::updateOrCreate(
            ['email' => 'nonaktif@laporan.com'],
            [
                'name' => 'User Nonaktif',
                'password' => Hash::make('staff123'),
                'role' => 'staff',
                'is_active' => false,
            ]
        );
    }
}
