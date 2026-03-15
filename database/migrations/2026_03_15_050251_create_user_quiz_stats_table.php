<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_quiz_stats', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')
                ->constrained('users','id_user')
                ->cascadeOnDelete();

            $table->foreignId('quiz_id')
                ->constrained('quizzes','id_quiz')
                ->cascadeOnDelete();

            $table->integer('attempt_count')->default(0);

            $table->decimal('last_score',5,2)->nullable();
            $table->decimal('avg_score',5,2)->nullable();

            $table->timestamps();

            $table->index('user_id');
            $table->index('quiz_id');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_quiz_stats');
    }
};