<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BonusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('bonuses')->truncate();
        
        DB::table('bonuses')->insert([
            'description' => 'Por aprobar test sencillo',
            'score_bonus' => 1,
            'event' => 'all_answers_ok',
            "event_value" => null,
            "difficulty" => 1,
            "complexity" => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => null
        ]);
        DB::table('bonuses')->insert([
            'description' => 'Por aprobar test normal',
            'score_bonus' => 3,
            'event' => 'all_answers_ok',
            "event_value" => null,
            "difficulty" => 2,
            "complexity" => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => null
        ]);
        DB::table('bonuses')->insert([
            'description' => 'Por aprobar test complejo',
            'score_bonus' => 6,
            'event' => 'all_answers_ok',
            "event_value" => null,
            "difficulty" => 3,
            "complexity" => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => null
        ]);
        DB::table('bonuses')->insert([
            'description' => 'Por aprobar test muy complejo',
            'score_bonus' => 12,
            'event' => 'all_answers_ok',
            "event_value" => null,
            "difficulty" => 4,
            "complexity" => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => null
        ]);

        DB::table('bonuses')->insert([
            'description' => 'Por reprobar test sencillo',
            'score_bonus' => -1,
            'event' => 'failed_test',
            "event_value" => null,
            "difficulty" => 1,
            "complexity" => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => null
        ]);
        DB::table('bonuses')->insert([
            'description' => 'Por reprobar test normal',
            'score_bonus' => -3,
            'event' => 'failed_test',
            "event_value" => null,
            "difficulty" => 2,
            "complexity" => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => null
        ]);
        DB::table('bonuses')->insert([
            'description' => 'Por reprobar test complejo',
            'score_bonus' => -6,
            'event' => 'failed_test',
            "event_value" => null,
            "difficulty" => 3,
            "complexity" => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => null
        ]);
        DB::table('bonuses')->insert([
            'description' => 'Por reprobar test muy complejo',
            'score_bonus' => -12,
            'event' => 'failed_test',
            "event_value" => null,
            "difficulty" => 4,
            "complexity" => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => null
        ]);
        
        DB::table('bonuses')->insert([
            'description' => 'Por asertar pregunta #<questionNumber> (fácil)',
            'score_bonus' => 1,
            'event' => 'answer_ok',
            "event_value" => null,
            "difficulty" => 1,
            "complexity" => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => null
        ]);
        DB::table('bonuses')->insert([
            'description' => 'Por asertar pregunta #<questionNumber> (normal)',
            'score_bonus' => 2,
            'event' => 'answer_ok',
            "event_value" => null,
            "difficulty" => 1,
            "complexity" => 2,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => null
        ]);
        DB::table('bonuses')->insert([
            'description' => 'Por asertar pregunta #<questionNumber> (difícil)',
            'score_bonus' => 3,
            'event' => 'answer_ok',
            "event_value" => null,
            "difficulty" => 1,
            "complexity" => 3,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => null
        ]);
        DB::table('bonuses')->insert([
            'description' => 'Por asertar pregunta #<questionNumber> (fácil)',
            'score_bonus' => 1,
            'event' => 'answer_ok',
            "event_value" => null,
            "difficulty" => 2,
            "complexity" => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => null
        ]);
        DB::table('bonuses')->insert([
            'description' => 'Por asertar pregunta #<questionNumber> (normal)',
            'score_bonus' => 2,
            'event' => 'answer_ok',
            "event_value" => null,
            "difficulty" => 2,
            "complexity" => 2,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => null
        ]);
        DB::table('bonuses')->insert([
            'description' => 'Por asertar pregunta #<questionNumber> (difícil)',
            'score_bonus' => 4,
            'event' => 'answer_ok',
            "event_value" => null,
            "difficulty" => 2,
            "complexity" => 3,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => null
        ]);
        DB::table('bonuses')->insert([
            'description' => 'Por asertar pregunta #<questionNumber> (fácil)',
            'score_bonus' => 1,
            'event' => 'answer_ok',
            "event_value" => null,
            "difficulty" => 3,
            "complexity" => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => null
        ]);
        DB::table('bonuses')->insert([
            'description' => 'Por asertar pregunta #<questionNumber> (normal)',
            'score_bonus' => 3,
            'event' => 'answer_ok',
            "event_value" => null,
            "difficulty" => 3,
            "complexity" => 2,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => null
        ]);
        DB::table('bonuses')->insert([
            'description' => 'Por asertar pregunta #<questionNumber> (difícil)',
            'score_bonus' => 5,
            'event' => 'answer_ok',
            "event_value" => null,
            "difficulty" => 3,
            "complexity" => 3,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => null
        ]);
        DB::table('bonuses')->insert([
            'description' => 'Por asertar pregunta #<questionNumber> (fácil)',
            'score_bonus' => 1,
            'event' => 'answer_ok',
            "event_value" => null,
            "difficulty" => 4,
            "complexity" => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => null
        ]);
        DB::table('bonuses')->insert([
            'description' => 'Por asertar pregunta #<questionNumber> (normal)',
            'score_bonus' => 3,
            'event' => 'answer_ok',
            "event_value" => null,
            "difficulty" => 4,
            "complexity" => 2,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => null
        ]);
        DB::table('bonuses')->insert([
            'description' => 'Por asertar pregunta #<questionNumber> (difícil)',
            'score_bonus' => 6,
            'event' => 'answer_ok',
            "event_value" => null,
            "difficulty" => 4,
            "complexity" => 3,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => null
        ]);

        DB::table('bonuses')->insert([
            'description' => 'Por responderla antes del 75% del tiempo.',
            'score_bonus' => 1,
            'event' => 'answered_before_time',
            "event_value" => 75,
            "difficulty" => 0,
            "complexity" => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => null
        ]);
        DB::table('bonuses')->insert([
            'description' => 'Por responderla antes del 50% del tiempo.',
            'score_bonus' => 2,
            'event' => 'answered_before_time',
            "event_value" => 50,
            "difficulty" => 0,
            "complexity" => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => null
        ]);
        DB::table('bonuses')->insert([
            'description' => 'Por responderla antes del 25% del tiempo.',
            'score_bonus' => 3,
            'event' => 'answered_before_time',
            "event_value" => 25,
            "difficulty" => 0,
            "complexity" => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => null
        ]);
        DB::table('bonuses')->insert([
            'description' => 'Por responderla antes del 75% del tiempo.',
            'score_bonus' => 2,
            'event' => 'answered_before_time',
            "event_value" => 75,
            "difficulty" => 0,
            "complexity" => 2,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => null
        ]);
        DB::table('bonuses')->insert([
            'description' => 'Por responderla antes del 50% del tiempo.',
            'score_bonus' => 3,
            'event' => 'answered_before_time',
            "event_value" => 50,
            "difficulty" => 0,
            "complexity" => 2,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => null
        ]);
        DB::table('bonuses')->insert([
            'description' => 'Por responderla antes del 25% del tiempo.',
            'score_bonus' => 4,
            'event' => 'answered_before_time',
            "event_value" => 25,
            "difficulty" => 0,
            "complexity" => 2,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => null
        ]);
        DB::table('bonuses')->insert([
            'description' => 'Por responderla antes del 75% del tiempo.',
            'score_bonus' => 3,
            'event' => 'answered_before_time',
            "event_value" => 75,
            "difficulty" => 0,
            "complexity" => 3,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => null
        ]);
        DB::table('bonuses')->insert([
            'description' => 'Por responderla antes del 50% del tiempo.',
            'score_bonus' => 4,
            'event' => 'answered_before_time',
            "event_value" => 50,
            "difficulty" => 0,
            "complexity" => 3,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => null
        ]);
        DB::table('bonuses')->insert([
            'description' => 'Por responderla antes del 25% del tiempo.',
            'score_bonus' => 6,
            'event' => 'answered_before_time',
            "event_value" => 25,
            "difficulty" => 0,
            "complexity" => 3,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => null
        ]);

        DB::table('bonuses')->insert([
            'description' => 'Por lograr 3 tests aprobados consecutivos.',
            'score_bonus' => 3,
            'event' => 'winning_streak_reached',
            "event_value" => 3,
            "difficulty" => 0,
            "complexity" => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => null
        ]);
        DB::table('bonuses')->insert([
            'description' => 'Por lograr 5 tests aprobados consecutivos.',
            'score_bonus' => 5,
            'event' => 'winning_streak_reached',
            "event_value" => 5,
            "difficulty" => 0,
            "complexity" => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => null
        ]);
        DB::table('bonuses')->insert([
            'description' => 'Por lograr 3 tests reprobados consecutivos.',
            'score_bonus' => -9,
            'event' => 'losing_streak_reached',
            "event_value" => 3,
            "difficulty" => 0,
            "complexity" => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => null
        ]);
        DB::table('bonuses')->insert([
            'description' => 'Por lograr 5 tests reprobados consecutivos.',
            'score_bonus' => -20,
            'event' => 'losing_streak_reached',
            "event_value" => 5,
            "difficulty" => 0,
            "complexity" => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => null
        ]);
    }
}
