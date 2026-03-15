<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {

            $table->id('id_course');

            $table->string('code',50)->nullable();
            $table->string('name',255);

            $table->foreignId('major_id')
                ->nullable()
                ->constrained('majors','id_major')
                ->nullOnDelete();

            $table->integer('credits')->nullable();

            $table->timestamps();

            $table->index('major_id');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};