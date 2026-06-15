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
        $this->addIndexSafely('users', 'email');
        $this->addIndexSafely('users', 'role');
        $this->addIndexSafely('users', 'created_at');
        $this->addCompositeIndexSafely('users', ['role', 'created_at']);

        $this->addIndexSafely('orders', 'user_id');
        $this->addIndexSafely('orders', 'status');
        $this->addIndexSafely('orders', 'payment_status');
        $this->addIndexSafely('orders', 'payment_method');
        $this->addIndexSafely('orders', 'created_at');
        $this->addCompositeIndexSafely('orders', ['status', 'created_at']);
        $this->addCompositeIndexSafely('orders', ['user_id', 'created_at']);
        $this->addCompositeIndexSafely('orders', ['payment_status', 'created_at']);

        $this->addIndexSafely('order_items', 'order_id');
        $this->addIndexSafely('order_items', 'dish_id');
        $this->addCompositeIndexSafely('order_items', ['order_id', 'dish_id']);

        $this->addIndexSafely('dishes', 'category');
        $this->addIndexSafely('dishes', 'is_bestseller');
        $this->addIndexSafely('dishes', 'is_vegetarian');
        $this->addIndexSafely('dishes', 'is_vegan');
        $this->addIndexSafely('dishes', 'is_spicy');
        $this->addIndexSafely('dishes', 'price');
        $this->addCompositeIndexSafely('dishes', ['category', 'is_bestseller']);

        $this->addIndexSafely('carts', 'user_id');
        $this->addIndexSafely('carts', 'dish_id');
        $this->addCompositeIndexSafely('carts', ['user_id', 'dish_id']);

        $this->addIndexSafely('favorites', 'user_id');
        $this->addIndexSafely('favorites', 'dish_id');
        $this->addCompositeIndexSafely('favorites', ['user_id', 'dish_id']);
        $this->addCompositeIndexSafely('favorites', ['user_id', 'created_at']);

        $this->addIndexSafely('addresses', 'user_id');
        $this->addIndexSafely('addresses', 'is_default');
        $this->addCompositeIndexSafely('addresses', ['user_id', 'is_default']);

        $this->addIndexSafely('otps', 'user_id');
        $this->addIndexSafely('otps', 'code');
        $this->addIndexSafely('otps', 'type');
        $this->addIndexSafely('otps', 'expires_at');
        $this->addCompositeIndexSafely('otps', ['user_id', 'type']);
        $this->addCompositeIndexSafely('otps', ['user_id', 'expires_at']);

        $this->addIndexSafely('audit_logs', 'user_id');
        $this->addIndexSafely('audit_logs', 'action');
        $this->addIndexSafely('audit_logs', 'created_at');
        $this->addCompositeIndexSafely('audit_logs', ['action', 'created_at']);
        $this->addCompositeIndexSafely('audit_logs', ['user_id', 'created_at']);
    }

    private function addIndexSafely(string $table, string $column): void
    {
        try {
            Schema::table($table, function (Blueprint $table) use ($column) {
                $table->index($column);
            });
        } catch (\Exception $e) {
            // Index already exists, skip
        }
    }

    private function addCompositeIndexSafely(string $table, array $columns): void
    {
        try {
            Schema::table($table, function (Blueprint $table) use ($columns) {
                $table->index($columns);
            });
        } catch (\Exception $e) {
            // Index already exists, skip
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Users table indexes
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['email']);
            $table->dropIndex(['role']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['role', 'created_at']);
        });

        // Orders table indexes
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['payment_status']);
            $table->dropIndex(['payment_method']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['status', 'created_at']);
            $table->dropIndex(['user_id', 'created_at']);
            $table->dropIndex(['payment_status', 'created_at']);
        });

        // Order items table indexes
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropIndex(['order_id']);
            $table->dropIndex(['dish_id']);
            $table->dropIndex(['order_id', 'dish_id']);
        });

        // Dishes table indexes
        Schema::table('dishes', function (Blueprint $table) {
            $table->dropIndex(['category']);
            $table->dropIndex(['is_bestseller']);
            $table->dropIndex(['is_vegetarian']);
            $table->dropIndex(['is_vegan']);
            $table->dropIndex(['is_spicy']);
            $table->dropIndex(['price']);
            $table->dropIndex(['category', 'is_bestseller']);
        });

        // Cart table indexes
        Schema::table('carts', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['dish_id']);
            $table->dropIndex(['user_id', 'dish_id']);
        });

        // Favorites table indexes
        Schema::table('favorites', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['dish_id']);
            $table->dropIndex(['user_id', 'dish_id']);
            $table->dropIndex(['user_id', 'created_at']);
        });

        // Addresses table indexes
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['is_default']);
            $table->dropIndex(['user_id', 'is_default']);
        });

        // OTPs table indexes
        Schema::table('otps', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['code']);
            $table->dropIndex(['type']);
            $table->dropIndex(['expires_at']);
            $table->dropIndex(['user_id', 'type']);
            $table->dropIndex(['user_id', 'expires_at']);
        });

        // Audit logs table indexes
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['action']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['action', 'created_at']);
            $table->dropIndex(['user_id', 'created_at']);
        });
    }
};
