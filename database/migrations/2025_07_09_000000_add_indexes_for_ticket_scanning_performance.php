<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if is_walk_in column exists before creating indexes that depend on it
        $hasWalkInColumn = Schema::hasColumn('ticket_purchases', 'is_walk_in');

        Schema::table('ticket_purchases', function (Blueprint $table) {
            // Add indexes using raw SQL for TEXT columns
        });

        // Only create indexes for MySQL/MariaDB (SQLite doesn't support key length specification)
        if (DB::connection()->getDriverName() === 'mysql') {
            // Use raw SQL for TEXT column indexes (MySQL requires key length for TEXT/BLOB columns)
            try {
                DB::statement('CREATE INDEX ticket_purchases_qr_code_index ON ticket_purchases (qr_code(100))');
            } catch (\Exception $e) {
                // Index might already exist, continue
            }

            try {
                DB::statement('CREATE INDEX ticket_purchases_status_qr_code_index ON ticket_purchases (status, qr_code(100))');
            } catch (\Exception $e) {
                // Index might already exist, continue
            }

            // Only create walk-in index if column exists (for backward compatibility)
            if ($hasWalkInColumn) {
                try {
                    DB::statement('CREATE INDEX ticket_purchases_walk_in_qr_code_index ON ticket_purchases (is_walk_in, qr_code(100))');
                } catch (\Exception $e) {
                    // Index might already exist, continue
                }
            }
        } else {
            // For SQLite and other databases, create indexes without key length
            try {
                DB::statement('CREATE INDEX IF NOT EXISTS ticket_purchases_qr_code_index ON ticket_purchases (qr_code)');
            } catch (\Exception $e) {
                // Index might already exist, continue
            }

            try {
                DB::statement('CREATE INDEX IF NOT EXISTS ticket_purchases_status_qr_code_index ON ticket_purchases (status, qr_code)');
            } catch (\Exception $e) {
                // Index might already exist, continue
            }

            // Only create walk-in index if column exists (for backward compatibility)
            if ($hasWalkInColumn) {
                try {
                    DB::statement('CREATE INDEX IF NOT EXISTS ticket_purchases_walk_in_qr_code_index ON ticket_purchases (is_walk_in, qr_code)');
                } catch (\Exception $e) {
                    // Index might already exist, continue
                }
            }
        }

        // Only create composite index if walk-in column exists
        if ($hasWalkInColumn) {
            try {
                Schema::table('ticket_purchases', function (Blueprint $table) {
                    // Add composite index for walk-in sales status checking (no TEXT columns)
                    $table->index(['is_walk_in', 'is_sold', 'status'], 'ticket_purchases_walk_in_status_index');
                });
            } catch (\Exception $e) {
                // Index might already exist, continue
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop indexes created with raw SQL based on database type
        if (DB::connection()->getDriverName() === 'mysql') {
            try {
                DB::statement('DROP INDEX ticket_purchases_qr_code_index ON ticket_purchases');
            } catch (\Exception $e) {
                // Index might not exist, continue
            }

            try {
                DB::statement('DROP INDEX ticket_purchases_status_qr_code_index ON ticket_purchases');
            } catch (\Exception $e) {
                // Index might not exist, continue
            }

            try {
                DB::statement('DROP INDEX ticket_purchases_walk_in_qr_code_index ON ticket_purchases');
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
        } else {
            // For SQLite and other databases
            try {
                DB::statement('DROP INDEX IF EXISTS ticket_purchases_qr_code_index');
            } catch (\Exception $e) {
                // Index might not exist, continue
            }

            try {
                DB::statement('DROP INDEX IF EXISTS ticket_purchases_status_qr_code_index');
            } catch (\Exception $e) {
                // Index might not exist, continue
            }

            try {
                DB::statement('DROP INDEX IF EXISTS ticket_purchases_walk_in_qr_code_index');
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
        }

        try {
            Schema::table('ticket_purchases', function (Blueprint $table) {
                $table->dropIndex('ticket_purchases_walk_in_status_index');
            });
        } catch (\Exception $e) {
            // Index might not exist, continue
        }
    }
};
