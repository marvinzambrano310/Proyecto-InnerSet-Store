<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Product;
class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::truncate();
        $faker = \Faker\Factory::create();
        $users = User::all();
        foreach ($users as $user){
            // creamos 5 productos por usuario
            for ($i = 0;$i < 2; $i++){
                Product::create([
                    'name' => $faker->name,
                    'stock' => $faker->numberBetween(5, 20),
                    'price' => $faker->randomFloat(2, 0.10, 5.00),
                    'image' => $faker->imageUrl(400, 300, null, false),
                    'user_id' => $user->id,
                    'category_id' => $faker->numberBetween(1, 5),
                ]);
            }
        }
    }
}
