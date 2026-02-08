<?php
// API endpoint for AJAX requests
if (isset($_GET['action'])) {
    header('Content-Type: application/json');
    
    switch ($_GET['action']) {
        case 'list':
            $filters = [
                'search' => $_GET['search'] ?? '',
                'course' => $_GET['course'] ?? '',
                'status' => $_GET['status'] ?? ''
            ];
            $students = getAllStudents($filters);
            echo json_encode(['success' => true, 'data' => $students]);
            exit;
            
        case 'get':
            $student = getStudentById($_GET['id']);
            if ($student) {
                $enrollments = getEnrollmentsByStudent($_GET['id']);
                echo json_encode(['success' => true, 'data' => $student, 'enrollments' => $enrollments]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Student not found']);
            }
            exit;
            
        case 'delete':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $result = deleteStudent($_POST['id']);
                echo json_encode(['success' => $result]);
            }
            exit;
            
        case 'update':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id = $_POST['id'];
                $data = [
                    'first_name' => $_POST['first_name'],
                    'middle_name' => $_POST['middle_name'] ?? '',
                    'last_name' => $_POST['last_name'],
                    'date_of_birth' => $_POST['date_of_birth'] ?? null,
                    'place_of_birth' => $_POST['place_of_birth'] ?? '',
                    'sex' => $_POST['sex'] ?? '',
                    'civil_status' => $_POST['civil_status'] ?? '',
                    'nationality' => $_POST['nationality'] ?? '',
                    'religion' => $_POST['religion'] ?? '',
                    'address' => $_POST['address'],
                    'phone' => $_POST['phone'],
                    'email' => $_POST['email'],
                    'guardian_name' => $_POST['guardian_name'] ?? '',
                    'guardian_phone' => $_POST['guardian_phone'] ?? '',
                    'guardian_relationship' => $_POST['guardian_relationship'] ?? '',
                    'course' => $_POST['course'] ?? '',
                    'year_level' => $_POST['year_level'] ?? '',
                    'section' => $_POST['section'] ?? '',
                    'status' => $_POST['status'] ?? 'active'
                ];
                $result = updateStudent($id, $data);
                echo json_encode(['success' => $result]);
            }
            exit;
    }
}

// Get initial data for the page
$students = getAllStudents();
$courses = getAllCourses();
?>

