<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('software_licenses', function (Blueprint $table) {
            $table->id();
            $table->string('license_code')->unique();
            $table->string('software_name');
            $table->string('publisher')->nullable();
            $table->string('version')->nullable();
            $table->string('license_key')->nullable();
            $table->enum('license_type', ['Per-Seat', 'Volume', 'OEM', 'Freeware', 'Open Source', 'Subscription'])->default('Per-Seat');
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('vendor_id')->nullable()->constrained()->nullOnDelete();
            $table->date('purchase_date')->nullable();
            $table->decimal('purchase_price', 15, 2)->nullable();
            $table->date('expiry_date')->nullable();
            $table->integer('max_users')->default(1);
            $table->integer('used_users')->default(0);
            $table->enum('status', ['Aktif', 'Expired', 'Tidak Aktif'])->default('Aktif');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('software_licenses');
    }
};
