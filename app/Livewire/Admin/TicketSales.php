<?php

namespace App\Livewire\Admin;

use App\Models\Concert;
use App\Models\Ticket;
use App\Models\TicketPurchase;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;

class TicketSales extends Component
{
    use WithPagination;
    
    public $concertFilter = '';
    public $teacherFilter = '';
    public $statusFilter = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $search = '';
    
    // Stats properties
    public $totalRevenue = 0;
    public $totalSales = 0;
    public $validTickets = 0;
    public $usedTickets = 0;
    public $cancelledTickets = 0;
    
    protected $queryString = [
        'concertFilter' => ['except' => ''],
        'teacherFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'search' => ['except' => ''],
    ];
    
    public function mount()
    {
        // Check if user has permission to view ticket sales
        if (!Gate::allows('view ticket sales')) {
            abort(403, 'You do not have permission to view ticket sales.');
        }
        
        $this->calculateStats();
    }
    
    public function updatingConcertFilter()
    {
        $this->resetPage();
        $this->calculateStats();
    }
    
    public function updatingTeacherFilter()
    {
        $this->resetPage();
        $this->calculateStats();
    }
    
    public function updatingStatusFilter()
    {
        $this->resetPage();
        $this->calculateStats();
    }
    
    public function updatingDateFrom()
    {
        $this->resetPage();
        $this->calculateStats();
    }
    
    public function updatingDateTo()
    {
        $this->resetPage();
        $this->calculateStats();
    }
    
    public function updatingSearch()
    {
        $this->resetPage();
        $this->calculateStats();
    }
    
    public function resetFilters()
    {
        $this->concertFilter = '';
        $this->teacherFilter = '';
        $this->statusFilter = '';
        $this->dateFrom = '';
        $this->dateTo = '';
        $this->search = '';
        $this->resetPage();
        $this->calculateStats();
    }
    
    public function exportCSV()
    {
        $sales = $this->getBaseQuery()
            ->select([
                'ticket_purchases.id',
                'ticket_purchases.order_id',
                DB::raw('COALESCE(students.name, "Walk-in Customer") as student_name'),
                DB::raw('COALESCE(students.email, "N/A") as student_email'),
                'concerts.title as concert_title',
                'concerts.date as concert_date',
                'concerts.venue',
                'tickets.ticket_type',
                'tickets.ticket_category',
                'tickets.price',
                'teachers.name as teacher_name',
                'ticket_purchases.purchase_date',
                'ticket_purchases.status'
            ])
            ->where(function($query) {
                $query->whereIn('tickets.ticket_category', ['regular', 'vip'])  // Regular and VIP tickets (always sold)
                      ->orWhere(function($q) {
                          $q->where('tickets.ticket_category', 'walk-in')
                            ->where('ticket_purchases.is_sold', true);  // Or walk-in tickets that are sold
                      });
            })
            ->orderBy('ticket_purchases.purchase_date', 'desc')
            ->get();
        
        $filename = 'ticket-sales-report-' . now()->format('Y-m-d-H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($sales) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, [
                'ID',
                'Order ID',
                'Student Name',
                'Student Email',
                'Concert',
                'Concert Date',
                'Venue',
                'Ticket Type',
                'Price',
                'Teacher',
                'Purchase Date',
                'Status'
            ]);
            
            // Add data rows
            foreach ($sales as $sale) {
                fputcsv($file, [
                    $sale->id,
                    $sale->order_id,
                    $sale->student_name,
                    $sale->student_email,
                    $sale->concert_title,
                    $sale->concert_date,
                    $sale->venue,
                    $sale->ticket_type,
                    'RM' . number_format($sale->price, 2),
                    $sale->teacher_name,
                    $sale->purchase_date,
                    ucfirst($sale->status)
                ]);
            }
            
