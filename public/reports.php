<?php
// public/reports.php - Reports Page
require_once '../includes/auth.php';
require_once '../includes/db.php';
requireLogin();

// Get report data
$total_sales = $pdo->query("SELECT SUM(total_amount) FROM bills")->fetchColumn();
$total_clients = $pdo->query("SELECT COUNT(*) FROM clients")->fetchColumn();
$total_products = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$active_projects = $pdo->query("SELECT COUNT(*) FROM projects WHERE status = 'In Progress'")->fetchColumn();

// Get monthly sales data
$monthly_sales = $pdo->query("
    SELECT TO_CHAR(bill_date, 'YYYY-MM') as month, 
           SUM(total_amount) as total 
    FROM bills 
    GROUP BY TO_CHAR(bill_date, 'YYYY-MM') 
    ORDER BY month DESC 
    LIMIT 6
")->fetchAll();
?>

<?php include '../includes/headers.php'; ?>

<div class="container">
    <h1>Reports & Analytics</h1>
    
    <div class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin: 2rem 0;">
        <div class="stat-card" style="background: linear-gradient(45deg, #1e3c72, #2a5298); color: white; padding: 1.5rem; border-radius: 10px; text-align: center;">
            <div style="font-size: 2rem; margin-bottom: 0.5rem;">₹<?php echo number_format($total_sales, 2); ?></div>
            <div>Total Sales</div>
        </div>
        
        <div class="stat-card" style="background: linear-gradient(45deg, #f7971e, #ffd200); color: white; padding: 1.5rem; border-radius: 10px; text-align: center;">
            <div style="font-size: 2rem; margin-bottom: 0.5rem;"><?php echo $total_clients; ?></div>
            <div>Total Clients</div>
        </div>
        
        <div class="stat-card" style="background: linear-gradient(45deg, #28a745, #20c997); color: white; padding: 1.5rem; border-radius: 10px; text-align: center;">
            <div style="font-size: 2rem; margin-bottom: 0.5rem;"><?php echo $total_products; ?></div>
            <div>Products</div>
        </div>
        
        <div class="stat-card" style="background: linear-gradient(45deg, #6f42c1, #e83e8c); color: white; padding: 1.5rem; border-radius: 10px; text-align: center;">
            <div style="font-size: 2rem; margin-bottom: 0.5rem;"><?php echo $active_projects; ?></div>
            <div>Active Projects</div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; margin-top: 2rem;">
        
        <!-- Sales Chart -->
        <div class="card">
            <h2 style="color: #1e3c72; margin-bottom: 1rem;">Monthly Sales Report</h2>
            <div style="height: 300px; background: #f8f9fa; border-radius: 8px; padding: 1rem; display: flex; align-items: end; gap: 1rem; justify-content: center;">
                <?php foreach ($monthly_sales as $sale): ?>
                <div style="text-align: center;">
                    <div style="background: linear-gradient(45deg, #1e3c72, #2a5298); width: 40px; height: <?php echo ($sale['total'] / 10000) * 2; ?>px; border-radius: 4px; margin: 0 auto;"></div>
                    <div style="font-size: 0.8rem; margin-top: 0.5rem;">₹<?php echo number_format($sale['total'], 0); ?></div>
                    <div style="font-size: 0.7rem; color: #666;"><?php echo date('M Y', strtotime($sale['month'] . '-01')); ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Quick Reports -->
        <div class="card">
            <h2 style="color: #1e3c72; margin-bottom: 1rem;">Quick Reports</h2>
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <a href="#" class="btn btn-primary" style="text-align: left;">
                    <i class="fas fa-file-invoice"></i> Sales Report
                </a>
                <a href="#" class="btn btn-secondary" style="text-align: left;">
                    <i class="fas fa-boxes"></i> Stock Report
                </a>
                <a href="#" class="btn btn-success" style="text-align: left;">
                    <i class="fas fa-users"></i> Client Report
                </a>
                <a href="#" class="btn btn-danger" style="text-align: left;">
                    <i class="fas fa-chart-pie"></i> Financial Report
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="card" style="margin-top: 2rem;">
        <h2 style="color: #1e3c72; margin-bottom: 1rem;">Recent Activity</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Activity</th>
                    <th>User</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo date('M d, Y'); ?></td>
                    <td>New Bill Generated</td>
                    <td><?php echo htmlspecialchars($_SESSION['admin_name']); ?></td>
                    <td>Bill #SOLAR-20241215-1234</td>
                </tr>
                <tr>
                    <td><?php echo date('M d, Y', strtotime('-1 day')); ?></td>
                    <td>New Client Added</td>
                    <td>System</td>
                    <td>Rajesh Kumar</td>
                </tr>
                <tr>
                    <td><?php echo date('M d, Y', strtotime('-2 days')); ?></td>
                    <td>Product Stock Updated</td>
                    <td><?php echo htmlspecialchars($_SESSION['admin_name']); ?></td>
                    <td>Solar Panel 100W</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>