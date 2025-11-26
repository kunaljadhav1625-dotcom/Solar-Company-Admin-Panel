<?php
// admin-dashboard.php - Admin Dashboard
require_once '../includes/auth.php';
require_once '../includes/db.php';
requireLogin();

// Get dashboard statistics
$totalProducts = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$totalClients = $pdo->query("SELECT COUNT(*) FROM clients")->fetchColumn();
$totalProjects = $pdo->query("SELECT COUNT(*) FROM projects WHERE status = 'Active'")->fetchColumn();
$totalBills = $pdo->query("SELECT COUNT(*) FROM bills")->fetchColumn();

// Get low stock products
$lowStockProducts = $pdo->query("SELECT * FROM products WHERE stock_quantity < 10 ORDER BY stock_quantity ASC LIMIT 5")->fetchAll();

// Get recent bills
$recentBills = $pdo->query("
    SELECT b.*, c.name as client_name 
    FROM bills b 
    LEFT JOIN clients c ON b.client_id = c.id 
    ORDER BY b.created_at DESC 
    LIMIT 5
")->fetchAll();

// Include header
include '../includes/headers.php';
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SolarTech Pro</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .dashboard-header {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin: 2rem 0;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            text-align: center;
            border-left: 4px solid #f7971e;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #1e3c72;
            margin: 10px 0;
        }

        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }

        .dashboard-section {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .section-title {
            color: #1e3c72;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #f7971e;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-badge {
            background: #ff4757;
            color: white;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 0.8rem;
            margin-left: 10px;
        }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .action-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 15px;
            background: linear-gradient(45deg, #1e3c72, #2a5298);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(30, 60, 114, 0.3);
        }

        .action-btn i {
            font-size: 1.2rem;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        .table th,
        .table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #1e3c72;
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-active {
            background: #d4edda;
            color: #155724;
        }

        .status-low {
            background: #fff3cd;
            color: #856404;
        }

        .status-critical {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <main class="container">
        <div class="dashboard-header">
            <div class="container">
                <h1>Welcome back, <?php echo htmlspecialchars($_SESSION['admin_name']); ?>! 👋</h1>
                <p>Here's what's happening with your solar business today</p>
            </div>
        </div>

        <!-- Statistics Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <i class="fas fa-solar-panel" style="font-size: 2rem; color: #f7971e;"></i>
                <div class="stat-number"><?php echo $totalProducts; ?></div>
                <div class="stat-label">Total Products</div>
            </div>

            <div class="stat-card">
                <i class="fas fa-users" style="font-size: 2rem; color: #1e3c72;"></i>
                <div class="stat-number"><?php echo $totalClients; ?></div>
                <div class="stat-label">Total Clients</div>
            </div>

            <div class="stat-card">
                <i class="fas fa-project-diagram" style="font-size: 2rem; color: #2a5298;"></i>
                <div class="stat-number"><?php echo $totalProjects; ?></div>
                <div class="stat-label">Active Projects</div>
            </div>

            <div class="stat-card">
                <i class="fas fa-file-invoice" style="font-size: 2rem; color: #28a745;"></i>
                <div class="stat-number"><?php echo $totalBills; ?></div>
                <div class="stat-label">Bills Generated</div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="dashboard-section">
            <h2 class="section-title">
                <i class="fas fa-bolt"></i>
                Quick Actions
            </h2>
            <div class="quick-actions">
                <a href="generate_bill.php" class="action-btn">
                    <i class="fas fa-file-invoice"></i>
                    Generate Bill
                </a>
                <a href="products.php" class="action-btn">
                    <i class="fas fa-plus"></i>
                    Add Product
                </a>
                <a href="clients.php" class="action-btn">
                    <i class="fas fa-user-plus"></i>
                    Add Client
                </a>
                <a href="projects.php" class="action-btn">
                    <i class="fas fa-project-diagram"></i>
                    New Project
                </a>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            <!-- Low Stock Alerts -->
            <div class="dashboard-section">
                <h2 class="section-title">
                    <i class="fas fa-exclamation-triangle"></i>
                    Low Stock Alerts
                    <?php if (count($lowStockProducts) > 0): ?>
                    <span class="alert-badge"><?php echo count($lowStockProducts); ?></span>
                    <?php endif; ?>
                </h2>
                
                <?php if (count($lowStockProducts) > 0): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Stock</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lowStockProducts as $product): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td><?php echo $product['stock_quantity']; ?></td>
                            <td>
                                <span class="status-badge <?php 
                                    echo $product['stock_quantity'] < 5 ? 'status-critical' : 'status-low';
                                ?>">
                                    <?php echo $product['stock_quantity'] < 5 ? 'Critical' : 'Low'; ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <p style="text-align: center; color: #666; padding: 2rem;">No low stock alerts! 🎉</p>
                <?php endif; ?>
            </div>

            <!-- Recent Bills -->
            <div class="dashboard-section">
                <h2 class="section-title">
                    <i class="fas fa-history"></i>
                    Recent Bills
                </h2>
                
                <?php if (count($recentBills) > 0): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Bill No</th>
                            <th>Client</th>
                            <th>Amount</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentBills as $bill): ?>
                        <tr>
                            <td>
                                <a href="view_bill.php?id=<?php echo $bill['id']; ?>" style="color: #1e3c72; text-decoration: none;">
                                    <?php echo htmlspecialchars($bill['bill_no']); ?>
                                </a>
                            </td>
                            <td><?php echo htmlspecialchars($bill['client_name']); ?></td>
                            <td>₹<?php echo number_format($bill['total_amount'], 2); ?></td>
                            <td><?php echo date('M d, Y', strtotime($bill['bill_date'])); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <p style="text-align: center; color: #666; padding: 2rem;">No bills generated yet.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Company Performance -->
        <div class="dashboard-section">
            <h2 class="section-title">
                <i class="fas fa-chart-line"></i>
                Business Overview
            </h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; text-align: center;">
                <div>
                    <div style="font-size: 2rem; color: #1e3c72; font-weight: bold;">₹25.8L</div>
                    <div style="color: #666;">Monthly Revenue</div>
                </div>
                <div>
                    <div style="font-size: 2rem; color: #28a745; font-weight: bold;">+15%</div>
                    <div style="color: #666;">Growth Rate</div>
                </div>
                <div>
                    <div style="font-size: 2rem; color: #f7971e; font-weight: bold;">94%</div>
                    <div style="color: #666;">Customer Satisfaction</div>
                </div>
                <div>
                    <div style="font-size: 2rem; color: #2a5298; font-weight: bold;">18</div>
                    <div style="color: #666;">Projects This Month</div>
                </div>
            </div>
        </div>
    </main>
    
    <?php include '../includes/footer.php'; ?>

    <script>
        // Auto refresh dashboard every 5 minutes
        setInterval(() => {
            window.location.reload();
        }, 300000);

        // Add some animations
        document.addEventListener('DOMContentLoaded', function() {
            const statCards = document.querySelectorAll('.stat-card');
            statCards.forEach((card, index) => {
                card.style.animation = `fadeInUp 0.6s ease ${index * 0.1}s both`;
            });
        });

        // Add CSS animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>