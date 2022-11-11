<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();
        
        DB::table('users')->insert([
            'name' => 'Claudio',
            'email' => 'thisisnotmyaddress@gmail.com',
            'email_verified_at' => null,
            'password' => '1234',
            'remember_token' => null,
            'gender' => 'Masculino',
            'birthdate' => '1977-08-13',
            'winning_streak' => 0,
            'winning_streak_type' => 0,
            'best_winning_streak' => 0,
            'losing_streak' => 0,
            'losing_streak_type' => 0,
            'worst_losing_streak' => 0,
            'score' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => null
        ]);
    }
}
