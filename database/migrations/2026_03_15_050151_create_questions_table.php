<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {

            $table->id('id_question');

            $table->foreignId('quiz_id')
                ->constrained('quizzes','id_quiz')
                ->cascadeOnDelete();

            $table->text('content');

            $table->string('question_type',20);

            $table->integer('order_index')->default(0);

            $table->text('explanation')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};