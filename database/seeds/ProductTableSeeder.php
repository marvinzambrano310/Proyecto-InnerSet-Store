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
        $users = User::first();

        //foreach ($users as $user){
            JWTAuth::attempt(['email' => $users->email, 'password' => '123123']);
            // el usuario 1 crea 10 productos
            for ($i = 0;$i < 10; $i++){
                Product::create([
                    'name' => $faker->word,
                    'stock' => $faker->numberBetween(5, 20),
                    'price' => $faker->randomFloat(2, 0.10, 5.00),
                    'image' => $faker->imageUrl(400, 300, null, false),
                    'category_id' => $faker->numberBetween(1, 5),
                ]);
            }
        //}
    }
}
