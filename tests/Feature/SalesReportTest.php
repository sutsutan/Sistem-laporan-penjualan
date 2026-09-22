<?php

use App\Models\SalesReport;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('sales dashboard page renders successfully without laporan 2 portal', function () {
    $response = $this->get(route('sales.index'));

    $response->assertOk();
    $response->assertSee('Laporan Penjualan');
    $response->assertSee('Filter Laporan Penjualan');
    $response->assertSee('Akumulasi Penjualan');
    $response->assertSee('Create New Table');
    $response->assertDontSee('/tabel');
    $response->assertDontSee('Buka Laporan 2');
});

test('user can create a new sales report entry without providing report name', function () {
    $payload = [
        'report_date' => '2026-09-18',
        'category' => 'cash',
        'qty' => 5,
    ];

    $response = $this->post(route('sales.store'), $payload);

    $response->assertRedirect(route('sales.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('sales_reports', [
        'category' => 'cash',
        'qty' => 5,
    ]);
    $created = SalesReport::latest('id')->first();
    expect($created->report_date->format('Y-m-d'))->toBe('2026-09-18');

    // Check that it displays in the index view and aggregates properly
    $viewResponse = $this->get(route('sales.index'));
    $viewResponse->assertOk();
    $viewResponse->assertSee('Cash');
    $viewResponse->assertSee('5');
});

test('aggregates are calculated correctly across categories based on sum of qty', function () {
    SalesReport::factory()->create(['category' => 'cash', 'qty' => 10, 'report_date' => '2026-09-10']);
    SalesReport::factory()->create(['category' => 'kredit', 'qty' => 7, 'report_date' => '2026-09-15']);
    SalesReport::factory()->create(['category' => 'instansi', 'qty' => 15, 'report_date' => '2026-09-20']);

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

test('filtering by month filters reports and recalculates aggregates accurately', function () {
    // September records
    SalesReport::factory()->create(['category' => 'cash', 'qty' => 5, 'report_date' => '2026-09-10']);
    SalesReport::factory()->create(['category' => 'kredit', 'qty' => 4, 'report_date' => '2026-09-20']);

    // August record
    SalesReport::factory()->create(['category' => 'cash', 'qty' => 12, 'report_date' => '2026-08-15']);

    $response = $this->get(route('sales.index', ['month' => '2026-09']));

    $response->assertOk();
    $response->assertViewHas('totalCash', 5);
    $response->assertViewHas('totalKredit', 4);
    $response->assertViewHas('totalAll', 9);
    $response->assertViewHas('recentReports', function ($reports) {
        return $reports->count() === 2;
    });
    $response->assertSee('September 2026');
});

test('filtering by day filters reports and recalculates aggregates for that specific date', function () {
    // 2026-09-18 records
    SalesReport::factory()->create(['category' => 'cash', 'qty' => 3, 'report_date' => '2026-09-18']);
    SalesReport::factory()->create(['category' => 'instansi', 'qty' => 2, 'report_date' => '2026-09-18']);

    // Another date record
    SalesReport::factory()->create(['category' => 'cash', 'qty' => 8, 'report_date' => '2026-09-19']);

    $response = $this->get(route('sales.index', ['date' => '2026-09-18']));

    $response->assertOk();
    $response->assertViewHas('totalCash', 3);
    $response->assertViewHas('totalInstansi', 2);
    $response->assertViewHas('totalAll', 5);
    $response->assertViewHas('recentReports', function ($reports) {
        return $reports->count() === 2;
    });
});

test('creating report validates required fields and category options and min qty', function () {
    $response = $this->post(route('sales.store'), [
        'report_date' => 'invalid-date',
        'category' => 'invalid_category',
        'qty' => -1,
    ]);

    $response->assertSessionHasErrors(['report_date', 'category', 'qty']);
});

test('user can delete a sales report', function () {
    $report = SalesReport::factory()->create([
        'category' => 'cash',
        'qty' => 4,
        'report_date' => '2026-09-01',
    ]);

    $response = $this->delete(route('sales.destroy', $report->id));

    $response->assertSessionHas('success');

    $this->assertDatabaseMissing('sales_reports', [
        'id' => $report->id,
    ]);
});
