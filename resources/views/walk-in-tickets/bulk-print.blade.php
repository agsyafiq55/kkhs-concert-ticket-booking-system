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
            border: 2px solid #34495e;
            border-radius: 0;
            padding: 20px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            page-break-inside: avoid;
            break-inside: avoid;
            position: relative;
            width: 100%;
            box-sizing: border-box;
            display: flex;
            align-items: center;
            gap: 30px;
            min-height: 200px;
            border-bottom: none;
        }

        .ticket::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 8px;
            background: linear-gradient(90deg, #3498db, #2ecc71, #f39c12, #e74c3c);
            border-radius: 0;
        }
        
        .ticket:last-child {
            border-bottom: 2px solid #34495e;
        }

        .ticket-left {
            flex: 1;
            padding-right: 20px;
        }

        .ticket-header {
            margin-bottom: 15px;
        }

        .ticket-title {
            font-size: 20px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 8px;
        }

        .ticket-type {
            font-size: 14px;
            color: #3498db;
            font-weight: bold;
            background: #ecf0f1;
            padding: 6px 15px;
            border-radius: 4px;
            display: inline-block;
            margin-bottom: 10px;
        }

        .ticket-details {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px 20px;
            margin: 15px 0;
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

        .ticket-right {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-width: 220px;
            text-align: center;
        }

        .price-highlight {
            padding: 12px 20px;
            background: #2ecc71;
            color: white;
            border-radius: 4px;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            width: 100%;
        }

        .qr-section {
            text-align: center;
        }

        .qr-section h4 {
            font-size: 11px;
            color: #7f8c8d;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .qr-code {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .qr-code img, .qr-code svg {
            max-width: 150px;
            max-height: 150px;
            border: 1px solid #bdc3c7;
            border-radius: 0;
            display: block;
            margin: 0 auto;
        }

        .walk-in-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: #e74c3c;
            color: white;
            padding: 4px 8px;
            border-radius: 0;
            font-size: 10px;
            font-weight: bold;
        }

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
                margin-bottom: 0 !important;
                border: 1px solid #95a5a6 !important;
                border-radius: 0 !important;
                background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%) !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
                position: relative;
                display: flex !important;
                align-items: center !important;
                gap: 25px !important;
                min-height: 180px !important;
                border-bottom: none !important;
            }
            
            .ticket:last-child {
                border-bottom: 1px solid #95a5a6 !important;
            }
            
            .ticket::before {
                background: linear-gradient(90deg, #3498db, #2ecc71, #f39c12, #e74c3c) !important;
                content: '' !important;
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 8px;
                border-radius: 0 !important;
            }
            
            .walk-in-badge {
                background: #e74c3c !important;
                color: white !important;
                border-radius: 0 !important;
            }
            
            .ticket-type {
                background: #ecf0f1 !important;
                color: #3498db !important;
                border-radius: 4px !important;
            }
            
            .price-highlight {
                background: #2ecc71 !important;
                color: white !important;
                border-radius: 4px !important;
            }
            
            .summary-section {
                page-break-before: always;
                background: #f8f9fa !important;
                border-left: 5px solid #3498db !important;
            }
            
            .qr-code img, .qr-code svg {
                border: 1px solid #d5d5d5 !important;
                border-radius: 0 !important;
                max-width: 150px !important;
                max-height: 150px !important;
            }
            
            /* Ensure QR codes print properly */
            svg {
                max-width: 150px !important;
                max-height: 150px !important;
            }
            
            .ticket-left {
                flex: 1 !important;
                padding-right: 20px !important;
            }
            
            .ticket-right {
                min-width: 220px !important;
                display: flex !important;
                flex-direction: column !important;
                align-items: center !important;
                justify-content: center !important;
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
            {{ $concert->date->format('l, F j, Y') }} at {{ $concert->start_time->format('g:i A') }}<br>
            @if($concert->venue)
                {{ $concert->venue }}
            @endif
        </div>
        <div style="font-size: 14px; color: #95a5a6;">
            Generated on {{ now()->format('M d, Y \a\t g:i A') }} | 
            Total Tickets: {{ $walkInTickets->count() }} | 
            Total Value: RM{{ number_format($walkInTickets->sum(function($ticket) { return $ticket->ticket->price; }), 2) }}
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

    <!-- Tickets Grid -->
    <div class="tickets-grid">
        @foreach($walkInTickets as $purchase)
            <div class="ticket">
                <div class="walk-in-badge">WALK-IN</div>
                
                <!-- Left side: Ticket info -->
                <div class="ticket-left">
                    <div class="ticket-header">
                        <div class="ticket-title">{{ $purchase->ticket->concert->title }}</div>
                        <div class="ticket-type">{{ $purchase->ticket->ticket_type }}</div>
                    </div>

                    <div class="ticket-details">
                        <div class="detail-row">
                            <span class="detail-label">Date</span>
                            <span class="detail-value">{{ $purchase->ticket->concert->date->format('M d, Y') }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Time</span>
                            <span class="detail-value">{{ $purchase->ticket->concert->start_time->format('g:i A') }}</span>
                        </div>
                        @if($purchase->ticket->concert->venue)
                            <div class="detail-row">
                                <span class="detail-label">Venue</span>
                                <span class="detail-value">{{ $purchase->ticket->concert->venue }}</span>
                            </div>
                        @endif
                        <div class="detail-row">
                            <span class="detail-label">Generated by</span>
                            <span class="detail-value">{{ $purchase->teacher->name ?? 'Unknown' }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Generated</span>
                            <span class="detail-value">{{ $purchase->created_at->format('M d, g:i A') }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Ticket ID</span>
                            <span class="detail-value">#{{ $purchase->id }}</span>
                        </div>
                    </div>
                </div>

                <!-- Right side: Price and QR -->
                <div class="ticket-right">
                    <div class="price-highlight">
                        RM{{ number_format($purchase->ticket->price, 2) }}
                    </div>

                    <div class="qr-section">
                        <h4>Entry QR Code</h4>
                        <div class="qr-code">
                            @php
                                try {
                                    $qrCodeSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
                                        ->size(150)
                                        ->margin(1)
                                        ->errorCorrection('H')
                                        ->generate($purchase->qr_code);
                                    echo $qrCodeSvg; // phpcs:ignore
                                } catch (\Exception $e) {
                                    echo '<div style="width: 150px; height: 150px; border: 1px solid #ccc; display: flex; align-items: center; justify-content: center; font-size: 10px; color: #999;">QR Code</div>'; // phpcs:ignore
                                }
                            @endphp
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
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