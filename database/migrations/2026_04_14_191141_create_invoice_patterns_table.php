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
        Schema::create('invoice_patterns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enterprise_id')->references('id')->on('enterprises')->onDelete('cascade');
            $table->smallInteger('type');
            $table->string('pattern');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_patterns');
    }
};
