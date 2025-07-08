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
        Schema::table('ticket_purchases', function (Blueprint $table) {
            // Add VIP customer fields
            $table->boolean('is_vip')->default(false)->after('is_sold');
            $table->string('vip_name')->nullable()->after('is_vip');
            $table->string('vip_email')->nullable()->after('vip_name');
            $table->string('vip_phone')->nullable()->after('vip_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket_purchases', function (Blueprint $table) {
            $table->dropColumn(['is_vip', 'vip_name', 'vip_email', 'vip_phone']);
        });
    }
};
