<?php
// public/settings.php - Admin Settings
require_once '../includes/auth.php';
require_once '../includes/db.php';
requireLogin();

$success = '';
$error = '';

// Get admin details
$admin_id = $_SESSION['admin_id'];
$stmt = $pdo->prepare("SELECT * FROM admins WHERE id = ?");
$stmt->execute([$admin_id]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    
    $stmt = $pdo->prepare("UPDATE admins SET name = ?, email = ?, phone = ? WHERE id = ?");
    if ($stmt->execute([$name, $email, $phone, $admin_id])) {
        $_SESSION['admin_name'] = $name;
        $_SESSION['admin_email'] = $email;
        $success = "Profile updated successfully!";
    } else {
        $error = "Failed to update profile.";
    }
}

// Handle password change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    if (password_verify($current_password, $admin['password'])) {
        if ($new_password === $confirm_password) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE admins SET password = ? WHERE id = ?");
            if ($stmt->execute([$hashed_password, $admin_id])) {
                $success = "Password changed successfully!";
            } else {
                $error = "Failed to change password.";
            }
        } else {
            $error = "New passwords do not match.";
        }
    } else {
        $error = "Current password is incorrect.";
    }
}

// Handle company settings update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_company'])) {
    $company_name = $_POST['company_name'];
    $company_address = $_POST['company_address'];
    $company_phone = $_POST['company_phone'];
    $company_email = $_POST['company_email'];
    $company_gst = $_POST['company_gst'];
    
    // Here you would typically save to a company_settings table
    $success = "Company settings updated successfully!";
}
?>

<?php include '../includes/headers.php'; ?>

<div class="container">
    <h1>Settings</h1>
    
    <?php if ($success): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
    <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-top: 2rem;">
        
        <!-- Profile Settings -->
        <div class="card">
            <h2 style="color: #1e3c72; margin-bottom: 1.5rem; border-bottom: 2px solid #f7971e; padding-bottom: 0.5rem;">
                <i class="fas fa-user-cog"></i> Profile Settings
            </h2>
            
            <form method="POST">
                <input type="hidden" name="update_profile" value="1">
                
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" 
                           value="<?php echo htmlspecialchars($admin['name']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" 
                           value="<?php echo htmlspecialchars($admin['email']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Phone Number</label>
                    <input type="tel" name="phone" class="form-control" 
                           value="<?php echo htmlspecialchars($admin['phone'] ?? ''); ?>">
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Profile
                </button>
            </form>
        </div>

        <!-- Password Change -->
        <div class="card">
            <h2 style="color: #1e3c72; margin-bottom: 1.5rem; border-bottom: 2px solid #f7971e; padding-bottom: 0.5rem;">
                <i class="fas fa-lock"></i> Change Password
            </h2>
            
            <form method="POST">
                <input type="hidden" name="change_password" value="1">
                
                <div class="form-group">
                    <label class="form-label">Current Password</label>
                    <input type="password" name="current_password" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">New Password</label>
                    <input type="password" name="new_password" class="form-control" required minlength="6">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Confirm New Password</label>
                    <input type="password" name="confirm_password" class="form-control" required>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-key"></i> Change Password
                </button>
            </form>
        </div>

        <!-- Company Settings -->
        <div class="card" style="grid-column: span 2;">
            <h2 style="color: #1e3c72; margin-bottom: 1.5rem; border-bottom: 2px solid #f7971e; padding-bottom: 0.5rem;">
                <i class="fas fa-building"></i> Company Settings
            </h2>
            
            <form method="POST">
                <input type="hidden" name="update_company" value="1">
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Company Name</label>
                        <input type="text" name="company_name" class="form-control" 
                               value="SolarTech Pro" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">GSTIN Number</label>
                        <input type="text" name="company_gst" class="form-control" 
                               value="07AABCU9603R1ZM">
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Company Address</label>
                    <textarea name="company_address" class="form-control" rows="3" required>Solar Energy Park, Sector 15, Gurgaon, Haryana 122001</textarea>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Phone Number</label>
                        <input type="tel" name="company_phone" class="form-control" 
                               value="+91-9876543210" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="company_email" class="form-control" 
                               value="info@solartech.com" required>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Company Settings
                </button>
            </form>
        </div>

        <!-- System Information -->
        <div class="card" style="grid-column: span 2;">
            <h2 style="color: #1e3c72; margin-bottom: 1.5rem; border-bottom: 2px solid #f7971e; padding-bottom: 0.5rem;">
                <i class="fas fa-info-circle"></i> System Information
            </h2>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
                <div style="text-align: center; padding: 1rem; background: #f8f9fa; border-radius: 8px;">
                    <div style="font-size: 2rem; color: #1e3c72; margin-bottom: 0.5rem;">
                        <i class="fas fa-database"></i>
                    </div>
                    <strong>Database</strong>
                    <p style="margin: 0.5rem 0; color: #666;">PostgreSQL</p>
                </div>
                
                <div style="text-align: center; padding: 1rem; background: #f8f9fa; border-radius: 8px;">
                    <div style="font-size: 2rem; color: #f7971e; margin-bottom: 0.5rem;">
                        <i class="fas fa-code"></i>
                    </div>
                    <strong>Version</strong>
                    <p style="margin: 0.5rem 0; color: #666;">v2.1.0</p>
                </div>
                
                <div style="text-align: center; padding: 1rem; background: #f8f9fa; border-radius: 8px;">
                    <div style="font-size: 2rem; color: #28a745; margin-bottom: 0.5rem;">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <strong>Last Backup</strong>
                    <p style="margin: 0.5rem 0; color: #666;"><?php echo date('M d, Y'); ?></p>
                </div>
                
                <div style="text-align: center; padding: 1rem; background: #f8f9fa; border-radius: 8px;">
                    <div style="font-size: 2rem; color: #6f42c1; margin-bottom: 0.5rem;">
                        <i class="fas fa-server"></i>
                    </div>
                    <strong>Server</strong>
                    <p style="margin: 0.5rem 0; color: #666;">Apache/PHP 8.1</p>
                </div>
            </div>
            
            <div style="margin-top: 1.5rem; padding: 1rem; background: #e3f2fd; border-radius: 8px;">
                <h4 style="color: #1976d2; margin-bottom: 0.5rem;">
                    <i class="fas fa-shield-alt"></i> System Status
                </h4>
                <p style="margin: 0; color: #1565c0;">
                    <i class="fas fa-check-circle" style="color: #4caf50;"></i> All systems operational
                </p>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>