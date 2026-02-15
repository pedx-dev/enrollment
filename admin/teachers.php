<?php
// API endpoint for AJAX requests
if (isset($_GET['action']) || (isset($_POST['action']) && $_SERVER['REQUEST_METHOD'] === 'POST')) {
    header('Content-Type: application/json');
    $action = $_GET['action'] ?? $_POST['action'];
    
    switch ($action) {
        case 'list':
            $filters = [
                'search' => $_GET['search'] ?? '',
                'department' => $_GET['department'] ?? ''
            ];
            $teachers = getAllTeachers($filters);
            echo json_encode(['success' => true, 'data' => $teachers]);
            exit;
            
        case 'get':
            $teacher = getTeacherById($_GET['id']);
            if ($teacher) {
                $courses = getCoursesByInstructor($_GET['id']);
                echo json_encode(['success' => true, 'data' => $teacher, 'courses' => $courses]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Teacher not found']);
            }
            exit;
            
        case 'add_teacher':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = [
                    'name' => $_POST['name'],
                    'email' => $_POST['email'],
                    'department' => $_POST['department'],
                    'specialization' => $_POST['specialization'] ?? '',
                    'phone' => $_POST['phone'] ?? '',
                    'password' => $_POST['password'] ?? 'password123'
                ];
                $result = addTeacher($data);
                echo json_encode(['success' => $result, 'message' => $result ? 'Teacher added successfully' : 'Failed to add teacher']);
            }
            exit;
            
        case 'update_teacher':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id = $_POST['id'];
                $data = [
                    'name' => $_POST['name'],
                    'email' => $_POST['email'],
                    'department' => $_POST['department'],
                    'specialization' => $_POST['specialization'] ?? '',
                    'phone' => $_POST['phone'] ?? ''
                ];
                $result = updateTeacher($id, $data);
                echo json_encode(['success' => $result, 'message' => $result ? 'Teacher updated successfully' : 'Failed to update teacher']);
            }
            exit;
            
        case 'delete_teacher':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $result = deleteTeacher($_POST['id']);
                echo json_encode(['success' => $result, 'message' => $result ? 'Teacher deleted successfully' : 'Failed to delete teacher']);
            }
            exit;
    }
}

// Get teachers from database
$teachers = getAllTeachers();
?>

<!-- Teachers Management Page -->
<div class="space-y-6">
    <!-- Header -->
    <div class="card bg-white shadow-md">
        <div class="card-body">
            <div class="flex flex-col lg:flex-row gap-4 items-center justify-between">
                <div>
                    <h2 class="card-title text-2xl font-bold text-gray-800">Teachers Directory</h2>
                    <p class="text-gray-600 text-sm mt-2">Faculty members and their courses</p>
                </div>
                <button class="btn btn-primary" id="addTeacherBtn" onclick="openAddTeacherModal()">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Teacher
                </button>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="card bg-white shadow-md">
        <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Search Teachers</span>
                    </label>
                    <input 
                        type="text" 
                        id="searchTeachers"
                        placeholder="Name, email..." 
                        class="input input-bordered"
                    />
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Filter by Department</span>
                    </label>
                    <select id="departmentFilter" class="select select-bordered">
                        <option value="">All Departments</option>
                        <option value="Computer Science">Computer Science</option>
                        <option value="Business Administration">Business Administration</option>
                        <option value="Information Technology">Information Technology</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Teachers Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="teachersGrid">
        <?php if (empty($teachers)): ?>
        <div class="col-span-full text-center text-gray-500 py-8">No teachers found</div>
        <?php else: ?>
        <?php foreach ($teachers as $teacher): ?>
        <div class="card bg-white shadow-md hover:shadow-lg transition cursor-pointer">
            <div class="card-body">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center text-white font-bold">
                        <?php echo strtoupper(substr($teacher['name'], 0, 1)); ?>
                    </div>
                    <div>
                        <h2 class="card-title text-lg text-gray-800"><?php echo htmlspecialchars($teacher['name']); ?></h2>
                        <p class="text-sm text-gray-600"><?php echo htmlspecialchars($teacher['department']); ?></p>
                    </div>
                </div>

                <div class="space-y-2 text-sm mb-4">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <a href="mailto:<?php echo htmlspecialchars($teacher['email']); ?>" class="link link-primary"><?php echo htmlspecialchars($teacher['email']); ?></a>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <span><?php echo htmlspecialchars($teacher['phone'] ?? 'N/A'); ?></span>
                    </div>
                </div>

                <div class="badge badge-outline mb-4"><?php echo htmlspecialchars($teacher['specialization'] ?? 'General'); ?></div>

                <div class="card-actions gap-2">
                    <button class="btn btn-sm btn-outline flex-1" onclick="viewTeacher(<?php echo $teacher['id']; ?>)">View</button>
                    <button class="btn btn-sm btn-warning" onclick="editTeacher(<?php echo $teacher['id']; ?>)">Edit</button>
                    <button class="btn btn-sm btn-error" onclick="deleteTeacher(<?php echo $teacher['id']; ?>)">Delete</button>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Add/Edit Teacher Modal -->
