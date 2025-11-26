<?php
// public/view_bill.php - View Bill Details
require_once '../includes/auth.php';
require_once '../includes/db.php';
requireLogin();

if (!isset($_GET['id'])) {
    header('Location: bills.php');
    exit;
}

$bill_id = $_GET['id'];
$print_mode = isset($_GET['print']);

// Get bill details
$stmt = $pdo->prepare("
    SELECT b.*, c.name as client_name, c.email as client_email, c.phone as client_phone, c.address as client_address,
           p.name as project_name 
    FROM bills b 
    LEFT JOIN clients c ON b.client_id = c.id 
    LEFT JOIN projects p ON b.project_id = p.id 
    WHERE b.id = ?
");
$stmt->execute([$bill_id]);
$bill = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$bill) {
    header('Location: bills.php?message=Bill not found');
    exit;
}

// Get bill items
$items = $pdo->prepare("
    SELECT bi.*, p.name as product_name, p.sku 
    FROM bill_items bi 
    LEFT JOIN products p ON bi.product_id = p.id 
    WHERE bi.bill_id = ?
");
$items->execute([$bill_id]);
$bill_items = $items->fetchAll();

if ($print_mode) {
    header('Content-Type: text/html; charset=utf-8');
}
?>

<?php if (!$print_mode) include '../includes/headers.php'; ?>

<style>
    <?php if ($print_mode): ?>
    @media print {
        body { margin: 0; padding: 0; }
        .no-print { display: none !important; }
        .bill-container { box-shadow: none; border: none; }
        .bill-header { border: 2px solid #000; }
    }
    <?php endif; ?>

    .bill-container {
        max-width: 800px;
        margin: 0 auto;
        background: white;
        padding: 2rem;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
    }
    
    .bill-header {
        border: 2px solid #1e3c72;
        padding: 2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    }
    
    .company-info {
        text-align: center;
        margin-bottom: 2rem;
    }
    
    .company-logo {
        font-size: 3rem;
        margin-bottom: 1rem;
    }
    
    .bill-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }
    
    .bill-table {
        width: 100%;
        border-collapse: collapse;
        margin: 2rem 0;
    }
    
    .bill-table th,
    .bill-table td {
        padding: 12px;
        border: 1px solid #ddd;
        text-align: left;
    }
    
    .bill-table th {
        background: #1e3c72;
        color: white;
    }
    
    .total-section {
        text-align: right;
        margin-top: 2rem;
        padding-top: 1rem;
        border-top: 2px solid #1e3c72;
    }
    
    .grand-total {
        font-size: 1.5rem;
        font-weight: bold;
        color: #1e3c72;
    }
    
    .footer-note {
        margin-top: 3rem;
        padding-top: 1rem;
        border-top: 1px solid #ddd;
        text-align: center;
        color: #666;
    }
</style>

<div class="container">
    <div class="no-print" style="margin-bottom: 2rem;">
        <a href="bills.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Bills
        </a>
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fas fa-print"></i> Print Bill
        </button>
        <a href="view_bill.php?id=<?php echo $bill_id; ?>&print=true" 
           class="btn btn-secondary" target="_blank">
            <i class="fas fa-external-link-alt"></i> Print View
        </a>
    </div>

    <div class="bill-container">
        <!-- Bill Header -->
        <div class="bill-header">
            <div class="company-info">
                <div class="company-logo">☀️</div>
                <h1 style="color: #1e3c72; margin: 0;">SolarTech Pro</h1>
                <p style="color: #2a5298; margin: 0.5rem 0;">Powering Tomorrow with Clean Energy</p>
                <p style="margin: 0;">Solar Energy Park, Sector 15, Gurgaon, Haryana 122001</p>
                <p style="margin: 0;">Phone: +91-9876543210 | Email: info@solartech.com</p>
                <p style="margin: 0;">GSTIN: 07AABCU9603R1ZM</p>
            </div>
            
            <div style="text-align: center;">
                <h2 style="color: #f7971e; margin: 0; font-size: 2rem;">TAX INVOICE</h2>
                <p style="font-size: 1.2rem; margin: 0.5rem 0;">Bill No: <strong><?php echo $bill['bill_no']; ?></strong></p>
                <p style="margin: 0;">Date: <?php echo date('F d, Y', strtotime($bill['bill_date'])); ?></p>
            </div>
        </div>

        <!-- Bill Details -->
        <div class="bill-details">
            <div>
                <h3 style="color: #1e3c72; border-bottom: 2px solid #f7971e; padding-bottom: 0.5rem;">Bill To:</h3>
                <p><strong><?php echo htmlspecialchars($bill['client_name']); ?></strong></p>
                <p><?php echo htmlspecialchars($bill['client_address']); ?></p>
                <p>Phone: <?php echo htmlspecialchars($bill['client_phone']); ?></p>
                <p>Email: <?php echo htmlspecialchars($bill['client_email']); ?></p>
            </div>
            
            <div>
                <h3 style="color: #1e3c72; border-bottom: 2px solid #f7971e; padding-bottom: 0.5rem;">Project Details:</h3>
                <p><strong><?php echo htmlspecialchars($bill['project_name']); ?></strong></p>
                <p>Bill Date: <?php echo date('F d, Y', strtotime($bill['bill_date'])); ?></p>
                <p>Due Date: <?php echo date('F d, Y', strtotime($bill['bill_date'] . ' +15 days')); ?></p>
            </div>
        </div>

        <!-- Bill Items -->
        <table class="bill-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product Code</th>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Unit Price (₹)</th>
                    <th>Amount (₹)</th>
                </tr>
            </thead>
            <tbody>
                <?php $counter = 1; ?>
                <?php foreach ($bill_items as $item): ?>
                <tr>
                    <td><?php echo $counter++; ?></td>
                    <td><?php echo htmlspecialchars($item['sku']); ?></td>
                    <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>₹<?php echo number_format($item['unit_price'], 2); ?></td>
                    <td>₹<?php echo number_format($item['line_total'], 2); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Total Section -->
        <div class="total-section">
            <div style="display: inline-block; text-align: left;">
                <p style="margin: 0.5rem 0;">Subtotal: ₹<?php echo number_format($bill['total_amount'], 2); ?></p>
                <p style="margin: 0.5rem 0;">GST (18%): ₹<?php echo number_format($bill['total_amount'] * 0.18, 2); ?></p>
                <p class="grand-total">Grand Total: ₹<?php echo number_format($bill['total_amount'] * 1.18, 2); ?></p>
            </div>
        </div>

        <!-- Footer Notes -->
        <div class="footer-note">
            <p><strong>Terms & Conditions:</strong></p>
            <p>• Payment due within 15 days from invoice date</p>
            <p>• Warranty: 5 years on solar panels, 2 years on inverters</p>
            <p>• Installation included in project cost</p>
            <p>• For queries, contact: +91-9876543210</p>
            
            <div style="margin-top: 2rem;">
                <p>Authorized Signature</p>
                <div style="border-top: 1px solid #000; width: 200px; margin: 0 auto; padding-top: 2rem;">
                    <p>For SolarTech Pro</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if (!$print_mode) include '../includes/footer.php'; ?>