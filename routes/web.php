<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KabupatenController;
use App\Http\Controllers\KecamatanController;
use App\Http\Controllers\LayananController;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Ticket;

// API endpoint for kecamatan data (must be outside middleware to avoid redirects)
Route::get('/api/kecamatan/{kabupatenId}', function ($kabupatenId) {
    return Kecamatan::where('kabupaten_id', $kabupatenId)->get();
});

// Guest routes - protected from admin access
Route::middleware(['prevent.admin.guest'])->group(function () {
    Route::get('/', [TicketController::class, 'guestDashboard']);

    Route::get('/guest/guest_dashboard', function () {
        $countSent = Ticket::count();
        $countWorkedOn = Ticket::whereIn('status', ['diterima/approved', 'selesai/completed'])->count();
        return view('guest.guest_dashboard', compact('countSent', 'countWorkedOn'));
    })->name('guest.guest_dashboard');

    Route::get('guest/create_tiket', function () {
        $kabupatens = Kabupaten::all();
        return view('guest.create_tiket', compact('kabupatens'));
    })->name('guest.create_tiket');

    Route::post('tickets', [TicketController::class, 'store'])->name('tickets.store');

    Route::get('guest/lacak_tiket', [TicketController::class, 'track'])->name('guest.lacak_tiket');

    Route::get('guest/lihat_tiket/{code_tracking}', [TicketController::class, 'show'])->name('guest.liat_tiket');
});

// Admin Authentication Routes (not protected - admins need access when not logged in)
Route::get('admin/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [AdminController::class, 'login'])->name('admin.login.submit');

// Admin protected routes - only accessible by authenticated admins
Route::middleware(['admin.auth', 'prevent.guest.admin'])->prefix('admin')->name('admin.')->group(function () {
    // Logout route
    Route::post('logout', [AdminController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Dashboard API routes for AJAX calls
    Route::get('dashboard/stats', [AdminController::class, 'getRealtimeStats'])->name('dashboard.stats');
    Route::get('dashboard/recent-tickets', [AdminController::class, 'getRecentTickets'])->name('dashboard.recent-tickets');
    Route::post('dashboard/chart-data', [AdminController::class, 'getChartDataAjax'])->name('dashboard.chart-data');
    Route::post('dashboard/processing-time-data', [AdminController::class, 'getProcessingTimeAnalyticsAjax'])->name('dashboard.processing-time-data');
    Route::post('dashboard/processing-time', [AdminController::class, 'getProcessingTimeAnalyticsAjax'])->name('dashboard.processing-time');
    Route::get('tickets/{ticket}/details', [AdminController::class, 'getTicketDetails'])->name('tickets.details');

    // Navigation routes for month navigation
    Route::post('dashboard/navigate/previous', [AdminController::class, 'navigatePreviousMonth'])->name('dashboard.navigate.previous');
    Route::post('dashboard/navigate/next', [AdminController::class, 'navigateNextMonth'])->name('dashboard.navigate.next');

    // Resource routes
    Route::resource('kabupaten', KabupatenController::class);
    Route::resource('kecamatan', KecamatanController::class);
    Route::resource('layanan', LayananController::class);

    // Tickets - static routes FIRST (specific routes before parameterized ones)
    Route::get('tickets', [TicketController::class, 'adminIndex'])->name('tickets.index');
    Route::get('tickets/export', [TicketController::class, 'export'])->name('tickets.export');
    Route::get('tickets/activity', [TicketController::class, 'activity'])->name('tickets.activity');
    Route::get('tickets/process', [TicketController::class, 'process'])->name('tickets.process');

    // Calendar API routes
    Route::get('tickets/calendar/data', [TicketController::class, 'getCalendarData'])->name('tickets.calendar.data');
    Route::get('tickets/calendar/day/{date}', [TicketController::class, 'getDayDetails'])->name('tickets.calendar.day');

    // Complete ticket route
    Route::post('tickets/{ticket}/complete', [TicketController::class, 'complete'])->name('tickets.complete');

    // Accept/reject routes
    Route::post('tickets/{ticket}/accept', [TicketController::class, 'accept'])->name('tickets.accept');
    Route::post('tickets/{ticket}/reject', [TicketController::class, 'reject'])->name('tickets.reject');

    // Work session management routes
    Route::post('tickets/{ticket}/start-session', [TicketController::class, 'startWorkSession'])->name('tickets.start-session');
    Route::post('work-sessions/{workSession}/pause', [TicketController::class, 'pauseWorkSession'])->name('work-sessions.pause');
    Route::post('work-sessions/{workSession}/resume', [TicketController::class, 'resumeWorkSession'])->name('work-sessions.resume');
    Route::post('work-sessions/{workSession}/complete', [TicketController::class, 'completeWorkSession'])->name('work-sessions.complete');

    // Parameterized route LAST (this catches any tickets/{anything} that doesn't match above)
    Route::get('tickets/{ticket}', [TicketController::class, 'adminShow'])->name('tickets.show');
});

// Chat API routes
// Route::get('api/tickets/{ticket}/messages', [App\Http\Controllers\ChatController::class, 'getMessages']);