<dialog id="teacherModal" class="modal">
    <div class="modal-box">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        
        <h3 class="font-bold text-lg mb-4" id="teacherModalTitle">Add New Teacher</h3>

        <form id="teacherForm" class="space-y-4">
            <div class="form-control">
                <label class="label">
                    <span class="label-text">Full Name</span>
                </label>
                <input type="text" id="teacherName" placeholder="Full name" class="input input-bordered" required />
            </div>

            <div class="form-control">
                <label class="label">
                    <span class="label-text">Email</span>
                </label>
                <input type="email" id="teacherEmail" placeholder="email@college.edu" class="input input-bordered" required />
            </div>

            <div class="form-control">
                <label class="label">
                    <span class="label-text">Department</span>
                </label>
                <select id="teacherDepartment" class="select select-bordered" required>
                    <option value="">Select department</option>
                    <option value="Computer Science">Computer Science</option>
                    <option value="Business Administration">Business Administration</option>
                    <option value="Information Technology">Information Technology</option>
                </select>
            </div>

            <div class="form-control">
                <label class="label">
                    <span class="label-text">Specialization</span>
                </label>
                <input type="text" id="teacherSpecialization" placeholder="e.g., Data Science" class="input input-bordered" />
            </div>

            <div class="form-control">
                <label class="label">
                    <span class="label-text">Contact Number</span>
                </label>
                <input type="tel" id="teacherPhone" placeholder="Phone number" class="input input-bordered" />
            </div>

            <div class="modal-action">
                <button type="submit" class="btn btn-primary">Save Teacher</button>
                <button type="button" class="btn btn-outline" onclick="document.getElementById('teacherModal').close()">Cancel</button>
            </div>
        </form>
    </div>
</dialog>

<!-- Teacher Detail Modal -->
<dialog id="teacherDetailModal" class="modal">
    <div class="modal-box">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        
        <h3 class="font-bold text-lg mb-4">Teacher Details</h3>

        <div class="space-y-4">
            <div>
                <p class="text-sm text-gray-600">Name</p>
                <p class="font-bold text-gray-800" id="detail-name"></p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Email</p>
                <p class="font-bold text-gray-800" id="detail-email"></p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Department</p>
                <p class="font-bold text-gray-800" id="detail-department"></p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Specialization</p>
                <p class="font-bold text-gray-800" id="detail-specialization"></p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Contact Number</p>
                <p class="font-bold text-gray-800" id="detail-phone"></p>
            </div>

            <div class="divider"></div>

            <div>
                <p class="font-bold text-gray-800 mb-3">Teaching Courses</p>
                <div class="space-y-2" id="detail-courses">
                    <!-- Courses will be populated here -->
                </div>
            </div>
        </div>

        <div class="modal-action">
            <button type="button" class="btn btn-warning" id="editTeacherBtn">Edit</button>
            <button type="button" class="btn btn-error" id="deleteTeacherBtn">Delete</button>
            <form method="dialog">
                <button class="btn">Close</button>
            </form>
        </div>
    </div>
</dialog>

