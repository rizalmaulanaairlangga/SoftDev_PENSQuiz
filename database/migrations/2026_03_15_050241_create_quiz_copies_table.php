<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quiz_copies', function (Blueprint $table) {

            $table->id('id_copy');

            $table->foreignId('original_quiz_id')
                ->constrained('quizzes','id_quiz')
                ->cascadeOnDelete();

            $table->foreignId('new_quiz_id')
                ->constrained('quizzes','id_quiz')
                ->cascadeOnDelete();

            $table->foreignId('copied_by_user_id')
                ->nullable()
                ->constrained('users','id_user')
                ->nullOnDelete();

            $table->timestamp('copied_at')->nullable();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_copies');
    }
};