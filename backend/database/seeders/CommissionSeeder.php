<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Commission;
use App\Models\Affiliate;
use Faker\Factory as Faker;

class CommissionSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('pt_BR');


        $affiliates = Affiliate::all();

        foreach (range(1, 22) as $index) {

            $affiliate = $affiliates->random();

            Commission::create([
                'affiliate_id' => $affiliate->id,
                'amount' => $faker->randomFloat(2, 50, 1000),
                'description' => $faker->sentence,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
