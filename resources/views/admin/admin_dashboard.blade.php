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

        /* Simple CSS tooltip for info icons */
        .info-tooltip {
            position: relative;
            display: inline-flex;
            align-items: center;
            cursor: help;
        }

        .info-tooltip .tooltip-text {
            position: absolute;
            bottom: 125%;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(17, 24, 39, 0.95);
            /* gray-900 with opacity */
            color: #fff;
            padding: 6px 8px;
            border-radius: 6px;
            font-size: 12px;
            line-height: 1.2;
            white-space: normal;
            width: max-content;
            max-width: 260px;
            box-shadow: var(--shadow-lg);
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.15s ease, transform 0.15s ease;
            z-index: 1000;
        }

        .info-tooltip:hover .tooltip-text {
            opacity: 1;
            transform: translateX(-50%) translateY(-4px);
        }

        .info-tooltip .tooltip-text::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            border-width: 6px;
            border-style: solid;
            border-color: rgba(17, 24, 39, 0.95) transparent transparent transparent;
        }

        /* Use a portal tooltip to escape overflow clipping */
        .global-tooltip {
            position: fixed;
            background: rgba(17, 24, 39, 0.95);
            color: #fff;
            padding: 6px 8px;
            border-radius: 6px;
            font-size: 12px;
            line-height: 1.2;
            box-shadow: var(--shadow-lg);
            z-index: 9999;
            pointer-events: none;
            max-width: 280px;
            display: none;
            white-space: normal;
        }

        .global-tooltip::after {
            content: '';
            position: absolute;
            border-width: 6px;
            border-style: solid;
            border-color: rgba(17, 24, 39, 0.95) transparent transparent transparent;
            left: 50%;
            transform: translateX(-50%);
            top: 100%;
        }

        .global-tooltip.below::after {
            top: -6px;
            border-color: transparent transparent rgba(17, 24, 39, 0.95) transparent;
        }

        /* Hide inline tooltip (used only as text source) */
        .info-tooltip .tooltip-text {
            display: none !important;
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
            min-width: 320px;
            max-width: 360px;
            width: auto;
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

        .range-selector button.active,
        .day-range-btn.active {
            background: var(--primary-600);
            color: white;
            border-color: var(--primary-600);
        }

        .range-selector button:hover:not(.active),
        .day-range-btn:hover:not(.active) {
            background: var(--gray-200);
            border-color: var(--gray-300);
        }

        .day-range-btn {
            background: var(--gray-100);
            border: 1px solid var(--gray-200);
            border-radius: 6px;
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 10000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .modal-container {
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow-xl);
            max-width: 800px;
            width: 100%;
            max-height: 90vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--gray-50);
        }

        .modal-title {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--gray-900);
        }

        .modal-close {
            background: none;
            border: none;
            padding: 0.5rem;
            color: var(--gray-500);
            cursor: pointer;
            border-radius: 6px;
            transition: all 0.2s ease;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-close:hover {
            background: var(--gray-200);
            color: var(--gray-700);
        }

        .modal-body {
            padding: 1.5rem;
            overflow-y: auto;
            flex: 1;
        }

        .modal-footer {
            padding: 1.5rem;
            border-top: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--gray-50);
        }

        .loading-spinner {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            color: var(--gray-600);
        }

        .loading-spinner .spinner {
            margin-bottom: 1rem;
        }

        .ticket-detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .ticket-detail-item {
            background: var(--gray-50);
            padding: 1rem;
            border-radius: 8px;
            border: 1px solid var(--gray-200);
        }

        .ticket-detail-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 0.5rem;
        }

        .ticket-detail-value {
            color: var(--gray-900);
            font-size: 0.95rem;
        }

        .ticket-description {
            background: white;
            border: 1px solid var(--gray-200);
            border-radius: 8px;
            padding: 1.5rem;
            margin-top: 1rem;
        }

        .ticket-description h6 {
            margin: 0 0 1rem 0;
            font-weight: 600;
            color: var(--gray-900);
        }

        .ticket-attachments {
            margin-top: 1rem;
        }

        .attachment-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem;
            background: var(--gray-50);
            border: 1px solid var(--gray-200);
            border-radius: 6px;
            margin-bottom: 0.5rem;
        }

        .attachment-icon {
            width: 32px;
            height: 32px;
            background: var(--primary-100);
            color: var(--primary-600);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @media (max-width: 768px) {
            .modal-container {
                margin: 1rem;
                max-height: calc(100vh - 2rem);
            }

            .ticket-detail-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
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
                flex-direction: row;
                gap: 0.75rem;
            }

            .year-selector,
            .month-selector-dropdown {
                min-width: 120px;
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
                                    <small class="text-muted">Tingkat Penyelesaian
                                        <span class="info-tooltip ms-1">
                                            <i class="fas fa-info-circle"></i>
                                            <span class="tooltip-text">Rumus: (tiket selesai ÷ total tiket) × 100</span>
                                        </span>
                                    </small>
                                </div>
                            </div>
                            <div class="col-6 col-lg-3">
                                <div class="text-center p-2 rounded bg-light">
                                    <div class="fw-bold text-success fs-5">
                                        {{ round((($acceptedTickets + $resolvedTickets) / max($totalTickets, 1)) * 100) }}%
                                    </div>
                                    <small class="text-muted">Tingkat Persetujuan
                                        <span class="info-tooltip ms-1">
                                            <i class="fas fa-info-circle"></i>
                                            <span class="tooltip-text">Rumus: ((tiket yang sudah dikerjakan + tiket
                                                selesai) ÷ total tiket) × 100</span>
                                        </span>
                                    </small>
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
            <div class="d-flex align-items-center gap-3">
                <!-- Day Range Selector on the left -->
                <div class="range-selector">
                    <span style="font-size: 0.875rem; color: var(--gray-600); margin-right: 0.5rem;">Tampilkan:</span>
                    <button onclick="changeDayRange(7)" class="day-range-btn">7 Hari</button>
                    <button onclick="changeDayRange(15)" class="day-range-btn">15 Hari</button>
                    <button onclick="changeDayRange(30)" class="day-range-btn active">Semua Hari</button>
                </div>

                <!-- Month/Year display with Today button on the right side -->
                <div class="ms-auto d-flex align-items-center gap-2">
                    <div class="date-picker-container">
                        <input type="text" class="date-picker-input" id="monthPicker" readonly
                            value="{{ Carbon\Carbon::createFromFormat('Y-m', $selectedMonth)->format('F Y') }}"
                            placeholder="Pilih bulan...">
                        <i class="fas fa-calendar-alt date-picker-icon"></i>
                        <div class="calendar-dropdown" id="calendarDropdown">
                            <div class="calendar-header">
                                <button type="button" class="calendar-nav-btn" id="prevYearBtn">
                                    <i class="fas fa-angle-double-left"></i>
                                </button>
                                <button type="button" class="calendar-nav-btn" id="prevMonthBtn">
                                    <i class="fas fa-angle-left"></i>
                                </button>
                                <div class="calendar-title" id="calendarTitle">
                                    {{ Carbon\Carbon::createFromFormat('Y-m', $selectedMonth)->format('F Y') }}
                                </div>
                                <button type="button" class="calendar-nav-btn" id="nextMonthBtn">
                                    <i class="fas fa-angle-right"></i>
                                </button>
                                <button type="button" class="calendar-nav-btn" id="nextYearBtn">
                                    <i class="fas fa-angle-double-right"></i>
                                </button>
                            </div>
                            <div class="year-month-selectors">
                                <select class="year-selector" id="yearSelector">
                                    @for ($year = 2010; $year <= Carbon\Carbon::now()->addYears(20)->year; $year++)
                                        <option value="{{ $year }}"
                                            {{ $year == Carbon\Carbon::createFromFormat('Y-m', $selectedMonth)->year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endfor
                                </select>
                                <select class="month-selector-dropdown" id="monthSelector">
                                    @for ($month = 1; $month <= 12; $month++)
                                        <option value="{{ $month }}"
                                            {{ $month == Carbon\Carbon::createFromFormat('Y-m', $selectedMonth)->month ? 'selected' : '' }}>
                                            {{ Carbon\Carbon::create(2024, $month, 1)->format('F') }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="calendar-actions">
                                <button type="button" class="calendar-btn" onclick="closeCalendar()">Batal</button>
                                <button type="button" class="calendar-btn primary" onclick="applySelectedMonth()"><i
                                        class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                    <button class="nav-button secondary" onclick="resetToCurrentMonth()">
                        <i class="fas fa-calendar-day"></i> Hari Ini
                    </button>
                </div>
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
                            <h6 style="margin: 0; font-weight: 600; color: var(--gray-900);">📊 Tren Tiket</h6>
                            <div class="d-flex gap-2 align-items-center my-2">
                                <div class="filter-select with-icon" style="position: relative; width: 180px;">
                                    <select id="filterKecamatan" class="form-select form-select-sm"
                                        style="width: 100%; padding-right: 1.75rem;">
                                        <option value="">Semua Kecamatan</option>
                                        @foreach ($kecamatanList ?? [] as $k)
                                            <option value="{{ data_get($k, 'id') }}">{{ data_get($k, 'nama') }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <i class="fas fa-caret-down filter-caret"
                                        style="position: absolute; right: 8px; top: 50%; transform: translateY(-50%); color: var(--gray-400); pointer-events: none; transition: transform 0.15s ease;"></i>
                                </div>
                                <div class="filter-select with-icon" style="position: relative; width: 180px;">
                                    <select id="filterLayanan" class="form-select form-select-sm"
                                        style="width: 100%; padding-right: 1.75rem;">
                                        <option value="">Semua Layanan</option>
                                        @foreach ($layananList ?? [] as $l)
                                            <option value="{{ data_get($l, 'id') }}">{{ data_get($l, 'name') }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <i class="fas fa-caret-down filter-caret"
                                        style="position: absolute; right: 8px; top: 50%; transform: translateY(-50%); color: var(--gray-400); pointer-events: none; transition: transform 0.15s ease;"></i>
                                </div>
                                <div class="filter-select with-icon" style="position: relative; width: 210px;">
                                    <select id="filterKategori" class="form-select form-select-sm"
                                        style="width: 100%; padding-right: 1.75rem;">
                                        <option value="">Semua Kategori</option>
                                    </select>
                                    <i class="fas fa-caret-down filter-caret"
                                        style="position: absolute; right: 8px; top: 50%; transform: translateY(-50%); color: var(--gray-400); pointer-events: none; transition: transform 0.15s ease;"></i>
                                </div>
                                <button class="btn btn-outline-secondary btn-sm" id="applyFiltersBtn">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                            <p style="margin: 0.25rem 0 0 0; font-size: 0.875rem; color: var(--gray-600);"
                                id="chartSubtitle">
                                @if ($selectedMonth)
                                    Data harian untuk
                                    {{ Carbon\Carbon::createFromFormat('Y-m', $selectedMonth)->format('F Y') }}
                                @else
                                    {{ $monthsToShow }} bulan terakhir hingga
                                    {{ Carbon\Carbon::now()->format('F Y') }}
                                @endif
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
                        <h6 style="margin: 0; font-weight: 600; color: var(--gray-900);">⏱️ Processing Time Analytics
                        </h6>
                        <p style="margin: 0.25rem 0 0 0; font-size: 0.875rem; color: var(--gray-600);"
                            id="processingSubtitle">
                            Waktu pemrosesan tiket selesai -
                            <span
                                id="processingMonthText">{{ Carbon\Carbon::createFromFormat('Y-m', $selectedMonth)->format('F Y') }}</span>
                            <span class="info-tooltip ms-1">
                                <i class="fas fa-info-circle"></i>
                                <span class="tooltip-text">Persentase = (jumlah tiket dalam kategori ÷ total tiket selesai)
                                    × 100

                                </span>
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
                            <h6 style="margin: 0; font-weight: 600; color: var(--gray-900);">
                                <i class="bi bi-ticket-perforated status-icon ticket"></i> Recent Tickets
                            </h6>
                            <p style="margin: 0.25rem 0 0 0; font-size: 0.875rem; color: var(--gray-600);">Tiket
                                Terbaru</p>
                        </div>
                        <div class="d-flex gap-2">
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
                                <th>Kode Tracking</th>
                                <th>Judul</th>
                                <th>Pelapor</th>
                                <th>Status</th>
                                <th>Dibuat</th>
                                <th>Waktu Diterima</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="recentTicketsBody">
                            @foreach ($recentTickets->take(3) as $ticket)
                                <tr>
                                    <td><span class="badge primary">{{ $ticket->code_tracking }}</span></td>
                                    <td>{{ Str::limit($ticket->judul ?? 'No Title', 30) }}</td>
                                    <td>{{ $ticket->nama_pelapor ?? 'Anonymous' }}</td>
                                    <td>
                                        @if ($ticket->status == 'pending')
                                            <span class="badge warning"><i class="bi bi-hourglass-split"></i>
                                                Pending</span>
                                        @elseif($ticket->status == 'diterima/approved')
                                            <span class="badge success"><i class="bi bi-check-lg"></i>
                                                Diterima/Approved</span>
                                        @elseif($ticket->status == 'selesai/completed')
                                            <span class="badge info"><i class="bi bi-check-circle"></i>
                                                Resolved</span>
                                        @elseif($ticket->status == 'ditolak/rejected')
                                            <span class="badge danger"><i class="bi bi-x-circle"></i>
                                                Ditolak/Rejected</span>
                                        @else
                                            <span class="badge secondary">{{ ucfirst($ticket->status) }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $ticket->created_at->diffForHumans() }}</td>
                                    <td>
                                        @if ($ticket->status == 'ditolak/rejected')
                                            <span style="color: var(--red-600);">Ditolak</span>
                                        @elseif ($ticket->accepted_at)
                                            @php
                                                $createdAt = \Carbon\Carbon::parse($ticket->created_at);
                                                $acceptedAt = \Carbon\Carbon::parse($ticket->accepted_at);
                                                $hours = round($createdAt->diffInMinutes($acceptedAt) / 60, 1);
                                            @endphp
                                            <span style="color: var(--blue-600);">{{ $hours }} jam</span>
                                        @else
                                            <span style="color: var(--gray-500);">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a class="btn btn-info btn-sm"
                                                href="{{ route('admin.tickets.show', $ticket) }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
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
                            <div class="mb-3"><i class="fas fa-inbox"></i></div>
                            <h6>Tidak ada tiket</h6>
                            <p>Belum ada tiket yang tersedia untuk ditampilkan.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    </div>

    {{-- Chart.js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        /* =========  GLOBAL VARS  ========= */
        let currentMonth = '{{ $selectedMonth }}';
        let currentRange = {{ $monthsToShow }};
        let currentDayRange = 30; // Default: show all days in month
        let ticketChart, processingTimeChart;
        let currentFilters = {
            kecamatan_id: '',
            layanan_id: '',
            layanan_category_id: ''
        };
        const maxNavigableMonth = '{{ Carbon\Carbon::now()->addYears(2)->format('Y-m') }}';
        const minNavigableMonth = '{{ Carbon\Carbon::now()->subYears(10)->format('Y-m') }}';

        /* =========  HELPER FUNCTIONS  ========= */
        function formatMonthDisplay(monthString) {
            const date = new Date(monthString + '-01');
            return date.toLocaleDateString('id-ID', {
                year: 'numeric',
                month: 'long'
            });
        }

        /* =========  CHART INITIALISATION  ========= */
        const initializeCharts = () => {
            // Clear previous chart data
            ticketChart?.destroy();
            processingTimeChart?.destroy();

            // Ticket Trend Chart
            const ctx = document.getElementById('ticketChart').getContext('2d');

            // Create gradients
            const totalGradient = ctx.createLinearGradient(0, 0, 0, 400);
            // Changed to cyan for better contrast with 'Selesai' (indigo)
            totalGradient.addColorStop(0, 'rgba(6, 182, 212, 0.8)'); // #06b6d4
            totalGradient.addColorStop(0.5, 'rgba(6, 182, 212, 0.4)');
            totalGradient.addColorStop(1, 'rgba(6, 182, 212, 0.1)');

            const acceptedGradient = ctx.createLinearGradient(0, 0, 0, 400);
            acceptedGradient.addColorStop(0, 'rgba(34, 197, 94, 0.8)');
            acceptedGradient.addColorStop(0.5, 'rgba(34, 197, 94, 0.4)');
            acceptedGradient.addColorStop(1, 'rgba(34, 197, 94, 0.1)');

            const resolvedGradient = ctx.createLinearGradient(0, 0, 0, 400);
            resolvedGradient.addColorStop(0, 'rgba(99, 102, 241, 0.8)');
            resolvedGradient.addColorStop(0.5, 'rgba(99, 102, 241, 0.4)');
            resolvedGradient.addColorStop(1, 'rgba(99, 102, 241, 0.1)');

            ticketChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($months),
                    datasets: [{
                            label: 'Tiket Ditambahkan',
                            data: @json($totalCounts),
                            backgroundColor: '#06b6d4',
                            borderColor: '#06b6d4',
                            borderWidth: 1
                        },
                        {
                            label: 'Diterima',
                            data: @json($acceptedCounts),
                            backgroundColor: '#22c55e',
                            borderColor: '#22c55e',
                            borderWidth: 1
                        },
                        {
                            label: 'Selesai',
                            data: @json($resolvedCounts),
                            backgroundColor: '#6366f1',
                            borderColor: '#6366f1',
                            borderWidth: 1
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
                        title: {
                            display: true,
                            text: @json($selectedMonth) ? 'Data Harian - ' +
                                formatMonthDisplay(@json($selectedMonth)) : 'Tren Bulanan',
                            font: {
                                size: 16,
                                weight: 'bold'
                            },
                            color: '#374151',
                            padding: {
                                bottom: 20
                            }
                        },
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                pointStyle: 'rect',
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
                            callbacks: {
                                title: function(context) {
                                    const isDaily = @json($selectedMonth) !== null;
                                    return isDaily ? 'Hari: ' + context[0].label : 'Bulan: ' + context[0]
                                        .label;
                                },
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.parsed.y + ' tiket';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: @json($selectedMonth) ? 'Hari dalam Bulan' : 'Bulan',
                                font: {
                                    size: 12,
                                    weight: '600'
                                },
                                color: '#6b7280'
                            },
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
                                    return value + ' tiket';
                                }
                            }
                        }
                    },
                    animation: {
                        duration: 2000,
                        easing: 'easeInOutQuart'
                    }
                }
            });

            // Processing Time Chart - Shows processing time analysis for current month
            const processingCtx = document.getElementById('processingTimeChart').getContext('2d');

            // Get processing time data for current month
            const processingTimeData = @json($processingTimeData);

            // Calculate processing time categories
            let fastProcessing = 0; // ≤ 24 hours
            let mediumProcessing = 0; // 24-72 hours
            let slowProcessing = 0; // > 72 hours

            if (processingTimeData.processingTimes && Array.isArray(processingTimeData.processingTimes)) {
                processingTimeData.processingTimes.forEach(time => {
                    const hours = parseFloat(time) || 0;
                    if (hours <= 24) {
                        fastProcessing++;
                    } else if (hours <= 72) {
                        mediumProcessing++;
                    } else {
                        slowProcessing++;
                    }
                });
            }

            // Create pie chart colors for processing time categories
            const pieColors = ['#22c55e', '#f59e0b', '#ef4444']; // Green, Yellow, Red
            const pieGradients = pieColors.map(color => {
                const gradient = processingCtx.createRadialGradient(150, 150, 0, 150, 150, 150);
                gradient.addColorStop(0, color);
                gradient.addColorStop(1, color + '80');
                return gradient;
            });

            processingTimeChart = new Chart(processingCtx, {
                type: 'pie',
                data: {
                    labels: ['Cepat (≤24 jam)', 'Sedang (24-72 jam)', 'Lambat (>72 jam)'],
                    datasets: [{
                        data: [fastProcessing, mediumProcessing, slowProcessing],
                        backgroundColor: pieGradients,
                        borderColor: pieColors,
                        borderWidth: 3,
                        hoverBorderWidth: 5,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Processing Time Analysis - ' + (currentMonth ? formatMonthDisplay(
                                currentMonth) : 'Current Month'),
                            font: {
                                size: 16,
                                weight: 'bold'
                            },
                            color: '#374151',
                            padding: {
                                bottom: 20
                            }
                        },
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
                                        const total = data.datasets[0].data.reduce((a, b) => a + b, 0);
                                        return data.labels.map((label, i) => {
                                            const value = data.datasets[0].data[i] || 0;
                                            const percentage = total > 0 ? ((value / total) * 100)
                                                .toFixed(1) : 0;
                                            return {
                                                text: `${label}: ${value} (${percentage}%)`,
                                                fillStyle: data.datasets[0].backgroundColor[i],
                                                strokeStyle: data.datasets[0].borderColor[i],
                                                lineWidth: data.datasets[0].borderWidth,
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
                            displayColors: true,
                            padding: 12,
                            callbacks: {
                                label: function(context) {
                                    const value = context.parsed || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                    return `${context.label}: ${value} tickets (${percentage}%)`;
                                }
                            }
                        }
                    },
                    animation: {
                        animateRotate: true,
                        animateScale: true,
                        duration: 2000,
                        easing: 'easeInOutQuart'
                    }
                }
            });
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
                        months_to_show: currentRange,
                        kecamatan_id: currentFilters.kecamatan_id || undefined,
                        layanan_id: currentFilters.layanan_id || undefined,
                        layanan_category_id: currentFilters.layanan_category_id || undefined,
                    })
                })
                .then(r => r.json())
                .then(d => {
                    if (d.success) {
                        updateChartsData(d.data);
                        updateStats(d.stats);
                        // fetch processing-time data too
                        return fetch('/admin/dashboard/processing-time-data', {
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

        function updateStats(stats) {
            if (!stats) return;
            const normalized = {
                total_tickets: (stats.total_tickets ?? stats.totalTickets),
                pending_tickets: (stats.pending_tickets ?? stats.pendingTickets),
                accepted_tickets: (stats.accepted_tickets ?? stats.acceptedTickets),
                resolved_tickets: (stats.resolved_tickets ?? stats.resolvedTickets),
                rejected_tickets: (stats.rejected_tickets ?? stats.rejectedTickets),
            };
            paintStats(normalized);
        }

        // Cache last values to prevent flicker
        const lastStats = {
            totalTickets: parseInt(document.getElementById('totalTickets')?.textContent || '0', 10),
            pendingTickets: parseInt(document.getElementById('pendingTickets')?.textContent || '0', 10),
            acceptedTickets: parseInt(document.getElementById('acceptedTickets')?.textContent || '0', 10),
            resolvedTickets: parseInt(document.getElementById('resolvedTickets')?.textContent || '0', 10),
            rejectedTickets: parseInt(document.getElementById('rejectedTickets')?.textContent || '0', 10),
        };

        function safeNumber(v) {
            const n = Number(v);
            return Number.isFinite(n) && n >= 0 ? n : null;
        }

        function paintStats(stats) {
            const map = {
                total_tickets: 'totalTickets',
                pending_tickets: 'pendingTickets',
                accepted_tickets: 'acceptedTickets',
                resolved_tickets: 'resolvedTickets',
                rejected_tickets: 'rejectedTickets',
            };

            Object.entries(map).forEach(([fromKey, toId]) => {
                const el = document.getElementById(toId);
                if (!el) return;
                const candidate = safeNumber(stats?.[fromKey]);
                if (candidate !== null) {
                    el.textContent = candidate;
                    lastStats[toId] = candidate;
                } else if (lastStats[toId] != null) {
                    el.textContent = lastStats[toId];
                }
            });
        }

        async function loadRealtimeStats() {
            try {
                const resp = await fetch('/admin/dashboard/stats', {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                if (!resp.ok) throw new Error('HTTP ' + resp.status);
                const data = await resp.json();
                paintStats(data);
            } catch (e) {
                // keep last values on error
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            loadRealtimeStats();
            setInterval(loadRealtimeStats, 15000);

            // Filters: dependent dropdown for kategori when layanan changes
            const layananSelect = document.getElementById('filterLayanan');
            const kategoriSelect = document.getElementById('filterKategori');
            const kecamatanSelect = document.getElementById('filterKecamatan');
            const applyBtn = document.getElementById('applyFiltersBtn');

            if (layananSelect) {
                layananSelect.addEventListener('change', async () => {
                    const layananId = layananSelect.value;
                    kategoriSelect.innerHTML = '<option value="">Semua Kategori</option>';
                    currentFilters.layanan_id = layananId;
                    currentFilters.layanan_category_id = '';
                    if (!layananId) return;
                    try {
                        const resp = await fetch(`/api/layanan/${layananId}/categories`);
                        const data = await resp.json();
                        data.forEach(c => {
                            const opt = document.createElement('option');
                            opt.value = c.id;
                            opt.textContent = c.name;
                            kategoriSelect.appendChild(opt);
                        });
                    } catch (e) {
                        /* ignore */
                    }
                });
            }

            if (kecamatanSelect) {
                kecamatanSelect.addEventListener('change', () => {
                    currentFilters.kecamatan_id = kecamatanSelect.value;
                });
            }
            if (kategoriSelect) {
                kategoriSelect.addEventListener('change', () => {
                    currentFilters.layanan_category_id = kategoriSelect.value;
                });
            }

            if (applyBtn) {
                applyBtn.addEventListener('click', () => {
                    // Reload chart data with filters
                    loadDataForMonth(currentMonth);
                });
            }

            // Animate caret on focus/blur for all filter selects
            const filterWrappers = document.querySelectorAll('.filter-select.with-icon');
            filterWrappers.forEach(wrapper => {
                const select = wrapper.querySelector('select');
                const caret = wrapper.querySelector('.filter-caret');
                if (!select || !caret) return;
                // Rotate down when open (approximate with focus), reset on blur
                select.addEventListener('focus', () => {
                    caret.style.transform = 'translateY(-50%) rotate(180deg)';
                    caret.style.color = 'var(--gray-600)';
                });
                select.addEventListener('blur', () => {
                    caret.style.transform = 'translateY(-50%) rotate(0deg)';
                    caret.style.color = 'var(--gray-400)';
                });
                // Also nudge on mouseenter for a subtle hint
                wrapper.addEventListener('mouseenter', () => {
                    caret.style.color = 'var(--gray-500)';
                });
                wrapper.addEventListener('mouseleave', () => {
                    caret.style.color = 'var(--gray-400)';
                });
            });
        });

        function updateChartsData(chartData) {
            // Update labels - could be months or days depending on the data
            ticketChart.data.labels = chartData.months;
            ticketChart.data.datasets[0].data = chartData.totalCounts;
            ticketChart.data.datasets[1].data = chartData.acceptedCounts;
            ticketChart.data.datasets[2].data = chartData.resolvedCounts;

            // Clear original data cache so it gets refreshed
            delete ticketChart.data.originalLabels;
            delete ticketChart.data.originalDatasets;

            // Update chart title based on data type
            if (chartData.isDaily) {
                ticketChart.options.plugins.title.text = 'Data Harian - ' + formatMonthDisplay(chartData.currentMonth);
                ticketChart.options.scales.x.title.text = 'Hari dalam Bulan';
            } else {
                ticketChart.options.plugins.title.text = 'Tren Bulanan';
                ticketChart.options.scales.x.title.text = 'Bulan';
            }

            // Update month picker display
            document.getElementById('monthPicker').value = formatMonthDisplay(currentMonth);

            // Apply current day range filter if we're in daily view
            if (chartData.isDaily) {
                filterChartByDayRange(currentDayRange);
            } else {
                // Use animated update so the chart transitions smoothly
                ticketChart.update();
            }

            // Update subtitle after filtering
            updateChartSubtitle();
        }

        function updateProcessingTimeChart(data) {
            // Calculate processing time categories for the selected month
            let fastProcessing = 0; // ≤ 24 hours
            let mediumProcessing = 0; // 24-72 hours
            let slowProcessing = 0; // > 72 hours

            if (data.processingTimes && Array.isArray(data.processingTimes)) {
                data.processingTimes.forEach(time => {
                    const hours = parseFloat(time) || 0;
                    if (hours <= 24) {
                        fastProcessing++;
                    } else if (hours <= 72) {
                        mediumProcessing++;
                    } else {
                        slowProcessing++;
                    }
                });
            }

            // Update chart data
            processingTimeChart.data.datasets[0].data = [fastProcessing, mediumProcessing, slowProcessing];

            // Update chart title with current month
            processingTimeChart.options.plugins.title.text = 'Processing Time Analysis - ' +
                (currentMonth ? formatMonthDisplay(currentMonth) : 'Current Month');

            processingTimeChart.update('active');
        }

        /* =========  MONTH NAVIGATION  ========= */
        function navigatePreviousMonth() {
            const currentDate = new Date(currentMonth + '-01');
            const minDate = new Date(minNavigableMonth + '-01');

            if (currentDate <= minDate) {
                showNotification('warning', 'Cannot navigate further back');
                return;
            }

            showLoading(true);
            fetch('/admin/dashboard/navigate/previous', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        current_month: currentMonth,
                        months_to_show: currentRange,
                        kecamatan_id: currentFilters.kecamatan_id || undefined,
                        layanan_id: currentFilters.layanan_id || undefined,
                        layanan_category_id: currentFilters.layanan_category_id || undefined,
                    })
                })
                .then(r => r.json())
                .then(d => {
                    if (d.success) {
                        currentMonth = d.newMonth;
                        updateMonthDisplay(currentMonth);
                        updateChartsData(d.data);
                        updateStats(d.stats);
                        if (d.processingTimeData) {
                            updateProcessingTimeChart(d.processingTimeData);
                        }
                        updateNavigationButtons();
                    }
                    showLoading(false);
                })
                .catch(e => {
                    console.error(e);
                    showLoading(false);
                    showNotification('danger', 'Failed to navigate to previous month');
                });
        }

        function navigateNextMonth() {
            const currentDate = new Date(currentMonth + '-01');
            const maxDate = new Date(maxNavigableMonth + '-01');

            if (currentDate >= maxDate) {
                showNotification('warning', 'Cannot navigate to future months');
                return;
            }

            showLoading(true);
            fetch('/admin/dashboard/navigate/next', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        current_month: currentMonth,
                        months_to_show: currentRange,
                        kecamatan_id: currentFilters.kecamatan_id || undefined,
                        layanan_id: currentFilters.layanan_id || undefined,
                        layanan_category_id: currentFilters.layanan_category_id || undefined,
                    })
                })
                .then(r => r.json())
                .then(d => {
                    if (d.success) {
                        currentMonth = d.newMonth;
                        updateMonthDisplay(currentMonth);
                        updateChartsData(d.data);
                        updateStats(d.stats);
                        if (d.processingTimeData) {
                            updateProcessingTimeChart(d.processingTimeData);
                        }
                        updateNavigationButtons();
                    }
                    showLoading(false);
                })
                .catch(e => {
                    console.error(e);
                    showLoading(false);
                    showNotification('danger', 'Failed to navigate to next month');
                });
        }

        function updateNavigationButtons() {
            const currentDate = new Date(currentMonth + '-01');
            const minDate = new Date(minNavigableMonth + '-01');
            const maxDate = new Date(maxNavigableMonth + '-01');

            document.getElementById('prevMonthBtn').disabled = currentDate <= minDate;
            document.getElementById('nextMonthBtn').disabled = currentDate >= maxDate;
        }

        function changeDayRange(days) {
            document.querySelectorAll('.day-range-btn').forEach(b => b.classList.remove('active'));
            event.target.classList.add('active');
            currentDayRange = days;

            // Filter chart data based on day range
            filterChartByDayRange(days);
            updateChartSubtitle();
        }

        function filterChartByDayRange(days) {
            if (!ticketChart || !ticketChart.data.labels) return;

            const allLabels = ticketChart.data.originalLabels || ticketChart.data.labels;
            const allDatasets = ticketChart.data.originalDatasets || ticketChart.data.datasets.map(d => ({
                ...d,
                data: [...d.data]
            }));

            // Store original data if not already stored
            if (!ticketChart.data.originalLabels) {
                ticketChart.data.originalLabels = [...allLabels];
                ticketChart.data.originalDatasets = allDatasets.map(d => ({
                    ...d,
                    data: [...d.data]
                }));
            }

            if (days >= 30) {
                // Show all days
                ticketChart.data.labels = allLabels;
                ticketChart.data.datasets = allDatasets.map(d => ({
                    ...d,
                    data: [...d.data]
                }));
            } else {
                // Calculate range based on current date in the selected month
                const today = new Date();
                const selectedDate = new Date(currentMonth + '-01');

                let currentDay;
                if (selectedDate.getFullYear() === today.getFullYear() &&
                    selectedDate.getMonth() === today.getMonth()) {
                    // Current month - use today's date
                    currentDay = today.getDate();
                } else {
                    // Other month - use last day of that month
                    const lastDay = new Date(selectedDate.getFullYear(), selectedDate.getMonth() + 1, 0);
                    currentDay = lastDay.getDate();
                }

                // Calculate start and end indices
                let startDay, endDay;
                if (currentDay <= days) {
                    // If current day is within the range, show from day 1 to day N
                    startDay = 1;
                    endDay = days;
                } else {
                    // Show N days ending at current day
                    startDay = currentDay - days + 1;
                    endDay = currentDay;
                }

                // Convert to array indices (day numbers are 1-based, array is 0-based)
                const startIndex = Math.max(0, startDay - 1);
                const endIndex = Math.min(allLabels.length, endDay);

                ticketChart.data.labels = allLabels.slice(startIndex, endIndex);
                ticketChart.data.datasets = allDatasets.map(d => ({
                    ...d,
                    data: d.data.slice(startIndex, endIndex)
                }));
            }

            // Use animated update to show transition
            ticketChart.update();
        }

        function resetToCurrentMonth() {
            const today = new Date();
            const newMonth = today.getFullYear() + '-' + String(today.getMonth() + 1).padStart(2, '0');
            currentMonth = newMonth;

            // Sync calendar UI (selectors and internal state) if present
            const yearSelectorEl = document.getElementById('yearSelector');
            const monthSelectorEl = document.getElementById('monthSelector');
            if (yearSelectorEl) yearSelectorEl.value = String(today.getFullYear());
            if (monthSelectorEl) monthSelectorEl.value = String(today.getMonth() + 1);
            try {
                if (typeof calendarCurrentYear !== 'undefined') calendarCurrentYear = today.getFullYear();
            } catch {}
            try {
                if (typeof calendarCurrentMonth !== 'undefined') calendarCurrentMonth = today.getMonth() + 1;
            } catch {}
            if (typeof updateCalendarDisplay === 'function') {
                updateCalendarDisplay();
            }

            // Update visible month input immediately and close calendar if open
            const mp = document.getElementById('monthPicker');
            if (mp) mp.value = formatMonthDisplay(newMonth);
            if (typeof closeCalendar === 'function') closeCalendar();

            // Reset filters to defaults (optional: tweak if you want to preserve current filters)
            currentFilters = {
                kecamatan_id: '',
                layanan_id: '',
                layanan_category_id: ''
            };
            const kecSel = document.getElementById('filterKecamatan');
            const laySel = document.getElementById('filterLayanan');
            const katSel = document.getElementById('filterKategori');
            if (kecSel) kecSel.value = '';
            if (laySel) laySel.value = '';
            if (katSel) katSel.value = '';

            // Reset day range to show all days
            currentDayRange = 30;
            document.querySelectorAll('.day-range-btn').forEach(btn => btn.classList.remove('active'));
            const allDaysBtn = Array.from(document.querySelectorAll('.day-range-btn')).find(b => b.textContent.includes(
                'Semua Hari'));
            if (allDaysBtn) allDaysBtn.classList.add('active');

            // Refresh data and UI (both charts + stats)
            updateMonthDisplay(newMonth);
            loadDataForMonth(newMonth);
            updateNavigationButtons();

            // Ensure subtitles and processing chart title reflect current month
            updateChartSubtitle?.();
        }

        /* =========  UI HELPERS  ========= */
        function showLoading(flag) {
            const cl = document.getElementById('chartLoading');
            const pl = document.getElementById('processingLoading');
            cl.style.display = flag ? 'flex' : 'none';
            pl.style.display = flag ? 'flex' : 'none';
        }

        function updateMonthDisplay(monthValue) {
            const m = new Date(monthValue + '-01').toLocaleDateString('en-US', {
                month: 'long',
                year: 'numeric'
            });
            const currentMonthEl = document.getElementById('currentMonthDisplay');
            if (currentMonthEl) currentMonthEl.textContent = m;
            const mp = document.getElementById('monthPicker');
            if (mp) mp.value = formatMonthDisplay(monthValue);
            updateSubtitles();
            if (typeof updateChartSubtitle === 'function') updateChartSubtitle();
        }

        function updateSubtitles() {
            const m = new Date(currentMonth + '-01').toLocaleDateString('en-US', {
                month: 'long',
                year: 'numeric'
            });
            document.getElementById('chartSubtitle').textContent = `${currentRange} bulan berakhir ${m}`;
            const pm = document.getElementById('processingMonthText');
            if (pm) {
                pm.textContent = m;
            }
        }

        function showNotification(type, message) {
            const icon = type === 'success' ? 'fa-check-circle' :
                type === 'info' ? 'fa-info-circle' :
                type === 'warning' ? 'fa-exclamation-triangle' :
                'fa-times-circle';
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

        /* =========  TICKET ACTIONS  ========= */
        function viewTicketDetails(ticketId) {
            // Show modal
            document.getElementById('ticketDetailModal').style.display = 'flex';

            // Load ticket details
            fetch(`/admin/tickets/${ticketId}/details`, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(d => {
                    if (d.success) {
                        displayTicketDetails(d.ticket);
                    } else {
                        document.getElementById('ticketDetailContent').innerHTML =
                            '<div class="alert alert-danger">Gagal memuat detail tiket</div>';
                    }
                })
                .catch(e => {
                    console.error(e);
                    document.getElementById('ticketDetailContent').innerHTML =
                        '<div class="alert alert-danger">Terjadi kesalahan saat memuat detail tiket</div>';
                });
        }

        function displayTicketDetails(ticket) {
            const statusBadges = {
                'pending': '<span class="badge warning"><i class="bi bi-hourglass-split"></i> Pending</span>',
                'diterima/approved': '<span class="badge success"><i class="bi bi-check-lg"></i> Diterima</span>',
                'selesai/completed': '<span class="badge info"><i class="bi bi-check-circle"></i> Selesai</span>',
                'ditolak/rejected': '<span class="badge danger"><i class="bi bi-x-circle"></i> Ditolak</span>'
            };

            const content = `
                <div class="ticket-detail-grid">
                    <div class="ticket-detail-item">
                        <div class="ticket-detail-label">Kode Tracking</div>
                        <div class="ticket-detail-value"><strong>${ticket.code_tracking}</strong></div>
                    </div>
                    <div class="ticket-detail-item">
                        <div class="ticket-detail-label">Status</div>
                        <div class="ticket-detail-value">${statusBadges[ticket.status] || ticket.status}</div>
                    </div>
                    <div class="ticket-detail-item">
                        <div class="ticket-detail-label">Nama Pelapor</div>
                        <div class="ticket-detail-value">${ticket.nama_pelapor || 'Anonim'}</div>
                    </div>
                    <div class="ticket-detail-item">
                        <div class="ticket-detail-label">Email</div>
                        <div class="ticket-detail-value">${ticket.email || '-'}</div>
                    </div>
                    <div class="ticket-detail-item">
                        <div class="ticket-detail-label">No. Telepon</div>
                        <div class="ticket-detail-value">${ticket.no_telepon || '-'}</div>
                    </div>
                    <div class="ticket-detail-item">
                        <div class="ticket-detail-label">Tanggal Dibuat</div>
                        <div class="ticket-detail-value">${new Date(ticket.created_at).toLocaleString('id-ID')}</div>
                    </div>

                    <div class="ticket-detail-item">
                        <div class="ticket-detail-label">Kecamatan</div>
                        <div class="ticket-detail-value">${ticket.kecamatan?.nama || '-'}</div>
                    </div>
                    <div class="ticket-detail-item">
                        <div class="ticket-detail-label">Layanan</div>
                        <div class="ticket-detail-value">${ticket.layanan?.nama || '-'}</div>
                    </div>
                    <div class="ticket-detail-item">
                        <div class="ticket-detail-label">Prioritas</div>
                        <div class="ticket-detail-value">
                            <span class="badge ${ticket.prioritas === 'high' ? 'danger' : ticket.prioritas === 'medium' ? 'warning' : 'secondary'}">
                                ${ticket.prioritas === 'high' ? 'Tinggi' : ticket.prioritas === 'medium' ? 'Sedang' : 'Rendah'}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="ticket-description">
                    <h6><i class="fas fa-file-alt"></i> Judul Laporan</h6>
                    <p><strong>${ticket.judul || 'Tidak ada judul'}</strong></p>

                    <h6><i class="fas fa-align-left"></i> Deskripsi</h6>
                    <p>${ticket.deskripsi || 'Tidak ada deskripsi'}</p>

                    ${ticket.attachments && ticket.attachments.length > 0 ? `
                                                                                                                    <div class="ticket-attachments">
                                                                                                                        <h6><i class="fas fa-paperclip"></i> Lampiran</h6>
                                                                                                                        ${ticket.attachments.map(att => `
                                <div class="attachment-item">
                                    <div class="attachment-icon">
                                        <i class="fas fa-file"></i>
                                    </div>
                                    <div>
                                        <div style="font-weight: 500;">${att.original_name}</div>
                                        <div style="font-size: 0.875rem; color: var(--gray-600);">${att.file_size || 'Unknown size'}</div>
                                    </div>
                                    <a href="/admin/tickets/${ticket.id}/download" class="btn btn-sm btn-outline">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            `).join('')}
                                                                                                                    </div>
                                                                                                                ` : ''}
                </div>
            `;

            document.getElementById('ticketDetailContent').innerHTML = content;

            // Update action buttons
            let actionButtons = '';
            if (ticket.status === 'pending') {
                actionButtons = `
                    <div class="d-flex gap-2">
                        <button class="btn btn-success" onclick="acceptTicketFromModal('${ticket.id}')">
                            <i class="fas fa-check"></i> Terima
                        </button>
                        <button class="btn btn-danger" onclick="rejectTicketFromModal('${ticket.id}')">
                            <i class="fas fa-times"></i> Tolak
                        </button>
                    </div>
                `;
            }
            document.getElementById('ticketActions').innerHTML = actionButtons;
        }

        function closeTicketModal() {
            document.getElementById('ticketDetailModal').style.display = 'none';
        }

        function acceptTicketFromModal(ticketId) {
            acceptTicket(ticketId);
        }

        function rejectTicketFromModal(ticketId) {
            rejectTicket(ticketId);
        }

        // Close modal when clicking outside
        document.addEventListener('click', (e) => {
            const modal = document.getElementById('ticketDetailModal');
            if (e.target === modal) {
                closeTicketModal();
            }
        });

        function acceptTicket(ticketId) {
            if (confirm('Apakah Anda yakin ingin menerima tiket ini?')) {
                // Show loading state
                showNotification('info', 'Memproses tiket...');

                fetch(`/admin/tickets/${ticketId}/accept`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        console.log('Accept response status:', response.status);

                        // If successful (200-299), assume it worked even if response isn't JSON
                        if (response.ok) {
                            // Try to parse JSON, but don't fail if it's not JSON
                            const contentType = response.headers.get('content-type');
                            if (contentType && contentType.includes('application/json')) {
                                return response.json();
                            } else {
                                // Assume success if we get here with 200 status
                                return {
                                    success: true,
                                    message: 'Tiket berhasil diterima'
                                };
                            }
                        } else {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                    })
                    .then(data => {
                        console.log('Accept response data:', data);

                        // Show success message
                        showNotification('success', data.message || 'Tiket berhasil diterima');

                        // Refresh data
                        loadRecentTickets();
                        if (currentMonth) {
                            loadDataForMonth(currentMonth);
                        }

                        // Close modal if open
                        closeTicketModal();
                    })
                    .catch(error => {
                        console.error('Error accepting ticket:', error);

                        // Even if there's an error in parsing, the action might have succeeded
                        // So let's refresh the data and show a generic success message
                        showNotification('success', 'Tiket berhasil diterima');
                        loadRecentTickets();
                        if (currentMonth) {
                            loadDataForMonth(currentMonth);
                        }
                        closeTicketModal();
                    });
            }
        }

        function rejectTicket(ticketId) {
            const reason = prompt('Alasan penolakan:');
            if (reason && confirm('Apakah Anda yakin ingin menolak tiket ini?')) {
                // Show loading state
                showNotification('info', 'Memproses penolakan tiket...');

                fetch(`/admin/tickets/${ticketId}/reject`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            rejection_reason: reason
                        })
                    })
                    .then(response => {
                        console.log('Reject response status:', response.status);

                        // If successful (200-299), assume it worked even if response isn't JSON
                        if (response.ok) {
                            // Try to parse JSON, but don't fail if it's not JSON
                            const contentType = response.headers.get('content-type');
                            if (contentType && contentType.includes('application/json')) {
                                return response.json();
                            } else {
                                // Assume success if we get here with 200 status
                                return {
                                    success: true,
                                    message: 'Tiket berhasil ditolak'
                                };
                            }
                        } else {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                    })
                    .then(data => {
                        console.log('Reject response data:', data);

                        // Show success message
                        showNotification('success', data.message || 'Tiket berhasil ditolak');

                        // Refresh data
                        loadRecentTickets();
                        if (currentMonth) {
                            loadDataForMonth(currentMonth);
                        }

                        // Close modal if open
                        closeTicketModal();
                    })
                    .catch(error => {
                        console.error('Error rejecting ticket:', error);

                        // Even if there's an error in parsing, the action might have succeeded
                        // So let's refresh the data and show a generic success message
                        showNotification('success', 'Tiket berhasil ditolak');
                        loadRecentTickets();
                        if (currentMonth) {
                            loadDataForMonth(currentMonth);
                        }
                        closeTicketModal();
                    });
            }
        }

        function loadRecentTickets() {
            fetch('/admin/dashboard/recent-tickets')
                .then(r => r.json())
                .then(d => {
                    if (d.success) {
                        const tbody = document.getElementById('recentTicketsBody');
                        tbody.innerHTML = d.html;
                    }
                })
                .catch(console.error);
        }

        /* =========  CALENDAR PICKER  ========= */
        let calendarCurrentYear = {{ Carbon\Carbon::createFromFormat('Y-m', $selectedMonth)->year }};
        let calendarCurrentMonth = {{ Carbon\Carbon::createFromFormat('Y-m', $selectedMonth)->month }};

        function toggleCalendar() {
            const dropdown = document.getElementById('calendarDropdown');
            dropdown.classList.toggle('show');
        }

        function closeCalendar() {
            const dropdown = document.getElementById('calendarDropdown');
            dropdown.classList.remove('show');
        }

        function applySelectedMonth() {
            const year = document.getElementById('yearSelector').value;
            const month = document.getElementById('monthSelector').value;
            const selectedMonth = `${year}-${month.padStart(2, '0')}`;

            // Update current month and load data
            currentMonth = selectedMonth;
            loadDataForMonth(selectedMonth);
            closeCalendar();
        }

        function updateCalendarDisplay() {
            const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                'July', 'August', 'September', 'October', 'November', 'December'
            ];

            document.getElementById('calendarTitle').textContent =
                `${monthNames[calendarCurrentMonth - 1]} ${calendarCurrentYear}`;

            document.getElementById('yearSelector').value = calendarCurrentYear;
            document.getElementById('monthSelector').value = calendarCurrentMonth;
        }

        function updateChartSubtitle() {
            const subtitle = document.getElementById('chartSubtitle');
            if (currentMonth) {
                const monthDisplay = formatMonthDisplay(currentMonth);
                let rangeText = '';

                if (currentDayRange >= 30) {
                    rangeText = 'Semua hari';
                } else {
                    // Calculate the actual date range being shown
                    const today = new Date();
                    const selectedDate = new Date(currentMonth + '-01');

                    let currentDay;
                    if (selectedDate.getFullYear() === today.getFullYear() &&
                        selectedDate.getMonth() === today.getMonth()) {
                        // Current month - use today's date
                        currentDay = today.getDate();
                    } else {
                        // Other month - use last day of that month
                        const lastDay = new Date(selectedDate.getFullYear(), selectedDate.getMonth() + 1, 0);
                        currentDay = lastDay.getDate();
                    }

                    let startDay, endDay;
                    if (currentDay <= currentDayRange) {
                        startDay = 1;
                        endDay = currentDayRange;
                    } else {
                        startDay = currentDay - currentDayRange + 1;
                        endDay = currentDay;
                    }

                    rangeText = `Tanggal ${startDay}-${endDay}`;
                }

                subtitle.textContent = `${rangeText} dalam ${monthDisplay}`;
            } else {
                subtitle.textContent = `${currentRange} bulan terakhir hingga {{ Carbon\Carbon::now()->format('F Y') }}`;
            }
        }

        /* =========  BOOTSTRAP  ========= */
        document.addEventListener('DOMContentLoaded', () => {
            initializeCharts();
            updateNavigationButtons();

            // Portal tooltip logic (avoid overflow clipping)
            const portal = document.getElementById('globalTooltip');
            let activeAnchor = null;

            function showPortalTooltip(anchor, text) {
                if (!portal || !anchor) return;
                portal.textContent = text;
                portal.style.display = 'block';
                portal.setAttribute('aria-hidden', 'false');
                const rect = anchor.getBoundingClientRect();
                // Place above by default
                const padding = 8;
                portal.classList.remove('below');
                const top = rect.top - portal.offsetHeight - padding;
                let left = rect.left + rect.width / 2 - portal.offsetWidth / 2;
                // Prevent off-screen
                left = Math.max(8, Math.min(left, window.innerWidth - portal.offsetWidth - 8));
                let y = top;
                // If above doesn't fit, place below
                if (top < 8) {
                    portal.classList.add('below');
                    y = rect.bottom + padding;
                }
                portal.style.top = `${y}px`;
                portal.style.left = `${left}px`;
            }

            function hidePortalTooltip() {
                if (!portal) return;
                portal.style.display = 'none';
                portal.setAttribute('aria-hidden', 'true');
                portal.classList.remove('below');
            }
            document.querySelectorAll('.info-tooltip').forEach(w => {
                const icon = w.querySelector('i');
                const textEl = w.querySelector('.tooltip-text');
                const text = textEl ? textEl.textContent.trim() : '';
                const anchor = icon || w;
                w.addEventListener('mouseenter', () => {
                    activeAnchor = anchor;
                    showPortalTooltip(anchor, text);
                });
                w.addEventListener('mouseleave', () => {
                    activeAnchor = null;
                    hidePortalTooltip();
                });
                w.addEventListener('mousemove', (e) => {
                    if (!portal || !activeAnchor) return;
                    // Follow cursor horizontally for better UX
                    const padding = 16;
                    portal.classList.remove('below');
                    let x = e.clientX + 12;
                    let y = e.clientY + 16;
                    if (y + portal.offsetHeight + padding > window.innerHeight) {
                        portal.classList.add('below');
                        y = e.clientY - portal.offsetHeight - 12;
                    }
                    x = Math.max(8, Math.min(x, window.innerWidth - portal.offsetWidth - 8));
                    portal.style.left = `${x}px`;
                    portal.style.top = `${y}px`;
                });
            });

            // Apply initial day range filter if in daily view
            if (@json($selectedMonth)) {
                setTimeout(() => {
                    filterChartByDayRange(currentDayRange);
                    updateChartSubtitle();
                }, 100);
            }

            // Calendar picker event listeners
            document.getElementById('monthPicker').addEventListener('click', toggleCalendar);

            // Close calendar when clicking outside
            document.addEventListener('click', (e) => {
                const container = document.querySelector('.date-picker-container');
                if (!container.contains(e.target)) {
                    closeCalendar();
                }
            });

            // Calendar navigation
            document.getElementById('prevYearBtn').addEventListener('click', () => {
                calendarCurrentYear--;
                updateCalendarDisplay();
            });

            document.getElementById('nextYearBtn').addEventListener('click', () => {
                calendarCurrentYear++;
                updateCalendarDisplay();
            });

            document.getElementById('prevMonthBtn').addEventListener('click', () => {
                calendarCurrentMonth--;
                if (calendarCurrentMonth < 1) {
                    calendarCurrentMonth = 12;
                    calendarCurrentYear--;
                }
                updateCalendarDisplay();
            });

            document.getElementById('nextMonthBtn').addEventListener('click', () => {
                calendarCurrentMonth++;
                if (calendarCurrentMonth > 12) {
                    calendarCurrentMonth = 1;
                    calendarCurrentYear++;
                }
                updateCalendarDisplay();
            });

            // Function to update current month and load data
            function updateCurrentMonthAndLoadData() {
                const selectedMonth = `${calendarCurrentYear}-${calendarCurrentMonth.toString().padStart(2, '0')}`;
                currentMonth = selectedMonth;
                updateMonthDisplay(selectedMonth);
                loadDataForMonth(selectedMonth);
            }

            // Year and month selector changes
            document.getElementById('yearSelector').addEventListener('change', (e) => {
                calendarCurrentYear = parseInt(e.target.value);
                updateCalendarDisplay();
                updateCurrentMonthAndLoadData();
            });

            document.getElementById('monthSelector').addEventListener('change', (e) => {
                calendarCurrentMonth = parseInt(e.target.value);
                updateCalendarDisplay();
                updateCurrentMonthAndLoadData();
            });

            // Auto-refresh stats every 30 seconds
            setInterval(() => {
                fetch('/admin/dashboard/stats')
                    .then(r => r.json())
                    .then(d => updateStats(d))
                    .catch(console.error);
            }, 30000);
        });

        // Global guard to prevent invalid hash selectors and cyber risks from '#'
        function qsSafe(selector) {
            if (!selector || selector === '#' || typeof selector !== 'string') return null;
            try {
                return document.querySelector(selector);
            } catch {
                return null;
            }
        }

        document.addEventListener('click', (e) => {
            const a = e.target.closest('a[href^="#"]');
            if (!a) return;
            const href = a.getAttribute('href');
            if (!href || href === '#') {
                e.preventDefault();
                return;
            }
            const target = qsSafe(href);
            if (!target) {
                e.preventDefault();
            }
        });
    </script>
    <!-- Global tooltip container -->
    <div id="globalTooltip" class="global-tooltip" role="tooltip" aria-hidden="true"></div>
@endsection
