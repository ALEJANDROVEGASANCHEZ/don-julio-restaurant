<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('cash_shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cash_register_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('code', 30)->unique();
            $table->decimal('opening_balance', 12, 2);
            $table->decimal('total_sales', 12, 2)->default(0);
            $table->decimal('total_cash_sales', 12, 2)->default(0);
            $table->decimal('total_electronic_sales', 12, 2)->default(0);
            $table->decimal('total_tips', 12, 2)->default(0);
            $table->decimal('total_refunds', 12, 2)->default(0);
            $table->decimal('total_voids', 12, 2)->default(0);
            $table->decimal('total_incomes', 12, 2)->default(0);
            $table->decimal('total_expenses', 12, 2)->default(0);
            $table->decimal('expected_cash', 12, 2)->default(0);
            $table->decimal('declared_cash', 12, 2)->nullable();
            $table->decimal('difference', 12, 2)->default(0);
            $table->string('status', 20)->default('opened');
            $table->timestamp('opened_at');
            $table->timestamp('closed_at')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('closed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('cash_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cash_shift_id')->constrained()->cascadeOnDelete();
            $table->foreignId('payment_id')->nullable()->constrained('payments')->nullOnDelete();
            $table->string('type', 20);
            $table->string('concept');
            $table->decimal('amount', 12, 2);
            $table->string('reference', 100)->nullable();
            $table->foreignId('authorized_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('cash_shift_id')->nullable()->constrained('cash_shifts')->nullOnDelete();
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('cash_shift_id')->nullable()->constrained('cash_shifts')->nullOnDelete();
        });
    }

    public function down(): void {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['cash_shift_id']);
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['cash_shift_id']);
        });
        Schema::dropIfExists('cash_movements');
        Schema::dropIfExists('cash_shifts');
    }
};
