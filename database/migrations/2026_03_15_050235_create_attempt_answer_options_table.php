<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attempt_answer_options', function (Blueprint $table) {

            $table->id();

            $table->foreignId('attempt_answer_id')
                ->constrained('attempt_answers','id_attempt_answer')
                ->cascadeOnDelete();

            $table->foreignId('snapshot_option_id')
                ->constrained('snapshot_options','id_snapshot_option')
                ->cascadeOnDelete();

            $table->index('attempt_answer_id');
            $table->index('snapshot_option_id');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attempt_answer_options');
    }
};