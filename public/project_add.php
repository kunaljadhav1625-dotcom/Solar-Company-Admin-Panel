<?php
// public/project_add.php - Add/Edit Project
require_once '../includes/auth.php';
require_once '../includes/db.php';
requireLogin();

$is_edit = false;
$project = null;

// Check if editing existing project
if (isset($_GET['id'])) {
    $is_edit = true;
    $project_id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT p.*, c.name as client_name FROM projects p LEFT JOIN clients c ON p.client_id = c.id WHERE p.id = ?");
    $stmt->execute([$project_id]);
    $project = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$project) {
        header('Location: projects.php?message=Project not found');
        exit;
    }
}

// Get all clients for dropdown
$clients = $pdo->query("SELECT * FROM clients ORDER BY name")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $client_id = !empty($_POST['client_id']) ? (int)$_POST['client_id'] : null;
    $description = trim($_POST['description']);
    $status = $_POST['status'];
    $start_date = !empty($_POST['start_date']) ? $_POST['start_date'] : null;
    $end_date = !empty($_POST['end_date']) ? $_POST['end_date'] : null;
    $budget = !empty($_POST['budget']) ? (float)$_POST['budget'] : 0.00;
    
    // Validation
    $error = '';
    if (empty($name)) {
        $error = "Project name is required";
    } elseif (empty($status)) {
        $error = "Status is required";
    } elseif (!in_array($status, ['Active', 'Completed', 'On Hold', 'Cancelled'])) {
        $error = "Invalid status selected";
    } elseif ($budget < 0) {
        $error = "Budget cannot be negative";
    } elseif ($start_date && !strtotime($start_date)) {
        $error = "Invalid start date format";
    } elseif ($end_date && !strtotime($end_date)) {
        $error = "Invalid end date format";
    } elseif ($start_date && $end_date && strtotime($start_date) > strtotime($end_date)) {
        $error = "End date must be after start date";
    }
    
    if (!$error) {
        try {
            if ($is_edit) {
                $stmt = $pdo->prepare("UPDATE projects SET name = ?, client_id = ?, description = ?, status = ?, start_date = ?, end_date = ?, budget = ? WHERE id = ?");
                $success = $stmt->execute([$name, $client_id, $description, $status, $start_date, $end_date, $budget, $project_id]);
                $message = "Project updated successfully";
            } else {
                $stmt = $pdo->prepare("INSERT INTO projects (name, client_id, description, status, start_date, end_date, budget) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $success = $stmt->execute([$name, $client_id, $description, $status, $start_date, $end_date, $budget]);
                $message = "Project added successfully";
            }
            
            if ($success) {
                header("Location: projects.php?message=$message");
                exit;
            } else {
                $error = "Failed to save project";
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>

<?php include '../includes/headers.php'; ?>

<div class="container">
    <h1><?php echo $is_edit ? 'Edit Project' : 'Add New Project'; ?></h1>
    
    <?php if (isset($error)): ?>
    <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="card">
        <form method="POST">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div class="form-group">
                    <label class="form-label">Project Name *</label>
                    <input type="text" name="name" 
                           value="<?php echo $is_edit ? htmlspecialchars($project['name']) : ''; ?>" 
                           class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Client *</label>
                    <select name="client_id" class="form-control" required>
                        <option value="">-- Select Client --</option>
                        <?php foreach ($clients as $client): ?>
                        <option value="<?php echo $client['id']; ?>" 
                            <?php echo ($is_edit && $project['client_id'] == $client['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($client['name']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3"><?php 
                    echo $is_edit ? htmlspecialchars($project['description']) : ''; 
                ?></textarea>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.5rem;">
                <div class="form-group">
                    <label class="form-label">Status *</label>
                    <select name="status" class="form-control" required>
                        <option value="Active" <?php echo ($is_edit && $project['status'] == 'Active') ? 'selected' : ''; ?>>Active</option>
                        <option value="Completed" <?php echo ($is_edit && $project['status'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                        <option value="On Hold" <?php echo ($is_edit && $project['status'] == 'On Hold') ? 'selected' : ''; ?>>On Hold</option>
                        <option value="Cancelled" <?php echo ($is_edit && $project['status'] == 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="start_date" 
                           value="<?php echo $is_edit ? $project['start_date'] : date('Y-m-d'); ?>" 
                           class="form-control">
                </div>
                
                <div class="form-group">
                    <label class="form-label">End Date</label>
                    <input type="date" name="end_date" 
                           value="<?php echo $is_edit ? $project['end_date'] : ''; ?>" 
                           class="form-control">
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Budget (₹)</label>
                <input type="number" name="budget" step="0.01" 
                       value="<?php echo $is_edit ? $project['budget'] : '0.00'; ?>" 
                       class="form-control">
            </div>
            
            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> 
                    <?php echo $is_edit ? 'Update Project' : 'Create Project'; ?>
                </button>
                <a href="projects.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>