@extends('layouts.admin')

@section('breadcrumb', 'Process Tickets')
@section('page-title', 'Process Approved Tickets')

@section('content')
    <style>
        .process-dashboard {
            min-height: 100vh;
            background: #f8fafc;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .process-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 24px;
        }

        .process-header {
            background: white;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .process-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #2563eb;
        }

        .stat-label {
            font-size: 14px;
            color: #64748b;
            margin-top: 4px;
        }

        /* Tab Navigation */
        .tab-navigation {
            background: white;
            border-radius: 12px 12px 0 0;
            padding: 0;
            margin-bottom: 0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            display: flex;
            border-bottom: 1px solid #e2e8f0;
        }

        .tab-btn {
            padding: 16px 24px;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            color: #64748b;
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
        }

        .tab-btn.active {
            color: #2563eb;
            border-bottom-color: #2563eb;
            background: #f8fafc;
        }

        .tab-btn:hover {
            color: #2563eb;
            background: #f8fafc;
        }

        /* Tab Content */
        .tab-content {
            background: white;
            border-radius: 0 0 12px 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            min-height: 500px;
        }

        .tab-pane {
            display: none;
            padding: 24px;
        }

        .tab-pane.active {
            display: block;
        }

        /* Ticket List Styles */
        .ticket-list {
            background: transparent;
            border-radius: 0;
            overflow: hidden;
            box-shadow: none;
        }

        .ticket-item {
            padding: 20px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .ticket-info h4 {
            margin: 0 0 8px 0;
            color: #1e293b;
        }

        .ticket-meta {
            font-size: 14px;
            color: #64748b;
        }

        .timer-container {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .timer {
            font-family: 'SF Mono', Monaco, monospace;
            font-size: 18px;
            font-weight: bold;
            color: #059669;
            background: #f0fdf4;
            padding: 8px 12px;
            border-radius: 6px;
        }

        .timer.stopped {
            color: #dc2626;
            background: #fef2f2;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-start {
            background: #10b981;
            color: white;
        }

        .btn-pause {
            background: #f59e0b;
            color: white;
        }

        .btn-complete {
            background: #3b82f6;
            color: white;
        }

        .btn-view {
            background: #6b7280;
            color: white;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #64748b;
        }

        .work-session-info {
            background: #f8fafc;
            padding: 12px;
            border-radius: 6px;
            font-size: 14px;
            color: #475569;
            margin-top: 8px;
        }

        /* Calendar Styles */
        .calendar-container {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 24px;
            min-height: 600px;
        }

        .calendar-main {
            background: #f8fafc;
            border-radius: 8px;
            padding: 20px;
        }

        .calendar-sidebar {
            background: #f8fafc;
            border-radius: 8px;
            padding: 20px;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 2px;
            background: #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
            min-height: 400px;
        }

        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .calendar-nav {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .calendar-nav button {
            background: #3b82f6;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
        }

        .calendar-nav button:hover {
            background: #2563eb;
        }

        .calendar-month {
            font-size: 20px;
            font-weight: bold;
            color: #1e293b;
            margin: 0 16px;
        }

        .calendar-day-header {
            background: #374151;
            color: white;
            padding: 12px 8px;
            text-align: center;
            font-weight: 600;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .calendar-day {
            background: white;
            min-height: 80px;
            padding: 8px;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
            display: flex;
            flex-direction: column;
            border: 1px solid #e5e7eb;
        }

        .calendar-day:hover {
            background: #f1f5f9;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .calendar-day.other-month {
            background: #f8fafc;
            color: #9ca3af;
        }

        .calendar-day.today {
            background: #dbeafe;
            border: 2px solid #3b82f6;
            font-weight: bold;
        }

        .calendar-day.has-work {
            background: #ecfdf5;
            border-left: 4px solid #10b981;
        }

        .calendar-day.selected {
            background: #dbeafe !important;
            border: 2px solid #3b82f6 !important;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
        }

        .day-number {
            font-weight: 600;
            margin-bottom: 4px;
            font-size: 16px;
            color: #1f2937;
        }

        .calendar-day.other-month .day-number {
            color: #9ca3af;
        }

        .calendar-day.today .day-number {
            color: #1e40af;
        }

        .day-work-info {
            font-size: 10px;
            color: #059669;
            background: #dcfce7;
            padding: 2px 4px;
            border-radius: 3px;
            margin-bottom: 2px;
            text-align: center;
            font-weight: 500;
        }

        .sidebar-section {
            margin-bottom: 24px;
        }

        .sidebar-title {
            font-size: 16px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 12px;
        }

        .day-detail {
            background: white;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .day-detail-header {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 8px;
            font-size: 16px;
        }

        .work-session-item {
            background: #f8fafc;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 8px;
            font-size: 13px;
            border-left: 3px solid #10b981;
        }

        .session-time {
            color: #059669;
            font-weight: 600;
            font-size: 14px;
        }

        .session-ticket {
            color: #64748b;
            margin-top: 2px;
        }

        @media (max-width: 768px) {
            .calendar-container {
                grid-template-columns: 1fr;
                height: auto;
            }

            .calendar-sidebar {
                order: -1;
            }

            .calendar-grid {
                min-height: 300px;
            }

            .calendar-day {
                min-height: 60px;
                padding: 4px;
            }
        }
    </style>

    <div class="process-dashboard">
        <div class="process-container">
            <div class="process-header">
                <h1>Process Approved Tickets</h1>
                <p>Manage and track tickets that have been approved for processing</p>
            </div>

            <div class="process-stats">
                <div class="stat-card">
                    <div class="stat-number">{{ $acceptedTickets->count() }}</div>
                    <div class="stat-label">Approved Tickets</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $acceptedTickets->where('is_processing', true)->count() }}</div>
                    <div class="stat-label">Currently Processing</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $completedToday->count() }}</div>
                    <div class="stat-label">Completed Today</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ gmdate('H:i:s', $totalWorkTimeToday) }}</div>
                    <div class="stat-label">Total Work Time Today</div>
                </div>
            </div>

            <!-- Tab Navigation -->
            <div class="tab-navigation">
                <button class="tab-btn active" onclick="switchTab('tickets')">
                    <i class="fas fa-list"></i> Daftar Tiket
                </button>
                <button class="tab-btn" onclick="switchTab('calendar')">
                    <i class="fas fa-calendar-alt"></i> Kalender Kerja
                </button>
            </div>

            <!-- Tab Content -->
            <div class="tab-content">
                <!-- Tickets Tab -->
                <div id="tickets-tab" class="tab-pane active">
                    <div class="ticket-list">
                        @if ($acceptedTickets->isEmpty())
                            <div class="empty-state">
                                <i class="fas fa-inbox" style="font-size: 48px; color: #cbd5e1; margin-bottom: 16px;"></i>
                                <h3>Tidak ada tiket yang disetujui untuk diproses</h3>
                                <p>Saat ini tidak ada tiket yang disetujui menunggu untuk diproses.</p>
                            </div>
                        @else
                            @foreach ($acceptedTickets as $ticket)
                                <div class="ticket-item" data-ticket-id="{{ $ticket->id }}">
                                    <div class="ticket-info">
                                        <h4>{{ $ticket->judul ?? 'Tanpa Judul' }}</h4>
                                        <div class="ticket-meta">
                                            <span><strong>ID:</strong> {{ $ticket->code_tracking }}</span> |
                                            <span><strong>Pelapor:</strong> {{ $ticket->nama_pelapor }}</span> |
                                            <span><strong>Diterima:</strong>
                                                {{ $ticket->accepted_at->diffForHumans() }}</span>
                                        </div>
                                        <div class="work-session-info">
                                            <strong>Waktu Pengerjaan:</strong> {{ $ticket->formatted_processing_time }}
                                        </div>
                                    </div>

                                    <div class="timer-container">
                                        <div class="timer @if ($ticket->is_processing) {{ 'active' }}@else{{ 'stopped' }} @endif"
                                            id="timer-{{ $ticket->id }}"
                                            data-accepted-at="{{ $ticket->accepted_at->toISOString() }}">
                                            {{ $ticket->formatted_processing_time }}
                                        </div>

                                        <div class="action-buttons">
                                            <form action="{{ route('admin.tickets.complete', $ticket) }}" method="POST"
                                                style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-complete"
                                                    onclick="return confirm('Apakah Anda yakin ingin menandai tiket ini sebagai selesai?')">
                                                    <i class="fas fa-check"></i> Selesai
                                                </button>
                                            </form>

                                            <a href="{{ route('admin.tickets.show', $ticket) }}" class="btn btn-view">
                                                <i class="fas fa-eye"></i> Lihat
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- Calendar Tab -->
                <div id="calendar-tab" class="tab-pane">
                    <div class="calendar-container">
                        <div class="calendar-main">
                            <div class="calendar-header">
                                <div class="calendar-nav">
                                    <button onclick="changeMonth(-1)">
                                        <i class="fas fa-chevron-left"></i>
                                    </button>
                                    <span class="calendar-month" id="current-month"></span>
                                    <button onclick="changeMonth(1)">
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                </div>
                                <button onclick="goToToday()" class="btn" style="background: #10b981; color: white;">
                                    <i class="fas fa-calendar-day"></i> Hari Ini
                                </button>
                            </div>
                            <div class="calendar-grid" id="calendar-grid">
                                <!-- Calendar will be generated by JavaScript -->
                            </div>
                        </div>
                        <div class="calendar-sidebar">
                            <div class="sidebar-section">
                                <div class="sidebar-title">Hari Terpilih</div>
                                <div id="selected-day-info">
                                    <p style="color: #64748b; font-style: italic;">Klik pada tanggal untuk melihat detail
                                        kerja</p>
                                </div>
                            </div>
                            <div class="sidebar-section">
                                <div class="sidebar-title">Ringkasan Bulan Ini</div>
                                <div class="day-detail">
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                        <span>Total Hari Kerja:</span>
                                        <span id="month-work-days">-</span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                        <span>Total Waktu:</span>
                                        <span id="month-total-hours">-</span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                        <span>Rata-rata/Hari:</span>
                                        <span id="month-avg-hours">-</span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between;">
                                        <span>Tiket Selesai:</span>
                                        <span id="month-tickets">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentDate = new Date();
        let workData = {}; // Will store real work session data from API

        document.addEventListener('DOMContentLoaded', function() {
            // Real-time timer update for processing tickets
            function updateTimers() {
                const timerElements = document.querySelectorAll('.timer.active');

                timerElements.forEach(timer => {
                    const ticketId = timer.id.replace('timer-', '');
                    const acceptedAt = timer.getAttribute('data-accepted-at');

                    if (acceptedAt) {
                        const acceptedTime = new Date(acceptedAt).getTime();
                        const currentTime = new Date().getTime();
                        const elapsedSeconds = Math.floor((currentTime - acceptedTime) / 1000);

                        // Format time as HH:MM:SS
                        const hours = Math.floor(elapsedSeconds / 3600);
                        const minutes = Math.floor((elapsedSeconds % 3600) / 60);
                        const seconds = elapsedSeconds % 60;

                        timer.textContent =
                            hours.toString().padStart(2, '0') + ':' +
                            minutes.toString().padStart(2, '0') + ':' +
                            seconds.toString().padStart(2, '0');
                    }
                });
            }

            // Update timers immediately and then every second
            updateTimers();
            setInterval(updateTimers, 1000);

            // Initialize calendar
            generateCalendar();
            loadWorkData();
        });

        function switchTab(tabName) {
            // Remove active class from all tabs and buttons
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('active'));

            // Add active class to selected tab and button
            document.querySelector(`button[onclick="switchTab('${tabName}')"]`).classList.add('active');
            document.getElementById(`${tabName}-tab`).classList.add('active');

            // If switching to calendar, refresh the calendar
            if (tabName === 'calendar') {
                generateCalendar();
                loadWorkData();
            }
        }

        function generateCalendar() {
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();

            // Update month display - Indonesian month names
            const monthNames = [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];
            document.getElementById('current-month').textContent = `${monthNames[month]} ${year}`;

            // Get first day of month and number of days
            const firstDay = new Date(year, month, 1);
            const lastDay = new Date(year, month + 1, 0);
            const daysInMonth = lastDay.getDate();
            const startingDayOfWeek = firstDay.getDay();

            // Generate calendar grid
            const calendarGrid = document.getElementById('calendar-grid');
            calendarGrid.innerHTML = '';

            // Add day headers - Indonesian day names
            const dayHeaders = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
            dayHeaders.forEach(day => {
                const dayHeader = document.createElement('div');
                dayHeader.className = 'calendar-day-header';
                dayHeader.textContent = day;
                calendarGrid.appendChild(dayHeader);
            });

            // Add empty cells for days before month starts
            for (let i = 0; i < startingDayOfWeek; i++) {
                const emptyDay = document.createElement('div');
                emptyDay.className = 'calendar-day other-month';
                calendarGrid.appendChild(emptyDay);
            }

            // Add days of the month
            const today = new Date();
            for (let day = 1; day <= daysInMonth; day++) {
                const dayElement = document.createElement('div');
                dayElement.className = 'calendar-day';

                const dayNumber = document.createElement('div');
                dayNumber.className = 'day-number';
                dayNumber.textContent = day;
                dayElement.appendChild(dayNumber);

                // Check if this is today
                if (year === today.getFullYear() && month === today.getMonth() && day === today.getDate()) {
                    dayElement.classList.add('today');
                }

                // Add click event
                const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                dayElement.onclick = () => selectDay(dateStr);
                dayElement.dataset.date = dateStr;

                calendarGrid.appendChild(dayElement);
            }
        }

        function changeMonth(direction) {
            currentDate.setMonth(currentDate.getMonth() + direction);
            generateCalendar();
            loadWorkData();
        }

        function goToToday() {
            currentDate = new Date();
            generateCalendar();
            loadWorkData();
        }

        function loadWorkData() {
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();
            const startDate = new Date(year, month, 1).toISOString().split('T')[0];
            const endDate = new Date(year, month + 1, 0).toISOString().split('T')[0];

            console.log('Loading work data for:', startDate, 'to', endDate);

            // Fetch real data from API
            fetch(`{{ route('admin.tickets.calendar.data') }}?start=${startDate}&end=${endDate}`)
                .then(response => response.json())
                .then(events => {
                    console.log('API Response:', events);
                    workData = {};

                    events.forEach(event => {
                        const date = event.start;
                        const props = event.extendedProps;

                        workData[date] = {
                            totalDuration: props.duration,
                            formattedDuration: props.formatted_duration,
                            ticketsWorked: props.ticket_count,
                            sessionsCount: props.sessions_count
                        };

                        // Add work info to calendar day
                        const dayElement = document.querySelector(`[data-date="${date}"]`);
                        if (dayElement) {
                            dayElement.classList.add('has-work');

                            const workInfo = document.createElement('div');
                            workInfo.className = 'day-work-info';
                            workInfo.textContent = `${props.formatted_duration} (${props.ticket_count}t)`;
                            dayElement.appendChild(workInfo);
                        }
                    });

                    console.log('Work data processed:', workData);
                    updateMonthlySummary();
                })
                .catch(error => {
                    console.error('Error loading work data:', error);
                });
        }

        function selectDay(dateStr) {
            // Remove previous selection
            document.querySelectorAll('.calendar-day').forEach(day => {
                day.classList.remove('selected');
            });

            // Add selection to clicked day
            const selectedDay = document.querySelector(`[data-date="${dateStr}"]`);
            if (selectedDay) {
                selectedDay.classList.add('selected');
            }

            // Fetch day details from API
            fetch(`{{ url('admin/tickets/calendar/day') }}/${dateStr}`)
                .then(response => response.json())
                .then(data => {
                    const dayInfo = document.getElementById('selected-day-info');

                    if (data.sessions && data.sessions.length > 0) {
                        let sessionsHtml = '';
                        data.sessions.forEach(session => {
                            sessionsHtml += `
                        <div class="work-session-item">
                            <div class="session-time">${session.time_range}</div>
                            <div class="session-ticket">${session.ticket_code} - ${session.ticket_title}</div>
                            <div style="font-size: 12px; color: #9ca3af;">Durasi: ${session.duration} </div>
                        </div>
                    `;
                        });

                        dayInfo.innerHTML = `
                    <div class="day-detail">
                        <div class="day-detail-header">${data.date}</div>
                        <div style="margin-bottom: 12px;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                                <span>Total Waktu:</span>
                                <span class="session-time">${data.stats.formatted_duration}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                                <span>Tiket Dikerjakan:</span>
                                <span>${data.stats.unique_tickets}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span>Sesi Kerja:</span>
                                <span>${data.stats.completed_sessions}</span>
                            </div>
                        </div>
                        <div style="font-size: 13px; color: #64748b; margin-bottom: 8px;">Detail Sesi Kerja:</div>
                        ${sessionsHtml}
                    </div>
                `;
                    } else {
                        dayInfo.innerHTML = `
                    <div class="day-detail">
                        <div class="day-detail-header">${data.date}</div>
                        <p style="color: #64748b; font-style: italic; margin: 12px 0;">Tidak ada sesi kerja yang tercatat untuk hari ini.</p>
                    </div>
                `;
                    }
                })
                .catch(error => {
                    console.error('Error loading day details:', error);
                });
        }

        function updateMonthlySummary() {
            const workDays = Object.keys(workData).length;
            const totalSeconds = Object.values(workData).reduce((sum, day) => sum + day.totalDuration, 0);
            const totalTickets = Object.values(workData).reduce((sum, day) => sum + day.ticketsWorked, 0);

            // Format total time with hours, minutes, seconds
            const totalHours = Math.floor(totalSeconds / 3600);
            const totalMinutes = Math.floor((totalSeconds % 3600) / 60);
            const remainingSeconds = totalSeconds % 60;

            let totalTimeText = '';
            if (totalHours > 0) {
                totalTimeText = `${totalHours}j ${totalMinutes}m ${remainingSeconds}s`;
            } else if (totalMinutes > 0) {
                totalTimeText = `${totalMinutes}m ${remainingSeconds}s`;
            } else {
                totalTimeText = `${remainingSeconds}s`;
            }

            // Calculate average time per day
            const avgSeconds = workDays > 0 ? (totalSeconds / workDays) : 0;
            const avgHours = Math.floor(avgSeconds / 3600);
            const avgMinutes = Math.floor((avgSeconds % 3600) / 60);
            const avgRemainingSeconds = Math.floor(avgSeconds % 60);

            let avgTimeText = '';
            if (avgHours > 0) {
                avgTimeText = `${avgHours}j ${avgMinutes}m ${avgRemainingSeconds}s`;
            } else if (avgMinutes > 0) {
                avgTimeText = `${avgMinutes}m ${avgRemainingSeconds}s`;
            } else {
                avgTimeText = `${avgRemainingSeconds}s`;
            }

            document.getElementById('month-work-days').textContent = workDays;
            document.getElementById('month-total-hours').textContent = totalTimeText;
            document.getElementById('month-avg-hours').textContent = avgTimeText;
            document.getElementById('month-tickets').textContent = totalTickets;
        }
    </script>
@endsection
