<?php
// public/product_add.php - Add New Product
require_once '../includes/auth.php';
require_once '../includes/db.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sku = $_POST['sku'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $unit_price = $_POST['unit_price'];
    $stock_quantity = $_POST['stock_quantity'];
    
    $stmt = $pdo->prepare("INSERT INTO products (sku, name, description, unit_price, stock_quantity) VALUES (?, ?, ?, ?, ?)");
    
    if ($stmt->execute([$sku, $name, $description, $unit_price, $stock_quantity])) {
        header('Location: products.php?message=Product added successfully');
        exit;
    } else {
        $error = "Failed to add product";
    }
}
?>

<?php include '../includes/headers.php'; ?>

<div class="container">
    <h1>Add New Product</h1>
    
    <?php if (isset($error)): ?>
    <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="card">
        <form method="POST">
            <div class="form-group">
                <label class="form-label">SKU Code</label>
                <input type="text" name="sku" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Product Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>
            
            <div class="form-group">
                <label class="form-label">Unit Price (₹)</label>
                <input type="number" name="unit_price" step="0.01" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Stock Quantity</label>
                <input type="number" name="stock_quantity" class="form-control" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Add Product</button>
            <a href="products.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>