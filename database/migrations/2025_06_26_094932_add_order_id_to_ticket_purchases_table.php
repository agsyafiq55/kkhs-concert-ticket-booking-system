<?php

use App\Models\TicketPurchase;
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
        // First, add the order_id column as nullable
        Schema::table('ticket_purchases', function (Blueprint $table) {
            $table->string('order_id', 20)->nullable()->after('id');
        });

        // Populate existing records with unique order IDs
        $ticketPurchases = TicketPurchase::whereNull('order_id')->get();

        foreach ($ticketPurchases as $purchase) {
            $purchase->order_id = $this->generateOrderId();
            $purchase->save();
        }

        // Now make the order_id column unique and not nullable
        Schema::table('ticket_purchases', function (Blueprint $table) {
            $table->string('order_id', 20)->nullable(false)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket_purchases', function (Blueprint $table) {
            $table->dropColumn('order_id');
        });
    }

    /**
     * Generate a unique order ID for existing records.
     */
    private function generateOrderId(): string
    {
        do {
            // Get current date in YYYYMMDD format
            $datePrefix = now()->format('Ymd');

            // Generate a 12-digit random number
            $randomNumber = str_pad(mt_rand(0, 999999999999), 12, '0', STR_PAD_LEFT);

            // Combine them to create a 20-digit order ID
            $orderId = $datePrefix.$randomNumber;

        } while (TicketPurchase::where('order_id', $orderId)->exists());

        return $orderId;
    }
};
