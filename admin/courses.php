<?php
// API endpoint for AJAX requests
if (isset($_GET['action']) || (isset($_POST['action']) && $_SERVER['REQUEST_METHOD'] === 'POST')) {
    header('Content-Type: application/json');
    $action = $_GET['action'] ?? $_POST['action'];
    
    switch ($action) {
        case 'list':
            $courses = getAllCourses();
            echo json_encode(['success' => true, 'data' => $courses]);
            exit;
            
        case 'get':
            $course = getCourseById($_GET['id']);
            if ($course) {
                echo json_encode(['success' => true, 'data' => $course]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Course not found']);
            }
            exit;
            
        case 'add_course':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = [
                    'code' => $_POST['code'],
                    'name' => $_POST['name'],
                    'department' => $_POST['department'],
                    'credits' => $_POST['credits'],
                    'description' => $_POST['description'] ?? '',
                    'instructor_id' => !empty($_POST['instructor_id']) ? $_POST['instructor_id'] : null
                ];
                $result = addCourse($data);
                echo json_encode(['success' => $result, 'message' => $result ? 'Course added successfully' : 'Failed to add course']);
            }
            exit;
            
        case 'update_course':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id = $_POST['id'];
                $data = [
                    'code' => $_POST['code'],
                    'name' => $_POST['name'],
                    'department' => $_POST['department'],
                    'credits' => $_POST['credits'],
                    'description' => $_POST['description'] ?? '',
                    'instructor_id' => !empty($_POST['instructor_id']) ? $_POST['instructor_id'] : null,
                    'status' => $_POST['status'] ?? 'active'
                ];
                $result = updateCourse($id, $data);
                echo json_encode(['success' => $result, 'message' => $result ? 'Course updated successfully' : 'Failed to update course']);
            }
            exit;
            
        case 'delete_course':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $result = deleteCourse($_POST['id']);
                echo json_encode(['success' => $result, 'message' => $result ? 'Course deleted successfully' : 'Failed to delete course']);
            }
            exit;
    }
}

// Get courses from database
$courses = getAllCourses();
$teachers = getAllTeachers();
?>

<!-- Courses Management Page -->
<div class="space-y-6">
    <!-- Header -->
    <div class="card bg-white shadow-md">
        <div class="card-body">
            <div class="flex flex-col lg:flex-row gap-4 items-center justify-between">
                <div>
                    <h2 class="card-title text-2xl font-bold text-gray-800">Course Management</h2>
                    <p class="text-gray-600 text-sm mt-2">View and manage available courses</p>
                </div>
                <button class="btn btn-primary" id="addCourseBtn" onclick="openAddCourseModal()">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Course
                </button>
            </div>
        </div>
    </div>

    <!-- Courses Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="coursesGrid">
        <?php if (empty($courses)): ?>
        <div class="col-span-full text-center text-gray-500 py-8">No courses found</div>
        <?php else: ?>
        <?php foreach ($courses as $course): ?>
        <div class="card bg-white shadow-md hover:shadow-lg transition">
            <div class="card-body">
                <div class="badge badge-primary mb-2"><?php echo htmlspecialchars($course['code']); ?></div>
                <h2 class="card-title text-lg text-gray-800 mb-2"><?php echo htmlspecialchars($course['name']); ?></h2>
                <p class="text-sm text-gray-600 mb-3"><?php echo htmlspecialchars($course['description'] ?? 'No description available'); ?></p>
                
                <div class="space-y-2 text-sm mb-4">
                    <div><span class="text-gray-600">Department:</span> <strong><?php echo htmlspecialchars($course['department']); ?></strong></div>
                    <div><span class="text-gray-600">Credits:</span> <strong><?php echo htmlspecialchars($course['credits']); ?></strong></div>
                    <div><span class="text-gray-600">Instructor:</span> <strong><?php echo htmlspecialchars($course['instructor_name'] ?? 'TBA'); ?></strong></div>
                </div>

                <div class="card-actions gap-2">
                    <button class="btn btn-sm btn-outline flex-1" onclick="editCourse('<?php echo $course['id']; ?>')">Edit</button>
                    <button class="btn btn-sm btn-error" onclick="deleteCourse('<?php echo $course['id']; ?>')">Delete</button>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Add/Edit Course Modal -->
