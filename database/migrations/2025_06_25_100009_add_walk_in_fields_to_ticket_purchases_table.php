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
            // Make student_id nullable to allow walk-in tickets without student accounts
            $table->foreignId('student_id')->nullable()->change();
            
            // Add walk-in specific fields
            $table->boolean('is_walk_in')->default(false)->after('status');
            $table->boolean('is_sold')->default(false)->after('is_walk_in');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket_purchases', function (Blueprint $table) {
            // Remove the new columns
            $table->dropColumn(['is_walk_in', 'is_sold']);
            
            // Revert student_id to not nullable (but be careful with existing data)
            $table->foreignId('student_id')->nullable(false)->change();
        });
    }
};
