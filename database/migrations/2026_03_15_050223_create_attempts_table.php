<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attempts', function (Blueprint $table) {

            $table->id('id_attempt');

            $table->foreignId('user_id')
                ->constrained('users','id_user')
                ->cascadeOnDelete();

            $table->foreignId('quiz_id')
                ->constrained('quizzes','id_quiz')
                ->cascadeOnDelete();

            $table->foreignId('snapshot_id')
                ->constrained('quiz_snapshots','id_snapshot')
                ->restrictOnDelete();

            $table->timestamp('started_at')->nullable();
            $table->timestamp('submitted_at')->nullable();

            $table->decimal('score',5,2)->nullable();

            $table->integer('duration_seconds')->nullable();

            $table->timestamps();

            $table->index('user_id');
            $table->index('quiz_id');
            $table->index('snapshot_id');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attempts');
    }
};