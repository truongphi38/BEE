<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\products;
use App\Models\Status;
use Faker\Factory as Faker;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        Status::create([
            'name' => $faker->name
        ]);
        for ($i=0; $i <50 ; $i++) { 
            products::create([
                'name' => $faker->name,
                'description' => $faker->text,
                'price' => $faker->randomFloat(2, 100, 10000),
                'img' => $faker->imageUrl($width = 640, $height = 480),
                'category_id' => Category::inRandomOrder()->first()->id,
                'status_id' => Status::inRandomOrder()->first()->id,
            ]);
        }
    }
}
