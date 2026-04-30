<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id('id_tag');
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('quiz_tags', function (Blueprint $table) {
            $table->id('id_quiz_tag');
            $table->foreignId('quiz_id')
                ->constrained('quizzes', 'id_quiz')
                ->onDelete('cascade');
            $table->foreignId('tag_id')
                ->constrained('tags', 'id_tag')
                ->onDelete('cascade');
            
            $table->unique(['quiz_id', 'tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_tags');
        Schema::dropIfExists('tags');
    }
};
