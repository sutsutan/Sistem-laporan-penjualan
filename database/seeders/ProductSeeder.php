<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::create(['name' => 'Laptop Asus']);
        Product::create(['name' => 'Mouse Wireless']);
        Product::create(['name' => 'Keyboard Mechanical']);
    }
}
