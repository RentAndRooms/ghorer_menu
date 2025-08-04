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
        Schema::table('food', function (Blueprint $table) {
            $table->renameColumn('is_vegetarian', 'full');
            $table->renameColumn('is_spicy', 'half');
            $table->integer('full')->default(0)->change();
            $table->integer('half')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('food', function (Blueprint $table) {
            $table->dropColumn('full');
            $table->dropColumn('half');
        });
    }
};
