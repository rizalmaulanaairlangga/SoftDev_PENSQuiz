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

            $table->string('nrp',50)->unique();
            $table->string('username',100)->unique();
            $table->string('full_name',255);

            $table->string('email',255)->unique();
            $table->string('password_hash',255);

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