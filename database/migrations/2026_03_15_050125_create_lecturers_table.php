<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lecturers', function (Blueprint $table) {

            $table->id('id_lecturer');

            $table->string('staff_number',50)->nullable();
            $table->string('full_name',255);

            $table->string('email',255)->nullable()->unique();

            $table->foreignId('major_id')
                ->nullable()
                ->constrained('majors','id_major')
                ->nullOnDelete();

            $table->timestamps();

            $table->index('major_id');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lecturers');
    }
};