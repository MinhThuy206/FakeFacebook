<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('conservations', function (Blueprint $table) {
            $table->id();
            $table->text('name')->nullable();
            $table->unsignedBigInteger('avtGroup_id')->nullable();
            $table->foreign('avtGroup_id')->references('id')->on('images');
            $table->boolean("two")->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conservations');
    }
};
