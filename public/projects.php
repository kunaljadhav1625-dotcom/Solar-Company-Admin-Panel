<?php
// public/projects.php - Projects Management
require_once '../includes/auth.php';
require_once '../includes/db.php';
requireLogin();

// Handle project deletion
if (isset($_GET['delete_id'])) {
    $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
    $stmt->execute([$_GET['delete_id']]);
    header('Location: projects.php?message=Project deleted successfully');
    exit;
}

// Get all projects with client names
$projects = $pdo->query("
    SELECT p.*, c.name as client_name 
    FROM projects p 
    LEFT JOIN clients c ON p.client_id = c.id 
    ORDER BY p.id DESC
")->fetchAll();
?>

<?php include '../includes/headers.php'; ?>

<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h1>Projects Management</h1>
        <a href="./project_add.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Project
        </a>
    </div>

    <?php if (isset($_GET['message'])): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($_GET['message']); ?></div>
    <?php endif; ?>

    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>Project Name</th>
                    <th>Client</th>
                    <th>Created Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($projects as $project): ?>
                <tr>
                    <td><?php echo htmlspecialchars($project['name']); ?></td>
                    <td><?php echo htmlspecialchars($project['client_name']); ?></td>
                    <td><?php echo date('M d, Y', strtotime($project['created_date'])); ?></td>
                    <td>
                        <span class="status-badge status-active">
                            <?php echo htmlspecialchars($project['status']); ?>
                        </span>
                    </td>
                    <td>
                        <a href="project_add.php?id=<?php echo $project['id']; ?>" class="btn btn-secondary">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="projects.php?delete_id=<?php echo $project['id']; ?>" 
                           class="btn btn-danger" 
                           onclick="return confirm('Are you sure you want to delete this project?')">
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