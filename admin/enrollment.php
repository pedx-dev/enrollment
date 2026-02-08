<?php
// Get settings for student ID generation
$settings = getSettings();
$collegeCode = $settings['college_code'];
$nextStudentNumber = $settings['next_student_number'];
$nextStudentId = $collegeCode . '-' . str_pad($nextStudentNumber, 5, '0', STR_PAD_LEFT);

// Get courses for dropdown
$courses = getAllCourses();

// Handle AJAX enrollment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'enroll_student') {
    header('Content-Type: application/json');
    
    try {
        $studentData = [
            'first_name' => $_POST['firstName'] ?? '',
            'middle_name' => $_POST['middleName'] ?? '',
            'last_name' => $_POST['lastName'] ?? '',
            'date_of_birth' => $_POST['dateOfBirth'] ?? '',
            'place_of_birth' => $_POST['placeOfBirth'] ?? '',
            'sex' => $_POST['sex'] ?? '',
            'civil_status' => $_POST['status'] ?? '',
            'nationality' => $_POST['nationality'] ?? '',
            'religion' => $_POST['religion'] ?? '',
            'address' => $_POST['address'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'email' => $_POST['email'] ?? '',
            'guardian_name' => $_POST['guardianName'] ?? '',
            'guardian_phone' => $_POST['guardianPhone'] ?? '',
            'guardian_relationship' => $_POST['guardianRelationship'] ?? '',
            'course' => $_POST['course'] ?? '',
            'year_level' => $_POST['year'] ?? '',
            'section' => $_POST['section'] ?? '',
            'enrollment_date' => $_POST['enrollmentDate'] ?? date('Y-m-d'),
            'status' => 'Enrolled'
        ];
        
        $newStudent = addStudent($studentData);
        
        if ($newStudent) {
            echo json_encode([
                'success' => true,
                'student' => $newStudent,
                'message' => 'Student enrolled successfully'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to enroll student'
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
    exit;
}
?>
<!-- Student Enrollment Form -->
<style>
    .input-error {
        border-color: #ef4444 !important;
        background-color: #fef2f2 !important;
    }
    
    .form-step {
        animation: fadeIn 0.3s ease-in;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<div class="max-w-4xl mx-auto">
    <div class="card bg-white shadow-md mb-6">
        <div class="card-body">
            <h2 class="card-title text-2xl font-bold text-gray-800 mb-2">New Student Enrollment</h2>
            <p class="text-gray-600">Complete all sections to enroll a new student</p>
        </div>
    </div>

    <!-- Progress Indicator -->
    <div class="card bg-white shadow-md mb-6">
        <div class="card-body">
            <div class="flex justify-between items-center">
                <div class="flex flex-col items-center flex-1">
                    <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold step-1" id="step-1">1</div>
                    <p class="text-xs text-gray-600 mt-2">Personal</p>
                </div>
                <div class="flex-1 h-1 bg-gray-300 mx-2" id="progress-1"></div>
                <div class="flex flex-col items-center flex-1">
                    <div class="w-10 h-10 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center font-bold" id="step-2">2</div>
                    <p class="text-xs text-gray-600 mt-2">Contact</p>
                </div>
                <div class="flex-1 h-1 bg-gray-300 mx-2" id="progress-2"></div>
                <div class="flex flex-col items-center flex-1">
                    <div class="w-10 h-10 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center font-bold" id="step-3">3</div>
                    <p class="text-xs text-gray-600 mt-2">Guardian</p>
                </div>
                <div class="flex-1 h-1 bg-gray-300 mx-2" id="progress-3"></div>
                <div class="flex flex-col items-center flex-1">
                    <div class="w-10 h-10 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center font-bold" id="step-4">4</div>
                    <p class="text-xs text-gray-600 mt-2">Academic</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Enrollment Form -->
    <form id="enrollmentForm" class="space-y-6">
        <!-- Step 1: Personal Information -->
        <div class="form-step card bg-white shadow-md" data-step="1">
            <div class="card-body">
                <h3 class="card-title text-lg font-bold mb-6 text-gray-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Personal Information
                </h3>

                <!-- Generated Student ID -->
                <div class="alert alert-info mb-6">
                    <svg class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div>
                        <h3 class="font-bold">Generated Student ID</h3>
                        <div class="text-2xl font-bold text-primary mt-2" id="studentIdDisplay"><?php echo htmlspecialchars($nextStudentId); ?></div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <!-- First Name -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">First Name</span>
                        </label>
                        <input type="text" name="firstName" placeholder="First name" class="input input-bordered" required />
                    </div>

                    <!-- Middle Name -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Middle Name</span>
                        </label>
                        <input type="text" name="middleName" placeholder="Middle name" class="input input-bordered" />
                    </div>

                    <!-- Last Name -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Last Name</span>
                        </label>
                        <input type="text" name="lastName" placeholder="Last name" class="input input-bordered" required />
                    </div>
                </div>

                <!-- Date of Birth -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Date of Birth</span>
                        </label>
                        <input type="date" name="dateOfBirth" class="input input-bordered" required />
                    </div>
                </div>

                <!-- Place of Birth - Cascading Dropdowns -->
                <div class="mb-6">
                    <label class="label">
                        <span class="label-text font-semibold">Place of Birth</span>
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Region -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text text-sm">Region</span>
                            </label>
                            <select name="pobRegion" id="pobRegion" class="select select-bordered select-sm" required onchange="loadProvinces()">
                                <option value="">Select Region</option>
                            </select>
                        </div>

                        <!-- Province -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text text-sm">Province</span>
                            </label>
                            <select name="pobProvince" id="pobProvince" class="select select-bordered select-sm" required onchange="loadCities()" disabled>
                                <option value="">Select Province</option>
                            </select>
                        </div>

                        <!-- City/Municipality -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text text-sm">City/Municipality</span>
                            </label>
                            <select name="pobCity" id="pobCity" class="select select-bordered select-sm" required onchange="loadBarangays()" disabled>
                                <option value="">Select City/Municipality</option>
                            </select>
                        </div>

                        <!-- Barangay -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text text-sm">Barangay</span>
                            </label>
                            <select name="pobBarangay" id="pobBarangay" class="select select-bordered select-sm" required disabled>
                                <option value="">Select Barangay</option>
                            </select>
                        </div>
                    </div>
                    <!-- Hidden field to store combined place of birth -->
                    <input type="hidden" name="placeOfBirth" id="placeOfBirth" />
                </div>

                <!-- Sex and Status -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Sex</span>
                        </label>
                        <select name="sex" class="select select-bordered" required>
                            <option value="">Select sex</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Civil Status</span>
                        </label>
                        <select name="status" class="select select-bordered" required>
                            <option value="">Select status</option>
                            <option value="Single">Single</option>
                            <option value="Married">Married</option>
                            <option value="Divorced">Divorced</option>
                            <option value="Widowed">Widowed</option>
                        </select>
                    </div>
                </div>

                <!-- Nationality and Religion -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Nationality</span>
                        </label>
                        <input type="text" name="nationality" placeholder="e.g., Filipino" class="input input-bordered" required />
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Religion</span>
                        </label>
                        <input type="text" name="religion" placeholder="e.g., Roman Catholic" class="input input-bordered" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 2: Contact Information -->
        <div class="form-step hidden card bg-white shadow-md" data-step="2">
            <div class="card-body">
                <h3 class="card-title text-lg font-bold mb-6 text-gray-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Contact Information
                </h3>

                <!-- Complete Address - Cascading Dropdowns -->
                <div class="mb-6">
                    <label class="label">
                        <span class="label-text font-semibold">Complete Address</span>
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                        <!-- Region -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text text-sm">Region</span>
                            </label>
                            <select name="addrRegion" id="addrRegion" class="select select-bordered select-sm" required onchange="loadAddrProvinces()">
                                <option value="">Select Region</option>
                            </select>
                        </div>

                        <!-- Province -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text text-sm">Province</span>
                            </label>
                            <select name="addrProvince" id="addrProvince" class="select select-bordered select-sm" required onchange="loadAddrCities()" disabled>
                                <option value="">Select Province</option>
                            </select>
                        </div>

                        <!-- City/Municipality -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text text-sm">City/Municipality</span>
                            </label>
                            <select name="addrCity" id="addrCity" class="select select-bordered select-sm" required onchange="loadAddrBarangays()" disabled>
                                <option value="">Select City/Municipality</option>
                            </select>
                        </div>

                        <!-- Barangay -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text text-sm">Barangay</span>
                            </label>
                            <select name="addrBarangay" id="addrBarangay" class="select select-bordered select-sm" required onchange="updateAddress()" disabled>
                                <option value="">Select Barangay</option>
                            </select>
                        </div>
                    </div>

                    <!-- House/Street Number and Purok -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text text-sm">House/Street No.</span>
                            </label>
                            <input type="text" name="addrStreet" id="addrStreet" placeholder="e.g., 123 Rizal Street" class="input input-bordered input-sm" onchange="updateAddress()" />
                        </div>
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text text-sm">Purok/Sitio/Subdivision</span>
                            </label>
                            <input type="text" name="addrPurok" id="addrPurok" placeholder="e.g., Purok 5 or Villa Rosa Subd." class="input input-bordered input-sm" onchange="updateAddress()" />
                        </div>
                    </div>

                    <!-- Hidden field to store combined address -->
                    <input type="hidden" name="address" id="completeAddress" />
                </div>

                <!-- Contact Number -->
                <div class="form-control mb-6">
                    <label class="label">
                        <span class="label-text font-semibold">Contact Number</span>
                    </label>
                    <input type="tel" name="phone" placeholder="+63-9175551234" class="input input-bordered" required />
                    <label class="label">
                        <span class="label-text-alt text-gray-500">Format: +63-9XXXXXXXXX</span>
                    </label>
                </div>

                <!-- Email Address -->
                <div class="form-control mb-6">
                    <label class="label">
                        <span class="label-text font-semibold">Email Address</span>
                    </label>
                    <input type="email" name="email" placeholder="student@email.com" class="input input-bordered" required />
                </div>
            </div>
        </div>

        <!-- Step 3: Guardian Information -->
        <div class="form-step hidden card bg-white shadow-md" data-step="3">
            <div class="card-body">
                <h3 class="card-title text-lg font-bold mb-6 text-gray-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 8.646 4 4 0 010-8.646M12 20H7m5 0h5m0 0a8.001 8.001 0 01-8-8v-6m8 8v6m0 0h3m-3 0h-3"></path>
                    </svg>
                    Guardian Information
                </h3>

                <!-- Guardian Full Name -->
                <div class="form-control mb-6">
                    <label class="label">
                        <span class="label-text font-semibold">Guardian Full Name</span>
                    </label>
                    <input type="text" name="guardianName" placeholder="Full name" class="input input-bordered" required />
                </div>

                <!-- Guardian Contact Number -->
                <div class="form-control mb-6">
                    <label class="label">
                        <span class="label-text font-semibold">Guardian Contact Number</span>
                    </label>
                    <input type="tel" name="guardianPhone" placeholder="+63-9175551234" class="input input-bordered" required />
                </div>

                <!-- Relationship -->
                <div class="form-control mb-6">
                    <label class="label">
                        <span class="label-text font-semibold">Relationship</span>
                    </label>
                    <select name="guardianRelationship" class="select select-bordered" required>
                        <option value="">Select relationship</option>
                        <option value="Father">Father</option>
                        <option value="Mother">Mother</option>
                        <option value="Guardian">Guardian</option>
                        <option value="Sibling">Sibling</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Step 4: Academic Information -->
        <div class="form-step hidden card bg-white shadow-md" data-step="4">
            <div class="card-body">
                <h3 class="card-title text-lg font-bold mb-6 text-gray-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.5 6.253 3 9.756 3 14s3.5 7.747 9 7.747m0-13c5.5 0 9 3.503 9 7.747"></path>
                    </svg>
                    Academic Information
                </h3>

                <!-- Course/Program Selection -->
                <div class="form-control mb-6">
                    <label class="label">
                        <span class="label-text font-semibold">Course/Program</span>
                    </label>
                    <select name="course" id="programSelect" class="select select-bordered" required onchange="loadSubjects()">
                        <option value="">Select course/program</option>
                        <option value="Bachelor of Science in Computer Science">Bachelor of Science in Computer Science (BSCS)</option>
                        <option value="Bachelor of Science in Information Technology">Bachelor of Science in Information Technology (BSIT)</option>
                        <option value="Bachelor of Arts in Business Administration">Bachelor of Arts in Business Administration (BSBA)</option>
                    </select>
                </div>

                <!-- Year Level -->
                <div class="form-control mb-6">
                    <label class="label">
                        <span class="label-text font-semibold">Year Level</span>
                    </label>
                    <select name="year" id="yearSelect" class="select select-bordered" required onchange="loadSubjects()">
                        <option value="">Select year</option>
                        <option value="First Year">First Year</option>
                        <option value="Second Year">Second Year</option>
                        <option value="Third Year">Third Year</option>
                        <option value="Fourth Year">Fourth Year</option>
                    </select>
                </div>

                <!-- Subjects Selection -->
                <div class="form-control mb-6">
                    <label class="label">
                        <span class="label-text font-semibold">Subjects to Enroll</span>
                    </label>
                    <div id="subjectsContainer" class="border rounded-lg p-4 bg-gray-50 min-h-[100px]">
                        <p class="text-gray-500 text-sm text-center py-4">Please select a course/program and year level first</p>
                    </div>
                    <label class="label">
                        <span class="label-text-alt text-gray-500">Select all subjects the student will enroll in</span>
                    </label>
                    <!-- Hidden field to store selected subjects -->
                    <input type="hidden" name="subjects" id="selectedSubjects" />
                </div>

                <!-- Enrollment Date -->
                <div class="form-control mb-6">
                    <label class="label">
                        <span class="label-text font-semibold">Enrollment Date</span>
                    </label>
                    <input type="date" name="enrollmentDate" id="enrollmentDate" class="input input-bordered" required />
                    <label class="label">
                        <span class="label-text-alt text-gray-500">Auto-filled with today's date</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="card bg-white shadow-md">
            <div class="card-body">
                <div class="flex justify-between items-center">
                    <button type="button" id="prevBtn" class="btn btn-outline" style="display: none;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Previous
                    </button>

                    <div class="text-sm text-gray-600" id="stepIndicator">Step 1 of 4</div>

                    <div class="space-x-3">
                        <button type="button" class="btn btn-outline" id="draftBtn">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h6a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V5z"></path>
                            </svg>
                            Save Draft
                        </button>
                        <button type="button" id="nextBtn" class="btn btn-primary">
                            Next
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Success Modal -->
<dialog id="successModal" class="modal">
    <div class="modal-box">
        <button type="button" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" onclick="document.getElementById('successModal').close()">✕</button>
        <h3 class="font-bold text-lg text-green-600 mb-4">
            <svg class="w-6 h-6 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Enrollment Successful!
        </h3>
        <p class="text-gray-700 mb-4">Student has been successfully enrolled in the system.</p>
        <div class="alert alert-info mb-6">
            <div>
                <p class="text-sm">Student ID: <strong id="confirmStudentId"></strong></p>
                <p class="text-sm">Name: <strong id="confirmStudentName"></strong></p>
            </div>
        </div>
        <div class="modal-action">
            <button type="button" class="btn btn-primary" onclick="redirectToDashboard()">
                Back to Dashboard
            </button>
            <button type="button" class="btn btn-outline" onclick="resetAndNewEnrollment()">
                Enroll Another Student
            </button>
        </div>
    </div>
</dialog>

<!-- Summary Confirmation Modal -->
<dialog id="summaryModal" class="modal">
    <div class="modal-box max-w-3xl">
        <button type="button" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" onclick="document.getElementById('summaryModal').close()">✕</button>
        <h3 class="font-bold text-xl text-primary mb-4">
            <svg class="w-6 h-6 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Student Information Summary
        </h3>
        <p class="text-gray-600 mb-4">Please review the information below before confirming enrollment.</p>
        
        <div class="space-y-4">
            <!-- Student ID -->
            <div class="alert alert-info">
                <svg class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div>
                    <p class="text-sm font-semibold">Student ID</p>
                    <p class="text-lg font-bold" id="summaryStudentId"></p>
                </div>
            </div>

            <!-- Personal Information -->
            <div class="collapse collapse-open bg-base-200 rounded-lg">
                <div class="collapse-title font-semibold bg-gray-100">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Personal Information
                </div>
                <div class="collapse-content bg-white">
                    <div class="grid grid-cols-2 gap-2 text-sm pt-2">
                        <div><span class="text-gray-500">Full Name:</span></div>
                        <div class="font-medium" id="summaryFullName"></div>
                        <div><span class="text-gray-500">Date of Birth:</span></div>
                        <div class="font-medium" id="summaryDOB"></div>
                        <div><span class="text-gray-500">Place of Birth:</span></div>
                        <div class="font-medium" id="summaryPOB"></div>
                        <div><span class="text-gray-500">Sex:</span></div>
                        <div class="font-medium" id="summarySex"></div>
                        <div><span class="text-gray-500">Civil Status:</span></div>
                        <div class="font-medium" id="summaryCivilStatus"></div>
                        <div><span class="text-gray-500">Nationality:</span></div>
                        <div class="font-medium" id="summaryNationality"></div>
                        <div><span class="text-gray-500">Religion:</span></div>
                        <div class="font-medium" id="summaryReligion"></div>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="collapse collapse-open bg-base-200 rounded-lg">
                <div class="collapse-title font-semibold bg-gray-100">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Contact Information
                </div>
                <div class="collapse-content bg-white">
                    <div class="grid grid-cols-2 gap-2 text-sm pt-2">
                        <div><span class="text-gray-500">Address:</span></div>
                        <div class="font-medium" id="summaryAddress"></div>
                        <div><span class="text-gray-500">Phone:</span></div>
                        <div class="font-medium" id="summaryPhone"></div>
                        <div><span class="text-gray-500">Email:</span></div>
                        <div class="font-medium" id="summaryEmail"></div>
                    </div>
                </div>
            </div>

            <!-- Guardian Information -->
            <div class="collapse collapse-open bg-base-200 rounded-lg">
                <div class="collapse-title font-semibold bg-gray-100">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Guardian Information
                </div>
                <div class="collapse-content bg-white">
                    <div class="grid grid-cols-2 gap-2 text-sm pt-2">
                        <div><span class="text-gray-500">Guardian Name:</span></div>
                        <div class="font-medium" id="summaryGuardianName"></div>
                        <div><span class="text-gray-500">Guardian Phone:</span></div>
                        <div class="font-medium" id="summaryGuardianPhone"></div>
                        <div><span class="text-gray-500">Relationship:</span></div>
                        <div class="font-medium" id="summaryGuardianRelationship"></div>
                    </div>
                </div>
            </div>

            <!-- Academic Information -->
            <div class="collapse collapse-open bg-base-200 rounded-lg">
                <div class="collapse-title font-semibold bg-gray-100">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                    </svg>
                    Academic Information
                </div>
                <div class="collapse-content bg-white">
                    <div class="grid grid-cols-2 gap-2 text-sm pt-2">
                        <div><span class="text-gray-500">Course/Program:</span></div>
                        <div class="font-medium" id="summaryCourse"></div>
                        <div><span class="text-gray-500">Year Level:</span></div>
                        <div class="font-medium" id="summaryYear"></div>
                        <div><span class="text-gray-500">Enrollment Date:</span></div>
                        <div class="font-medium" id="summaryEnrollmentDate"></div>
                    </div>
                    <div class="mt-3 pt-3 border-t">
                        <p class="text-gray-500 text-sm mb-2">Enrolled Subjects:</p>
                        <div id="summarySubjects" class="text-sm"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="divider"></div>

        <div class="modal-action">
            <button type="button" class="btn btn-outline" onclick="document.getElementById('summaryModal').close()">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                </svg>
                Go Back & Edit
            </button>
            <button type="button" class="btn btn-primary" id="confirmEnrollBtn" onclick="confirmEnrollment()">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Confirm & Enroll Student
            </button>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<script>
    let currentStep = 1;
    const totalSteps = 4;
    let formData = {};
    
    // PHP data passed to JavaScript
    const collegeCode = '<?php echo $collegeCode; ?>';
    let nextStudentNumber = <?php echo $nextStudentNumber; ?>;

    document.addEventListener('DOMContentLoaded', function() {
        console.log('Enrollment form initializing...');
        
        // Set enrollment date to today
        const today = new Date().toISOString().split('T')[0];
        const enrollmentDateField = document.getElementById('enrollmentDate');
        if (enrollmentDateField) {
            enrollmentDateField.value = today;
        }

        // Event listeners
        const nextBtn = document.getElementById('nextBtn');
        const prevBtn = document.getElementById('prevBtn');
        const draftBtn = document.getElementById('draftBtn');
        
        if (nextBtn) {
            nextBtn.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Next button clicked, current step:', currentStep);
                nextStep();
            });
        } else {
            console.error('Next button not found!');
        }
        
        if (prevBtn) {
            prevBtn.addEventListener('click', function(e) {
                e.preventDefault();
                prevStep();
            });
        }
        
        if (draftBtn) {
            draftBtn.addEventListener('click', function(e) {
                e.preventDefault();
                saveDraft();
            });
        }

        console.log('Enrollment form initialized successfully');
    });

    function nextStep() {
        console.log('nextStep called, validating step', currentStep);
        if (validateCurrentStep()) {
            console.log('Validation passed');
            saveStepData();
            if (currentStep < totalSteps) {
                currentStep++;
                console.log('Moving to step', currentStep);
                updateSteps();
            } else {
                console.log('Final step, showing summary');
                showSummaryModal();
            }
        } else {
            console.log('Validation failed for step', currentStep);
        }
    }

    function showSummaryModal() {
        // Populate summary modal with form data
        const studentId = collegeCode + '-' + String(nextStudentNumber).padStart(5, '0');
        document.getElementById('summaryStudentId').textContent = studentId;
        
        // Personal Information
        const fullName = `${formData.firstName || ''} ${formData.middleName || ''} ${formData.lastName || ''}`.replace(/\s+/g, ' ').trim();
        document.getElementById('summaryFullName').textContent = fullName;
        document.getElementById('summaryDOB').textContent = formatDate(formData.dateOfBirth);
        document.getElementById('summaryPOB').textContent = formData.placeOfBirth || 'N/A';
        document.getElementById('summarySex').textContent = formData.sex || 'N/A';
        document.getElementById('summaryCivilStatus').textContent = formData.status || 'N/A';
        document.getElementById('summaryNationality').textContent = formData.nationality || 'N/A';
        document.getElementById('summaryReligion').textContent = formData.religion || 'N/A';
        
        // Contact Information
        document.getElementById('summaryAddress').textContent = formData.address || 'N/A';
        document.getElementById('summaryPhone').textContent = formData.phone || 'N/A';
        document.getElementById('summaryEmail').textContent = formData.email || 'N/A';
        
        // Guardian Information
        document.getElementById('summaryGuardianName').textContent = formData.guardianName || 'N/A';
        document.getElementById('summaryGuardianPhone').textContent = formData.guardianPhone || 'N/A';
        document.getElementById('summaryGuardianRelationship').textContent = formData.guardianRelationship || 'N/A';
        
        // Academic Information
        document.getElementById('summaryCourse').textContent = formData.course || 'N/A';
        document.getElementById('summaryYear').textContent = formData.year || 'N/A';
        document.getElementById('summaryEnrollmentDate').textContent = formatDate(formData.enrollmentDate);
        
        // Display selected subjects
        let subjectsHtml = '';
        try {
            const subjects = JSON.parse(formData.subjects || '[]');
            if (subjects.length > 0) {
                const totalUnits = subjects.reduce((sum, s) => sum + s.units, 0);
                subjectsHtml = '<div class="flex flex-wrap gap-1">';
                subjects.forEach(s => {
                    subjectsHtml += `<span class="badge badge-outline badge-sm">${s.code}</span>`;
                });
                subjectsHtml += `</div><p class="mt-2 font-semibold text-primary">${subjects.length} subjects (${totalUnits} units)</p>`;
            } else {
                subjectsHtml = '<span class="text-gray-400">No subjects selected</span>';
            }
        } catch (e) {
            subjectsHtml = '<span class="text-gray-400">No subjects selected</span>';
        }
        document.getElementById('summarySubjects').innerHTML = subjectsHtml;
        
        // Show the modal
        document.getElementById('summaryModal').showModal();
    }

    function formatDate(dateString) {
        if (!dateString) return 'N/A';
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
    }

    function confirmEnrollment() {
        document.getElementById('summaryModal').close();
        submitEnrollment();
    }

    // Subjects data organized by program and year
    const subjectsData = {
        'Bachelor of Science in Computer Science': {
            'First Year': [
                { code: 'CS101', name: 'Introduction to Programming', units: 3 },
                { code: 'CS103', name: 'Discrete Mathematics', units: 3 },
                { code: 'CS105', name: 'Computer Organization', units: 3 },
                { code: 'GE101', name: 'Understanding the Self', units: 3 },
                { code: 'GE102', name: 'Readings in Philippine History', units: 3 },
                { code: 'GE103', name: 'The Contemporary World', units: 3 },
                { code: 'PE101', name: 'Physical Education 1', units: 2 },
                { code: 'NSTP1', name: 'National Service Training Program 1', units: 3 }
            ],
            'Second Year': [
                { code: 'CS201', name: 'Data Structures', units: 3 },
                { code: 'CS202', name: 'Object-Oriented Programming', units: 3 },
                { code: 'CS203', name: 'Database Systems', units: 3 },
                { code: 'CS204', name: 'Computer Networks', units: 3 },
                { code: 'CS205', name: 'Operating Systems', units: 3 },
                { code: 'GE104', name: 'Mathematics in the Modern World', units: 3 },
                { code: 'GE105', name: 'Purposive Communication', units: 3 },
                { code: 'PE102', name: 'Physical Education 2', units: 2 }
            ],
            'Third Year': [
                { code: 'CS301', name: 'Software Engineering', units: 3 },
                { code: 'CS302', name: 'Web Development', units: 3 },
                { code: 'CS303', name: 'Algorithms and Complexity', units: 3 },
                { code: 'CS304', name: 'Information Security', units: 3 },
                { code: 'CS305', name: 'Mobile Application Development', units: 3 },
                { code: 'CS306', name: 'Artificial Intelligence', units: 3 },
                { code: 'GE106', name: 'Art Appreciation', units: 3 },
                { code: 'GE107', name: 'Science, Technology and Society', units: 3 }
            ],
            'Fourth Year': [
                { code: 'CS401', name: 'Capstone Project 1', units: 3 },
                { code: 'CS402', name: 'Capstone Project 2', units: 3 },
                { code: 'CS403', name: 'Machine Learning', units: 3 },
                { code: 'CS404', name: 'Cloud Computing', units: 3 },
                { code: 'CS405', name: 'Systems Administration', units: 3 },
                { code: 'CS406', name: 'IT Project Management', units: 3 },
                { code: 'OJT', name: 'On-the-Job Training', units: 6 }
            ]
        },
        'Bachelor of Science in Information Technology': {
            'First Year': [
                { code: 'IT101', name: 'Introduction to Information Technology', units: 3 },
                { code: 'IT102', name: 'Computer Programming 1', units: 3 },
                { code: 'IT103', name: 'Computer Hardware Fundamentals', units: 3 },
                { code: 'GE101', name: 'Understanding the Self', units: 3 },
                { code: 'GE102', name: 'Readings in Philippine History', units: 3 },
                { code: 'GE103', name: 'The Contemporary World', units: 3 },
                { code: 'PE101', name: 'Physical Education 1', units: 2 },
                { code: 'NSTP1', name: 'National Service Training Program 1', units: 3 }
            ],
            'Second Year': [
                { code: 'IT201', name: 'Computer Programming 2', units: 3 },
                { code: 'IT202', name: 'Networking Fundamentals', units: 3 },
                { code: 'IT203', name: 'Database Management', units: 3 },
                { code: 'IT204', name: 'Web Systems and Technologies', units: 3 },
                { code: 'IT205', name: 'Systems Analysis and Design', units: 3 },
                { code: 'GE104', name: 'Mathematics in the Modern World', units: 3 },
                { code: 'GE105', name: 'Purposive Communication', units: 3 },
                { code: 'PE102', name: 'Physical Education 2', units: 2 }
            ],
            'Third Year': [
                { code: 'IT301', name: 'Network Administration', units: 3 },
                { code: 'IT302', name: 'Information Assurance and Security', units: 3 },
                { code: 'IT303', name: 'Integrative Programming', units: 3 },
                { code: 'IT304', name: 'System Integration and Architecture', units: 3 },
                { code: 'IT305', name: 'Platform Technologies', units: 3 },
                { code: 'IT306', name: 'Multimedia Systems', units: 3 },
                { code: 'GE106', name: 'Art Appreciation', units: 3 },
                { code: 'GE107', name: 'Science, Technology and Society', units: 3 }
            ],
            'Fourth Year': [
                { code: 'IT401', name: 'Capstone Project 1', units: 3 },
                { code: 'IT402', name: 'Capstone Project 2', units: 3 },
                { code: 'IT403', name: 'Technopreneurship', units: 3 },
                { code: 'IT404', name: 'IT Service Management', units: 3 },
                { code: 'IT405', name: 'Social and Professional Issues in IT', units: 3 },
                { code: 'OJT', name: 'On-the-Job Training', units: 6 }
            ]
        },
        'Bachelor of Arts in Business Administration': {
            'First Year': [
                { code: 'BA101', name: 'Introduction to Business', units: 3 },
                { code: 'BA102', name: 'Basic Economics', units: 3 },
                { code: 'BA103', name: 'Business Mathematics', units: 3 },
                { code: 'GE101', name: 'Understanding the Self', units: 3 },
                { code: 'GE102', name: 'Readings in Philippine History', units: 3 },
                { code: 'GE103', name: 'The Contemporary World', units: 3 },
                { code: 'PE101', name: 'Physical Education 1', units: 2 },
                { code: 'NSTP1', name: 'National Service Training Program 1', units: 3 }
            ],
            'Second Year': [
                { code: 'BA201', name: 'Principles of Management', units: 3 },
                { code: 'BA202', name: 'Financial Accounting', units: 3 },
                { code: 'BA203', name: 'Business Law', units: 3 },
                { code: 'BA204', name: 'Marketing Management', units: 3 },
                { code: 'BA205', name: 'Human Resource Management', units: 3 },
                { code: 'GE104', name: 'Mathematics in the Modern World', units: 3 },
                { code: 'GE105', name: 'Purposive Communication', units: 3 },
                { code: 'PE102', name: 'Physical Education 2', units: 2 }
            ],
            'Third Year': [
                { code: 'BA301', name: 'Financial Management', units: 3 },
                { code: 'BA302', name: 'Operations Management', units: 3 },
                { code: 'BA303', name: 'Business Research Methods', units: 3 },
                { code: 'BA304', name: 'Strategic Management', units: 3 },
                { code: 'BA305', name: 'Business Ethics', units: 3 },
                { code: 'BA306', name: 'Entrepreneurship', units: 3 },
                { code: 'GE106', name: 'Art Appreciation', units: 3 },
                { code: 'GE107', name: 'Science, Technology and Society', units: 3 }
            ],
            'Fourth Year': [
                { code: 'BA401', name: 'Business Policy and Strategy', units: 3 },
                { code: 'BA402', name: 'International Business', units: 3 },
                { code: 'BA403', name: 'Business Thesis 1', units: 3 },
                { code: 'BA404', name: 'Business Thesis 2', units: 3 },
                { code: 'BA405', name: 'Business Simulation', units: 3 },
                { code: 'OJT', name: 'On-the-Job Training', units: 6 }
            ]
        }
    };

    function loadSubjects() {
        const program = document.getElementById('programSelect').value;
        const year = document.getElementById('yearSelect').value;
        const container = document.getElementById('subjectsContainer');
        
        if (!program || !year) {
            container.innerHTML = '<p class="text-gray-500 text-sm text-center py-4">Please select a course/program and year level first</p>';
            document.getElementById('selectedSubjects').value = '';
            return;
        }
        
        const subjects = subjectsData[program]?.[year];
        
        if (!subjects || subjects.length === 0) {
            container.innerHTML = '<p class="text-gray-500 text-sm text-center py-4">No subjects available for this selection</p>';
            document.getElementById('selectedSubjects').value = '';
            return;
        }
        
        // Calculate total units
        const totalUnits = subjects.reduce((sum, s) => sum + s.units, 0);
        
        let html = `
            <div class="flex justify-between items-center mb-3 pb-2 border-b">
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="selectAll" class="checkbox checkbox-primary checkbox-sm" onchange="toggleAllSubjects(this)" checked />
                    <label for="selectAll" class="text-sm font-semibold cursor-pointer">Select All</label>
                </div>
                <div class="badge badge-primary">Total: <span id="totalUnits">${totalUnits}</span> units</div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
        `;
        
        subjects.forEach((subject, index) => {
            html += `
                <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-100 cursor-pointer border border-gray-200">
                    <input type="checkbox" class="checkbox checkbox-primary checkbox-sm subject-checkbox" 
                           value="${subject.code}" 
                           data-name="${subject.name}" 
                           data-units="${subject.units}"
                           onchange="updateSelectedSubjects()" checked />
                    <div class="flex-1">
                        <span class="font-semibold text-sm text-primary">${subject.code}</span>
                        <span class="text-sm text-gray-700"> - ${subject.name}</span>
                    </div>
                    <span class="badge badge-ghost badge-sm">${subject.units} units</span>
                </label>
            `;
        });
        
        html += '</div>';
        container.innerHTML = html;
        
        // Initialize selected subjects
        updateSelectedSubjects();
    }

    function toggleAllSubjects(selectAllCheckbox) {
        const checkboxes = document.querySelectorAll('.subject-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = selectAllCheckbox.checked;
        });
        updateSelectedSubjects();
    }

    function updateSelectedSubjects() {
        const checkboxes = document.querySelectorAll('.subject-checkbox:checked');
        const selectedSubjects = [];
        let totalUnits = 0;
        
        checkboxes.forEach(cb => {
            selectedSubjects.push({
                code: cb.value,
                name: cb.dataset.name,
                units: parseInt(cb.dataset.units)
            });
            totalUnits += parseInt(cb.dataset.units);
        });
        
        // Update hidden field with JSON
        document.getElementById('selectedSubjects').value = JSON.stringify(selectedSubjects);
        
        // Update total units display
        const totalUnitsEl = document.getElementById('totalUnits');
        if (totalUnitsEl) {
            totalUnitsEl.textContent = totalUnits;
        }
        
        // Update select all checkbox state
        const allCheckboxes = document.querySelectorAll('.subject-checkbox');
        const selectAllCheckbox = document.getElementById('selectAll');
        if (selectAllCheckbox) {
            selectAllCheckbox.checked = checkboxes.length === allCheckboxes.length;
            selectAllCheckbox.indeterminate = checkboxes.length > 0 && checkboxes.length < allCheckboxes.length;
        }
    }

    function prevStep() {
        if (currentStep > 1) {
            saveStepData();
            currentStep--;
            updateSteps();
        }
    }

    function updateSteps() {
        // Hide all steps
        document.querySelectorAll('.form-step').forEach(step => {
            step.classList.add('hidden');
        });

        // Show current step
        document.querySelector(`[data-step="${currentStep}"]`).classList.remove('hidden');

        // Update progress indicator
        for (let i = 1; i <= totalSteps; i++) {
            const stepElement = document.getElementById(`step-${i}`);
            const progressBar = document.getElementById(`progress-${i}`);
            
            if (i < currentStep) {
                // Completed step
                stepElement.classList.add('bg-green-600', 'text-white');
                stepElement.classList.remove('bg-gray-300', 'text-gray-600', 'bg-blue-600');
                if (progressBar) {
                    progressBar.classList.add('bg-green-600');
                    progressBar.classList.remove('bg-gray-300');
                }
            } else if (i === currentStep) {
                // Current step
                stepElement.classList.add('bg-blue-600', 'text-white');
                stepElement.classList.remove('bg-gray-300', 'text-gray-600', 'bg-green-600');
            } else {
                // Upcoming step
                stepElement.classList.add('bg-gray-300', 'text-gray-600');
                stepElement.classList.remove('bg-blue-600', 'text-white', 'bg-green-600');
                if (progressBar) {
                    progressBar.classList.add('bg-gray-300');
                    progressBar.classList.remove('bg-green-600');
                }
            }
        }

        // Update button visibility
        document.getElementById('prevBtn').style.display = currentStep > 1 ? 'block' : 'none';
        document.getElementById('nextBtn').textContent = currentStep === totalSteps ? 'Submit' : 'Next';

        // Add arrow icon back
        if (currentStep < totalSteps) {
            document.getElementById('nextBtn').innerHTML = `
                Next
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            `;
        } else {
            document.getElementById('nextBtn').innerHTML = `
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Submit
            `;
        }

        // Update step indicator
        document.getElementById('stepIndicator').textContent = `Step ${currentStep} of ${totalSteps}`;
        
        // Scroll to top of form
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function validateCurrentStep() {
        console.log('Validating step', currentStep);
        const currentStepElement = document.querySelector(`[data-step="${currentStep}"]`);
        
        if (!currentStepElement) {
            console.error('Step element not found for step', currentStep);
            return false;
        }
        
        const inputs = currentStepElement.querySelectorAll('input[required], select[required], textarea[required]');
        console.log('Found', inputs.length, 'required fields');

        let isValid = true;
        let emptyFields = [];
        
        for (let input of inputs) {
            const value = input.value ? input.value.trim() : '';
            console.log('Field:', input.name, 'Value:', value, 'Valid:', !!value);
            
            if (!value) {
                input.classList.add('input-error', 'border-red-500');
                emptyFields.push(input.name || input.placeholder || 'Unknown field');
                isValid = false;
            } else {
                input.classList.remove('input-error', 'border-red-500');
            }
        }
        
        if (!isValid) {
            console.log('Empty fields:', emptyFields);
            alert('Please fill in all required fields:\n- ' + emptyFields.join('\n- '));
        }
        
        return isValid;
    }

    function saveStepData() {
        const currentStepElement = document.querySelector(`[data-step="${currentStep}"]`);
        const inputs = currentStepElement.querySelectorAll('input, select, textarea');

        inputs.forEach(input => {
            formData[input.name] = input.value;
        });
    }

    function submitEnrollment() {
        saveStepData();

        // Prepare form data for AJAX submission
        const submitData = new FormData();
        submitData.append('action', 'enroll_student');
        
        // Add all form fields
        for (const key in formData) {
            submitData.append(key, formData[key]);
        }

        // Show loading state
        const nextBtn = document.getElementById('nextBtn');
        nextBtn.disabled = true;
        nextBtn.innerHTML = '<span class="loading loading-spinner loading-sm"></span> Submitting...';

        // Submit via AJAX
        fetch('?page=enrollment', {
            method: 'POST',
            body: submitData
        })
        .then(response => response.json())
        .then(data => {
            nextBtn.disabled = false;
            nextBtn.innerHTML = `
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Submit
            `;
            
            if (data.success) {
                console.log('Student enrolled successfully:', data.student);
                
                // Update next student number for subsequent enrollments
                nextStudentNumber++;

                // Show success modal
                document.getElementById('confirmStudentId').textContent = data.student.student_id;
                document.getElementById('confirmStudentName').textContent = `${data.student.first_name} ${data.student.last_name}`;
                document.getElementById('successModal').showModal();
                
                // Clear form data
                formData = {};
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Enrollment Failed',
                    text: data.message || 'An error occurred while enrolling the student.'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            nextBtn.disabled = false;
            nextBtn.innerHTML = `
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Submit
            `;
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to connect to the server. Please try again.'
            });
        });
    }

    function saveDraft() {
        saveStepData();
        localStorage.setItem('enrollment_draft', JSON.stringify({
            data: formData,
            step: currentStep,
            timestamp: new Date().getTime()
        }));
        alert('Draft saved successfully!');
    }

    function resetAndNewEnrollment() {
        formData = {};
        currentStep = 1;
        document.getElementById('enrollmentForm').reset();
        document.getElementById('successModal').close();
        updateSteps();

        // Set enrollment date to today
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('enrollmentDate').value = today;
        
        // Update student ID display for next student
        document.getElementById('studentIdDisplay').textContent = collegeCode + '-' + String(nextStudentNumber).padStart(5, '0');
    }

    function redirectToDashboard() {
        window.location.href = '?page=dashboard';
    }

    // Philippine Address Data
    const philippineData = {
        regions: [
            { code: 'NCR', name: 'National Capital Region (NCR)' },
            { code: 'CAR', name: 'Cordillera Administrative Region (CAR)' },
            { code: 'I', name: 'Region I - Ilocos Region' },
            { code: 'II', name: 'Region II - Cagayan Valley' },
            { code: 'III', name: 'Region III - Central Luzon' },
            { code: 'IV-A', name: 'Region IV-A - CALABARZON' },
            { code: 'IV-B', name: 'Region IV-B - MIMAROPA' },
            { code: 'V', name: 'Region V - Bicol Region' },
            { code: 'VI', name: 'Region VI - Western Visayas' },
            { code: 'VII', name: 'Region VII - Central Visayas' },
            { code: 'VIII', name: 'Region VIII - Eastern Visayas' },
            { code: 'IX', name: 'Region IX - Zamboanga Peninsula' },
            { code: 'X', name: 'Region X - Northern Mindanao' },
            { code: 'XI', name: 'Region XI - Davao Region' },
            { code: 'XII', name: 'Region XII - SOCCSKSARGEN' },
            { code: 'XIII', name: 'Region XIII - Caraga' },
            { code: 'BARMM', name: 'Bangsamoro Autonomous Region in Muslim Mindanao (BARMM)' }
        ],
        provinces: {
            'NCR': [
                { code: 'NCR', name: 'Metro Manila' }
            ],
            'CAR': [
                { code: 'ABR', name: 'Abra' },
                { code: 'APA', name: 'Apayao' },
                { code: 'BEN', name: 'Benguet' },
                { code: 'IFU', name: 'Ifugao' },
                { code: 'KAL', name: 'Kalinga' },
                { code: 'MOU', name: 'Mountain Province' }
            ],
            'I': [
                { code: 'ILN', name: 'Ilocos Norte' },
                { code: 'ILS', name: 'Ilocos Sur' },
                { code: 'LUN', name: 'La Union' },
                { code: 'PAN', name: 'Pangasinan' }
            ],
            'II': [
                { code: 'BTN', name: 'Batanes' },
                { code: 'CAG', name: 'Cagayan' },
                { code: 'ISA', name: 'Isabela' },
                { code: 'NUV', name: 'Nueva Vizcaya' },
                { code: 'QUI', name: 'Quirino' }
            ],
            'III': [
                { code: 'AUR', name: 'Aurora' },
                { code: 'BAN', name: 'Bataan' },
                { code: 'BUL', name: 'Bulacan' },
                { code: 'NEC', name: 'Nueva Ecija' },
                { code: 'PAM', name: 'Pampanga' },
                { code: 'TAR', name: 'Tarlac' },
                { code: 'ZAM', name: 'Zambales' }
            ],
            'IV-A': [
                { code: 'BAT', name: 'Batangas' },
                { code: 'CAV', name: 'Cavite' },
                { code: 'LAG', name: 'Laguna' },
                { code: 'QUE', name: 'Quezon' },
                { code: 'RIZ', name: 'Rizal' }
            ],
            'IV-B': [
                { code: 'MAD', name: 'Marinduque' },
                { code: 'OCC', name: 'Occidental Mindoro' },
                { code: 'ORI', name: 'Oriental Mindoro' },
                { code: 'PLW', name: 'Palawan' },
                { code: 'ROM', name: 'Romblon' }
            ],
            'V': [
                { code: 'ALB', name: 'Albay' },
                { code: 'CAN', name: 'Camarines Norte' },
                { code: 'CAS', name: 'Camarines Sur' },
                { code: 'CAT', name: 'Catanduanes' },
                { code: 'MAS', name: 'Masbate' },
                { code: 'SOR', name: 'Sorsogon' }
            ],
            'VI': [
                { code: 'AKL', name: 'Aklan' },
                { code: 'ANT', name: 'Antique' },
                { code: 'CAP', name: 'Capiz' },
                { code: 'GUI', name: 'Guimaras' },
                { code: 'ILO', name: 'Iloilo' },
                { code: 'NOR', name: 'Negros Occidental' }
            ],
            'VII': [
                { code: 'BOH', name: 'Bohol' },
                { code: 'CEB', name: 'Cebu' },
                { code: 'NEO', name: 'Negros Oriental' },
                { code: 'SIQ', name: 'Siquijor' }
            ],
            'VIII': [
                { code: 'BIL', name: 'Biliran' },
                { code: 'EAS', name: 'Eastern Samar' },
                { code: 'LEY', name: 'Leyte' },
                { code: 'NOS', name: 'Northern Samar' },
                { code: 'SAM', name: 'Samar' },
                { code: 'SOL', name: 'Southern Leyte' }
            ],
            'IX': [
                { code: 'ZDN', name: 'Zamboanga del Norte' },
                { code: 'ZDS', name: 'Zamboanga del Sur' },
                { code: 'ZSI', name: 'Zamboanga Sibugay' }
            ],
            'X': [
                { code: 'BUK', name: 'Bukidnon' },
                { code: 'CAM', name: 'Camiguin' },
                { code: 'LDN', name: 'Lanao del Norte' },
                { code: 'MSC', name: 'Misamis Occidental' },
                { code: 'MSO', name: 'Misamis Oriental' }
            ],
            'XI': [
                { code: 'COM', name: 'Davao de Oro' },
                { code: 'DVO', name: 'Davao del Norte' },
                { code: 'DVS', name: 'Davao del Sur' },
                { code: 'DOR', name: 'Davao Oriental' },
                { code: 'DOC', name: 'Davao Occidental' }
            ],
            'XII': [
                { code: 'COT', name: 'Cotabato' },
                { code: 'SAR', name: 'Sarangani' },
                { code: 'SCO', name: 'South Cotabato' },
                { code: 'SKU', name: 'Sultan Kudarat' }
            ],
            'XIII': [
                { code: 'AGN', name: 'Agusan del Norte' },
                { code: 'AGS', name: 'Agusan del Sur' },
                { code: 'DIN', name: 'Dinagat Islands' },
                { code: 'SUN', name: 'Surigao del Norte' },
                { code: 'SUS', name: 'Surigao del Sur' }
            ],
            'BARMM': [
                { code: 'BAS', name: 'Basilan' },
                { code: 'LDS', name: 'Lanao del Sur' },
                { code: 'MAG', name: 'Maguindanao' },
                { code: 'SUL', name: 'Sulu' },
                { code: 'TAW', name: 'Tawi-Tawi' }
            ]
        },
        cities: {
            // NCR - Metro Manila Cities
            'NCR': [
                { code: 'CAL', name: 'Caloocan' },
                { code: 'LPC', name: 'Las Piñas' },
                { code: 'MAK', name: 'Makati' },
                { code: 'MAL', name: 'Malabon' },
                { code: 'MAN', name: 'Mandaluyong' },
                { code: 'MNL', name: 'Manila' },
                { code: 'MAR', name: 'Marikina' },
                { code: 'MUN', name: 'Muntinlupa' },
                { code: 'NAV', name: 'Navotas' },
                { code: 'PAR', name: 'Parañaque' },
                { code: 'PAS', name: 'Pasay' },
                { code: 'PSG', name: 'Pasig' },
                { code: 'PAT', name: 'Pateros' },
                { code: 'QC', name: 'Quezon City' },
                { code: 'SMJ', name: 'San Juan' },
                { code: 'TAG', name: 'Taguig' },
                { code: 'VAL', name: 'Valenzuela' }
            ],
            // Region III - Pampanga Cities/Municipalities
            'PAM': [
                { code: 'ANG', name: 'Angeles City' },
                { code: 'SFC', name: 'San Fernando City' },
                { code: 'MAC', name: 'Mabalacat City' },
                { code: 'APA', name: 'Apalit' },
                { code: 'ARA', name: 'Arayat' },
                { code: 'BAC', name: 'Bacolor' },
                { code: 'CAN', name: 'Candaba' },
                { code: 'FLO', name: 'Floridablanca' },
                { code: 'GUA', name: 'Guagua' },
                { code: 'LUB', name: 'Lubao' },
                { code: 'MAC', name: 'Macabebe' },
                { code: 'MAG', name: 'Magalang' },
                { code: 'MAS', name: 'Masantol' },
                { code: 'MEX', name: 'Mexico' },
                { code: 'MIN', name: 'Minalin' },
                { code: 'POC', name: 'Porac' },
                { code: 'SAG', name: 'San Luis' },
                { code: 'SAN', name: 'San Simon' },
                { code: 'SAS', name: 'Santa Ana' },
                { code: 'SRT', name: 'Santa Rita' },
                { code: 'SAC', name: 'Santo Tomas' },
                { code: 'SAM', name: 'Sasmuan' }
            ],
            // Region III - Bulacan Cities/Municipalities
            'BUL': [
                { code: 'MAL', name: 'Malolos City' },
                { code: 'MEY', name: 'Meycauayan City' },
                { code: 'SMR', name: 'San Jose del Monte City' },
                { code: 'ANG', name: 'Angat' },
                { code: 'BAL', name: 'Balagtas' },
                { code: 'BLI', name: 'Baliuag' },
                { code: 'BOC', name: 'Bocaue' },
                { code: 'BUL', name: 'Bulakan' },
                { code: 'BUS', name: 'Bustos' },
                { code: 'CAL', name: 'Calumpit' },
                { code: 'DON', name: 'Doña Remedios Trinidad' },
                { code: 'GUA', name: 'Guiguinto' },
                { code: 'HAG', name: 'Hagonoy' },
                { code: 'MAR', name: 'Marilao' },
                { code: 'NOR', name: 'Norzagaray' },
                { code: 'OBA', name: 'Obando' },
                { code: 'PAL', name: 'Pandi' },
                { code: 'PAS', name: 'Paombong' },
                { code: 'PLA', name: 'Plaridel' },
                { code: 'PUL', name: 'Pulilan' },
                { code: 'SAI', name: 'San Ildefonso' },
                { code: 'SMI', name: 'San Miguel' },
                { code: 'SRA', name: 'San Rafael' },
                { code: 'SMA', name: 'Santa Maria' }
            ],
            // Region III - Nueva Ecija Cities/Municipalities
            'NEC': [
                { code: 'CAB', name: 'Cabanatuan City' },
                { code: 'GAP', name: 'Gapan City' },
                { code: 'MUN', name: 'Muñoz City' },
                { code: 'PAL', name: 'Palayan City' },
                { code: 'SJS', name: 'San Jose City' },
                { code: 'ALI', name: 'Aliaga' },
                { code: 'BON', name: 'Bongabon' },
                { code: 'CAB', name: 'Cabiao' },
                { code: 'CAR', name: 'Carranglan' },
                { code: 'CUY', name: 'Cuyapo' },
                { code: 'GAL', name: 'Gabaldon' },
                { code: 'GEN', name: 'General Mamerto Natividad' },
                { code: 'GMT', name: 'General Tinio' },
                { code: 'GUA', name: 'Guimba' },
                { code: 'JAE', name: 'Jaen' },
                { code: 'LAU', name: 'Laur' },
                { code: 'LIC', name: 'Licab' },
                { code: 'LLA', name: 'Llanera' },
                { code: 'LUP', name: 'Lupao' },
                { code: 'NAM', name: 'Nampicuan' },
                { code: 'PEÑ', name: 'Peñaranda' },
                { code: 'QUE', name: 'Quezon' },
                { code: 'RIZ', name: 'Rizal' },
                { code: 'SAN', name: 'San Antonio' },
                { code: 'SIS', name: 'San Isidro' },
                { code: 'SLE', name: 'San Leonardo' },
                { code: 'STA', name: 'Santa Rosa' },
                { code: 'STD', name: 'Santo Domingo' },
                { code: 'TAL', name: 'Talavera' },
                { code: 'TAU', name: 'Talugtug' },
                { code: 'ZAR', name: 'Zaragoza' }
            ],
            // Region III - Tarlac Cities/Municipalities
            'TAR': [
                { code: 'TAR', name: 'Tarlac City' },
                { code: 'ANA', name: 'Anao' },
                { code: 'BAM', name: 'Bamban' },
                { code: 'CAM', name: 'Camiling' },
                { code: 'CAP', name: 'Capas' },
                { code: 'CON', name: 'Concepcion' },
                { code: 'GER', name: 'Gerona' },
                { code: 'LAP', name: 'La Paz' },
                { code: 'MAY', name: 'Mayantoc' },
                { code: 'MAN', name: 'Moncada' },
                { code: 'PAN', name: 'Paniqui' },
                { code: 'PUR', name: 'Pura' },
                { code: 'RAM', name: 'Ramos' },
                { code: 'SAN', name: 'San Clemente' },
                { code: 'SNJ', name: 'San Jose' },
                { code: 'SNM', name: 'San Manuel' },
                { code: 'STA', name: 'Santa Ignacia' },
                { code: 'VIC', name: 'Victoria' }
            ],
            // Region III - Bataan Cities/Municipalities
            'BAN': [
                { code: 'BAL', name: 'Balanga City' },
                { code: 'ABU', name: 'Abucay' },
                { code: 'BAG', name: 'Bagac' },
                { code: 'DIN', name: 'Dinalupihan' },
                { code: 'HER', name: 'Hermosa' },
                { code: 'LIM', name: 'Limay' },
                { code: 'MAR', name: 'Mariveles' },
                { code: 'MOR', name: 'Morong' },
                { code: 'ORA', name: 'Orani' },
                { code: 'ORI', name: 'Orion' },
                { code: 'PIL', name: 'Pilar' },
                { code: 'SAM', name: 'Samal' }
            ],
            // Region III - Zambales Cities/Municipalities
            'ZAM': [
                { code: 'OLO', name: 'Olongapo City' },
                { code: 'BOT', name: 'Botolan' },
                { code: 'CAS', name: 'Castillejos' },
                { code: 'IBA', name: 'Iba' },
                { code: 'MAI', name: 'Masinloc' },
                { code: 'PAL', name: 'Palauig' },
                { code: 'SNM', name: 'San Antonio' },
                { code: 'SNF', name: 'San Felipe' },
                { code: 'SNM', name: 'San Marcelino' },
                { code: 'SNN', name: 'San Narciso' },
                { code: 'SNA', name: 'Santa Cruz' },
                { code: 'SUB', name: 'Subic' }
            ],
            // Region III - Aurora Cities/Municipalities
            'AUR': [
                { code: 'BAL', name: 'Baler' },
                { code: 'CAS', name: 'Casiguran' },
                { code: 'DIL', name: 'Dilasag' },
                { code: 'DIN', name: 'Dinalungan' },
                { code: 'DIG', name: 'Dingalan' },
                { code: 'DIP', name: 'Dipaculao' },
                { code: 'MAR', name: 'Maria Aurora' },
                { code: 'SAN', name: 'San Luis' }
            ],
            // Region IV-A - Cavite Cities/Municipalities
            'CAV': [
                { code: 'BAC', name: 'Bacoor City' },
                { code: 'CAV', name: 'Cavite City' },
                { code: 'DAS', name: 'Dasmariñas City' },
                { code: 'GEN', name: 'General Trias City' },
                { code: 'IMU', name: 'Imus City' },
                { code: 'TAG', name: 'Tagaytay City' },
                { code: 'TRE', name: 'Trece Martires City' },
                { code: 'ALF', name: 'Alfonso' },
                { code: 'AMA', name: 'Amadeo' },
                { code: 'CAR', name: 'Carmona' },
                { code: 'GMA', name: 'General Mariano Alvarez' },
                { code: 'GEA', name: 'General Emilio Aguinaldo' },
                { code: 'IND', name: 'Indang' },
                { code: 'KAW', name: 'Kawit' },
                { code: 'MAG', name: 'Magallanes' },
                { code: 'MAR', name: 'Maragondon' },
                { code: 'MEN', name: 'Mendez' },
                { code: 'NAI', name: 'Naic' },
                { code: 'NOV', name: 'Noveleta' },
                { code: 'ROS', name: 'Rosario' },
                { code: 'SIL', name: 'Silang' },
                { code: 'TAN', name: 'Tanza' },
                { code: 'TER', name: 'Ternate' }
            ],
            // Region IV-A - Laguna Cities/Municipalities
            'LAG': [
                { code: 'BIN', name: 'Biñan City' },
                { code: 'CAB', name: 'Cabuyao City' },
                { code: 'CAL', name: 'Calamba City' },
                { code: 'SPC', name: 'San Pablo City' },
                { code: 'SPE', name: 'San Pedro City' },
                { code: 'SRO', name: 'Santa Rosa City' },
                { code: 'ALA', name: 'Alaminos' },
                { code: 'BAY', name: 'Bay' },
                { code: 'CAL', name: 'Calauan' },
                { code: 'CAV', name: 'Cavinti' },
                { code: 'FON', name: 'Famy' },
                { code: 'KAL', name: 'Kalayaan' },
                { code: 'LIL', name: 'Liliw' },
                { code: 'LOS', name: 'Los Baños' },
                { code: 'LUB', name: 'Luisiana' },
                { code: 'LUM', name: 'Lumban' },
                { code: 'MAB', name: 'Mabitac' },
                { code: 'MAG', name: 'Magdalena' },
                { code: 'MAJ', name: 'Majayjay' },
                { code: 'NAG', name: 'Nagcarlan' },
                { code: 'PAE', name: 'Paete' },
                { code: 'PAG', name: 'Pagsanjan' },
                { code: 'PAK', name: 'Pakil' },
                { code: 'PAN', name: 'Pangil' },
                { code: 'PIL', name: 'Pila' },
                { code: 'RIZ', name: 'Rizal' },
                { code: 'SAN', name: 'San Antonio' },
                { code: 'SCR', name: 'Santa Cruz' },
                { code: 'SMA', name: 'Santa Maria' },
                { code: 'SIN', name: 'Siniloan' },
                { code: 'VIC', name: 'Victoria' }
            ],
            // Region IV-A - Batangas Cities/Municipalities
            'BAT': [
                { code: 'BAT', name: 'Batangas City' },
                { code: 'LIP', name: 'Lipa City' },
                { code: 'TAN', name: 'Tanauan City' },
                { code: 'STO', name: 'Santo Tomas City' },
                { code: 'AGO', name: 'Agoncillo' },
                { code: 'ALI', name: 'Alitagtag' },
                { code: 'BAL', name: 'Balayan' },
                { code: 'BAU', name: 'Bauan' },
                { code: 'CAL', name: 'Calaca' },
                { code: 'CAA', name: 'Calatagan' },
                { code: 'CUE', name: 'Cuenca' },
                { code: 'IBA', name: 'Ibaan' },
                { code: 'LAU', name: 'Laurel' },
                { code: 'LEM', name: 'Lemery' },
                { code: 'LIA', name: 'Lian' },
                { code: 'LOB', name: 'Lobo' },
                { code: 'MAB', name: 'Mabini' },
                { code: 'MAL', name: 'Malvar' },
                { code: 'MAT', name: 'Mataas na Kahoy' },
                { code: 'NAS', name: 'Nasugbu' },
                { code: 'PAD', name: 'Padre Garcia' },
                { code: 'ROS', name: 'Rosario' },
                { code: 'SNJ', name: 'San Jose' },
                { code: 'SJN', name: 'San Juan' },
                { code: 'SNL', name: 'San Luis' },
                { code: 'SNN', name: 'San Nicolas' },
                { code: 'SNP', name: 'San Pascual' },
                { code: 'SRC', name: 'Santa Teresita' },
                { code: 'TAL', name: 'Taal' },
                { code: 'TAY', name: 'Taysan' },
                { code: 'TIN', name: 'Tingloy' },
                { code: 'TUY', name: 'Tuy' }
            ],
            // Region IV-A - Rizal Cities/Municipalities
            'RIZ': [
                { code: 'ANT', name: 'Antipolo City' },
                { code: 'ANG', name: 'Angono' },
                { code: 'BAR', name: 'Baras' },
                { code: 'BIN', name: 'Binangonan' },
                { code: 'CAI', name: 'Cainta' },
                { code: 'CAR', name: 'Cardona' },
                { code: 'JAL', name: 'Jalajala' },
                { code: 'MOR', name: 'Morong' },
                { code: 'PIL', name: 'Pililla' },
                { code: 'ROD', name: 'Rodriguez' },
                { code: 'SMA', name: 'San Mateo' },
                { code: 'TAN', name: 'Tanay' },
                { code: 'TAY', name: 'Taytay' },
                { code: 'TER', name: 'Teresa' }
            ],
            // Region IV-A - Quezon Cities/Municipalities
            'QUE': [
                { code: 'LUC', name: 'Lucena City' },
                { code: 'TAY', name: 'Tayabas City' },
                { code: 'ALA', name: 'Alabat' },
                { code: 'ATI', name: 'Atimonan' },
                { code: 'BUR', name: 'Burdeos' },
                { code: 'CAL', name: 'Calauag' },
                { code: 'CAN', name: 'Candelaria' },
                { code: 'CAT', name: 'Catanauan' },
                { code: 'DOL', name: 'Dolores' },
                { code: 'GEN', name: 'General Luna' },
                { code: 'GNA', name: 'General Nakar' },
                { code: 'GUM', name: 'Guinayangan' },
                { code: 'GUJ', name: 'Gumaca' },
                { code: 'INF', name: 'Infanta' },
                { code: 'JOV', name: 'Jomalig' },
                { code: 'LOP', name: 'Lopez' },
                { code: 'LUC', name: 'Lucban' },
                { code: 'MAC', name: 'Macalelon' },
                { code: 'MAU', name: 'Mauban' },
                { code: 'MUR', name: 'Mulanay' },
                { code: 'PAD', name: 'Padre Burgos' },
                { code: 'PAG', name: 'Pagbilao' },
                { code: 'PAN', name: 'Panukulan' },
                { code: 'PAT', name: 'Patnanungan' },
                { code: 'PER', name: 'Perez' },
                { code: 'PIT', name: 'Pitogo' },
                { code: 'PLA', name: 'Plaridel' },
                { code: 'POL', name: 'Polillo' },
                { code: 'QUE', name: 'Quezon' },
                { code: 'REA', name: 'Real' },
                { code: 'SAM', name: 'Sampaloc' },
                { code: 'SNA', name: 'San Andres' },
                { code: 'SNT', name: 'San Antonio' },
                { code: 'SNF', name: 'San Francisco' },
                { code: 'SNN', name: 'San Narciso' },
                { code: 'SAR', name: 'Sariaya' },
                { code: 'TAG', name: 'Tagkawayan' },
                { code: 'TIA', name: 'Tiaong' },
                { code: 'UNI', name: 'Unisan' }
            ],
            // Region I - Ilocos Norte Cities/Municipalities
            'ILN': [
                { code: 'LAO', name: 'Laoag City' },
                { code: 'BAT', name: 'Batac City' },
                { code: 'ADA', name: 'Adams' },
                { code: 'BAC', name: 'Bacarra' },
                { code: 'BAD', name: 'Badoc' },
                { code: 'BAN', name: 'Bangui' },
                { code: 'BAU', name: 'Banna' },
                { code: 'BUR', name: 'Burgos' },
                { code: 'CAR', name: 'Carasi' },
                { code: 'CUR', name: 'Currimao' },
                { code: 'DIN', name: 'Dingras' },
                { code: 'DUM', name: 'Dumalneg' },
                { code: 'MAR', name: 'Marcos' },
                { code: 'NUE', name: 'Nueva Era' },
                { code: 'PAG', name: 'Pagudpud' },
                { code: 'PAO', name: 'Paoay' },
                { code: 'PAS', name: 'Pasuquin' },
                { code: 'PID', name: 'Piddig' },
                { code: 'PIN', name: 'Pinili' },
                { code: 'SNI', name: 'San Nicolas' },
                { code: 'SAR', name: 'Sarrat' },
                { code: 'SOL', name: 'Solsona' },
                { code: 'VIN', name: 'Vintar' }
            ],
            // Region I - Ilocos Sur Cities/Municipalities
            'ILS': [
                { code: 'VIG', name: 'Vigan City' },
                { code: 'CAN', name: 'Candon City' },
                { code: 'ALS', name: 'Alilem' },
                { code: 'BAN', name: 'Banayoyo' },
                { code: 'BAN', name: 'Bantay' },
                { code: 'BUR', name: 'Burgos' },
                { code: 'CAB', name: 'Cabugao' },
                { code: 'CAO', name: 'Caoayan' },
                { code: 'CER', name: 'Cervantes' },
                { code: 'GAL', name: 'Galimuyod' },
                { code: 'GRE', name: 'Gregorio del Pilar' },
                { code: 'LID', name: 'Lidlidda' },
                { code: 'MAG', name: 'Magsingal' },
                { code: 'NAR', name: 'Narvacan' },
                { code: 'QUI', name: 'Quirino' },
                { code: 'SLO', name: 'Salcedo' },
                { code: 'SEM', name: 'San Emilio' },
                { code: 'SEV', name: 'San Esteban' },
                { code: 'SIL', name: 'San Ildefonso' },
                { code: 'SJU', name: 'San Juan' },
                { code: 'SVE', name: 'San Vicente' },
                { code: 'SAC', name: 'Santa' },
                { code: 'SCA', name: 'Santa Catalina' },
                { code: 'SCR', name: 'Santa Cruz' },
                { code: 'SLU', name: 'Santa Lucia' },
                { code: 'SMA', name: 'Santa Maria' },
                { code: 'SAN', name: 'Santiago' },
                { code: 'STO', name: 'Santo Domingo' },
                { code: 'SIG', name: 'Sigay' },
                { code: 'SIN', name: 'Sinait' },
                { code: 'SUG', name: 'Sugpon' },
                { code: 'SUY', name: 'Suyo' },
                { code: 'TAG', name: 'Tagudin' }
            ],
            // Region I - La Union Cities/Municipalities
            'LUN': [
                { code: 'SFC', name: 'San Fernando City' },
                { code: 'AGO', name: 'Agoo' },
                { code: 'ARO', name: 'Aringay' },
                { code: 'BAC', name: 'Bacnotan' },
                { code: 'BAL', name: 'Balaoan' },
                { code: 'BAN', name: 'Bangar' },
                { code: 'BAU', name: 'Bauang' },
                { code: 'BUR', name: 'Burgos' },
                { code: 'CAB', name: 'Caba' },
                { code: 'LUN', name: 'Luna' },
                { code: 'NAG', name: 'Naguilian' },
                { code: 'PUR', name: 'Pugo' },
                { code: 'ROS', name: 'Rosario' },
                { code: 'SGA', name: 'San Gabriel' },
                { code: 'SJN', name: 'San Juan' },
                { code: 'STO', name: 'Santo Tomas' },
                { code: 'SAN', name: 'Santol' },
                { code: 'SUB', name: 'Sudipen' },
                { code: 'TUB', name: 'Tubao' }
            ],
            // Region I - Pangasinan Cities/Municipalities
            'PAN': [
                { code: 'ALA', name: 'Alaminos City' },
                { code: 'DAG', name: 'Dagupan City' },
                { code: 'SFC', name: 'San Carlos City' },
                { code: 'URD', name: 'Urdaneta City' },
                { code: 'AGN', name: 'Agno' },
                { code: 'AGU', name: 'Aguilar' },
                { code: 'ALC', name: 'Alcala' },
                { code: 'AND', name: 'Anda' },
                { code: 'ASI', name: 'Asingan' },
                { code: 'BAL', name: 'Balungao' },
                { code: 'BAN', name: 'Bani' },
                { code: 'BAS', name: 'Basista' },
                { code: 'BAU', name: 'Bautista' },
                { code: 'BAY', name: 'Bayambang' },
                { code: 'BIN', name: 'Binalonan' },
                { code: 'BMA', name: 'Binmaley' },
                { code: 'BOL', name: 'Bolinao' },
                { code: 'BUG', name: 'Bugallon' },
                { code: 'BUR', name: 'Burgos' },
                { code: 'CAL', name: 'Calasiao' },
                { code: 'DAS', name: 'Dasol' },
                { code: 'INF', name: 'Infanta' },
                { code: 'LAB', name: 'Labrador' },
                { code: 'LIN', name: 'Lingayen' },
                { code: 'MAB', name: 'Mabini' },
                { code: 'MAL', name: 'Malasiqui' },
                { code: 'MAN', name: 'Manaoag' },
                { code: 'MAG', name: 'Mangaldan' },
                { code: 'MAT', name: 'Mangatarem' },
                { code: 'MAP', name: 'Mapandan' },
                { code: 'NAT', name: 'Natividad' },
                { code: 'PEX', name: 'Pozorrubio' },
                { code: 'ROS', name: 'Rosales' },
                { code: 'SFA', name: 'San Fabian' },
                { code: 'SJA', name: 'San Jacinto' },
                { code: 'SMA', name: 'San Manuel' },
                { code: 'SNI', name: 'San Nicolas' },
                { code: 'SQU', name: 'San Quintin' },
                { code: 'SBA', name: 'Santa Barbara' },
                { code: 'SMA', name: 'Santa Maria' },
                { code: 'SIS', name: 'Sison' },
                { code: 'SUL', name: 'Sual' },
                { code: 'TAY', name: 'Tayug' },
                { code: 'UMI', name: 'Umingan' },
                { code: 'URB', name: 'Urbiztondo' },
                { code: 'VIL', name: 'Villasis' }
            ],
            // Region VII - Cebu Cities/Municipalities
            'CEB': [
                { code: 'CEB', name: 'Cebu City' },
                { code: 'LAP', name: 'Lapu-Lapu City' },
                { code: 'MAN', name: 'Mandaue City' },
                { code: 'TOL', name: 'Toledo City' },
                { code: 'TAS', name: 'Talisay City' },
                { code: 'NAG', name: 'Naga City' },
                { code: 'CAR', name: 'Carcar City' },
                { code: 'DAN', name: 'Danao City' },
                { code: 'BOG', name: 'Bogo City' },
                { code: 'ALC', name: 'Alcantara' },
                { code: 'ALC', name: 'Alcoy' },
                { code: 'ALO', name: 'Aloguinsan' },
                { code: 'ARG', name: 'Argao' },
                { code: 'AST', name: 'Asturias' },
                { code: 'BAD', name: 'Badian' },
                { code: 'BAL', name: 'Balamban' },
                { code: 'BAN', name: 'Bantayan' },
                { code: 'BAR', name: 'Barili' },
                { code: 'BOL', name: 'Boljoon' },
                { code: 'BOR', name: 'Borbon' },
                { code: 'CAR', name: 'Carmen' },
                { code: 'CAT', name: 'Catmon' },
                { code: 'COM', name: 'Compostela' },
                { code: 'CON', name: 'Consolacion' },
                { code: 'COR', name: 'Cordova' },
                { code: 'DAA', name: 'Daanbantayan' },
                { code: 'DAL', name: 'Dalaguete' },
                { code: 'DUM', name: 'Dumanjug' },
                { code: 'GIN', name: 'Ginatilan' },
                { code: 'LIL', name: 'Liloan' },
                { code: 'MAD', name: 'Madridejos' },
                { code: 'MAL', name: 'Malabuyoc' },
                { code: 'MED', name: 'Medellin' },
                { code: 'MIN', name: 'Minglanilla' },
                { code: 'MOA', name: 'Moalboal' },
                { code: 'OPO', name: 'Oslob' },
                { code: 'PIL', name: 'Pilar' },
                { code: 'PIN', name: 'Pinamungajan' },
                { code: 'POR', name: 'Poro' },
                { code: 'RON', name: 'Ronda' },
                { code: 'SAM', name: 'Samboan' },
                { code: 'SFE', name: 'San Fernando' },
                { code: 'SFR', name: 'San Francisco' },
                { code: 'SRE', name: 'San Remigio' },
                { code: 'SCA', name: 'Santa Fe' },
                { code: 'SAN', name: 'Santander' },
                { code: 'SIB', name: 'Sibonga' },
                { code: 'SOG', name: 'Sogod' },
                { code: 'TAB', name: 'Tabogon' },
                { code: 'TAU', name: 'Tabuelan' },
                { code: 'TUD', name: 'Tuburan' },
                { code: 'TUT', name: 'Tudela' }
            ],
            // Region XI - Davao del Sur Cities/Municipalities
            'DVS': [
                { code: 'DAV', name: 'Davao City' },
                { code: 'DIG', name: 'Digos City' },
                { code: 'BAN', name: 'Bansalan' },
                { code: 'HAG', name: 'Hagonoy' },
                { code: 'KIB', name: 'Kiblawan' },
                { code: 'MAG', name: 'Magsaysay' },
                { code: 'MAL', name: 'Malalag' },
                { code: 'MAT', name: 'Matanao' },
                { code: 'PAD', name: 'Padada' },
                { code: 'STC', name: 'Santa Cruz' },
                { code: 'SUL', name: 'Sulop' }
            ],
            // CAR - Benguet Cities/Municipalities  
            'BEN': [
                { code: 'BAG', name: 'Baguio City' },
                { code: 'ATK', name: 'Atok' },
                { code: 'BAK', name: 'Bakun' },
                { code: 'BOK', name: 'Bokod' },
                { code: 'BUG', name: 'Buguias' },
                { code: 'ITA', name: 'Itogon' },
                { code: 'KAB', name: 'Kabayan' },
                { code: 'KAP', name: 'Kapangan' },
                { code: 'KIB', name: 'Kibungan' },
                { code: 'LAT', name: 'La Trinidad' },
                { code: 'MAN', name: 'Mankayan' },
                { code: 'SAB', name: 'Sablan' },
                { code: 'TOB', name: 'Tuba' },
                { code: 'TUB', name: 'Tublay' }
            ]
        },
        barangays: {
            // Pampanga Municipalities Barangays
            'ANG': ['Agapito del Rosario', 'Amsic', 'Anunas', 'Balibago', 'Capaya', 'Claro M. Recto', 'Cuayan', 'Cutcut', 'Cutud', 'Lourdes North West', 'Lourdes Sur', 'Lourdes Sur East', 'Malabanias', 'Margot', 'Mining', 'Ninoy Aquino', 'Pampang', 'Pandan', 'Pulungbulu', 'Pulung Cacutud', 'Pulung Maragul', 'Salapungan', 'San Jose', 'San Nicolas', 'Santa Teresita', 'Santa Trinidad', 'Santo Cristo', 'Santo Domingo', 'Santo Rosario', 'Sapalibutad', 'Sapangbato', 'Tabun', 'Virgen Delos Remedios'],
            'SFC': ['Alasas', 'Baliti', 'Bulaon', 'Calulut', 'Dela Paz Norte', 'Dela Paz Sur', 'Del Carmen', 'Del Pilar', 'Del Rosario', 'Dolores', 'Juliana', 'Lara', 'Lourdes', 'Magliman', 'Maimpis', 'Malino', 'Malpitic', 'Pandaras', 'Panipuan', 'Pulung Bulu', 'Quebiawan', 'Saguin', 'San Agustin', 'San Felipe', 'San Isidro', 'San Jose', 'San Juan', 'San Nicolas', 'San Pedro', 'Santa Lucia', 'Santa Teresita', 'Santo Niño', 'Santo Rosario', 'Sindalan', 'Telabastagan'],
            'MAC': ['Atlu-Bola', 'Bical', 'Bundagul', 'Cacutud', 'Calumpang', 'Camachiles', 'Dapdap', 'Dau', 'Dolores', 'Duquit', 'Lakandula', 'Mabiga', 'Macapagal Village', 'Mamatitang', 'Mangalit', 'Marcos Village', 'Mawaque', 'Paralayunan', 'Poblacion', 'San Francisco', 'San Joaquin', 'Santa Ines', 'Santa Maria', 'Santo Rosario', 'Sapang Balen', 'Sapang Biabas', 'Tabun'],
            'APA': ['Balucuc', 'Calantipe', 'Cansinala', 'Capalangan', 'Colgante', 'Paligui', 'Sampaloc', 'San Juan', 'San Vicente', 'Sucad', 'Sulipan', 'Tabuyuc'],
            'ARA': ['Arenas', 'Baliti', 'Batasan', 'Buensuceso', 'Candating', 'Gatiawin', 'Guemasan', 'La Paz', 'Lacmit', 'Lacquios', 'Mangga-Cacutud', 'Mapalad', 'Palinlang', 'Paralaya', 'Plazang Luma', 'Poblacion', 'San Agustin Norte', 'San Agustin Sur', 'San Antonio', 'San Jose Mesulo', 'San Juan Bano', 'San Mateo', 'San Nicolas', 'San Roque Bitas', 'Santa Lucia', 'Santa Maria', 'Santo Niño Tabuan', 'Suclayin', 'Telapayong'],
            'BAC': ['Balas', 'Cabalantian', 'Cabambangan', 'Cabetican', 'Calibutbut', 'Concepcion', 'Duat', 'Macabacle', 'Magliman', 'Maliwalu', 'Mesalipit', 'Parulog', 'Potrero', 'San Antonio', 'San Isidro', 'San Vicente', 'Santa Barbara', 'Santa Ines', 'Talba', 'Tinajero'],
            'CAN': ['Bahay Pare', 'Bambang', 'Barit', 'Buas', 'Cuayang Bugtong', 'Dalayap', 'Dulong Ilog', 'Gulap', 'Lanang', 'Lourdes', 'Magumbali', 'Mandasig', 'Mandili', 'Mangga', 'Mapaniqui', 'Paligui', 'Pangclara', 'Pansinao', 'Paralaya', 'Pasig', 'Pescadores', 'Pulong Gubat', 'Pulong Palazan', 'Salapungan', 'San Agustin', 'Santo Niño', 'Tagulod', 'Talang', 'Tenejero', 'Vizal San Pablo', 'Vizal Santo Cristo', 'Vizal Santo Niño'],
            'FLO': ['Anon', 'Apalit', 'Basa Air Base', 'Benedicto', 'Bodega', 'Cabangcalan', 'Calantas', 'Carmencita', 'Consuelo', 'Dampe', 'Del Carmen', 'Fortuna', 'Gutad', 'Mabical', 'Malabo', 'Maligaya', 'Nabuclod', 'Pabanlag', 'Paguiruan', 'Palmayo', 'Pandaguirig', 'Poblacion', 'San Antonio', 'San Isidro', 'San Jose', 'San Nicolas', 'San Pedro', 'San Ramon', 'San Roque', 'Santa Monica', 'Solib', 'Valdez', 'Mawacat'],
            'GUA': ['Ascomo', 'Bancal', 'Jose Abad Santos', 'Lambac', 'Maquiapo', 'Natividad', 'Plaza Burgos', 'Pulungmasle', 'Rizal', 'San Agustin', 'San Antonio', 'San Isidro', 'San Jose', 'San Juan Bautista', 'San Juan Nepomuceno', 'San Matias', 'San Miguel', 'San Nicolas', 'San Pablo', 'San Pedro', 'San Rafael', 'San Roque', 'San Vicente', 'Santa Filomena', 'Santa Ines', 'Santa Ursula', 'Santiago', 'Santo Cristo', 'Santo Niño', 'Santo Tomas'],
            'LUB': ['Balantacan', 'Bancal Pugad', 'Bancal Sinubli', 'Baruya', 'Calangain', 'Concepcion', 'Del Carmen', 'De La Paz', 'Dolores', 'Lourdes', 'Prado Siongco', 'Remedios', 'San Agustin', 'San Antonio', 'San Francisco', 'San Isidro', 'San Jose Apunan', 'San Jose Gumi', 'San Juan', 'San Matias', 'San Miguel', 'San Nicolas', 'San Pablo', 'San Pedro Palcarangan', 'San Pedro Saug', 'San Rafael', 'San Roque Arbol', 'San Roque Dau', 'San Vicente', 'Santa Barbara', 'Santa Catalina', 'Santa Cruz', 'Santa Lucia', 'Santa Maria', 'Santa Monica', 'Santa Rita', 'Santa Teresa', 'Santiago', 'Santo Cristo', 'Santo Domingo', 'Santo Niño', 'Santo Tomas'],
            'MAG': ['Ayala', 'Balitucan', 'Camias', 'Dolores', 'Escaler', 'La Paz', 'Navaling', 'San Agustin', 'San Antonio', 'San Francisco', 'San Ildefonso', 'San Isidro', 'San Jose', 'San Miguel', 'San Nicolas I', 'San Nicolas II', 'San Pablo', 'San Pedro', 'San Roque', 'San Vicente', 'Santa Cruz', 'Santa Lucia', 'Santa Maria', 'Santo Niño', 'Santo Rosario', 'Turu'],
            'MAS': ['Alauli', 'Bagang', 'Balibago', 'Bebe Anac', 'Bebe Matua', 'Bulacus', 'Cambasi', 'Malauli', 'Nigui', 'Palimpe', 'Puti', 'Sagrada', 'San Isidro', 'San Nicolas', 'San Pedro', 'Santa Lucia', 'Santa Monica', 'Santo Niño', 'Sapang Kawayan', 'Sua'],
            'MEX': ['Acli', 'Anao', 'Balas', 'Bical', 'Buenavista', 'Camuning', 'Cawayan', 'Concepcion', 'Culubasa', 'Divisoria', 'Dolores', 'Eden', 'Gandus', 'Lagundi', 'Laput', 'Laug', 'Masamat', 'Masangsang', 'Nueva Victoria', 'Pandacaqui', 'Pangatlan', 'Panipuan', 'Parian', 'Sabanilla', 'San Antonio', 'San Carlos', 'San Jose Malino', 'San Jose Matulid', 'San Juan', 'San Lorenzo', 'San Miguel', 'San Nicolas', 'San Pablo', 'San Patricio', 'San Rafael', 'San Roque', 'San Vicente', 'Santa Cruz', 'Santa Maria', 'Santo Domingo', 'Santo Rosario', 'Sapang Maisac', 'Suclaban', 'Tangle'],
            'MIN': ['Bulac', 'Dawe', 'Lourdes', 'Maniango', 'San Francisco I', 'San Francisco II', 'San Isidro', 'San Nicolas', 'Santa Catalina', 'Santa Maria', 'Santa Rita', 'Santo Domingo', 'Santo Rosario', 'Sapang Maragul'],
            'POC': ['Anunas', 'Balubad', 'Banbad', 'Camias', 'Cangatba', 'Diaz', 'Dolores', 'Hacienda Dolores', 'Jalung', 'Mancatian', 'Manibaug Libutad', 'Manibaug Paralaya', 'Manibaug Pasig', 'Manuali', 'Mitla Proper', 'Palat', 'Pias', 'Pio', 'Planas', 'Poblacion', 'Pulung Santol', 'Salu', 'San Jose Mitla', 'Santa Cruz', 'Sapang Bato', 'Sapang Uwak', 'Sepung Bulaun', 'Villa Maria'],
            'SAG': ['San Agustin', 'San Carlos', 'San Isidro', 'San Jose', 'San Juan', 'San Nicolas', 'San Roque', 'San Sebastian', 'Santa Catalina', 'Santa Cruz Pambilog', 'Santa Cruz Poblacion', 'Santa Lucia', 'Santa Monica', 'Santa Rita', 'Santo Niño', 'Santo Rosario', 'Santo Tomas'],
            'SAN': ['Concepcion', 'De La Paz', 'San Agustin', 'San Isidro', 'San Jose', 'San Juan', 'San Miguel', 'San Nicolas', 'San Pablo Libutad', 'San Pablo Proper', 'San Pedro', 'Santa Cruz', 'Santa Monica', 'Santo Niño'],
            'SAS': ['San Agustin', 'San Bartolome', 'San Isidro', 'San Joaquin', 'San Jose', 'San Juan', 'San Nicolas', 'San Pablo', 'San Pedro', 'San Roque', 'Santa Lucia', 'Santa Maria', 'Santo Rosario'],
            'SRT': ['Becuran', 'Dila-Dila', 'San Agustin', 'San Basilio', 'San Isidro', 'San Jose', 'San Juan', 'San Matias', 'San Vicente', 'Santa Monica'],
            'SAC': ['Moras De La Paz', 'Poblacion', 'San Bartolome', 'San Matias', 'San Vicente', 'Santo Rosario', 'Sapa'],
            'SAM': ['Batang I', 'Batang II', 'Mabuanbuan', 'Malusac', 'Santa Lucia', 'Santo Tomas'],
            // Metro Manila Cities Barangays
            'QC': ['Alicia', 'Amihan', 'Apolonio Samson', 'Aurora', 'Baesa', 'Bagbag', 'Bagong Lipunan ng Crame', 'Bagong Pag-asa', 'Bagong Silangan', 'Bahay Toro', 'Balingasa', 'Balintawak', 'Balumbato', 'Batasan Hills', 'Bayanihan', 'Blue Ridge A', 'Blue Ridge B', 'Botocan', 'Bungad', 'Camp Aguinaldo', 'Capitol Hills', 'Central', 'Claro', 'Commonwealth', 'Culiat', 'Damar', 'Damayan', 'Damayang Lagi', 'Del Monte', 'Diliman', 'Dioquino Zobel', 'Don Manuel', 'Doña Aurora', 'Doña Imelda', 'Doña Josefa', 'Duyan-duyan', 'E. Rodriguez', 'East Kamias', 'Escopa I', 'Escopa II', 'Escopa III', 'Escopa IV', 'Fairview', 'Greater Lagro', 'Gulod', 'Holy Spirit', 'Horseshoe', 'Immaculate Concepcion', 'Kaligayahan', 'Kalusugan', 'Kamuning', 'Katipunan', 'Kaunlaran', 'Kristong Hari', 'Krus na Ligas', 'Laging Handa', 'Libis', 'Lourdes', 'Loyola Heights', 'Maharlika', 'Malaya', 'Mangga', 'Manresa', 'Mariana', 'Mariblo', 'Marilag', 'Masagana', 'Masambong', 'Matandang Balara', 'Milagrosa', 'N.S. Amoranto', 'Nagkaisang Nayon', 'Nayong Kanluran', 'New Era', 'North Fairview', 'Novaliches Proper', 'Obrero', 'Old Capitol Site', 'Paang Bundok', 'Pag-ibig sa Nayon', 'Paligsahan', 'Paltok', 'Pansol', 'Paraiso', 'Pasong Putik Proper', 'Pasong Tamo', 'Payatas', 'Phil-Am', 'Pinagkaisahan', 'Pinyahan', 'Project 6', 'Ramon Magsaysay', 'Roxas', 'Sacred Heart', 'San Agustin', 'San Antonio', 'San Bartolome', 'San Isidro', 'San Isidro Labrador', 'San Jose', 'San Martin de Porres', 'San Roque', 'San Vicente', 'Sangandaan', 'Santa Cruz', 'Santa Lucia', 'Santa Monica', 'Santa Teresita', 'Santo Cristo', 'Santo Niño', 'Santol', 'Sauyo', 'Sienna', 'Sikatuna Village', 'Silangan', 'Socorro', 'South Triangle', 'Tagumpay', 'Talayan', 'Talipapa', 'Tandang Sora', 'Tatalon', 'Teachers Village East', 'Teachers Village West', 'Ugong Norte', 'Unang Sigaw', 'UP Campus', 'UP Village', 'Valencia', 'Vasra', 'Veterans Village', 'Villa Maria Clara', 'West Kamias', 'West Triangle', 'White Plains'],
            'MNL': ['Binondo', 'Ermita', 'Intramuros', 'Malate', 'Paco', 'Pandacan', 'Port Area', 'Quiapo', 'Sampaloc', 'San Andres', 'San Miguel', 'San Nicolas', 'Santa Ana', 'Santa Cruz', 'Santa Mesa', 'Tondo'],
            'MAK': ['Bangkal', 'Bel-Air', 'Carmona', 'Cembo', 'Comembo', 'Dasmariñas', 'East Rembo', 'Forbes Park', 'Guadalupe Nuevo', 'Guadalupe Viejo', 'Kasilawan', 'La Paz', 'Magallanes', 'Olympia', 'Palanan', 'Pembo', 'Pinagkaisahan', 'Pio del Pilar', 'Pitogo', 'Poblacion', 'Post Proper Northside', 'Post Proper Southside', 'Rizal', 'San Antonio', 'San Isidro', 'San Lorenzo', 'Santa Cruz', 'Singkamas', 'South Cembo', 'Tejeros', 'Urdaneta', 'Valenzuela', 'West Rembo'],
            'CAL': ['Barangay 1', 'Barangay 2', 'Barangay 3', 'Barangay 4', 'Barangay 5', 'Barangay 6', 'Barangay 7', 'Barangay 8', 'Barangay 9', 'Barangay 10', 'Bagong Silang', 'Bagumbong', 'Camarin', 'Deparo', 'Llano', 'Amparo', 'Marulas', 'Parada', 'Potrero', 'Tala', 'Talipapa'],
            'LPC': ['Almanza Dos', 'Almanza Uno', 'B.F. International Village', 'CAA/BF International', 'Daniel Fajardo', 'Elias Aldana', 'Ilaya', 'Manuyo Dos', 'Manuyo Uno', 'Pamplona Dos', 'Pamplona Tres', 'Pamplona Uno', 'Pilar Village', 'Pulang Lupa Dos', 'Pulang Lupa Uno', 'Talon Dos', 'Talon Kuatro', 'Talon Singko', 'Talon Tres', 'Talon Uno', 'Zapote'],
            'MAL': ['Acacia', 'Baritan', 'Bayan-bayanan', 'Catmon', 'Concepcion', 'Dampalit', 'Flores', 'Hulong Duhat', 'Ibaba', 'Longos', 'Maysilo', 'Muzon', 'Niugan', 'Panghulo', 'Potrero', 'San Agustin', 'Santolan', 'Tañong', 'Tinajeros', 'Tonsuya', 'Tugatog'],
            'MAN': ['Addition Hills', 'Bagong Silang', 'Barangka Drive', 'Barangka Ibaba', 'Barangka Ilaya', 'Barangka Itaas', 'Buayang Bato', 'Burol', 'Daang Bakal', 'Hagdang Bato Itaas', 'Hagdang Bato Libis', 'Harapin Ang Bukas', 'Highway Hills', 'Hulo', 'Mabini-J. Rizal', 'Malamig', 'Mauway', 'Namayan', 'New Zañiga', 'Old Zañiga', 'Pag-asa', 'Plainview', 'Pleasant Hills', 'Poblacion', 'San Jose', 'Vergara', 'Wack-Wack Greenhills'],
            'MAR': ['Barangka', 'Calumpang', 'Concepcion Dos', 'Concepcion Uno', 'Fortune', 'Industrial Valley', 'Jesus de la Peña', 'Malanday', 'Marikina Heights', 'Nangka', 'Parang', 'San Roque', 'Santa Elena', 'Santo Niño', 'Tañong', 'Tumana'],
            'MUN': ['Alabang', 'Ayala Alabang', 'Bayanan', 'Buli', 'Cupang', 'New Alabang Village', 'Poblacion', 'Putatan', 'Sucat', 'Tunasan'],
            'NAV': ['Bagumbayan North', 'Bagumbayan South', 'Bangculasi', 'Daanghari', 'Navotas East', 'Navotas West', 'North Bay Boulevard North', 'North Bay Boulevard South', 'San Jose', 'San Rafael Village', 'San Roque', 'Sipac-Almacen', 'Tangos North', 'Tangos South', 'Tanza'],
            'PAR': ['Baclaran', 'BF Homes', 'Don Bosco', 'Don Galo', 'La Huerta', 'Marcelo Green Village', 'Merville', 'Moonwalk', 'San Antonio', 'San Dionisio', 'San Isidro', 'San Martin de Porres', 'Santo Niño', 'Sun Valley', 'Tambo', 'Vitalez'],
            'PAS': ['Barangay 1', 'Barangay 2', 'Barangay 3', 'Barangay 4', 'Barangay 5', 'Barangay 6', 'Barangay 7', 'Barangay 8', 'Barangay 9', 'Barangay 10', 'Barangay 11', 'Barangay 12', 'Barangay 13', 'Barangay 14', 'Barangay 15', 'Barangay 16', 'Barangay 17', 'Barangay 18', 'Barangay 19', 'Barangay 20', 'Barangay 21', 'Barangay 22', 'Barangay 23', 'Barangay 24', 'Barangay 25', 'Barangay 26', 'Barangay 27', 'Barangay 28', 'Barangay 29', 'Barangay 30', 'Barangay 31', 'Barangay 32', 'Barangay 33', 'Barangay 34', 'Barangay 35', 'Barangay 36', 'Barangay 37', 'Barangay 38', 'Barangay 39', 'Barangay 40', 'Barangay 41', 'Barangay 42', 'Barangay 43', 'Barangay 44', 'Barangay 45', 'Barangay 46', 'Barangay 47', 'Barangay 48', 'Barangay 49', 'Barangay 50', 'Barangay 51', 'Barangay 52', 'Barangay 53', 'Barangay 54', 'Barangay 55', 'Barangay 56', 'Barangay 57', 'Barangay 58', 'Barangay 59', 'Barangay 60', 'Barangay 61', 'Barangay 62', 'Barangay 63', 'Barangay 64', 'Barangay 65', 'Barangay 66', 'Barangay 67', 'Barangay 68', 'Barangay 69', 'Barangay 70', 'Barangay 71', 'Barangay 72', 'Barangay 73', 'Barangay 74', 'Barangay 75', 'Barangay 76'],
            'PSG': ['Bagong Ilog', 'Bagong Katipunan', 'Bambang', 'Buting', 'Caniogan', 'Dela Paz', 'Kalawaan', 'Kapasigan', 'Kapitolyo', 'Malinao', 'Manggahan', 'Maybunga', 'Oranbo', 'Palatiw', 'Pinagbuhatan', 'Pineda', 'Rosario', 'Sagad', 'San Antonio', 'San Joaquin', 'San Jose', 'San Miguel', 'San Nicolas', 'Santa Cruz', 'Santa Lucia', 'Santa Rosa', 'Santo Tomas', 'Santolan', 'Sumilang', 'Ugong'],
            'PAT': ['Aguho', 'Magtanggol', 'Martires del 96', 'Poblacion', 'San Pedro', 'San Roque', 'Santa Ana', 'Santo Rosario', 'Tabacalera'],
            'SMJ': ['Addition Hills', 'Balong-Bato', 'Batis', 'Corazon de Jesus', 'Ermitaño', 'Greenhills', 'Halo-Halo', 'Isabelita', 'Kabayanan', 'Little Baguio', 'Maytunas', 'Onse', 'Pasadena', 'Pedro Cruz', 'Progreso', 'Rivera', 'Salapan', 'San Perfecto', 'Santa Lucia', 'Tibagan', 'West Crame'],
            'TAG': ['Bagumbayan', 'Bambang', 'Calzada', 'Central Bicutan', 'Central Signal Village', 'Fort Bonifacio', 'Hagonoy', 'Ibayo-Tipas', 'Katuparan', 'Ligid-Tipas', 'Lower Bicutan', 'Maharlika Village', 'Napindan', 'New Lower Bicutan', 'North Daang Hari', 'North Signal Village', 'Palingon', 'Pinagsama', 'San Miguel', 'Santa Ana', 'South Daang Hari', 'South Signal Village', 'Tanyag', 'Tuktukan', 'Upper Bicutan', 'Ususan', 'Wawa', 'Western Bicutan'],
            'VAL': ['Arkong Bato', 'Balangkas', 'Bignay', 'Bisig', 'Canumay East', 'Canumay West', 'Coloong', 'Dalandanan', 'Gen. T. de Leon', 'Isla', 'Karuhatan', 'Lawang Bato', 'Lingunan', 'Mabolo', 'Malanday', 'Malinta', 'Mapulang Lupa', 'Marulas', 'Maysan', 'Palasan', 'Parada', 'Pariancillo Villa', 'Paso de Blas', 'Pasolo', 'Poblacion', 'Polo', 'Punturin', 'Rincon', 'Tagalag', 'Ugong', 'Viente Reales', 'Wawang Pulo'],
            // Cebu City Barangays
            'CEB': ['Adlaon', 'Agsungot', 'Apas', 'Babag', 'Bacayan', 'Banilad', 'Basak Pardo', 'Basak San Nicolas', 'Binaliw', 'Bonbon', 'Budla-an', 'Buhisan', 'Bulacao', 'Buot-Taup Pardo', 'Busay', 'Calamba', 'Cambinocot', 'Capitol Site', 'Carreta', 'Cogon Pardo', 'Cogon Ramos', 'Day-as', 'Duljo-Fatima', 'Ermita', 'Guadalupe', 'Guba', 'Hipodromo', 'Inayawan', 'Kalubihan', 'Kalunasan', 'Kamagayan', 'Kamputhaw', 'Kasambagan', 'Kinasang-an Pardo', 'Labangon', 'Lahug', 'Lorega San Miguel', 'Lusaran', 'Luz', 'Mabini', 'Mabolo', 'Malubog', 'Mambaling', 'Pahina Central', 'Pahina San Nicolas', 'Pamutan', 'Pardo', 'Pari-an', 'Paril', 'Pasil', 'Pit-os', 'Poblacion Pardo', 'Pulangbato', 'Pung-ol-Sibugay', 'Punta Princesa', 'Quiot Pardo', 'Sambag I', 'Sambag II', 'San Antonio', 'San Jose', 'San Nicolas Central', 'San Roque', 'Santa Cruz', 'Sawang Calero', 'Sinsin', 'Sirao', 'Suba', 'Sudlon I', 'Sudlon II', 'T. Padilla', 'Tabunan', 'Tagbao', 'Talamban', 'Taptap', 'Tejero', 'Tinago', 'Tisa', 'To-ong Pardo', 'Zapatera'],
            // Davao City Barangays
            'DAV': ['Acacia', 'Agdao', 'Alambre', 'Alejandra Navarro', 'Alfonso Angliongto Sr.', 'Angalan', 'Atan-Awe', 'Baganihan', 'Bago Aplaya', 'Bago Gallera', 'Bago Oshiro', 'Baguio District', 'Balengaeng', 'Baliok', 'Bangkas Heights', 'Bantol', 'Baracatan', 'Bato', 'Bayabas', 'Biao Escuela', 'Biao Guianga', 'Biao Joaquin', 'Binugao', 'Bucana', 'Buda', 'Buhangin Proper', 'Bunawan Proper', 'Cabantian', 'Cadalian', 'Calinan Proper', 'Callawa', 'Camansi', 'Carmen', 'Catalunan Grande', 'Catalunan Pequeño', 'Catigan', 'Cawayan', 'Centro', 'Colosas', 'Communal', 'Crossing Bayabas', 'Dacudao', 'Dalag', 'Daliao', 'Daliaon Plantation', 'Datu Salumay', 'Dominga', 'Dumoy', 'Eden', 'Fatima', 'Gatungan', 'Gov. Paciano Bangoy', 'Gov. Vicente Duterte', 'Gumalang', 'Gumitan', 'Ilang', 'Indangan', 'Kap. Tomas Monteverde Sr.', 'Kilate', 'Lacson', 'Lamanan', 'Lampianao', 'Langub', 'Lapu-Lapu', 'Leon Garcia Sr.', 'Lizada', 'Los Amigos', 'Lubogan', 'Lumiad', 'Ma-a', 'Mabuhay', 'Magsaysay', 'Magtuod', 'Mahayag', 'Malabog', 'Malagos', 'Malamba', 'Manambulan', 'Mandug', 'Manuel Guianga', 'Mapula', 'Marapangi', 'Marilog Proper', 'Matina Aplaya', 'Matina Biao', 'Matina Crossing', 'Matina Pangi', 'Megkawayan', 'Mintal', 'Mudiang', 'Mulig', 'New Carmen', 'New Valencia', 'Pampanga', 'Panacan', 'Pangyan', 'Paquibato Proper', 'Paradise Embak', 'PNR', 'Poblacion District', 'Riverside', 'Salapawan', 'Salaysay', 'Saloy', 'San Antonio', 'San Isidro', 'Santo Niño', 'Sasa', 'Sibulan', 'Sirawan', 'Sirib', 'Suawan', 'Subasta', 'Sumimao', 'Tacunan', 'Tagakpan', 'Tagluno', 'Tagurano', 'Talandang', 'Talomo Proper', 'Talomo River', 'Tamayong', 'Tamugan', 'Tapak', 'Tawan-tawan', 'Tibuloy', 'Tibungco', 'Tigatto', 'Toril Proper', 'Tugbok Proper', 'Tungakalan', 'Ubalde', 'Ula', 'Vicente Hizon Sr.', 'Waan', 'Wines'],
            // Default fallback
            'default': ['Poblacion', 'Centro', 'Barangay 1', 'Barangay 2', 'Barangay 3', 'Barangay 4', 'Barangay 5']
        }
    };

    // Initialize Place of Birth Dropdowns
    document.addEventListener('DOMContentLoaded', function() {
        loadRegions();
        loadAddrRegions();
    });

    function loadRegions() {
        const regionSelect = document.getElementById('pobRegion');
        regionSelect.innerHTML = '<option value="">Select Region</option>';
        
        philippineData.regions.forEach(region => {
            const option = document.createElement('option');
            option.value = region.code;
            option.textContent = region.name;
            regionSelect.appendChild(option);
        });
    }

    // Address dropdown functions
    function loadAddrRegions() {
        const regionSelect = document.getElementById('addrRegion');
        regionSelect.innerHTML = '<option value="">Select Region</option>';
        
        philippineData.regions.forEach(region => {
            const option = document.createElement('option');
            option.value = region.code;
            option.textContent = region.name;
            regionSelect.appendChild(option);
        });
    }

    function loadAddrProvinces() {
        const regionCode = document.getElementById('addrRegion').value;
        const provinceSelect = document.getElementById('addrProvince');
        const citySelect = document.getElementById('addrCity');
        const barangaySelect = document.getElementById('addrBarangay');
        
        // Reset dependent dropdowns
        provinceSelect.innerHTML = '<option value="">Select Province</option>';
        citySelect.innerHTML = '<option value="">Select City/Municipality</option>';
        barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
        
        provinceSelect.disabled = true;
        citySelect.disabled = true;
        barangaySelect.disabled = true;
        
        if (regionCode && philippineData.provinces[regionCode]) {
            philippineData.provinces[regionCode].forEach(province => {
                const option = document.createElement('option');
                option.value = province.code;
                option.textContent = province.name;
                provinceSelect.appendChild(option);
            });
            provinceSelect.disabled = false;
        }
        
        updateAddress();
    }

    function loadAddrCities() {
        const provinceCode = document.getElementById('addrProvince').value;
        const citySelect = document.getElementById('addrCity');
        const barangaySelect = document.getElementById('addrBarangay');
        
        // Reset dependent dropdowns
        citySelect.innerHTML = '<option value="">Select City/Municipality</option>';
        barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
        
        citySelect.disabled = true;
        barangaySelect.disabled = true;
        
        if (provinceCode && philippineData.cities[provinceCode]) {
            philippineData.cities[provinceCode].forEach(city => {
                const option = document.createElement('option');
                option.value = city.code;
                option.textContent = city.name;
                citySelect.appendChild(option);
            });
            citySelect.disabled = false;
        }
        
        updateAddress();
    }

    function loadAddrBarangays() {
        const cityCode = document.getElementById('addrCity').value;
        const barangaySelect = document.getElementById('addrBarangay');
        
        // Reset barangay dropdown
        barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
        barangaySelect.disabled = true;
        
        // Get barangays for selected city or use default
        const barangays = philippineData.barangays[cityCode] || philippineData.barangays['default'];
        
        if (cityCode) {
            barangays.forEach(barangay => {
                const option = document.createElement('option');
                option.value = barangay;
                option.textContent = barangay;
                barangaySelect.appendChild(option);
            });
            barangaySelect.disabled = false;
        }
        
        updateAddress();
    }

    function updateAddress() {
        const street = document.getElementById('addrStreet')?.value || '';
        const purok = document.getElementById('addrPurok')?.value || '';
        const barangay = document.getElementById('addrBarangay');
        const city = document.getElementById('addrCity');
        const province = document.getElementById('addrProvince');
        const region = document.getElementById('addrRegion');
        
        const parts = [];
        
        // Add street number first
        if (street.trim()) {
            parts.push(street.trim());
        }
        
        // Add purok/sitio
        if (purok.trim()) {
            parts.push(purok.trim());
        }
        
        // Add barangay
        if (barangay && barangay.value) {
            parts.push('Brgy. ' + barangay.value);
        }
        
        // Add city
        if (city && city.value) {
            const cityText = city.options[city.selectedIndex]?.text;
            if (cityText) parts.push(cityText);
        }
        
        // Add province
        if (province && province.value) {
            const provinceText = province.options[province.selectedIndex]?.text;
            if (provinceText) parts.push(provinceText);
        }
        
        document.getElementById('completeAddress').value = parts.join(', ');
    }

    function loadProvinces() {
        const regionCode = document.getElementById('pobRegion').value;
        const provinceSelect = document.getElementById('pobProvince');
        const citySelect = document.getElementById('pobCity');
        const barangaySelect = document.getElementById('pobBarangay');
        
        // Reset dependent dropdowns
        provinceSelect.innerHTML = '<option value="">Select Province</option>';
        citySelect.innerHTML = '<option value="">Select City/Municipality</option>';
        barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
        
        provinceSelect.disabled = true;
        citySelect.disabled = true;
        barangaySelect.disabled = true;
        
        if (regionCode && philippineData.provinces[regionCode]) {
            philippineData.provinces[regionCode].forEach(province => {
                const option = document.createElement('option');
                option.value = province.code;
                option.textContent = province.name;
                provinceSelect.appendChild(option);
            });
            provinceSelect.disabled = false;
        }
        
        updatePlaceOfBirth();
    }

    function loadCities() {
        const provinceCode = document.getElementById('pobProvince').value;
        const citySelect = document.getElementById('pobCity');
        const barangaySelect = document.getElementById('pobBarangay');
        
        // Reset dependent dropdowns
        citySelect.innerHTML = '<option value="">Select City/Municipality</option>';
        barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
        
        citySelect.disabled = true;
        barangaySelect.disabled = true;
        
        if (provinceCode && philippineData.cities[provinceCode]) {
            philippineData.cities[provinceCode].forEach(city => {
                const option = document.createElement('option');
                option.value = city.code;
                option.textContent = city.name;
                citySelect.appendChild(option);
            });
            citySelect.disabled = false;
        }
        
        updatePlaceOfBirth();
    }

    function loadBarangays() {
        const cityCode = document.getElementById('pobCity').value;
        const barangaySelect = document.getElementById('pobBarangay');
        
        // Reset barangay dropdown
        barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
        barangaySelect.disabled = true;
        
        // Get barangays for selected city or use default
        const barangays = philippineData.barangays[cityCode] || philippineData.barangays['default'];
        
        if (cityCode) {
            barangays.forEach(barangay => {
                const option = document.createElement('option');
                option.value = barangay;
                option.textContent = barangay;
                barangaySelect.appendChild(option);
            });
            barangaySelect.disabled = false;
        }
        
        updatePlaceOfBirth();
    }

    function updatePlaceOfBirth() {
        const region = document.getElementById('pobRegion');
        const province = document.getElementById('pobProvince');
        const city = document.getElementById('pobCity');
        const barangay = document.getElementById('pobBarangay');
        
        const parts = [];
        
        if (barangay.value) {
            parts.push(barangay.value);
        }
        if (city.value) {
            const cityText = city.options[city.selectedIndex]?.text;
            if (cityText) parts.push(cityText);
        }
        if (province.value) {
            const provinceText = province.options[province.selectedIndex]?.text;
            if (provinceText) parts.push(provinceText);
        }
        if (region.value) {
            const regionText = region.options[region.selectedIndex]?.text;
            if (regionText) parts.push(regionText);
        }
        
        document.getElementById('placeOfBirth').value = parts.join(', ');
        
        // Add event listener to barangay for final update
        barangay.addEventListener('change', function() {
            updatePlaceOfBirth();
        });
    }
</script>
