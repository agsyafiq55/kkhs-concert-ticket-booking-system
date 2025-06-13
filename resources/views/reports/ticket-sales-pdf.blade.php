<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Sales Report</title>
    <style>
        @media print {
            .no-print { display: none !important; }
            .page-break { page-break-before: always; }
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #2563eb;
        }
        
        .header p {
            margin: 5px 0;
            color: #666;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            background: #f9f9f9;
        }
        
        .stat-card h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #666;
            text-transform: uppercase;
        }
        
        .stat-card .value {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }
        
        .revenue { color: #059669; }
        .sales { color: #2563eb; }
        .valid { color: #10b981; }
        .used { color: #8b5cf6; }
        .cancelled { color: #ef4444; }
        
        .section {
            margin-bottom: 30px;
        }
        
        .section h2 {
            font-size: 18px;
            margin-bottom: 15px;
            color: #1f2937;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 8px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        th {
            background-color: #f3f4f6;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
        }
        
        td {
            font-size: 11px;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .filters {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 30px;
        }
        
        .filters h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
        }
        
        .filter-item {
            display: inline-block;
            margin-right: 20px;
            margin-bottom: 5px;
        }
        
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #2563eb;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }
        
        .print-button:hover {
            background: #1d4ed8;
        }
        
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .badge-success { background: #d1fae5; color: #065f46; }
        .badge-used { background: #e0e7ff; color: #3730a3; }
        .badge-danger { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>
    <button class="print-button no-print" onclick="window.print()">🖨️ Print Report</button>
    
    <div class="header">
        <h1>🎭 KKHS Concert Ticket Sales Report</h1>
        <p><strong>Generated on:</strong> {{ $generatedAt->format('F j, Y \a\t g:i A') }}</p>
        @if(count($filters) > 0)
            <p><strong>Filtered Report</strong></p>
        @endif
    </div>
    
    @if(count($filters) > 0)
        <div class="filters">
            <h3>📋 Applied Filters:</h3>
            @foreach($filters as $key => $value)
                <div class="filter-item">
                    <strong>{{ $key }}:</strong> {{ $value }}
                </div>
            @endforeach
        </div>
    @endif
    
    <!-- Summary Statistics -->
    <div class="section">
        <h2>📊 Overall Statistics</h2>
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Revenue</h3>
                <p class="value revenue">RM{{ number_format($totalRevenue, 2) }}</p>
            </div>
            <div class="stat-card">
                <h3>Total Sales</h3>
                <p class="value sales">{{ number_format($totalSales) }}</p>
            </div>
            <div class="stat-card">
                <h3>Valid Tickets</h3>
                <p class="value valid">{{ number_format($validTickets) }}</p>
            </div>
            <div class="stat-card">
                <h3>Used Tickets</h3>
                <p class="value used">{{ number_format($usedTickets) }}</p>
            </div>
            <div class="stat-card">
                <h3>Cancelled Tickets</h3>
                <p class="value cancelled">{{ number_format($cancelledTickets) }}</p>
            </div>
        </div>
    </div>
    
    <!-- Concert Revenue Breakdown -->
    <div class="section page-break">
        <h2>🎵 Revenue by Concert</h2>
        <table>
            <thead>
                <tr>
                    <th>Concert</th>
                    <th>Date</th>
                    <th>Venue</th>
                    <th class="text-center">Total Sales</th>
                    <th class="text-right">Revenue</th>
                    <th class="text-center">Valid</th>
                    <th class="text-center">Used</th>
                    <th class="text-center">Cancelled</th>
                </tr>
            </thead>
            <tbody>
                @forelse($concertRevenue as $concert)
                    <tr>
                        <td><strong>{{ $concert->title }}</strong></td>
                        <td>{{ \Carbon\Carbon::parse($concert->date)->format('M d, Y') }}</td>
                        <td>{{ $concert->venue }}</td>
                        <td class="text-center">{{ number_format($concert->total_sales) }}</td>
                        <td class="text-right"><strong>RM{{ number_format($concert->revenue, 2) }}</strong></td>
                        <td class="text-center">{{ $concert->valid_count }}</td>
                        <td class="text-center">{{ $concert->used_count }}</td>
                        <td class="text-center">{{ $concert->cancelled_count }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No concert data available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Teacher Sales Performance -->
    <div class="section page-break">
        <h2>👨‍🏫 Sales by Teacher</h2>
        <table>
            <thead>
                <tr>
                    <th>Teacher</th>
                    <th>Email</th>
                    <th class="text-center">Total Sales</th>
                    <th class="text-right">Revenue</th>
                    <th class="text-center">Valid</th>
                    <th class="text-center">Used</th>
                    <th class="text-center">Cancelled</th>
                </tr>
            </thead>
            <tbody>
                @forelse($teacherSales as $teacher)
                    <tr>
                        <td><strong>{{ $teacher->name }}</strong></td>
                        <td>{{ $teacher->email }}</td>
                        <td class="text-center">{{ number_format($teacher->total_sales) }}</td>
                        <td class="text-right"><strong>RM{{ number_format($teacher->revenue, 2) }}</strong></td>
                        <td class="text-center">{{ $teacher->valid_count }}</td>
                        <td class="text-center">{{ $teacher->used_count }}</td>
                        <td class="text-center">{{ $teacher->cancelled_count }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No teacher data available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Individual Sales Details -->
    @if($sales->count() <= 50)
        <div class="section page-break">
            <h2>🎫 Individual Sales Details @if($sales->count() > 0)({{ $sales->count() }} records)@endif</h2>
            @if($sales->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Student</th>
                            <th>Concert</th>
                            <th>Ticket Type</th>
                            <th class="text-right">Price</th>
                            <th>Teacher</th>
                            <th>Purchase Date</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sales as $sale)
                            <tr>
                                <td>{{ $sale->id }}</td>
                                <td>
                                    <strong>{{ $sale->student_name }}</strong><br>
                                    <small>{{ $sale->student_email }}</small>
                                </td>
                                <td>
                                    <strong>{{ $sale->concert_title }}</strong><br>
                                    <small>{{ \Carbon\Carbon::parse($sale->concert_date)->format('M d, Y') }}</small>
                                </td>
                                <td>{{ $sale->ticket_type }}</td>
                                                                 <td class="text-right"><strong>RM{{ number_format($sale->price, 2) }}</strong></td>
                                <td>{{ $sale->teacher_name }}</td>
                                <td>{{ \Carbon\Carbon::parse($sale->purchase_date)->format('M d, Y g:i A') }}</td>
                                <td class="text-center">
                                    @if($sale->status === 'valid')
                                        <span class="badge badge-success">Valid</span>
                                    @elseif($sale->status === 'used')
                                        <span class="badge badge-used">Used</span>
                                    @else
                                        <span class="badge badge-danger">Cancelled</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-center">No individual sales data available</p>
            @endif
        </div>
    @else
        <div class="section">
            <h2>🎫 Individual Sales Details</h2>
            <div style="text-align: center; padding: 20px; background: #f8fafc; border-radius: 8px;">
                <p><strong>{{ number_format($sales->count()) }} sales records found</strong></p>
                <p>Too many records to display in PDF. Please use CSV export for detailed individual sales data.</p>
            </div>
        </div>
    @endif
    
    <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #ddd; text-align: center; color: #666; font-size: 10px;">
        <p>This report was generated by the KKHS Concert Ticket Booking System</p>
        <p>{{ $generatedAt->format('F j, Y \a\t g:i:s A') }}</p>
    </div>
</body>
</html> 