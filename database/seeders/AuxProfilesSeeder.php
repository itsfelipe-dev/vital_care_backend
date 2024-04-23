<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class AuxProfilesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Adjust the number of records as per your requirement
        $numberOfRecords = 10;

        // Loop to create fake data
        for ($i = 0; $i < $numberOfRecords; $i++) {
            DB::table('aux_profiles')->insert([
                'birthday' => $faker->date,
                'specialty' => $faker->jobTitle,
                'profile_image' => $faker->imageUrl(),
                'description' => $faker->text,
                'email' => $faker->unique()->safeEmail,
                'stars' => $faker->randomFloat(1, 0.0, 5.0),
                'city' => $faker->city(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
