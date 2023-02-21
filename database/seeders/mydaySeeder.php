<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class mydaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $mydays = [];
        for ($i = 0; $i < 10; $i++) {
            $mydays[] = [
                'user_id' => 1,
                'message' => $faker->text(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        DB::table('mydays')->insert($mydays);
        // DB::table('mydays')->delete();
    }
}
