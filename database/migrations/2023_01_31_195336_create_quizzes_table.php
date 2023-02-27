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
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->integer( 'number' )->unsigned()->unique();
            $table->string( 'quiz_winner' )->nullable();
            $table->string( 'quiz_master' )->nullable();
            $table->timestamp( 'started_at' )->nullable();
            $table->timestamps();
            $table->foreignId( 'location_id' )->nullable()->constrained( 'locations' );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quizzes');
    }
};
