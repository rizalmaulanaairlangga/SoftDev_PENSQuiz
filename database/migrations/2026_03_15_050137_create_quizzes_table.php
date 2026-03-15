<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quizzes', function (Blueprint $table) {

            $table->id('id_quiz');

            $table->foreignId('author_id')
                ->constrained('users','id_user')
                ->cascadeOnDelete();

            $table->string('title');
            $table->text('description')->nullable();

            $table->foreignId('major_id')->nullable()->constrained('majors','id_major')->nullOnDelete();
            $table->foreignId('course_id')->nullable()->constrained('courses','id_course')->nullOnDelete();
            $table->foreignId('lecturer_id')->nullable()->constrained('lecturers','id_lecturer')->nullOnDelete();
            $table->foreignId('academic_year_id')->nullable()->constrained('academic_years','id_academic_year')->nullOnDelete();
            $table->foreignId('class_id')->nullable()->constrained('classes','id_class')->nullOnDelete();

            $table->smallInteger('semester')->nullable();

            $table->string('visibility',20)->default('draft');
            $table->string('access',20)->default('private');

            $table->boolean('allow_copy')->default(false);
            $table->integer('version_number')->default(1);
            $table->boolean('has_been_updated')->default(false);

            $table->string('cover_image_url',1024)->nullable();

            $table->timestamps();
            $table->softDeletes();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};