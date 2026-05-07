<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('animal_id')->constrained();
            $table->foreignId('product_category_id')->constrained();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('sku')->nullable()->unique();
            $table->string('hero_image')->nullable();
            $table->text('description')->nullable();
            $table->text('composition')->nullable();
            $table->text('benefits')->nullable();
            $table->text('usage_instructions')->nullable();
            $table->enum('status', ['draft', 'published'])->default('published');
            $table->unsignedInteger('order_column')->default(0);
            $table->timestamps();

            $table->index(['animal_id', 'product_category_id']);
            $table->index(['status', 'animal_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
