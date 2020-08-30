<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Owner;
use App\Client;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Vaciar la tabla
        User::truncate();
        $faker = \Faker\Factory::create();

        $password = Hash::make('123123');
        $owner = Owner::create(['store_name' => 'Los Bajitos']);
        $owner->user()->create([
            'name' => 'Administrador',
            'email' => 'admin@prueba.com',
            'password' => $password,
            'role' => 'ROLE_ADMIN'
        ]);

        // Generar algunos usuarios para nuestra aplicacion
        for ($i = 0; $i < 10; $i++) {
            $client = Client::create([
                'home_number' => $faker->numberBetween(1, 30),
            ]);

            $client->user()->create([
                'name' => $faker->name,
                'email' => $faker->email,
                'password' => $password,
                'role' => 'ROLE_CLIENT'
            ]);

        }
    }
}
