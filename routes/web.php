<?php

use App\Livewire\Admin\Concerts\Create as ConcertCreate;
use App\Livewire\Admin\Concerts\Edit as ConcertEdit;
use App\Livewire\Admin\Concerts\Index as ConcertIndex;
use App\Livewire\Admin\EditUserRoles;
use App\Livewire\Admin\Tickets\Create as TicketCreate;
use App\Livewire\Admin\Tickets\Edit as TicketEdit;
use App\Livewire\Admin\Tickets\Index as TicketIndex;
use App\Livewire\Admin\UserManagement;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Teacher\AssignTickets;
use App\Models\TicketPurchase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Spatie\Permission\Models\Role;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Preview email template route
Route::get('/preview-email', function () {
    // This route is for previewing the email template
    // Only accessible by admin users
    if (! Auth::check() || ! Auth::user()->roles->pluck('name')->intersect(['admin', 'teacher'])->isEmpty()) {
        abort(403, 'Access denied');
    }

    // Get a sample ticket purchase to preview the email
    $ticketPurchase = TicketPurchase::with(['student', 'teacher', 'ticket.concert'])->first();

    if (! $ticketPurchase) {
        return 'No ticket purchases found. Please create a ticket purchase first.';
    }

    // Return the email view for preview
    return view('mail.emailer', compact('ticketPurchase'));
})->middleware('auth');

// Test email route
Route::get('/test-email', function () {
    // This route is for testing the email functionality
    // Only accessible by admin users
    if (! Auth::check() || ! Auth::user()->roles->pluck('name')->intersect(['admin', 'teacher'])->isEmpty()) {
        abort(403, 'Access denied');
    }

    // Get a sample ticket purchase to test the email
    $ticketPurchase = TicketPurchase::with(['student', 'teacher', 'ticket.concert'])->first();

    if (! $ticketPurchase) {
        return 'No ticket purchases found. Please create a ticket purchase first.';
    }

    try {
        // Send test email
        \Illuminate\Support\Facades\Mail::to($ticketPurchase->student->email)
            ->send(new \App\Mail\Emailer($ticketPurchase));

        return 'Test email sent successfully to '.$ticketPurchase->student->email;
    } catch (\Exception $e) {
        return 'Failed to send email: '.$e->getMessage();
    }
})->middleware('auth');

// Temporary route to check roles
Route::get('/check-roles', function () {
    if (Auth::check()) {
        $user = Auth::user();

        return response()->json([
            'user' => $user->name,
            'email' => $user->email,
            'roles' => $user->roles->pluck('name'),
        ]);
    }

    return response()->json(['error' => 'Not logged in']);
})->middleware('auth');

// Role test view
Route::get('/role-test', function () {
    return view('role-test');
})->middleware('auth')->name('role-test');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Help page - accessible to all authenticated users
Route::middleware(['auth'])->get('/help', \App\Livewire\Help::class)->name('help');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

