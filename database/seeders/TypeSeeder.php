<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('types')->insert([
            ['name' => 'Áo thun', 'description' => 'Các loại áo thun'],
            ['name' => 'Áo sơ mi', 'description' => 'Các loại áo sơ mi'],
            ['name' => 'Quần dài', 'description' => 'Các loại quần dài'],
            ['name' => 'Quần ngắn', 'description' => 'Các loại quần ngắn'],
        ]);
    }
}

