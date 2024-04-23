<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('aux_profiles', function (Blueprint $table) {
            $table->id();
            $table->date('birthday');
            $table->string('specialty');
            $table->string('profile_image')->nullable();
            $table->text('description');
            $table->string('email');
            $table->string('stars');
            $table->string('city');
            // $table->foreign('email')->references('email')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aux_profiles');
    }
};
