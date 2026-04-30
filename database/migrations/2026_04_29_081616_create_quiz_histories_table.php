<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('quiz_histories', function (Blueprint $table) {
            $table->id('id_history');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('quiz_id');
            $table->timestamp('last_opened_at');
            $table->timestamps();

            $table->foreign('user_id')->references('id_user')->on('users')->onDelete('cascade');
            $table->foreign('quiz_id')->references('id_quiz')->on('quizzes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_histories');
    }
};
