<?php
require_once 'includes/config.php';
require_once 'includes/database.php';

$error = '';
$success = '';

// Redirect if already logged in
if (isLoggedIn()) {
    if (getUserType() === 'admin') {
        header('Location: admin/dashboard.php');
    } else {
        header('Location: teacher/dashboard.php');
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $department = trim($_POST['department'] ?? '');
    $specialization = trim($_POST['specialization'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    // Validation
    if (empty($name) || empty($email) || empty($password)) {
        $error = 'Please fill in all required fields';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match';
    } else {
        $result = registerTeacher([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'department' => $department,
            'specialization' => $specialization,
            'phone' => $phone
        ]);

        if ($result['success']) {
            header('Location: index.php?registered=1');
            exit;
        } else {
            $error = $result['message'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Registration - College Enrollment System</title>
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

        .text-accent {
            color: var(--color-accent) !important;
        }
    </style>
</head>
<body class="login-gradient min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-lg">
        <!-- Logo and Title -->
        <div class="text-center mb-6">
            <div class="inline-block mb-4">
                <img src="https://hccp-sms.holycrosscollegepampanga.edu.ph/public/assets/images/logo4.png" alt="HOLYCROSSCORE Logo" class="w-20 h-20">
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">HOLYCROSSCORE</h1>
            <p class="text-gray-300">Teacher Registration</p>
        </div>

        <!-- Registration Card -->
        <div class="bg-white rounded-lg card-shadow overflow-hidden">
            <div class="brand-header px-6 py-4">
                <h2 class="text-xl font-bold text-white">Create Teacher Account</h2>
                <p class="text-gray-300 text-sm">Your account will require admin approval</p>
            </div>

            <div class="p-6">
                <!-- Error Message -->
                <?php if ($error): ?>
                <div class="alert alert-error mb-4 shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l-2-2m0 0l-2-2m2 2l2-2m-2 2l-2 2m9-11a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span><?php echo htmlspecialchars($error); ?></span>
                </div>
                <?php endif; ?>

                <form method="POST" class="space-y-4">
                    <!-- Full Name -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold text-gray-700">Full Name <span class="text-red-500">*</span></span>
                        </label>
                        <input 
                            type="text" 
                            name="name"
                            placeholder="e.g., Prof. John Smith"
                            value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>"
                            class="input input-bordered input-focus w-full"
                            required
                        />
                    </div>

                    <!-- Email -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold text-gray-700">Email Address <span class="text-red-500">*</span></span>
                        </label>
                        <input 
                            type="email" 
                            name="email"
                            placeholder="yourname@college.edu"
                            value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                            class="input input-bordered input-focus w-full"
                            required
                        />
                    </div>

                    <!-- Password Fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold text-gray-700">Password <span class="text-red-500">*</span></span>
                            </label>
                            <input 
                                type="password" 
                                name="password"
                                placeholder="Min. 6 characters"
                                class="input input-bordered input-focus w-full"
                                required
                            />
                        </div>
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold text-gray-700">Confirm Password <span class="text-red-500">*</span></span>
                            </label>
                            <input 
                                type="password" 
                                name="confirm_password"
                                placeholder="Re-enter password"
                                class="input input-bordered input-focus w-full"
                                required
                            />
                        </div>
                    </div>

                    <!-- Department -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold text-gray-700">Department</span>
                        </label>
                        <select name="department" class="select select-bordered w-full">
                            <option value="">Select Department</option>
                            <option value="Computer Science" <?php echo (($_POST['department'] ?? '') === 'Computer Science') ? 'selected' : ''; ?>>Computer Science</option>
                            <option value="Information Technology" <?php echo (($_POST['department'] ?? '') === 'Information Technology') ? 'selected' : ''; ?>>Information Technology</option>
                            <option value="Business Administration" <?php echo (($_POST['department'] ?? '') === 'Business Administration') ? 'selected' : ''; ?>>Business Administration</option>
                            <option value="Engineering" <?php echo (($_POST['department'] ?? '') === 'Engineering') ? 'selected' : ''; ?>>Engineering</option>
                            <option value="Education" <?php echo (($_POST['department'] ?? '') === 'Education') ? 'selected' : ''; ?>>Education</option>
                            <option value="Arts and Sciences" <?php echo (($_POST['department'] ?? '') === 'Arts and Sciences') ? 'selected' : ''; ?>>Arts and Sciences</option>
                        </select>
                    </div>

                    <!-- Specialization -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold text-gray-700">Specialization</span>
                        </label>
                        <input 
                            type="text" 
                            name="specialization"
                            placeholder="e.g., Web Development, Data Science"
                            value="<?php echo htmlspecialchars($_POST['specialization'] ?? ''); ?>"
                            class="input input-bordered input-focus w-full"
                        />
                    </div>

                    <!-- Phone -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold text-gray-700">Phone Number</span>
                        </label>
                        <input 
                            type="tel" 
                            name="phone"
                            placeholder="+63-9XX-XXX-XXXX"
                            value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>"
                            class="input input-bordered input-focus w-full"
                        />
                    </div>

                    <!-- Info Notice -->
                    <div class="alert alert-info text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Your registration will be reviewed by an administrator. You will be able to login once approved.</span>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary w-full btn-brand border-none font-semibold">
                        Submit Registration
                    </button>

                    <!-- Back to Login -->
                    <div class="text-center">
                        <a href="index.php" class="text-sm text-accent hover:underline">
                            ← Back to Login
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-6 text-gray-300">
            <p class="text-sm">© 2024 HOLYCROSSCORE. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
