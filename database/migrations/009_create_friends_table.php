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
        Schema::create('friends', function (Blueprint $table) {
            $table->timestamps();
            $table->unsignedBigInteger('user_id1');
            $table->unsignedBigInteger('user_id2');
            $table->foreign('user_id1')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_id2')->references('id')->on('users')->onDelete('cascade');
            $table->primary(['user_id1', 'user_id2']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('friends');
    }
};
