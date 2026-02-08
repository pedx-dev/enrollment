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

                <!-- Complete Address -->
                <div class="form-control mb-6">
                    <label class="label">
                        <span class="label-text font-semibold">Complete Address</span>
                    </label>
                    <textarea name="address" placeholder="Street, City, Province, Postal Code" class="textarea textarea-bordered" rows="4" required></textarea>
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

                <!-- Course Selection -->
                <div class="form-control mb-6">
                    <label class="label">
                        <span class="label-text font-semibold">Course/Program</span>
                    </label>
                    <select name="course" class="select select-bordered" required>
                        <option value="">Select course</option>
                        <?php foreach ($courses as $course): ?>
                        <option value="<?php echo htmlspecialchars($course['name']); ?>"><?php echo htmlspecialchars($course['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Year -->
                <div class="form-control mb-6">
                    <label class="label">
                        <span class="label-text font-semibold">Year Level</span>
                    </label>
                    <select name="year" class="select select-bordered" required>
                        <option value="">Select year</option>
                        <option value="First Year">First Year</option>
                        <option value="Second Year">Second Year</option>
                        <option value="Third Year">Third Year</option>
                        <option value="Fourth Year">Fourth Year</option>
                    </select>
                </div>

                <!-- Section -->
                <div class="form-control mb-6">
                    <label class="label">
                        <span class="label-text font-semibold">Section/Block</span>
                    </label>
                    <select name="section" class="select select-bordered" required>
                        <option value="">Select section</option>
                        <option value="A">Section A</option>
                        <option value="B">Section B</option>
                        <option value="C">Section C</option>
                    </select>
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
                console.log('Final step, submitting enrollment');
                submitEnrollment();
            }
        } else {
            console.log('Validation failed for step', currentStep);
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
</script>
