<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('service_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('table_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->string('code', 30)->unique();
            $table->string('status', 25)->default('open');
            $table->unsignedTinyInteger('guests')->default(1);
            $table->foreignId('waiter_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('cash_register_id')->nullable()->constrained('cash_registers')->nullOnDelete();
            $table->timestamp('opened_at');
            $table->timestamp('pre_bill_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->string('cancellation_reason')->nullable();
            $table->json('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['table_id', 'status']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('service_sessions');
    }
};
