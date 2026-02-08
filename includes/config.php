<?php
// Start session
session_start();

// Define base URL
define('BASE_URL', 'http://localhost/enroll/');
define('ADMIN_URL', BASE_URL . 'admin/');
define('TEACHER_URL', BASE_URL . 'teacher/');

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'enrollment_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// Database connection
function getDbConnection() {
    static $conn = null;
    if ($conn === null) {
        try {
            $conn = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
    return $conn;
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']) && isset($_SESSION['user_type']);
}

// Check user type
function getUserType() {
    return isset($_SESSION['user_type']) ? $_SESSION['user_type'] : null;
}

// Get user info
function getUserInfo() {
    return isset($_SESSION['user_info']) ? $_SESSION['user_info'] : null;
}

// Logout user
function logoutUser() {
    session_destroy();
    header('Location: ' . BASE_URL . 'index.php');
    exit();
}

// Login user - auto-detect user type based on credentials
function loginUser($email, $password) {
    $conn = getDbConnection();
    
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if (!$user) {
        return ['success' => false, 'message' => 'Invalid email or password'];
    }
    
    if (!password_verify($password, $user['password'])) {
        return ['success' => false, 'message' => 'Invalid email or password'];
    }
    
    // Check account status
    if ($user['status'] === 'pending') {
        return ['success' => false, 'message' => 'Your account is pending approval. Please wait for admin confirmation.'];
    }
    
    if ($user['status'] === 'rejected') {
        return ['success' => false, 'message' => 'Your registration was not approved. Please contact the administrator.'];
    }
    
    if ($user['status'] === 'inactive') {
        return ['success' => false, 'message' => 'Your account has been deactivated. Please contact the administrator.'];
    }
    
    // Login successful
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_type'] = $user['role'];
    $_SESSION['fullname'] = $user['name'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['user_info'] = [
        'id' => $user['id'],
        'name' => $user['name'],
        'email' => $user['email'],
        'role' => $user['role'] === 'admin' ? 'Administrator' : 'Faculty',
        'department' => $user['department']
    ];
    return ['success' => true, 'role' => $user['role']];
}

// Redirect to login if not authenticated
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ' . BASE_URL . 'index.php');
        exit;
    }
}

// Require admin access
function requireAdmin() {
    requireLogin();
    if (getUserType() !== 'admin') {
        header('Location: ' . BASE_URL . 'index.php');
        exit;
    }
}

// Require teacher access
function requireTeacher() {
    requireLogin();
    if (getUserType() !== 'teacher') {
        header('Location: ' . BASE_URL . 'index.php');
        exit;
    }
}
?>
