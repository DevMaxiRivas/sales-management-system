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
        Schema::create('product_enterprise', function (Blueprint $table) {
            $table->foreignId('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreignId('enterprise_id')->references('id')->on('enterprises')->onDelete('cascade');

            $table->primary(['product_id', 'enterprise_id']);

            $table->bigInteger('product_enterprise_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_enterprise');
    }
};
