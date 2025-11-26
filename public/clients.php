<?php
// public/clients.php - Clients Management
require_once '../includes/auth.php';
require_once '../includes/db.php';
requireLogin();

// Handle client deletion
if (isset($_GET['delete_id'])) {
    $stmt = $pdo->prepare("DELETE FROM clients WHERE id = ?");
    $stmt->execute([$_GET['delete_id']]);
    header('Location: clients.php?message=Client deleted successfully');
    exit;
}

// Get all clients
$clients = $pdo->query("SELECT * FROM clients ORDER BY id DESC")->fetchAll();
?>

<?php include '../includes/headers.php'; ?>

<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h1>Clients Management</h1>
        <a href="client_add.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Client
        </a>
    </div>

    <?php if (isset($_GET['message'])): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($_GET['message']); ?></div>
    <?php endif; ?>

    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clients as $client): ?>
                <tr>
                    <td><?php echo htmlspecialchars($client['name']); ?></td>
                    <td><?php echo htmlspecialchars($client['email']); ?></td>
                    <td><?php echo htmlspecialchars($client['phone']); ?></td>
                    <td><?php echo htmlspecialchars($client['address']); ?></td>
                    <td>
                        <a href="client_add.php?id=<?php echo $client['id']; ?>" class="btn btn-secondary">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="clients.php?delete_id=<?php echo $client['id']; ?>" 
                           class="btn btn-danger" 
                           onclick="return confirm('Are you sure you want to delete this client?')">
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