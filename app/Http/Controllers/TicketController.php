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
use Illuminate\Http\JsonResponse;

class TicketController extends Controller
{
    public function guestDashboard()
    {
        $countSent = \App\Models\Ticket::count();
        $countWorkedOn = \App\Models\Ticket::where('status', 'selesai/completed')->count();

        // Get latest news for portfolio section, limit to 3
        $latestNews = \App\Models\News::published()->latest()->take(3)->get();

        return view('guest.guest_dashboard', [
            'countSent' => $countSent,
            'countWorkedOn' => $countWorkedOn,
            'latestNews' => $latestNews,
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
            'layanan_id' => 'required|exists:master_layanan,id',
            // kategori wajib hanya jika layanan tsb punya kategori aktif
            'layanan_category_id' => [
                'nullable',
                'exists:layanan_categories,id'
            ],
            'attachment' => [
                'nullable',
                'bail', // hentikan pada error pertama agar tidak dobel pesan
                'file',
                'max:5120',
                'mimes:pdf,jpg,jpeg',
                'mimetypes:application/pdf,image/jpeg'
            ],
            'kecamatan_id' => 'required|exists:kecamatan,id',
        ], [
            // Pesan khusus berbahasa Indonesia yang ringkas
            'attachment.file' => 'Lampiran harus berupa file.',
            'attachment.max' => 'Ukuran file maksimal 5 MB.',
            'attachment.mimes' => 'Format lampiran harus PDF atau JPG.',
            'attachment.mimetypes' => 'Format lampiran harus PDF atau JPG.',
        ], [
            // Ubah label attribute
            'attachment' => 'Lampiran',
        ]);

        // Jika layanan punya kategori aktif, maka kategori wajib
        if ($request->filled('layanan_id')) {
            $hasActiveCategories = \App\Models\LayananCategory::where('layanan_id', $request->layanan_id)
                ->where('is_active', true)
                ->exists();
            if ($hasActiveCategories && !$request->filled('layanan_category_id')) {
                return back()->withErrors(['layanan_category_id' => 'Silakan pilih kategori untuk layanan tersebut.'])->withInput();
            }
        }

        // Bersihkan field legacy yang tidak dipakai lagi
        unset($validated['layanan_type'], $validated['layanan_custom']);

        // Generate unique code_tracking
        do {
            $code_tracking = strtoupper(uniqid('TKT'));
        } while (Ticket::where('code_tracking', $code_tracking)->exists());

        $validated['code_tracking'] = $code_tracking;
        $validated['status'] = 'pending'; // default status

        // Handle file upload securely: validate, block dangerous extensions, and store in private disk
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');

            // Extra guard: block potentially dangerous double extensions or executable types
            $originalName = $file->getClientOriginalName();
            if (preg_match('/\.(php|phtml|phar|js|html|svg|exe|cmd|bat|sh)(\.|$)/i', $originalName)) {
                return back()->withErrors(['attachment' => 'Tipe file tidak diperbolehkan.']);
            }

            // Content-based verification (magic bytes) to prevent disguised files
            try {
                $stream = fopen($file->getPathname(), 'rb');
                $header = $stream ? fread($stream, 8) : '';
                if ($stream) {
                    fclose($stream);
                }
                $isPdf = str_starts_with($header, "%PDF"); // PDF starts with %PDF
                // JPEG starts with FF D8 and ends with FF D9
                $isJpeg = false;
                if (!$isPdf) {
                    $bytes = file_get_contents($file->getPathname());
                    if ($bytes !== false) {
                        $isJpeg = (substr($bytes, 0, 2) === "\xFF\xD8") && (substr($bytes, -2) === "\xFF\xD9");
                    }
                }
                if (!($isPdf || $isJpeg)) {
                    return back()->withErrors(['attachment' => 'File tidak valid. Hanya PDF atau JPG yang diperbolehkan.'])->withInput();
                }
            } catch (\Throwable $e) {
                return back()->withErrors(['attachment' => 'Gagal memverifikasi file lampiran.'])->withInput();
            }

            // Store into private storage, not publicly accessible
            $path = $file->store('ticket_attachments', 'private');
            $validated['attachment_path'] = $path;
        }

        $ticket = Ticket::create($validated);

