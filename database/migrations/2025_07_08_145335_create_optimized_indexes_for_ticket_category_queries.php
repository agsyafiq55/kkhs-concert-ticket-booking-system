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
     * These indexes optimize the new relationship-based ticket categorization system
     * that uses ticket_category field instead of boolean flags.
     */
    public function up(): void
    {
        // 1. Primary index on tickets.ticket_category for filtering tickets by category
        Schema::table('tickets', function (Blueprint $table) {
            $table->index('ticket_category', 'tickets_category_index');
        });
        
        // 2. Composite index for concert + category filtering (common in admin views)
        Schema::table('tickets', function (Blueprint $table) {
            $table->index(['concert_id', 'ticket_category'], 'tickets_concert_category_index');
        });
        
        // 3. Composite index for ticket purchases + ticket relationship queries
        // This optimizes joins between ticket_purchases and tickets with category filtering
        Schema::table('ticket_purchases', function (Blueprint $table) {
            $table->index(['ticket_id', 'status'], 'ticket_purchases_ticket_status_index');
        });
        
        // 4. Composite index for walk-in ticket queries (category + sold status)
        // This optimizes queries that filter walk-in tickets by sold status
        Schema::table('ticket_purchases', function (Blueprint $table) {
            $table->index(['status', 'is_sold'], 'ticket_purchases_status_sold_index');
        });
        
        // 5. Index for purchase date filtering (used in dashboard and reports)
        Schema::table('ticket_purchases', function (Blueprint $table) {
            $table->index('purchase_date', 'ticket_purchases_purchase_date_index');
        });
        
        // 6. Composite index for teacher performance queries
        Schema::table('ticket_purchases', function (Blueprint $table) {
            $table->index(['teacher_id', 'status'], 'ticket_purchases_teacher_status_index');
        });
        
        // 7. Create a covering index for revenue calculations
        // This index covers the most common fields used in revenue queries
        if (DB::connection()->getDriverName() === 'mysql') {
            // MySQL supports covering indexes with included columns
            DB::statement('CREATE INDEX ticket_purchases_revenue_covering_index ON ticket_purchases (ticket_id, status, is_sold, teacher_id)');
        } else {
            // For other databases, create a simple composite index
            Schema::table('ticket_purchases', function (Blueprint $table) {
                $table->index(['ticket_id', 'status', 'is_sold'], 'ticket_purchases_revenue_index');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropIndex('tickets_category_index');
            $table->dropIndex('tickets_concert_category_index');
        });
        
        Schema::table('ticket_purchases', function (Blueprint $table) {
            $table->dropIndex('ticket_purchases_ticket_status_index');
            $table->dropIndex('ticket_purchases_status_sold_index');
            $table->dropIndex('ticket_purchases_purchase_date_index');
            $table->dropIndex('ticket_purchases_teacher_status_index');
        });
        
        // Drop the covering index
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement('DROP INDEX ticket_purchases_revenue_covering_index ON ticket_purchases');
        } else {
            Schema::table('ticket_purchases', function (Blueprint $table) {
                $table->dropIndex('ticket_purchases_revenue_index');
            });
        }
    }
};
