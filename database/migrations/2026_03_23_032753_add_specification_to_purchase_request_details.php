<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchase_request_details', function (Blueprint $table) {
            $table->string('specification')->nullable()->after('item_name');
        });
    }

    public function down(): void
    {
        Schema::table('purchase_request_details', function (Blueprint $table) {
            $table->dropColumn('specification');
        });
    }
};
