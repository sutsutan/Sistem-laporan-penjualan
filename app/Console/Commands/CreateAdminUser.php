<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

#[Signature('app:create-admin {--name=Administrator : Nama lengkap admin} {--email=admin@laporan.com : Email admin} {--password=admin123 : Password admin}')]
#[Description('Membuat atau memperbarui akun Administrator di database')]
class CreateAdminUser extends Command
{
    /**
     * Menjalankan perintah artisan untuk membuat atau memperbarui akun admin.
     */
    public function handle(): int
    {
        $name = (string) $this->option('name');
        $email = (string) $this->option('email');
        $password = (string) $this->option('password');

        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make($password),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        $this->newLine();
        $this->info(' Akun Administrator berhasil disimpan di database!');
        $this->newLine();

        $this->table(
            ['ID', 'Nama', 'Email', 'Password', 'Role', 'Status'],
            [
                [
                    $user->id,
                    $user->name,
                    $user->email,
                    $password,
                    $user->role,
                    $user->is_active ? 'Aktif' : 'Nonaktif',
                ],
            ]
        );

        $this->comment('Anda dapat langsung login di browser menggunakan kredensial di atas.');

        return self::SUCCESS;
    }
}
