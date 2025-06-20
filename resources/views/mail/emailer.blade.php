<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Concert Ticket{{ $isMultiple ? 's' : '' }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            background-color: #f8fafc;
            padding: 20px;
        }
        .email-container {
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .header p {
            margin: 8px 0 0 0;
            opacity: 0.9;
            font-size: 16px;
        }
        .content {
            padding: 30px;
        }
        .summary-section {
            background-color: #ebf8ff;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid #3182ce;
        }
        .ticket-card {
            border: 2px dashed #e2e8f0;
            border-radius: 8px;
            padding: 25px;
            margin: 20px 0;
            background-color: #f8fafc;
        }
        .ticket-title {
            font-size: 24px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 15px;
            text-align: center;
        }
        .ticket-number {
            background-color: #667eea;
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 10px;
        }
        .ticket-details {
            display: table;
            width: 100%;
        }
        .detail-row {
            display: table-row;
        }
        .detail-label {
            display: table-cell;
            font-weight: 600;
            color: #4a5568;
            padding: 8px 0;
            width: 30%;
        }
        .detail-value {
            display: table-cell;
            color: #2d3748;
            padding: 8px 0;
        }
        .price-highlight {
            background-color: #48bb78;
            color: white;
            padding: 12px 20px;
            border-radius: 6px;
            text-align: center;
            margin: 20px 0;
            font-size: 18px;
            font-weight: 600;
        }
        .total-amount {
            background-color: #2c5282;
            color: white;
            padding: 15px 20px;
            border-radius: 6px;
            text-align: center;
            margin: 20px 0;
            font-size: 20px;
            font-weight: 600;
        }
        .qr-section {
            text-align: center;
            margin: 25px 0;
            padding: 20px;
            background-color: #edf2f7;
            border-radius: 8px;
        }
        .qr-code {
            background-color: white;
            padding: 15px;
            border-radius: 8px;
            display: inline-block;
            margin: 10px 0;
        }
        .important-notes {
            background-color: #fed7d7;
            border-left: 4px solid #fc8181;
            padding: 15px;
            margin: 20px 0;
            border-radius: 0 4px 4px 0;
        }
        .important-notes h3 {
            color: #c53030;
            margin: 0 0 10px 0;
            font-size: 16px;
        }
        .important-notes ul {
            margin: 0;
            padding-left: 20px;
        }
        .important-notes li {
            color: #742a2a;
            margin: 5px 0;
        }
        .footer {
            background-color: #2d3748;
            color: #e2e8f0;
            padding: 20px 30px;
            text-align: center;
        }
        .footer p {
            margin: 5px 0;
        }
        @media (max-width: 600px) {
            .detail-label, .detail-value {
                display: block;
                width: 100%;
            }
            .detail-label {
                font-weight: 600;
                margin-top: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>🎵 Concert Ticket{{ $isMultiple ? 's' : '' }} Confirmed!</h1>
            <p>Your ticket{{ $isMultiple ? 's have' : ' has' }} been successfully assigned</p>
        </div>

        <!-- Main Content -->
        <div class="content">
            @php
                $firstPurchase = $ticketPurchases->first();
                $totalAmount = $ticketPurchases->sum(function($purchase) { 
                    return $purchase->ticket->price; 
                });
            @endphp
            
            <h2>Hello {{ $firstPurchase->student->name }}!</h2>
            <p>Great news! Your concert ticket{{ $isMultiple ? 's have' : ' has' }} been successfully assigned. Here are your ticket details:</p>

            @if($isMultiple)
            <!-- Purchase Summary for Multiple Tickets -->
            <div class="summary-section">
                <h3 style="color: #2c5282; margin: 0 0 10px 0;">📋 Purchase Summary</h3>
                <div class="ticket-details">
                    <div class="detail-row">
                        <div class="detail-label">Concert:</div>
                        <div class="detail-value">{{ $firstPurchase->ticket->concert->title }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Number of Tickets:</div>
                        <div class="detail-value">{{ $ticketPurchases->count() }} tickets</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Purchase Date:</div>
                        <div class="detail-value">{{ $firstPurchase->purchase_date->format('F j, Y \a\t g:i A') }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Assigned by:</div>
                        <div class="detail-value">{{ $firstPurchase->teacher->name }}</div>
                    </div>
                </div>
                
                <div class="total-amount">
                    Total Amount Paid: RM{{ number_format($totalAmount, 2) }}
                </div>
            </div>
            @endif

            <!-- Individual Ticket Cards -->
            @foreach($ticketPurchases as $index => $ticketPurchase)
            <div class="ticket-card">
                @if($isMultiple)
                    <div class="ticket-number">Ticket #{{ $index + 1 }}</div>
                @endif
                <div class="ticket-title">{{ $ticketPurchase->ticket->concert->title }}</div>
                
                <div class="ticket-details">
                    <div class="detail-row">
                        <div class="detail-label">Student Name:</div>
                        <div class="detail-value">{{ $ticketPurchase->student->name }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Ticket Type:</div>
                        <div class="detail-value">{{ $ticketPurchase->ticket->ticket_type }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Concert Date:</div>
                        <div class="detail-value">{{ $ticketPurchase->ticket->concert->date->format('l, F j, Y') }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Start Time:</div>
                        <div class="detail-value">{{ $ticketPurchase->ticket->concert->start_time->format('g:i A') }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Venue:</div>
                        <div class="detail-value">{{ $ticketPurchase->ticket->concert->venue }}</div>
                    </div>
                    @if(!$isMultiple)
                    <div class="detail-row">
                        <div class="detail-label">Purchase Date:</div>
                        <div class="detail-value">{{ $ticketPurchase->purchase_date->format('F j, Y \a\t g:i A') }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Assigned by:</div>
                        <div class="detail-value">{{ $ticketPurchase->teacher->name }}</div>
                    </div>
                    @endif
                </div>

                @if(!$isMultiple)
                <div class="price-highlight">
                    Amount Paid: RM{{ number_format($ticketPurchase->ticket->price, 2) }}
                </div>
                @else
                <div style="text-align: center; margin: 15px 0; font-weight: 600; color: #4a5568;">
                    Ticket Price: RM{{ number_format($ticketPurchase->ticket->price, 2) }}
                </div>
                @endif

                <!-- QR Code Section for each ticket -->
                <div class="qr-section">
                    <h4>🎫 Digital Ticket {{ $isMultiple ? '#' . ($index + 1) : '' }}</h4>
                    <p>Show this QR code at the venue for entry:</p>
                    <div class="qr-code">
                        <!-- QR Code placeholder - will be generated -->
                        <div style="width: 150px; height: 150px; background-color: #f0f0f0; border: 1px solid #ddd; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                            <small style="color: #666;">QR Code: {{ substr($ticketPurchase->qr_code, 0, 20) }}...</small>
                        </div>
                    </div>
                    <p><small>Ticket ID: {{ $ticketPurchase->id }}</small></p>
                </div>
            </div>
            @endforeach

            <!-- Important Notes -->
            <div class="important-notes">
                <h3>📋 Important Information</h3>
                <ul>
                    <li>Please arrive at least 15 minutes before the show starts</li>
                    <li>Bring a printed copy of this email or save it on your phone</li>
                    <li>{{ $isMultiple ? 'These tickets are' : 'This ticket is' }} non-transferable and non-refundable</li>
                    <li>Present your student ID along with {{ $isMultiple ? 'these tickets' : 'this ticket' }} at the venue</li>
                    <li>{{ $isMultiple ? 'Each ticket must be scanned separately for entry' : 'Scan your QR code at the entrance' }}</li>
                    <li>Contact your teacher if you have any questions about the event</li>
                </ul>
            </div>

            @if($firstPurchase->ticket->concert->description)
            <div style="margin: 20px 0; padding: 15px; background-color: #ebf8ff; border-radius: 6px;">
                <h3 style="color: #2c5282; margin: 0 0 10px 0;">About the Concert</h3>
                <p style="margin: 0; color: #2d3748;">{{ $firstPurchase->ticket->concert->description }}</p>
            </div>
            @endif

            <p style="margin-top: 30px;">
                We're excited to see you at the concert! If you have any questions, please contact your teacher or the school administration.
            </p>

            <p>
                Best regards,<br>
                <strong>KKHS Concert Team</strong>
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Kota Kinabalu High School</strong></p>
            <p>Concert Ticket Booking System</p>
            <p><small>This is an automated message. Please do not reply to this email.</small></p>
        </div>
    </div>
</body>
</html>