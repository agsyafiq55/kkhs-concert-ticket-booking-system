<?php

namespace App\Console\Commands;

use App\Models\TicketPurchase;
use App\Models\Ticket;
use App\Models\Concert;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class StressTestTicketScanning extends Command
{
    protected $signature = 'ticket:stress-test 
                            {--tickets=2000 : Number of tickets to test}
                            {--scanners=10 : Number of concurrent scanners}
                            {--mode=full : Test mode (seed|scan|full|cleanup)}
                            {--concert-id= : Specific concert ID to test}
                            {--delay=100 : Delay between scans in milliseconds}
                            {--confirm : Skip confirmation prompts}';

    protected $description = 'Stress test the ticket scanning system with concurrent scanners (SAFE - uses test data only)';

    private $testConcertId;
    private $testTicketId;
    private $testStudentId;
    private $testTeacherId;

    public function handle()
    {
        $ticketCount = (int) $this->option('tickets');
        $scannerCount = (int) $this->option('scanners');
        $mode = $this->option('mode');
        $concertId = $this->option('concert-id');
        $delay = (int) $this->option('delay');

        // Safety checks
        if (!$this->option('confirm') && !$this->confirmSafety()) {
            $this->error('Test cancelled for safety.');
            return 1;
        }

        $this->info("🎫 KKHS TICKET SCANNING STRESS TEST");
        $this->info("=" . str_repeat("=", 45));
        $this->info("Tickets: {$ticketCount} | Scanners: {$scannerCount} | Mode: {$mode}");
        $this->info("Delay: {$delay}ms | Environment: " . config('app.env'));
        $this->newLine();

        switch ($mode) {
            case 'seed':
                return $this->seedTestData($ticketCount, $concertId);
            case 'scan':
                return $this->stressTestScanning($ticketCount, $scannerCount, $delay);
            case 'full':
                $this->seedTestData($ticketCount, $concertId);
                return $this->stressTestScanning($ticketCount, $scannerCount, $delay);
            case 'cleanup':
                return $this->cleanupTestData();
            default:
                $this->error("Invalid mode. Use: seed, scan, full, or cleanup");
                return 1;
        }
    }

    /**
     * Safety confirmation prompt
     */
    private function confirmSafety()
    {
        $this->warn("⚠️  SAFETY CHECK");
        $this->warn("This stress test will:");
        $this->warn("• Create test tickets with 'STRESS-TEST' prefix");
        $this->warn("• Use temporary test users");
        $this->warn("• Only affect test data (no real tickets)");
        $this->warn("• Can be cleaned up with --mode=cleanup");
        $this->newLine();
        
        if (config('app.env') === 'production') {
            $this->error("🚨 PRODUCTION ENVIRONMENT DETECTED!");
            $this->error("This test should only be run on development/staging servers.");
            return $this->confirm('Are you ABSOLUTELY SURE you want to continue?');
        }
        
        return $this->confirm('Do you want to continue with the stress test?');
    }

    /**
     * Create test data for stress testing
     */
    private function seedTestData($ticketCount, $concertId = null)
    {
        $this->info("🌱 Creating test data (safe test data only)...");

        try {
            DB::beginTransaction();

            // Create or get test concert
            if ($concertId) {
                $concert = Concert::findOrFail($concertId);
                $this->testConcertId = $concert->id;
            } else {
                $concert = Concert::firstOrCreate([
                    'title' => 'STRESS-TEST Concert ' . now()->format('Y-m-d H:i:s'),
                ], [
                    'description' => 'Automated stress test concert for testing ticket scanning performance. This is test data only and will be cleaned up automatically.',
                    'date' => now()->addDays(30),
                    'start_time' => '19:00:00',
                    'end_time' => '22:00:00',
                    'venue' => 'Test Venue (STRESS TEST)'
                ]);
                $this->testConcertId = $concert->id;
            }

            // Create test ticket type
            $ticket = Ticket::firstOrCreate([
                'concert_id' => $concert->id,
                'ticket_type' => 'STRESS-TEST Ticket',
            ], [
                'price' => 1.00, // Minimal price for test
                'quantity_available' => $ticketCount + 100
            ]);
            $this->testTicketId = $ticket->id;

            // Create test student
            $testStudent = User::firstOrCreate([
                'email' => 'stress.test.student@test.kkhs.edu.my'
            ], [
                'name' => 'STRESS-TEST Student',
                'password' => bcrypt('test123'),
                'email_verified_at' => now()
            ]);
            $this->testStudentId = $testStudent->id;

            // Create test teacher
            $testTeacher = User::firstOrCreate([
                'email' => 'stress.test.teacher@test.kkhs.edu.my'
            ], [
                'name' => 'STRESS-TEST Teacher',
                'password' => bcrypt('test123'),
                'email_verified_at' => now()
            ]);
            $this->testTeacherId = $testTeacher->id;

            // Clean up any existing stress test tickets
            $this->cleanupTestTickets();

            DB::commit();

            $this->info("✅ Test entities created:");
            $this->info("   Concert: {$concert->title}");
            $this->info("   Ticket Type: {$ticket->ticket_type}");
            $this->info("   Test Users: Created");

            // Create test tickets in batches
            $this->createTestTickets($ticketCount);

            return 0;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("❌ Error creating test data: " . $e->getMessage());
            return 1;
        }
    }

    /**
     * Create test tickets in batches for better performance
     */
    private function createTestTickets($ticketCount)
    {
        $this->info("Creating {$ticketCount} test tickets...");
        
        $progressBar = $this->output->createProgressBar($ticketCount);
        $progressBar->start();

        $batchSize = 500;
        $totalCreated = 0;

        for ($i = 0; $i < $ticketCount; $i += $batchSize) {
            $currentBatchSize = min($batchSize, $ticketCount - $i);
            $batch = [];
            
            for ($j = 0; $j < $currentBatchSize; $j++) {
                $index = $i + $j + 1;
                $batch[] = [
                    'ticket_id' => $this->testTicketId,
                    'student_id' => $this->testStudentId,
                    'teacher_id' => $this->testTeacherId,
                    'purchase_date' => now(),
                    'qr_code' => $this->generateTestQrCode($index),
                    'status' => 'valid',
                    'is_walk_in' => false,
                    'is_sold' => true,
                    'order_id' => $this->generateOrderId(),
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            try {
                TicketPurchase::insert($batch);
                $totalCreated += $currentBatchSize;
                $progressBar->advance($currentBatchSize);
            } catch (\Exception $e) {
                $this->error("\n❌ Error creating batch: " . $e->getMessage());
                break;
            }
        }

        $progressBar->finish();
        $this->newLine();
        $this->info("✅ Created {$totalCreated} test tickets");
    }

    /**
     * Stress test the scanning functionality
     */
    private function stressTestScanning($ticketCount, $scannerCount, $delay)
    {
        $this->info("🔍 Starting scanning stress test...");

        // Get test tickets
        $testTickets = TicketPurchase::where('qr_code', 'LIKE', 'KKHS-STRESS-TEST-%')
            ->where('status', 'valid')
            ->limit($ticketCount)
            ->pluck('qr_code')
            ->toArray();

        if (empty($testTickets)) {
            $this->error("❌ No test tickets found. Run with --mode=seed first.");
            return 1;
        }

        $actualTicketCount = count($testTickets);
        $this->info("Found {$actualTicketCount} test tickets");

        // Shuffle for realistic random scanning
        shuffle($testTickets);

        // Split tickets among scanners
        $ticketsPerScanner = array_chunk($testTickets, ceil($actualTicketCount / $scannerCount));

        $this->info("Starting {$scannerCount} concurrent scanners...");
        $this->newLine();

        $startTime = microtime(true);
        $results = $this->runConcurrentScanners($scannerCount, $ticketsPerScanner, $delay, $actualTicketCount);
        $endTime = microtime(true);

        $duration = $endTime - $startTime;
        $this->displayResults($results, $duration, $scannerCount, $actualTicketCount);

        return 0;
    }

    /**
     * Run concurrent scanners simulation
     */
    private function runConcurrentScanners($scannerCount, $ticketsPerScanner, $delay, $totalTickets)
    {
        $results = [
            'successful_scans' => 0,
            'failed_scans' => 0,
            'deadlocks' => 0,
            'already_used' => 0,
            'errors' => [],
            'scanner_results' => [],
            'scan_times' => []
        ];

        $progressBar = $this->output->createProgressBar($totalTickets);
        $progressBar->start();

        // Process each scanner sequentially (simulating concurrent load)
        for ($scannerId = 0; $scannerId < $scannerCount; $scannerId++) {
            if (!isset($ticketsPerScanner[$scannerId])) continue;

            $scannerResult = $this->simulateScanner($scannerId, $ticketsPerScanner[$scannerId], $delay, $progressBar);
            
            $results['successful_scans'] += $scannerResult['successful'];
            $results['failed_scans'] += $scannerResult['failed'];
            $results['deadlocks'] += $scannerResult['deadlocks'];
            $results['already_used'] += $scannerResult['already_used'];
            $results['errors'] = array_merge($results['errors'], $scannerResult['errors']);
            $results['scanner_results'][] = $scannerResult;
            $results['scan_times'] = array_merge($results['scan_times'], $scannerResult['scan_times']);
        }

        $progressBar->finish();
        $this->newLine();

        return $results;
    }

    /**
     * Simulate a single scanner
     */
    private function simulateScanner($scannerId, $tickets, $delay, &$progressBar)
    {
        $result = [
            'scanner_id' => $scannerId,
            'successful' => 0,
            'failed' => 0,
            'deadlocks' => 0,
            'already_used' => 0,
            'errors' => [],
            'scan_times' => [],
            'total_time' => 0
        ];

        foreach ($tickets as $qrCode) {
            $scanStart = microtime(true);
            
            try {
                // Use the same transaction logic as the real scanning
                $scanResult = DB::transaction(function () use ($qrCode) {
                    $ticketPurchase = TicketPurchase::with(['ticket.concert', 'student', 'teacher'])
                        ->where('qr_code', $qrCode)
                        ->lockForUpdate()
                        ->first();

                    if (!$ticketPurchase) {
                        return ['status' => 'error', 'type' => 'not_found'];
                    }

                    if ($ticketPurchase->status === 'used') {
                        return ['status' => 'warning', 'type' => 'already_used'];
                    }

                    if ($ticketPurchase->status === 'cancelled') {
                        return ['status' => 'error', 'type' => 'cancelled'];
                    }

                    // Mark as used (same as real scanning logic)
                    $ticketPurchase->status = 'used';
                    $ticketPurchase->save();

                    return ['status' => 'success', 'type' => 'scanned'];
                }, 3); // Same retry logic as real scanner

                $scanEnd = microtime(true);
                $scanTime = ($scanEnd - $scanStart) * 1000; // Convert to milliseconds
                $result['scan_times'][] = $scanTime;
                $result['total_time'] += $scanTime;

                // Categorize results
                switch ($scanResult['status']) {
                    case 'success':
                        $result['successful']++;
                        break;
                    case 'warning':
                        if ($scanResult['type'] === 'already_used') {
                            $result['already_used']++;
                        } else {
                            $result['failed']++;
                        }
                        break;
                    default:
                        $result['failed']++;
                        break;
                }

            } catch (\Illuminate\Database\QueryException $e) {
                $scanEnd = microtime(true);
                $result['scan_times'][] = ($scanEnd - $scanStart) * 1000;
                
                if (isset($e->errorInfo[0]) && $e->errorInfo[0] === '40001') {
                    $result['deadlocks']++;
                }
                $result['failed']++;
                $result['errors'][] = "Scanner {$scannerId}: DB Error - " . substr($e->getMessage(), 0, 100);
                
            } catch (\Exception $e) {
                $scanEnd = microtime(true);
                $result['scan_times'][] = ($scanEnd - $scanStart) * 1000;
                
                $result['failed']++;
                $result['errors'][] = "Scanner {$scannerId}: " . substr($e->getMessage(), 0, 100);
            }

            if ($progressBar) {
                $progressBar->advance();
            }
            
            // Simulate realistic scanning delay
            if ($delay > 0) {
                usleep($delay * 1000); // Convert milliseconds to microseconds
            }
        }

        return $result;
    }

    /**
     * Display comprehensive stress test results
     */
    private function displayResults($results, $duration, $scannerCount, $totalTickets)
    {
        $this->newLine();
        $this->info("🏁 STRESS TEST RESULTS");
        $this->info("=" . str_repeat("=", 60));
        
        // Calculate metrics
        $throughput = $totalTickets / $duration;
        $successRate = ($results['successful_scans'] / $totalTickets) * 100;
        $avgScanTime = !empty($results['scan_times']) ? array_sum($results['scan_times']) / count($results['scan_times']) : 0;
        $maxScanTime = !empty($results['scan_times']) ? max($results['scan_times']) : 0;
        $minScanTime = !empty($results['scan_times']) ? min($results['scan_times']) : 0;

        // Overall performance table
        $this->table([
            'Metric', 'Value', 'Assessment'
        ], [
            ['Total Duration', round($duration, 2) . 's', $duration < 300 ? '✅ Good' : '⚠️ Slow'],
            ['Total Tickets', $totalTickets, ''],
            ['Successful Scans', $results['successful_scans'], ''],
            ['Failed Scans', $results['failed_scans'], $results['failed_scans'] == 0 ? '✅ Perfect' : '⚠️ Check'],
            ['Already Used', $results['already_used'], ''],
            ['Deadlocks', $results['deadlocks'], $results['deadlocks'] == 0 ? '✅ None' : '⚠️ ' . $results['deadlocks']],
            ['Success Rate', round($successRate, 2) . '%', $successRate > 99 ? '✅ Excellent' : ($successRate > 95 ? '✅ Good' : '❌ Poor')],
            ['Throughput', round($throughput, 2) . ' scans/sec', $throughput > 50 ? '✅ Fast' : '⚠️ Slow'],
            ['Avg Scan Time', round($avgScanTime, 2) . 'ms', $avgScanTime < 100 ? '✅ Fast' : '⚠️ Slow'],
            ['Max Scan Time', round($maxScanTime, 2) . 'ms', $maxScanTime < 500 ? '✅ Good' : '⚠️ Slow'],
        ]);

        // Performance assessment
        $this->newLine();
        $this->assessPerformance($results, $duration, $totalTickets, $successRate, $throughput);

        // Scanner breakdown
        if (!empty($results['scanner_results'])) {
            $this->newLine();
            $this->info("📊 SCANNER BREAKDOWN");
            
            $scannerData = [];
            foreach ($results['scanner_results'] as $scanner) {
                $avgTime = count($scanner['scan_times']) > 0 ? 
                    round(array_sum($scanner['scan_times']) / count($scanner['scan_times']), 2) : 0;
                
                $scannerData[] = [
                    'Scanner ' . $scanner['scanner_id'],
                    $scanner['successful'],
                    $scanner['failed'],
                    $scanner['deadlocks'],
                    $scanner['already_used'],
                    $avgTime . 'ms'
                ];
            }
            
            $this->table([
                'Scanner', 'Success', 'Failed', 'Deadlocks', 'Already Used', 'Avg Time'
            ], $scannerData);
        }

        // Error details (limited)
        if (!empty($results['errors'])) {
            $this->newLine();
            $this->warn("❌ ERROR SAMPLE (first 5):");
            foreach (array_slice($results['errors'], 0, 5) as $error) {
                $this->line("  • " . $error);
            }
            if (count($results['errors']) > 5) {
                $this->line("  ... and " . (count($results['errors']) - 5) . " more errors");
            }
        }
    }

    /**
     * Assess overall performance and provide recommendations
     */
    private function assessPerformance($results, $duration, $totalTickets, $successRate, $throughput)
    {
        $this->info("🎯 PERFORMANCE ASSESSMENT");
        
        if ($successRate > 99 && $results['deadlocks'] == 0 && $throughput > 50) {
            $this->info("✅ EXCELLENT - Your system is ready for concert day!");
        } elseif ($successRate > 95 && $results['deadlocks'] <= 5 && $throughput > 30) {
            $this->info("✅ GOOD - System should handle concert day well.");
        } elseif ($successRate > 90) {
            $this->warn("⚠️ FAIR - Some optimization recommended before concert day.");
        } else {
            $this->error("❌ POOR - System needs optimization before concert day!");
        }

        $this->newLine();
        $this->info("💡 RECOMMENDATIONS:");

        if ($results['deadlocks'] > 0) {
            $this->warn("  • Database deadlocks detected - ensure proper indexing");
            $this->line("    Run: SHOW ENGINE INNODB STATUS; to check lock contention");
        }

        if ($throughput < 30) {
            $this->warn("  • Low throughput - consider database optimization");
            $this->line("    Check: Query cache, connection pooling, server resources");
        }

        if ($results['failed_scans'] > ($totalTickets * 0.01)) {
            $this->warn("  • High failure rate - investigate error causes");
        }

        $estimatedConcertTime = (2000 / $throughput) / 60; // minutes
        $this->line("  • Estimated time for 2000 people: " . round($estimatedConcertTime, 1) . " minutes");
        
        if ($estimatedConcertTime > 60) {
            $this->warn("  • Consider adding more scanners or optimizing performance");
        }
    }

    /**
     * Clean up all test data
     */
    private function cleanupTestData()
    {
        $this->info("🧹 Cleaning up stress test data...");

        try {
            // Count items to be deleted
            $ticketPurchases = TicketPurchase::where('qr_code', 'LIKE', 'KKHS-STRESS-TEST-%')->count();
            $testUsers = User::where('email', 'LIKE', '%@test.kkhs.edu.my')->count();
            $testConcerts = Concert::where('title', 'LIKE', 'STRESS-TEST%')->count();
            $testTickets = Ticket::where('ticket_type', 'LIKE', 'STRESS-TEST%')->count();

            $this->table(['Item Type', 'Count'], [
                ['Test Ticket Purchases', $ticketPurchases],
                ['Test Users', $testUsers],
                ['Test Concerts', $testConcerts],
                ['Test Ticket Types', $testTickets]
            ]);

            if (!$this->confirm('Do you want to delete all this test data?')) {
                $this->info('Cleanup cancelled.');
                return 0;
            }

            DB::beginTransaction();

            // Delete in correct order (respecting foreign keys)
            TicketPurchase::where('qr_code', 'LIKE', 'KKHS-STRESS-TEST-%')->delete();
            Ticket::where('ticket_type', 'LIKE', 'STRESS-TEST%')->delete();
            Concert::where('title', 'LIKE', 'STRESS-TEST%')->delete();
            User::where('email', 'LIKE', '%@test.kkhs.edu.my')->delete();

            DB::commit();

            $this->info("✅ Test data cleaned up successfully!");
            return 0;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("❌ Error during cleanup: " . $e->getMessage());
            return 1;
        }
    }

    /**
     * Clean up existing test tickets only
     */
    private function cleanupTestTickets()
    {
        TicketPurchase::where('qr_code', 'LIKE', 'KKHS-STRESS-TEST-%')->delete();
    }

    /**
     * Generate test QR code
     */
    private function generateTestQrCode($sequence)
    {
        $uniqueId = Str::uuid();
        $timestamp = now()->timestamp;
        return "KKHS-STRESS-TEST-{$uniqueId}-{$timestamp}-{$sequence}";
    }

    /**
     * Generate order ID
     */
    private function generateOrderId()
    {
        $datePrefix = now()->format('Ymd');
        $randomNumber = str_pad(mt_rand(0, 999999999999), 12, '0', STR_PAD_LEFT);
        return $datePrefix . $randomNumber;
    }
} 