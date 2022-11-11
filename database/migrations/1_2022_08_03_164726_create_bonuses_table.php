<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bonuses', function (Blueprint $table) {
            $table->id();
            $table->integer('score_bonus'); //+5, +3, +1, -1, -2, -3
            $table->string('description');
            $table->string('event')->comment('Tipo de evento: all_answers_ok, answered_before_time, winning_streak_reached, losing_streak_reached, etc.');
            $table->decimal('event_value', $precision = 5, $decimals = 2)->nullable()->comment('Valor umbral del evento: 0.75, 3, -2, etc.');
            $table->integer('difficulty')->comment('Dificultad del test: 1=sencillo, 2=normal, 3=complejo, 4=muy complejo, 0=todas/sin definir');
            $table->integer('complexity')->comment('Complejidad de pregunta: 1=fácil, 2=normal, 3=difícil, 0=todas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bonuses');
    }
};
