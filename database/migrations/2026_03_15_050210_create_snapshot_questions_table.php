<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('snapshot_questions', function (Blueprint $table) {

            $table->id('id_snapshot_question');

            $table->foreignId('snapshot_id')
                ->constrained('quiz_snapshots','id_snapshot')
                ->cascadeOnDelete();

            $table->unsignedBigInteger('original_question_id')->nullable();

            $table->text('content');

            $table->string('question_type',20);

            $table->integer('order_index');

            $table->index('snapshot_id');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('snapshot_questions');
    }
};