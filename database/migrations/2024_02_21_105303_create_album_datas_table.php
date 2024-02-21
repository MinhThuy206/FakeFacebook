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
        Schema::create('album_datas', function (Blueprint $table) {
            $table->timestamps();
            $table->unsignedBigInteger('album_id');
            $table->foreign('album_id')->references('id')->on('album_metas')->onDelete('cascade');
            $table->unsignedBigInteger('image_id');
            $table->foreign('image_id')->references('id')->on('images')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('album_datas');
    }
};
