<?php
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
                <button class="btn btn-primary" id="addTeacherBtn" onclick="document.getElementById('teacherModal').showModal()">
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
    const teachersData = <?php echo json_encode($teachers); ?>;

    function viewTeacher(teacherId) {
        const teacher = teachersData.find(t => t.id == teacherId);
        if (!teacher) return;

        document.getElementById('detail-name').textContent = teacher.name;
        document.getElementById('detail-email').textContent = teacher.email;
        document.getElementById('detail-department').textContent = teacher.department || 'N/A';
        document.getElementById('detail-specialization').textContent = teacher.specialization || 'N/A';
        document.getElementById('detail-phone').textContent = teacher.phone || 'N/A';

        document.getElementById('detail-courses').innerHTML = '<p class="text-gray-500 text-sm">Loading courses...</p>';
        
        document.getElementById('teacherDetailModal').showModal();
    }

    function editTeacher(teacherId) {
        Swal.fire('Info', 'Edit functionality coming soon', 'info');
    }

    // Search and filter functionality
    document.getElementById('searchTeachers').addEventListener('keyup', filterTeachers);
    document.getElementById('departmentFilter').addEventListener('change', filterTeachers);

    function filterTeachers() {
        const searchQuery = document.getElementById('searchTeachers').value.toLowerCase();
        const departmentFilter = document.getElementById('departmentFilter').value;

        const cards = document.querySelectorAll('#teachersGrid > div');
        cards.forEach(card => {
            const name = card.querySelector('h2')?.textContent.toLowerCase() || '';
            const email = card.querySelector('a[href^="mailto:"]')?.textContent.toLowerCase() || '';
            const department = card.querySelector('.text-gray-600')?.textContent || '';

            const matchesSearch = !searchQuery || name.includes(searchQuery) || email.includes(searchQuery);
            const matchesDepartment = !departmentFilter || department === departmentFilter;

            card.style.display = (matchesSearch && matchesDepartment) ? '' : 'none';
        });
    }
</script>
