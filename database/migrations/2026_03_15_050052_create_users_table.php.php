<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {

            $table->id('id_user');


            $table->string('first_name', 100);
            $table->string('last_name', 100)->nullable();
            $table->string('username', 50)->unique();

            $table->string('email',255)->unique();
            $table->string('password',255);

            $table->foreignId('major_id')
                ->nullable()
                ->constrained('majors','id_major')
                ->nullOnDelete();

            $table->integer('year_of_entry')->nullable();

            $table->string('role',50)->default('student');

            $table->timestamps();
            $table->softDeletes();

            $table->index('major_id');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};