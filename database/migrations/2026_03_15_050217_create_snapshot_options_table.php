<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('snapshot_options', function (Blueprint $table) {

            $table->id('id_snapshot_option');

            $table->foreignId('snapshot_question_id')
                ->constrained('snapshot_questions','id_snapshot_question')
                ->cascadeOnDelete();

            $table->unsignedBigInteger('original_option_id')->nullable();

            $table->text('content');

            $table->boolean('is_correct')->default(false);

            $table->integer('order_index');

            $table->index('snapshot_question_id');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('snapshot_options');
    }
};