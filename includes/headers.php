<?php
// includes/header.php - Common Header with Navigation
require_once __DIR__ . '/auth.php';
requireLogin();

// Get current page for active menu highlighting
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Technosky Solar System</title>
    <link rel="stylesheet" href="<?php echo strpos($_SERVER['PHP_SELF'], '/public/') !== false ? '../css/style.css' : './css/style.css'; ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Header Styles */
        .admin-header {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-top {
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo {
            width: 50px;
            height: 50px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            backdrop-filter: blur(10px);
            transition: transform 0.3s ease;
        }

        .logo:hover {
            transform: scale(1.1);
        }

        .company-info h1 {
            font-size: 1.5rem;
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .company-info p {
            font-size: 0.8rem;
            opacity: 0.9;
            margin: 0;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-welcome {
            text-align: right;
        }

        .user-welcome strong {
            display: block;
            font-size: 0.9rem;
        }

        .user-welcome small {
            opacity: 0.8;
            font-size: 0.8rem;
        }

        .logout-btn {
            background: rgba(231, 76, 60, 0.2);
            border: 1px solid rgba(231, 76, 60, 0.4);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            text-decoration: none;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
        }

        .logout-btn:hover {
            background: rgba(231, 76, 60, 0.3);
            border-color: rgba(231, 76, 60, 0.6);
            transform: translateY(-1px);
        }

        /* Navigation Menu */
        .admin-nav {
            background: rgba(255,255,255,0.95);
            padding: 0;
            backdrop-filter: blur(10px);
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .nav-menu {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            gap: 0;
        }

        .nav-item {
            position: relative;
            
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 1rem 1.5rem;
            text-decoration: none;
            color: #1e3c72;
            font-weight: 500;
            transition: all 0.3s;
            border-bottom: 3px solid transparent;
            font-size: 0.95rem;
        }

        .nav-link:hover {
            color: #2a5298;
            background: linear-gradient(135deg, rgba(30, 60, 114, 0.1), rgba(42, 82, 152, 0.1));
            border-bottom-color: #2a5298;
        }

        .nav-link.active {
            color: #2a5298;
            background: linear-gradient(135deg, rgba(30, 60, 114, 0.15), rgba(42, 82, 152, 0.15));
            border-bottom-color: #2a5298;
        }

        .nav-icon {
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }

        .nav-badge {
            background: #f7971e;
            color: white;
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 0.7rem;
            margin-left: 5px;
        }

        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.5rem;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .header-top {
                padding: 1rem;
            }

            .mobile-menu-toggle {
                display: block;
            }

            .user-welcome {
                display: none;
            }

            .admin-nav {
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: white;
                box-shadow: 0 5px 15px rgba(0,0,0,0.1);
                display: none;
            }

            .admin-nav.mobile-active {
                display: block;
            }

            .nav-menu {
                flex-direction: column;
            }

            .nav-link {
                padding: 1rem;
                border-bottom: 1px solid #e2e8f0;
                border-left: 4px solid transparent;
            }

            .nav-link.active {
                border-left-color: #f7971e;
                border-bottom-color: #e2e8f0;
            }
        }

        /* Notification Bell */
        .notification-bell {
            position: relative;
            margin-right: 1rem;
        }

        .notification-count {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #e74c3c;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <header class="admin-header">
        <div class="header-top">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="logo-section">
                    <div class="logo">☀️</div>
                    <div class="company-info">
                        <h1>Technosky Solar System</h1>
                        <p>Admin Control Panel</p>
                    </div>
                </div>
            </div>

            <div class="user-info">
                <div class="notification-bell">
                    <i class="fas fa-bell" style="font-size: 1.2rem; color: white;"></i>
                    <span class="notification-count">3</span>
                </div>
                
                <div class="user-welcome">
                    <strong>Welcome, <?php echo isset($_SESSION['admin_name']) ? htmlspecialchars($_SESSION['admin_name']) : 'Vishal Mane'; ?></strong>
                    <small><?php echo isset($_SESSION['admin_role']) ? htmlspecialchars($_SESSION['admin_role']) : 'System Administrator'; ?></small>
                </div>
                
                <a href="./logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="admin-nav" id="mainNav">
            <div class="nav-container">
                <ul class="nav-menu">
                    <li class="nav-item">
                        <a href="<?php echo strpos($_SERVER['PHP_SELF'], '/public/') !== false ? './admin-dashboard.php' : './public/admin-dashboard.php'; ?>" class="nav-link <?php echo $current_page == 'admin-dashboard.php' ? 'active' : ''; ?>">
                            <i class="fas fa-tachometer-alt nav-icon"></i>
                            Dashboard
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?php echo strpos($_SERVER['PHP_SELF'], '/public/') !== false ? './products.php' : './public/products.php'; ?>" class="nav-link <?php echo $current_page == 'products.php' ? 'active' : ''; ?>">
                            <i class="fas fa-boxes nav-icon"></i>
                            Products
                            <span class="nav-badge">15</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?php echo strpos($_SERVER['PHP_SELF'], '/public/') !== false ? './projects.php' : './public/projects.php'; ?>" class="nav-link <?php echo $current_page == 'projects.php' ? 'active' : ''; ?>">
                            <i class="fas fa-solar-panel nav-icon"></i>
                            Projects
                            <span class="nav-badge">8</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?php echo strpos($_SERVER['PHP_SELF'], '/public/') !== false ? './clients.php' : './public/clients.php'; ?>" class="nav-link <?php echo $current_page == 'clients.php' ? 'active' : ''; ?>">
                            <i class="fas fa-users nav-icon"></i>
                            Clients
                            <span class="nav-badge">24</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?php echo strpos($_SERVER['PHP_SELF'], '/public/') !== false ? './bills.php' : './public/bills.php'; ?>" class="nav-link <?php echo $current_page == 'bills.php' ? 'active' : ''; ?>">
                            <i class="fas fa-file-invoice nav-icon"></i>
                            Bills
                            <span class="nav-badge">45</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?php echo strpos($_SERVER['PHP_SELF'], '/public/') !== false ? './generate_bill.php' : './public/generate_bill.php'; ?>" class="nav-link <?php echo $current_page == 'generate_bill.php' ? 'active' : ''; ?>">
                            <i class="fas fa-plus-circle nav-icon"></i>
                            Generate Bill
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?php echo strpos($_SERVER['PHP_SELF'], '/public/') !== false ? './contact.php' : './public/contact.php'; ?>" class="nav-link <?php echo $current_page == 'contact.php' ? 'active' : ''; ?>">
                            <i class="fas fa-envelope nav-icon"></i>
                            Contact
                        </a>
                    </li>
                    
                    <!-- Settings Link in Navigation -->
                    <li class="nav-item" style="margin-left: auto;">
                        <a href="../public/settings.php" class="nav-link <?php echo $current_page == 'settings.php' ? 'active' : ''; ?>">
                            <i class="fas fa-cog nav-icon"></i>
                            Settings
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <!-- Main Content Container -->
    <main class="container" style="min-height: calc(100vh - 200px); padding: 2rem 0;">