// Admin routes - permission-based access
Route::middleware(['auth'])->prefix('admin')->group(function () {
    // User management - requires manage roles permission
    Route::middleware(['permission:manage roles'])->group(function () {
        Route::get('/users', UserManagement::class)->name('admin.users');
        Route::get('/users/{userId}/edit-roles', EditUserRoles::class)->name('admin.users.edit-roles');
        Route::get('/roles-permissions', \App\Livewire\Admin\RolePermissionManagement::class)->name('admin.roles-permissions');
    });

    // Bulk student upload - requires bulk upload students permission
    Route::middleware(['permission:bulk upload students'])->group(function () {
        Route::get('/bulk-student-upload', \App\Livewire\Admin\BulkStudentUpload::class)->name('admin.bulk-student-upload');
        Route::get('/bulk-student-upload/template', function () {
            // Create a proper Excel file with formatted columns
            return Excel::download(new class implements FromArray, WithColumnFormatting, WithHeadings, WithStyles
            {
                public function array(): array
                {
                    return [
                        ['ALI BIN ABDUL', 'aliabdul@moe-dl.edu.my', '12345', '1 AMANAH'],
                        ['SITI AMINAH BINTI HASSAN', 'sitiaminah@moe-dl.edu.my', '67890', '2 BESTARI'],
                        ['AHMAD FARID BIN IBRAHIM', 'ahmadfarid@moe-dl.edu.my', '11111', '6 ATAS 1'],
                    ];
                }

                public function headings(): array
                {
                    return ['name', 'email', 'daftar_no', 'class'];
                }

                public function columnFormats(): array
                {
                    return [
                        // Column formatting handled in styles() method for better control
                    ];
                }

                public function styles(Worksheet $sheet)
                {
                    // Set column widths for better readability
                    $sheet->getColumnDimension('A')->setWidth(25); // name
                    $sheet->getColumnDimension('B')->setWidth(35); // email
                    $sheet->getColumnDimension('C')->setWidth(15); // daftar_no
                    $sheet->getColumnDimension('D')->setWidth(20); // class

                    // Format the ENTIRE daftar_no column (C) as text to preserve leading zeros
                    $sheet->getStyle('C:C')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);

                    // Format header row
                    $sheet->getStyle('A1:D1')->applyFromArray([
                        'font' => ['bold' => true],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'E3F2FD'],
                        ],
                    ]);

                    return $sheet;
                }
            }, 'student_upload_template.xlsx');
        })->name('admin.bulk-student-upload.template');
    });

    // Bulk teacher upload routes - requires bulk upload teachers permission
    Route::middleware(['permission:bulk upload teachers'])->group(function () {
        Route::get('/bulk-teacher-upload', \App\Livewire\Admin\BulkTeacherUpload::class)->name('admin.bulk-teacher-upload');
        Route::get('/bulk-teacher-upload/template', function () {
            // Create a proper Excel file with formatted columns for teachers
            return Excel::download(new class implements FromArray, WithColumnFormatting, WithHeadings, WithStyles
            {
                public function array(): array
                {
                    return [
                        ['AHMAD BIN IBRAHIM', 'ahmad.ibrahim@moe-dl.edu.my', '750515081234', '1 AMANAH, 1 BESTARI'],
                        ['SITI NURHALIZA BINTI HASSAN', 'siti.nurhaliza@moe-dl.edu.my', '820210051234', '6 ATAS SAINS, 6 RENDAH SAINS'],
                        ['MOHD RAZAK BIN ABDULLAH', 'razak.abdullah@moe-dl.edu.my', '680825061234', '2 CERIA, 3 DINAMIK, 4 KREATIF'],
                        ['FARIDAH BINTI ISMAIL', 'faridah.ismail@moe-dl.edu.my', '851115071234', 'Peralihan CERIA 1, Peralihan CERIA 2'],
                        ['LIM WEI MING', 'lim.weiming@moe-dl.edu.my', '900320081234', '5 MULIA, 5 RAJIN'],
                    ];
                }

                public function headings(): array
                {
                    return ['name', 'email', 'ic_number', 'assigned_classes'];
                }

                public function columnFormats(): array
                {
                    return [
                        // Column formatting handled in styles() method for better control
                    ];
                }

                public function styles(Worksheet $sheet)
                {
                    // Set column widths for better readability
                    $sheet->getColumnDimension('A')->setWidth(30); // name
                    $sheet->getColumnDimension('B')->setWidth(35); // email
                    $sheet->getColumnDimension('C')->setWidth(20); // ic_number
                    $sheet->getColumnDimension('D')->setWidth(50); // assigned_classes

                    // Format the ENTIRE ic_number column (C) as text to preserve leading zeros
                    $sheet->getStyle('C:C')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);

                    // Format header row
                    $sheet->getStyle('A1:D1')->applyFromArray([
                        'font' => ['bold' => true],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'E3F2FD'],
                        ],
                    ]);

                    // Add instruction comments to the template
                    $sheet->getComment('D1')->getText()->createTextRun('Enter class names separated by commas.' . PHP_EOL . 
                        'Use full class names like:' . PHP_EOL .
                        '• 1 AMANAH, 2 BESTARI' . PHP_EOL .
                        '• 6 ATAS SAINS, 6 RENDAH 1' . PHP_EOL .
                        '• PERALIHAN CERIA 1, PERALIHAN CERIA 2' . PHP_EOL .
                        'Leave empty if no classes to assign.');

                    return $sheet;
                }
            }, 'teacher_upload_template.xlsx');
        })->name('admin.bulk-teacher-upload.template');
    });

    // Concert routes - requires view concerts permission
    Route::middleware(['permission:view concerts'])->group(function () {
        Route::get('/concerts', ConcertIndex::class)->name('admin.concerts');
    });

    Route::middleware(['permission:create concerts'])->group(function () {
        Route::get('/concerts/create', ConcertCreate::class)->name('admin.concerts.create');
    });

    Route::middleware(['permission:edit concerts'])->group(function () {
        Route::get('/concerts/{id}/edit', ConcertEdit::class)->name('admin.concerts.edit');
    });

    // Ticket routes - requires view tickets permission
    Route::middleware(['permission:view tickets'])->group(function () {
        Route::get('/tickets', TicketIndex::class)->name('admin.tickets');
    });

    Route::middleware(['permission:create tickets'])->group(function () {
        Route::get('/tickets/create', TicketCreate::class)->name('admin.tickets.create');
    });

    Route::middleware(['permission:edit tickets'])->group(function () {
        Route::get('/tickets/{id}/edit', TicketEdit::class)->name('admin.tickets.edit');
    });

    // Ticket Sales routes - requires view ticket sales permission
    Route::middleware(['permission:view ticket sales'])->group(function () {
        Route::get('/ticket-sales', \App\Livewire\Admin\TicketSales::class)->name('admin.ticket-sales');
    });

    // Walk-in ticket management - requires manage walk-in tickets permission
    Route::middleware(['permission:manage walk-in tickets'])->group(function () {
        Route::get('/walk-in-tickets', \App\Livewire\Admin\WalkInTickets::class)->name('admin.walk-in-tickets');
    });

    // VIP ticket sales - requires sell vip tickets permission
    Route::middleware(['permission:sell vip tickets'])->group(function () {
        Route::get('/vip-ticket-sales', \App\Livewire\Admin\VipTicketSales::class)->name('admin.vip-ticket-sales');
    });

    // Class management - requires manage classes permission
    Route::middleware(['permission:manage classes'])->group(function () {
        Route::get('/class-management', \App\Livewire\Admin\ClassManagement::class)->name('admin.class-management');
        Route::get('/class/{classId}/details', \App\Livewire\Admin\ClassDetails::class)->name('admin.class-details');
    });
});

