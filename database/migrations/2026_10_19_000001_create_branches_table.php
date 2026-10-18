<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name');
            $table->string('trade_name')->nullable();
            $table->string('tax_id', 20)->nullable();
            $table->string('address')->nullable();
            $table->string('district')->nullable();
            $table->string('province')->nullable();
            $table->string('department')->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('email')->nullable();
            $table->json('schedule')->nullable();
            $table->string('timezone', 50)->default('America/Lima');
            $table->string('currency', 3)->default('PEN');
            $table->decimal('igv_rate', 5, 4)->default(0.18);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->json('settings')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('branches');
    }
};
