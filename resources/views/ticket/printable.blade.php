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
            border: 2px solid #34495e;
        }
        
        .ticket-main {
            flex: 1;
            padding: 20px;
            position: relative;
            display: flex;
            flex-direction: column;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }
        
        .ticket-content {
            display: flex;
            height: 100%;
            gap: 25px;
        }
        
        .ticket-left {
            flex: 2;
            display: flex;
            flex-direction: column;
        }
        
        .ticket-right {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-width: 200px;
        }
        
        .ticket-sidebar {
            width: 70px;
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        
        .ticket-sidebar::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 12px;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 12 100"><circle cx="6" cy="12" r="1.5" fill="white"/><circle cx="6" cy="25" r="1.5" fill="white"/><circle cx="6" cy="38" r="1.5" fill="white"/><circle cx="6" cy="51" r="1.5" fill="white"/><circle cx="6" cy="64" r="1.5" fill="white"/><circle cx="6" cy="77" r="1.5" fill="white"/><circle cx="6" cy="90" r="1.5" fill="white"/></svg>') repeat-y;
        }
        
        .sidebar-text {
            writing-mode: vertical-rl;
            text-orientation: mixed;
            color: white;
            font-weight: bold;
            font-size: 12px;
            letter-spacing: 1.5px;
        }
        
        .ticket-header {
            text-align: left;
            margin-bottom: 10px;
        }
        
        .ticket-header h1 {
            color: #7f8c8d;
            font-size: 10px;
            font-weight: bold;
            margin-bottom: 2px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .ticket-header p {
            color: #95a5a6;
            font-size: 9px;
        }
        
        .event-title {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 8px;
            line-height: 1.1;
        }
        
        .ticket-type {
            font-size: 11px;
            color: white;
            font-weight: bold;
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            padding: 4px 10px;
            border-radius: 15px;
            display: inline-block;
            margin-bottom: 10px;
            box-shadow: 0 2px 4px rgba(52, 152, 219, 0.3);
            width: auto;
        }
        
        .student-info {
            background-color: #ffffff;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 10px;
            border: 1px solid #e9ecef;
        }
        
        .student-info h3 {
            color: #34495e;
            font-size: 10px;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }
        
        .student-name {
            font-size: 14px;
            font-weight: bold;
            color: #2c3e50;
        }
        
        .order-id {
            font-family: 'Courier New', monospace;
            font-size: 10px;
            color: #7f8c8d;
            margin-top: 4px;
        }
        
        .event-details {
            margin-bottom: 10px;
            flex-grow: 1;
        }
        
        .ticket-details {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 6px 15px;
            margin: 10px 0;
        }
        
        .detail-row {
            display: flex;
            flex-direction: column;
            font-size: 12px;
        }
        
        .detail-label {
            font-weight: 600;
            color: #34495e;
            font-size: 10px;
            text-transform: uppercase;
            margin-bottom: 1px;
        }
        
        .detail-value {
            color: #2c3e50;
            font-weight: 500;
        }
        
        .qr-section {
            text-align: center;
            background-color: #ffffff;
            border-radius: 8px;
            padding: 12px;
            border: 1px solid #e9ecef;
        }
        
        .qr-title {
            margin-bottom: 8px;
            color: #7f8c8d;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .qr-code {
            background: transparent;
            padding: 0;
            border-radius: 6px;
            display: inline-block;
            box-shadow: none;
            border: none;
        }
        
        .qr-code img {
            width: 140px;
            height: 140px;
            display: block;
        }
        
        .qr-ticket-id {
            margin-top: 8px;
            font-size: 10px;
            color: #7f8c8d;
        }
        
        .ticket-footer {
            margin-top: auto;
            padding-top: 10px;
            border-top: 1px dashed #bdc3c7;
            text-align: left;
        }
        
        .ticket-footer p {
            color: #7f8c8d;
            font-size: 9px;
            margin-bottom: 2px;
        }
        
        .price-highlight {
            padding: 8px 12px;
            background: #2ecc71;
            color: white;
            border-radius: 6px;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            text-align: center;
        }
        
        .online-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            background: #2ecc71;
            color: white;
            padding: 3px 6px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
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
                border: 2px solid #34495e !important;
                background: white !important;
                border-radius: 12px !important;
            }
            
            .ticket-main {
                background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%) !important;
                padding: 20px !important;
            }
            
            .ticket-content {
                gap: 25px !important;
                height: 100% !important;
            }
            
            .ticket-right {
                min-width: 200px !important;
            }
            
            .ticket-sidebar {
                background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%) !important;
                width: 70px !important;
            }
            
            .sidebar-text {
                color: white !important;
                font-size: 12px !important;
                margin-left: 15px !important;
            }
            
            .ticket-type {
                background: linear-gradient(135deg, #3498db 0%, #2980b9 100%) !important;
                color: white !important;
            }
            
            .student-info {
                background-color: #ffffff !important;
                border: 1px solid #e9ecef !important;
            }
            
            .qr-section {
                background-color: #ffffff !important;
                border: 1px solid #e9ecef !important;
            }
            
            .qr-code {
                border: none !important;
                background: transparent !important;
            }
            
            .online-badge {
                background: #2ecc71 !important;
                color: white !important;
            }
            
            .price-highlight {
                background: #2ecc71 !important;
                color: white !important;
            }
            
            .print-button {
                display: none;
            }
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

        .sidebar-order-id {
            writing-mode: vertical-rl;
            text-orientation: mixed;
            font-size: 10px;
            color: white;
            font-weight: bold;
            margin-left: 15px;
        }
    </style>
</head>
<body>
    <button class="print-button" onclick="window.print()">🖨️ Print Ticket</button>
    
    <div class="ticket-container">
        <!-- Main Ticket Content -->
        <div class="ticket-main">
            <div class="online-badge">ONLINE</div>
            
            <div class="ticket-content">
                <!-- Left Side Content -->
                <div class="ticket-left">
                    <!-- Header -->
                    <div class="ticket-header">
                        <h1>Concert Event Ticket</h1>
                        <p>Please present this ticket at entry</p>
                    </div>
                    
                    <!-- Event Title -->
                    <div class="event-title">
                        {{ $purchase->ticket->concert->title }}
                    </div>
                    
                    <!-- Ticket Type -->
                    <div class="ticket-type">{{ $purchase->ticket->ticket_type }}</div>
                    
                    <!-- Student Information -->
                    <div class="student-info">
                        <h3>Ticket Holder</h3>
                        <div class="student-name">{{ $purchase->student->name }}</div>
                        <div class="order-id">
                            {{ $purchase->formatted_order_id }}
                        </div>
                    </div>
                    
                    <!-- Event Details -->
                    <div class="event-details">
                        <div class="ticket-details">
                            <div class="detail-row">
                                <span class="detail-label">Date</span>
                                <span class="detail-value">{{ $purchase->ticket->concert->date->format('d M Y') }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Time</span>
                                <span class="detail-value">{{ $purchase->ticket->concert->start_time->format('g:i A') }} - {{ $purchase->ticket->concert->end_time ? $purchase->ticket->concert->end_time->format('g:i A') : 'TBA' }}</span>
                            </div>
                            @if($purchase->ticket->concert->venue)
                            <div class="detail-row">
                                <span class="detail-label">Venue</span>
                                <span class="detail-value">{{ $purchase->ticket->concert->venue }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Right Side - QR Code -->
                <div class="ticket-right">
                    <div class="price-highlight">
                        RM{{ number_format($purchase->ticket->price, 2) }}
                    </div>
                    
                    <div class="qr-section">
                        <h4 class="qr-title">Entry QR Code</h4>
                        <div class="qr-code">
                            @php
                                $token = hash('sha256', $purchase->id . $purchase->qr_code . config('app.key'));
                                $qrUrl = route('qr.ticket', ['id' => $purchase->id, 'token' => $token]);
                            @endphp
                            <img src="{{ $qrUrl }}" alt="Entry QR Code" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="ticket-sidebar">
            <p class="sidebar-order-id">{{ $purchase->formatted_order_id }}</p>
            <div class="sidebar-text">CONCERT TICKET</div>
        </div>
    </div>
</body>
</html> 