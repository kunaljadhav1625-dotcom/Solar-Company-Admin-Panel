<?php
// includes/auth.php - Authentication Functions
session_start();

function requireLogin() {
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header('Location: index.php?message=Please login first');
        exit();
    }
}

function login($email, $password) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ? AND password = ?");
    $stmt->execute([$email, $password]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($admin) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['name'] ?? 'Vishal Mane';
        $_SESSION['admin_email'] = $admin['email'];
        $_SESSION['admin_role'] = $admin['role'] ?? 'System Administrator';
        return true;
    }
    return false;
}

function logout() {
    $_SESSION = array();
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time()-42000, '/');
    }
    session_destroy();
    $loginPath = strpos($_SERVER['PHP_SELF'], '/public/') !== false ? './login.php' : './public/login.php';
    header('Location: ' . $loginPath . '?message=Logged out successfully');
    exit;
}
?>