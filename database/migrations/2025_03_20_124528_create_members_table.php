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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agent_id')->nullable()->index('agent');
            $table->string('uuid')->index('uuid');
            $table->string('model')->nullable();
            $table->string('os')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('member_subscribe', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id')->index('member');
            $table->unsignedBigInteger('subscribe_id')->index('subscribe');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
        Schema::dropIfExists('member_subscribe');
    }
};