// Teacher routes - permission-based access
Route::middleware(['auth'])->prefix('teacher')->group(function () {
    // Ticket assignment route - requires assign tickets permission
    Route::middleware(['permission:assign tickets'])->group(function () {
        Route::get('/assign-tickets', AssignTickets::class)->name('teacher.assign-tickets');
    });

    // Ticket scanning route - requires scan tickets permission
    Route::middleware(['permission:scan tickets'])->group(function () {
        Route::get('/scan-tickets', \App\Livewire\Teacher\ScanTickets::class)->name('teacher.scan-tickets');
    });

    // Walk-in ticket sales scanning - requires scan walk-in sales permission
    Route::middleware(['permission:scan walk-in sales'])->group(function () {
        Route::get('/scan-walk-in-sales', \App\Livewire\Teacher\ScanWalkInSales::class)->name('teacher.scan-walk-in-sales');
    });
});

// Student routes - permission-based access
Route::middleware(['auth'])->prefix('student')->group(function () {
    // My tickets route - requires view own tickets permission
    Route::middleware(['permission:view own tickets'])->group(function () {
        Route::get('/my-tickets', \App\Livewire\Student\MyTickets::class)->name('student.my-tickets');
    });
});

// Printable ticket view route
Route::get('/ticket/{id}/{token}', function ($id, $token) {
    try {
        $purchase = TicketPurchase::with(['student', 'teacher', 'ticket.concert'])->findOrFail($id);

        // Verify token (simple hash-based verification)
        $expectedToken = hash('sha256', $purchase->id.$purchase->qr_code.config('app.key'));

        if (! hash_equals($expectedToken, $token)) {
            abort(403, 'Invalid token');
        }

        return view('ticket.printable', compact('purchase'));

    } catch (\Exception $e) {
        abort(404, 'Ticket not found');
    }
})->name('ticket.printable');

// Bulk print walk-in tickets for a concert
Route::middleware(['auth', 'permission:manage walk-in tickets'])->get('/walk-in-tickets/print/{concertId}', function ($concertId) {
    try {
        $concert = \App\Models\Concert::findOrFail($concertId);

        // Get all pre-generated walk-in tickets for this concert
        $walkInTickets = TicketPurchase::query()
            ->with(['ticket.concert', 'teacher'])
            ->walkIn() // Use the new walk-in scope for relationship-based filtering
            ->where('is_sold', false) // Only pre-generated tickets
            ->where('status', 'valid')
            ->whereHas('ticket', function ($q) use ($concertId) {
                $q->where('concert_id', $concertId);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        if ($walkInTickets->isEmpty()) {
            abort(404, 'No walk-in tickets found for this concert');
        }

        return view('walk-in-tickets.bulk-print', compact('concert', 'walkInTickets'));

    } catch (\Exception $e) {
        abort(404, 'Concert not found or no tickets available');
    }
})->name('walk-in-tickets.bulk-print');

// QR Code generation route for emails
Route::get('/qr/ticket/{id}/{token}', function ($id, $token) {
    try {
        $purchase = TicketPurchase::findOrFail($id);

        // Verify token (simple hash-based verification)
        $expectedToken = hash('sha256', $purchase->id.$purchase->qr_code.config('app.key'));

        if (! hash_equals($expectedToken, $token)) {
            abort(403, 'Invalid token');
        }

        // Generate QR code as SVG then convert to PNG response
        $qrCodeSvg = QrCode::format('svg')
            ->size(300)
            ->margin(2)
            ->errorCorrection('H')
            ->generate($purchase->qr_code);

        // Return SVG as image (works in all email clients and browsers)
        return response($qrCodeSvg)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Cache-Control', 'public, max-age=3600')
            ->header('Content-Disposition', 'inline; filename="qr_ticket_'.$id.'.svg"');

    } catch (\Exception $e) {
        // Return a simple error image instead of failing
        $errorQr = QrCode::format('svg')
            ->size(300)
            ->margin(2)
            ->generate('Error: Invalid QR Code');

        return response($errorQr)
            ->header('Content-Type', 'image/svg+xml');
    }
})->name('qr.ticket');

require __DIR__.'/auth.php';
