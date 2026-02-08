<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
$is_logged_in = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
$user_name = $_SESSION['fullname'] ?? 'User';
$user_role = $_SESSION['role'] ?? '';

// Handle logout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_destroy();
    header('Location: ' . SITE_URL . '/login.php?logged_out=1');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'College Enrollment'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@3.51.1/dist/full.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #30425A;
            --secondary: #222431;
            --white: #FFFFFF;
        }
        
        body {
            background-color: #f5f5f5;
        }
        
        .navbar-bg {
            background-color: var(--secondary);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .sidebar-bg {
            background-color: #FFFFFF;
            border-right: 1px solid #e5e7eb;
        }
        
        .btn-logout {
            background-color: #ef4444;
            border-color: #ef4444;
            transition: all 0.3s ease;
        }
        
        .btn-logout:hover {
            background-color: #dc2626;
            border-color: #dc2626;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }
        
        .active-link {
            background-color: rgba(48, 66, 90, 0.1);
            color: #30425A;
            border-left: 3px solid #30425A;
        }
    </style>
</head>
<body>
    <div class="flex h-screen bg-gray-100">
