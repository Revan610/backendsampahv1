<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Categories;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            ['name' => 'Organik', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Kertas', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Plastik', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
    }
}
