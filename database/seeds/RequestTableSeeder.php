<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Product;
use App\Request;
use App\DetailRequest;
class RequestTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Request::truncate();
        DetailRequest::truncate();
        $faker = \Faker\Factory::create();
        $users = User::all();
        $products = Product::all();

        foreach ($users as $user){
            Request::create([
                'date' => $faker->dateTime,
                'subtotal' => $faker->randomFloat(2,0.10, 30),
                'type' => $faker->randomElement(['withdraw','deliver']),
                'surcharge' => $faker->randomFloat(2, 0, 2.00),
                'total' => $faker->randomFloat(2,0.10,30),
                'user_id' => $user->id,
            ]);
        }

        $requests = Request::all();
        foreach($requests as $request){
            for ($i=0;$i<5;$i++) {
                DetailRequest::create([
                    'request_id' => $request->id,
                    'product_id' => $faker->numberBetween(1, 20),
                    'quantity' => $faker->numberBetween(1,5),
                    'final_price' => $faker->numberBetween(1,5) * $faker->randomFloat(2,0.10,5),
                ]);
            }
        }
    }
}
