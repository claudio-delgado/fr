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
        Schema::create('users', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->enum('gender', ['Femenino', 'Masculino', 'Otro'])->default('Femenino');
            $table->date('birthDate');
            $table->integer('winning_streak')->default(0); //Racha de preguntas respondidas ok en línea.
            $table->integer('winning_streak_type')->default(0); //Tipo de test de pregunta respondida en la racha (sencilla, normal, difícil, muy difícil)
            $table->integer('best_winning_streak')->default(0); //Mejor racha de preguntas respondidas ok en línea.
            $table->integer('losing_streak')->default(0); //Racha de preguntas respondidas mal en línea.
            $table->integer('losing_streak_type')->default(0); //Tipo de test de pregunta respondida mal en la racha (sencilla, normal, difícil, muy difícil)
            $table->integer('worst_losing_streak')->default(0); //Peor racha de preguntas respondidas mal en línea.
            $table->integer('score')->default(0);
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
        Schema::dropIfExists('users');
    }
};
