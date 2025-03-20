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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 10, 2)->default(0);
            $table->unsignedInteger('duration')->default(0);
            $table->enum('status', ['enabled', 'disabled'])->default('enabled')->index('status');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('package_subscribe', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('package_id')->index('package');
            $table->unsignedBigInteger('subscribe_id')->index('subscribe');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
        Schema::dropIfExists('package_subscribe');
    }
};
