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
        Schema::create('message_in_conservations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('userFrom');
            $table->foreign('userFrom')->references('id')->on('users');
            $table->unsignedBigInteger('cons_id')->nullable();
            $table->foreign('cons_id')->references('id')->on('conservations');
            $table->text('message');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('message_in_conservations');
    }
};