        // Redirect to the ticket detail page using the ticket's ID instead of code_tracking
        return redirect()->route('guest.liat_tiket', ['code_tracking' => $ticket->code_tracking]);
    }

    public function show($code_tracking)
    {
        $ticket = Ticket::with(['layanan', 'kecamatan'])
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
        $query = Ticket::with(['kecamatan', 'layanan']);

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

        $tickets = $query->orderBy('created_at', 'desc')->paginate(2);

        // Get filter options
        $kecamatanList = \App\Models\Kecamatan::orderBy('nama')->get();

        // Get statistics for each status
        $stats = [
            'total' => Ticket::count(),
            'pending' => Ticket::where('status', 'pending')->count(),
            'approved' => Ticket::where('status', 'diterima/approved')->count(),
            'rejected' => Ticket::where('status', 'ditolak/rejected')->count(),
            'completed' => Ticket::where('status', 'selesai/completed')->count(),
        ];

        return view('admin.tickets.index', compact('tickets', 'kecamatanList', 'stats'));
    }

    // Admin: show individual ticket details
    public function adminShow(Ticket $ticket)
    {
        return view('admin.tickets.show', compact('ticket'));
    }

    // Admin: accept ticket
    public function accept(Ticket $ticket)
    {
        try {
            $ticket->status = 'diterima/approved';
            $ticket->accepted_at = now();
            $ticket->save();

            // Log activity: accepted
            \App\Models\TicketActivityLog::create([
                'ticket_id' => $ticket->id,
                'action' => 'accepted',
                'description' => 'Tiket disetujui untuk diproses',
            ]);

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Tiket berhasil diterima dan siap untuk diproses.'
                ])->header('Content-Type', 'application/json');
            }

            return redirect()->back()->with('success', 'Tiket berhasil diterima dan siap untuk diproses.');
        } catch (\Exception $e) {
            Log::error('Error accepting ticket: ' . $e->getMessage());

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menerima tiket: ' . $e->getMessage()
                ], 500)->header('Content-Type', 'application/json');
            }

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menerima tiket.');
        }
    }

    // Admin: reject ticket
    public function reject(Ticket $ticket)
    {
        try {
            request()->validate([
                'rejection_reason' => 'required|string|min:5|max:2000',
            ]);

            $rejectionReason = request()->input('rejection_reason');
            $ticket->status = 'ditolak/rejected';
            $ticket->rejection_reason = $rejectionReason;
            $ticket->resolved_at = now();
            $ticket->save();

            // Log activity: rejected
            \App\Models\TicketActivityLog::create([
                'ticket_id' => $ticket->id,
                'action' => 'rejected',
                'description' => 'Tiket ditolak: ' . $rejectionReason,
            ]);

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Tiket berhasil ditolak.'
                ])->header('Content-Type', 'application/json');
            }

            return redirect()->back()->with('success', 'Tiket berhasil ditolak.');
        } catch (\Exception $e) {
            Log::error('Error rejecting ticket: ' . $e->getMessage());

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menolak tiket: ' . $e->getMessage()
                ], 500)->header('Content-Type', 'application/json');
            }

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menolak tiket.');
        }
    }

    // Get ticket details for modal
    public function getDetails(Ticket $ticket)
    {
        $ticket->load(['kecamatan', 'layanan']);

        return response()->json([
            'success' => true,
            'ticket' => $ticket
        ]);
    }

    // Securely download attachment for a ticket (admin side)
    public function downloadTicketAttachment(Ticket $ticket)
    {
        if (!$ticket->attachment_path) {
            abort(404);
        }

        $disk = \Illuminate\Support\Facades\Storage::disk('private');
        if (!$disk->exists($ticket->attachment_path)) {
            abort(404);
        }

        $ext = pathinfo($ticket->attachment_path, PATHINFO_EXTENSION);
        $downloadName = 'lampiran_' . $ticket->code_tracking . '.' . $ext;

        try {
            // Resolve absolute path from private disk and send as download
            $absolutePath = $disk->path($ticket->attachment_path);

            // Basic extension-to-MIME mapping (avoids calling mimeType on the adapter)
            $extLower = strtolower($ext);
            $mimeMap = [
                'pdf'  => 'application/pdf',
                'doc'  => 'application/msword',
                'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'xls'  => 'application/vnd.ms-excel',
                'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'png'  => 'image/png',
                'jpg'  => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'txt'  => 'text/plain',
            ];
            $mime = $mimeMap[$extLower] ?? 'application/octet-stream';

            return response()->download($absolutePath, $downloadName, [
                'Content-Type' => $mime,
                'X-Content-Type-Options' => 'nosniff',
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma' => 'no-cache',
            ], 'attachment');
        } catch (\Throwable $e) {
            Log::error('Attachment download error for ticket ' . $ticket->id . ': ' . $e->getMessage());
            abort(500, 'Gagal mengunduh lampiran.');
        }
    }

    // Guest: securely download attachment by code_tracking without requiring admin auth
    public function downloadGuestAttachment(string $code_tracking)
    {
        $ticket = Ticket::where('code_tracking', $code_tracking)->firstOrFail();

        if (!$ticket->attachment_path) {
            abort(404);
        }

        $disk = \Illuminate\Support\Facades\Storage::disk('private');
        if (!$disk->exists($ticket->attachment_path)) {
            abort(404);
        }

        $ext = pathinfo($ticket->attachment_path, PATHINFO_EXTENSION);
        $downloadName = 'lampiran_' . $ticket->code_tracking . '.' . $ext;

        try {
            $absolutePath = $disk->path($ticket->attachment_path);

            $extLower = strtolower($ext);
            $mimeMap = [
                'pdf'  => 'application/pdf',
                'doc'  => 'application/msword',
                'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'xls'  => 'application/vnd.ms-excel',
                'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'png'  => 'image/png',
                'jpg'  => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'txt'  => 'text/plain',
            ];
            $mime = $mimeMap[$extLower] ?? 'application/octet-stream';

            return response()->download($absolutePath, $downloadName, [
                'Content-Type' => $mime,
                'X-Content-Type-Options' => 'nosniff',
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma' => 'no-cache',
            ], 'attachment');
        } catch (\Throwable $e) {
            Log::error('Guest attachment download error for code ' . $code_tracking . ': ' . $e->getMessage());
            abort(500, 'Gagal mengunduh lampiran.');
        }
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
        // Base query for current filters (date range, code), used for both list and counts
        $base = \App\Models\TicketActivityLog::query()
            ->with(['ticket'])
            ->orderBy('created_at', 'desc');

        // Apply date range filters to base
        if ($request->filled('start_date')) {
            $base->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $base->whereDate('created_at', '<=', $request->end_date);
        }

        // Apply ticket code filter to base
        if ($request->filled('code_tracking')) {
            $code = $request->code_tracking;
            $base->whereHas('ticket', function ($q) use ($code) {
                $q->where('code_tracking', 'like', "%{$code}%");
            });
        }

        // Derive the listing query from base and then apply action filter (if any)
        $query = (clone $base);
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Compute counts per action (respecting date/code filters, but not the action filter)
        $counts = [
            'total'     => (clone $base)->count(),
            'accepted'  => (clone $base)->where('action', 'accepted')->count(),
            'rejected'  => (clone $base)->where('action', 'rejected')->count(),
            'completed' => (clone $base)->where('action', 'completed')->count(),
        ];

        $activities = $query->paginate(6)->appends($request->query());

        return view('admin.tickets.activity', compact('activities', 'counts'));
    }

    public function process(Request $request)
    {
        // Build query for accepted tickets with search and pagination
        $query = Ticket::where('status', 'diterima/approved')
            ->with(['layanan', 'kecamatan']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code_tracking', 'like', "%{$search}%")
                    ->orWhere('nama_pelapor', 'like', "%{$search}%")
                    ->orWhere('judul', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by layanan
        if ($request->filled('layanan_id')) {
            $query->where('layanan_id', $request->layanan_id);
        }

        // Get paginated accepted tickets
        $acceptedTickets = $query->orderBy('accepted_at', 'asc')->paginate(10);

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

        // Get layanan list for filter dropdown
        $layananList = \App\Models\MasterLayanan::orderBy('name')->get();

        return view('admin.tickets.process', compact(
            'acceptedTickets',
            'completedToday',
            'totalWorkTimeToday',
            'layananList'
        ));
    }

    public function complete(Ticket $ticket)
    {
        request()->validate([
            'resolution_notes' => 'required|string|min:5|max:3000',
        ]);

        $oldStatus = $ticket->status;
        $resolutionNotes = request()->input('resolution_notes');
        $ticket->status = 'selesai/completed';
        $ticket->resolution_notes = $resolutionNotes;
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

    /**
     * Build calendar events array from WorkSession or Ticket data within a date range.
     *
     * Request accepts optional 'start' and 'end' (Y-m-d or ISO8601). Returns list of events for FullCalendar.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getCalendarData(Request $request): JsonResponse
    {
        // Normalize date inputs to Carbon instances
        $startInput = $request->input('start');
        $endInput = $request->input('end');
        $start = $startInput ? Carbon::parse($startInput) : now()->startOfMonth();
        $end = $endInput ? Carbon::parse($endInput) : now()->endOfMonth();

        $events = [];

        // First, try to get work sessions data grouped by started_at (for duration/active metrics)
        $workSessionsByDate = WorkSession::with(['ticket'])
            ->whereBetween('started_at', [$start, $end])
            ->where('status', 'completed')
            ->whereNotNull('started_at')
            ->get()
            ->groupBy(function (WorkSession $session) {
                return Carbon::parse($session->started_at)->format('Y-m-d');
            });

        // Get in-progress work sessions grouped by started_at
        $inProgressSessionsByDate = WorkSession::with(['ticket'])
            ->whereBetween('started_at', [$start, $end])
            ->whereIn('status', ['active', 'paused'])
            ->whereNotNull('started_at')
            ->get()
            ->groupBy(function (WorkSession $session) {
                return Carbon::parse($session->started_at)->format('Y-m-d');
            });

        // Additional: completed sessions grouped by completed_at to count tickets finished per day (even if started earlier)
        $completedSessionsByCompletedDate = WorkSession::with(['ticket'])
            ->whereBetween('completed_at', [$start, $end])
            ->where('status', 'completed')
            ->whereNotNull('completed_at')
            ->get()
            ->groupBy(function (WorkSession $session) {
                return Carbon::parse($session->completed_at)->format('Y-m-d');
            });

        // Combine all dates that have any activity (start/in-progress/completed)
        $allDates = collect($workSessionsByDate->keys())
            ->merge($inProgressSessionsByDate->keys())
            ->merge($completedSessionsByCompletedDate->keys())
            ->unique()
            ->values();

        // Build resolved tickets per-day only once to avoid per-iteration queries
        $resolvedTicketsByDate = Ticket::whereNotNull('resolved_at')
            ->whereBetween('resolved_at', [$start, $end])
            ->get(['id', 'resolved_at'])
            ->groupBy(function ($t) {
                return Carbon::parse($t->resolved_at)->format('Y-m-d');
            });

        foreach ($allDates as $date) {
            $completed = $workSessionsByDate->get($date, collect());
            $inProgress = $inProgressSessionsByDate->get($date, collect());

            $completedDuration = $completed->sum('duration');
            $inProgressDuration = $this->calculateInProgressDuration($inProgress);
            $totalDuration = $completedDuration + $inProgressDuration;

            $completedTicketIds = $completed->pluck('ticket_id');
            $inProgressTicketIds = $inProgress->pluck('ticket_id');
            $ticketCountTotal = $completedTicketIds->merge($inProgressTicketIds)->unique()->count();

            $formattedTime = $this->formatDuration($totalDuration);

            // Use ticket resolved_at per day as the ONLY source for completed ticket counts
            $completedTicketIdsByCompletedDate = collect($resolvedTicketsByDate->get($date, []))
                ->pluck('id')
                ->unique();

            $events[] = [
                'id' => 'work-' . $date,
                'title' => sprintf('%s (%d tiket)', $formattedTime, $ticketCountTotal),
                'start' => $date,
                'backgroundColor' => $this->getColorByDuration($totalDuration),
                'borderColor' => $this->getColorByDuration($totalDuration),
                'extendedProps' => [
                    'type' => 'work_session',
                    'duration' => $totalDuration,
                    'formatted_duration' => $formattedTime,
                    'ticket_count' => $ticketCountTotal,
                    'sessions_count' => $completed->count() + $inProgress->count(),
                    'completed_duration' => $completedDuration,
                    'formatted_completed_duration' => $this->formatDuration($completedDuration),
                    'in_progress_duration' => $inProgressDuration,
                    'formatted_in_progress_duration' => $this->formatDuration($inProgressDuration),
                    'completed_ticket_count' => $completedTicketIds->unique()->count(),
                    'completed_ticket_count_by_completed_date' => $completedTicketIdsByCompletedDate->count(),
                    'in_progress_ticket_count' => $inProgressTicketIds->unique()->count(),
                ]
            ];
        }

        // FALLBACK: If no work sessions, use ticket data
        if (empty($events)) {
            $events = $this->getTicketFallbackEvents($start, $end);
        }



        return response()->json($events);
    }

    /**
     * Calculate total duration for in-progress work sessions
     */
    private function calculateInProgressDuration($inProgressSessions)
    {
        return $inProgressSessions->sum(function ($session) {
            if ($session->status === 'active') {
                return ($session->duration ?? 0) + now()->diffInSeconds($session->started_at);
            }
            return $session->duration ?? 0;
        });
    }

    /**
     * Get fallback events from ticket data when work sessions are not available
     */
    private function getTicketFallbackEvents($start, $end)
    {
        $events = [];

        $ticketsByDate = Ticket::where('status', '!=', 'pending')
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('accepted_at', [$start, $end])
                    ->orWhereBetween('resolved_at', [$start, $end]);
            })
            ->whereNotNull('accepted_at')
            ->get()
            ->groupBy(function (Ticket $ticket) {
                // Group by resolved date if available, otherwise by accepted date
                if ($ticket->resolved_at) {
                    return $ticket->resolved_at->format('Y-m-d');
                }
                if ($ticket->accepted_at) {
                    return $ticket->accepted_at->format('Y-m-d');
                }
                return null;
            })
            ->filter(); // Remove null keys

        foreach ($ticketsByDate as $date => $tickets) {
            $totalDuration = $this->calculateTicketsDuration($tickets);
            $ticketCount = $tickets->count();
            $formattedTime = $this->formatDuration($totalDuration);

            if ($totalDuration > 0) {
                $events[] = [
                    'id' => 'ticket-' . $date,
                    'title' => sprintf('%s (%d tiket)', $formattedTime, $ticketCount),
                    'start' => $date,
                    'backgroundColor' => $this->getColorByDuration($totalDuration),
                    'borderColor' => $this->getColorByDuration($totalDuration),
                    'extendedProps' => [
                        'type' => 'ticket_fallback',
                        'duration' => $totalDuration,
                        'ticket_count' => $ticketCount,
                        'sessions_count' => $ticketCount,
                        'formatted_duration' => $formattedTime,
                        // count of completed tickets strictly by resolved_at on this date
                        'completed_ticket_count_by_completed_date' => $tickets->whereNotNull('resolved_at')->count(),
                        'completed_ticket_count' => $tickets->whereNotNull('resolved_at')->count(),
                    ]
                ];
            }
        }

        return $events;
    }

    /**
     * Calculate total duration for a collection of tickets
     */
    private function calculateTicketsDuration($tickets)
    {
        $totalDuration = 0;

        foreach ($tickets as $ticket) {
            if ($ticket->accepted_at && $ticket->resolved_at) {
                $totalDuration += $ticket->accepted_at->diffInSeconds($ticket->resolved_at);
            } else if ($ticket->accepted_at) {
                // For ongoing tickets, calculate time from accepted_at to now
                $totalDuration += $ticket->accepted_at->diffInSeconds(now());
            }
        }

        return $totalDuration;
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
            // Use work sessions data (separate completed vs in-progress)
            $completedSessions = $workSessions->where('status', 'completed');
            $inProgressSessions = $workSessions->whereIn('status', ['active', 'paused']);

            $completedDuration = $completedSessions->sum('duration');
            $inProgressDuration = $inProgressSessions->sum(function ($s) {
                if ($s->status === 'active') {
                    return ($s->duration ?? 0) + now()->diffInSeconds($s->started_at);
                }
                return $s->duration ?? 0;
            });
            $totalDuration = $completedDuration + $inProgressDuration;

            $dailyStats = [
                'total_duration' => $totalDuration,
                'formatted_duration' => $this->formatDuration($totalDuration),
                'total_sessions' => $workSessions->count(),
                'completed_sessions' => $completedSessions->count(),
                'in_progress_sessions' => $inProgressSessions->count(),
                'completed_duration' => $completedDuration,
                'formatted_completed_duration' => $this->formatDuration($completedDuration),
                'in_progress_duration' => $inProgressDuration,
                'formatted_in_progress_duration' => $this->formatDuration($inProgressDuration),
                'unique_tickets' => $workSessions->pluck('ticket_id')->unique()->count(),
            ];

            $formattedSessions = $workSessions->map(function ($session) {
                $startAt = $session->started_at ? $session->started_at->locale('id')->translatedFormat('d M Y H:i') : '-';
                $endAt = $session->completed_at ? $session->completed_at->locale('id')->translatedFormat('d M Y H:i') : null;

                return [
                    'id' => $session->id,
                    'ticket_id' => $session->ticket_id,
                    'ticket_code' => optional($session->ticket)->code_tracking ?? '-',
                    'ticket_title' => optional($session->ticket)->judul ?? 'No Title',
                    'admin_name' => optional($session->admin)->name ?? 'Unknown Admin',
                    'started_at' => $startAt,
                    'completed_at' => $endAt ?? 'Sedang diproses',
                    'duration' => $this->formatDuration($session->status === 'active' ? ($session->duration ?? 0) + now()->diffInSeconds($session->started_at) : ($session->duration ?? 0)),
                    'status' => $session->status,
                    'time_range' => $endAt ? "{$startAt} — {$endAt}" : "Mulai {$startAt} — Sedang diproses"
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
            $completedDuration = 0;
            $inProgressDuration = 0;
            $formattedSessions = collect();

            foreach ($tickets as $ticket) {
                $duration = 0;
                if ($ticket->accepted_at && $ticket->resolved_at) {
                    $duration = $ticket->accepted_at->diffInSeconds($ticket->resolved_at);
                    $completedDuration += $duration;
                } else if ($ticket->accepted_at) {
                    $duration = $ticket->accepted_at->diffInSeconds(now());
                    $inProgressDuration += $duration;
                }

                $totalDuration += $duration;

                $startTime = $ticket->accepted_at->format('H:i');
                $endTime = $ticket->resolved_at ? $ticket->resolved_at->format('H:i') : 'Ongoing';

                $formattedSessions->push([
                    'id' => 'ticket-' . $ticket->id,
                    'ticket_id' => $ticket->id,
                    'ticket_code' => $ticket->code_tracking,
                    'ticket_title' => $ticket->judul ?? 'No Title',
                    'admin_name' => 'System',
                    'started_at' => $ticket->accepted_at?->locale('id')->translatedFormat('d M Y H:i') ?? '-',
                    'completed_at' => $ticket->resolved_at ? $ticket->resolved_at->locale('id')->translatedFormat('d M Y H:i') : 'Sedang diproses',
                    'duration' => $this->formatDuration($duration),
                    'status' => $ticket->status,
                    'time_range' => $ticket->resolved_at ? $ticket->accepted_at->locale('id')->translatedFormat('d M Y H:i') . ' — ' . $ticket->resolved_at->locale('id')->translatedFormat('d M Y H:i') : 'Mulai ' . ($ticket->accepted_at?->locale('id')->translatedFormat('d M Y H:i') ?? '-') . ' — Sedang diproses'
                ]);
            }

            $completedCount = $tickets->where('status', 'selesai/completed')->count();
            $inProgressCount = $tickets->count() - $completedCount;

            $dailyStats = [
                'total_duration' => $totalDuration,
                'formatted_duration' => $this->formatDuration($totalDuration),
                'total_sessions' => $tickets->count(),
                'completed_sessions' => $completedCount,
                'in_progress_sessions' => $inProgressCount,
                'completed_duration' => $completedDuration,
                'formatted_completed_duration' => $this->formatDuration($completedDuration),
                'in_progress_duration' => $inProgressDuration,
                'formatted_in_progress_duration' => $this->formatDuration($inProgressDuration),
                'unique_tickets' => $tickets->count(),
            ];
        }

        return response()->json([
            'date' => $date->locale('id')->translatedFormat('d F Y'),
            'day_name' => $date->locale('id')->translatedFormat('l'),
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
