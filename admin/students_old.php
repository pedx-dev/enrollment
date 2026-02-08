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
                        <tr>
                            <td colspan="7" class="text-center text-gray-500 py-8">Loading students...</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex justify-between items-center mt-6">
                <p class="text-sm text-gray-600" id="recordCount">Showing 0 records</p>
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
        <!-- Cards will be populated here -->
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
            <a class="tab" onclick="switchDetailTab('documents')">Documents</a>
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
                        <th>Year</th>
                        <th>Section</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody id="modal-enrollmentsList">
                    <tr><td colspan="4" class="text-center text-gray-500">No enrollments</td></tr>
                </tbody>
            </table>
        </div>

        <!-- Documents Tab -->
        <div id="tab-documents" class="hidden">
            <div id="modal-documentsList" class="text-center text-gray-500">
                No documents uploaded yet
            </div>
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
    let allStudents = [];
    let filteredStudents = [];
    let currentPage = 1;
    const recordsPerPage = 10;
    let currentViewMode = 'table';
    let currentSelectedStudent = null;

    document.addEventListener('DOMContentLoaded', function() {
        loadStudents();
        setupEventListeners();
    });

    function loadStudents() {
        allStudents = enrollmentDB.getAllStudents();
        filteredStudents = [...allStudents];
        renderStudents();
        updateRecordCount();
    }

    function setupEventListeners() {
        document.getElementById('searchInput').addEventListener('keyup', filterStudents);
        document.getElementById('courseFilter').addEventListener('change', filterStudents);
        document.getElementById('statusFilter').addEventListener('change', filterStudents);
        document.getElementById('tableViewBtn').addEventListener('click', switchToTableView);
        document.getElementById('cardViewBtn').addEventListener('click', switchToCardView);
        document.getElementById('prevPageBtn').addEventListener('click', prevPage);
        document.getElementById('nextPageBtn').addEventListener('click', nextPage);
        document.getElementById('exportBtn').addEventListener('click', exportStudents);
        
            // Edit and delete buttons in modal
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
    }

    function filterStudents() {
        const searchQuery = document.getElementById('searchInput').value.toLowerCase();
        const courseFilter = document.getElementById('courseFilter').value;
        const statusFilter = document.getElementById('statusFilter').value;

        filteredStudents = allStudents.filter(student => {
            const matchesSearch = !searchQuery || 
                student.id.includes(searchQuery) ||
                student.fullName.first.toLowerCase().includes(searchQuery) ||
                student.fullName.last.toLowerCase().includes(searchQuery) ||
                student.contact.email.toLowerCase().includes(searchQuery);

            const matchesCourse = !courseFilter || student.academic.course === courseFilter;
            const matchesStatus = !statusFilter || student.status === statusFilter;

            return matchesSearch && matchesCourse && matchesStatus;
        });

        currentPage = 1;
        renderStudents();
        updateRecordCount();
    }

    function renderStudents() {
        if (currentViewMode === 'table') {
            renderTableView();
        } else {
            renderCardView();
        }
    }

    function renderTableView() {
        const tbody = document.getElementById('studentsTableBody');
        tbody.innerHTML = '';

        if (filteredStudents.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" class="text-center text-gray-500 py-8">No students found</td></tr>';
            return;
        }

        const start = (currentPage - 1) * recordsPerPage;
        const end = start + recordsPerPage;
        const pageStudents = filteredStudents.slice(start, end);

        pageStudents.forEach(student => {
            const enrollmentDate = new Date(student.createdAt).toLocaleDateString();
            const row = document.createElement('tr');
            row.innerHTML = `
                <td><span class="badge badge-primary">${student.id}</span></td>
                <td><strong>${student.fullName.first} ${student.fullName.last}</strong></td>
                <td><span class="text-sm">${student.academic.course}</span></td>
                <td><a href="mailto:${student.contact.email}" class="link link-primary text-sm">${student.contact.email}</a></td>
                <td><span class="badge badge-${student.status === 'active' ? 'success' : 'warning'}">${student.status}</span></td>
                <td>${enrollmentDate}</td>
                <td>
                    <button class="btn btn-xs btn-outline" onclick="viewStudent('${student.id}')">View</button>
                    <button class="btn btn-xs btn-warning" onclick="editStudent('${student.id}')">Edit</button>
                    <button class="btn btn-xs btn-error" onclick="confirmDelete('${student.id}')">Delete</button>
                </td>
            `;
            tbody.appendChild(row);
        });

        updatePagination();
    }

    function renderCardView() {
        const cardContainer = document.getElementById('cardView');
        cardContainer.innerHTML = '';

        if (filteredStudents.length === 0) {
            cardContainer.innerHTML = '<div class="col-span-full text-center text-gray-500 py-8">No students found</div>';
            return;
        }

        filteredStudents.forEach(student => {
            const card = document.createElement('div');
            card.className = 'card bg-white shadow-md hover:shadow-lg transition';
            card.innerHTML = `
                <div class="card-body">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-bold text-gray-800">${student.fullName.first} ${student.fullName.last}</h3>
                        <span class="badge badge-${student.status === 'active' ? 'success' : 'warning'}">${student.status}</span>
                    </div>
                    <p class="text-sm text-gray-600 mb-2">ID: <strong>${student.id}</strong></p>
                    <p class="text-sm text-gray-600 mb-2">${student.academic.course}</p>
                    <p class="text-sm text-gray-600 mb-4">${student.contact.email}</p>
                    <div class="card-actions gap-2">
                        <button class="btn btn-xs btn-primary flex-1" onclick="viewStudent('${student.id}')">View</button>
                        <button class="btn btn-xs btn-warning flex-1" onclick="editStudent('${student.id}')">Edit</button>
                    </div>
                </div>
            `;
            cardContainer.appendChild(card);
        });
    }

    function viewStudent(studentId) {
        const student = enrollmentDB.getStudentById(studentId);
        if (!student) return;

        currentSelectedStudent = student;

        // Populate modal
        document.getElementById('modal-studentId').textContent = student.id;
        document.getElementById('modal-fullName').textContent = `${student.fullName.first} ${student.fullName.middle} ${student.fullName.last}`;
        document.getElementById('modal-dob').textContent = student.personalInfo.dob;
        document.getElementById('modal-nationality').textContent = student.personalInfo.nationality;
        document.getElementById('modal-address').textContent = student.contact.address;
        document.getElementById('modal-phone').textContent = student.contact.phone;
        document.getElementById('modal-email').textContent = student.contact.email;
        document.getElementById('modal-guardianName').textContent = student.guardian.name;
        document.getElementById('modal-guardianRelation').textContent = student.guardian.relationship;
        document.getElementById('modal-guardianPhone').textContent = student.guardian.phone;

        document.getElementById('studentDetailModal').showModal();
    }

    function editStudent(studentId) {
        const student = enrollmentDB.getStudentById(studentId);
        if (!student) return;

        currentSelectedStudent = student;

        // Populate edit form
        document.getElementById('edit-firstName').value = student.fullName.first;
        document.getElementById('edit-middleName').value = student.fullName.middle;
        document.getElementById('edit-lastName').value = student.fullName.last;
        document.getElementById('edit-email').value = student.contact.email;
        document.getElementById('edit-phone').value = student.contact.phone;
        document.getElementById('edit-address').value = student.contact.address;

        document.getElementById('studentDetailModal').close();
        document.getElementById('editStudentModal').showModal();
    }

    function confirmDelete(studentId) {
        if (confirm('Are you sure you want to delete this student record?')) {
            enrollmentDB.deleteStudent(studentId);
            loadStudents();
            document.getElementById('studentDetailModal').close();
        }
    }

    function switchToTableView() {
        currentViewMode = 'table';
        document.getElementById('tableView').classList.remove('hidden');
        document.getElementById('cardView').classList.add('hidden');
        document.getElementById('tableViewBtn').classList.add('btn-active');
        document.getElementById('cardViewBtn').classList.remove('btn-active');
        renderTableView();
    }

    function switchToCardView() {
        currentViewMode = 'card';
        document.getElementById('tableView').classList.add('hidden');
        document.getElementById('cardView').classList.remove('hidden');
        document.getElementById('cardViewBtn').classList.add('btn-active');
        document.getElementById('tableViewBtn').classList.remove('btn-active');
        renderCardView();
    }

    function updateRecordCount() {
        const count = filteredStudents.length;
        const start = (currentPage - 1) * recordsPerPage + 1;
        const end = Math.min(currentPage * recordsPerPage, count);
        document.getElementById('recordCount').textContent = `Showing ${count === 0 ? 0 : start}-${end} of ${count} records`;
    }

    function updatePagination() {
        const totalPages = Math.ceil(filteredStudents.length / recordsPerPage);
        document.getElementById('pageInfo').textContent = `Page ${currentPage} of ${totalPages}`;
        document.getElementById('prevPageBtn').disabled = currentPage === 1;
        document.getElementById('nextPageBtn').disabled = currentPage === totalPages;
    }

    function prevPage() {
        if (currentPage > 1) {
            currentPage--;
            renderTableView();
            updateRecordCount();
        }
    }

    function nextPage() {
        const totalPages = Math.ceil(filteredStudents.length / recordsPerPage);
        if (currentPage < totalPages) {
            currentPage++;
            renderTableView();
            updateRecordCount();
        }
    }

    function switchDetailTab(tab) {
        document.querySelectorAll('#studentDetailModal .tabs a').forEach(el => el.classList.remove('tab-active'));
        document.querySelectorAll('#studentDetailModal [id^="tab-"]').forEach(el => el.classList.add('hidden'));
        
        event.target.classList.add('tab-active');
        document.getElementById(`tab-${tab}`).classList.remove('hidden');
    }

    function exportStudents() {
        const csv = 'Student ID,Name,Course,Email,Status,Enrollment Date\n' + 
            filteredStudents.map(s => 
                `${s.id},"${s.fullName.first} ${s.fullName.last}",${s.academic.course},${s.contact.email},${s.status},${new Date(s.createdAt).toLocaleDateString()}`
            ).join('\n');

        const blob = new Blob([csv], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'students.csv';
        a.click();
    }

    // Edit form submission
    document.getElementById('editStudentForm').addEventListener('submit', function(e) {
        e.preventDefault();

        if (currentSelectedStudent) {
            const updates = {
                fullName: {
                    first: document.getElementById('edit-firstName').value,
                    middle: document.getElementById('edit-middleName').value,
                    last: document.getElementById('edit-lastName').value
                },
                contact: {
                    ...currentSelectedStudent.contact,
                    email: document.getElementById('edit-email').value,
                    phone: document.getElementById('edit-phone').value,
                    address: document.getElementById('edit-address').value
                }
            };

            enrollmentDB.updateStudent(currentSelectedStudent.id, updates);
            loadStudents();
            document.getElementById('editStudentModal').close();
            alert('Student information updated successfully!');
        }
    });
</script>