<script>
    let currentTeacherId = null;
    let teachersData = <?php echo json_encode($teachers); ?>;

    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('teacherForm').addEventListener('submit', saveTeacher);
        document.getElementById('searchTeachers').addEventListener('keyup', debounce(filterTeachers, 300));
        document.getElementById('departmentFilter').addEventListener('change', filterTeachers);
        document.getElementById('editTeacherBtn').addEventListener('click', function() {
            if (currentTeacherId) {
                document.getElementById('teacherDetailModal').close();
                openEditTeacherModal(currentTeacherId);
            }
        });
        document.getElementById('deleteTeacherBtn').addEventListener('click', function() {
            if (currentTeacherId) {
                deleteTeacher(currentTeacherId);
            }
        });
    });

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

    function openAddTeacherModal() {
        currentTeacherId = null;
        document.getElementById('teacherModalTitle').textContent = 'Add New Teacher';
        document.getElementById('teacherForm').reset();
        document.getElementById('teacherModal').showModal();
    }

    function viewTeacher(teacherId) {
        fetch(`?page=teachers&action=get&id=${teacherId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const teacher = data.data;
                    currentTeacherId = teacher.id;

                    document.getElementById('detail-name').textContent = teacher.name;
                    document.getElementById('detail-email').textContent = teacher.email;
                    document.getElementById('detail-department').textContent = teacher.department || 'N/A';
                    document.getElementById('detail-specialization').textContent = teacher.specialization || 'N/A';
                    document.getElementById('detail-phone').textContent = teacher.phone || 'N/A';

                    // Populate courses
                    const coursesDiv = document.getElementById('detail-courses');
                    if (data.courses && data.courses.length > 0) {
                        coursesDiv.innerHTML = data.courses.map(c => 
                            `<div class="badge badge-outline mr-2 mb-2">${c.code} - ${c.name}</div>`
                        ).join('');
                    } else {
                        coursesDiv.innerHTML = '<p class="text-gray-500 text-sm">No courses assigned</p>';
                    }
                    
                    document.getElementById('teacherDetailModal').showModal();
                } else {
                    Swal.fire('Error', data.message || 'Failed to load teacher', 'error');
                }
            });
    }

    function openEditTeacherModal(teacherId) {
        fetch(`?page=teachers&action=get&id=${teacherId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const teacher = data.data;
                    currentTeacherId = teacher.id;
                    
                    document.getElementById('teacherModalTitle').textContent = 'Edit Teacher';
                    document.getElementById('teacherName').value = teacher.name;
                    document.getElementById('teacherEmail').value = teacher.email;
                    document.getElementById('teacherDepartment').value = teacher.department || '';
                    document.getElementById('teacherSpecialization').value = teacher.specialization || '';
                    document.getElementById('teacherPhone').value = teacher.phone || '';
                    
                    document.getElementById('teacherModal').showModal();
                } else {
                    Swal.fire('Error', data.message || 'Failed to load teacher', 'error');
                }
            });
    }

    function editTeacher(teacherId) {
        openEditTeacherModal(teacherId);
    }

    function saveTeacher(e) {
        e.preventDefault();
        
        const formData = new FormData();
        formData.append('action', currentTeacherId ? 'update_teacher' : 'add_teacher');
        
        if (currentTeacherId) {
            formData.append('id', currentTeacherId);
        }
        
        formData.append('name', document.getElementById('teacherName').value);
        formData.append('email', document.getElementById('teacherEmail').value);
        formData.append('department', document.getElementById('teacherDepartment').value);
        formData.append('specialization', document.getElementById('teacherSpecialization').value);
        formData.append('phone', document.getElementById('teacherPhone').value);
        
        fetch('?page=teachers', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire('Success', data.message, 'success').then(() => {
                    location.reload();
                });
            } else {
                Swal.fire('Error', data.message || 'Operation failed', 'error');
            }
        })
        .catch(error => {
            Swal.fire('Error', 'An error occurred', 'error');
        });
    }

    function deleteTeacher(teacherId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This will permanently delete this teacher!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('action', 'delete_teacher');
                formData.append('id', teacherId);
                
                fetch('?page=teachers', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Deleted!', data.message, 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error', data.message || 'Failed to delete teacher', 'error');
                    }
                });
            }
        });
    }

    function filterTeachers() {
        const search = document.getElementById('searchTeachers').value;
        const department = document.getElementById('departmentFilter').value;
        
        fetch(`?page=teachers&action=list&search=${encodeURIComponent(search)}&department=${encodeURIComponent(department)}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    teachersData = data.data;
                    renderTeachers();
                }
            });
    }

    function renderTeachers() {
        const grid = document.getElementById('teachersGrid');
        
        if (teachersData.length === 0) {
            grid.innerHTML = '<div class="col-span-full text-center text-gray-500 py-8">No teachers found</div>';
            return;
        }
        
        grid.innerHTML = teachersData.map(teacher => `
            <div class="card bg-white shadow-md hover:shadow-lg transition cursor-pointer">
                <div class="card-body">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center text-white font-bold">
                            ${teacher.name.charAt(0).toUpperCase()}
                        </div>
                        <div>
                            <h2 class="card-title text-lg text-gray-800">${teacher.name}</h2>
                            <p class="text-sm text-gray-600">${teacher.department || 'N/A'}</p>
                        </div>
                    </div>

                    <div class="space-y-2 text-sm mb-4">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <a href="mailto:${teacher.email}" class="link link-primary">${teacher.email}</a>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <span>${teacher.phone || 'N/A'}</span>
                        </div>
                    </div>

                    <div class="badge badge-outline mb-4">${teacher.specialization || 'General'}</div>

                    <div class="card-actions gap-2">
                        <button class="btn btn-sm btn-outline flex-1" onclick="viewTeacher(${teacher.id})">View</button>
                        <button class="btn btn-sm btn-warning" onclick="editTeacher(${teacher.id})">Edit</button>
                        <button class="btn btn-sm btn-error" onclick="deleteTeacher(${teacher.id})">Delete</button>
                    </div>
                </div>
            </div>
        `).join('');
    }
</script>
