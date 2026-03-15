<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classes', function (Blueprint $table) {

            $table->id('id_class');

            $table->string('name',100);

            $table->foreignId('course_id')
                ->nullable()
                ->constrained('courses','id_course')
                ->nullOnDelete();

            $table->foreignId('academic_year_id')
                ->nullable()
                ->constrained('academic_years','id_academic_year')
                ->nullOnDelete();

            $table->smallInteger('semester')->nullable();

            $table->foreignId('lecturer_id')
                ->nullable()
                ->constrained('lecturers','id_lecturer')
                ->nullOnDelete();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};