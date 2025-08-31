<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/admin/dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }

    public function dashboard(Request $request)
    {
        // Get selected month from request or default to current month
        $selectedMonth = $request->input('month', Carbon::now()->format('Y-m'));
        $monthsToShow = $request->input('months_to_show', 6);

        // Generate month options for the dropdown (last 12 months + next 3 months)
        $monthOptions = [];
        $startDate = Carbon::now()->subMonths(12);
        $endDate = Carbon::now()->addMonths(3);

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addMonth()) {
            $monthOptions[] = [
                'value' => $date->format('Y-m'),
                'label' => $date->format('F Y')
            ];
        }

        // STANDARDIZED STATUS VALUES - Fixed inconsistency
        $totalTickets = \App\Models\Ticket::count();
        $pendingTickets = \App\Models\Ticket::where('status', 'pending')->count();
        $acceptedTickets = \App\Models\Ticket::where('status', 'diterima/approved')->count();
        $resolvedTickets = \App\Models\Ticket::where('status', 'selesai/completed')->count();
        $rejectedTickets = \App\Models\Ticket::where('status', 'ditolak/rejected')->count();

        // SLA Compliance calculations
        $slaCompliance = $this->calculateSLACompliance();

        // Processing time analytics - FIXED: Only use resolved tickets
        $processingTimeData = $this->getProcessingTimeAnalytics($selectedMonth);

        // Recent tickets for the table
        $recentTickets = \App\Models\Ticket::with(['layanan'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get ticket counts per month for the selected range
        $chartData = $this->getChartData($selectedMonth, $monthsToShow);

        return view('admin.admin_dashboard', compact(
            'totalTickets',
            'pendingTickets',
            'acceptedTickets',
            'resolvedTickets',
            'rejectedTickets',
            'recentTickets',
            'slaCompliance',
            'processingTimeData',
            'selectedMonth',
            'monthsToShow',
            'monthOptions'
        ) + $chartData);
    }

    /**
     * Get chart data for a specific month range
     */
    public function getChartData($selectedMonth = null, $monthsToShow = 6)
    {
        if ($selectedMonth) {
            // Parse the selected month
            $selectedDate = Carbon::createFromFormat('Y-m', $selectedMonth);
            $startDate = $selectedDate->copy()->subMonths($monthsToShow - 1)->startOfMonth();
            $endDate = $selectedDate->copy()->endOfMonth();
        } else {
            // Default: last 6 months
            $startDate = Carbon::now()->subMonths($monthsToShow - 1)->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();
        }

        $monthlyTickets = \App\Models\Ticket::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('count(*) as total'),
            DB::raw('sum(case when status = "selesai/completed" then 1 else 0 end) as resolved'),
            DB::raw('sum(case when status = "pending" then 1 else 0 end) as pending'),
            DB::raw('sum(case when status = "diterima/approved" then 1 else 0 end) as accepted'),
            DB::raw('sum(case when status = "ditolak/rejected" then 1 else 0 end) as rejected')
        )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Prepare data for charts
        $months = [];
        $totalCounts = [];
        $resolvedCounts = [];
        $pendingCounts = [];
        $acceptedCounts = [];
        $rejectedCounts = [];

        for ($i = 0; $i < $monthsToShow; $i++) {
            $month = $startDate->copy()->addMonths($i)->format('M Y');
            $monthKey = $startDate->copy()->addMonths($i)->format('Y-m');
            $months[] = $month;

            $data = $monthlyTickets->firstWhere('month', $monthKey);
            $totalCounts[] = $data ? $data->total : 0;
            $resolvedCounts[] = $data ? $data->resolved : 0;
            $pendingCounts[] = $data ? $data->pending : 0;
            $acceptedCounts[] = $data ? $data->accepted : 0;
            $rejectedCounts[] = $data ? $data->rejected : 0;
        }

        return [
            'months' => $months,
            'totalCounts' => $totalCounts,
            'resolvedCounts' => $resolvedCounts,
            'pendingCounts' => $pendingCounts,
            'acceptedCounts' => $acceptedCounts,
            'rejectedCounts' => $rejectedCounts,
            'currentMonth' => $selectedMonth ?: Carbon::now()->format('Y-m')
        ];
    }

    /**
     * AJAX endpoint to get chart data for specific month
     */
    public function getChartDataAjax(Request $request)
    {
        $selectedMonth = $request->input('month');
        $monthsToShow = $request->input('months_to_show', 6);

        $chartData = $this->getChartData($selectedMonth, $monthsToShow);

        return response()->json([
            'success' => true,
            'data' => $chartData
        ]);
    }

    /**
     * Get processing time analytics for specific month - FIXED: Calculate from timestamps
     */
    public function getProcessingTimeAnalytics($selectedMonth = null)
    {
        if ($selectedMonth) {
            $selectedDate = Carbon::createFromFormat('Y-m', $selectedMonth);
            $startDate = $selectedDate->startOfMonth();
            $endDate = $selectedDate->endOfMonth();

            $resolvedTickets = \App\Models\Ticket::where('status', 'selesai/completed')
                ->whereNotNull('resolved_at')
                ->whereNotNull('created_at')
                ->whereBetween('resolved_at', [$startDate, $endDate])
                ->get();

            $processingTimes = [];
            foreach ($resolvedTickets as $ticket) {
                $createdAt = Carbon::parse($ticket->created_at);
                $resolvedAt = Carbon::parse($ticket->resolved_at);
                $processingTimeHours = $createdAt->diffInHours($resolvedAt);

                if ($processingTimeHours > 0) {
                    $processingTimes[] = $processingTimeHours;
                }
            }

            // Generate daily data for the selected month
            $days = [];
            $avgProcessingTimes = [];

            $daysInMonth = $endDate->day;
            for ($i = 1; $i <= $daysInMonth; $i++) {
                $date = $selectedDate->copy()->day($i);
                $days[] = $date->format('j');

                // Calculate average processing time for completed tickets on this date
                $dayTickets = $resolvedTickets->filter(function ($ticket) use ($date) {
                    return Carbon::parse($ticket->resolved_at)->isSameDay($date);
                });

                $avgTime = 0;
                if ($dayTickets->count() > 0) {
                    $totalHours = 0;
                    foreach ($dayTickets as $ticket) {
                        $createdAt = Carbon::parse($ticket->created_at);
                        $resolvedAt = Carbon::parse($ticket->resolved_at);
                        $totalHours += $createdAt->diffInHours($resolvedAt);
                    }
                    $avgTime = round($totalHours / $dayTickets->count(), 1);
                }

                $avgProcessingTimes[] = $avgTime;
            }

            return [
                'labels' => $days,
                'data' => $avgProcessingTimes,
                'processingTimes' => $processingTimes, // Raw processing times for pie chart
                'totalResolvedTickets' => count($processingTimes) // Actual count of resolved tickets with processing times
            ];
        } else {
            // Default: last 7 days
            $last7Days = [];
            $avgProcessingTimes = [];
            $allProcessingTimes = [];

            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $last7Days[] = $date->format('M j');

                $dayTickets = \App\Models\Ticket::where('status', 'selesai/completed')
                    ->whereNotNull('resolved_at')
                    ->whereNotNull('created_at')
                    ->whereDate('resolved_at', $date)
                    ->get();

                $avgTime = 0;
                if ($dayTickets->count() > 0) {
                    $totalHours = 0;
                    foreach ($dayTickets as $ticket) {
                        $createdAt = Carbon::parse($ticket->created_at);
                        $resolvedAt = Carbon::parse($ticket->resolved_at);
                        $hours = $createdAt->diffInHours($resolvedAt);
                        $totalHours += $hours;

                        if ($hours > 0) {
                            $allProcessingTimes[] = $hours;
                        }
                    }
                    $avgTime = round($totalHours / $dayTickets->count(), 1);
                }

                $avgProcessingTimes[] = $avgTime;
            }

            return [
                'labels' => $last7Days,
                'data' => $avgProcessingTimes,
                'processingTimes' => $allProcessingTimes,
                'totalResolvedTickets' => count($allProcessingTimes)
            ];
        }
    }

    /**
     * AJAX endpoint to get processing time analytics for specific month
     */
    public function getProcessingTimeAnalyticsAjax(Request $request)
    {
        $selectedMonth = $request->input('month');
        $processingTimeData = $this->getProcessingTimeAnalytics($selectedMonth);

        return response()->json([
            'success' => true,
            'data' => $processingTimeData
        ]);
    }

    /**
     * Navigate to previous month - FIXED: Remove overly restrictive boundary checks
     */
    public function navigatePreviousMonth(Request $request)
    {
        $currentMonth = $request->input('current_month');
        $monthsToShow = $request->input('months_to_show', 6);

        $currentDate = Carbon::createFromFormat('Y-m', $currentMonth);

        $minDate = Carbon::create(2015, 1, 1); // More reasonable minimum date
        if ($currentDate->copy()->subMonth()->lt($minDate)) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat navigasi lebih jauh ke belakang dari tahun 2015'
            ]);
        }

        $newMonth = $currentDate->subMonth()->format('Y-m');

        $chartData = $this->getChartData($newMonth, $monthsToShow);
        $processingTimeData = $this->getProcessingTimeAnalytics($newMonth);

        return response()->json([
            'success' => true,
            'newMonth' => $newMonth,
            'data' => $chartData,
            'processingTimeData' => $processingTimeData
        ]);
    }

    /**
     * Navigate to next month - FIXED: Remove overly restrictive boundary checks
     */
    public function navigateNextMonth(Request $request)
    {
        $currentMonth = $request->input('current_month');
        $monthsToShow = $request->input('months_to_show', 6);

        $currentDate = Carbon::createFromFormat('Y-m', $currentMonth);

        $maxDate = Carbon::now()->addYears(2); // Allow navigation up to 2 years in future
        if ($currentDate->copy()->addMonth()->gt($maxDate)) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat navigasi lebih dari 2 tahun ke depan'
            ]);
        }

        $newMonth = $currentDate->addMonth()->format('Y-m');

        $chartData = $this->getChartData($newMonth, $monthsToShow);
        $processingTimeData = $this->getProcessingTimeAnalytics($newMonth);

        return response()->json([
            'success' => true,
            'newMonth' => $newMonth,
            'data' => $chartData,
            'processingTimeData' => $processingTimeData
        ]);
    }

    private function calculateSLACompliance()
    {
        $now = Carbon::now();
        $tickets = \App\Models\Ticket::all();

        $totalTickets = $tickets->count();
        $slaCompliant = 0;
        $responseTimeCompliant = 0;
        $resolutionTimeCompliant = 0;

        foreach ($tickets as $ticket) {
            $createdAt = Carbon::parse($ticket->created_at);
            $responseTime = $ticket->accepted_at ? Carbon::parse($ticket->accepted_at)->diffInHours($createdAt) : null;
            $resolutionTime = $ticket->status === 'selesai/completed' && $ticket->updated_at
                ? Carbon::parse($ticket->updated_at)->diffInHours($createdAt)
                : null;

            // SLA targets: 4 hours response, 24 hours resolution
            if ($responseTime && $responseTime <= 4) {
                $responseTimeCompliant++;
            }

            if ($resolutionTime && $resolutionTime <= 24) {
                $resolutionTimeCompliant++;
            }

            if (($responseTime && $responseTime <= 4) && ($resolutionTime && $resolutionTime <= 24)) {
                $slaCompliant++;
            }
        }

        return [
            'overall' => $totalTickets > 0 ? round(($slaCompliant / $totalTickets) * 100, 1) : 0,
            'response_time' => $totalTickets > 0 ? round(($responseTimeCompliant / $totalTickets) * 100, 1) : 0,
            'resolution_time' => $totalTickets > 0 ? round(($resolutionTimeCompliant / $totalTickets) * 100, 1) : 0,
        ];
    }

    public function getRealtimeStats()
    {
        $stats = [
            'total_tickets' => \App\Models\Ticket::count(),
            'pending_tickets' => \App\Models\Ticket::where('status', 'pending')->count(),
            'accepted_tickets' => \App\Models\Ticket::where('status', 'diterima/approved')->count(),
            'resolved_tickets' => \App\Models\Ticket::where('status', 'selesai/completed')->count(),
            'rejected_tickets' => \App\Models\Ticket::where('status', 'ditolak/rejected')->count(),
            'sla_compliance' => $this->calculateSLACompliance(),
            'processing_time_data' => $this->getProcessingTimeAnalytics(),
        ];

        return response()->json($stats);
    }

    // FIXED: Made parameters optional with defaults
    public function acceptTicket(Request $request, $id)
    {
        $ticket = \App\Models\Ticket::findOrFail($id);

        // Optional validation - use defaults if not provided
        $assignedTo = $request->input('assigned_to', 'Admin ' . (Auth::guard('admin')->user()->name ?? 'System'));
        $priority = $request->input('priority', 'medium');

        // Validate only if provided
        if ($request->has('assigned_to')) {
            $request->validate([
                'assigned_to' => 'string|max:255'
            ]);
        }

        if ($request->has('priority')) {
            $request->validate([
                'priority' => 'in:low,medium,high,critical'
            ]);
        }

        $ticket->update([
            'status' => 'diterima/approved', // STANDARDIZED STATUS
            'assigned_to' => $assignedTo,
            'priority' => $priority,
            'accepted_at' => Carbon::now(),
            'accepted_by' => Auth::guard('admin')->user()->name ?? 'System'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ticket berhasil diterima dan ditugaskan'
        ]);
    }

    public function rejectTicket(Request $request, $id)
    {
        $ticket = \App\Models\Ticket::findOrFail($id);

        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $ticket->update([
            'status' => 'ditolak/rejected', // STANDARDIZED STATUS
            'rejection_reason' => $request->rejection_reason,
            'rejected_at' => Carbon::now(),
            'rejected_by' => Auth::guard('admin')->user()->name ?? 'System'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ticket berhasil ditolak'
        ]);
    }

    public function resolveTicket(Request $request, $id)
    {
        $ticket = \App\Models\Ticket::findOrFail($id);

        $request->validate([
            'resolution_notes' => 'required|string|max:1000',
            'resolution_category' => 'required|in:resolved,escalated,duplicate'
        ]);

        $ticket->update([
            'status' => 'selesai/completed', // STANDARDIZED STATUS - changed from 'resolved'
            'resolution_notes' => $request->resolution_notes,
            'resolution_category' => $request->resolution_category,
            'resolved_at' => Carbon::now(),
            'resolved_by' => Auth::guard('admin')->user()->name ?? 'System'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ticket berhasil diselesaikan'
        ]);
    }

    public function getTicketDetails($id)
    {
        $ticket = \App\Models\Ticket::with(['layanan', 'kabupaten', 'kecamatan'])->findOrFail($id);

        return response()->json([
            'ticket' => $ticket,
            'processing_time' => $ticket->created_at ?
                Carbon::parse($ticket->created_at)->diffForHumans() : null
        ]);
    }

    // FIXED: Return proper JSON format
    public function getRecentTickets()
    {
        $recentTickets = \App\Models\Ticket::with(['layanan'])
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        $html = '';
        foreach ($recentTickets as $ticket) {
            $statusBadge = '';
            // STANDARDIZED STATUS HANDLING
            switch ($ticket->status) {
                case 'pending':
                    $statusBadge = '<span class="badge warning">⏳ Pending</span>';
                    break;
                case 'diterima/approved':
                    $statusBadge = '<span class="badge success">✅ Diterima/Approved</span>';
                    break;
                case 'selesai/completed':
                    $statusBadge = '<span class="badge info">🎯 Resolved</span>';
                    break;
                case 'ditolak/rejected':
                    $statusBadge = '<span class="badge danger">❌ Ditolak/Rejected</span>';
                    break;
                default:
                    $statusBadge = '<span class="badge secondary">' . ucfirst($ticket->status) . '</span>';
            }

            $processingTime = $ticket->accepted_at
                ? '<span style="color: var(--green-600);">' . $ticket->created_at->diffForHumans($ticket->accepted_at) . '</span>'
                : '<span style="color: var(--gray-500);">Not started</span>';

            $actions = '<div class="d-flex gap-2">
                <button class="btn btn-info btn-sm" onclick="viewTicketDetails(\'' . $ticket->id . '\')">
                    <i class="fas fa-eye"></i>
                </button>';

            if ($ticket->status == 'pending') {
                $actions .= '<button class="btn btn-success btn-sm" onclick="acceptTicket(\'' . $ticket->id . '\')">
                    <i class="fas fa-check"></i>
                </button>
                <button class="btn btn-danger btn-sm" onclick="rejectTicket(\'' . $ticket->id . '\')">
                    <i class="fas fa-times"></i>
                </button>';
            }

            $actions .= '</div>';

            $html .= '<tr>
                <td><span class="badge primary">' . $ticket->code_tracking . '</span></td>
                <td>' . \Illuminate\Support\Str::limit($ticket->judul ?? 'No Title', 30) . '</td>
                <td>' . ($ticket->nama_pelapor ?? 'Anonymous') . '</td>
                <td>' . $statusBadge . '</td>
                <td>' . $ticket->created_at->diffForHumans() . '</td>
                <td>' . $processingTime . '</td>
                <td>' . $actions . '</td>
            </tr>';
        }

        // Return JSON instead of plain HTML
        return response()->json([
            'success' => true,
            'html' => $html
        ]);
    }
}