<!-- Student Management Page -->
<div class="space-y-6">
    <!-- Header with Search and Filters -->
    <div class="card bg-white shadow-md">
        <div class="card-body">
            <div class="flex flex-col lg:flex-row gap-4 items-center justify-between">
                <h2 class="card-title text-2xl font-bold text-gray-800">Student Management</h2>
                <a href="?page=enrollment" class="btn btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    New Enrollment
                </a>
            </div>

            <!-- Search and Filters -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Search Students</span>
                    </label>
                    <input 
                        type="text" 
                        id="searchInput"
                        placeholder="Name, ID, Email..." 
                        class="input input-bordered"
                    />
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Filter by Course</span>
                    </label>
                    <select id="courseFilter" class="select select-bordered">
                        <option value="">All Courses</option>
                        <option value="Bachelor of Science in Computer Science">Computer Science</option>
                        <option value="Bachelor of Science in Information Technology">Information Technology</option>
                        <option value="Bachelor of Arts in Business Administration">Business Admin</option>
                    </select>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Filter by Status</span>
                    </label>
                    <select id="statusFilter" class="select select-bordered">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <div class="form-control flex items-end">
                    <button type="button" class="btn btn-outline w-full" id="exportBtn">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Export
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Toggle -->
    <div class="flex justify-end gap-2 mb-4">
        <button type="button" class="btn btn-sm btn-active" id="tableViewBtn">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                <path d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
            </svg>
            Table View
        </button>
        <button type="button" class="btn btn-sm" id="cardViewBtn">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                <path d="M3 4a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm10 0a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1h-6a1 1 0 01-1-1V4zM3 14a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zm10 0a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1h-6a1 1 0 01-1-1v-6z"></path>
            </svg>
            Card View
        </button>
    </div>

    <!-- Table View -->
    <div id="tableView" class="card bg-white shadow-md">
        <div class="card-body">
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="text-gray-700">Student ID</th>
                            <th class="text-gray-700">Name</th>
                            <th class="text-gray-700">Course</th>
                            <th class="text-gray-700">Email</th>
                            <th class="text-gray-700">Status</th>
                            <th class="text-gray-700">Enrollment Date</th>
                            <th class="text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="studentsTableBody">
                        <?php if (empty($students)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-gray-500 py-8">No students found</td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($students as $student): ?>
                        <tr data-id="<?php echo htmlspecialchars($student['id']); ?>">
                            <td><span class="badge badge-primary"><?php echo htmlspecialchars($student['id']); ?></span></td>
                            <td><strong><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></strong></td>
                            <td><span class="text-sm"><?php echo htmlspecialchars($student['course']); ?></span></td>
                            <td><a href="mailto:<?php echo htmlspecialchars($student['email']); ?>" class="link link-primary text-sm"><?php echo htmlspecialchars($student['email']); ?></a></td>
                            <td><span class="badge badge-<?php echo $student['status'] === 'active' ? 'success' : 'warning'; ?>"><?php echo htmlspecialchars($student['status']); ?></span></td>
                            <td><?php echo date('M d, Y', strtotime($student['enrollment_date'])); ?></td>
                            <td>
                                <button class="btn btn-xs btn-outline" onclick="viewStudent('<?php echo $student['id']; ?>')">View</button>
                                <button class="btn btn-xs btn-warning" onclick="editStudent('<?php echo $student['id']; ?>')">Edit</button>
                                <button class="btn btn-xs btn-error" onclick="confirmDelete('<?php echo $student['id']; ?>')">Delete</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex justify-between items-center mt-6">
                <p class="text-sm text-gray-600" id="recordCount">Showing <?php echo count($students); ?> records</p>
                <div class="join">
                    <button class="join-item btn btn-sm" id="prevPageBtn">«</button>
                    <button class="join-item btn btn-sm btn-active" id="pageInfo">Page 1</button>
                    <button class="join-item btn btn-sm" id="nextPageBtn">»</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Card View -->
    <div id="cardView" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($students as $student): ?>
        <div class="card bg-white shadow-md hover:shadow-lg transition">
            <div class="card-body">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="font-bold text-gray-800"><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></h3>
                    <span class="badge badge-<?php echo $student['status'] === 'active' ? 'success' : 'warning'; ?>"><?php echo htmlspecialchars($student['status']); ?></span>
                </div>
                <p class="text-sm text-gray-600 mb-2">ID: <strong><?php echo htmlspecialchars($student['id']); ?></strong></p>
                <p class="text-sm text-gray-600 mb-2"><?php echo htmlspecialchars($student['course']); ?></p>
                <p class="text-sm text-gray-600 mb-4"><?php echo htmlspecialchars($student['email']); ?></p>
                <div class="card-actions gap-2">
                    <button class="btn btn-xs btn-primary flex-1" onclick="viewStudent('<?php echo $student['id']; ?>')">View</button>
                    <button class="btn btn-xs btn-warning flex-1" onclick="editStudent('<?php echo $student['id']; ?>')">Edit</button>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Student Detail Modal -->
<dialog id="studentDetailModal" class="modal">
    <div class="modal-box w-11/12 max-w-2xl">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        
        <h3 class="font-bold text-lg mb-4 text-gray-800">Student Details</h3>

        <div class="tabs tabs-bordered mb-4">
            <a class="tab tab-active" onclick="switchDetailTab('info')">Information</a>
            <a class="tab" onclick="switchDetailTab('enrollment')">Enrollments</a>
        </div>

        <!-- Info Tab -->
        <div id="tab-info" class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Student ID</p>
                    <p class="font-bold text-gray-800" id="modal-studentId"></p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Full Name</p>
                    <p class="font-bold text-gray-800" id="modal-fullName"></p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Date of Birth</p>
                    <p class="font-bold text-gray-800" id="modal-dob"></p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Nationality</p>
                    <p class="font-bold text-gray-800" id="modal-nationality"></p>
                </div>
                <div class="col-span-2">
                    <p class="text-sm text-gray-600">Address</p>
                    <p class="font-bold text-gray-800" id="modal-address"></p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Contact Number</p>
                    <p class="font-bold text-gray-800" id="modal-phone"></p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Email</p>
                    <p class="font-bold text-gray-800 break-words" id="modal-email"></p>
                </div>
            </div>

            <div class="divider"></div>

            <div>
                <p class="font-bold text-gray-800 mb-3">Guardian Information</p>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Name</p>
                        <p class="font-bold text-gray-800" id="modal-guardianName"></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Relationship</p>
                        <p class="font-bold text-gray-800" id="modal-guardianRelation"></p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-sm text-gray-600">Contact Number</p>
                        <p class="font-bold text-gray-800" id="modal-guardianPhone"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enrollment Tab -->
        <div id="tab-enrollment" class="hidden">
            <table class="table table-sm w-full">
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Credits</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody id="modal-enrollmentsList">
                    <tr><td colspan="4" class="text-center text-gray-500">No enrollments</td></tr>
                </tbody>
            </table>
        </div>

        <!-- Modal Actions -->
        <div class="modal-action mt-6">
            <button type="button" class="btn btn-outline" id="editStudentBtn">Edit</button>
            <button type="button" class="btn btn-error" id="deleteStudentBtn">Delete</button>
            <form method="dialog">
                <button class="btn">Close</button>
            </form>
        </div>
    </div>
</dialog>

<!-- Edit Student Modal -->
<dialog id="editStudentModal" class="modal">
    <div class="modal-box w-11/12 max-w-2xl">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>

        <h3 class="font-bold text-lg mb-4 text-gray-800">Edit Student Information</h3>

        <form id="editStudentForm" class="space-y-4">
            <input type="hidden" id="edit-studentId" />
            
            <div class="grid grid-cols-3 gap-4">
                <input type="text" placeholder="First Name" id="edit-firstName" class="input input-bordered" required />
                <input type="text" placeholder="Middle Name" id="edit-middleName" class="input input-bordered" />
                <input type="text" placeholder="Last Name" id="edit-lastName" class="input input-bordered" required />
            </div>

            <input type="email" placeholder="Email" id="edit-email" class="input input-bordered w-full" required />
            <input type="tel" placeholder="Phone" id="edit-phone" class="input input-bordered w-full" required />
            <textarea placeholder="Address" id="edit-address" class="textarea textarea-bordered w-full" rows="3" required></textarea>

            <div class="modal-action">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <button type="button" class="btn btn-outline" onclick="document.getElementById('editStudentModal').close()">Cancel</button>
            </div>
        </form>
    </div>
</dialog>

<script>
    let currentSelectedStudent = null;
    let allStudents = <?php echo json_encode($students); ?>;

    document.addEventListener('DOMContentLoaded', function() {
        setupEventListeners();
    });

    function setupEventListeners() {
        // Search and filter
        document.getElementById('searchInput').addEventListener('keyup', debounce(filterStudents, 300));
        document.getElementById('courseFilter').addEventListener('change', filterStudents);
        document.getElementById('statusFilter').addEventListener('change', filterStudents);
        
        // View toggle
        document.getElementById('tableViewBtn').addEventListener('click', switchToTableView);
        document.getElementById('cardViewBtn').addEventListener('click', switchToCardView);
        
        // Export
        document.getElementById('exportBtn').addEventListener('click', exportStudents);
        
        // Modal buttons
        document.getElementById('editStudentBtn').addEventListener('click', function() {
            if (currentSelectedStudent) {
                editStudent(currentSelectedStudent.id);
            }
        });
        
        document.getElementById('deleteStudentBtn').addEventListener('click', function() {
            if (currentSelectedStudent) {
                confirmDelete(currentSelectedStudent.id);
            }
        });
        
        // Edit form submission
        document.getElementById('editStudentForm').addEventListener('submit', saveStudentChanges);
    }

    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    function filterStudents() {
        const search = document.getElementById('searchInput').value;
        const course = document.getElementById('courseFilter').value;
        const status = document.getElementById('statusFilter').value;
        
        fetch(`?page=students&action=list&search=${encodeURIComponent(search)}&course=${encodeURIComponent(course)}&status=${encodeURIComponent(status)}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    allStudents = data.data;
                    renderStudents();
                }
            });
    }

    function renderStudents() {
        const tbody = document.getElementById('studentsTableBody');
        tbody.innerHTML = '';
        
        if (allStudents.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" class="text-center text-gray-500 py-8">No students found</td></tr>';
            document.getElementById('recordCount').textContent = 'Showing 0 records';
            return;
        }
        
        allStudents.forEach(student => {
            const row = document.createElement('tr');
            row.setAttribute('data-id', student.id);
            row.innerHTML = `
                <td><span class="badge badge-primary">${student.id}</span></td>
                <td><strong>${student.first_name} ${student.last_name}</strong></td>
                <td><span class="text-sm">${student.course}</span></td>
                <td><a href="mailto:${student.email}" class="link link-primary text-sm">${student.email}</a></td>
                <td><span class="badge badge-${student.status === 'active' ? 'success' : 'warning'}">${student.status}</span></td>
                <td>${new Date(student.enrollment_date).toLocaleDateString()}</td>
                <td>
                    <button class="btn btn-xs btn-outline" onclick="viewStudent('${student.id}')">View</button>
                    <button class="btn btn-xs btn-warning" onclick="editStudent('${student.id}')">Edit</button>
                    <button class="btn btn-xs btn-error" onclick="confirmDelete('${student.id}')">Delete</button>
                </td>
            `;
            tbody.appendChild(row);
        });
        
        document.getElementById('recordCount').textContent = `Showing ${allStudents.length} records`;
    }

    function viewStudent(studentId) {
        fetch(`?page=students&action=get&id=${studentId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const student = data.data;
                    currentSelectedStudent = student;
                    
                    // Populate modal
                    document.getElementById('modal-studentId').textContent = student.id;
                    document.getElementById('modal-fullName').textContent = `${student.first_name} ${student.middle_name || ''} ${student.last_name}`;
                    document.getElementById('modal-dob').textContent = student.date_of_birth || 'N/A';
                    document.getElementById('modal-nationality').textContent = student.nationality || 'N/A';
                    document.getElementById('modal-address').textContent = student.address || 'N/A';
                    document.getElementById('modal-phone').textContent = student.phone || 'N/A';
                    document.getElementById('modal-email').textContent = student.email || 'N/A';
                    document.getElementById('modal-guardianName').textContent = student.guardian_name || 'N/A';
                    document.getElementById('modal-guardianRelation').textContent = student.guardian_relationship || 'N/A';
                    document.getElementById('modal-guardianPhone').textContent = student.guardian_phone || 'N/A';
                    
                    // Populate enrollments
                    const enrollmentsTbody = document.getElementById('modal-enrollmentsList');
                    enrollmentsTbody.innerHTML = '';
                    
                    if (data.enrollments && data.enrollments.length > 0) {
                        data.enrollments.forEach(enrollment => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${enrollment.course_name} (${enrollment.code})</td>
                                <td>${enrollment.credits}</td>
                                <td><span class="badge badge-${enrollment.status === 'active' ? 'success' : 'info'}">${enrollment.status}</span></td>
                                <td>${new Date(enrollment.enrollment_date).toLocaleDateString()}</td>
                            `;
                            enrollmentsTbody.appendChild(row);
                        });
                    } else {
                        enrollmentsTbody.innerHTML = '<tr><td colspan="4" class="text-center text-gray-500">No enrollments</td></tr>';
                    }
                    
                    document.getElementById('studentDetailModal').showModal();
                }
            });
    }

    function editStudent(studentId) {
        fetch(`?page=students&action=get&id=${studentId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const student = data.data;
                    currentSelectedStudent = student;
                    
                    // Populate edit form
                    document.getElementById('edit-studentId').value = student.id;
                    document.getElementById('edit-firstName').value = student.first_name;
                    document.getElementById('edit-middleName').value = student.middle_name || '';
                    document.getElementById('edit-lastName').value = student.last_name;
                    document.getElementById('edit-email').value = student.email;
                    document.getElementById('edit-phone').value = student.phone;
                    document.getElementById('edit-address').value = student.address;
                    
                    document.getElementById('studentDetailModal').close();
                    document.getElementById('editStudentModal').showModal();
                }
            });
    }

    function saveStudentChanges(e) {
        e.preventDefault();
        
        const formData = new FormData();
        formData.append('id', document.getElementById('edit-studentId').value);
        formData.append('first_name', document.getElementById('edit-firstName').value);
        formData.append('middle_name', document.getElementById('edit-middleName').value);
        formData.append('last_name', document.getElementById('edit-lastName').value);
        formData.append('email', document.getElementById('edit-email').value);
        formData.append('phone', document.getElementById('edit-phone').value);
        formData.append('address', document.getElementById('edit-address').value);
        
        // Keep existing values for fields not in the form
        if (currentSelectedStudent) {
            formData.append('date_of_birth', currentSelectedStudent.date_of_birth || '');
            formData.append('place_of_birth', currentSelectedStudent.place_of_birth || '');
            formData.append('sex', currentSelectedStudent.sex || '');
            formData.append('civil_status', currentSelectedStudent.civil_status || '');
            formData.append('nationality', currentSelectedStudent.nationality || '');
            formData.append('religion', currentSelectedStudent.religion || '');
            formData.append('guardian_name', currentSelectedStudent.guardian_name || '');
            formData.append('guardian_phone', currentSelectedStudent.guardian_phone || '');
            formData.append('guardian_relationship', currentSelectedStudent.guardian_relationship || '');
            formData.append('course', currentSelectedStudent.course || '');
            formData.append('year_level', currentSelectedStudent.year_level || '');
            formData.append('section', currentSelectedStudent.section || '');
            formData.append('status', currentSelectedStudent.status || 'active');
        }
        
        fetch('?page=students&action=update', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire('Success', 'Student information updated successfully!', 'success');
                document.getElementById('editStudentModal').close();
                filterStudents(); // Refresh the list
            } else {
                Swal.fire('Error', 'Failed to update student information', 'error');
            }
        });
    }

    function confirmDelete(studentId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This will permanently delete this student record!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('id', studentId);
                
                fetch('?page=students&action=delete', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Deleted!', 'Student record has been deleted.', 'success');
                        document.getElementById('studentDetailModal').close();
                        filterStudents(); // Refresh the list
                    } else {
                        Swal.fire('Error', 'Failed to delete student record', 'error');
                    }
                });
            }
        });
    }

    function switchToTableView() {
        document.getElementById('tableView').classList.remove('hidden');
        document.getElementById('cardView').classList.add('hidden');
        document.getElementById('tableViewBtn').classList.add('btn-active');
        document.getElementById('cardViewBtn').classList.remove('btn-active');
    }

    function switchToCardView() {
        document.getElementById('tableView').classList.add('hidden');
        document.getElementById('cardView').classList.remove('hidden');
        document.getElementById('cardViewBtn').classList.add('btn-active');
        document.getElementById('tableViewBtn').classList.remove('btn-active');
    }

    function switchDetailTab(tab) {
        document.querySelectorAll('#studentDetailModal .tabs a').forEach(el => el.classList.remove('tab-active'));
        document.querySelectorAll('#studentDetailModal [id^="tab-"]').forEach(el => el.classList.add('hidden'));
        
        event.target.classList.add('tab-active');
        document.getElementById(`tab-${tab}`).classList.remove('hidden');
    }

    function exportStudents() {
        const csv = 'Student ID,Name,Course,Email,Status,Enrollment Date\n' + 
            allStudents.map(s => 
                `${s.id},"${s.first_name} ${s.last_name}","${s.course}",${s.email},${s.status},${s.enrollment_date}`
            ).join('\n');

        const blob = new Blob([csv], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'students.csv';
        a.click();
    }
</script>
