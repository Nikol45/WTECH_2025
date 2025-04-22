<?php

namespace Database\Seeders;

use App\Models\FarmProduct;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FarmProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FarmProduct::factory(400)->create();
    }
}
