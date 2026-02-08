<?php
require_once '../includes/config.php';
require_once '../includes/database.php';

// Handle logout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_destroy();
    header('Location: ../index.php?logged_out=1');
    exit;
}

// Page title for header
$pageTitle = 'Teacher Dashboard';
$is_logged_in = true;
$user_name = $_SESSION['fullname'] ?? 'Teacher';
$user_role = $_SESSION['role'] ?? 'teacher';
$userInfo = getUserInfo();
$page = $_GET['page'] ?? 'dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard - College Enrollment</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        
        /* Card Styles */
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
        
        /* Icon Animation */
        .stat-icon-wrapper {
            transition: transform 0.3s ease;
        }
        
        .stat-icon-wrapper:hover {
            transform: rotate(360deg) scale(1.1);
        }
        
        /* Status Indicators */
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
        
        /* Glass Effect */
        .glass-effect {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(10px);
        }
        
        /* Logout Button */
        .btn-logout {
            background-color: var(--primary);
            border-color: var(--primary);
            transition: all 0.3s ease;
        }
        
        .btn-logout:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
            box-shadow: 0 4px 12px rgba(34, 36, 49, 0.3);
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

        .status-dot {
            background-color: var(--primary) !important;
        }
        
        /* Fix modal/dialog visibility */
        .modal-box,
        dialog.modal .modal-box {
            background-color: #FFFFFF !important;
            color: #1f2937 !important;
        }
        
        .modal-box h3,
        .modal-box .font-bold {
            color: #111827 !important;
        }
        
        .modal-box p,
        .modal-box .text-sm,
        .modal-box .text-gray-600,
        .modal-box .text-gray-500 {
            color: #6b7280 !important;
        }
        
        .modal-box .text-gray-800 {
            color: #1f2937 !important;
        }
        
        .modal-box ul li p {
            color: #1f2937 !important;
        }
        
        .modal-box .text-xs {
            color: #9ca3af !important;
        }
        
        .modal-box .font-semibold {
            color: #111827 !important;
        }
        
        /* Ensure backdrop is visible */
        .modal::backdrop,
        dialog::backdrop {
            background-color: rgba(0, 0, 0, 0.5) !important;
        }
        
        /* Fix input visibility in teacher dashboard */
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
        
        input::placeholder,
        textarea::placeholder {
            color: #9ca3af !important;
            opacity: 1 !important;
        }
        
        select option {
            background-color: #FFFFFF !important;
            color: #1f2937 !important;
        }
        
        .label-text {
            color: #374151 !important;
        }
        
        /* Fix table visibility */
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
            <!-- Enhanced Header -->
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
                                <h2 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Teacher Dashboard</h2>
                                <span class="status-dot bg-green-500"></span>
                            </div>
                            <p class="text-sm text-gray-600 mt-1">Manage classes and students 📚</p>
                        </div>
                    </div>
                    
                    <!-- Right Side -->
                    <div class="flex items-center gap-6">
                        <!-- Search -->
                        <button class="btn btn-ghost btn-circle hover:bg-blue-50">
                            <i class="fas fa-search text-gray-600"></i>
                        </button>
                        
                        <!-- Notifications -->
                        <div class="indicator">
                            <span class="indicator-item badge badge-primary badge-sm">3</span>
                            <button class="btn btn-ghost btn-circle hover:bg-blue-50">
                                <i class="fas fa-bell text-gray-600"></i>
                            </button>
                        </div>
                        
                        <!-- User Info -->
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold">
                                <?php echo strtoupper(substr($user_name, 0, 1)); ?>
                            </div>
                            <div class="hidden sm:block">
                                <p class="text-sm font-semibold text-gray-800"><?php echo htmlspecialchars($user_name); ?></p>
                                <p class="text-xs text-gray-500">Instructor</p>
                            </div>
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

            <!-- Page Content -->
            <div class="flex-1 p-6 overflow-y-auto">
                <div class="max-w-7xl mx-auto">
                    <?php
                    if ($page === 'classes') {
                        include 'classes.php';
                    } elseif ($page === 'roster') {
                        include 'roster.php';
                    } elseif ($page === 'schedule') {
                        include 'schedule.php';
                    } else {
                        include 'overview.php';
                    }
                    ?>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="drawer-side">
            <label for="drawer-toggle" class="drawer-overlay"></label>
            <aside class="bg-white w-64 overflow-y-auto shadow-lg">
                <!-- Logo -->
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-600 to-purple-600 flex items-center justify-center text-white">
                            <i class="fas fa-graduation-cap text-lg"></i>
                        </div>
                        <div>
                            <h1 class="font-bold text-gray-800">ENROLL</h1>
                            <p class="text-xs text-gray-500">Teacher Portal</p>
                        </div>
                    </div>
                </div>

                <!-- Navigation Menu -->
                <nav class="p-4 space-y-2">
                    <a href="?page=dashboard" class="flex items-center gap-3 px-4 py-3 rounded-lg transition <?php echo $page === 'dashboard' ? 'sidebar-active' : 'text-gray-700 hover:bg-gray-100'; ?>">
                        <i class="fas fa-chart-line w-5"></i>
                        <span class="font-medium">Dashboard</span>
                    </a>

                    <a href="?page=classes" class="flex items-center gap-3 px-4 py-3 rounded-lg transition <?php echo $page === 'classes' ? 'sidebar-active' : 'text-gray-700 hover:bg-gray-100'; ?>">
                        <i class="fas fa-book w-5"></i>
                        <span class="font-medium">My Classes</span>
                    </a>

                    <a href="?page=roster" class="flex items-center gap-3 px-4 py-3 rounded-lg transition <?php echo $page === 'roster' ? 'sidebar-active' : 'text-gray-700 hover:bg-gray-100'; ?>">
                        <i class="fas fa-users w-5"></i>
                        <span class="font-medium">Student Roster</span>
                    </a>

                    <hr class="my-4">

                   
                </nav>

                <!-- User Profile Section -->
             
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

<?php
require_once __DIR__ . '/../includes/footer.php';
?>
