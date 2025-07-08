<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration removes the legacy boolean fields (is_walk_in, is_vip) and their
     * associated indexes, completing the migration to relationship-based ticket categorization.
     */
    public function up(): void
    {
        // First, drop indexes that use the legacy boolean fields
        
        // Drop the walk-in specific indexes (from the ticket scanning performance migration)
        if (DB::connection()->getDriverName() === 'mysql') {
            // Drop MySQL-specific indexes
            try {
                DB::statement('DROP INDEX ticket_purchases_walk_in_qr_code_index ON ticket_purchases');
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
        } else {
            // Drop SQLite/other database indexes
            try {
                DB::statement('DROP INDEX IF EXISTS ticket_purchases_walk_in_qr_code_index');
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
        }
        
        // Drop the composite walk-in status index (if it exists)
        try {
            Schema::table('ticket_purchases', function (Blueprint $table) {
                $table->dropIndex('ticket_purchases_walk_in_status_index');
            });
        } catch (\Exception $e) {
            // Index might not exist (especially in test environments), continue
        }
        
        // Now remove the legacy boolean columns
        Schema::table('ticket_purchases', function (Blueprint $table) {
            $table->dropColumn(['is_walk_in', 'is_vip']);
        });
    }

    /**
     * Reverse the migrations.
     * 
     * This restores the legacy boolean fields and their indexes for rollback purposes.
     * NOTE: Data will be lost when rolling back - the boolean fields will be restored
     * but will need to be repopulated based on ticket relationships.
     */
    public function down(): void
    {
        // Restore the legacy boolean columns
        Schema::table('ticket_purchases', function (Blueprint $table) {
            $table->boolean('is_walk_in')->default(false)->after('status');
            $table->boolean('is_vip')->default(false)->after('is_sold');
        });
        
        // Restore the indexes that used these fields
        
        // Restore the walk-in QR code index
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement('CREATE INDEX ticket_purchases_walk_in_qr_code_index ON ticket_purchases (is_walk_in, qr_code(100))');
        } else {
            DB::statement('CREATE INDEX IF NOT EXISTS ticket_purchases_walk_in_qr_code_index ON ticket_purchases (is_walk_in, qr_code)');
        }
        
        // Restore the composite walk-in status index
        Schema::table('ticket_purchases', function (Blueprint $table) {
            $table->index(['is_walk_in', 'is_sold', 'status'], 'ticket_purchases_walk_in_status_index');
        });
        
        // Important: After rollback, you would need to run a data migration to repopulate
        // the boolean fields based on the ticket relationships:
        // 
        // UPDATE ticket_purchases tp 
        // JOIN tickets t ON tp.ticket_id = t.id 
        // SET tp.is_walk_in = (t.ticket_category = 'walk-in');
        // 
        // UPDATE ticket_purchases tp 
        // JOIN tickets t ON tp.ticket_id = t.id 
        // SET tp.is_vip = (t.ticket_category = 'vip');
    }
};