            fclose($file);
        };
        
        return Response::stream($callback, 200, $headers);
    }
    
    public function exportSummaryCSV()
    {
        // Get concert revenue breakdown
        $concertRevenue = $this->getBaseQuery()
            ->select([
                'concerts.title',
                'concerts.date',
                'concerts.venue',
                DB::raw('COUNT(CASE WHEN tickets.ticket_category IN ("regular", "vip") OR (tickets.ticket_category = "walk-in" AND ticket_purchases.is_sold = 1) THEN 1 END) as total_sales'),
                DB::raw('SUM(CASE WHEN ticket_purchases.status IN ("valid", "used") AND (tickets.ticket_category IN ("regular", "vip") OR (tickets.ticket_category = "walk-in" AND ticket_purchases.is_sold = 1)) THEN tickets.price ELSE 0 END) as revenue'),
                DB::raw('SUM(CASE WHEN ticket_purchases.status = "valid" THEN 1 ELSE 0 END) as valid_count'),
                DB::raw('SUM(CASE WHEN ticket_purchases.status = "used" THEN 1 ELSE 0 END) as used_count'),
                DB::raw('SUM(CASE WHEN ticket_purchases.status = "cancelled" THEN 1 ELSE 0 END) as cancelled_count')
            ])
            ->groupBy('concerts.id', 'concerts.title', 'concerts.date', 'concerts.venue')
            ->orderBy('concerts.date', 'desc')
            ->get();
        
        // Get teacher sales breakdown
        $teacherSales = $this->getBaseQuery()
            ->select([
                'teachers.name',
                'teachers.email',
                DB::raw('COUNT(CASE WHEN tickets.ticket_category IN ("regular", "vip") OR (tickets.ticket_category = "walk-in" AND ticket_purchases.is_sold = 1) THEN 1 END) as total_sales'),
                DB::raw('SUM(CASE WHEN ticket_purchases.status IN ("valid", "used") AND (tickets.ticket_category IN ("regular", "vip") OR (tickets.ticket_category = "walk-in" AND ticket_purchases.is_sold = 1)) THEN tickets.price ELSE 0 END) as revenue'),
                DB::raw('SUM(CASE WHEN ticket_purchases.status = "valid" THEN 1 ELSE 0 END) as valid_count'),
                DB::raw('SUM(CASE WHEN ticket_purchases.status = "used" THEN 1 ELSE 0 END) as used_count'),
                DB::raw('SUM(CASE WHEN ticket_purchases.status = "cancelled" THEN 1 ELSE 0 END) as cancelled_count')
            ])
            ->groupBy('teachers.id', 'teachers.name', 'teachers.email')
            ->orderBy(DB::raw('SUM(CASE WHEN ticket_purchases.status IN ("valid", "used") AND (tickets.ticket_category IN ("regular", "vip") OR (tickets.ticket_category = "walk-in" AND ticket_purchases.is_sold = 1)) THEN tickets.price ELSE 0 END)'), 'desc')
            ->get();
        
        $filename = 'ticket-sales-summary-' . now()->format('Y-m-d-H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($concertRevenue, $teacherSales) {
            $file = fopen('php://output', 'w');
            
            // Summary Statistics
            fputcsv($file, ['TICKET SALES SUMMARY REPORT']);
            fputcsv($file, ['Generated on: ' . now()->format('Y-m-d H:i:s')]);
            fputcsv($file, ['']);
            
            fputcsv($file, ['OVERALL STATISTICS']);
            fputcsv($file, ['Total Revenue', 'RM' . number_format($this->totalRevenue, 2)]);
            fputcsv($file, ['Total Sales', number_format($this->totalSales)]);
            fputcsv($file, ['Valid Tickets', number_format($this->validTickets)]);
            fputcsv($file, ['Used Tickets', number_format($this->usedTickets)]);
            fputcsv($file, ['Cancelled Tickets', number_format($this->cancelledTickets)]);
            fputcsv($file, ['']);
            
            // Concert Revenue Breakdown
            fputcsv($file, ['REVENUE BY CONCERT']);
            fputcsv($file, [
                'Concert',
                'Date',
                'Venue',
                'Total Sales',
                'Revenue',
                'Valid',
                'Used',
                'Cancelled'
            ]);
            
            foreach ($concertRevenue as $concert) {
                fputcsv($file, [
                    $concert->title,
                    $concert->date,
                    $concert->venue,
                    $concert->total_sales,
                    'RM' . number_format($concert->revenue, 2),
                    $concert->valid_count,
                    $concert->used_count,
                    $concert->cancelled_count
                ]);
            }
            
            fputcsv($file, ['']);
            
            // Teacher Sales Performance
            fputcsv($file, ['SALES BY TEACHER']);
            fputcsv($file, [
                'Teacher',
                'Email',
                'Total Sales',
                'Revenue',
                'Valid',
                'Used',
                'Cancelled'
            ]);
            
            foreach ($teacherSales as $teacher) {
                fputcsv($file, [
                    $teacher->name,
                    $teacher->email,
                    $teacher->total_sales,
                    'RM' . number_format($teacher->revenue, 2),
                    $teacher->valid_count,
                    $teacher->used_count,
                    $teacher->cancelled_count
                ]);
            }
            
            fclose($file);
        };
        
        return Response::stream($callback, 200, $headers);
    }
    
    public function exportPDF()
    {
        // For now, we'll create an HTML report that can be printed as PDF
        // In a production environment, you'd use a proper PDF library
        
        $sales = $this->getBaseQuery()
            ->select([
                'ticket_purchases.id',
                'ticket_purchases.order_id',
                DB::raw('COALESCE(students.name, "Walk-in Customer") as student_name'),
                DB::raw('COALESCE(students.email, "N/A") as student_email'),
                'concerts.title as concert_title',
                'concerts.date as concert_date',
                'concerts.venue',
                'tickets.ticket_type',
                'tickets.ticket_category',
                'tickets.price',
                'teachers.name as teacher_name',
                'ticket_purchases.purchase_date',
                'ticket_purchases.status'
            ])
            ->where(function($query) {
                $query->whereIn('tickets.ticket_category', ['regular', 'vip'])  // Regular and VIP tickets (always sold)
                      ->orWhere(function($q) {
                          $q->where('tickets.ticket_category', 'walk-in')
                            ->where('ticket_purchases.is_sold', true);  // Or walk-in tickets that are sold
                      });
            })
            ->orderBy('ticket_purchases.purchase_date', 'desc')
            ->get();
        
        // Get concert revenue breakdown
        $concertRevenue = $this->getBaseQuery()
            ->select([
                'concerts.title',
                'concerts.date',
                'concerts.venue',
                DB::raw('COUNT(CASE WHEN tickets.ticket_category IN ("regular", "vip") OR (tickets.ticket_category = "walk-in" AND ticket_purchases.is_sold = 1) THEN 1 END) as total_sales'),
                DB::raw('SUM(CASE WHEN ticket_purchases.status IN ("valid", "used") AND (tickets.ticket_category IN ("regular", "vip") OR (tickets.ticket_category = "walk-in" AND ticket_purchases.is_sold = 1)) THEN tickets.price ELSE 0 END) as revenue'),
                DB::raw('SUM(CASE WHEN ticket_purchases.status = "valid" THEN 1 ELSE 0 END) as valid_count'),
                DB::raw('SUM(CASE WHEN ticket_purchases.status = "used" THEN 1 ELSE 0 END) as used_count'),
                DB::raw('SUM(CASE WHEN ticket_purchases.status = "cancelled" THEN 1 ELSE 0 END) as cancelled_count')
            ])
            ->groupBy('concerts.id', 'concerts.title', 'concerts.date', 'concerts.venue')
            ->orderBy('concerts.date', 'desc')
            ->get();
        
        // Get teacher sales breakdown
        $teacherSales = $this->getBaseQuery()
            ->select([
                'teachers.name',
                'teachers.email',
                DB::raw('COUNT(CASE WHEN tickets.ticket_category IN ("regular", "vip") OR (tickets.ticket_category = "walk-in" AND ticket_purchases.is_sold = 1) THEN 1 END) as total_sales'),
                DB::raw('SUM(CASE WHEN ticket_purchases.status IN ("valid", "used") AND (tickets.ticket_category IN ("regular", "vip") OR (tickets.ticket_category = "walk-in" AND ticket_purchases.is_sold = 1)) THEN tickets.price ELSE 0 END) as revenue'),
                DB::raw('SUM(CASE WHEN ticket_purchases.status = "valid" THEN 1 ELSE 0 END) as valid_count'),
                DB::raw('SUM(CASE WHEN ticket_purchases.status = "used" THEN 1 ELSE 0 END) as used_count'),
                DB::raw('SUM(CASE WHEN ticket_purchases.status = "cancelled" THEN 1 ELSE 0 END) as cancelled_count')
            ])
            ->groupBy('teachers.id', 'teachers.name', 'teachers.email')
            ->orderBy(DB::raw('SUM(CASE WHEN ticket_purchases.status IN ("valid", "used") AND (tickets.ticket_category IN ("regular", "vip") OR (tickets.ticket_category = "walk-in" AND ticket_purchases.is_sold = 1)) THEN tickets.price ELSE 0 END)'), 'desc')
            ->get();
        
        return view('reports.ticket-sales-pdf', [
            'totalRevenue' => $this->totalRevenue,
            'totalSales' => $this->totalSales,
            'validTickets' => $this->validTickets,
            'usedTickets' => $this->usedTickets,
            'cancelledTickets' => $this->cancelledTickets,
            'sales' => $sales,
            'concertRevenue' => $concertRevenue,
            'teacherSales' => $teacherSales,
            'filters' => $this->getActiveFilters(),
            'generatedAt' => now()
        ]);
    }
    
    protected function getActiveFilters()
    {
        $filters = [];
        
        if ($this->concertFilter) {
            $concert = Concert::find($this->concertFilter);
            $filters['Concert'] = $concert ? $concert->title : 'Unknown';
        }
        
        if ($this->teacherFilter) {
            $teacher = User::find($this->teacherFilter);
            $filters['Teacher'] = $teacher ? $teacher->name : 'Unknown';
        }
        
        if ($this->statusFilter) {
            $filters['Status'] = ucfirst($this->statusFilter);
        }
        
        if ($this->dateFrom) {
            $filters['From Date'] = $this->dateFrom;
        }
        
        if ($this->dateTo) {
            $filters['To Date'] = $this->dateTo;
        }
        
        if ($this->search) {
            $filters['Search'] = $this->search;
        }
        
        return $filters;
    }
    
    public function calculateStats()
    {
        $query = $this->getBaseQuery();
        
        // Calculate total revenue (only for sold tickets: regular/VIP tickets or walk-in tickets that are sold)
        $this->totalRevenue = $query->clone()
            ->whereIn('ticket_purchases.status', ['valid', 'used'])
            ->where(function($q) {
                $q->whereIn('tickets.ticket_category', ['regular', 'vip']) // Regular and VIP tickets (always sold)
                  ->orWhere(function($subq) {
                      $subq->where('tickets.ticket_category', 'walk-in')
                           ->where('ticket_purchases.is_sold', true);  // Or walk-in tickets that are sold
                  });
            })
            ->sum(DB::raw('tickets.price'));
        
        // Calculate total sales count (only sold tickets)
        $this->totalSales = $query->clone()
            ->where(function($q) {
                $q->whereIn('tickets.ticket_category', ['regular', 'vip']) // Regular and VIP tickets (always sold)
                  ->orWhere(function($subq) {
                      $subq->where('tickets.ticket_category', 'walk-in')
                           ->where('ticket_purchases.is_sold', true);  // Or walk-in tickets that are sold
                  });
            })
            ->count();
        
        // Calculate status-based counts (all tickets including pre-generated walk-in)
        $statusCounts = $query->clone()
            ->select('ticket_purchases.status', DB::raw('COUNT(*) as count'))
            ->groupBy('ticket_purchases.status')
            ->pluck('count', 'status')
            ->toArray();
        
        $this->validTickets = $statusCounts['valid'] ?? 0;
        $this->usedTickets = $statusCounts['used'] ?? 0;
        $this->cancelledTickets = $statusCounts['cancelled'] ?? 0;
    }
    
    protected function getBaseQuery()
    {
        $query = TicketPurchase::query()
            ->join('tickets', 'ticket_purchases.ticket_id', '=', 'tickets.id')
            ->join('concerts', 'tickets.concert_id', '=', 'concerts.id')
            ->leftJoin('users as students', 'ticket_purchases.student_id', '=', 'students.id') // Left join for walk-in tickets
            ->join('users as teachers', 'ticket_purchases.teacher_id', '=', 'teachers.id');
        
        // Apply filters
        if ($this->concertFilter) {
            $query->where('concerts.id', $this->concertFilter);
        }
        
        if ($this->teacherFilter) {
            $query->where('teachers.id', $this->teacherFilter);
        }
        
        if ($this->statusFilter) {
            $query->where('ticket_purchases.status', $this->statusFilter);
        }
        
        if ($this->dateFrom) {
            $query->whereDate('ticket_purchases.purchase_date', '>=', $this->dateFrom);
        }
        
        if ($this->dateTo) {
            $query->whereDate('ticket_purchases.purchase_date', '<=', $this->dateTo);
        }
        
        if ($this->search) {
            $query->where(function($q) {
                $q->where('students.name', 'like', '%' . $this->search . '%')
                  ->orWhere('students.email', 'like', '%' . $this->search . '%')
                  ->orWhere('concerts.title', 'like', '%' . $this->search . '%')
                  ->orWhere('tickets.ticket_type', 'like', '%' . $this->search . '%');
            });
        }
        
        return $query;
    }
    
    public function render()
    {
        // Get paginated sales data (only actual sales, exclude pre-generated walk-in tickets)
        $sales = $this->getBaseQuery()
            ->select([
                'ticket_purchases.id',
                'ticket_purchases.order_id',
                DB::raw('COALESCE(students.name, "Walk-in Customer") as student_name'),
                DB::raw('COALESCE(students.email, "N/A") as student_email'),
                'concerts.title as concert_title',
                'concerts.date as concert_date',
                'concerts.venue',
                'tickets.ticket_type',
                'tickets.ticket_category',
                'tickets.price',
                'teachers.name as teacher_name',
                'ticket_purchases.purchase_date',
                'ticket_purchases.status'
            ])
            ->where(function($query) {
                $query->whereIn('tickets.ticket_category', ['regular', 'vip'])  // Regular and VIP tickets (always sold)
                      ->orWhere(function($q) {
                          $q->where('tickets.ticket_category', 'walk-in')
                            ->where('ticket_purchases.is_sold', true);  // Or walk-in tickets that are sold
                      });
            })
            ->orderBy('ticket_purchases.purchase_date', 'desc')
            ->paginate(15);
        
        // Get concert revenue breakdown
        $concertRevenue = $this->getBaseQuery()
            ->select([
                'concerts.id',
                'concerts.title',
                'concerts.date',
                DB::raw('COUNT(CASE WHEN tickets.ticket_category IN ("regular", "vip") OR (tickets.ticket_category = "walk-in" AND ticket_purchases.is_sold = 1) THEN 1 END) as total_sales'),
                DB::raw('SUM(CASE WHEN ticket_purchases.status IN ("valid", "used") AND (tickets.ticket_category IN ("regular", "vip") OR (tickets.ticket_category = "walk-in" AND ticket_purchases.is_sold = 1)) THEN tickets.price ELSE 0 END) as revenue'),
                DB::raw('SUM(CASE WHEN ticket_purchases.status = "valid" THEN 1 ELSE 0 END) as valid_count'),
                DB::raw('SUM(CASE WHEN ticket_purchases.status = "used" THEN 1 ELSE 0 END) as used_count'),
                DB::raw('SUM(CASE WHEN ticket_purchases.status = "cancelled" THEN 1 ELSE 0 END) as cancelled_count')
            ])
            ->groupBy('concerts.id', 'concerts.title', 'concerts.date')
            ->orderBy('concerts.date', 'desc')
            ->get();
        
        // Get teacher sales breakdown
        $teacherSales = $this->getBaseQuery()
            ->select([
                'teachers.id',
                'teachers.name',
                'teachers.email',
                DB::raw('COUNT(CASE WHEN tickets.ticket_category IN ("regular", "vip") OR (tickets.ticket_category = "walk-in" AND ticket_purchases.is_sold = 1) THEN 1 END) as total_sales'),
                DB::raw('SUM(CASE WHEN ticket_purchases.status IN ("valid", "used") AND (tickets.ticket_category IN ("regular", "vip") OR (tickets.ticket_category = "walk-in" AND ticket_purchases.is_sold = 1)) THEN tickets.price ELSE 0 END) as revenue'),
                DB::raw('SUM(CASE WHEN ticket_purchases.status = "valid" THEN 1 ELSE 0 END) as valid_count'),
                DB::raw('SUM(CASE WHEN ticket_purchases.status = "used" THEN 1 ELSE 0 END) as used_count'),
                DB::raw('SUM(CASE WHEN ticket_purchases.status = "cancelled" THEN 1 ELSE 0 END) as cancelled_count')
            ])
            ->groupBy('teachers.id', 'teachers.name', 'teachers.email')
            ->orderBy(DB::raw('SUM(CASE WHEN ticket_purchases.status IN ("valid", "used") AND (tickets.ticket_category IN ("regular", "vip") OR (tickets.ticket_category = "walk-in" AND ticket_purchases.is_sold = 1)) THEN tickets.price ELSE 0 END)'), 'desc')
            ->get();
        
        // Get filter options
        $concerts = Concert::orderBy('date', 'desc')->get();
        $teachers = User::role('teacher')->orderBy('name')->get();
        
        return view('livewire.admin.ticket-sales', [
            'sales' => $sales,
            'concertRevenue' => $concertRevenue,
            'teacherSales' => $teacherSales,
            'concerts' => $concerts,
            'teachers' => $teachers,
        ]);
    }
} 