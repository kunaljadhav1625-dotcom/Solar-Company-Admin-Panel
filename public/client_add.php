<?php
// public/client_add.php - Add/Edit Client
require_once '../includes/auth.php';
require_once '../includes/db.php';
requireLogin();

$is_edit = false;
$client = null;

// Check if editing existing client
if (isset($_GET['id'])) {
    $is_edit = true;
    $client_id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM clients WHERE id = ?");
    $stmt->execute([$client_id]);
    $client = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$client) {
        header('Location: clients.php?message=Client not found');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    
    if ($is_edit) {
        $stmt = $pdo->prepare("UPDATE clients SET name = ?, email = ?, phone = ?, address = ? WHERE id = ?");
        $success = $stmt->execute([$name, $email, $phone, $address, $client_id]);
        $message = "Client updated successfully";
    } else {
        $stmt = $pdo->prepare("INSERT INTO clients (name, email, phone, address) VALUES (?, ?, ?, ?)");
        $success = $stmt->execute([$name, $email, $phone, $address]);
        $message = "Client added successfully";
    }
    
    if ($success) {
        header("Location: clients.php?message=$message");
        exit;
    } else {
        $error = "Failed to save client";
    }
}
?>

<?php include '../includes/headers.php'; ?>

<div class="container">
    <h1><?php echo $is_edit ? 'Edit Client' : 'Add New Client'; ?></h1>
    
    <?php if (isset($error)): ?>
    <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="card">
        <form method="POST">
            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" 
                       value="<?php echo $is_edit ? htmlspecialchars($client['name']) : ''; ?>" 
                       class="form-control" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" 
                       value="<?php echo $is_edit ? htmlspecialchars($client['email']) : ''; ?>" 
                       class="form-control">
            </div>
            
            <div class="form-group">
                <label class="form-label">Phone Number</label>
                <input type="tel" name="phone" 
                       value="<?php echo $is_edit ? htmlspecialchars($client['phone']) : ''; ?>" 
                       class="form-control" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Address</label>
                <textarea name="address" class="form-control" rows="3" required><?php 
                    echo $is_edit ? htmlspecialchars($client['address']) : ''; 
                ?></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary">
                <?php echo $is_edit ? 'Update Client' : 'Add Client'; ?>
            </button>
            <a href="clients.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>