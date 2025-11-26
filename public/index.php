<?php
// index.php - SolarTech Pro Admin Login
session_start();

// If already logged in, redirect to dashboard
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: ./admin-dashboard.php');
    exit;
}

// Get any messages from URL
$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Technosky Solar Syatem - Admin Login</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        /* Additional login page specific styles */
        .login-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #f7971e 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-box {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 450px;
            backdrop-filter: blur(10px);
        }

        .company-logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-icon {
            font-size: 60px;
            background: linear-gradient(45deg, #f7971e, #ffd200);
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .company-name {
            font-size: 28px;
            font-weight: bold;
            color: #1e3c72;
            margin-bottom: 5px;
        }

        .company-tagline {
            color: #2a5298;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #1e3c72;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: #f7971e;
            box-shadow: 0 0 0 3px rgba(247, 151, 30, 0.1);
        }

        .password-input {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #2a5298;
        }

        .btn-login {
            width: 100%;
            padding: 15px;
            background: linear-gradient(45deg, #1e3c72, #2a5298);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(30, 60, 114, 0.3);
        }

        .alert {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: <?php echo $message ? 'block' : 'none'; ?>;
        }

        .alert-error {
            background: #fee;
            color: #c53030;
            border: 1px solid #fed7d7;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div class="company-logo">
                <div class="logo-icon">☀️</div>
                <h1 class="company-name">Technosky Solar Syatem</h1>
                <p class="company-tagline">Powering Tomorrow with Clean Energy</p>
                <p style="color: #f7971e; font-weight: 600; margin-top: 10px;">Admin Control Panel</p>
            </div>

            <?php if ($message): ?>
            <div class="alert alert-error">
                <?php echo $message; ?>
            </div>
            <?php endif; ?>

            <form action="login.php" method="POST" id="loginForm">
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="admin@solartech.com" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="password-input">
                        <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
                        <span class="password-toggle" onclick="togglePassword()">👁️</span>
                    </div>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <label style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" name="remember">
                        Remember me
                    </label>
                    <a href="#" style="color: #f7971e; text-decoration: none;">Forgot Password?</a>
                </div>

                <button type="submit" class="btn-login">Access Dashboard</button>
            </form>

            <div style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #e1e5e9;">
                <small style="color: #6c757d;">
                    🔐 Secure Admin Access • All activities are monitored
                </small>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleBtn = document.querySelector('.password-toggle');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleBtn.textContent = '🙈';
            } else {
                passwordInput.type = 'password';
                toggleBtn.textContent = '👁️';
            }
        }

        // Demo credentials auto-fill
        document.querySelector('.logo-icon').addEventListener('click', function() {
            document.querySelector('input[name="email"]').value = 'admin@solartech.com';
            document.getElementById('password').value = 'solar123';
        });

        // Form validation
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const email = document.querySelector('input[name="email"]').value;
            const password = document.getElementById('password').value;
            
            if (!email || !password) {
                e.preventDefault();
                alert('Please fill in all fields');
                return false;
            }
            
            if (password.length < 6) {
                e.preventDefault();
                alert('Password must be at least 6 characters long');
                return false;
            }
        });
    </script>
</body>
</html>