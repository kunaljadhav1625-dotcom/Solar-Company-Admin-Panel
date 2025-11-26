<?php
// public/bills.php - Bills List
require_once '../includes/auth.php';
require_once '../includes/db.php';
requireLogin();

// Get all bills with client and project information
$bills = $pdo->query("
    SELECT b.*, c.name as client_name, p.name as project_name 
    FROM bills b 
    LEFT JOIN clients c ON b.client_id = c.id 
    LEFT JOIN projects p ON b.project_id = p.id 
    ORDER BY b.created_at DESC
")->fetchAll();
?>

<?php include '../includes/headers.php'; ?>

<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h1>All Bills</h1>
        <a href="generate_bill.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Generate New Bill
        </a>
    </div>

    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>Bill No</th>
                    <th>Client</th>
                    <th>Project</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bills as $bill): ?>
                <tr>
                    <td>
                        <strong><?php echo htmlspecialchars($bill['bill_no']); ?></strong>
                    </td>
                    <td><?php echo htmlspecialchars($bill['client_name']); ?></td>
                    <td><?php echo htmlspecialchars($bill['project_name']); ?></td>
                    <td><?php echo date('M d, Y', strtotime($bill['bill_date'])); ?></td>
                    <td>₹<?php echo number_format($bill['total_amount'], 2); ?></td>
                    <td>
                        <a href="./view_bill.php?id=<?php echo $bill['id']; ?>" class="btn btn-primary">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a href="./view_bill.php?id=<?php echo $bill['id']; ?>&print=true" 
                           class="btn btn-secondary" target="_blank">
                            <i class="fas fa-print"></i> Print
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <?php if (empty($bills)): ?>
        <div style="text-align: center; padding: 2rem; color: #666;">
            <i class="fas fa-file-invoice" style="font-size: 3rem; margin-bottom: 1rem;"></i>
            <p>No bills generated yet.</p>
            <a href="generate_bill.php" class="btn btn-primary">Generate Your First Bill</a>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>