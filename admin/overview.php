<?php
// Get data from database
$stats = getStudentStats();
$totalEnrollments = getTotalEnrollments();
$courses = getAllCourses();
$settings = getSettings();
$recentStudents = getRecentStudents(5);
?>

<!-- Admin Dashboard Overview Page with Enhanced Design -->
<div class="space-y-6">
    <!-- Stats Cards with Gradient Enhancements -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Students - Enhanced -->
        <div class="card gradient-card stat-card blue card-hover shadow-lg">
            <div class="card-body relative z-10">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/90 text-sm font-medium">Total Students</p>
                        <p class="text-4xl font-bold text-white mt-2"><?php echo $stats['total']; ?></p>
                        <p class="text-xs text-white/80 mt-2">Active enrollments</p>
                    </div>
                    <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center stat-icon-wrapper">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10h.01M13 16H9m4-4H9m6-2a3 3 0 11-6 0 3 3 0 016 0zM9 20H5a2 2 0 01-2-2v-2a3 3 0 015.856-1.487M15 20H9"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- New Enrollments This Month - Enhanced -->
        <div class="card gradient-card stat-card green card-hover shadow-lg">
            <div class="card-body relative z-10">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/90 text-sm font-medium">New This Month</p>
                        <p class="text-4xl font-bold text-white mt-2"><?php echo $stats['newThisMonth']; ?></p>
                        <p class="text-xs text-white/80 mt-2 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"></path>
                            </svg>
                            Recent enrollments
                        </p>
                    </div>
                    <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center stat-icon-wrapper">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Enrollments - Enhanced -->
        <div class="card gradient-card stat-card purple card-hover shadow-lg">
            <div class="card-body relative z-10">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/90 text-sm font-medium">Total Enrollments</p>
                        <p class="text-4xl font-bold text-white mt-2"><?php echo $totalEnrollments; ?></p>
                        <p class="text-xs text-white/80 mt-2">Course registrations</p>
                    </div>
                    <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center stat-icon-wrapper">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.5 6.253 3 9.756 3 14s3.5 7.747 9 7.747m0-13c5.5 0 9 3.503 9 7.747M3 14h18"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Courses - Enhanced -->
        <div class="card gradient-card stat-card orange card-hover shadow-lg">
            <div class="card-body relative z-10">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/90 text-sm font-medium">Active Courses</p>
                        <p class="text-4xl font-bold text-white mt-2"><?php echo count($courses); ?></p>
                        <p class="text-xs text-white/80 mt-2">Currently offered</p>
                    </div>
                    <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center stat-icon-wrapper">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Enrollments -->
        <div class="lg:col-span-2 card bg-white shadow-md">
            <div class="card-body">
                <h2 class="card-title text-xl font-bold text-gray-800 mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Recent Enrollments
                </h2>

                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="text-gray-700">Student ID</th>
                                <th class="text-gray-700">Name</th>
                                <th class="text-gray-700">Course</th>
                                <th class="text-gray-700">Date</th>
                                <th class="text-gray-700">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recentStudents)): ?>
                            <tr>
                                <td colspan="5" class="text-center text-gray-500">No enrollments yet</td>
                            </tr>
                            <?php else: ?>
                            <?php foreach ($recentStudents as $student): ?>
                            <tr>
                                <td><span class="badge badge-primary"><?php echo htmlspecialchars($student['id']); ?></span></td>
                                <td><strong><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></strong></td>
                                <td><?php echo htmlspecialchars($student['course']); ?></td>
                                <td><?php echo date('M d, Y', strtotime($student['enrollment_date'])); ?></td>
                                <td><span class="badge badge-<?php echo $student['status'] === 'active' ? 'success' : 'warning'; ?>"><?php echo htmlspecialchars($student['status']); ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="card-actions mt-4">
                    <a href="?page=students" class="btn btn-sm btn-primary">View All</a>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card bg-white shadow-md">
            <div class="card-body">
                <h2 class="card-title text-xl font-bold text-gray-800 mb-4">Quick Actions</h2>

                <div class="space-y-3">
                    <a href="?page=enrollment" class="btn btn-block btn-primary btn-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        New Student Enrollment
                    </a>
                    <a href="?page=students" class="btn btn-block btn-outline btn-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Manage Students
                    </a>
                    <a href="?page=courses" class="btn btn-block btn-outline btn-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.5 6.253 3 9.756 3 14s3.5 7.747 9 7.747m0-13c5.5 0 9 3.503 9 7.747"></path>
                        </svg>
                        View Courses
                    </a>
                    <a href="?page=teachers" class="btn btn-block btn-outline btn-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Teachers Directory
                    </a>
                </div>

                <div class="divider my-4"></div>

                <h3 class="font-bold text-gray-700 mb-3">System Info</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Academic Year:</span>
                        <span class="font-semibold text-gray-800"><?php echo htmlspecialchars($settings['academic_year'] ?? '2024-2025'); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Semester:</span>
                        <span class="font-semibold text-gray-800"><?php echo htmlspecialchars($settings['semester'] ?? 'First Semester'); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
