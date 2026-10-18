<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('commands', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('station_id')->constrained()->cascadeOnDelete();
            $table->string('code', 30)->unique();
            $table->string('status', 20)->default('queued');
            $table->integer('priority')->default(0);
            $table->string('channel', 20)->default('dine_in');
            $table->unsignedTinyInteger('promised_minutes')->default(15);
            $table->unsignedSmallInteger('elapsed_seconds')->default(0);
            $table->string('idempotency_key', 64)->nullable()->unique();
            $table->json('notes')->nullable();
            $table->unsignedInteger('items_count')->default(0);
            $table->unsignedInteger('items_ready')->default(0);
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('ready_at')->nullable();
            $table->foreignId('chef_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['branch_id', 'status', 'priority']);
        });

        Schema::create('command_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('command_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_item_id')->constrained()->cascadeOnDelete();
            $table->string('status', 20)->default('pending');
            $table->timestamp('ready_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('command_items');
        Schema::dropIfExists('commands');
    }
};
