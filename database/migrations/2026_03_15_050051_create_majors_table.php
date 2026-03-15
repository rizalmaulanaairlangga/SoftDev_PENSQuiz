<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('majors', function (Blueprint $table) {

            $table->id('id_major');
            $table->string('code',50)->nullable();
            $table->string('name',255)->unique();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('majors');
    }
};