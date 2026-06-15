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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name')->default('Cafe Delight');
            $table->string('site_email')->default('info@cafedelight.com');
            $table->string('site_phone')->default('+254700000000');
            $table->decimal('delivery_fee', 8, 2)->default(200.00);
            $table->decimal('tax_rate', 5, 2)->default(16.00);
            $table->string('currency')->default('KES');
            $table->text('address')->nullable();
            $table->string('social_facebook')->nullable();
            $table->string('social_twitter')->nullable();
            $table->string('social_instagram')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
