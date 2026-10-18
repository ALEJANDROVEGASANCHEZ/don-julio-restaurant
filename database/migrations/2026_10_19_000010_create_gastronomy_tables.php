<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('inputs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->string('code', 40);
            $table->string('name');
            $table->string('base_unit', 20);
            $table->string('purchase_unit', 20)->nullable();
            $table->decimal('purchase_to_base_factor', 12, 6)->default(1);
            $table->decimal('standard_cost', 12, 4)->default(0);
            $table->string('category', 30)->nullable();
            $table->boolean('tracks_lots')->default(false);
            $table->integer('min_stock')->default(0);
            $table->integer('max_stock')->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
            $table->unique(['branch_id', 'code']);
        });

        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('code', 40);
            $table->string('name');
            $table->string('type', 20)->default('dish');
            $table->decimal('yield_quantity', 12, 4)->default(1);
            $table->string('yield_unit', 20)->default('portion');
            $table->integer('portions')->default(1);
            $table->decimal('waste_percentage', 5, 2)->default(0);
            $table->integer('prep_minutes')->default(0);
            $table->text('instructions')->nullable();
            $table->json('photos')->nullable();
            $table->decimal('calculated_cost', 12, 4)->default(0);
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
            $table->unique(['branch_id', 'code']);
        });

        Schema::create('recipe_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipe_id')->constrained()->cascadeOnDelete();
            $table->morphs('componentable');
            $table->decimal('quantity', 12, 4);
            $table->string('unit', 20);
            $table->decimal('waste_percentage', 5, 2)->default(0);
            $table->boolean('is_optional')->default(false);
            $table->string('notes')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('recipe_components');
        Schema::dropIfExists('recipes');
        Schema::dropIfExists('inputs');
    }
};
