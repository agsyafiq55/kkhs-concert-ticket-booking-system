<?php

use App\Livewire\Admin\Concerts\Create as ConcertCreate;
use App\Livewire\Admin\Concerts\Edit as ConcertEdit;
use App\Livewire\Admin\Concerts\Index as ConcertIndex;
use App\Livewire\Admin\Tickets\Create as TicketCreate;
use App\Livewire\Admin\Tickets\Edit as TicketEdit;
use App\Livewire\Admin\Tickets\Index as TicketIndex;
use App\Livewire\Admin\UserManagement;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;

Route::get('/', function () {
    return view('welcome');
})->name('home');

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

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/users', UserManagement::class)->name('admin.users');
    
    // Concert routes
    Route::get('/concerts', ConcertIndex::class)->name('admin.concerts');
    Route::get('/concerts/create', ConcertCreate::class)->name('admin.concerts.create');
    Route::get('/concerts/{id}/edit', ConcertEdit::class)->name('admin.concerts.edit');
    
    // Ticket routes
    Route::get('/tickets', TicketIndex::class)->name('admin.tickets');
    Route::get('/tickets/create', TicketCreate::class)->name('admin.tickets.create');
    Route::get('/tickets/{id}/edit', TicketEdit::class)->name('admin.tickets.edit');
});

require __DIR__.'/auth.php';
