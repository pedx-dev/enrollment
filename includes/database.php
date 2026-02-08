<?php
/**
 * Database Helper Functions
 * All CRUD operations for the enrollment system
 */

require_once __DIR__ . '/config.php';

// ============================================
// STUDENT FUNCTIONS
// ============================================

function getAllStudents($filters = []) {
    $conn = getDbConnection();
    $sql = "SELECT * FROM students WHERE 1=1";
    $params = [];
    
    if (!empty($filters['search'])) {
        $search = '%' . $filters['search'] . '%';
        $sql .= " AND (id LIKE ? OR first_name LIKE ? OR last_name LIKE ? OR email LIKE ?)";
        $params = array_merge($params, [$search, $search, $search, $search]);
    }
    
    if (!empty($filters['course'])) {
        $sql .= " AND course = ?";
        $params[] = $filters['course'];
    }
    
    if (!empty($filters['status'])) {
        $sql .= " AND status = ?";
        $params[] = $filters['status'];
    }
    
    $sql .= " ORDER BY created_at DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function getStudentById($id) {
    $conn = getDbConnection();
    $stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function addStudent($data) {
    $conn = getDbConnection();
    
    // Generate student ID
    $studentId = generateStudentId();
    
    $sql = "INSERT INTO students (id, first_name, middle_name, last_name, date_of_birth, place_of_birth, 
            sex, civil_status, nationality, religion, address, phone, email, 
            guardian_name, guardian_phone, guardian_relationship, course, year_level, section, 
            status, enrollment_date) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'active', CURDATE())";
    
    $stmt = $conn->prepare($sql);
    $result = $stmt->execute([
        $studentId,
        $data['first_name'],
        $data['middle_name'] ?? '',
        $data['last_name'],
        $data['date_of_birth'],
        $data['place_of_birth'],
        $data['sex'],
        $data['civil_status'],
        $data['nationality'],
        $data['religion'] ?? '',
        $data['address'],
        $data['phone'],
        $data['email'],
        $data['guardian_name'],
        $data['guardian_phone'],
        $data['guardian_relationship'],
        $data['course'],
        $data['year_level'],
        $data['section']
    ]);
    
    if ($result) {
        // Increment the next student number
        incrementStudentNumber();
        return $studentId;
    }
    return false;
}

function updateStudent($id, $data) {
    $conn = getDbConnection();
    
    $sql = "UPDATE students SET 
            first_name = ?, middle_name = ?, last_name = ?,
            date_of_birth = ?, place_of_birth = ?, sex = ?, civil_status = ?,
            nationality = ?, religion = ?, address = ?, phone = ?, email = ?,
            guardian_name = ?, guardian_phone = ?, guardian_relationship = ?,
            course = ?, year_level = ?, section = ?, status = ?
            WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    return $stmt->execute([
        $data['first_name'],
        $data['middle_name'] ?? '',
        $data['last_name'],
        $data['date_of_birth'],
        $data['place_of_birth'],
        $data['sex'],
        $data['civil_status'],
        $data['nationality'],
        $data['religion'] ?? '',
        $data['address'],
        $data['phone'],
        $data['email'],
        $data['guardian_name'],
        $data['guardian_phone'],
        $data['guardian_relationship'],
        $data['course'],
        $data['year_level'],
        $data['section'],
        $data['status'] ?? 'active',
        $id
    ]);
}

function deleteStudent($id) {
    $conn = getDbConnection();
    $stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
    return $stmt->execute([$id]);
}

function getStudentStats() {
    $conn = getDbConnection();
    
    // Total students
    $stmt = $conn->query("SELECT COUNT(*) as total FROM students");
    $total = $stmt->fetch()['total'];
    
    // Active students
    $stmt = $conn->query("SELECT COUNT(*) as active FROM students WHERE status = 'active'");
    $active = $stmt->fetch()['active'];
    
    // New this month
    $stmt = $conn->query("SELECT COUNT(*) as new_this_month FROM students 
                          WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) 
                          AND YEAR(created_at) = YEAR(CURRENT_DATE())");
    $newThisMonth = $stmt->fetch()['new_this_month'];
    
    return [
        'total' => $total,
        'active' => $active,
        'newThisMonth' => $newThisMonth
    ];
}

