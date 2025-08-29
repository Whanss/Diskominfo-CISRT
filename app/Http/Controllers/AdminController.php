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

        // Update ticket counts to match our new status flow
        $totalTickets = \App\Models\Ticket::count();
        $pendingTickets = \App\Models\Ticket::where('status', 'pending')->count();
        $acceptedTickets = \App\Models\Ticket::where('status', 'diterima/approved')->count();
        $resolvedTickets = \App\Models\Ticket::where('status', 'selesai/completed')->count();
        $rejectedTickets = \App\Models\Ticket::where('status', 'ditolak/rejected')->count();

        // SLA Compliance calculations
        $slaCompliance = $this->calculateSLACompliance();

        // Processing time analytics
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
     * Get processing time analytics for specific month
     */
    public function getProcessingTimeAnalytics($selectedMonth = null)
    {
        if ($selectedMonth) {
            $selectedDate = Carbon::createFromFormat('Y-m', $selectedMonth);
            $startDate = $selectedDate->startOfMonth();
            $endDate = $selectedDate->endOfMonth();

            // Get daily data for the selected month
            $days = [];
            $avgProcessingTimes = [];

            $daysInMonth = $endDate->day;
            for ($i = 1; $i <= $daysInMonth; $i++) {
                $date = $selectedDate->copy()->day($i);
                $days[] = $date->format('j');

                // Calculate average processing time for completed tickets on this date
                $avgTime = \App\Models\Ticket::whereDate('resolved_at', $date)
                    ->where('status', 'selesai/completed')
                    ->get()
                    ->avg(function ($ticket) {
                        return $ticket->total_processing_time / 3600; // Convert seconds to hours
                    });

                $avgProcessingTimes[] = $avgTime ? round($avgTime, 1) : 0;
            }
        } else {
            // Default: last 7 days
            $last7Days = [];
            $avgProcessingTimes = [];

            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $last7Days[] = $date->format('M j');

                // Calculate average processing time for completed tickets on this date
                $avgTime = \App\Models\Ticket::whereDate('resolved_at', $date)
                    ->where('status', 'selesai/completed')
                    ->get()
                    ->avg(function ($ticket) {
                        return $ticket->total_processing_time / 3600; // Convert seconds to hours
                    });

                $avgProcessingTimes[] = $avgTime ? round($avgTime, 1) : 0;
            }

            $days = $last7Days;
        }

        return [
            'labels' => $days,
            'data' => $avgProcessingTimes
        ];
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
     * Navigate to previous month
     */
    public function navigatePreviousMonth(Request $request)
    {
        $currentMonth = $request->input('current_month');
        $monthsToShow = $request->input('months_to_show', 6);

        $currentDate = Carbon::createFromFormat('Y-m', $currentMonth);
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
     * Navigate to next month
     */
    public function navigateNextMonth(Request $request)
    {
        $currentMonth = $request->input('current_month');
        $monthsToShow = $request->input('months_to_show', 6);

        $currentDate = Carbon::createFromFormat('Y-m', $currentMonth);
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
            $resolutionTime = $ticket->status === 'resolved' && $ticket->updated_at
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

    public function acceptTicket(Request $request, $id)
    {
        $ticket = \App\Models\Ticket::findOrFail($id);

        $request->validate([
            'assigned_to' => 'required|string|max:255',
            'priority' => 'required|in:low,medium,high,critical'
        ]);

        $ticket->update([
            'status' => 'diterima/approved',
            'assigned_to' => $request->assigned_to,
            'priority' => $request->priority,
            'accepted_at' => Carbon::now(),
            'accepted_by' => Auth::guard('admin')->user()->name
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
            'status' => 'ditolak/rejected',
            'rejection_reason' => $request->rejection_reason,
            'rejected_at' => Carbon::now(),
            'rejected_by' => Auth::guard('admin')->user()->name
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
            'status' => 'resolved',
            'resolution_notes' => $request->resolution_notes,
            'resolution_category' => $request->resolution_category,
            'resolved_at' => Carbon::now(),
            'resolved_by' => Auth::guard('admin')->user()->name
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

    public function getRecentTickets()
    {
        $recentTickets = \App\Models\Ticket::with(['layanan'])
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        $html = '';
        foreach ($recentTickets as $ticket) {
            $statusBadge = '';
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

        return response($html);
    }
}
