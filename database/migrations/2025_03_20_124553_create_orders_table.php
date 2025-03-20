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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->char('no', 64)->index('no');
            $table->string('merchant_no')->nullable()->index('merchant_no');
            $table->string('remark')->nullable();
            $table->unsignedBigInteger('agent_id')->index('agent');
            $table->unsignedBigInteger('package_id')->index('package');
            $table->unsignedBigInteger('member_id')->index('member');
            $table->decimal('amount',10,2)->default(0);
            $table->decimal('pay_amount',10,2)->default(0);
            $table->enum('pay_status', ['unpaid', 'paid'])->default('unpaid')->index('pay_status');
            $table->enum('status', ['pending', 'completed', 'expired'])->default('pending')->index('status');
            $table->enum('exchange_status',['pending','completed','expired'])->default('pending')->index('exchange_status');
            $table->char('code',64)->index('code');
            $table->timestamp('pay_at')->nullable();
            $table->timestamp('exchange_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
