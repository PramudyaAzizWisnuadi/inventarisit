<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['Preventif', 'Korektif', 'Penggantian Komponen', 'Upgrade', 'Kalibrasi'])->default('Preventif');
            $table->string('technician')->nullable();
            $table->string('vendor_service')->nullable();
            $table->date('scheduled_at')->nullable();
            $table->date('completed_at')->nullable();
            $table->decimal('cost', 15, 2)->nullable();
            $table->enum('status', ['Dijadwalkan', 'Dalam Proses', 'Selesai', 'Dibatalkan'])->default('Dijadwalkan');
            $table->text('problem_description')->nullable();
            $table->text('action_taken')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};
