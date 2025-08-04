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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('branch_id')->constrained()->onDelete('cascade');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('delivery_fee', 8, 2)->nullable();
            $table->decimal('tax', 8, 2);
            $table->decimal('total_amount', 10, 2);
            $table->text('delivery_address')->nullable();
            $table->decimal('delivery_latitude', 10, 8)->nullable();
            $table->decimal('delivery_longitude', 11, 8)->nullable();
            $table->enum('status', [
                'pending',
                'confirmed',
                'preparing',
                'ready_for_delivery',
                'out_for_delivery',
                'delivered',
                'cancelled'
            ])->default('pending');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])
                  ->default('pending');
            $table->enum('payment_method', ['cash', 'card', 'upi', 'wallet'])
                  ->default('cash');
            $table->text('delivery_notes')->nullable();
            $table->timestamp('estimated_delivery_time')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
