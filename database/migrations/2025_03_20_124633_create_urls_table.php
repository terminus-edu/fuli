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
        Schema::create('urls', function (Blueprint $table) {
            $table->id();
            $table->string('cover');
            $table->string('title');
            $table->string('url');
            $table->enum('status', ['enabled', 'disabled'])->default('enabled')->index('status');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('url_url_group', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('url_id')->index('url');
            $table->unsignedBigInteger('url_group_id')->index('url_group');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('urls');
        Schema::dropIfExists('url_url_group');
    }
};
