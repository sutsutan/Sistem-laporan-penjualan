<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('unauthenticated user is redirected to login page when accessing sales dashboard', function () {
    $response = $this->get(route('sales.index'));

    $response->assertRedirect(route('login'));
});

test('login page renders correctly with login form', function () {
    $response = $this->get(route('login'));

    $response->assertOk();
    $response->assertSee('Sistem Laporan Penjualan');
    $response->assertSee('Masuk ke Dashboard');
    $response->assertSee('Alamat Email');
    $response->assertSee('Kata Sandi');
});

test('active user can authenticate with correct credentials', function () {
    $user = User::factory()->create([
        'email' => 'petugas@laporan.com',
        'password' => bcrypt('password123'),
        'is_active' => true,
    ]);

    $response = $this->post(route('login.store'), [
        'email' => 'petugas@laporan.com',
        'password' => 'password123',
    ]);

    $response->assertRedirect(route('sales.index'));
    $this->assertAuthenticatedAs($user);
});

test('user cannot login with incorrect password', function () {
    User::factory()->create([
        'email' => 'petugas@laporan.com',
        'password' => bcrypt('password123'),
        'is_active' => true,
    ]);

    $response = $this->from(route('login'))->post(route('login.store'), [
        'email' => 'petugas@laporan.com',
        'password' => 'wrong-password',
    ]);

    $response->assertRedirect(route('login'));
    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('inactive user cannot authenticate even with valid credentials', function () {
    User::factory()->create([
        'email' => 'nonaktif@laporan.com',
        'password' => bcrypt('password123'),
        'is_active' => false,
    ]);

    $response = $this->from(route('login'))->post(route('login.store'), [
        'email' => 'nonaktif@laporan.com',
        'password' => 'password123',
    ]);

    $response->assertRedirect(route('login'));
    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('authenticated user can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('logout'));

    $response->assertRedirect(route('login'));
    $this->assertGuest();
});

test('admin can access user management page', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->get(route('users.index'));

    $response->assertOk();
    $response->assertSee('Kelola Hak Akses Pengguna');
    $response->assertSee('Tambah Pengguna');
});

test('staff user cannot access user management page and receives 403 forbidden', function () {
    $staff = User::factory()->create(['role' => 'staff']);

    $response = $this->actingAs($staff)->get(route('users.index'));

    $response->assertForbidden();
});

test('admin can register a new authorized user', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->post(route('users.store'), [
        'name' => 'Karyawan Baru',
        'email' => 'baru@laporan.com',
        'password' => 'secret123',
        'role' => 'staff',
        'is_active' => 1,
    ]);

    $response->assertRedirect(route('users.index'));
    $this->assertDatabaseHas('users', [
        'name' => 'Karyawan Baru',
        'email' => 'baru@laporan.com',
        'role' => 'staff',
        'is_active' => true,
    ]);
});

test('admin can toggle active status of a user', function () {
    $admin = User::factory()->admin()->create();
    $targetUser = User::factory()->create(['is_active' => true]);

    $response = $this->actingAs($admin)->patch(route('users.toggle-status', $targetUser));

    $response->assertRedirect();
    $this->assertDatabaseHas('users', [
        'id' => $targetUser->id,
        'is_active' => false,
    ]);
});

test('admin cannot deactivate own account', function () {
    $admin = User::factory()->admin()->create(['is_active' => true]);

    $response = $this->actingAs($admin)->patch(route('users.toggle-status', $admin));

    $response->assertSessionHas('error');
    $this->assertDatabaseHas('users', [
        'id' => $admin->id,
        'is_active' => true,
    ]);
});

test('admin can delete another user', function () {
    $admin = User::factory()->admin()->create();
    $targetUser = User::factory()->create();

    $response = $this->actingAs($admin)->delete(route('users.destroy', $targetUser));

    $response->assertRedirect();
    $this->assertDatabaseMissing('users', [
        'id' => $targetUser->id,
    ]);
});
