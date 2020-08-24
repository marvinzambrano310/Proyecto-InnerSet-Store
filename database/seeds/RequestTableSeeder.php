<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Product;
use App\Request;
use App\DetailRequest;
use Tymon\JWTAuth\Facades\JWTAuth;

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
            JWTAuth::attempt(['email' => $user->email, 'password' => '123123']);
            $subtotal =$faker->randomFloat(2,0.10, 30);
            $surcharge = $faker->randomFloat(2, 0, 2.00);
            $total= $surcharge+$subtotal;
            Request::create([
                'date' => $faker->dateTime,
                'subtotal' => $subtotal,
                'type' => $faker->randomElement(['withdraw','deliver']),
                'surcharge' => $surcharge,
                'total' => $total,
                //'user_id' => $user->id,
            ]);
        }

        $requests = Request::all();

        foreach($requests as $request){
            for ($i=0;$i<5;$i++) {
                $quantity =$faker->numberBetween(1,5);
                DetailRequest::create([
                    'request_id' => $request->id,
                    'product_id' => $faker->numberBetween(1, 10),
                    'quantity' => $quantity,
                    'final_price' => $quantity * $faker->randomFloat(2,0.10,5),
                ]);
            }
        }
    }
}
