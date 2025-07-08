<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Concert Ticket - {{ $purchase->ticket->concert->title }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            line-height: 1.4;
            color: #2c3e50;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        
        .ticket-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 900px;
            height: 320px;
            display: flex;
            position: relative;
            border: 2px solid #e5e7eb;
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
        
        .online-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            background: #10b981;
            color: white;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .ticket-header h1 {
            color: #000;
            font-size: 24px;
            font-weight: bold;
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
        
        .action-button {
            background: #3b82f6;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
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
        }
        
        .qr-code {
            background: white;
            padding: 8px;
            border-radius: 8px;
        }
        
        .qr-code img {
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
        
        @media print {
            @page {
                size: portrait;
                margin: 0.5in;
            }
            
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            
            body {
                background: white;
                padding: 0;
                margin: 0;
            }
            
            .ticket-container {
                box-shadow: none;
                max-width: none;
                width: 100%;
                height: 320px;
                page-break-inside: avoid;
                margin: 0;
                border: 2px solid #e5e7eb !important;
                background: white !important;
                border-radius: 12px !important;
            }
            
            .ticket-main {
                background: white !important;
                padding: 24px !important;
            }
            
            .qr-section {
                background: white !important;
                border-left: 1px solid #e5e7eb !important;
                width: 200px !important;
            }
            
            .qr-code {
                background: white !important;
            }
            
            .ticket-stub {
                width: 80px !important;
            }
            
            .online-badge {
                background: #10b981 !important;
                color: white !important;
            }
            
            .action-button {
                background: #3b82f6 !important;
                color: white !important;
            }
            
            .print-button {
                display: none;
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
        }
        
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #0ea5e9;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(14, 165, 233, 0.3);
            transition: all 0.2s;
            z-index: 1000;
        }
        
        .print-button:hover {
            background: #0284c7;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(14, 165, 233, 0.4);
        }
    </style>
</head>
<body>
    <button class="print-button" onclick="window.print()">🖨️ Print Ticket</button>
    
    @php
        // Generate dynamic color based on concert ID (matching my-tickets.blade.php logic)
        $colors = ['orange', 'emerald', 'sky', 'purple', 'amber', 'pink'];
        $colorIndex = $purchase->ticket->concert_id % count($colors);
        $ticketColor = $colors[$colorIndex];
    @endphp
    
    <div class="ticket-container">
        <!-- Main ticket section -->
        <div class="ticket-main">
            <div class="online-badge">
                @if($purchase->is_vip)
                    VIP
                @else
                    ONLINE
                @endif
            </div>
            
            <div>
                <div class="ticket-header">
                    <h1>{{ $purchase->ticket->concert->title }}</h1>
                    <div class="ticket-instruction">Please present this ticket at entry</div>
                    <div class="ticket-type type-{{ $ticketColor }}">{{ $purchase->ticket->ticket_type }}</div>
                </div>
                
                <div class="ticket-details">
                    <div><strong>Date:</strong> {{ $purchase->ticket->concert->date->format('d M Y') }}</div>
                    <div><strong>Time:</strong> {{ $purchase->ticket->concert->start_time->format('g:i A') }} - {{ $purchase->ticket->concert->end_time ? $purchase->ticket->concert->end_time->format('g:i A') : 'TBA' }}</div>
                    @if($purchase->ticket->concert->venue)
                    <div><strong>Venue:</strong> {{ $purchase->ticket->concert->venue }}</div>
                    @endif
                    <div><strong>Price:</strong> RM{{ number_format($purchase->ticket->price, 2) }}</div>
                </div>
                
                <div>
                    <div><strong>Ticket Holder:</strong> 
                        @if($purchase->is_vip)
                            {{ $purchase->vip_name }}
                        @else
                            {{ $purchase->student->name }}
                        @endif
                    </div>
                    <div><strong>Order ID:</strong> {{ $purchase->formatted_order_id }}</div>
                </div>
            </div>
        </div>
        
        <!-- QR code section -->
        <div class="qr-section">
            <div class="qr-code">
                @php
                    $token = hash('sha256', $purchase->id . $purchase->qr_code . config('app.key'));
                    $qrUrl = route('qr.ticket', ['id' => $purchase->id, 'token' => $token]);
                @endphp
                <img src="{{ $qrUrl }}" alt="Entry QR Code" />
            </div>
        </div>
        
        <!-- Colored ticket stub -->
        <div class="ticket-stub color-{{ $ticketColor }}">
            <div class="stub-text">
                <div class="stub-title">Concert Ticket</div>
                <div class="stub-order">{{ $purchase->formatted_order_id }}</div>
            </div>
        </div>
    </div>
</body>
</html> 