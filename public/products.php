<?php
// public/products.php - Products Management
require_once '../includes/auth.php';
require_once '../includes/db.php';
requireLogin();

// Handle product deletion
if (isset($_GET['delete_id'])) {
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$_GET['delete_id']]);
    header('Location: ./products.php?message=Product deleted successfully');
    exit;
}

// Get all products
$products = $pdo->query("SELECT * FROM products ORDER BY id DESC")->fetchAll();
?>

<?php include '../includes/headers.php'; ?>

<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h1>Products Management</h1>
        <a href="./product_add.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Product
        </a>
    </div>

    <?php if (isset($_GET['message'])): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($_GET['message']); ?></div>
    <?php endif; ?>

    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Product Name</th>
                    <th>Description</th>
                    <th>Unit Price</th>
                    <th>Stock Qty</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                <tr>
                    <td><?php echo htmlspecialchars($product['sku']); ?></td>
                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                    <td><?php echo htmlspecialchars($product['description']); ?></td>
                    <td>₹<?php echo number_format($product['unit_price'], 2); ?></td>
                    <td>
                        <span class="status-badge <?php 
                            echo $product['stock_quantity'] < 5 ? 'status-critical' : 
                                ($product['stock_quantity'] < 10 ? 'status-low' : 'status-active'); 
                        ?>">
                            <?php echo $product['stock_quantity']; ?>
                        </span>
                    </td>
                    <td>
                        <a href="./product_edit.php?id=<?php echo $product['id']; ?>" class="btn btn-secondary">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="./products.php?delete_id=<?php echo $product['id']; ?>" 
                           class="btn btn-danger" 
                           onclick="return confirm('Are you sure you want to delete this product?')">
                            <i class="fas fa-trash"></i> Delete
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>