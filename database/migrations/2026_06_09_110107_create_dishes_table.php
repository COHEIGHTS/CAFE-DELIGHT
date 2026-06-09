<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dishes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('category');
            $table->decimal('price', 8, 2);
            $table->string('primary_image')->nullable();
            $table->string('secondary_image')->nullable();
            $table->string('tertiary_image')->nullable();
            $table->boolean('is_vegetarian')->default(false);
            $table->boolean('is_vegan')->default(false);
            $table->boolean('is_spicy')->default(false);
            $table->boolean('is_gluten_free')->default(false);
            $table->boolean('is_dairy_free')->default(false);
            $table->boolean('is_bestseller')->default(false);
            $table->text('ingredients')->nullable();
            $table->integer('prep_time')->nullable();
            $table->string('serving_size')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dishes');
    }
};