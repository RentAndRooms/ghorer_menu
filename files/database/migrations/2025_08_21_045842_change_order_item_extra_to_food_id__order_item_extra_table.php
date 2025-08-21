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
        Schema::table('order_item_extras', function (Blueprint $table) {
             $table->dropForeign(['extra_option_id']);
            $table->dropColumn('extra_option_id');
            $table->foreignId('food_id')->constrained('food')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_item_extras', function (Blueprint $table) {
            //
        });
    }
};
