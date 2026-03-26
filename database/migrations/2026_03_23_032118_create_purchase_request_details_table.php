<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchase_requests', function (Blueprint $table) {
            $table->dropColumn(['item_name', 'brand', 'qty', 'price']);
        });

        Schema::create('purchase_request_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_request_id')->constrained()->cascadeOnDelete();
            $table->string('item_name');
            $table->string('brand')->nullable();
            $table->integer('qty')->default(1);
            $table->decimal('price', 15, 2)->default(0);
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_request_details');
        Schema::table('purchase_requests', function (Blueprint $table) {
            $table->string('item_name')->nullable();
            $table->string('brand')->nullable();
            $table->integer('qty')->default(1);
            $table->decimal('price', 15, 2)->default(0);
        });
    }
};
