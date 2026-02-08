<?php
require_once '../includes/config.php';
require_once '../includes/database.php';
requireAdmin();

// Handle logout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_destroy();
    header('Location: ../index.php?logged_out=1');
    exit;
}

$userInfo = getUserInfo();
$page = $_GET['page'] ?? 'dashboard';

// Get pending teachers count for sidebar badge
$pendingTeachersCount = getPendingTeachersCount();

// Handle AJAX actions for approvals page
if ($page === 'approvals' && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    $teacherId = $_POST['teacher_id'] ?? null;
    
    if (!$teacherId) {
        echo json_encode(['success' => false, 'message' => 'Invalid teacher ID']);
        exit;
    }
    
    switch ($_POST['action']) {
        case 'approve':
            $result = approveTeacher($teacherId);
            echo json_encode([
                'success' => $result,
                'message' => $result ? 'Teacher approved successfully' : 'Failed to approve teacher'
            ]);
            break;
            
        case 'reject':
            $result = rejectTeacher($teacherId);
            echo json_encode([
                'success' => $result,
                'message' => $result ? 'Teacher registration rejected' : 'Failed to reject registration'
            ]);
            break;
            
        case 'delete':
            $result = deleteTeacherRegistration($teacherId);
            echo json_encode([
                'success' => $result,
                'message' => $result ? 'Registration deleted' : 'Failed to delete registration'
            ]);
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - College Enrollment</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/heroicons@2.0.18/16/solid.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/heroicons@2.0.18/16/solid.css">
    <style>
        :root {
            --primary-dark: #222431;
            --primary: #30425A;
            --primary-light: #3c516b;
            --gradient-blue: linear-gradient(135deg, #222431 0%, #30425A 100%);
            --gradient-purple: linear-gradient(135deg, #30425A 0%, #222431 100%);
            --gradient-cyan: linear-gradient(135deg, #222431 0%, #30425A 100%);
            --gradient-green: linear-gradient(135deg, #30425A 0%, #222431 100%);
            --shadow-sm: 0 2px 4px rgba(0,0,0,0.05);
            --shadow-md: 0 4px 6px rgba(0,0,0,0.07);
            --shadow-lg: 0 10px 25px rgba(0,0,0,0.1);
            --shadow-xl: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        body {
            font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: #FFFFFF;
            color: #222431;
        }
        
        /* Enhanced Card Styles */
        .card-hover {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(0,0,0,0.05);
        }
        
        .card-hover:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: var(--shadow-xl);
            border-color: rgba(48, 66, 90, 0.3);
        }
        
        /* Gradient Stat Cards */
        .stat-card {
            position: relative;
            overflow: hidden;
            color: white;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1));
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .stat-card:hover::before {
            opacity: 1;
        }
        
        .stat-card.blue {
            background: var(--gradient-blue);
        }
        
        .stat-card.green {
            background: var(--gradient-green);
        }
        
        .stat-card.orange {
            background: var(--gradient-cyan);
        }
        
        .stat-card.purple {
            background: var(--gradient-purple);
        }
        
        /* Sidebar Active State */
        .sidebar-active {
            background: var(--primary);
            color: white;
            box-shadow: var(--shadow-md);
            transform: translateX(4px);
        }
        
        /* Animated Icons */
        .icon-bounce {
            animation: bounce 2s ease-in-out infinite;
        }
        
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        /* Pulse Animation */
        .pulse-animation {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        
        /* Enhanced Badge */
        .badge-enhanced {
            animation: ring 3s ease-in-out infinite;
        }
        
        @keyframes ring {
            0%, 100% { transform: rotate(0deg); }
            10%, 30% { transform: rotate(-10deg); }
            20%, 40% { transform: rotate(10deg); }
        }
        
        /* Glass morphism effect */
        .glass-effect {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .btn-primary,
        .badge-primary {
            background-color: var(--primary) !important;
            border-color: var(--primary) !important;
            color: #FFFFFF !important;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark) !important;
            border-color: var(--primary-dark) !important;
        }

        .text-blue-600,
        .text-blue-700,
        .text-blue-800 {
            color: var(--primary) !important;
        }

        .bg-blue-50,
        .bg-blue-100 {
            background-color: #F7F8FA !important;
        }
        
        /* Smooth transitions */
        * {
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Enhanced navbar shadow */
        .navbar {
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        
        /* Stat icon wrapper */
        .stat-icon-wrapper {
            position: relative;
            transition: all 0.3s ease;
        }
        
        .stat-icon-wrapper:hover {
            transform: rotate(360deg) scale(1.1);
        }
        
        /* Progress bar animation */
        .progress-bar {
            position: relative;
            overflow: hidden;
        }
        
        .progress-bar::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            width: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            animation: shimmer 2s infinite;
        }
        
        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        
        /* Enhanced dropdown */
        .dropdown-content {
            animation: slideDown 0.3s ease-out;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Status indicators */
        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
            animation: blink 2s ease-in-out infinite;
        }
        
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }
        
        /* Enhanced table styles */
        .table-zebra tbody tr:hover {
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.05), rgba(147, 197, 253, 0.1));
            transition: all 0.3s ease;
        }
        
        /* Fix input visibility - ensure white background and dark text */
        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="date"],
        input[type="number"],
        input[type="password"],
        select,
        textarea,
        .input,
        .select,
        .textarea {
            background-color: #FFFFFF !important;
            color: #1f2937 !important;
            border: 1px solid #d1d5db !important;
        }
        
        .input:focus,
        .select:focus,
        .textarea:focus {
            background-color: #FFFFFF !important;
            color: #1f2937 !important;
            border-color: #30425A !important;
            outline: none !important;
            box-shadow: 0 0 0 3px rgba(48, 66, 90, 0.1) !important;
        }
        
        /* Ensure placeholder text is visible */
        input::placeholder,
        textarea::placeholder {
            color: #9ca3af !important;
            opacity: 1 !important;
        }
        
        /* Ensure select options are visible */
        select option {
            background-color: #FFFFFF !important;
            color: #1f2937 !important;
        }
        
        /* Label text should be visible */
        .label-text {
            color: #374151 !important;
        }
        
        /* Alert info box styling */
        .alert-info {
            background-color: #dbeafe !important;
            color: #1e3a8a !important;
            border: 1px solid #93c5fd !important;
        }
        
        .alert-info .text-primary {
            color: #1e40af !important;
        }
        
        /* Fix modal/dialog visibility */
        .modal-box,
        dialog.modal .modal-box,
        .swal2-popup {
            background-color: #FFFFFF !important;
            color: #1f2937 !important;
        }
        
        .modal-box h3,
        .modal-box h2,
        .modal-box .font-bold,
        .modal-box .text-lg,
        .modal-box .text-xl,
        .modal-box .text-2xl {
            color: #111827 !important;
        }
        
        .modal-box p,
        .modal-box span,
        .modal-box div,
        .modal-box .text-sm,
        .modal-box .text-gray-600,
        .modal-box .text-gray-500,
        .modal-box .text-gray-700 {
            color: #6b7280 !important;
        }
        
        .modal-box .text-gray-800 {
            color: #1f2937 !important;
        }
        
        .modal-box label,
        .modal-box .label-text {
            color: #374151 !important;
        }
        
        .modal-box .font-semibold {
            color: #111827 !important;
        }
        
        /* Tab styling in modals */
        .modal-box .tabs .tab {
            color: #6b7280 !important;
        }
        
        .modal-box .tabs .tab-active {
            color: #1f2937 !important;
            border-bottom-color: #30425A !important;
        }
        
        /* Ensure backdrop is visible */
        .modal::backdrop,
        dialog::backdrop {
            background-color: rgba(0, 0, 0, 0.5) !important;
        }
        
        /* Fix SweetAlert modals */
        .swal2-title,
        .swal2-html-container,
        .swal2-content {
            color: #1f2937 !important;
        }
        
        .swal2-input,
        .swal2-textarea {
            background-color: #FFFFFF !important;
            color: #1f2937 !important;
            border: 1px solid #d1d5db !important;
        }
        
        /* Fix table visibility - ensure all rows have proper background and text color */
        .table tbody tr,
        .table tbody tr:nth-child(odd),
        .table tbody tr:nth-child(even),
        .table-zebra tbody tr:nth-child(odd),
        .table-zebra tbody tr:nth-child(even) {
            background-color: #FFFFFF !important;
        }
        
        .table tbody tr:nth-child(even),
        .table-zebra tbody tr:nth-child(even) {
            background-color: #f9fafb !important;
        }
        
        .table tbody tr:hover,
        .table-zebra tbody tr:hover {
            background-color: #f3f4f6 !important;
        }
        
        .table tbody td,
        .table thead th {
            color: #1f2937 !important;
        }
        
        /* Ensure links in tables are visible */
        .table tbody td a {
            color: #2563eb !important;
        }
        
        .table tbody td a:hover {
            color: #1d4ed8 !important;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="drawer drawer-mobile lg:drawer-open">
        <input id="drawer-toggle" type="checkbox" class="drawer-toggle" />
        
        <!-- Main Content -->
        <div class="drawer-content flex flex-col">
            <!-- Enhanced Header with Glass Effect -->
            <div class="glass-effect sticky top-0 z-40 border-b border-gray-200">
                <div class="navbar flex items-center justify-between px-6 py-4">
                    <div class="flex items-center gap-4">
                        <label for="drawer-toggle" class="btn btn-ghost lg:hidden hover:bg-blue-50">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </label>
                        <div>
                            <div class="flex items-center gap-3">
                                <h2 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Admin Dashboard</h2>
                                <span class="status-dot bg-green-500"></span>
                            </div>
                            <p class="text-sm text-gray-600 mt-1">Manage enrollment and students 🎓</p>
                        </div>
                    </div>
                    
                    <!-- Right Side - Enhanced Notifications and User -->
                    <div class="flex items-center gap-4">
                        <!-- Search Button -->
                        <button class="btn btn-ghost btn-circle hover:bg-blue-50">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                        
                        <!-- Notifications with Animation -->
                        <div class="indicator">
                            <span class="indicator-item badge badge-sm bg-gradient-to-r from-blue-500 to-purple-500 border-0 badge-enhanced">3</span>
                            <button class="btn btn-ghost btn-circle hover:bg-blue-50 relative">
                                <svg class="w-5 h-5 text-gray-600 icon-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <!-- User Menu with Enhanced Style -->
                        <div class="dropdown dropdown-end">
                            <button tabindex="0" class="btn btn-ghost btn-circle avatar group">
                                <div class="w-11 h-11 rounded-full bg-gradient-to-br from-blue-400 via-blue-500 to-blue-600 flex items-center justify-center text-white font-bold shadow-lg group-hover:shadow-xl transition-all duration-300 group-hover:scale-110">
                                    <?php echo strtoupper(substr($userInfo['name'], 0, 1)); ?>
                                </div>
                            </button>
                            <ul tabindex="0" class="dropdown-content z-50 menu p-3 shadow-xl bg-white rounded-2xl w-56 border border-gray-100 mt-2">
                                <li class="menu-title">
                                    <span class="text-xs text-gray-500 uppercase">Account</span>
                                </li>
                                <li><a href="#" class="hover:bg-blue-50 hover:text-blue-600 rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    Profile
                                </a></li>
                                <li><a href="#" class="hover:bg-blue-50 hover:text-blue-600 rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Settings
                                </a></li>
                                <div class="divider my-1"></div>
                                <li><a href="../logout.php" class="hover:bg-red-50 hover:text-red-600 rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    Logout
                                </a></li>
                            </ul>
                        </div>
                        
                        <!-- Logout Button -->
                        <form id="logoutForm" method="POST" style="display: inline;">
                            <input type="hidden" name="logout" value="1">
                            <button 
                                type="button" 
                                onclick="confirmLogout()" 
                                class="flex items-center space-x-2 px-4 py-2 btn-logout text-white rounded-lg font-medium hover:shadow-lg transition"
                                title="Logout from your account"
                            >
                                <i class="fas fa-sign-out-alt"></i>
                                <span class="hidden sm:inline">Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Page Content with Enhanced Background -->
            <div class="flex-1 p-6 bg-gradient-to-br from-gray-50 to-blue-50/30">
                <?php
                if ($page === 'students') {
                    include 'students.php';
                } elseif ($page === 'enrollment') {
                    include 'enrollment.php';
                } elseif ($page === 'courses') {
                    include 'courses.php';
                } elseif ($page === 'teachers') {
                    include 'teachers.php';
                } elseif ($page === 'approvals') {
                    include 'approvals.php';
                } else {
                    include 'overview.php';
                }
                ?>
            </div>
        </div>

        <!-- Enhanced Sidebar -->
        <div class="drawer-side">
            <label for="drawer-toggle" class="drawer-overlay"></label>
            <aside class="bg-white w-64 overflow-y-auto shadow-2xl border-r border-gray-100">
                <!-- Enhanced Logo Section -->
                <div class="p-6 border-b border-gray-200 bg-gradient-to-br from-blue-50 to-white">
                    <div class="flex items-center justify-center gap-3">
                        <img src="https://hccp-sms.holycrosscollegepampanga.edu.ph/public/assets/images/logo4.png" alt="Premier College Logo" class="w-14 h-14 rounded-xl shadow-lg transform hover:scale-110 transition-transform duration-300 object-cover flex-shrink-0">
                        <div class="text-center">
                            <h1 class="font-bold text-gray-800 text-lg">Premier College</h1>
                            <p class="text-xs text-blue-600 font-medium">Enrollment System</p>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Navigation Menu -->
                <nav class="p-4 space-y-2">
                    <!-- Dashboard -->
                    <a href="?page=dashboard" class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all duration-300 group <?php echo $page === 'dashboard' ? 'sidebar-active' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600'; ?>">
                        <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 11l4-4m0 0l4 4m-4-4v4"></path>
                        </svg>
                        <span class="font-medium">Dashboard</span>
                        <?php if ($page === 'dashboard'): ?>
                        <span class="ml-auto w-2 h-2 bg-white rounded-full pulse-animation"></span>
                        <?php endif; ?>
                    </a>

                    <!-- Student Enrollment -->
                    <a href="?page=enrollment" class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all duration-300 group <?php echo $page === 'enrollment' ? 'sidebar-active' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600'; ?>">
                        <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                        <span class="font-medium">New Enrollment</span>
                        <?php if ($page === 'enrollment'): ?>
                        <span class="ml-auto w-2 h-2 bg-white rounded-full pulse-animation"></span>
                        <?php endif; ?>
                    </a>

                    <!-- Student Management -->
                    <a href="?page=students" class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all duration-300 group <?php echo $page === 'students' ? 'sidebar-active' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600'; ?>">
                        <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 8.646 4 4 0 010-8.646M12 20H7m5 0h5m0 0a8.001 8.001 0 01-8-8v-6m8 8v6m0 0h3m-3 0h-3"></path>
                        </svg>
                        <span class="font-medium">Students</span>
                        <?php if ($page === 'students'): ?>
                        <span class="ml-auto w-2 h-2 bg-white rounded-full pulse-animation"></span>
                        <?php endif; ?>
                    </a>

                    <!-- Course Management -->
                    <a href="?page=courses" class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all duration-300 group <?php echo $page === 'courses' ? 'sidebar-active' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600'; ?>">
                        <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.5 6.253 3 9.756 3 14s3.5 7.747 9 7.747m0-13c5.5 0 9 3.503 9 7.747"></path>
                        </svg>
                        <span class="font-medium">Courses</span>
                        <?php if ($page === 'courses'): ?>
                        <span class="ml-auto w-2 h-2 bg-white rounded-full pulse-animation"></span>
                        <?php endif; ?>
                    </a>

                    <!-- Teacher Management -->
                    <a href="?page=teachers" class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all duration-300 group <?php echo $page === 'teachers' ? 'sidebar-active' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600'; ?>">
                        <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a4 4 0 11-8 0 4 4 0 018 0zm12 0a4 4 0 11-8 0 4 4 0 018 0zm0 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <span class="font-medium">Teachers</span>
                        <?php if ($page === 'teachers'): ?>
                        <span class="ml-auto w-2 h-2 bg-white rounded-full pulse-animation"></span>
                        <?php endif; ?>
                    </a>

                    <!-- Teacher Approvals -->
                    <a href="?page=approvals" class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all duration-300 group <?php echo $page === 'approvals' ? 'sidebar-active' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600'; ?>">
                        <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="font-medium">Approvals</span>
                        <?php if ($pendingTeachersCount > 0): ?>
                        <span class="ml-auto badge badge-warning badge-sm"><?php echo $pendingTeachersCount; ?></span>
                        <?php elseif ($page === 'approvals'): ?>
                        <span class="ml-auto w-2 h-2 bg-white rounded-full pulse-animation"></span>
                        <?php endif; ?>
                    </a>

                    <div class="divider my-4"></div>

                    <!-- Reports -->
                    <a href="#" class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-all duration-300 group">
                        <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <span class="font-medium">Reports</span>
                    </a>

                    <!-- Settings -->
                    <a href="#" class="flex items-center gap-3 px-4 py-3.5 rounded-xl text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-all duration-300 group">
                        <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="font-medium">Settings</span>
                    </a>
                </nav>

                <!-- Enhanced User Profile Section -->
                <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-200 bg-gradient-to-t from-blue-50 to-white">
                    <div class="flex items-center gap-3 p-3 rounded-xl hover:bg-white cursor-pointer transition-all duration-300 shadow-sm hover:shadow-md">
                        <div class="w-11 h-11 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold text-sm shadow-md">
                            <?php echo strtoupper(substr($userInfo['name'], 0, 1)); ?>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-800 text-sm truncate"><?php echo htmlspecialchars($userInfo['name']); ?></p>
                            <p class="text-xs text-blue-600 truncate capitalize flex items-center gap-1">
                                <span class="w-2 h-2 bg-green-500 rounded-full inline-block"></span>
                                <?php echo htmlspecialchars($userInfo['email']); ?>
                            </p>
                        </div>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                    <a href="#" onclick="confirmLogout(); return false;" class="block mt-3 px-4 py-2 text-center text-sm font-medium text-red-600 hover:bg-red-50 rounded-xl transition-all duration-300 hover:shadow-md">
                        <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Logout
                    </a>
                </div>
            </aside>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmLogout() {
            Swal.fire({
                title: 'Are you sure?',
                text: "You will be logged out of your account",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, logout!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logoutForm').submit();
                }
            });
        }
    </script>
</body>
</html>
