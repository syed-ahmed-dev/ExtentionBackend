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
        Schema::create('flash_card', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image');
            $table->string('company');
            $table->string('title');
            $table->string('notes');
            $table->unsignedBigInteger('collection_id');
            $table->foreign('collection_id')->references('id')->on('collection')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flash_card');
    }
};
