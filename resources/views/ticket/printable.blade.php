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
        }
        
        .ticket-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 600px;
            display: flex;
            position: relative;
        }
        
        .ticket-main {
            flex: 1;
            padding: 30px;
            position: relative;
        }
        
        .ticket-sidebar {
            width: 120px;
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
            width: 20px;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 100"><circle cx="10" cy="10" r="3" fill="white"/><circle cx="10" cy="30" r="3" fill="white"/><circle cx="10" cy="50" r="3" fill="white"/><circle cx="10" cy="70" r="3" fill="white"/><circle cx="10" cy="90" r="3" fill="white"/></svg>') repeat-y;
        }
        
        .sidebar-text {
            writing-mode: vertical-rl;
            text-orientation: mixed;
            color: white;
            font-weight: bold;
            font-size: 16px;
            letter-spacing: 2px;
            margin-left: 25px;
        }
        
        .ticket-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .ticket-header h1 {
            color: #666;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .ticket-header p {
            color: #888;
            font-size: 12px;
        }
        
        .event-title {
            font-size: 28px;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
        }
        
        .event-details {
            margin-bottom: 25px;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
            padding: 8px 0;
        }
        
        .detail-label {
            font-size: 16px;
            color: #333;
            font-weight: 500;
        }
        
        .detail-value {
            font-size: 16px;
            color: #333;
            font-weight: 600;
        }
        
        .ticket-badges {
            display: flex;
            gap: 10px;
            margin-bottom: 25px;
        }
        
        .badge {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .badge-public {
            background-color: #0ea5e9;
            color: white;
        }
        
        .badge-valid {
            background-color: #10b981;
            color: white;
        }
        
        .qr-section {
            text-align: center;
            padding: 20px;
            background-color: #f8fafc;
            border-radius: 8px;
            margin-top: 20px;
        }
        
        .qr-code {
            background: white;
            padding: 15px;
            border-radius: 8px;
            display: inline-block;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .qr-code img {
            width: 120px;
            height: 120px;
            display: block;
        }
        
        .ticket-footer {
            margin-top: 25px;
            padding-top: 20px;
            border-top: 2px dashed #e5e7eb;
            text-align: center;
        }
        
        .ticket-footer p {
            color: #666;
            font-size: 12px;
            margin-bottom: 5px;
        }
        
        .student-info {
            background-color: #f1f5f9;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .student-info h3 {
            color: #475569;
            font-size: 14px;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .student-name {
            font-size: 18px;
            font-weight: bold;
            color: #1e293b;
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .ticket-container {
                box-shadow: none;
                max-width: none;
                width: 100%;
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
        }
        
        .print-button:hover {
            background: #0284c7;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(14, 165, 233, 0.4);
        }
        
        @media print {
            .print-button {
                display: none;
            }
        }
    </style>
</head>
<body>
    <button class="print-button" onclick="window.print()">🖨️ Print Ticket</button>
    
    <div class="ticket-container">
        <!-- Main Ticket Content -->
        <div class="ticket-main">
            <!-- Header -->
            <div class="ticket-header">
                <h1>Concert Event Ticket</h1>
                <p>Please present this ticket at entry</p>
            </div>
            
            <!-- Event Title -->
            <div class="event-title">
                {{ $purchase->ticket->concert->title }}
            </div>
            
            <!-- Student Information -->
            <div class="student-info">
                <h3>Ticket Holder</h3>
                <div class="student-name">{{ $purchase->student->name }}</div>
            </div>
            
            <!-- Event Details -->
            <div class="event-details">
                <div class="detail-row">
                    <span class="detail-label">{{ $purchase->ticket->concert->date->format('d M Y') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">{{ $purchase->ticket->concert->start_time->format('g:i A') }} - {{ $purchase->ticket->concert->end_time ? $purchase->ticket->concert->end_time->format('g:i A') : 'TBA' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">{{ $purchase->ticket->concert->venue }}</span>
                </div>
            </div>
            
            <!-- Badges -->
            <div class="ticket-badges">
                <span class="badge badge-public">{{ $purchase->ticket->ticket_type }}</span>
                <span class="badge badge-valid">{{ ucfirst($purchase->status) }}</span>
            </div>
            
            <!-- QR Code Section -->
            <div class="qr-section">
                <h4 style="margin-bottom: 15px; color: #374151;">Entry QR Code</h4>
                <div class="qr-code">
                    @php
                        $token = hash('sha256', $purchase->id . $purchase->qr_code . config('app.key'));
                        $qrUrl = route('qr.ticket', ['id' => $purchase->id, 'token' => $token]);
                    @endphp
                    <img src="{{ $qrUrl }}" alt="Entry QR Code" />
                </div>
                <p style="margin-top: 10px; font-size: 12px; color: #6b7280;">
                    <strong>Ticket ID:</strong> {{ $purchase->id }}
                </p>
            </div>
            
            <!-- Footer -->
            <div class="ticket-footer">
                <p><strong>Kota Kinabalu High School</strong></p>
                <p>Concert Ticket Booking System</p>
                <p>Assigned by: {{ $purchase->teacher->name }} on {{ $purchase->purchase_date->format('M d, Y') }}</p>
                @if($purchase->ticket->concert->description)
                <p style="margin-top: 10px; font-style: italic;">{{ Str::limit($purchase->ticket->concert->description, 100) }}</p>
                @endif
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="ticket-sidebar">
            <div class="sidebar-text">CONCERT TICKET</div>
        </div>
    </div>
</body>
</html> 