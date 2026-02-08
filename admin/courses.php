<?php
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
                <button class="btn btn-primary" id="addCourseBtn" onclick="document.getElementById('courseModal').showModal()">
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

        <form id="courseForm" class="space-y-4" method="POST" action="?page=courses">
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
    function editCourse(courseId) {
        Swal.fire('Info', 'Edit functionality coming soon', 'info');
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
                // For now, show success message
                Swal.fire('Info', 'Delete functionality coming soon', 'info');
            }
        });
    }
</script>
