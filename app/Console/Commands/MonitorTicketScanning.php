<?php

namespace App\Console\Commands;

use App\Models\TicketPurchase;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MonitorTicketScanning extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ticket:monitor-scanning {--real-time : Monitor in real-time mode}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Monitor ticket scanning performance and detect concurrency issues';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('real-time')) {
            $this->monitorRealTime();
        } else {
            $this->showCurrentStats();
        }
    }

    /**
     * Show current scanning statistics
     */
    private function showCurrentStats()
    {
        $this->info('=== TICKET SCANNING MONITORING REPORT ===');
        $this->info('Generated at: ' . now()->format('Y-m-d H:i:s'));
        $this->newLine();

        // Overall statistics
        $totalTickets = TicketPurchase::count();
        $usedTickets = TicketPurchase::where('status', 'used')->count();
        $validTickets = TicketPurchase::where('status', 'valid')->count();
        $cancelledTickets = TicketPurchase::where('status', 'cancelled')->count();

        $this->table(
            ['Metric', 'Count', 'Percentage'],
            [
                ['Total Tickets', number_format($totalTickets), '100%'],
                ['Used/Scanned', number_format($usedTickets), round(($usedTickets / max($totalTickets, 1)) * 100, 2) . '%'],
                ['Valid/Unused', number_format($validTickets), round(($validTickets / max($totalTickets, 1)) * 100, 2) . '%'],
                ['Cancelled', number_format($cancelledTickets), round(($cancelledTickets / max($totalTickets, 1)) * 100, 2) . '%'],
            ]
        );

        // Recent scanning activity (last hour)
        $recentScans = TicketPurchase::where('status', 'used')
            ->where('updated_at', '>=', now()->subHour())
            ->orderBy('updated_at', 'desc')
            ->count();

        $this->newLine();
        $this->info("Recent Activity (Last Hour): $recentScans tickets scanned");

        // Potential concurrency issues detection
        $this->detectConcurrencyIssues();

        // Database performance metrics
        $this->showDatabaseMetrics();
    }

    /**
     * Monitor scanning in real-time
     */
    private function monitorRealTime()
    {
        $this->info('=== REAL-TIME TICKET SCANNING MONITOR ===');
        $this->info('Press Ctrl+C to stop monitoring');
        $this->newLine();

        $lastUsedCount = TicketPurchase::where('status', 'used')->count();
        $startTime = now();

        while (true) {
            $currentUsedCount = TicketPurchase::where('status', 'used')->count();
            $newScans = $currentUsedCount - $lastUsedCount;

            if ($newScans > 0) {
                $this->line(
                    now()->format('H:i:s') . 
                    " - $newScans new scan(s) | Total: $currentUsedCount | " .
                    "Rate: " . round($currentUsedCount / max($startTime->diffInMinutes(now()), 1), 2) . " scans/min"
                );

                // Check for rapid concurrent scans (potential issues)
                if ($newScans > 10) {
                    $this->warn("⚠️  High concurrent activity detected: $newScans scans in last 5 seconds");
                }

                $lastUsedCount = $currentUsedCount;
            }

            sleep(5); // Check every 5 seconds
        }
    }

    /**
     * Detect potential concurrency issues
     */
    private function detectConcurrencyIssues()
    {
        $this->newLine();
        $this->info('=== CONCURRENCY ANALYSIS ===');

        // Check for tickets scanned very close together (potential race conditions)
        $suspiciousScans = DB::select("
            SELECT 
                tp1.qr_code,
                tp1.updated_at as scan1_time,
                tp2.updated_at as scan2_time,
                TIMESTAMPDIFF(MICROSECOND, tp1.updated_at, tp2.updated_at) / 1000 as gap_ms
            FROM ticket_purchases tp1
            JOIN ticket_purchases tp2 ON tp1.qr_code = tp2.qr_code
            WHERE tp1.status = 'used' 
            AND tp2.status = 'used'
            AND tp1.id != tp2.id
            AND TIMESTAMPDIFF(SECOND, tp1.updated_at, tp2.updated_at) <= 5
            ORDER BY gap_ms ASC
            LIMIT 10
        ");

        if (count($suspiciousScans) > 0) {
            $this->warn('⚠️  Potential concurrency issues detected:');
            foreach ($suspiciousScans as $scan) {
                $this->line("  QR: " . substr($scan->qr_code, -10) . " | Gap: {$scan->gap_ms}ms");
            }
        } else {
            $this->info('✅ No concurrency issues detected');
        }

        // Check for deadlock indicators in logs (if available)
        $this->checkForDeadlocks();
    }

    /**
     * Show database performance metrics
     */
    private function showDatabaseMetrics()
    {
        $this->newLine();
        $this->info('=== DATABASE PERFORMANCE ===');

        try {
            // Get current database connections
            $connections = DB::select("SHOW STATUS LIKE 'Threads_connected'");
            $maxConnections = DB::select("SHOW VARIABLES LIKE 'max_connections'");

            if (!empty($connections) && !empty($maxConnections)) {
                $current = $connections[0]->Value;
                $max = $maxConnections[0]->Value;
                $this->line("Database Connections: $current / $max");
            }

            // Get innodb status for lock waits
            $lockWaits = DB::select("SHOW STATUS LIKE 'Innodb_row_lock_waits'");
            if (!empty($lockWaits)) {
                $this->line("Row Lock Waits: " . $lockWaits[0]->Value);
            }

            // Check table status for ticket_purchases
            $tableStatus = DB::select("SHOW TABLE STATUS LIKE 'ticket_purchases'");
            if (!empty($tableStatus)) {
                $rows = number_format($tableStatus[0]->Rows);
                $avgRowLength = number_format($tableStatus[0]->Avg_row_length);
                $this->line("Table Rows: $rows | Avg Row Length: {$avgRowLength} bytes");
            }

        } catch (\Exception $e) {
            $this->warn('Could not retrieve database metrics: ' . $e->getMessage());
        }
    }

    /**
     * Check for deadlock indicators
     */
    private function checkForDeadlocks()
    {
        // This would typically check log files, but we'll check for recent database errors
        try {
            $errorLog = storage_path('logs/laravel.log');
            if (file_exists($errorLog)) {
                $logContent = file_get_contents($errorLog);
                $deadlockCount = substr_count(strtolower($logContent), 'deadlock');
                
                if ($deadlockCount > 0) {
                    $this->warn("⚠️  Found $deadlockCount deadlock mention(s) in application logs");
                } else {
                    $this->info('✅ No deadlocks found in recent logs');
                }
            }
        } catch (\Exception $e) {
            $this->line('Could not check log files for deadlocks');
        }
    }
} 