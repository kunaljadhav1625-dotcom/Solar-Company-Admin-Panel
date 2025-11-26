
<?php
// public/generate_bill.php - Generate Bill
require_once '../includes/auth.php';
require_once '../includes/db.php';
requireLogin();

$error = '';
$success = '';

// Get all projects with client names and status
$projects = $pdo->query("SELECT p.*, c.name as client_name FROM projects p LEFT JOIN clients c ON p.client_id = c.id ORDER BY p.status, p.name")->fetchAll();
$products = $pdo->query("SELECT * FROM products WHERE stock_quantity > 0 ORDER BY name")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project_id = $_POST['project_id'];
    $bill_date = $_POST['bill_date'];
    $products_data = $_POST['products'];
    
    // Validate products
    $valid_products = [];
    $total_amount = 0;
    
    foreach ($products_data as $product) {
        if (!empty($product['product_id']) && $product['quantity'] > 0) {
            $product_id = $product['product_id'];
            $quantity = $product['quantity'];
            
            // Get product details
            $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
            $stmt->execute([$product_id]);
            $product_details = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($product_details && $product_details['stock_quantity'] >= $quantity) {
                $line_total = $product_details['unit_price'] * $quantity;
                $total_amount += $line_total;
                
                $valid_products[] = [
                    'product_id' => $product_id,
                    'quantity' => $quantity,
                    'unit_price' => $product_details['unit_price'],
                    'line_total' => $line_total
                ];
            } else {
                $error = "Insufficient stock for product: " . $product_details['name'];
                break;
            }
        }
    }
    
    if (empty($error)) {
        try {
            $pdo->beginTransaction();
            
            // Get client_id from project
            $stmt = $pdo->prepare("SELECT client_id FROM projects WHERE id = ?");
            $stmt->execute([$project_id]);
            $project = $stmt->fetch(PDO::FETCH_ASSOC);
            $client_id = $project['client_id'];
            
            // Generate bill number
            $bill_no = 'SOLAR-' . date('Ymd') . '-' . rand(1000, 9999);
            
            // Create bill
            $stmt = $pdo->prepare("INSERT INTO bills (bill_no, project_id, client_id, bill_date, total_amount) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$bill_no, $project_id, $client_id, $bill_date, $total_amount]);
            $bill_id = $pdo->lastInsertId();
            
            // Add bill items and update stock
            foreach ($valid_products as $item) {
                // Add bill item
                $stmt = $pdo->prepare("INSERT INTO bill_items (bill_id, product_id, quantity, unit_price, line_total) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$bill_id, $item['product_id'], $item['quantity'], $item['unit_price'], $item['line_total']]);
                
                // Update product stock
                $stmt = $pdo->prepare("UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ?");
                $stmt->execute([$item['quantity'], $item['product_id']]);
            }
            
            $pdo->commit();
            $success = "Bill generated successfully! Bill Number: " . $bill_no;
            header("Location: view_bill.php?id=" . $bill_id);
            exit;
            
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = "Failed to generate bill: " . $e->getMessage();
        }
    }
}
?>

<?php include '../includes/headers.php'; ?>

<div class="container">
    <h1>Generate New Bill</h1>
    
    <?php if ($error): ?>
    <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <div class="card">
        <form method="POST" id="billForm">
            <div class="form-group">
                <label class="form-label">Select Project</label>
                <select name="project_id" id="project_id" class="form-control" required>
                    <option value="">-- Select Project --</option>
                    <?php foreach ($projects as $project): ?>
                    <option value="<?php echo $project['id']; ?>" <?php echo $project['status'] !== 'Active' ? 'style="color: #666;"' : ''; ?>>
                        <?php echo htmlspecialchars($project['name'] . ' - ' . $project['client_name'] . ' (' . $project['status'] . ')'); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">Bill Date</label>
                <input type="date" name="bill_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
            
            <div id="products-section">
                <h3>Products</h3>
                <div class="product-row" style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr auto; gap: 10px; margin-bottom: 10px; align-items: end;">
                    <div>
                        <label class="form-label">Product</label>
                        <select name="products[0][product_id]" class="form-control product-select" required>
                            <option value="">-- Select Product --</option>
                            <?php foreach ($products as $product): ?>
                            <option value="<?php echo $product['id']; ?>" data-price="<?php echo $product['unit_price']; ?>">
                                <?php echo htmlspecialchars($product['name'] . ' - ₹' . $product['unit_price']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Quantity</label>
                        <input type="number" name="products[0][quantity]" class="form-control quantity-input" min="1" value="1" required>
                    </div>
                    <div>
                        <label class="form-label">Unit Price</label>
                        <input type="text" class="form-control unit-price" readonly>
                    </div>
                    <div>
                        <label class="form-label">Total</label>
                        <input type="text" class="form-control line-total" readonly>
                    </div>
                    <div>
                        <button type="button" class="btn btn-danger remove-product" style="display: none;">Remove</button>
                    </div>
                </div>
            </div>
            
            <button type="button" id="add-product" class="btn btn-secondary">
                <i class="fas fa-plus"></i> Add Another Product
            </button>
            
            <div style="margin-top: 2rem; padding-top: 1rem; border-top: 2px solid #1e3c72;">
                <h3>Grand Total: ₹<span id="grand-total">0.00</span></h3>
            </div>
            
            <div style="margin-top: 2rem;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-file-invoice"></i> Generate Bill
                </button>
                <a href="bills.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

<script>
let productCount = 1;

document.getElementById('add-product').addEventListener('click', function() {
    const productsSection = document.getElementById('products-section');
    const newRow = document.querySelector('.product-row').cloneNode(true);
    
    // Update indexes
    newRow.querySelectorAll('select, input').forEach(element => {
        if (element.name) {
            element.name = element.name.replace(/products\[\d+\]/, `products[${productCount}]`);
            element.value = '';
        }
    });
    
    // Show remove button for new rows
    newRow.querySelector('.remove-product').style.display = 'block';
    newRow.querySelector('.remove-product').onclick = function() {
        newRow.remove();
        calculateTotal();
    };
    
    // Add event listeners for new row
    addProductEventListeners(newRow);
    
    productsSection.appendChild(newRow);
    productCount++;
});

function addProductEventListeners(row) {
    const productSelect = row.querySelector('.product-select');
    const quantityInput = row.querySelector('.quantity-input');
    const unitPriceInput = row.querySelector('.unit-price');
    const lineTotalInput = row.querySelector('.line-total');
    
    function updateLineTotal() {
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const unitPrice = selectedOption ? parseFloat(selectedOption.getAttribute('data-price')) : 0;
        const quantity = parseInt(quantityInput.value) || 0;
        const lineTotal = unitPrice * quantity;
        
        unitPriceInput.value = unitPrice.toFixed(2);
        lineTotalInput.value = lineTotal.toFixed(2);
        
        calculateTotal();
    }
    
    productSelect.addEventListener('change', updateLineTotal);
    quantityInput.addEventListener('input', updateLineTotal);
}

function calculateTotal() {
    let grandTotal = 0;
    document.querySelectorAll('.line-total').forEach(input => {
        grandTotal += parseFloat(input.value) || 0;
    });
    document.getElementById('grand-total').textContent = grandTotal.toFixed(2);
}

// Add event listeners to initial row
document.querySelectorAll('.product-row').forEach(row => {
    addProductEventListeners(row);
});

// Initialize first row calculation
document.querySelector('.product-select').dispatchEvent(new Event('change'));
</script>