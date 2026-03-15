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
        Schema::create('quiz_topics', function (Blueprint $table) {

            $table->foreignId('quiz_id')
                ->constrained('quizzes','id_quiz')
                ->cascadeOnDelete();

            $table->foreignId('topic_id')
                ->constrained('topics','id_topic')
                ->cascadeOnDelete();

            $table->boolean('is_primary')->default(false);

            $table->timestamp('created_at')->nullable();

            $table->primary(['quiz_id','topic_id']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_topics');
    }
};