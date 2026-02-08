<?php
// Get pending teachers and all teachers for display
$pendingCount = getPendingTeachersCount();
$pendingTeachers = getPendingTeachers();
$allTeacherUsers = getAllTeachers();
?>

<!-- Teacher Approvals Page -->
<div class="space-y-6">
    <!-- Header -->
    <div class="card bg-white shadow-md">
        <div class="card-body">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="card-title text-2xl font-bold text-gray-800">Teacher Approvals</h2>
                    <p class="text-gray-600">Review and approve teacher registrations</p>
                </div>
                <?php if ($pendingCount > 0): ?>
                <div class="badge badge-warning badge-lg gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-4 h-4 stroke-current"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <?php echo $pendingCount; ?> Pending
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Pending Registrations -->
    <div class="card bg-white shadow-md">
        <div class="card-body">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Pending Registrations
            </h3>

            <?php if (empty($pendingTeachers)): ?>
            <div class="text-center py-8 text-gray-500">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p>No pending registrations</p>
            </div>
            <?php else: ?>
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="text-gray-700">Name</th>
                            <th class="text-gray-700">Email</th>
                            <th class="text-gray-700">Department</th>
                            <th class="text-gray-700">Specialization</th>
                            <th class="text-gray-700">Phone</th>
                            <th class="text-gray-700">Registered</th>
                            <th class="text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pendingTeachers as $teacher): ?>
                        <tr id="pending-row-<?php echo $teacher['id']; ?>">
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="avatar placeholder">
                                        <div class="bg-warning text-warning-content rounded-full w-10">
                                            <span class="text-sm"><?php echo strtoupper(substr($teacher['name'], 0, 2)); ?></span>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-bold"><?php echo htmlspecialchars($teacher['name']); ?></div>
                                        <span class="badge badge-warning badge-sm">Pending</span>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($teacher['email']); ?></td>
                            <td><?php echo htmlspecialchars($teacher['department'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($teacher['specialization'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($teacher['phone'] ?? '-'); ?></td>
                            <td class="text-sm text-gray-500"><?php echo date('M d, Y', strtotime($teacher['created_at'])); ?></td>
                            <td>
                                <div class="flex gap-2">
                                    <button onclick="approveTeacher(<?php echo $teacher['id']; ?>, '<?php echo htmlspecialchars($teacher['name']); ?>')" 
                                            class="btn btn-success btn-xs gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Approve
                                    </button>
                                    <button onclick="rejectTeacher(<?php echo $teacher['id']; ?>, '<?php echo htmlspecialchars($teacher['name']); ?>')" 
                                            class="btn btn-error btn-xs gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Reject
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- All Teachers List -->
    <div class="card bg-white shadow-md">
        <div class="card-body">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                All Teachers (<?php echo count($allTeacherUsers); ?>)
            </h3>

            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="text-gray-700">Name</th>
                            <th class="text-gray-700">Email</th>
                            <th class="text-gray-700">Department</th>
                            <th class="text-gray-700">Status</th>
                            <th class="text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($allTeacherUsers as $teacher): ?>
                        <tr id="teacher-row-<?php echo $teacher['id']; ?>">
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="avatar placeholder">
                                        <div class="bg-primary text-primary-content rounded-full w-10">
                                            <span class="text-sm"><?php echo strtoupper(substr($teacher['name'], 0, 2)); ?></span>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-bold"><?php echo htmlspecialchars($teacher['name']); ?></div>
                                        <div class="text-sm text-gray-500"><?php echo htmlspecialchars($teacher['specialization'] ?? ''); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($teacher['email']); ?></td>
                            <td><?php echo htmlspecialchars($teacher['department'] ?? '-'); ?></td>
                            <td>
                                <?php 
                                $statusClass = match($teacher['status']) {
                                    'active' => 'badge-success',
                                    'inactive' => 'badge-ghost',
                                    'pending' => 'badge-warning',
                                    'rejected' => 'badge-error',
                                    default => 'badge-ghost'
                                };
                                ?>
                                <span class="badge <?php echo $statusClass; ?>"><?php echo ucfirst($teacher['status']); ?></span>
                            </td>
                            <td>
                                <?php if ($teacher['status'] === 'active'): ?>
                                <button onclick="toggleTeacherStatus(<?php echo $teacher['id']; ?>, 'inactive')" 
                                        class="btn btn-ghost btn-xs text-warning">Deactivate</button>
                                <?php elseif ($teacher['status'] === 'inactive'): ?>
                                <button onclick="toggleTeacherStatus(<?php echo $teacher['id']; ?>, 'active')" 
                                        class="btn btn-ghost btn-xs text-success">Activate</button>
                                <?php elseif ($teacher['status'] === 'rejected'): ?>
                                <button onclick="deleteRegistration(<?php echo $teacher['id']; ?>)" 
                                        class="btn btn-ghost btn-xs text-error">Delete</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function approveTeacher(id, name) {
    Swal.fire({
        title: 'Approve Teacher?',
        html: `Are you sure you want to approve <strong>${name}</strong>?<br><small>They will be able to login to the system.</small>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#22c55e',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, Approve'
    }).then((result) => {
        if (result.isConfirmed) {
            performAction('approve', id);
        }
    });
}

function rejectTeacher(id, name) {
    Swal.fire({
        title: 'Reject Registration?',
        html: `Are you sure you want to reject <strong>${name}</strong>'s registration?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, Reject'
    }).then((result) => {
        if (result.isConfirmed) {
            performAction('reject', id);
        }
    });
}

function deleteRegistration(id) {
    Swal.fire({
        title: 'Delete Registration?',
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, Delete'
    }).then((result) => {
        if (result.isConfirmed) {
            performAction('delete', id);
        }
    });
}

function toggleTeacherStatus(id, newStatus) {
    const action = newStatus === 'active' ? 'approve' : 'reject';
    performAction(action, id);
}

function performAction(action, teacherId) {
    const formData = new FormData();
    formData.append('action', action);
    formData.append('teacher_id', teacherId);

    fetch('?page=approvals', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: data.message,
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred. Please try again.'
        });
    });
}
</script>
