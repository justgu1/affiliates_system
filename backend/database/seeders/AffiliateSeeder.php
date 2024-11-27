<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Affiliate;
use Faker\Factory as Faker;

class AffiliateSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('pt_BR');

        foreach (range(1, 12) as $index) {
            Affiliate::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'cpf' => $faker->cpf,
                'birthday' => $faker->date($format = 'Y-m-d', $max = 'now'),
                'phone' => $faker->cellphone,
                'address' => $faker->address,
                'city' => $faker->city,
                'state' => $faker->stateAbbr,
                'password' => bcrypt('secret123'),
                'type' => 'affiliate',
            ]);
        }
    }
}

