@extends('layouts.admin')

@section('breadcrumb', 'Admin Dashboard')
@section('page-title', 'CSIRT Admin Dashboard')

@section('content')
    <style>
        /* Modern Tailwind-inspired Admin Dashboard */
        :root {
            --primary-50: #eff6ff;
            --primary-100: #dbeafe;
            --primary-500: #3b82f6;
            --primary-600: #2563eb;
            --primary-700: #1d4ed8;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
            --green-50: #f0fdf4;
            --green-500: #22c55e;
            --green-600: #16a34a;
            --amber-50: #fffbeb;
            --amber-500: #f59e0b;
            --amber-600: #d97706;
            --red-50: #fef2f2;
            --red-500: #ef4444;
            --red-600: #dc2626;
            --indigo-50: #eef2ff;
            --indigo-500: #6366f1;
            --indigo-600: #4f46e5;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }

        * {
            box-sizing: border-box;
        }

        body {

            background: var(--gray-50);
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: var(--gray-900);
            line-height: 1.6;
        }

        .container-fluid {
            max-width: 1280px;
            margin: 0 auto;
            padding: 1rem;
        }

        /* Enhanced Month Navigation Styles */
        .month-navigation {
            background: white;
            border: 1px solid var(--gray-200);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-sm);
        }

        .nav-button {
            background: var(--primary-600);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: all 0.2s ease;
            font-weight: 500;
        }

        .nav-button:hover:not(:disabled) {
            background: var(--primary-700);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .nav-button:disabled {
            background: var(--gray-300);
            color: var(--gray-500);
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .nav-button.secondary {
            background: var(--gray-600);
        }

        .nav-button.secondary:hover:not(:disabled) {
            background: var(--gray-700);
        }

        .month-display {
            background: var(--gray-50);
            border: 1px solid var(--gray-200);
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-weight: 600;
            color: var(--gray-800);
            text-align: center;
            min-width: 150px;
        }

        /* Enhanced Date Picker */
        .date-picker-container {
            position: relative;
            display: inline-block;
            min-width: 200px;
        }

        .date-picker-input {
            background: white;
            border: 1px solid var(--gray-300);
            border-radius: 8px;
            padding: 0.5rem 2.5rem 0.5rem 0.75rem;
            font-size: 0.875rem;
            color: var(--gray-700);
            cursor: pointer;
            transition: all 0.2s ease;
            min-width: 180px;
            width: 100%;
        }

        .date-picker-input:hover {
            border-color: var(--primary-500);
        }

        .date-picker-input:focus {
            outline: 2px solid var(--primary-500);
            outline-offset: -2px;
            border-color: var(--primary-500);
            box-shadow: 0 0 0 3px rgb(59 130 246 / 0.1);
        }

        .date-picker-icon {
            position: absolute;
            right: 0.5rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
            pointer-events: none;
        }

        /* Custom Calendar Dropdown */
        .calendar-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid var(--gray-200);
            border-radius: 12px;
            box-shadow: var(--shadow-lg);
            z-index: 1000;
            margin-top: 4px;
            overflow: hidden;
            display: none;
            min-width: 350px;
            width: max-content;
        }

        .calendar-dropdown.show {
            display: block;
            animation: fadeIn 0.15s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .calendar-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem;
            border-bottom: 1px solid var(--gray-200);
            background: var(--gray-50);
        }

        .calendar-nav-btn {
            background: none;
            border: none;
            padding: 0.5rem;
            color: var(--gray-600);
            cursor: pointer;
            border-radius: 6px;
            transition: all 0.2s ease;
            min-width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .calendar-nav-btn:hover {
            background: var(--gray-200);
            color: var(--gray-900);
        }

        .calendar-title {
            font-weight: 600;
            color: var(--gray-900);
            font-size: 1rem;
            min-width: 150px;
            text-align: center;
        }

        .year-month-selectors {
            display: flex;
            gap: 0.75rem;
            padding: 1rem;
            border-bottom: 1px solid var(--gray-200);
        }

        .year-selector,
        .month-selector-dropdown {
            background: white;
            border: 1px solid var(--gray-300);
            border-radius: 6px;
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            color: var(--gray-700);
            cursor: pointer;
            transition: all 0.2s ease;
            flex: 1;
            min-width: 120px;
        }

        .year-selector:focus,
        .month-selector-dropdown:focus {
            outline: 2px solid var(--primary-500);
            outline-offset: -2px;
            border-color: var(--primary-500);
        }

        .calendar-grid {
            padding: 1.25rem;
        }

        .calendar-weekdays {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 0.5rem;
            margin-bottom: 0.75rem;
        }

        .calendar-weekday {
            padding: 0.75rem;
            text-align: center;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--gray-500);
            text-transform: uppercase;
            letter-spacing: 0.025em;
            min-width: 40px;
        }

        .calendar-days {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 0.5rem;
        }

        .calendar-day {
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 1px solid transparent;
            min-width: 40px;
            min-height: 40px;
            font-weight: 500;
        }

        .calendar-day:hover {
            background: var(--primary-50);
            color: var(--primary-700);
            transform: scale(1.05);
        }

        .calendar-day.other-month {
            color: var(--gray-300);
        }

        .calendar-day.selected {
            background: var(--primary-600);
            color: white;
            border-color: var(--primary-600);
            font-weight: 600;
        }

        .calendar-day.today {
            border-color: var(--primary-400);
            font-weight: 600;
            background: var(--primary-50);
        }

        .calendar-actions {
            display: flex;
            gap: 0.75rem;
            padding: 1.25rem;
            border-top: 1px solid var(--gray-200);
            background: var(--gray-50);
        }

        .calendar-btn {
            flex: 1;
            padding: 0.5rem 1rem;
            border: 1px solid var(--gray-300);
            border-radius: 8px;
            background: white;
            color: var(--gray-700);
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.2s ease;
            font-weight: 500;
            min-height: 40px;
        }

        .calendar-btn:hover {
            background: var(--gray-50);
            border-color: var(--gray-400);
            transform: translateY(-1px);
        }

        .calendar-btn.primary {
            background: var(--primary-600);
            color: white;
            border-color: var(--primary-600);
        }

        .calendar-btn.primary:hover {
            background: var(--primary-700);
            border-color: var(--primary-700);
        }

        .range-selector {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .range-selector button {
            background: var(--gray-100);
            border: 1px solid var(--gray-200);
            border-radius: 6px;
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .range-selector button.active {
            background: var(--primary-600);
            color: white;
            border-color: var(--primary-600);
        }

        .range-selector button:hover:not(.active) {
            background: var(--gray-200);
            border-color: var(--gray-300);
        }

        /* Loading overlay */
        .chart-loading {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            z-index: 10;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid var(--gray-200);
            border-top: 4px solid var(--primary-600);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Cards */
        .card {
            background: white;
            border: 1px solid var(--gray-200);
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            transition: all 0.2s ease-in-out;
            overflow: hidden;
        }

        .card:hover {
            box-shadow: var(--shadow-md);
            border-color: var(--gray-300);
        }

        /* Statistics Cards */
        .stats-card {
            background: white;
            border: 1px solid var(--gray-200);
            border-radius: 12px;
            padding: 1.5rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }

        .stats-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-xl);
            border-color: var(--gray-300);
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            transition: all 0.3s ease;
        }

        .stats-card.primary::before {
            background: linear-gradient(90deg, var(--primary-500), var(--primary-600));
        }

        .stats-card.warning::before {
            background: linear-gradient(90deg, var(--amber-500), var(--amber-600));
        }

        .stats-card.success::before {
            background: linear-gradient(90deg, var(--green-500), var(--green-600));
        }

        .stats-card.info::before {
            background: linear-gradient(90deg, var(--indigo-500), var(--indigo-600));
        }

        .stats-card.danger::before {
            background: linear-gradient(90deg, var(--red-500), var(--red-600));
        }

        /* Icon containers */
        .icon-container {
            width: 3rem;
            height: 3rem;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            transition: all 0.3s ease;
        }

        .icon-container.primary {
            background: var(--primary-50);
            color: var(--primary-600);
        }

        .icon-container.warning {
            background: var(--amber-50);
            color: var(--amber-600);
        }

        .icon-container.success {
            background: var(--green-50);
            color: var(--green-600);
        }

        .icon-container.info {
            background: var(--indigo-50);
            color: var(--indigo-600);
        }

        .icon-container.danger {
            background: var(--red-50);
            color: var(--red-600);
        }

        .stats-card:hover .icon-container {
            transform: scale(1.1) rotate(5deg);
        }

        /* Typography */
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--gray-900);
            line-height: 1;
            margin: 0;
        }

        .stat-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--gray-600);
            text-transform: uppercase;
            letter-spacing: 0.025em;
            margin: 0;
        }

        /* Chart containers */
        .chart-card {
            background: white;
            border: 1px solid var(--gray-200);
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .chart-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--gray-200);
            background: var(--gray-50);
        }

        .chart-body {
            padding: 1.5rem;
            position: relative;
            height: 400px;
        }

        .chart-body.donut {
            height: 300px;
        }

        /* Table styling */
        .table-card {
            background: white;
            border: 1px solid var(--gray-200);
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .table-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--gray-200);
            background: var(--gray-50);
        }

        .table-responsive {
            border-radius: 0;
            border: none;
            box-shadow: none;
        }

        .table {
            margin: 0;
            font-size: 0.875rem;
        }

        .table thead th {
            background: transparent;
            border: none;
            border-bottom: 1px solid var(--gray-200);
            color: var(--gray-700);
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 1rem;
        }

        .table tbody td {
            border: none;
            border-bottom: 1px solid var(--gray-100);
            padding: 1rem;
            vertical-align: middle;
            color: var(--gray-800);
        }

        .table tbody tr:hover {
            background: var(--gray-50);
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Badges */
        .badge {
            font-size: 0.75rem;
            font-weight: 500;
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            border: 1px solid transparent;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }

        .badge.primary {
            background: var(--primary-50);
            color: var(--primary-700);
            border-color: var(--primary-200);
        }

        .badge.warning {
            background: var(--amber-50);
            color: var(--amber-700);
            border-color: var(--amber-200);
        }

        .badge.success {
            background: var(--green-50);
            color: var(--green-700);
            border-color: var(--green-200);
        }

        .badge.info {
            background: var(--indigo-50);
            color: var(--indigo-700);
            border-color: var(--indigo-200);
        }

        .badge.danger {
            background: var(--red-50);
            color: var(--red-700);
            border-color: var(--red-200);
        }

        /* Buttons */
        .btn {
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            border: 1px solid transparent;
            transition: all 0.2s ease-in-out;
            font-size: 0.875rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.8125rem;
        }

        .btn-primary {
            background: var(--primary-600);
            color: white;
            border-color: var(--primary-600);
        }

        .btn-primary:hover {
            background: var(--primary-700);
            border-color: var(--primary-700);
            color: white;
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .btn-success {
            background: var(--green-600);
            color: white;
            border-color: var(--green-600);
        }

        .btn-success:hover {
            background: var(--green-700);
            border-color: var(--green-700);
            color: white;
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .btn-info {
            background: var(--indigo-600);
            color: white;
            border-color: var(--indigo-600);
        }

        .btn-info:hover {
            background: var(--indigo-700);
            border-color: var(--indigo-700);
            color: white;
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .btn-danger {
            background: var(--red-600);
            color: white;
            border-color: var(--red-600);
        }

        .btn-danger:hover {
            background: var(--red-700);
            border-color: var(--red-700);
            color: white;
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .btn-secondary {
            background: var(--gray-600);
            color: white;
            border-color: var(--gray-600);
        }

        .btn-secondary:hover {
            background: var(--gray-700);
            border-color: var(--gray-700);
            color: white;
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .btn-outline {
            background: white;
            color: var(--gray-700);
            border-color: var(--gray-300);
        }

        .btn-outline:hover {
            background: var(--gray-50);
            border-color: var(--gray-400);
            color: var(--gray-800);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        /* Form controls */
        .form-control,
        .form-select {
            background: white;
            border: 1px solid var(--gray-300);
            border-radius: 8px;
            padding: 0.75rem;
            color: var(--gray-900);
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }

        .form-control:focus,
        .form-select:focus {
            outline: 2px solid var(--primary-500);
            outline-offset: -2px;
            border-color: var(--primary-500);
            box-shadow: 0 0 0 3px rgb(59 130 246 / 0.1);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container-fluid {
                padding: 0.5rem;
            }

            .month-navigation {
                padding: 1rem;
            }

            .nav-controls {
                flex-direction: column;
                gap: 1rem;
            }

            .stat-number {
                font-size: 1.75rem;
            }
        }

        /* Grid utilities */
        .row {
            display: flex;
            flex-wrap: wrap;
            margin: -0.5rem;
        }

        .row>* {
            padding: 0.5rem;
        }

        .col-12 {
            flex: 0 0 100%;
            max-width: 100%;
        }

        .col-xl-3 {
            flex: 0 0 25%;
            max-width: 25%;
        }

        .col-lg-8 {
            flex: 0 0 66.666667%;
            max-width: 66.666667%;
        }

        .col-lg-4 {
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
        }

        .col-sm-6 {
            flex: 0 0 50%;
            max-width: 50%;
        }

        @media (max-width: 1199.98px) {
            .col-xl-3 {
                flex: 0 0 50%;
                max-width: 50%;
            }
        }

        @media (max-width: 991.98px) {

            .col-lg-8,
            .col-lg-4 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }

        @media (max-width: 575.98px) {
            .col-sm-6 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }

        /* Spacing utilities */
        .mb-4 {
            margin-bottom: 1.5rem;
        }

        .mb-3 {
            margin-bottom: 1rem;
        }

        .py-4 {
            padding-top: 1.5rem;
            padding-bottom: 1.5rem;
        }

        .p-3 {
            padding: 1rem;
        }

        /* Flex utilities */
        .d-flex {
            display: flex;
        }

        .justify-content-between {
            justify-content: space-between;
        }

        .align-items-center {
            align-items: center;
        }

        .text-end {
            text-align: right;
        }

        .gap-2 {
            gap: 0.5rem;
        }

        .gap-3 {
            gap: 1rem;
        }

        /* Visibility */
        .mb-xl-0 {
            @media (min-width: 1200px) {
                margin-bottom: 0 !important;
            }
        }

        /* Responsive adjustments for calendar */
        @media (max-width: 768px) {
            .calendar-dropdown {
                min-width: 320px;
                left: -50px;
                right: -50px;
            }

            .calendar-day {
                min-width: 35px;
                min-height: 35px;
                font-size: 0.8125rem;
            }

            .calendar-weekday {
                padding: 0.5rem;
                min-width: 35px;
            }

            .year-month-selectors {
                flex-direction: column;
                gap: 0.5rem;
            }

            .year-selector,
            .month-selector-dropdown {
                min-width: auto;
            }
        }

        @media (max-width: 480px) {
            .calendar-dropdown {
                min-width: 300px;
                left: -75px;
                right: -75px;
            }
        }

        /* Modern Statistics Cards Styles */
        .stat-card-modern {
            transition: all 0.3s ease;
            border-radius: 16px !important;
            overflow: hidden;
        }

        .stat-card-modern:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1) !important;
        }

        .stat-icon-modern {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            transition: all 0.3s ease;
        }

        .stat-card-modern:hover .stat-icon-modern {
            transform: scale(1.1) rotate(5deg);
        }

        .stat-trend {
            font-size: 24px;
            opacity: 0.7;
            transition: all 0.3s ease;
        }

        .stat-card-modern:hover .stat-trend {
            opacity: 1;
            transform: scale(1.2);
        }

        .card-footer {
            background: transparent !important;
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            font-weight: 500;
        }

        /* Animation for cards */
        .stat-card-modern {
            animation: slideInUp 0.6s ease-out;
        }

        .stat-card-modern:nth-child(1) {
            animation-delay: 0.1s;
        }

        .stat-card-modern:nth-child(2) {
            animation-delay: 0.2s;
        }

        .stat-card-modern:nth-child(3) {
            animation-delay: 0.3s;
        }

        .stat-card-modern:nth-child(4) {
            animation-delay: 0.4s;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive adjustments for modern cards */
        @media (max-width: 768px) {
            .stat-card-modern {
                margin-bottom: 1rem;
            }

            .stat-icon-modern {
                width: 40px;
                height: 40px;
                font-size: 18px;
            }
        }
    </style>

    <div class="container-fluid py-4">
        <div class="container-fluid py-4">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">Dashboard Overview</h1>
                <p class="page-subtitle">Monitor and manage your CSIRT tickets</p>
            </div>

            <!-- Modern Statistics Cards -->
            <div class="row g-4 mb-5">
                <!-- Total Tickets Card -->
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="card border-0 shadow-sm h-100 stat-card-modern">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="stat-icon-modern bg-primary bg-opacity-10 text-primary me-3">
                                            <i class="fas fa-ticket-alt"></i>
                                        </div>
                                        <h6 class="text-muted mb-0 fw-medium">Total Tiket</h6>
                                    </div>
                                    <h2 class="mb-0 fw-bold text-dark" id="totalTickets">{{ $totalTickets }}</h2>
                                    <div class="mt-2">
                                        <span class="badge bg-primary bg-opacity-10 text-primary">
                                            <i class="fas fa-chart-line me-1"></i>Semua Status
                                        </span>
                                    </div>
                                </div>
                                <div class="stat-trend text-primary">
                                    <i class="fas fa-arrow-up"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-primary bg-opacity-5 border-0 py-2">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>Total keseluruhan tiket
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Pending Tickets Card -->
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="card border-0 shadow-sm h-100 stat-card-modern">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="stat-icon-modern bg-warning bg-opacity-10 text-warning me-3">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                        <h6 class="text-muted mb-0 fw-medium">Menunggu</h6>
                                    </div>
                                    <h2 class="mb-0 fw-bold text-dark" id="pendingTickets">{{ $pendingTickets }}</h2>
                                    <div class="mt-2">
                                        <span class="badge bg-warning bg-opacity-10 text-warning">
                                            <i class="fas fa-hourglass-half me-1"></i>Perlu Tindakan
                                        </span>
                                    </div>
                                </div>
                                <div class="stat-trend text-warning">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-warning bg-opacity-5 border-0 py-2">
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>Menunggu persetujuan
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Accepted Tickets Card -->
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="card border-0 shadow-sm h-100 stat-card-modern">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="stat-icon-modern bg-success bg-opacity-10 text-success me-3">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                        <h6 class="text-muted mb-0 fw-medium">Diterima</h6>
                                    </div>
                                    <h2 class="mb-0 fw-bold text-dark" id="acceptedTickets">{{ $acceptedTickets }}</h2>
                                    <div class="mt-2">
                                        <span class="badge bg-success bg-opacity-10 text-success">
                                            <i class="fas fa-thumbs-up me-1"></i>Disetujui
                                        </span>
                                    </div>
                                </div>
                                <div class="stat-trend text-success">
                                    <i class="fas fa-check"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-success bg-opacity-5 border-0 py-2">
                            <small class="text-muted">
                                <i class="fas fa-check-circle me-1"></i>Sedang diproses
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Resolved Tickets Card -->
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="card border-0 shadow-sm h-100 stat-card-modern">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="stat-icon-modern bg-info bg-opacity-10 text-info me-3">
                                            <i class="fas fa-check-double"></i>
                                        </div>
                                        <h6 class="text-muted mb-0 fw-medium">Selesai</h6>
                                    </div>
                                    <h2 class="mb-0 fw-bold text-dark" id="resolvedTickets">{{ $resolvedTickets }}</h2>
                                    <div class="mt-2">
                                        <span class="badge bg-info bg-opacity-10 text-info">
                                            <i class="fas fa-flag-checkered me-1"></i>Tuntas
                                        </span>
                                    </div>
                                </div>
                                <div class="stat-trend text-info">
                                    <i class="fas fa-trophy"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-info bg-opacity-5 border-0 py-2">
                            <small class="text-muted">
                                <i class="fas fa-check-double me-1"></i>Berhasil diselesaikan
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Stats Row -->
            <div class="row g-4 mb-5">
                <!-- Rejected Tickets Card -->
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="card border-0 shadow-sm h-100 stat-card-modern">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="stat-icon-modern bg-danger bg-opacity-10 text-danger me-3">
                                            <i class="fas fa-times-circle"></i>
                                        </div>
                                        <h6 class="text-muted mb-0 fw-medium">Ditolak</h6>
                                    </div>
                                    <h2 class="mb-0 fw-bold text-dark" id="rejectedTickets">{{ $rejectedTickets ?? 0 }}</h2>
                                    <div class="mt-2">
                                        <span class="badge bg-danger bg-opacity-10 text-danger">
                                            <i class="fas fa-ban me-1"></i>Tidak Disetujui
                                        </span>
                                    </div>
                                </div>
                                <div class="stat-trend text-danger">
                                    <i class="fas fa-times"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-danger bg-opacity-5 border-0 py-2">
                            <small class="text-muted">
                                <i class="fas fa-times-circle me-1"></i>Tiket yang ditolak
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Summary Card -->
                <div class="col-xl-9 col-lg-6 col-md-6">
                    <div class="card border-0 shadow-sm h-100 stat-card-modern">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div>
                                    <h6 class="text-muted mb-1 fw-medium">Ringkasan Kinerja</h6>
                                    <h5 class="mb-0 fw-bold text-dark">Status Tiket Keseluruhan</h5>
                                </div>
                                <div class="stat-icon-modern bg-secondary bg-opacity-10 text-secondary">
                                    <i class="fas fa-chart-pie"></i>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-6 col-lg-3">
                                    <div class="text-center p-2 rounded bg-light">
                                        <div class="fw-bold text-primary fs-5">
                                            {{ round(($resolvedTickets / max($totalTickets, 1)) * 100) }}%</div>
                                        <small class="text-muted">Tingkat Penyelesaian</small>
                                    </div>
                                </div>
                                <div class="col-6 col-lg-3">
                                    <div class="text-center p-2 rounded bg-light">
                                        <div class="fw-bold text-success fs-5">
                                            {{ round((($acceptedTickets + $resolvedTickets) / max($totalTickets, 1)) * 100) }}%
                                        </div>
                                        <small class="text-muted">Tingkat Persetujuan</small>
                                    </div>
                                </div>
                                <div class="col-6 col-lg-3">
                                    <div class="text-center p-2 rounded bg-light">
                                        <div class="fw-bold text-warning fs-5">{{ $pendingTickets }}</div>
                                        <small class="text-muted">Perlu Tindakan</small>
                                    </div>
                                </div>
                                <div class="col-6 col-lg-3">
                                    <div class="text-center p-2 rounded bg-light">
                                        <div class="fw-bold text-info fs-5">{{ $totalTickets - $rejectedTickets }}</div>
                                        <small class="text-muted">Tiket Aktif</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enhanced Month Navigation -->
            <div class="month-navigation">
                <div class="d-flex justify-content-between align-items-center nav-controls">
                    <div class="d-flex align-items-center gap-3">
                        <button class="btn btn-primary" id="prevMonthBtn" onclick="navigatePreviousMonth()">
                            <i class="fas fa-chevron-left"></i>.
                        </button>

                        <div class="month-display" id="currentMonthDisplay">
                            {{ Carbon\Carbon::createFromFormat('Y-m', '2025-08')->format('F Y') }}
                        </div>

                        <button class="btn btn-primary" id="nextMonthBtn" onclick="navigateNextMonth()">
                            .<i class="fas fa-chevron-right"></i>
                        </button>
                    </div>

                    <div class="d-flex align-items-center gap-3">
                        <!-- Enhanced Date Picker -->
                        <div class="date-picker-container">
                            <input type="text" class="date-picker-input" id="datePicker"
                                value="{{ Carbon\Carbon::createFromFormat('Y-m', $selectedMonth)->format('F Y') }}"
                                readonly onclick="toggleCalendar()">
                            <i class="fas fa-calendar-alt date-picker-icon"></i>

                            <!-- Custom Calendar Dropdown -->
                            <div class="calendar-dropdown" id="calendarDropdown">
                                <div class="calendar-header">
                                    <button type="button" class="calendar-nav-btn" onclick="navigateCalendar(-1)">
                                        <i class="fas fa-chevron-left"></i>
                                    </button>
                                    <div class="calendar-title" id="calendarTitle">January 2025</div>
                                    <button type="button" class="calendar-nav-btn" onclick="navigateCalendar(1)">
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                </div>

                                <div class="year-month-selectors">
                                    <select class="year-selector" id="yearSelector" onchange="updateCalendarDisplay()">
                                        <!-- Years will be populated by JavaScript -->
                                    </select>
                                    <select class="month-selector-dropdown" id="monthSelectorDropdown"
                                        onchange="updateCalendarDisplay()">
                                        <option value="0">January</option>
                                        <option value="1">February</option>
                                        <option value="2">March</option>
                                        <option value="3">April</option>
                                        <option value="4">May</option>
                                        <option value="5">June</option>
                                        <option value="6">July</option>
                                        <option value="7">August</option>
                                        <option value="8">September</option>
                                        <option value="9">October</option>
                                        <option value="10">November</option>
                                        <option value="11">December</option>
                                    </select>
                                </div>

                                <div class="calendar-grid">
                                    <div class="calendar-weekdays">
                                        <div class="calendar-weekday">Su</div>
                                        <div class="calendar-weekday">Mo</div>
                                        <div class="calendar-weekday">Tu</div>
                                        <div class="calendar-weekday">We</div>
                                        <div class="calendar-weekday">Th</div>
                                        <div class="calendar-weekday">Fr</div>
                                        <div class="calendar-weekday">Sa</div>
                                    </div>
                                    <div class="calendar-days" id="calendarDays">
                                        <!-- Days will be populated by JavaScript -->
                                    </div>
                                </div>

                                <div class="calendar-actions">
                                    <button type="button" class="calendar-btn" onclick="clearCalendar()">Clear</button>
                                    <button type="button" class="calendar-btn" onclick="selectToday()">Today</button>
                                    <button type="button" class="calendar-btn primary"
                                        onclick="applyCalendarSelection()">Apply</button>
                                </div>
                            </div>
                        </div>

                        <!-- Range Selector -->
                        <div class="range-selector">
                            <span style="font-size: 0.875rem; color: var(--gray-600); margin-right: 0.5rem;">Show:</span>
                            <button onclick="changeRange(3)" class="{{ $monthsToShow == 3 ? 'active' : '' }}"
                                id="show3M">3M</button>
                            <button onclick="changeRange(6)" class="{{ $monthsToShow == 6 ? 'active' : '' }}">6M</button>
                            <button onclick="changeRange(12)"
                                class="{{ $monthsToShow == 12 ? 'active' : '' }}">12M</button>
                        </div>

                        <!-- Reset to Current Month -->
                        <button class="nav-button secondary" onclick="resetToCurrentMonth()">
                            <i class="fas fa-calendar-day"></i> Today
                        </button>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="row mb-4">
                <div class="col-lg-8 mb-4">
                    <div class="chart-card">
                        <div class="chart-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 style="margin: 0; font-weight: 600; color: var(--gray-900);">📊 Grafik Overall</h6>
                                    <p style="margin: 0.25rem 0 0 0; font-size: 0.875rem; color: var(--gray-600);"
                                        id="chartSubtitle">
                                        {{ $monthsToShow }} kinerja bulan berakhir
                                        {{ Carbon\Carbon::createFromFormat('Y-m', $selectedMonth)->format('F Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="chart-body">
                            <div class="chart-loading" id="chartLoading" style="display: none;">
                                <div class="spinner"></div>
                            </div>
                            <canvas id="ticketChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 mb-4">
                    <div class="chart-card">
                        <div class="chart-header">
                            <div>
                                <h6 style="margin: 0; font-weight: 600; color: var(--gray-900);">⏱️ Processing Time
                                    Analytics
                                </h6>
                                <p style="margin: 0.25rem 0 0 0; font-size: 0.875rem; color: var(--gray-600);"
                                    id="processingSubtitle">
                                    Rata-rata waktu pemrosesan (jam) -
                                    {{ Carbon\Carbon::createFromFormat('Y-m', $selectedMonth)->format('F Y') }}
                                </p>
                            </div>
                        </div>
                        <div class="chart-body">
                            <div class="chart-loading" id="processingLoading" style="display: none;">
                                <div class="spinner"></div>
                            </div>
                            <canvas id="processingTimeChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Tickets Table -->
            <div class="row">
                <div class="col-12">
                    <div class="table-card">
                        <div class="table-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 style="margin: 0; font-weight: 600; color: var(--gray-900);">🎫 Recent Tickets</h6>
                                    <p style="margin: 0.25rem 0 0 0; font-size: 0.875rem; color: var(--gray-600);">Tiket
                                        Terbaru </p>
                                </div>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-outline btn-sm" onclick="loadRecentTickets()">
                                        <i class="fas fa-sync-alt"></i> Refresh
                                    </button>
                                    <a href="/admin/tickets" class="btn btn-primary btn-sm">
                                        <i class="fas fa-list"></i> View All
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table" id="recentTicketsTable">
                                <thead>
                                    <tr>
                                        <th>Tracking Code</th>
                                        <th>Title</th>
                                        <th>Reporter</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Processing Time</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="recentTicketsBody">
                                    @foreach ($recentTickets->take(3) as $ticket)
                                        <tr>
                                            <td>
                                                <span class="badge primary">{{ $ticket->code_tracking }}</span>
                                            </td>
                                            <td>{{ Str::limit($ticket->judul ?? 'No Title', 30) }}</td>
                                            <td>{{ $ticket->nama_pelapor ?? 'Anonymous' }}</td>
                                            <td>
                                                @if ($ticket->status == 'pending')
                                                    <span class="badge warning">⏳ Pending</span>
                                                @elseif($ticket->status == 'diterima/approved')
                                                    <span class="badge success">✅ Diterima/Approved</span>
                                                @elseif($ticket->status == 'selesai/completed')
                                                    <span class="badge info">🎯 Resolved</span>
                                                @elseif($ticket->status == 'ditolak/rejected')
                                                    <span class="badge danger">❌ Ditolak/Rejected</span>
                                                @else
                                                    <span class="badge secondary">{{ ucfirst($ticket->status) }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $ticket->created_at->diffForHumans() }}</td>
                                            <td>
                                                @if ($ticket->accepted_at)
                                                    <span
                                                        style="color: var(--green-600);">{{ $ticket->created_at->diffForHumans($ticket->accepted_at) }}</span>
                                                @else
                                                    <span style="color: var(--gray-500);">Not started</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <button class="btn btn-info btn-sm"
                                                        onclick="viewTicketDetails('{{ $ticket->id }}')">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    @if ($ticket->status == 'pending')
                                                        <button class="btn btn-success btn-sm"
                                                            onclick="acceptTicket('{{ $ticket->id }}')">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                        <button class="btn btn-danger btn-sm"
                                                            onclick="rejectTicket('{{ $ticket->id }}')">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @if ($recentTickets->count() == 0)
                                <div class="empty-state">
                                    <div class="mb-3">
                                        <i class="fas fa-inbox"></i>
                                    </div>
                                    <h6>No tickets found</h6>
                                    <p>There are no tickets to display at the moment.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Chart.js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js "></script>

    <script>
        /* =========  GLOBAL VARS  ========= */
        let currentMonth = '{{ $selectedMonth }}';
        let currentRange = {{ $monthsToShow }};
        let ticketChart, processingTimeChart;
        let calendarDate = new Date();
        let selectedDate = null;

        /* =========  SESSION PERSISTENCE  ========= */
        function initializeSessionState() {
            // Check if there's a saved month in localStorage
            const savedMonth = localStorage.getItem('dashboard_selected_month');
            const savedRange = localStorage.getItem('dashboard_selected_range');

            if (savedMonth && savedMonth !== currentMonth) {
                // Use saved month instead of server default
                currentMonth = savedMonth;
                console.log('Restored saved month:', currentMonth);

                // Update display immediately
                updateMonthDisplay(currentMonth);

                // Load data for saved month
                loadDataForMonth(currentMonth);
            }

            if (savedRange && parseInt(savedRange) !== currentRange) {
                // Use saved range
                currentRange = parseInt(savedRange);

                // Update range selector buttons
                document.querySelectorAll('.range-selector button').forEach(b => b.classList.remove('active'));
                document.querySelector(`.range-selector button[onclick="changeRange(${currentRange})"]`)?.classList.add(
                    'active');

                console.log('Restored saved range:', currentRange);
            }
        }

        function saveSessionState() {
            // Save current month and range to localStorage
            localStorage.setItem('dashboard_selected_month', currentMonth);
            localStorage.setItem('dashboard_selected_range', currentRange.toString());
            console.log('Saved session state:', {
                month: currentMonth,
                range: currentRange
            });
        }

        function clearSessionState() {
            // Clear saved state (useful for reset to today)
            localStorage.removeItem('dashboard_selected_month');
            localStorage.removeItem('dashboard_selected_range');
            console.log('Cleared session state');
        }

        /* =========  CALENDAR HELPERS  ========= */
        function initializeCalendar() {
            const currentDate = new Date(currentMonth + '-01');
            calendarDate = new Date(currentDate);

            // Populate year selector
            const yearSelector = document.getElementById('yearSelector');
            const thisYear = new Date().getFullYear();
            yearSelector.innerHTML = '';

            for (let y = thisYear - 5; y <= thisYear + 5; y++) {
                const opt = document.createElement('option');
                opt.value = y;
                opt.textContent = y;
                opt.selected = y === calendarDate.getFullYear();
                yearSelector.appendChild(opt);
            }

            // Set month selector
            document.getElementById('monthSelectorDropdown').value = calendarDate.getMonth();

            // Update calendar display
            updateCalendarDisplay();
        }

        function toggleCalendar() {
            const dd = document.getElementById('calendarDropdown');
            const isShown = dd.classList.toggle('show');
            if (isShown) {
                // Re-initialize calendar when opened
                initializeCalendar();
                setTimeout(() => document.addEventListener('click', closeCalendarOnOutsideClick), 100);
            } else {
                document.removeEventListener('click', closeCalendarOnOutsideClick);
            }
        }

        function closeCalendarOnOutsideClick(e) {
            if (!e.target.closest('.date-picker-container')) {
                document.getElementById('calendarDropdown').classList.remove('show');
                document.removeEventListener('click', closeCalendarOnOutsideClick);
            }
        }

        function navigateCalendar(dir) {
            calendarDate.setMonth(calendarDate.getMonth() + dir);
            document.getElementById('yearSelector').value = calendarDate.getFullYear();
            document.getElementById('monthSelectorDropdown').value = calendarDate.getMonth();
            updateCalendarDisplay();
        }

        function updateCalendarDisplay() {
            const y = +document.getElementById('yearSelector').value;
            const m = +document.getElementById('monthSelectorDropdown').value;
            calendarDate = new Date(y, m, 1);

            // Update calendar title
            document.getElementById('calendarTitle').textContent =
                calendarDate.toLocaleDateString('en-US', {
                    month: 'long',
                    year: 'numeric'
                });

            // Render calendar days
            renderCalendarDays();
        }

        function renderCalendarDays() {
            const container = document.getElementById('calendarDays');
            const y = calendarDate.getFullYear();
            const m = calendarDate.getMonth();

            container.innerHTML = '';

            // Get first day of month and number of days
            const firstDay = new Date(y, m, 1).getDay();
            const daysInMonth = new Date(y, m + 1, 0).getDate();
            const daysInPrevMonth = new Date(y, m, 0).getDate();

            // Add previous month trailing days
            for (let i = firstDay - 1; i >= 0; i--) {
                const dayElement = createDay(daysInPrevMonth - i, 'other-month');
                container.appendChild(dayElement);
            }

            // Add current month days
            for (let d = 1; d <= daysInMonth; d++) {
                const dayElement = createDay(d);

                // Check if this day is selected
                if (selectedDate &&
                    selectedDate.getFullYear() === y &&
                    selectedDate.getMonth() === m &&
                    selectedDate.getDate() === d) {
                    dayElement.classList.add('selected');
                }

                // Check if this is today
                const today = new Date();
                if (today.getFullYear() === y &&
                    today.getMonth() === m &&
                    today.getDate() === d) {
                    dayElement.classList.add('today');
                }

                dayElement.addEventListener('click', () => selectCalendarDate(y, m, d));
                container.appendChild(dayElement);
            }

            // Add next month leading days (fill to complete 6 weeks = 42 days)
            const totalCells = firstDay + daysInMonth;
            const remainingCells = 42 - totalCells;
            for (let d = 1; d <= remainingCells; d++) {
                const dayElement = createDay(d, 'other-month');
                container.appendChild(dayElement);
            }
        }

        function createDay(day, className = '') {
            const div = document.createElement('div');
            div.className = `calendar-day ${className}`;
            div.textContent = day;
            return div;
        }

        function selectCalendarDate(y, m, d) {
            selectedDate = new Date(y, m, d);

            // Remove previous selection
            document.querySelectorAll('.calendar-day.selected')
                .forEach(el => el.classList.remove('selected'));

            // Add selection to clicked day
            event.target.classList.add('selected');
        }

        function clearCalendar() {
            selectedDate = null;
            document.querySelectorAll('.calendar-day.selected')
                .forEach(el => el.classList.remove('selected'));
        }

        function selectToday() {
            const today = new Date();
            calendarDate = new Date(today.getFullYear(), today.getMonth(), 1);
            selectedDate = today;

            // Update selectors
            document.getElementById('yearSelector').value = today.getFullYear();
            document.getElementById('monthSelectorDropdown').value = today.getMonth();

            // Re-render calendar
            updateCalendarDisplay();
        }

        /* =========  CHART INITIALISATION  ========= */
        const initializeCharts = () => {
            // Clear previous chart data
            ticketChart?.destroy();
            processingTimeChart?.destroy();
            // Enhanced Ticket Trend Chart with gradient and animations
            const ctx = document.getElementById('ticketChart').getContext('2d');

            // Create gradients
            const totalGradient = ctx.createLinearGradient(0, 0, 0, 400);
            totalGradient.addColorStop(0, 'rgba(59, 130, 246, 0.8)');
            totalGradient.addColorStop(0.5, 'rgba(59, 130, 246, 0.4)');
            totalGradient.addColorStop(1, 'rgba(59, 130, 246, 0.1)');
            totalGradient.addColorStop(0, 'rgba(59, 130, 246, 0.8)');
            totalGradient.addColorStop(0.5, 'rgba(59, 130, 246, 0.4)');
            totalGradient.addColorStop(1, 'rgba(59, 130, 246, 0.1)');

            const acceptedGradient = ctx.createLinearGradient(0, 0, 0, 400);
            acceptedGradient.addColorStop(0, 'rgba(34, 197, 94, 0.8)');
            acceptedGradient.addColorStop(0.5, 'rgba(34, 197, 94, 0.4)');
            acceptedGradient.addColorStop(1, 'rgba(34, 197, 94, 0.1)');

            const resolvedGradient = ctx.createLinearGradient(0, 0, 0, 400);
            resolvedGradient.addColorStop(0, 'rgba(99, 102, 241, 0.8)');
            resolvedGradient.addColorStop(0.5, 'rgba(99, 102, 241, 0.4)');
            resolvedGradient.addColorStop(1, 'rgba(99, 102, 241, 0.1)');

            ticketChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($months),
                    datasets: [{
                            label: 'Total Tickets',
                            data: @json($totalCounts), // Ensure this data is accurate from the database
                            borderColor: '#3b82f6',
                            backgroundColor: totalGradient,
                            borderWidth: 3,
                            tension: 0.4,
                            fill: true,
                            pointRadius: 6,
                            pointHoverRadius: 10,
                            pointBackgroundColor: '#3b82f6',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 3,
                            pointHoverBackgroundColor: '#1d4ed8',
                            pointHoverBorderColor: '#ffffff',
                            pointHoverBorderWidth: 4,
                            shadowOffsetX: 3,
                            shadowOffsetY: 3,
                            shadowBlur: 10,
                            shadowColor: 'rgba(59, 130, 246, 0.3)'
                        },
                        {
                            label: 'Accepted',
                            data: @json($acceptedCounts),
                            borderColor: '#22c55e',
                            backgroundColor: acceptedGradient,
                            borderWidth: 3,
                            tension: 0.4,
                            fill: true,
                            pointRadius: 6,
                            pointHoverRadius: 10,
                            pointBackgroundColor: '#22c55e',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 3,
                            pointHoverBackgroundColor: '#16a34a',
                            pointHoverBorderColor: '#ffffff',
                            pointHoverBorderWidth: 4
                        },
                        {
                            label: 'Resolved',
                            data: @json($resolvedCounts),
                            borderColor: '#6366f1',
                            backgroundColor: resolvedGradient,
                            borderWidth: 3,
                            tension: 0.4,
                            fill: true,
                            pointRadius: 6,
                            pointHoverRadius: 10,
                            pointBackgroundColor: '#6366f1',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 3,
                            pointHoverBackgroundColor: '#4f46e5',
                            pointHoverBorderColor: '#ffffff',
                            pointHoverBorderWidth: 4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                pointStyle: 'circle',
                                padding: 20,
                                font: {
                                    size: 12,
                                    weight: '500'
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(255, 255, 255, 0.95)',
                            titleColor: '#374151',
                            bodyColor: '#6b7280',
                            borderColor: '#e5e7eb',
                            borderWidth: 1,
                            cornerRadius: 8,
                            displayColors: true,
                            padding: 12,
                            titleFont: {
                                size: 14,
                                weight: '600'
                            },
                            bodyFont: {
                                size: 13
                            },
                            callbacks: {
                                title: function(context) {
                                    return 'Month: ' + context[0].label;
                                },
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.parsed.y + ' tickets';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: true,
                                color: 'rgba(229, 231, 235, 0.5)',
                                drawBorder: false
                            },
                            ticks: {
                                color: '#6b7280',
                                font: {
                                    size: 11,
                                    weight: '500'
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                display: true,
                                color: 'rgba(229, 231, 235, 0.5)',
                                drawBorder: false
                            },
                            ticks: {
                                color: '#6b7280',
                                font: {
                                    size: 11,
                                    weight: '500'
                                },
                                callback: function(value) {
                                    return value + ' tickets';
                                }
                            }
                        }
                    },
                    elements: {
                        line: {
                            borderJoinStyle: 'round',
                            borderCapStyle: 'round'
                        }
                    },
                    animation: {
                        duration: 2000,
                        easing: 'easeInOutQuart'
                    }
                }
            });

            // Processing Time Donut Chart
            const processingCtx = document.getElementById('processingTimeChart').getContext('2d');

            const processingData = @json($processingTimeData['data'] ?? []);
            const processingLabels = @json($processingTimeData['labels'] ?? []);
            const dashboardTotalTickets = {{ $resolvedTickets }};

            // Create categories for donut chart with better error handling
            let fastProcessing = 0;
            let mediumProcessing = 0;
            let slowProcessing = 0;

            // Ensure processingData is an array and handle empty data
            if (Array.isArray(processingData) && processingData.length > 0) {
                processingData.forEach(time => {
                    const numTime = parseFloat(time) || 0;
                    if (numTime <= 24) fastProcessing++;
                    else if (numTime <= 72) mediumProcessing++;
                    else slowProcessing++;
                });
            }

            const chartTotal = fastProcessing + mediumProcessing + slowProcessing;
            if (chartTotal !== dashboardTotalTickets && dashboardTotalTickets > 0) {
                // Scale the processing data to match dashboard total
                const scaleFactor = dashboardTotalTickets / chartTotal;
                fastProcessing = Math.round(fastProcessing * scaleFactor);
                mediumProcessing = Math.round(mediumProcessing * scaleFactor);
                slowProcessing = dashboardTotalTickets - fastProcessing - mediumProcessing;
            }

            const donutColors = [
                '#22c55e', // Fast - Green
                '#f59e0b', // Medium - Amber
                '#ef4444' // Slow - Red
            ];

            const donutGradients = donutColors.map(color => {
                const gradient = processingCtx.createRadialGradient(150, 150, 0, 150, 150, 150);
                gradient.addColorStop(0, color);
                gradient.addColorStop(1, color + '80');
                return gradient;
            });

            processingTimeChart = new Chart(processingCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Cepat (≤24h)', 'Medium (24-72h)', 'Lambat (>72h)'],
                    datasets: [{
                        data: [fastProcessing, mediumProcessing, slowProcessing],
                        backgroundColor: donutGradients,
                        borderColor: ['#22c55e', '#f59e0b', '#ef4444'],
                        borderWidth: 3,
                        hoverBorderWidth: 5,
                        hoverOffset: 10,
                        cutout: '65%'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                pointStyle: 'circle',
                                padding: 20,
                                font: {
                                    size: 12,
                                    weight: '500'
                                },
                                generateLabels: function(chart) {
                                    const data = chart.data;
                                    if (data.labels.length && data.datasets.length) {
                                        return data.labels.map((label, i) => {
                                            const dataset = data.datasets[0];
                                            const value = dataset.data[i] || 0;
                                            const total = dataset.data.reduce((a, b) => (a || 0) + (
                                                b || 0), 0);
                                            const percentage = total > 0 ? ((value / total) * 100)
                                                .toFixed(1) : 0;

                                            return {
                                                text: `${label}: ${value} (${percentage}%)`,
                                                fillStyle: dataset.backgroundColor[i],
                                                strokeStyle: dataset.borderColor[i],
                                                lineWidth: dataset.borderWidth,
                                                hidden: false,
                                                index: i
                                            };
                                        });
                                    }
                                    return [];
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(255, 255, 255, 0.95)',
                            titleColor: '#374151',
                            bodyColor: '#6b7280',
                            borderColor: '#e5e7eb',
                            borderWidth: 1,
                            cornerRadius: 8,
                            padding: 12,
                            titleFont: {
                                size: 14,
                                weight: '600'
                            },
                            bodyFont: {
                                size: 13
                            },
                            callbacks: {
                                title: function(context) {
                                    return 'Waktu Pemrosesan';
                                },
                                label: function(context) {
                                    const label = context.label;
                                    const value = context.parsed || 0;
                                    const total = context.dataset.data.reduce((a, b) => (a || 0) + (b || 0),
                                        0);
                                    const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                    return `${label}: ${value} tickets (${percentage}%)`;
                                }
                            }
                        }
                    },
                    animation: {
                        animateRotate: true,
                        animateScale: true,
                        duration: 2000,
                        easing: 'easeInOutQuart'
                    },
                    elements: {
                        arc: {
                            borderRadius: 8
                        }
                    }
                }
            });

            // Add center text for donut chart
            const centerTextPlugin = {
                id: 'centerText',
                beforeDraw: function(chart) {
                    if (chart.config.type === 'doughnut') {
                        const ctx = chart.ctx;
                        const centerX = (chart.chartArea.left + chart.chartArea.right) / 2;
                        const centerY = (chart.chartArea.top + chart.chartArea.bottom) / 2;

                        ctx.save();
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';

                        // Main text
                        ctx.font = 'bold 24px Inter, sans-serif';
                        ctx.fillStyle = '#374151';
                        const total = chart.data.datasets[0].data.reduce((a, b) => (a || 0) + (b || 0), 0);
                        ctx.fillText(total.toString(), centerX, centerY - 10);

                        // Subtitle
                        ctx.font = '12px Inter, sans-serif';
                        ctx.fillStyle = '#6b7280';
                        ctx.fillText('Total Tickets', centerX, centerY + 15);

                        ctx.restore();
                    }
                }
            };

            Chart.register(centerTextPlugin);
        };

        /* =========  DATA LOADING  ========= */
        function loadDataForMonth(month) {
            showLoading(true);
            fetch('/admin/dashboard/chart-data', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        month: month,
                        months_to_show: currentRange
                    })
                })
                .then(r => r.json())
                .then(d => {
                    if (d.success) {
                        updateChartsData(d.data);
                        // fetch processing-time data too
                        return fetch('/admin/dashboard/processing-time', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                month: month
                            })
                        });
                    }
                })
                .then(r => r.json())
                .then(d => {
                    if (d.success) updateProcessingTimeChart(d.data);
                    showLoading(false);
                })
                .catch(e => {
                    console.error(e);
                    showLoading(false);
                    showNotification('danger', 'Failed to load data');
                });
        }

        function updateChartsData(chartData, processingData = null) {
            ticketChart.data.labels = chartData.months;
            ticketChart.data.datasets[0].data = chartData.totalCounts;
            ticketChart.data.datasets[1].data = chartData.acceptedCounts;
            ticketChart.data.datasets[2].data = chartData.resolvedCounts;
            ticketChart.update('none');

            if (processingData) updateProcessingTimeChart(processingData);
        }

        function updateProcessingTimeChart(data) {
            const processingData = data.data || [];

            let fastProcessing = 0;
            let mediumProcessing = 0;
            let slowProcessing = 0;

            if (Array.isArray(processingData) && processingData.length > 0) {
                processingData.forEach(time => {
                    const numTime = parseFloat(time) || 0;
                    if (numTime <= 24) fastProcessing++;
                    else if (numTime <= 72) mediumProcessing++;
                    else slowProcessing++;
                });
            }

            const currentTotal = parseInt(document.getElementById('totalTickets').textContent) || 0;
            const chartTotal = fastProcessing + mediumProcessing + slowProcessing;
            if (chartTotal !== currentTotal && currentTotal > 0) {
                const scaleFactor = currentTotal / chartTotal;
                fastProcessing = Math.round(fastProcessing * scaleFactor);
                mediumProcessing = Math.round(mediumProcessing * scaleFactor);
                slowProcessing = currentTotal - fastProcessing - mediumProcessing;
            }

            // Update chart data with animation
            processingTimeChart.data.datasets[0].data = [fastProcessing, mediumProcessing, slowProcessing];
            processingTimeChart.update('active');
        }

        /* =========  ADDITIONAL CHART FUNCTIONS  ========= */
        function updateChart(filterType) {
            // This function can be used to filter the main chart by ticket type
            console.log('Filtering chart by:', filterType);
            // Implementation can be added based on requirements
        }

        function loadRecentTickets() {
            // Refresh recent tickets table
            fetch('/admin/dashboard/recent-tickets')
                .then(r => r.json())
                .then(d => {
                    if (d.success) {
                        // Update recent tickets table
                        const tbody = document.getElementById('recentTicketsBody');
                        tbody.innerHTML = d.html;
                    }
                })
                .catch(console.error);
        }

        function viewTicketDetails(ticketId) {
            // Open ticket details modal or redirect
            window.open(`/admin/tickets/${ticketId}`, '_blank');
        }

        function acceptTicket(ticketId) {
            if (confirm('Are you sure you want to accept this ticket?')) {
                fetch(`/admin/tickets/${ticketId}/accept`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(r => r.json())
                    .then(d => {
                        if (d.success) {
                            showNotification('success', 'Tiket berhasil diterima');
                            loadRecentTickets();
                            // Refresh stats
                            location.reload();
                        } else {
                            showNotification('danger', d.message || 'Tiket gagal diterima');
                        }
                    })
                    .catch(e => {
                        console.error(e);
                        showNotification('danger', 'Tiket gagal diterima');
                    });
            }
        }

        function rejectTicket(ticketId) {
            if (confirm('Apakah anda yakin ingin menolak tiket ini?')) {
                fetch(`/admin/tickets/${ticketId}/reject`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(r => r.json())
                    .then(d => {
                        if (d.success) {
                            showNotification('success', 'Tiket Berhasil ditolak');
                            loadRecentTickets();
                            // Refresh stats
                            location.reload();
                        } else {
                            showNotification('danger', d.message || 'Failed to reject ticket');
                        }
                    })
                    .catch(e => {
                        console.error(e);
                        showNotification('danger', 'Failed to reject ticket');
                    });
            }
        }

        /* =========  MONTH NAVIGATION  ========= */
        function navigatePreviousMonth() {
            showLoading(true);
            fetch('/admin/dashboard/navigate/previous', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        current_month: currentMonth,
                        months_to_show: currentRange
                    })
                })
                .then(r => r.json())
                .then(d => {
                    if (d.success) {
                        updateChartsData(d.data, d.processingTimeData);
                        currentMonth = d.newMonth;
                        updateMonthDisplay(currentMonth);

                        // Save session state
                        saveSessionState();
                    }
                    showLoading(false);
                })
                .catch(e => {
                    console.error(e);
                    showLoading(false);
                    showNotification('danger', 'Gagal menavigasi ke bulan sebelumnya');
                });
        }

        function navigateNextMonth() {
            showLoading(true);
            fetch('/admin/dashboard/navigate/next', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        current_month: currentMonth,
                        months_to_show: currentRange
                    })
                })
                .then(r => r.json())
                .then(d => {
                    if (d.success) {
                        updateChartsData(d.data, d.processingTimeData);
                        currentMonth = d.newMonth;
                        updateMonthDisplay(currentMonth);

                        // Save session state
                        saveSessionState();
                    }
                    showLoading(false);
                })
                .catch(e => {
                    console.error(e);
                    showLoading(false);
                    showNotification('danger', 'Gagal menavigasi ke bulan Selanjutnya');
                });
        }

        function changeRange(months) {
            document.querySelectorAll('.range-selector button').forEach(b => b.classList.remove('active'));
            event.target.classList.add('active');
            currentRange = months;
            showLoading(true);

            fetch('/admin/dashboard/chart-data', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        month: currentMonth,
                        months_to_show: months
                    })
                })
                .then(r => r.json())
                .then(d => {
                    if (d.success) {
                        updateChartsData(d.data, d.processingTimeData);
                        updateSubtitles();

                        // Save session state
                        saveSessionState();
                    }
                    showLoading(false);
                })
                .catch(e => {
                    console.error(e);
                    showLoading(false);
                    showNotification('danger', 'Gagal Mengganti Jarak');
                });
        }

        function resetToCurrentMonth() {
            const today = new Date();
            const newMonth = today.getFullYear() + '-' + String(today.getMonth() + 1).padStart(2, '0');
            currentMonth = newMonth;
            currentRange = {{ $monthsToShow }}; // Reset to default range

            // Clear saved session state
            clearSessionState();

            // Update range selector buttons
            document.querySelectorAll('.range-selector button').forEach(b => b.classList.remove('active'));
            document.querySelector(`.range-selector button[onclick="changeRange(${currentRange})"]`)?.classList.add(
                'active');

            updateMonthDisplay(newMonth);
            loadDataForMonth(newMonth);

            showNotification('info', 'Reset ke bulan saat ini');
        }

        function applyCalendarSelection() {
            if (!selectedDate) {
                showNotification('warning', 'Silakan pilih tanggal terlebih dahulu');
                return;
            }

            const newMonth = selectedDate.getFullYear() + '-' +
                String(selectedDate.getMonth() + 1).padStart(2, '0');

            // Update date picker input
            document.getElementById('datePicker').value =
                selectedDate.toLocaleDateString('en-US', {
                    month: 'long',
                    year: 'numeric'
                });

            // Close calendar
            document.getElementById('calendarDropdown').classList.remove('show');
            document.removeEventListener('click', closeCalendarOnOutsideClick);

            // Update current month and load data
            currentMonth = newMonth;
            updateMonthDisplay(newMonth);
            loadDataForMonth(newMonth);

            // Save session state
            saveSessionState();

            showNotification('success', 'Bulan berhasil diperbarui');
        }

        /* =========  UI HELPERS  ========= */
        function showLoading(flag) {
            const cl = document.getElementById('chartLoading');
            const pl = document.getElementById('processingLoading');
            cl.style.display = flag ? 'flex' : 'none';
            pl.style.display = flag ? 'none' : 'none';
            ['prevMonthBtn', 'nextMonthBtn', 'datePicker']
            .forEach(id => document.getElementById(id).disabled = flag);
        }

        function updateMonthDisplay(monthValue) {
            const m = new Date(monthValue + '-01').toLocaleDateString('en-US', {
                month: 'long',
                year: 'numeric'
            });
            document.getElementById('currentMonthDisplay').textContent = m;
            document.getElementById('datePicker').value = m;
            updateSubtitles();
        }

        function updateSubtitles() {
            const m = new Date(currentMonth + '-01').toLocaleDateString('en-US', {
                month: 'long',
                year: 'numeric'
            });
            document.getElementById('chartSubtitle').textContent = `${currentRange} kinerja bulan berakhir ${m}`;
            document.getElementById('processingSubtitle').textContent = `Rata-rata waktu pemrosesan (jam) - ${m}`;
        }

        function showNotification(type, message) {
            const icon = type === 'success' ? 'fa-check-circle' :
                type === 'info' ? 'fa-info-circle' :
                'fa-exclamation-triangle';
            const alert = document.createElement('div');
            alert.className = `alert alert-${type} position-fixed top-0 end-0 m-3`;
            alert.style.zIndex = 9999;
            alert.innerHTML = `
                <i class="fas ${icon} me-2"></i>${message}
                <button type="button" class="btn-close ms-2" onclick="this.parentElement.remove()"></button>
            `;
            document.body.appendChild(alert);
            setTimeout(() => alert.remove(), 4000);
        }

        /* =========  BOOTSTRAP  ========= */
        document.addEventListener('DOMContentLoaded', () => {
            initializeCharts();
            initializeCalendar();
            initializeSessionState();

            // keyboard shortcuts
            document.addEventListener('keydown', e => {
                if (e.ctrlKey || e.metaKey) {
                    switch (e.key) {
                        case 'ArrowLeft':
                            e.preventDefault();
                            navigatePreviousMonth();
                            break;
                        case 'ArrowRight':
                            e.preventDefault();
                            navigateNextMonth();
                            break;
                        case 'h':
                        case 'H':
                            e.preventDefault();
                            resetToCurrentMonth();
                            break;
                    }
                }
                if (e.key === 'Escape') {
                    document.getElementById('calendarDropdown').classList.remove('show');
                }
            });

            // auto-refresh stats every 30 seconds
            setInterval(() => fetch('/admin/dashboard/stats')
                .then(r => r.json())
                .then(d => {
                    document.getElementById('totalTickets').textContent = d.total_tickets;
                    document.getElementById('pendingTickets').textContent = d.pending_tickets;
                    document.getElementById('acceptedTickets').textContent = d.accepted_tickets;
                    document.getElementById('resolvedTickets').textContent = d.resolved_tickets;
                    document.getElementById('rejectedTickets').textContent = d.rejected_tickets;
                })
                .catch(console.error), 30000);
        });
    </script>
@endsection
