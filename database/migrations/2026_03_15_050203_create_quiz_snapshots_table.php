<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quiz_snapshots', function (Blueprint $table) {

            $table->id('id_snapshot');

            $table->foreignId('quiz_id')
                ->constrained('quizzes','id_quiz')
                ->cascadeOnDelete();

            $table->integer('version_number');

            $table->timestamp('created_at')->nullable();

            $table->index('quiz_id');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_snapshots');
    }
};