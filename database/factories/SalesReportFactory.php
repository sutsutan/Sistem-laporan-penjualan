<?php

namespace Database\Factories;

use App\Models\SalesReport;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SalesReport>
 */
class SalesReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Laporan Penjualan '.fake()->company(),
            'report_date' => fake()->date(),
            'category' => fake()->randomElement(['cash', 'kredit', 'instansi']),
            'qty' => fake()->numberBetween(1, 10),
        ];
    }
}
