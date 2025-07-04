<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Walk-in Tickets - {{ $concert->title }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.4;
            color: #2c3e50;
            background: white;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .print-header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            border-bottom: 3px solid #3498db;
        }

        .print-header h1 {
            font-size: 28px;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .print-header .concert-info {
            font-size: 16px;
            color: #7f8c8d;
            margin-bottom: 10px;
        }

        .tickets-grid {
            display: flex;
            flex-direction: column;
            gap: 0;
            margin: 20px 0;
            width: 100%;
        }

        .ticket {
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            background: white;
            page-break-inside: avoid;
            break-inside: avoid;
            position: relative;
            width: 100%;
            box-sizing: border-box;
            display: flex;
            min-height: 220px;
            margin-bottom: 20px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        /* Main ticket section */
        .ticket-main {
            background: white;
            padding: 24px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
        }

        .walk-in-badge {
            position: absolute;
            top: 12px;
            left: 50%;
            transform: translateX(-50%);
            background: #e74c3c;
            color: white;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .ticket-header {
            margin-bottom: 16px;
        }

        .ticket-title {
            font-size: 24px;
            font-weight: bold;
            color: #000;
            margin-bottom: 4px;
        }

        .ticket-instruction {
            color: #ef4444;
            font-size: 12px;
            margin-bottom: 12px;
        }

        .ticket-type {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 16px;
            font-size: 12px;
            font-weight: bold;
            color: white;
            margin-bottom: 16px;
        }

        .ticket-details {
            color: #374151;
            margin-bottom: 16px;
        }

        .ticket-details div {
            margin-bottom: 4px;
        }

        .ticket-details strong {
            font-weight: 600;
        }

        .detail-row {
            display: flex;
            flex-direction: column;
            font-size: 13px;
        }

        .detail-label {
            font-weight: 600;
            color: #34495e;
            font-size: 11px;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .detail-value {
            color: #2c3e50;
            font-weight: 500;
        }

        /* QR code section */
        .qr-section {
            background: white;
            border-left: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
            width: 200px;
            flex-direction: column;
            position: relative;
        }

        .qr-section h4 {
            font-size: 11px;
            color: #7f8c8d;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .qr-code {
            background: white;
            padding: 8px;
            border-radius: 8px;
        }

        .qr-code img, .qr-code svg {
            width: 160px;
            height: 160px;
            display: block;
        }



        /* Colored ticket stub */
        .ticket-stub {
            position: relative;
            width: 80px;
            padding: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Perforated edge */
        .ticket-stub::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            width: 12px;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 12 100"><circle cx="6" cy="8" r="2" fill="white"/><circle cx="6" cy="20" r="2" fill="white"/><circle cx="6" cy="32" r="2" fill="white"/><circle cx="6" cy="44" r="2" fill="white"/><circle cx="6" cy="56" r="2" fill="white"/><circle cx="6" cy="68" r="2" fill="white"/><circle cx="6" cy="80" r="2" fill="white"/><circle cx="6" cy="92" r="2" fill="white"/></svg>') repeat-y;
        }

        .stub-text {
            transform: rotate(90deg);
            white-space: nowrap;
            text-align: center;
            color: white;
            font-weight: bold;
        }

        .stub-title {
            font-size: 14px;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .stub-order {
            font-size: 12px;
        }

        /* Dynamic colors based on concert ID */
        .color-emerald { background: #10b981; }
        .color-orange { background: #f97316; }
        .color-sky { background: #0ea5e9; }
        .color-purple { background: #8b5cf6; }
        .color-amber { background: #f59e0b; }
        .color-pink { background: #ec4899; }

        /* Ticket type colors */
        .type-emerald { background: #10b981; }
        .type-orange { background: #f97316; }
        .type-sky { background: #0ea5e9; }
        .type-purple { background: #8b5cf6; }
        .type-amber { background: #f59e0b; }
        .type-pink { background: #ec4899; }

        .summary-section {
            margin: 30px 0;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 5px solid #3498db;
        }

        .summary-title {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 15px;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }

        .summary-item {
            text-align: center;
        }

        .summary-number {
            font-size: 24px;
            font-weight: bold;
            color: #3498db;
        }

        .summary-label {
            font-size: 12px;
            color: #7f8c8d;
            margin-top: 5px;
        }

        /* Print styles */
        @media print {
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            
            body {
                margin: 0;
                padding: 10px;
                font-size: 12px;
                background: white !important;
            }
            
            .print-header {
                margin-bottom: 20px;
                padding: 10px;
                border-bottom: 3px solid #3498db !important;
            }
            
            .tickets-grid {
                gap: 0 !important;
                display: flex !important;
                flex-direction: column !important;
            }
            
            .ticket {
                border: 2px solid #e5e7eb !important;
                border-radius: 12px !important;
                background: white !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
                position: relative;
                display: flex !important;
                min-height: 220px !important;
                margin-bottom: 20px !important;
                overflow: hidden !important;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
            }
            
            .ticket-main {
                background: white !important;
                padding: 24px !important;
                flex-grow: 1 !important;
                display: flex !important;
                flex-direction: column !important;
                justify-content: space-between !important;
                position: relative !important;
            }
            
            .walk-in-badge {
                background: #e74c3c !important;
                color: white !important;
                border-radius: 6px !important;
                left: 50% !important;
                transform: translateX(-50%) !important;
            }
            
            .qr-section {
                background: white !important;
                border-left: 1px solid #e5e7eb !important;
                width: 200px !important;
                position: relative !important;
            }
            
            .qr-code {
                background: white !important;
            }
            
            .ticket-stub {
                width: 80px !important;
            }
            
            /* Ensure colors print correctly */
            .color-emerald { background: #10b981 !important; }
            .color-orange { background: #f97316 !important; }
            .color-sky { background: #0ea5e9 !important; }
            .color-purple { background: #8b5cf6 !important; }
            .color-amber { background: #f59e0b !important; }
            .color-pink { background: #ec4899 !important; }
            
            .type-emerald { background: #10b981 !important; }
            .type-orange { background: #f97316 !important; }
            .type-sky { background: #0ea5e9 !important; }
            .type-purple { background: #8b5cf6 !important; }
            .type-amber { background: #f59e0b !important; }
            .type-pink { background: #ec4899 !important; }
            
            .summary-section {
                page-break-before: always;
                background: #f8f9fa !important;
                border-left: 5px solid #3498db !important;
            }
            
            .qr-code img, .qr-code svg {
                width: 160px !important;
                height: 160px !important;
                display: block !important;
            }
            
            /* Ensure QR codes print properly */
            svg {
                width: 160px !important;
                height: 160px !important;
            }
        }

        @page {
            margin: 0.5in;
            size: A4;
        }
        
        /* Browser compatibility */
        @media screen {
            body {
                max-width: 8.5in;
                margin: 0 auto;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Print Header -->
    <div class="print-header">
        <h1>Walk-in Tickets</h1>
        <div class="concert-info">
            <strong>{{ $concert->title }}</strong><br>
            {{ $concert->date->format('l, j F Y') }} | {{ $concert->start_time->format('g:i A') }} - {{ $concert->end_time->format('g:i A') }}<br>
            @if($concert->venue)
                {{ $concert->venue }}
            @endif
        </div>
        <div style="font-size: 14px; color: #95a5a6;">
            Generated on {{ now()->format('d M Y \a\t g:i A') }}
        </div>
    </div>

    <!-- Summary Section -->
    <div class="summary-section">
        <div class="summary-title">Ticket Summary</div>
        <div class="summary-grid">
            <div class="summary-item">
                <div class="summary-number">{{ $walkInTickets->count() }}</div>
                <div class="summary-label">Total Tickets</div>
            </div>
            <div class="summary-item">
                <div class="summary-number">{{ $walkInTickets->groupBy('ticket.ticket_type')->count() }}</div>
                <div class="summary-label">Ticket Types</div>
            </div>
            <div class="summary-item">
                <div class="summary-number">RM{{ number_format($walkInTickets->sum(function($ticket) { return $ticket->ticket->price; }), 2) }}</div>
                <div class="summary-label">Total Value</div>
            </div>
        </div>
        
        <!-- Breakdown by ticket type -->
        <div style="margin-top: 20px;">
            <h4 style="color: #34495e; margin-bottom: 10px;">Breakdown by Type:</h4>
            @foreach($walkInTickets->groupBy('ticket.ticket_type') as $type => $tickets)
                <div style="display: flex; justify-content: space-between; margin: 5px 0; padding: 8px; background: white; border-radius: 4px;">
                    <span><strong>{{ $type }}</strong> ({{ $tickets->count() }} tickets)</span>
                    <span>RM{{ number_format($tickets->sum(function($ticket) { return $ticket->ticket->price; }), 2) }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Instructions -->
    <div style="margin-top: 40px; padding: 20px; background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 8px; page-break-before: always;">
        <h3 style="color: #856404; margin-bottom: 15px;">📋 Instructions for Walk-in Ticket Sales</h3>
        <div style="color: #856404; line-height: 1.6;">
            <p><strong>Before the Concert:</strong></p>
            <ul style="margin: 10px 0 15px 20px;">
                <li>Cut out individual tickets along the borders</li>
                <li>Store tickets securely until concert day</li>
                <li>Keep this summary sheet for reference</li>
            </ul>
            
            <p><strong>During Concert Day Sales:</strong></p>
            <ul style="margin: 10px 0 15px 20px;">
                <li>Use the "Walk-in Sales" scanner to scan each QR code when payment is received</li>
                <li>Collect the exact amount shown on each ticket</li>
                <li>Hand the physical ticket to the customer after scanning</li>
                <li>Customer will use the same ticket for entry validation</li>
            </ul>
            
            <p><strong>Important Notes:</strong></p>
            <ul style="margin: 10px 0 0 20px;">
                <li>Tickets must be scanned as "sold" before they can be used for entry</li>
                <li>Each QR code is unique and can only be used once for payment and once for entry</li>
                <li>Keep unsold tickets secure - they can be deleted from the system if not needed</li>
            </ul>
        </div>
    </div>

    <!-- Tickets Grid -->
    <div class="tickets-grid">
        @foreach($walkInTickets as $purchase)
            @php
                // Generate dynamic color based on concert ID (matching printable.blade.php logic)
                $colors = ['orange', 'emerald', 'sky', 'purple', 'amber', 'pink'];
                $colorIndex = $purchase->ticket->concert_id % count($colors);
                $ticketColor = $colors[$colorIndex];
            @endphp
            
            <div class="ticket">
                <!-- Main ticket section -->
                <div class="ticket-main">
                    <div>
                        <div class="ticket-header">
                            <div class="ticket-title">{{ $purchase->ticket->concert->title }}</div>
                            <div class="ticket-instruction">Walk-in ticket for on-site sale</div>
                            <div class="ticket-type type-{{ $ticketColor }}">{{ $purchase->ticket->ticket_type }}</div>
                        </div>
                        
                        <div class="ticket-details">
                            <div><strong>Date:</strong> {{ $purchase->ticket->concert->date->format('d M Y') }}</div>
                            <div><strong>Time:</strong> {{ $purchase->ticket->concert->start_time->format('g:i A') }} - {{ $purchase->ticket->concert->end_time->format('g:i A') }}</div>
                            @if($purchase->ticket->concert->venue)
                            <div><strong>Venue:</strong> {{ $purchase->ticket->concert->venue }}</div>
                            @endif
                            <div><strong>Price:</strong> RM{{ number_format($purchase->ticket->price, 2) }}</div>
                        </div>
                        
                        <div>
                            <div><strong>Order ID:</strong> {{ $purchase->formatted_order_id }}</div>
                        </div>
                    </div>
                </div>
                
                <!-- QR code section -->
                <div class="qr-section">
                    <div class="walk-in-badge">WALK-IN</div>
                    <div class="qr-code">
                        @php
                            try {
                                $qrCodeSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
                                    ->size(160)
                                    ->margin(1)
                                    ->errorCorrection('H')
                                    ->generate($purchase->qr_code);
                                echo $qrCodeSvg; // phpcs:ignore
                            } catch (\Exception $e) {
                                echo '<div style="width: 160px; height: 160px; border: 1px solid #ccc; display: flex; align-items: center; justify-content: center; font-size: 10px; color: #999;">QR Code</div>'; // phpcs:ignore
                            }
                        @endphp
                    </div>
                </div>
                
                <!-- Colored ticket stub -->
                <div class="ticket-stub color-{{ $ticketColor }}">
                    <div class="stub-text">
                        <div class="stub-title">Walk-in Ticket</div>
                        <div class="stub-order">{{ $purchase->formatted_order_id }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <script>
        // Auto-print when page loads
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html> 