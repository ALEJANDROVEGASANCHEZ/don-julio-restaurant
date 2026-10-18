<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('code', 30);
            $table->string('name');
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->string('image_path')->nullable();
            $table->string('display_channel', 30)->default('all');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
            $table->unique(['branch_id', 'code']);
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('sku', 40)->nullable();
            $table->string('code', 40);
            $table->string('name');
            $table->string('slug')->nullable();
            $table->text('short_description')->nullable();
            $table->text('long_description')->nullable();
            $table->string('image_path')->nullable();
            $table->string('type', 30)->default('simple');
            $table->string('unit', 20)->default('unit');
            $table->decimal('base_price', 12, 2)->default(0);
            $table->decimal('cost', 12, 2)->default(0);
            $table->boolean('includes_igv')->default(true);
            $table->decimal('igv_rate', 5, 4)->default(0.18);
            $table->string('tax_code', 20)->default('GRAVADO');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_visible_web')->default(true);
            $table->boolean('is_visible_pos')->default(true);
            $table->boolean('allow_modifiers')->default(false);
            $table->integer('sort_order')->default(0);
            $table->json('allergens')->nullable();
            $table->json('tags')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->unique(['branch_id', 'code']);
        });

        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('code', 30);
            $table->string('name');
            $table->decimal('price_delta', 12, 2)->default(0);
            $table->decimal('cost_delta', 12, 2)->default(0);
            $table->string('sku', 40)->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
            $table->unique(['product_id', 'code']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
    }
};
