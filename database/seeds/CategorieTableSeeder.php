<?php

use Illuminate\Database\Seeder;
use App\Category;
class CategorieTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::truncate();
        $faker = \Faker\Factory::create();
        for ($i = 0; $i < 5; $i++) {
            Category::create([
                'name' => $faker->word
            ]);
        }
    }
}
