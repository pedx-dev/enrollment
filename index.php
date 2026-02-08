<?php
require_once 'includes/config.php';

$error = '';
$success = '';

// Check for success message from registration
if (isset($_GET['registered'])) {
    $success = 'Registration successful! Please wait for admin approval before logging in.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Please fill in all fields';
    } else {
        $result = loginUser($email, $password);
        if ($result['success']) {
            if ($result['role'] === 'admin') {
                header('Location: admin/dashboard.php');
            } else {
                header('Location: teacher/dashboard.php');
            }
            exit;
        } else {
            $error = $result['message'];
        }
    }
}

if (isLoggedIn()) {
    if (getUserType() === 'admin') {
        header('Location: admin/dashboard.php');
    } else {
        header('Location: teacher/dashboard.php');
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Enrollment System - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.min.css" rel="stylesheet" type="text/css" />
    <style>
        :root {
            --color-primary: #FFFFFF;
            --color-secondary: #222431;
            --color-accent: #30425A;
        }
        
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }
        
        .login-gradient {
            background: linear-gradient(135deg, var(--color-secondary) 0%, var(--color-accent) 100%);
        }
        
        .card-shadow {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        
        .input-focus:focus {
            border-color: var(--color-accent);
            box-shadow: 0 0 0 3px rgba(48, 66, 90, 0.1);
        }

        .brand-header {
            background: linear-gradient(135deg, var(--color-secondary), var(--color-accent));
        }

        .btn-brand {
            background-color: var(--color-accent) !important;
            border-color: var(--color-accent) !important;
            color: #FFFFFF !important;
        }

        .btn-brand:hover {
            background-color: var(--color-secondary) !important;
            border-color: var(--color-secondary) !important;
        }

        .radio-primary {
            --chkbg: var(--color-accent);
            --chkfg: #FFFFFF;
        }

        .text-accent {
            color: var(--color-accent) !important;
        }
    </style>
</head>
<body class="login-gradient min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo and Title -->
        <div class="text-center mb-8">
            <div class="inline-block mb-4">
                <img src="https://hccp-sms.holycrosscollegepampanga.edu.ph/public/assets/images/logo4.png" alt="HOLYCROSSCORE Logo" class="w-24 h-24">
            </div>
            <h1 class="text-4xl font-bold text-white mb-2">HOLYCROSSCORE</h1>
            <p class="text-gray-300">Enrollment Management System</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-lg card-shadow overflow-hidden">
            <div class="brand-header px-6 py-4">
                <h2 class="text-2xl font-bold text-white">Welcome Back</h2>
            </div>

            <div class="p-8">
                <!-- Success Message -->
                <?php if ($success): ?>
                <div class="alert alert-success mb-6 shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span><?php echo htmlspecialchars($success); ?></span>
                </div>
                <?php endif; ?>

                <!-- Error Message -->
                <?php if ($error): ?>
                <div class="alert alert-error mb-6 shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l-2-2m0 0l-2-2m2 2l2-2m-2 2l-2 2m9-11a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span><?php echo htmlspecialchars($error); ?></span>
                </div>
                <?php endif; ?>

                <form method="POST" class="space-y-6">
                    <!-- Email Input -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold text-gray-700">Email Address</span>
                        </label>
                        <input 
                            type="email" 
                            name="email"
                            placeholder="Enter your email" 
                            class="input input-bordered input-focus w-full mt-2"
                            required
                        />
                    </div>

                    <!-- Password Input -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold text-gray-700">Password</span>
                        </label>
                        <input 
                            type="password" 
                            name="password"
                            placeholder="Enter your password" 
                            class="input input-bordered input-focus w-full mt-2"
                            required
                        />
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary w-full btn-brand border-none font-semibold">
                        Sign In
                    </button>

                    <!-- Register Link -->
                    <div class="text-center mt-4">
                        <p class="text-sm text-gray-600">
                            Are you a teacher? 
                            <a href="register.php" class="text-accent font-semibold hover:underline">Register here</a>
                        </p>
                    </div>
                </form>

                <!-- Demo Credentials Info -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="text-sm text-gray-600">
                        <p class="font-semibold mb-3 text-gray-700">Demo Credentials:</p>
                        <div class="space-y-2 bg-gray-50 p-4 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-700">Admin:</p>
                                <p class="text-xs text-gray-600">Email: admin@college.edu | Password: password</p>
                            </div>
                            <div>
                                <p class="font-medium text-gray-700">Teacher:</p>
                                <p class="text-xs text-gray-600">Email: john.smith@college.edu | Password: password</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-6 text-gray-300">
            <p class="text-sm">© 2024 HOLYCROSSCORE. All rights reserved.</p>
        </div>
    </div>

    <script>
        // Add focus animations
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('focused');
            });
        });
    </script>
</body>
</html>
