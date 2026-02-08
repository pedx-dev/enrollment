<?php
define('SITE_URL', 'http://localhost/enroll');
require_once __DIR__ . '/header.php';

// Check authentication
if (!$is_logged_in) {
    header('Location: ' . SITE_URL . '/login.php');
    exit;
}

// Check if user is teacher
if ($user_role !== 'teacher') {
    header('Location: ' . SITE_URL . '/login.php?unauthorized=1');
    exit;
}
?>

    <!-- Sidebar Overlay (Mobile) -->
    <div id="sidebarOverlay" class="hidden fixed inset-0 z-30 bg-black bg-opacity-50 lg:hidden" onclick="toggleMobileSidebar()"></div>

    <!-- Sidebar -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 w-64 sidebar-bg transform -translate-x-full lg:static lg:translate-x-0 transition-transform duration-300 overflow-y-auto">
        <!-- Logo Section -->
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-lg" style="background-color: #30425A;"></div>
                <div>
                    <h1 class="text-lg font-bold" style="color: #222431;">Teacher</h1>
                    <p class="text-xs text-gray-500">Portal</p>
                </div>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="p-4 space-y-2">
            <a href="<?php echo SITE_URL; ?>/teacher/dashboard.php" class="nav-link flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-100 transition" style="color: #222431;">
                <i class="fas fa-chart-line w-5"></i>
                <span class="font-medium">Dashboard</span>
            </a>

           

            <a href="<?php echo SITE_URL; ?>/teacher/roster.php" class="nav-link flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-100 transition" style="color: #222431;">
                <i class="fas fa-list w-5"></i>
                <span class="font-medium">Student Roster</span>
            </a>

           

          
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Top Navigation Bar -->
        <header class="navbar-bg text-white px-6 py-4 flex items-center justify-between">
            <!-- Left Side: Menu Toggle & Title -->
            <div class="flex items-center space-x-4">
                <button onclick="toggleMobileSidebar()" class="lg:hidden text-white hover:bg-white hover:text-gray-900 p-2 rounded-lg transition">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <h2 class="text-xl font-semibold hidden md:block"><?php echo $pageTitle ?? 'Dashboard'; ?></h2>
            </div>

            <!-- Right Side: User Info & Logout -->
            <div class="flex items-center space-x-6">
                <!-- User Info -->
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background-color: rgba(255,255,255,0.2);">
                        <i class="fas fa-chalkboard-user text-white"></i>
                    </div>
                    <div class="hidden sm:block">
                        <p class="text-sm font-medium text-white"><?php echo htmlspecialchars($user_name); ?></p>
                        <p class="text-xs text-gray-300">Teacher</p>
                    </div>
                </div>

                <!-- Separator -->
                <div class="hidden sm:block w-px h-6" style="background-color: rgba(255,255,255,0.2);"></div>

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
        </header>

        <!-- Page Content -->
        <main class="flex-1 overflow-y-auto">
            <div class="container mx-auto px-4 py-8 max-w-7xl">