<dialog id="courseModal" class="modal">
    <div class="modal-box">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        
        <h3 class="font-bold text-lg mb-4" id="courseModalTitle">Add New Course</h3>

        <form id="courseForm" class="space-y-4">
            <input type="hidden" name="action" value="add_course" />
            
            <div class="form-control">
                <label class="label">
                    <span class="label-text">Course Code</span>
                </label>
                <input type="text" name="code" id="courseCode" placeholder="e.g., CS101" class="input input-bordered" required />
            </div>

            <div class="form-control">
                <label class="label">
                    <span class="label-text">Course Name</span>
                </label>
                <input type="text" name="name" id="courseName" placeholder="Course name" class="input input-bordered" required />
            </div>

            <div class="form-control">
                <label class="label">
                    <span class="label-text">Department</span>
                </label>
                <select name="department" id="courseDepartment" class="select select-bordered" required>
                    <option value="">Select department</option>
                    <option value="Computer Science">Computer Science</option>
                    <option value="Information Technology">Information Technology</option>
                    <option value="Business Administration">Business Administration</option>
                </select>
            </div>

            <div class="form-control">
                <label class="label">
                    <span class="label-text">Credits</span>
                </label>
                <input type="number" name="credits" id="courseCredits" placeholder="3" min="1" value="3" class="input input-bordered" required />
            </div>

            <div class="form-control">
                <label class="label">
                    <span class="label-text">Description</span>
                </label>
                <textarea name="description" id="courseDescription" placeholder="Course description" class="textarea textarea-bordered" rows="3"></textarea>
            </div>

            <div class="form-control">
                <label class="label">
                    <span class="label-text">Instructor</span>
                </label>
                <select name="instructor_id" id="courseInstructor" class="select select-bordered">
                    <option value="">Select instructor</option>
                    <?php foreach ($teachers as $teacher): ?>
                    <option value="<?php echo $teacher['id']; ?>"><?php echo htmlspecialchars($teacher['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="modal-action">
                <button type="submit" class="btn btn-primary">Save Course</button>
                <button type="button" class="btn btn-outline" onclick="document.getElementById('courseModal').close()">Cancel</button>
            </div>
        </form>
    </div>
</dialog>

<script>
    let currentCourseId = null;
    let allCourses = <?php echo json_encode($courses); ?>;

    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('courseForm').addEventListener('submit', saveCourse);
    });

    function openAddCourseModal() {
        currentCourseId = null;
        document.getElementById('courseModalTitle').textContent = 'Add New Course';
        document.getElementById('courseForm').reset();
        document.querySelector('#courseForm input[name="action"]').value = 'add_course';
        document.getElementById('courseModal').showModal();
    }

    function editCourse(courseId) {
        fetch(`?page=courses&action=get&id=${courseId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const course = data.data;
                    currentCourseId = course.id;
                    
                    document.getElementById('courseModalTitle').textContent = 'Edit Course';
                    document.getElementById('courseCode').value = course.code;
                    document.getElementById('courseName').value = course.name;
                    document.getElementById('courseDepartment').value = course.department;
                    document.getElementById('courseCredits').value = course.credits;
                    document.getElementById('courseDescription').value = course.description || '';
                    document.getElementById('courseInstructor').value = course.instructor_id || '';
                    document.querySelector('#courseForm input[name="action"]').value = 'update_course';
                    
                    document.getElementById('courseModal').showModal();
                } else {
                    Swal.fire('Error', data.message || 'Failed to load course', 'error');
                }
            })
            .catch(error => {
                Swal.fire('Error', 'Failed to load course data', 'error');
            });
    }

    function saveCourse(e) {
        e.preventDefault();
        
        const formData = new FormData();
        const action = document.querySelector('#courseForm input[name="action"]').value;
        formData.append('action', action);
        
        if (currentCourseId) {
            formData.append('id', currentCourseId);
        }
        
        formData.append('code', document.getElementById('courseCode').value);
        formData.append('name', document.getElementById('courseName').value);
        formData.append('department', document.getElementById('courseDepartment').value);
        formData.append('credits', document.getElementById('courseCredits').value);
        formData.append('description', document.getElementById('courseDescription').value);
        formData.append('instructor_id', document.getElementById('courseInstructor').value);
        
        fetch('?page=courses', {
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

    function deleteCourse(courseId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This will permanently delete this course!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('action', 'delete_course');
                formData.append('id', courseId);
                
                fetch('?page=courses', {
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
                        Swal.fire('Error', data.message || 'Failed to delete course', 'error');
                    }
                })
                .catch(error => {
                    Swal.fire('Error', 'An error occurred', 'error');
                });
            }
        });
    }
</script>
