<?php 
$teacherName = $userInfo['name'] ?? ($user_name ?? '');
$teacherId = $_SESSION['user_id'] ?? null;

// Get all students and courses from database
$allStudents = getAllStudents();
$allCourses = getAllCourses();

// Convert students to a format usable by JavaScript (for filtering)
$studentsJson = json_encode(array_map(function($s) {
    return [
        'id' => $s['student_id'],
        'first_name' => $s['first_name'],
        'last_name' => $s['last_name'],
        'email' => $s['email'],
        'phone' => $s['phone'],
        'status' => $s['status'],
        'course' => $s['course']
    ];
}, $allStudents));

$coursesJson = json_encode(array_map(function($c) {
    return [
        'id' => $c['id'],
        'code' => $c['code'],
        'name' => $c['name']
    ];
}, $allCourses));
?>
<!-- Student Roster Page -->
<div class="space-y-6">
    <div class="card bg-white shadow-md">
        <div class="card-body">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="card-title text-2xl font-bold text-gray-800">Student Roster</h2>
                    <p class="text-gray-600">All enrolled students in your classes</p>
                </div>
                <button class="btn btn-sm btn-outline" onclick="window.print()">Print Roster</button>
            </div>
        </div>
    </div>

    <div class="card bg-white shadow-md">
        <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Filter by Course</span>
                    </label>
                    <select id="courseRosterFilter" class="select select-bordered">
                        <option value="">All Courses</option>
                        <?php foreach ($allCourses as $course): ?>
                        <option value="<?php echo htmlspecialchars($course['name']); ?>"><?php echo htmlspecialchars($course['code'] . ' - ' . $course['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Search Student</span>
                    </label>
                    <input type="text" id="rosterSearch" placeholder="Name or ID..." class="input input-bordered" />
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="text-gray-700">Student ID</th>
                            <th class="text-gray-700">Name</th>
                            <th class="text-gray-700">Course</th>
                            <th class="text-gray-700">Email</th>
                            <th class="text-gray-700">Status</th>
                            <th class="text-gray-700">Contact</th>
                        </tr>
                    </thead>
                    <tbody id="rosterTableBody">
                        <?php if (empty($allStudents)): ?>
                        <tr>
                            <td colspan="6" class="text-center text-gray-500 py-8">No students found</td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($allStudents as $student): ?>
                        <tr class="student-row" 
                            data-name="<?php echo htmlspecialchars(strtolower($student['first_name'] . ' ' . $student['last_name'])); ?>"
                            data-id="<?php echo htmlspecialchars(strtolower($student['student_id'])); ?>"
                            data-course="<?php echo htmlspecialchars($student['course']); ?>">
                            <td><span class="badge badge-primary"><?php echo htmlspecialchars($student['student_id']); ?></span></td>
                            <td><strong><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></strong></td>
                            <td class="text-sm"><?php echo htmlspecialchars($student['course']); ?></td>
                            <td><a href="mailto:<?php echo htmlspecialchars($student['email']); ?>" class="link link-primary text-sm"><?php echo htmlspecialchars($student['email']); ?></a></td>
                            <td><span class="badge badge-<?php echo $student['status'] === 'Enrolled' ? 'success' : 'warning'; ?>"><?php echo htmlspecialchars($student['status']); ?></span></td>
                            <td><?php echo htmlspecialchars($student['phone']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('rosterSearch').addEventListener('keyup', filterRoster);
        document.getElementById('courseRosterFilter').addEventListener('change', filterRoster);
    });

    function filterRoster() {
        const searchQuery = document.getElementById('rosterSearch').value.toLowerCase();
        const courseFilter = document.getElementById('courseRosterFilter').value;

        const rows = document.querySelectorAll('.student-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const name = row.dataset.name;
            const id = row.dataset.id;
            const course = row.dataset.course;

            const matchesSearch = name.includes(searchQuery) || id.includes(searchQuery);
            const matchesCourse = !courseFilter || course === courseFilter;

            if (matchesSearch && matchesCourse) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        // Show "no results" message if needed
        const tbody = document.getElementById('rosterTableBody');
        let noResultsRow = tbody.querySelector('.no-results-row');
        
        if (visibleCount === 0 && rows.length > 0) {
            if (!noResultsRow) {
                noResultsRow = document.createElement('tr');
                noResultsRow.className = 'no-results-row';
                noResultsRow.innerHTML = '<td colspan="6" class="text-center text-gray-500 py-8">No students match your filters</td>';
                tbody.appendChild(noResultsRow);
            }
            noResultsRow.style.display = '';
        } else if (noResultsRow) {
            noResultsRow.style.display = 'none';
        }
    }
</script>
