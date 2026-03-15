<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attempt_answers', function (Blueprint $table) {

            $table->id('id_attempt_answer');

            $table->foreignId('attempt_id')
                ->constrained('attempts','id_attempt')
                ->cascadeOnDelete();

            $table->foreignId('snapshot_question_id')
                ->constrained('snapshot_questions','id_snapshot_question')
                ->restrictOnDelete();

            $table->timestamps();

            $table->index('attempt_id');
            $table->index('snapshot_question_id');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attempt_answers');
    }
};