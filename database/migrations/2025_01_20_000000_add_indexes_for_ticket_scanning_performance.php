<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ticket_purchases', function (Blueprint $table) {
            // Add indexes using raw SQL for TEXT columns
        });
        
        // Use raw SQL for TEXT column indexes (MySQL requires key length for TEXT/BLOB columns)
        DB::statement('CREATE INDEX ticket_purchases_qr_code_index ON ticket_purchases (qr_code(100))');
        DB::statement('CREATE INDEX ticket_purchases_status_qr_code_index ON ticket_purchases (status, qr_code(100))');
        DB::statement('CREATE INDEX ticket_purchases_walk_in_qr_code_index ON ticket_purchases (is_walk_in, qr_code(100))');
        
        Schema::table('ticket_purchases', function (Blueprint $table) {
            // Add composite index for walk-in sales status checking (no TEXT columns)
            $table->index(['is_walk_in', 'is_sold', 'status'], 'ticket_purchases_walk_in_status_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop indexes created with raw SQL
        DB::statement('DROP INDEX ticket_purchases_qr_code_index ON ticket_purchases');
        DB::statement('DROP INDEX ticket_purchases_status_qr_code_index ON ticket_purchases');
        DB::statement('DROP INDEX ticket_purchases_walk_in_qr_code_index ON ticket_purchases');
        
        Schema::table('ticket_purchases', function (Blueprint $table) {
            $table->dropIndex('ticket_purchases_walk_in_status_index');
        });
    }
}; 