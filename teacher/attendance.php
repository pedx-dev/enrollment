<?php
// Get all courses and students from database
$allCourses = getAllCourses();
$allStudents = getAllStudents();
?>
<!-- Attendance Tracking Page -->
<div class="space-y-6">
    <div class="card bg-white shadow-md">
        <div class="card-body">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="card-title text-2xl font-bold text-gray-800">Attendance Tracking</h2>
                    <p class="text-gray-600">Record and manage student attendance</p>
                </div>
                <button class="btn btn-sm btn-primary" onclick="generateReport()">Generate Report</button>
            </div>
        </div>
    </div>

    <div class="card bg-white shadow-md">
        <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Select Course</span>
                    </label>
                    <select id="courseSelect" class="select select-bordered" onchange="loadClassStudents()">
                        <option value="">Choose a course</option>
                        <?php foreach ($allCourses as $course): ?>
                        <option value="<?php echo htmlspecialchars($course['name']); ?>"><?php echo htmlspecialchars($course['code'] . ' - ' . $course['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Date</span>
                    </label>
                    <input type="date" id="attendanceDate" class="input input-bordered" value="<?php echo date('Y-m-d'); ?>" />
                </div>
                <div class="form-control flex items-end">
                    <button class="btn btn-outline w-full" onclick="loadClassStudents()">Load Class</button>
                </div>
            </div>

            <div class="mb-6">
                <div class="flex gap-4 mb-4">
                    <button class="btn btn-sm btn-success" onclick="markAll('present')">Mark All Present</button>
                    <button class="btn btn-sm btn-error" onclick="markAll('absent')">Mark All Absent</button>
                </div>
            </div>

            <div class="space-y-3" id="attendanceList">
                <div class="text-center text-gray-500 py-8">Select a course and click "Load Class" to view students</div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button class="btn btn-outline" onclick="resetAttendance()">Reset</button>
                <button class="btn btn-primary" onclick="submitAttendance()">Submit Attendance</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Students data from PHP
    const allStudentsData = <?php echo json_encode(array_map(function($s) {
        return [
            'id' => $s['id'],
            'student_id' => $s['student_id'],
            'first_name' => $s['first_name'],
            'last_name' => $s['last_name'],
            'course' => $s['course']
        ];
    }, $allStudents)); ?>;

    function loadClassStudents() {
        const courseFilter = document.getElementById('courseSelect').value;
        const container = document.getElementById('attendanceList');
        
        if (!courseFilter) {
            container.innerHTML = '<div class="text-center text-gray-500 py-8">Select a course and click "Load Class" to view students</div>';
            return;
        }

        // Filter students by course
        const students = allStudentsData.filter(s => s.course === courseFilter);
        
        container.innerHTML = '';

        if (students.length === 0) {
            container.innerHTML = '<div class="text-center text-gray-500 py-8">No students found in this course</div>';
            return;
        }

        students.forEach(student => {
            const item = document.createElement('div');
            item.className = 'flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50';
            item.innerHTML = `
                <div>
                    <p class="font-semibold text-gray-800">${student.first_name} ${student.last_name}</p>
                    <p class="text-sm text-gray-600">${student.student_id}</p>
                </div>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="attendance-${student.id}" value="present" class="radio radio-success" checked />
                        <span class="text-sm">Present</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="attendance-${student.id}" value="absent" class="radio radio-error" />
                        <span class="text-sm">Absent</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="attendance-${student.id}" value="late" class="radio radio-warning" />
                        <span class="text-sm">Late</span>
                    </label>
                </div>
            `;
            container.appendChild(item);
        });
    }

    function markAll(status) {
        const radios = document.querySelectorAll(`input[type="radio"][value="${status}"]`);
        radios.forEach(radio => radio.checked = true);
    }

    function resetAttendance() {
        const radios = document.querySelectorAll('input[type="radio"][value="present"]');
        radios.forEach(radio => radio.checked = true);
    }

    function submitAttendance() {
        const courseFilter = document.getElementById('courseSelect').value;
        const attendanceDate = document.getElementById('attendanceDate').value;
        
        if (!courseFilter) {
            Swal.fire({
                icon: 'warning',
                title: 'No Course Selected',
                text: 'Please select a course first.'
            });
            return;
        }

        // Collect attendance data
        const attendanceData = [];
        const students = allStudentsData.filter(s => s.course === courseFilter);
        
        students.forEach(student => {
            const radios = document.querySelectorAll(`input[name="attendance-${student.id}"]`);
            radios.forEach(radio => {
                if (radio.checked) {
                    attendanceData.push({
                        student_id: student.id,
                        status: radio.value
                    });
                }
            });
        });

        // Show success message (in a real app, this would save to database)
        Swal.fire({
            icon: 'success',
            title: 'Attendance Submitted',
            text: `Attendance for ${students.length} students has been recorded for ${attendanceDate}.`,
            confirmButtonColor: '#30425A'
        });
    }

    function generateReport() {
        Swal.fire({
            icon: 'info',
            title: 'Generate Report',
            text: 'Attendance report feature coming soon!',
            confirmButtonColor: '#30425A'
        });
    }
</script>
