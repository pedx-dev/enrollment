<?php 
$teacherName = $userInfo['name'] ?? ($user_name ?? '');

// Get all courses from database
$allCourses = getAllCourses();
$settings = getSettings();
$currentSemester = $settings['current_semester'] ?? '1st Semester';
$currentYear = $settings['current_year'] ?? '2024-2025';

// Get student counts by course
$studentCounts = [];
$allStudents = getAllStudents();
foreach ($allStudents as $student) {
    $courseName = $student['course'];
    if (!isset($studentCounts[$courseName])) {
        $studentCounts[$courseName] = 0;
    }
    $studentCounts[$courseName]++;
}
?>
<!-- Teacher Classes Page -->
<div class="space-y-6">
    <div class="card bg-white shadow-md">
        <div class="card-body">
            <h2 class="card-title text-2xl font-bold text-gray-800">My Classes</h2>
            <p class="text-gray-600">View and manage your teaching assignments</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6" id="classesGrid">
        <?php if (empty($allCourses)): ?>
        <div class="text-gray-500">No classes assigned.</div>
        <?php else: ?>
        <?php foreach ($allCourses as $course): ?>
        <?php $studentCount = $studentCounts[$course['name']] ?? 0; ?>
        <div class="card bg-white shadow-md hover:shadow-lg transition">
            <div class="card-body">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h2 class="card-title text-lg"><?php echo htmlspecialchars($course['name']); ?></h2>
                        <p class="text-sm text-gray-600"><?php echo htmlspecialchars($course['code']); ?></p>
                    </div>
                    <span class="badge badge-primary"><?php echo htmlspecialchars($course['credits']); ?> Credits</span>
                </div>

                <p class="text-sm text-gray-600 mb-4"><?php echo htmlspecialchars($course['description'] ?? 'No description available'); ?></p>

                <div class="bg-gray-50 p-4 rounded-lg mb-4 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Enrolled Students:</span>
                        <span class="font-semibold"><?php echo $studentCount; ?></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Department:</span>
                        <span class="font-semibold"><?php echo htmlspecialchars($course['department'] ?? 'General'); ?></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Instructor:</span>
                        <span class="font-semibold"><?php echo htmlspecialchars($course['instructor'] ?? 'TBA'); ?></span>
                    </div>
                </div>

                <div class="card-actions gap-2">
                    <a href="?page=roster" class="btn btn-sm btn-primary flex-1">View Roster</a>
                    <button class="btn btn-sm btn-outline flex-1" onclick="showCourseDetails(<?php echo $course['id']; ?>)">Course Details</button>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <dialog id="courseDetailsModal" class="modal">
        <div class="modal-box max-w-2xl">
            <h3 class="font-bold text-lg text-gray-800" id="courseDetailsTitle">Course Details</h3>
            <div class="mt-4" id="courseDetailsList"></div>
            <div class="modal-action">
                <button class="btn btn-outline" onclick="document.getElementById('courseDetailsModal').close()">Close</button>
            </div>
        </div>
    </dialog>
</div>

<script>
    // Course data from PHP
    const coursesData = <?php echo json_encode($allCourses); ?>;
    
    function showCourseDetails(courseId) {
        const course = coursesData.find(c => c.id == courseId);
        if (!course) return;

        document.getElementById('courseDetailsTitle').textContent = `${course.code} - ${course.name}`;
        
        const list = document.getElementById('courseDetailsList');
        list.innerHTML = `
            <div class="space-y-4">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-semibold text-gray-800 mb-2">Course Information</h4>
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div>
                            <span class="text-gray-600">Code:</span>
                            <span class="font-medium ml-2">${course.code}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Credits:</span>
                            <span class="font-medium ml-2">${course.credits}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Department:</span>
                            <span class="font-medium ml-2">${course.department || 'General'}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Instructor:</span>
                            <span class="font-medium ml-2">${course.instructor || 'TBA'}</span>
                        </div>
                    </div>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-800 mb-2">Description</h4>
                    <p class="text-sm text-gray-600">${course.description || 'No description available.'}</p>
                </div>
            </div>
        `;

        document.getElementById('courseDetailsModal').showModal();
    }
</script>
