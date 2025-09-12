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

        // Collect optional filters from request
        $filters = [
            'kecamatan_id' => $request->input('kecamatan_id'),
            'layanan_id' => $request->input('layanan_id'),
            'layanan_category_id' => $request->input('layanan_category_id'),
        ];

        // Processing time analytics - use filters
        $processingTimeData = $this->getProcessingTimeAnalytics($selectedMonth, $filters);

        // Recent tickets for the table
        $recentTickets = \App\Models\Ticket::with(['layanan'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Dropdown data for filters
        $kecamatanList = \App\Models\Kecamatan::orderBy('nama')->get(['id', 'nama']);
        $layananList = \App\Models\MasterLayanan::where('is_active', true)->orderBy('name')->get(['id', 'name']);

        // Get ticket counts per month for the selected range
        $chartData = $this->getChartData($selectedMonth, $monthsToShow, $filters);

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
            'monthOptions',
            'kecamatanList',
            'layananList'
        ) + $chartData);
    }

    /**
     * Get chart data for a specific month - now shows daily data within the month
     */
    public function getChartData($selectedMonth = null, $monthsToShow = 6, $filters = [])
    {
        if ($selectedMonth) {
            // Parse the selected month and get daily data for that month
            $selectedDate = Carbon::createFromFormat('Y-m', $selectedMonth);
            $startDate = $selectedDate->copy()->startOfMonth();
            $endDate = $selectedDate->copy()->endOfMonth();

            // Get daily tickets for the selected month (by created_at)
            $dailyTicketsQuery = \App\Models\Ticket::select(
                DB::raw('DAY(created_at) as day'),
                DB::raw('count(*) as total'),
                DB::raw('sum(case when status = "pending" then 1 else 0 end) as pending'),
                DB::raw('sum(case when status = "diterima/approved" then 1 else 0 end) as accepted'),
                DB::raw('sum(case when status = "ditolak/rejected" then 1 else 0 end) as rejected')
            )
                ->whereBetween('created_at', [$startDate, $endDate]);

            // Apply optional filters
            if (!empty($filters['kecamatan_id'])) {
                $dailyTicketsQuery->where('kecamatan_id', $filters['kecamatan_id']);
            }
            if (!empty($filters['layanan_id'])) {
                $dailyTicketsQuery->where('layanan_id', $filters['layanan_id']);
            }
            if (!empty($filters['layanan_category_id'])) {
                $dailyTicketsQuery->where('layanan_category_id', $filters['layanan_category_id']);
            }

            $dailyTickets = $dailyTicketsQuery
                ->groupBy('day')
                ->orderBy('day')
                ->get();

            // Get daily resolved tickets for the selected month (by resolved_at date)
            $dailyResolvedQuery = \App\Models\Ticket::select(
                DB::raw('DAY(resolved_at) as day'),
                DB::raw('COUNT(*) as resolved')
            )
                ->where('status', 'selesai/completed')
                ->whereNotNull('resolved_at')
                ->whereBetween('resolved_at', [$startDate, $endDate]);

            if (!empty($filters['kecamatan_id'])) {
                $dailyResolvedQuery->where('kecamatan_id', $filters['kecamatan_id']);
            }
            if (!empty($filters['layanan_id'])) {
                $dailyResolvedQuery->where('layanan_id', $filters['layanan_id']);
            }
            if (!empty($filters['layanan_category_id'])) {
                $dailyResolvedQuery->where('layanan_category_id', $filters['layanan_category_id']);
            }

            $dailyResolved = $dailyResolvedQuery
                ->groupBy('day')
                ->orderBy('day')
                ->get();

            // Prepare data for charts - show all days in month
            $days = [];
            $totalCounts = [];
            $resolvedCounts = [];
            $pendingCounts = [];
            $acceptedCounts = [];
            $rejectedCounts = [];

            $daysInMonth = $endDate->day;
            for ($i = 1; $i <= $daysInMonth; $i++) {
                $days[] = $i; // Just show day number

                $data = $dailyTickets->firstWhere('day', $i);
                $totalCounts[] = $data ? $data->total : 0;

                // Use resolved_at for resolved counts so they appear on the actual day of completion
                $resolvedData = $dailyResolved->firstWhere('day', $i);
                $resolvedCounts[] = $resolvedData ? $resolvedData->resolved : 0;

                $pendingCounts[] = $data ? $data->pending : 0;
                $acceptedCounts[] = $data ? $data->accepted : 0;
                $rejectedCounts[] = $data ? $data->rejected : 0;
            }

            return [
                'months' => $days, // Now contains days instead of months
                'totalCounts' => $totalCounts,
                'resolvedCounts' => $resolvedCounts,
                'pendingCounts' => $pendingCounts,
                'acceptedCounts' => $acceptedCounts,
                'rejectedCounts' => $rejectedCounts,
                'currentMonth' => $selectedMonth,
                'isDaily' => true // Flag to indicate this is daily data
            ];
        } else {
            // Default: last 6 months (monthly view)
            $startDate = Carbon::now()->subMonths($monthsToShow - 1)->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();

            // Created-based monthly aggregates (totals and non-resolved statuses)
            $monthlyCreatedQuery = \App\Models\Ticket::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('count(*) as total'),
                DB::raw('sum(case when status = "pending" then 1 else 0 end) as pending'),
                DB::raw('sum(case when status = "diterima/approved" then 1 else 0 end) as accepted'),
                DB::raw('sum(case when status = "ditolak/rejected" then 1 else 0 end) as rejected')
            )
                ->whereBetween('created_at', [$startDate, $endDate]);

            if (!empty($filters['kecamatan_id'])) {
                $monthlyCreatedQuery->where('kecamatan_id', $filters['kecamatan_id']);
            }
            if (!empty($filters['layanan_id'])) {
                $monthlyCreatedQuery->where('layanan_id', $filters['layanan_id']);
            }
            if (!empty($filters['layanan_category_id'])) {
                $monthlyCreatedQuery->where('layanan_category_id', $filters['layanan_category_id']);
            }

            $monthlyCreated = $monthlyCreatedQuery
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            // Resolved-based monthly aggregates (completed tickets counted by resolved_at)
            $monthlyResolvedQuery = \App\Models\Ticket::select(
                DB::raw('DATE_FORMAT(resolved_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as resolved')
            )
                ->where('status', 'selesai/completed')
                ->whereNotNull('resolved_at')
                ->whereBetween('resolved_at', [$startDate, $endDate]);

            if (!empty($filters['kecamatan_id'])) {
                $monthlyResolvedQuery->where('kecamatan_id', $filters['kecamatan_id']);
            }
            if (!empty($filters['layanan_id'])) {
                $monthlyResolvedQuery->where('layanan_id', $filters['layanan_id']);
            }
            if (!empty($filters['layanan_category_id'])) {
                $monthlyResolvedQuery->where('layanan_category_id', $filters['layanan_category_id']);
            }

            $monthlyResolved = $monthlyResolvedQuery
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
                $monthDate = $startDate->copy()->addMonths($i);
                $month = $monthDate->format('M Y');
                $monthKey = $monthDate->format('Y-m');
                $months[] = $month;

                $createdData = $monthlyCreated->firstWhere('month', $monthKey);
                $resolvedData = $monthlyResolved->firstWhere('month', $monthKey);

                $totalCounts[] = $createdData ? $createdData->total : 0;
                $resolvedCounts[] = $resolvedData ? $resolvedData->resolved : 0;
                $pendingCounts[] = $createdData ? $createdData->pending : 0;
                $acceptedCounts[] = $createdData ? $createdData->accepted : 0;
                $rejectedCounts[] = $createdData ? $createdData->rejected : 0;
            }

            return [
                'months' => $months,
                'totalCounts' => $totalCounts,
                'resolvedCounts' => $resolvedCounts,
                'pendingCounts' => $pendingCounts,
                'acceptedCounts' => $acceptedCounts,
                'rejectedCounts' => $rejectedCounts,
                'currentMonth' => Carbon::now()->format('Y-m'),
                'isDaily' => false // Flag to indicate this is monthly data
            ];
        }
    }

    /**
     * AJAX endpoint to get chart data for specific month
     */
    public function getChartDataAjax(Request $request)
    {
        $selectedMonth = $request->input('month');
        $monthsToShow = $request->input('months_to_show', 6);

        $filters = [
            'kecamatan_id' => $request->input('kecamatan_id'),
            'layanan_id' => $request->input('layanan_id'),
            'layanan_category_id' => $request->input('layanan_category_id'),
        ];

        $chartData = $this->getChartData($selectedMonth, $monthsToShow, $filters);

        return response()->json([
            'success' => true,
            'data' => $chartData
        ]);
    }

    /**
     * Get processing time analytics - now shows monthly average processing time
     */
    public function getProcessingTimeAnalytics($selectedMonth = null, $filters = [])
    {
        if ($selectedMonth) {
            // Show last 6 months including the selected month
            $selectedDate = Carbon::createFromFormat('Y-m', $selectedMonth);
            $startDate = $selectedDate->copy()->subMonths(5)->startOfMonth();
            $endDate = $selectedDate->copy()->endOfMonth();

            // Get monthly resolved tickets data
            $monthlyDataQuery = \App\Models\Ticket::select(
                DB::raw('DATE_FORMAT(resolved_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as resolved_count'),
                DB::raw('AVG(TIMESTAMPDIFF(HOUR, accepted_at, resolved_at)) as avg_processing_hours')
            )
                ->where('status', 'selesai/completed')
                ->whereNotNull('resolved_at')
                ->whereNotNull('accepted_at')
                ->whereBetween('resolved_at', [$startDate, $endDate]);

            if (!empty($filters['kecamatan_id'])) {
                $monthlyDataQuery->where('kecamatan_id', $filters['kecamatan_id']);
            }
            if (!empty($filters['layanan_id'])) {
                $monthlyDataQuery->where('layanan_id', $filters['layanan_id']);
            }
            if (!empty($filters['layanan_category_id'])) {
                $monthlyDataQuery->where('layanan_category_id', $filters['layanan_category_id']);
            }

            $monthlyData = $monthlyDataQuery
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            // Prepare data for charts - show last 6 months
            $months = [];
            $avgProcessingTimes = [];
            $resolvedCounts = [];
            $allProcessingTimes = [];

            for ($i = 0; $i < 6; $i++) {
                $monthDate = $startDate->copy()->addMonths($i);
                $monthKey = $monthDate->format('Y-m');
                $months[] = $monthDate->format('M Y');

                $data = $monthlyData->firstWhere('month', $monthKey);
                $avgTime = $data ? round($data->avg_processing_hours, 1) : 0;
                $resolvedCount = $data ? $data->resolved_count : 0;

                $avgProcessingTimes[] = $avgTime;
                $resolvedCounts[] = $resolvedCount;

                // Get raw processing times for pie chart (only for selected month)
                if ($monthKey === $selectedMonth) {
                    $monthTicketsQuery = \App\Models\Ticket::where('status', 'selesai/completed')
                        ->whereNotNull('resolved_at')
                        ->whereNotNull('accepted_at')
                        ->whereYear('resolved_at', $monthDate->year)
                        ->whereMonth('resolved_at', $monthDate->month);

                    if (!empty($filters['kecamatan_id'])) {
                        $monthTicketsQuery->where('kecamatan_id', $filters['kecamatan_id']);
                    }
                    if (!empty($filters['layanan_id'])) {
                        $monthTicketsQuery->where('layanan_id', $filters['layanan_id']);
                    }
                    if (!empty($filters['layanan_category_id'])) {
                        $monthTicketsQuery->where('layanan_category_id', $filters['layanan_category_id']);
                    }

                    $monthTickets = $monthTicketsQuery->get();

                    foreach ($monthTickets as $ticket) {
                        $acceptedAt = Carbon::parse($ticket->accepted_at);
                        $resolvedAt = Carbon::parse($ticket->resolved_at);
                        $processingTimeHours = $acceptedAt->diffInHours($resolvedAt);

                        if ($processingTimeHours > 0) {
                            $allProcessingTimes[] = $processingTimeHours;
                        }
                    }
                }
            }

            return [
                'labels' => $months,
                'data' => $avgProcessingTimes,
                'resolvedCounts' => $resolvedCounts, // Monthly resolved counts
                'processingTimes' => $allProcessingTimes, // Raw processing times for pie chart (selected month only)
                'totalResolvedTickets' => count($allProcessingTimes), // Count for selected month
                'isMonthly' => true // Flag to indicate this is monthly data
            ];
        } else {
            // Default: last 6 months
            $startDate = Carbon::now()->subMonths(5)->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();

            // FIXED: Define the query variable properly
            $monthlyDataQuery = \App\Models\Ticket::select(
                DB::raw('DATE_FORMAT(resolved_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as resolved_count'),
                DB::raw('AVG(TIMESTAMPDIFF(HOUR, accepted_at, resolved_at)) as avg_processing_hours')
            )
                ->where('status', 'selesai/completed')
                ->whereNotNull('resolved_at')
                ->whereNotNull('accepted_at')
                ->whereBetween('resolved_at', [$startDate, $endDate]);

            // Apply optional filters
            if (!empty($filters['kecamatan_id'])) {
                $monthlyDataQuery->where('kecamatan_id', $filters['kecamatan_id']);
            }
            if (!empty($filters['layanan_id'])) {
                $monthlyDataQuery->where('layanan_id', $filters['layanan_id']);
            }
            if (!empty($filters['layanan_category_id'])) {
                $monthlyDataQuery->where('layanan_category_id', $filters['layanan_category_id']);
            }

            $monthlyData = $monthlyDataQuery
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            $months = [];
            $avgProcessingTimes = [];
            $resolvedCounts = [];
            $allProcessingTimes = [];

            for ($i = 0; $i < 6; $i++) {
                $monthDate = $startDate->copy()->addMonths($i);
                $monthKey = $monthDate->format('Y-m');
                $months[] = $monthDate->format('M Y');

                $data = $monthlyData->firstWhere('month', $monthKey);
                $avgTime = $data ? round($data->avg_processing_hours, 1) : 0;
                $resolvedCount = $data ? $data->resolved_count : 0;

                $avgProcessingTimes[] = $avgTime;
                $resolvedCounts[] = $resolvedCount;
            }

            // Get processing times for current month for pie chart
            $currentMonthDate = Carbon::now();
            $currentMonthTickets = \App\Models\Ticket::where('status', 'selesai/completed')
                ->whereNotNull('resolved_at')
                ->whereNotNull('accepted_at')
                ->whereYear('resolved_at', $currentMonthDate->year)
                ->whereMonth('resolved_at', $currentMonthDate->month)
                ->get();

            foreach ($currentMonthTickets as $ticket) {
                $acceptedAt = Carbon::parse($ticket->accepted_at);
                $resolvedAt = Carbon::parse($ticket->resolved_at);
                $processingTimeHours = $acceptedAt->diffInHours($resolvedAt);

                if ($processingTimeHours > 0) {
                    $allProcessingTimes[] = $processingTimeHours;
                }
            }

            return [
                'labels' => $months,
                'data' => $avgProcessingTimes,
                'resolvedCounts' => $resolvedCounts,
                'processingTimes' => $allProcessingTimes,
                'totalResolvedTickets' => count($allProcessingTimes),
                'isMonthly' => true
            ];
        }
    }

    /**
     * AJAX endpoint to get processing time analytics for specific month
     */
    public function getProcessingTimeAnalyticsAjax(Request $request)
    {
        $selectedMonth = $request->input('month');
        $filters = [
            'kecamatan_id' => $request->input('kecamatan_id'),
            'layanan_id' => $request->input('layanan_id'),
            'layanan_category_id' => $request->input('layanan_category_id'),
        ];
        $processingTimeData = $this->getProcessingTimeAnalytics($selectedMonth, $filters);

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

        $filters = [
            'kecamatan_id' => $request->input('kecamatan_id'),
            'layanan_id' => $request->input('layanan_id'),
            'layanan_category_id' => $request->input('layanan_category_id'),
        ];

        $chartData = $this->getChartData($newMonth, $monthsToShow, $filters);
        $processingTimeData = $this->getProcessingTimeAnalytics($newMonth, $filters);

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

        $filters = [
            'kecamatan_id' => $request->input('kecamatan_id'),
            'layanan_id' => $request->input('layanan_id'),
            'layanan_category_id' => $request->input('layanan_category_id'),
        ];

        $chartData = $this->getChartData($newMonth, $monthsToShow, $filters);
        $processingTimeData = $this->getProcessingTimeAnalytics($newMonth, $filters);

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
                    $statusBadge = '<span class="badge warning">Pending</span>';
                    break;
                case 'diterima/approved':
                    $statusBadge = '<span class="badge success">Diterima/Approved</span>';
                    break;
                case 'selesai/completed':
                    $statusBadge = '<span class="badge info">Resolved</span>';
                    break;
                case 'ditolak/rejected':
                    $statusBadge = '<span class="badge danger">Ditolak/Rejected</span>';
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
