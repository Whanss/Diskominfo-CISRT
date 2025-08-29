<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\WorkSession;
use App\Mail\TicketStatusChanged;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class TicketController extends Controller
{
    public function guestDashboard()
    {
        $countSent = \App\Models\Ticket::whereNotIn('status', ['ditolak/rejected'])->count();
        $countWorkedOn = \App\Models\Ticket::whereIn('status', ['diterima/approved', 'selesai/completed'])->count();

        return view('guest.guest_dashboard', [
            'countSent' => $countSent,
            'countWorkedOn' => $countWorkedOn,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'nama_pelapor' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'no_hp' => 'nullable|string|max:20',
            'description' => 'required|string',
            'attachment' => 'nullable|file|max:5120', // 5MB max
            'kabupaten_id' => 'required|exists:kabupaten,id',
            'kecamatan_id' => 'required|exists:kecamatan,id',
        ]);

        // Generate unique code_tracking
        do {
            $code_tracking = strtoupper(uniqid('TKT'));
        } while (Ticket::where('code_tracking', $code_tracking)->exists());

        $validated['code_tracking'] = $code_tracking;
        $validated['status'] = 'pending'; // default status

        // Handle file upload
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('ticket_attachments', 'public');
            $validated['attachment_path'] = $path;
        }

        $ticket = Ticket::create($validated);

        // Redirect to the ticket detail page using the ticket's ID instead of code_tracking
        return redirect()->route('guest.liat_tiket', ['code_tracking' => $ticket->code_tracking]);
    }

    public function show($code_tracking)
    {
        $ticket = Ticket::with(['layanan', 'kabupaten', 'kecamatan'])
            ->where('code_tracking', $code_tracking)
            ->firstOrFail();

        return view('guest.liat_tiket', compact('ticket'));
    }

    public function track(Request $request)
    {
        $code_tracking = $request->query('code_tracking');

        if ($request->ajax()) {
            $exists = Ticket::where('code_tracking', $code_tracking)->exists();
            return response()->json(['exists' => $exists]);
        }

        if ($code_tracking) {
            $ticket = Ticket::where('code_tracking', $code_tracking)->first();
            if ($ticket) {
                return redirect()->route('guest.liat_tiket', ['code_tracking' => $code_tracking]);
            } else {
                return redirect()->back()->with('error', 'Tiket tidak ditemukan.');
            }
        }

        return redirect()->route('guest.create_tiket');
    }

    // Admin: list all tickets with filtering
    public function adminIndex(Request $request)
    {
        $query = Ticket::with(['kabupaten', 'kecamatan']);

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Filter by kabupaten
        if ($request->filled('kabupaten_id')) {
            $query->where('kabupaten_id', $request->kabupaten_id);
        }

        // Filter by kecamatan
        if ($request->filled('kecamatan_id')) {
            $query->where('kecamatan_id', $request->kecamatan_id);
        }

        // Search by code tracking or reporter name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code_tracking', 'like', "%{$search}%")
                    ->orWhere('nama_pelapor', 'like', "%{$search}%")
                    ->orWhere('judul', 'like', "%{$search}%");
            });
        }

        $tickets = $query->orderBy('created_at', 'desc')->get();

        // Get filter options
        $kabupatenList = \App\Models\Kabupaten::orderBy('nama')->get();
        $kecamatanList = \App\Models\Kecamatan::orderBy('nama')->get();

        // Get statistics for each status
        $stats = [
            'total' => Ticket::count(),
            'pending' => Ticket::where('status', 'pending')->count(),
            'approved' => Ticket::where('status', 'diterima/approved')->count(),
            'rejected' => Ticket::where('status', 'ditolak/rejected')->count(),
            'completed' => Ticket::where('status', 'selesai/completed')->count(),
        ];

        return view('admin.tickets.index', compact('tickets', 'kabupatenList', 'kecamatanList', 'stats'));
    }

    // Admin: show individual ticket details
    public function adminShow(Ticket $ticket)
    {
        return view('admin.tickets.show', compact('ticket'));
    }

    // Admin: accept ticket
    public function accept(Ticket $ticket)
    {
        $oldStatus = $ticket->status;
        $ticket->status = 'diterima/approved';
        $ticket->accepted_at = now();
        $ticket->save();

        // Log activity
        \App\Models\TicketActivityLog::create([
            'ticket_id' => $ticket->id,
            'action' => 'accepted',
            'description' => 'Tiket diterima, siap untuk diproses',
        ]);

        // Send email notification
        Mail::to($ticket->email)->send(new TicketStatusChanged($ticket, $oldStatus, $ticket->status));

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Ticket accepted successfully and ready for processing.']);
        }

        return redirect()->back()->with('success', 'Ticket accepted successfully and ready for processing.');
    }

    // Admin: reject ticket
    public function reject(Ticket $ticket)
    {
        $oldStatus = $ticket->status;
        $ticket->status = 'ditolak/rejected';
        $ticket->resolved_at = now();
        $ticket->save();

        // Log activity
        \App\Models\TicketActivityLog::create([
            'ticket_id' => $ticket->id,
            'action' => 'rejected',
            'description' => 'Tiket ditolak',
        ]);

        // Send email notification
        Mail::to($ticket->email)->send(new TicketStatusChanged($ticket, $oldStatus, $ticket->status));

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Ticket rejected successfully.']);
        }

        return redirect()->back()->with('success', 'Ticket rejected successfully.');
    }

    public function export(Request $request)
    {
        $query = Ticket::query();

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('code_tracking')) {
            $query->where('code_tracking', $request->code_tracking);
        }

        $tickets = $query->orderBy('created_at', 'desc')->get();

        if ($request->has('format') && $request->get('format') === 'pdf') {
            $pdf = Pdf::loadView('admin.tickets.export_pdf', compact('tickets'));
            return $pdf->download('laporan_tiket_' . now()->format('Ymd_His') . '.pdf');
        }

        // Default to Excel export (existing functionality)
        // You can implement Excel export here or keep existing export logic
        return view('admin.tickets.export', compact('tickets'));
    }

    public function activity(Request $request)
    {
        $query = \App\Models\TicketActivityLog::query()
            ->with(['ticket'])
            ->orderBy('created_at', 'desc');

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Filter by action type
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by ticket code
        if ($request->filled('code_tracking')) {
            $query->whereHas('ticket', function ($q) use ($request) {
                $q->where('code_tracking', 'like', '%' . $request->code_tracking . '%');
            });
        }

        $activities = $query->paginate(20);

        return view('admin.tickets.activity', compact('activities'));
    }

    public function process()
    {
        // Get accepted tickets (status = 'diterima/approved') - ready for processing
        $acceptedTickets = Ticket::where('status', 'diterima/approved')
            ->with(['layanan'])
            ->orderBy('accepted_at', 'asc')
            ->get();

        // Get tickets completed today
        $completedToday = Ticket::where('status', 'selesai/completed')
            ->whereDate('resolved_at', today())
            ->get();

        // Calculate total work time for today (sum of processing time for completed tickets today)
        $totalWorkTimeToday = Ticket::where('status', 'selesai/completed')
            ->whereDate('resolved_at', today())
            ->get()
            ->sum(function ($ticket) {
                return $ticket->total_processing_time;
            });

        return view('admin.tickets.process', compact(
            'acceptedTickets',
            'completedToday',
            'totalWorkTimeToday'
        ));
    }

    public function complete(Ticket $ticket)
    {
        $oldStatus = $ticket->status;
        $ticket->status = 'selesai/completed';
        $ticket->resolved_at = now();
        $ticket->save();

        // Log activity
        \App\Models\TicketActivityLog::create([
            'ticket_id' => $ticket->id,
            'action' => 'completed',
            'description' => 'Tiket selesai',
        ]);

        // Send email notification
        Mail::to($ticket->email)->send(new TicketStatusChanged($ticket, $oldStatus, $ticket->status));

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Ticket completed successfully.']);
        }

        return redirect()->back()->with('success', 'Ticket completed successfully.');
    }

    // NEW CALENDAR METHODS
    public function calendar()
    {
        $currentMonth = now()->format('Y-m');

        // Get monthly statistics
        $monthlyStats = $this->getMonthlyStats($currentMonth);

        
    }

    public function getCalendarData(Request $request)
    {
        $start = Carbon::parse($request->start ?? now()->startOfMonth());
        $end = Carbon::parse($request->end ?? now()->endOfMonth());

        $events = [];

        // First, try to get work sessions data
        $workSessionsByDate = WorkSession::with(['ticket'])
            ->whereBetween('started_at', [$start, $end])
            ->where('status', 'completed')
            ->get()
            ->groupBy(function ($session) {
                return $session->started_at->format('Y-m-d');
            });

        // If we have work sessions, use them
        foreach ($workSessionsByDate as $date => $sessions) {
            $totalDuration = $sessions->sum('duration');
            $uniqueTickets = $sessions->pluck('ticket_id')->unique();
            $ticketCount = $uniqueTickets->count();

            $formattedTime = $this->formatDuration($totalDuration);

            $events[] = [
                'id' => 'work-' . $date,
                'title' => "{$formattedTime} ({$ticketCount} tiket)",
                'start' => $date,
                'backgroundColor' => $this->getColorByDuration($totalDuration),
                'borderColor' => $this->getColorByDuration($totalDuration),
                'extendedProps' => [
                    'type' => 'work_session',
                    'duration' => $totalDuration,
                    'ticket_count' => $ticketCount,
                    'sessions_count' => $sessions->count(),
                    'formatted_duration' => $formattedTime
                ]
            ];
        }

        // FALLBACK: If no work sessions, use ticket data (accepted_at to resolved_at or now)
        if (empty($events)) {
            $ticketsByDate = Ticket::where('status', '!=', 'pending')
                ->where(function ($query) use ($start, $end) {
                    $query->whereBetween('accepted_at', [$start, $end])
                        ->orWhereBetween('resolved_at', [$start, $end]);
                })
                ->whereNotNull('accepted_at')
                ->get()
                ->groupBy(function ($ticket) {
                    // Group by the date when ticket was being worked on
                    if ($ticket->resolved_at) {
                        return $ticket->resolved_at->format('Y-m-d');
                    } else if ($ticket->accepted_at) {
                        return $ticket->accepted_at->format('Y-m-d');
                    }
                    return null;
                })
                ->filter(); // Remove null keys

            foreach ($ticketsByDate as $date => $tickets) {
                $totalDuration = 0;
                $ticketCount = $tickets->count();

                foreach ($tickets as $ticket) {
                    if ($ticket->accepted_at && $ticket->resolved_at) {
                        $totalDuration += $ticket->accepted_at->diffInSeconds($ticket->resolved_at);
                    } else if ($ticket->accepted_at) {
                        // For ongoing tickets, calculate time from accepted_at to now
                        $totalDuration += $ticket->accepted_at->diffInSeconds(now());
                    }
                }

                $formattedTime = $this->formatDuration($totalDuration);

                if ($totalDuration > 0) { // Only show if there's actual work time
                    $events[] = [
                        'id' => 'ticket-' . $date,
                        'title' => "{$formattedTime} ({$ticketCount} tiket)",
                        'start' => $date,
                        'backgroundColor' => $this->getColorByDuration($totalDuration),
                        'borderColor' => $this->getColorByDuration($totalDuration),
                        'extendedProps' => [
                            'type' => 'ticket_fallback',
                            'duration' => $totalDuration,
                            'ticket_count' => $ticketCount,
                            'sessions_count' => $ticketCount,
                            'formatted_duration' => $formattedTime
                        ]
                    ];
                }
            }
        }

        return response()->json($events);
    }

    public function getDayDetails($date)
    {
        $date = Carbon::parse($date);

        // First try work sessions
        $workSessions = WorkSession::with(['ticket', 'admin'])
            ->whereDate('started_at', $date)
            ->orderBy('started_at', 'asc')
            ->get();

        if ($workSessions->isNotEmpty()) {
            // Use work sessions data
            $completedSessions = $workSessions->where('status', 'completed');
            $totalDuration = $completedSessions->sum('duration');
            $uniqueTickets = $completedSessions->pluck('ticket_id')->unique();

            $dailyStats = [
                'total_duration' => $totalDuration,
                'total_sessions' => $workSessions->count(),
                'completed_sessions' => $completedSessions->count(),
                'unique_tickets' => $uniqueTickets->count(),
                'formatted_duration' => $this->formatDuration($totalDuration),
            ];

            $formattedSessions = $workSessions->map(function ($session) {
                $startTime = $session->started_at->format('H:i');
                $endTime = $session->completed_at ? $session->completed_at->format('H:i') : 'Ongoing';

                return [
                    'id' => $session->id,
                    'ticket_code' => $session->ticket->code_tracking,
                    'ticket_title' => $session->ticket->judul ?? 'No Title',
                    'admin_name' => $session->admin->name ?? 'Unknown Admin',
                    'started_at' => $startTime,
                    'completed_at' => $endTime,
                    'duration' => $this->formatDuration($session->duration),
                    'status' => $session->status,
                    'time_range' => $session->completed_at ? "{$startTime} - {$endTime}" : "Started at {$startTime}"
                ];
            });
        } else {
            // FALLBACK: Use ticket data
            $tickets = Ticket::where(function ($query) use ($date) {
                $query->whereDate('accepted_at', $date)
                    ->orWhereDate('resolved_at', $date);
            })
                ->where('status', '!=', 'pending')
                ->whereNotNull('accepted_at')
                ->get();

            $totalDuration = 0;
            $formattedSessions = collect();

            foreach ($tickets as $ticket) {
                $duration = 0;
                if ($ticket->accepted_at && $ticket->resolved_at) {
                    $duration = $ticket->accepted_at->diffInSeconds($ticket->resolved_at);
                } else if ($ticket->accepted_at) {
                    $duration = $ticket->accepted_at->diffInSeconds(now());
                }

                $totalDuration += $duration;

                $startTime = $ticket->accepted_at->format('H:i');
                $endTime = $ticket->resolved_at ? $ticket->resolved_at->format('H:i') : 'Ongoing';

                $formattedSessions->push([
                    'id' => 'ticket-' . $ticket->id,
                    'ticket_code' => $ticket->code_tracking,
                    'ticket_title' => $ticket->judul ?? 'No Title',
                    'admin_name' => 'System',
                    'started_at' => $startTime,
                    'completed_at' => $endTime,
                    'duration' => $this->formatDuration($duration),
                    'status' => $ticket->status,
                    'time_range' => $ticket->resolved_at ? "{$startTime} - {$endTime}" : "Started at {$startTime}"
                ]);
            }

            $dailyStats = [
                'total_duration' => $totalDuration,
                'total_sessions' => $tickets->count(),
                'completed_sessions' => $tickets->where('status', 'selesai/completed')->count(),
                'unique_tickets' => $tickets->count(),
                'formatted_duration' => $this->formatDuration($totalDuration),
            ];
        }

        return response()->json([
            'date' => $date->format('d F Y'),
            'day_name' => $date->format('l'),
            'stats' => $dailyStats,
            'sessions' => $formattedSessions
        ]);
    }

    public function startWorkSession(Ticket $ticket)
    {
        $adminId = Auth::guard('admin')->id();

        // Check if there's already an active session for this ticket
        $activeSession = WorkSession::where('ticket_id', $ticket->id)
            ->where('admin_id', $adminId)
            ->where('status', 'active')
            ->first();

        if ($activeSession) {
            return response()->json(['error' => 'Work session already active for this ticket'], 400);
        }

        $workSession = WorkSession::create([
            'ticket_id' => $ticket->id,
            'admin_id' => $adminId,
            'status' => 'active',
            'started_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Work session started',
            'session_id' => $workSession->id
        ]);
    }

    public function pauseWorkSession(WorkSession $workSession)
    {
        if ($workSession->status !== 'active') {
            return response()->json(['error' => 'Work session is not active'], 400);
        }

        $duration = now()->diffInSeconds($workSession->started_at);

        $workSession->update([
            'status' => 'paused',
            'paused_at' => now(),
            'duration' => $workSession->duration + $duration
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Work session paused',
            'duration' => $this->formatDuration($workSession->duration)
        ]);
    }

    public function resumeWorkSession(WorkSession $workSession)
    {
        if ($workSession->status !== 'paused') {
            return response()->json(['error' => 'Work session is not paused'], 400);
        }

        $workSession->update([
            'status' => 'active',
            'started_at' => now(),
            'paused_at' => null
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Work session resumed'
        ]);
    }

    public function completeWorkSession(WorkSession $workSession)
    {
        if ($workSession->status === 'completed') {
            return response()->json(['error' => 'Work session already completed'], 400);
        }

        $additionalDuration = 0;
        if ($workSession->status === 'active') {
            $additionalDuration = now()->diffInSeconds($workSession->started_at);
        }

        $workSession->update([
            'status' => 'completed',
            'completed_at' => now(),
            'duration' => $workSession->duration + $additionalDuration
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Work session completed',
            'total_duration' => $this->formatDuration($workSession->duration)
        ]);
    }

    private function getMonthlyStats($month)
    {
        $startOfMonth = Carbon::parse($month . '-01')->startOfMonth();
        $endOfMonth = Carbon::parse($month . '-01')->endOfMonth();

        $workSessions = WorkSession::whereBetween('started_at', [$startOfMonth, $endOfMonth])->get();

        return [
            'total_duration' => $workSessions->sum('duration'),
            'total_sessions' => $workSessions->count(),
            'unique_tickets' => $workSessions->pluck('ticket_id')->unique()->count(),
            'avg_session_duration' => $workSessions->count() > 0 ? $workSessions->avg('duration') : 0,
            'most_productive_day' => $this->getMostProductiveDay($workSessions),
        ];
    }

    private function getMostProductiveDay($workSessions)
    {
        $dailyDurations = $workSessions->groupBy(function ($session) {
            return $session->started_at->format('Y-m-d');
        })->map(function ($sessions) {
            return $sessions->sum('duration');
        });

        if ($dailyDurations->isEmpty()) {
            return null;
        }

        $maxDay = $dailyDurations->keys()->first();
        $maxDuration = $dailyDurations->first();

        foreach ($dailyDurations as $day => $duration) {
            if ($duration > $maxDuration) {
                $maxDay = $day;
                $maxDuration = $duration;
            }
        }

        return [
            'date' => Carbon::parse($maxDay)->format('d F Y'),
            'duration' => $this->formatDuration($maxDuration)
        ];
    }

    private function formatDuration($seconds)
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $remainingSeconds = $seconds % 60;

        if ($hours > 0) {
            return sprintf('%dj %dm %ds', $hours, $minutes, $remainingSeconds);
        } elseif ($minutes > 0) {
            return sprintf('%dm %ds', $minutes, $remainingSeconds);
        } else {
            return sprintf('%ds', $remainingSeconds);
        }
    }

    private function getColorByDuration($duration)
    {
        if ($duration < 3600) { // Less than 1 hour
            return '#fbbf24'; // Yellow
        } elseif ($duration < 7200) { // Less than 2 hours
            return '#34d399'; // Green
        } elseif ($duration < 14400) { // Less than 4 hours
            return '#60a5fa'; // Blue
        } else { // 4+ hours
            return '#a78bfa'; // Purple
        }
    }
}
