<?php 
$teacherName = $userInfo['name'] ?? ($user_name ?? '');
// Get students count for this teacher (based on courses they teach)
$teacherId = $_SESSION['user_id'] ?? null;
$totalStudentsForTeacher = 0;
if ($teacherId) {
    // Get all students count - in a real app, this would filter by teacher's courses
    $allStudents = getAllStudents();
    $totalStudentsForTeacher = count($allStudents);
}
?>
<!-- Teacher Dashboard Overview with Admin-Style Design -->
<div class="space-y-6 w-full">
    <!-- Stats Cards with Gradient Enhancements -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 w-full">
        <!-- Classes Today -->
        <div class="card gradient-card stat-card blue card-hover shadow-lg border-0">
            <div class="card-body relative z-10 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/90 text-sm font-medium">Classes Today</p>
                        <p class="text-4xl font-bold text-white mt-2">3</p>
                        <p class="text-xs text-white/80 mt-2">Next class in 2 hours</p>
                    </div>
                    <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center stat-icon-wrapper">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Students -->
        <div class="card gradient-card stat-card green card-hover shadow-lg border-0">
            <div class="card-body relative z-10 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/90 text-sm font-medium">Total Students</p>
                        <p class="text-4xl font-bold text-white mt-2"><?php echo $totalStudentsForTeacher; ?></p>
                        <p class="text-xs text-white/80 mt-2">Across all classes</p>
                    </div>
                    <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center stat-icon-wrapper">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10h.01M13 16H9m4-4H9m6-2a3 3 0 11-6 0 3 3 0 016 0zM9 20H5a2 2 0 01-2-2v-2a3 3 0 015.856-1.487M15 20H9"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Rate -->
        <div class="card gradient-card stat-card purple card-hover shadow-lg border-0">
            <div class="card-body relative z-10 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/90 text-sm font-medium">Attendance Rate</p>
                        <p class="text-4xl font-bold text-white mt-2">94%</p>
                        <p class="text-xs text-white/80 mt-2 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"></path>
                            </svg>
                            +2% from last week
                        </p>
                    </div>
                    <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center stat-icon-wrapper">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Grades -->
        <div class="card gradient-card stat-card orange card-hover shadow-lg border-0">
            <div class="card-body relative z-10 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/90 text-sm font-medium">Pending Grades</p>
                        <p class="text-4xl font-bold text-white mt-2">12</p>
                        <p class="text-xs text-white/80 mt-2">To be submitted</p>
                    </div>
                    <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center stat-icon-wrapper">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area - Two Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 w-full">
        <!-- Today's Schedule -->
        <div class="lg:col-span-2 card bg-white shadow-md border-0 overflow-hidden hover:shadow-lg transition-shadow duration-300">
            <div class="card-body p-6">
                <h2 class="card-title text-xl font-bold text-gray-800 mb-5 flex items-center gap-2">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Today's Schedule
                </h2>

                <div class="space-y-3">
                    <div class="border-l-4 border-blue-500 pl-4 py-3 hover:bg-blue-50 rounded-r transition duration-200 cursor-pointer">
                        <p class="font-semibold text-gray-900 text-sm">Introduction to Programming (CS101)</p>
                        <p class="text-sm text-gray-600 mt-1 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            9:00 AM - 10:30 AM
                        </p>
                        <p class="text-xs text-gray-500 mt-1">Room 101 | Section A | 45 students</p>
                    </div>

                    <div class="border-l-4 border-green-500 pl-4 py-3 hover:bg-green-50 rounded-r transition duration-200 cursor-pointer">
                        <p class="font-semibold text-gray-900 text-sm">Data Structures (CS201)</p>
                        <p class="text-sm text-gray-600 mt-1 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            1:00 PM - 2:30 PM
                        </p>
                        <p class="text-xs text-gray-500 mt-1">Room 205 | Section B | 38 students</p>
                    </div>

                    <div class="border-l-4 border-purple-500 pl-4 py-3 hover:bg-purple-50 rounded-r transition duration-200 cursor-pointer">
                        <p class="font-semibold text-gray-900 text-sm">Web Development (CS102)</p>
                        <p class="text-sm text-gray-600 mt-1 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            3:00 PM - 4:30 PM
                        </p>
                        <p class="text-xs text-gray-500 mt-1">Lab 102 | Section C | 40 students</p>
                    </div>
                </div>

                <div class="card-actions mt-6 pt-4 border-t border-gray-200">
                    <a href="?page=classes" class="btn btn-sm btn-primary gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        View Full Schedule
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card bg-white shadow-md border-0 overflow-hidden hover:shadow-lg transition-shadow duration-300">
            <div class="card-body p-6">
                <h2 class="card-title text-xl font-bold text-gray-800 mb-5 flex items-center gap-2">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Quick Actions
                </h2>

                <div class="space-y-3">
                    <a href="?page=attendance" class="btn btn-primary w-full btn-sm justify-start gap-2 hover:shadow-md transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        Mark Attendance
                    </a>
                    <a href="?page=grades" class="btn btn-outline btn-primary w-full btn-sm justify-start gap-2 hover:bg-blue-50 transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Enter Grades
                    </a>
                    <a href="?page=roster" class="btn btn-outline btn-primary w-full btn-sm justify-start gap-2 hover:bg-blue-50 transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10h.01M13 16H9m4-4H9m6-2a3 3 0 11-6 0 3 3 0 016 0zM9 20H5a2 2 0 01-2-2v-2a3 3 0 015.856-1.487M15 20H9"></path>
                        </svg>
                        Student Roster
                    </a>
                    <a href="?page=classes" class="btn btn-outline btn-primary w-full btn-sm justify-start gap-2 hover:bg-blue-50 transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        My Classes
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="card bg-white shadow-md border-0 overflow-hidden hover:shadow-lg transition-shadow duration-300">
        <div class="card-body p-6">
            <h2 class="card-title text-xl font-bold text-gray-800 mb-5 flex items-center gap-2">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Recent Activity
            </h2>

            <div class="space-y-3">
                <div class="flex gap-4 pb-4 border-b border-gray-200 last:border-0 hover:bg-gray-50 p-3 rounded transition duration-200">
                    <div class="flex-shrink-0">
                        <div class="flex-shrink-0 h-10 w-10 rounded-lg bg-green-100 flex items-center justify-center">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-900 text-sm">Attendance marked for CS101</p>
                        <p class="text-sm text-gray-600 mt-1">Marked attendance for 45 students</p>
                        <p class="text-xs text-gray-500 mt-0.5">Today at 10:45 AM</p>
                    </div>
                    <span class="badge badge-success badge-sm flex-shrink-0">Completed</span>
                </div>

                <div class="flex gap-4 pb-4 border-b border-gray-200 last:border-0 hover:bg-gray-50 p-3 rounded transition duration-200">
                    <div class="flex-shrink-0">
                        <div class="flex-shrink-0 h-10 w-10 rounded-lg bg-green-100 flex items-center justify-center">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-900 text-sm">Grades submitted for CS201 Quiz 1</p>
                        <p class="text-sm text-gray-600 mt-1">Submitted grades for 38 students</p>
                        <p class="text-xs text-gray-500 mt-0.5">Yesterday at 3:20 PM</p>
                    </div>
                    <span class="badge badge-success badge-sm flex-shrink-0">Completed</span>
                </div>

                <div class="flex gap-4 hover:bg-gray-50 p-3 rounded transition duration-200">
                    <div class="flex-shrink-0">
                        <div class="flex-shrink-0 h-10 w-10 rounded-lg bg-warning/20 flex items-center justify-center">
                            <svg class="h-6 w-6 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-900 text-sm">Assignment deadline for CS102</p>
                        <p class="text-sm text-gray-600 mt-1">Web Development Project due soon</p>
                        <p class="text-xs text-gray-500 mt-0.5">Due in 3 days</p>
                    </div>
                    <span class="badge badge-warning badge-sm flex-shrink-0">Upcoming</span>
                </div>
            </div>
        </div>
    </div>
</div>