function generateStudentId() {
    $conn = getDbConnection();
    $stmt = $conn->prepare("SELECT setting_value FROM settings WHERE setting_key = 'college_code'");
    $stmt->execute();
    $collegeCode = $stmt->fetch()['setting_value'];
    
    $stmt = $conn->prepare("SELECT setting_value FROM settings WHERE setting_key = 'next_student_number'");
    $stmt->execute();
    $nextNumber = $stmt->fetch()['setting_value'];
    
    return $collegeCode . '-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
}

function incrementStudentNumber() {
    $conn = getDbConnection();
    $stmt = $conn->prepare("UPDATE settings SET setting_value = setting_value + 1 WHERE setting_key = 'next_student_number'");
    return $stmt->execute();
}

// ============================================
// COURSE FUNCTIONS
// ============================================

function getAllCourses($filters = []) {
    $conn = getDbConnection();
    $sql = "SELECT c.*, u.name as instructor_name 
            FROM courses c 
            LEFT JOIN users u ON c.instructor_id = u.id 
            WHERE 1=1";
    $params = [];
    
    if (!empty($filters['department'])) {
        $sql .= " AND c.department = ?";
        $params[] = $filters['department'];
    }
    
    if (!empty($filters['status'])) {
        $sql .= " AND c.status = ?";
        $params[] = $filters['status'];
    }
    
    $sql .= " ORDER BY c.code ASC";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function getCourseById($id) {
    $conn = getDbConnection();
    $stmt = $conn->prepare("SELECT c.*, u.name as instructor_name 
                            FROM courses c 
                            LEFT JOIN users u ON c.instructor_id = u.id 
                            WHERE c.id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function addCourse($data) {
    $conn = getDbConnection();
    
    $sql = "INSERT INTO courses (id, code, name, department, credits, description, semester, instructor_id, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'active')";
    
    $stmt = $conn->prepare($sql);
    return $stmt->execute([
        $data['code'], // Using code as id
        $data['code'],
        $data['name'],
        $data['department'],
        $data['credits'],
        $data['description'] ?? '',
        $data['semester'] ?? '',
        $data['instructor_id'] ?? null
    ]);
}

function updateCourse($id, $data) {
    $conn = getDbConnection();
    
    $sql = "UPDATE courses SET 
            code = ?, name = ?, department = ?, credits = ?, 
            description = ?, semester = ?, instructor_id = ?, status = ?
            WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    return $stmt->execute([
        $data['code'],
        $data['name'],
        $data['department'],
        $data['credits'],
        $data['description'] ?? '',
        $data['semester'] ?? '',
        $data['instructor_id'] ?? null,
        $data['status'] ?? 'active',
        $id
    ]);
}

function deleteCourse($id) {
    $conn = getDbConnection();
    $stmt = $conn->prepare("DELETE FROM courses WHERE id = ?");
    return $stmt->execute([$id]);
}

function getCoursesByInstructor($instructorId) {
    $conn = getDbConnection();
    $stmt = $conn->prepare("SELECT * FROM courses WHERE instructor_id = ? AND status = 'active'");
    $stmt->execute([$instructorId]);
    return $stmt->fetchAll();
}

// ============================================
// TEACHER/USER FUNCTIONS
// ============================================

function getAllTeachers($filters = []) {
    $conn = getDbConnection();
    $sql = "SELECT * FROM users WHERE role = 'teacher'";
    $params = [];
    
    if (!empty($filters['search'])) {
        $search = '%' . $filters['search'] . '%';
        $sql .= " AND (name LIKE ? OR email LIKE ?)";
        $params = array_merge($params, [$search, $search]);
    }
    
    if (!empty($filters['department'])) {
        $sql .= " AND department = ?";
        $params[] = $filters['department'];
    }
    
    $sql .= " ORDER BY name ASC";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function getTeacherById($id) {
    $conn = getDbConnection();
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ? AND role = 'teacher'");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function addTeacher($data) {
    $conn = getDbConnection();
    
    $sql = "INSERT INTO users (email, password, name, role, department, specialization, phone, status) 
            VALUES (?, ?, ?, 'teacher', ?, ?, ?, 'active')";
    
    $hashedPassword = password_hash($data['password'] ?? 'password123', PASSWORD_DEFAULT);
    
    $stmt = $conn->prepare($sql);
    return $stmt->execute([
        $data['email'],
        $hashedPassword,
        $data['name'],
        $data['department'],
        $data['specialization'] ?? '',
        $data['phone'] ?? ''
    ]);
}

function updateTeacher($id, $data) {
    $conn = getDbConnection();
    
    $sql = "UPDATE users SET 
            name = ?, email = ?, department = ?, specialization = ?, phone = ?
            WHERE id = ? AND role = 'teacher'";
    
    $stmt = $conn->prepare($sql);
    return $stmt->execute([
        $data['name'],
        $data['email'],
        $data['department'],
        $data['specialization'] ?? '',
        $data['phone'] ?? '',
        $id
    ]);
}

function deleteTeacher($id) {
    $conn = getDbConnection();
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ? AND role = 'teacher'");
    return $stmt->execute([$id]);
}

// ============================================
// ENROLLMENT FUNCTIONS
// ============================================

function getAllEnrollments($filters = []) {
    $conn = getDbConnection();
    $sql = "SELECT e.*, s.first_name, s.last_name, s.email as student_email, 
                   c.code as course_code, c.name as course_name
            FROM enrollments e
            JOIN students s ON e.student_id = s.id
            JOIN courses c ON e.course_id = c.id
            WHERE 1=1";
    $params = [];
    
    if (!empty($filters['student_id'])) {
        $sql .= " AND e.student_id = ?";
        $params[] = $filters['student_id'];
    }
    
    if (!empty($filters['course_id'])) {
        $sql .= " AND e.course_id = ?";
        $params[] = $filters['course_id'];
    }
    
    if (!empty($filters['status'])) {
        $sql .= " AND e.status = ?";
        $params[] = $filters['status'];
    }
    
    $sql .= " ORDER BY e.enrollment_date DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function getEnrollmentsByStudent($studentId) {
    $conn = getDbConnection();
    $stmt = $conn->prepare("SELECT e.*, c.code, c.name as course_name, c.credits
                            FROM enrollments e
                            JOIN courses c ON e.course_id = c.id
                            WHERE e.student_id = ?
                            ORDER BY e.enrollment_date DESC");
    $stmt->execute([$studentId]);
    return $stmt->fetchAll();
}

function addEnrollment($studentId, $courseId) {
    $conn = getDbConnection();
    
    $sql = "INSERT INTO enrollments (student_id, course_id, enrollment_date, status) 
            VALUES (?, ?, CURDATE(), 'active')";
    
    $stmt = $conn->prepare($sql);
    return $stmt->execute([$studentId, $courseId]);
}

function updateEnrollmentStatus($id, $status) {
    $conn = getDbConnection();
    $stmt = $conn->prepare("UPDATE enrollments SET status = ? WHERE id = ?");
    return $stmt->execute([$status, $id]);
}

function getTotalEnrollments() {
    $conn = getDbConnection();
    $stmt = $conn->query("SELECT COUNT(*) as total FROM enrollments");
    return $stmt->fetch()['total'];
}

function getStudentsForCourse($courseId) {
    $conn = getDbConnection();
    $stmt = $conn->prepare("SELECT s.*, e.status as enrollment_status, e.enrollment_date
                            FROM students s
                            JOIN enrollments e ON s.id = e.student_id
                            WHERE e.course_id = ? AND e.status = 'active'
                            ORDER BY s.last_name, s.first_name");
    $stmt->execute([$courseId]);
    return $stmt->fetchAll();
}

// ============================================
// ATTENDANCE FUNCTIONS
// ============================================

function getAttendance($courseId, $date) {
    $conn = getDbConnection();
    $stmt = $conn->prepare("SELECT a.*, s.first_name, s.last_name, s.id as student_id
                            FROM attendance a
                            JOIN students s ON a.student_id = s.id
                            WHERE a.course_id = ? AND a.attendance_date = ?");
    $stmt->execute([$courseId, $date]);
    return $stmt->fetchAll();
}

function saveAttendance($studentId, $courseId, $date, $status, $remarks = '') {
    $conn = getDbConnection();
    
    // Use INSERT ... ON DUPLICATE KEY UPDATE
    $sql = "INSERT INTO attendance (student_id, course_id, attendance_date, status, remarks) 
            VALUES (?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE status = ?, remarks = ?";
    
    $stmt = $conn->prepare($sql);
    return $stmt->execute([$studentId, $courseId, $date, $status, $remarks, $status, $remarks]);
}

function getAttendanceStats($courseId, $studentId = null) {
    $conn = getDbConnection();
    
    $sql = "SELECT status, COUNT(*) as count FROM attendance WHERE course_id = ?";
    $params = [$courseId];
    
    if ($studentId) {
        $sql .= " AND student_id = ?";
        $params[] = $studentId;
    }
    
    $sql .= " GROUP BY status";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

// ============================================
// SETTINGS FUNCTIONS
// ============================================

function getSettings() {
    $conn = getDbConnection();
    $stmt = $conn->query("SELECT setting_key, setting_value FROM settings");
    $results = $stmt->fetchAll();
    
    $settings = [];
    foreach ($results as $row) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
    return $settings;
}

function updateSetting($key, $value) {
    $conn = getDbConnection();
    $stmt = $conn->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = ?");
    return $stmt->execute([$value, $key]);
}

// ============================================
// UTILITY FUNCTIONS
// ============================================

function getRecentStudents($limit = 5) {
    $conn = getDbConnection();
    $stmt = $conn->prepare("SELECT * FROM students ORDER BY created_at DESC LIMIT ?");
    $stmt->bindValue(1, $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

function searchStudents($query) {
    $conn = getDbConnection();
    $search = '%' . $query . '%';
    $stmt = $conn->prepare("SELECT * FROM students 
                            WHERE id LIKE ? OR first_name LIKE ? OR last_name LIKE ? OR email LIKE ?
                            ORDER BY last_name, first_name");
    $stmt->execute([$search, $search, $search, $search]);
    return $stmt->fetchAll();
}

function getStudentsForTeacher($teacherId, $courseId = null) {
    $conn = getDbConnection();
    
    $sql = "SELECT DISTINCT s.*, c.code as course_code, c.name as course_name
            FROM students s
            JOIN enrollments e ON s.id = e.student_id
            JOIN courses c ON e.course_id = c.id
            WHERE c.instructor_id = ? AND e.status = 'active'";
    $params = [$teacherId];
    
    if ($courseId) {
        $sql .= " AND c.id = ?";
        $params[] = $courseId;
    }
    
    $sql .= " ORDER BY s.last_name, s.first_name";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

// ============================================
// TEACHER REGISTRATION FUNCTIONS
// ============================================

function registerTeacher($data) {
    $conn = getDbConnection();
    
    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$data['email']]);
    if ($stmt->fetch()) {
        return ['success' => false, 'message' => 'Email already registered'];
    }
    
    // Hash password
    $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO users (email, password, name, role, department, specialization, phone, status) 
            VALUES (?, ?, ?, 'teacher', ?, ?, ?, 'pending')";
    
    $stmt = $conn->prepare($sql);
    $result = $stmt->execute([
        $data['email'],
        $hashedPassword,
        $data['name'],
        $data['department'] ?? null,
        $data['specialization'] ?? null,
        $data['phone'] ?? null
    ]);
    
    if ($result) {
        return ['success' => true, 'message' => 'Registration submitted. Please wait for admin approval.'];
    }
    return ['success' => false, 'message' => 'Registration failed. Please try again.'];
}

function getPendingTeachers() {
    $conn = getDbConnection();
    $stmt = $conn->prepare("SELECT * FROM users WHERE role = 'teacher' AND status = 'pending' ORDER BY created_at DESC");
    $stmt->execute();
    return $stmt->fetchAll();
}

function approveTeacher($id) {
    $conn = getDbConnection();
    $stmt = $conn->prepare("UPDATE users SET status = 'active' WHERE id = ? AND role = 'teacher'");
    return $stmt->execute([$id]);
}

function rejectTeacher($id) {
    $conn = getDbConnection();
    $stmt = $conn->prepare("UPDATE users SET status = 'rejected' WHERE id = ? AND role = 'teacher'");
    return $stmt->execute([$id]);
}

function deleteTeacherRegistration($id) {
    $conn = getDbConnection();
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ? AND role = 'teacher' AND status IN ('pending', 'rejected')");
    return $stmt->execute([$id]);
}

function getPendingTeachersCount() {
    $conn = getDbConnection();
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM users WHERE role = 'teacher' AND status = 'pending'");
    $stmt->execute();
    $result = $stmt->fetch();
    return $result['count'] ?? 0;
}
