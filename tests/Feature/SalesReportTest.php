<?php

use App\Models\SalesReport;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('sales dashboard page renders successfully without product breakdown', function () {
    $response = $this->get(route('sales.index'));

    $response->assertOk();
    $response->assertSee('Laporan Penjualan');
    $response->assertSee('Akumulasi Penjualan');
    $response->assertSee('Laporan Terbaru');
    $response->assertSee('Create New Table');
    $response->assertDontSee('Laptop Asus');
    $response->assertDontSee('Mouse Wireless');
    $response->assertDontSee('Keyboard Mechanical');
});

test('user can create a new sales report entry via create new table with qty', function () {
    $payload = [
        'name' => 'Laporan Penjualan Q3 Cabang Bandung',
        'report_date' => '2026-09-18',
        'category' => 'cash',
        'qty' => 5,
    ];

    $response = $this->post(route('sales.store'), $payload);

    $response->assertRedirect(route('sales.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('sales_reports', [
        'name' => 'Laporan Penjualan Q3 Cabang Bandung',
        'category' => 'cash',
        'qty' => 5,
    ]);

    // Check that it shows in the index view and aggregates properly
    $viewResponse = $this->get(route('sales.index'));
    $viewResponse->assertOk();
    $viewResponse->assertSee('Laporan Penjualan Q3 Cabang Bandung');
    $viewResponse->assertSee('Cash');
    $viewResponse->assertSee('5');
});

test('aggregates are calculated correctly across categories based on sum of qty', function () {
    SalesReport::factory()->create(['category' => 'cash', 'qty' => 10]);
    SalesReport::factory()->create(['category' => 'kredit', 'qty' => 7]);
    SalesReport::factory()->create(['category' => 'instansi', 'qty' => 15]);

    $response = $this->get(route('sales.index'));

    $response->assertOk();
    $response->assertViewHas('totalCash', 10);
    $response->assertViewHas('totalKredit', 7);
    $response->assertViewHas('totalInstansi', 15);
    $response->assertViewHas('totalAll', 32);
    $response->assertViewHas('recentReports', function ($reports) {
        return $reports->count() === 3;
    });
});

test('creating report validates required fields and category options and min qty', function () {
    $response = $this->post(route('sales.store'), [
        'name' => '',
        'report_date' => 'invalid-date',
        'category' => 'invalid_category',
        'qty' => -1,
    ]);

    $response->assertSessionHasErrors(['name', 'report_date', 'category', 'qty']);
});

test('user can update an existing sales report including qty', function () {
    $report = SalesReport::factory()->create([
        'name' => 'Laporan Lama',
        'category' => 'cash',
        'report_date' => '2026-09-01',
        'qty' => 2,
    ]);

    $response = $this->put(route('sales.update', $report->id), [
        'name' => 'Laporan Diperbarui',
        'category' => 'kredit',
        'report_date' => '2026-09-15',
        'qty' => 12,
    ]);

    $response->assertRedirect(route('sales.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('sales_reports', [
        'id' => $report->id,
        'name' => 'Laporan Diperbarui',
        'category' => 'kredit',
        'qty' => 12,
    ]);
});

test('user can delete a sales report', function () {
    $report = SalesReport::factory()->create([
        'name' => 'Laporan Untuk Dihapus',
    ]);

    $response = $this->delete(route('sales.destroy', $report->id));

    $response->assertRedirect(route('sales.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseMissing('sales_reports', [
        'id' => $report->id,
    ]);
});
