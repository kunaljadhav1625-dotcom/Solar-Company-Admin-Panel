<?php
// public/product_edit.php - Edit Product
require_once '../includes/auth.php';
require_once '../includes/db.php';
requireLogin();

if (!isset($_GET['id'])) {
    header('Location: products.php');
    exit;
}

$product_id = $_GET['id'];
$product = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$product->execute([$product_id]);
$product = $product->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    header('Location: products.php?message=Product not found');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $unit_price = $_POST['unit_price'];
    $stock_quantity = $_POST['stock_quantity'];
    
    $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, unit_price = ?, stock_quantity = ? WHERE id = ?");
    
    if ($stmt->execute([$name, $description, $unit_price, $stock_quantity, $product_id])) {
        header('Location: products.php?message=Product updated successfully');
        exit;
    } else {
        $error = "Failed to update product";
    }
}
?>

<?php include '../includes/headers.php'; ?>

<div class="container">
    <h1>Edit Product</h1>
    
    <?php if (isset($error)): ?>
    <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="card">
        <form method="POST">
            <div class="form-group">
                <label class="form-label">SKU Code</label>
                <input type="text" value="<?php echo htmlspecialchars($product['sku']); ?>" class="form-control" readonly>
                <small>SKU cannot be changed</small>
            </div>
            
            <div class="form-group">
                <label class="form-label">Product Name</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3"><?php echo htmlspecialchars($product['description']); ?></textarea>
            </div>
            
            <div class="form-group">
                <label class="form-label">Unit Price (₹)</label>
                <input type="number" name="unit_price" step="0.01" value="<?php echo $product['unit_price']; ?>" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Stock Quantity</label>
                <input type="number" name="stock_quantity" value="<?php echo $product['stock_quantity']; ?>" class="form-control" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Update Product</button>
            <a href="products.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>