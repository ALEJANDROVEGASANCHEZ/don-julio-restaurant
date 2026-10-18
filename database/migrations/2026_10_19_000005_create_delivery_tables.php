<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('delivery_zones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->string('code', 30);
            $table->string('name');
            $table->json('polygon')->nullable();
            $table->decimal('radius_km', 6, 2)->nullable();
            $table->decimal('base_fee', 12, 2)->default(0);
            $table->decimal('per_km_fee', 12, 2)->default(0);
            $table->integer('estimated_minutes')->default(30);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['branch_id', 'code']);
        });

        Schema::create('riders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->unique()->constrained()->nullOnDelete();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->string('code', 20);
            $table->string('full_name');
            $table->string('phone', 30);
            $table->string('vehicle_type', 20)->default('motorcycle');
            $table->string('vehicle_plate', 20)->nullable();
            $table->string('status', 20)->default('offline');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('rider_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('zone_id')->nullable()->constrained('delivery_zones')->nullOnDelete();
            $table->string('status', 25)->default('pending');
            $table->decimal('fee', 12, 2)->default(0);
            $table->decimal('distance_km', 6, 2)->nullable();
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('picked_up_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->string('delivery_code', 10)->nullable();
            $table->json('incidents')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('deliveries');
        Schema::dropIfExists('riders');
        Schema::dropIfExists('delivery_zones');
    }
};
