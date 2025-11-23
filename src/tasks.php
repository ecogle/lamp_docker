<?php
require_once 'db.php';

// Handle form submissions
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    try {
        switch ($action) {
            case 'create':
                $sql = "INSERT INTO tasks (title, description, status, priority, due_date) 
                        VALUES (:title, :description, :status, :priority, :due_date)";
                execute($sql, [
                    ':title' => $_POST['title'],
                    ':description' => $_POST['description'],
                    ':status' => $_POST['status'],
                    ':priority' => $_POST['priority'],
                    ':due_date' => $_POST['due_date'] ?: null
                ]);
                $message = 'Task created successfully!';
                break;
                
            case 'update':
                $sql = "UPDATE tasks 
                        SET title = :title, description = :description, 
                            status = :status, priority = :priority, due_date = :due_date
                        WHERE id = :id";
                execute($sql, [
                    ':id' => $_POST['id'],
                    ':title' => $_POST['title'],
                    ':description' => $_POST['description'],
                    ':status' => $_POST['status'],
                    ':priority' => $_POST['priority'],
                    ':due_date' => $_POST['due_date'] ?: null
                ]);
                $message = 'Task updated successfully!';
                break;
                
            case 'delete':
                $sql = "DELETE FROM tasks WHERE id = :id";
                execute($sql, [':id' => $_POST['id']]);
                $message = 'Task deleted successfully!';
                break;
                
            case 'complete':
                $sql = "UPDATE tasks SET status = 'completed' WHERE id = :id";
                execute($sql, [':id' => $_POST['id']]);
                $message = 'Task marked as completed!';
                break;
        }
    } catch (Exception $e) {
        $error = 'Error: ' . $e->getMessage();
    }
}

// Get all tasks
$tasks = query("SELECT * FROM tasks ORDER BY created_at DESC");

// Get task for editing if edit_id is provided
$editTask = null;
if (isset($_GET['edit'])) {
    $editTask = queryOne("SELECT * FROM tasks WHERE id = :id", [':id' => $_GET['edit']]);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager v1 - CRUD Application</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-4">
        <div class="bg-white rounded shadow-sm p-4">
            <h1 class="mb-4"><i class="bi bi-clipboard-check"></i> Task Manager v1 - CRUD Application</h1>
            
            <!-- Toast Container -->
            <div class="toast-container position-fixed top-0 end-0 p-3">
                <?php if ($message): ?>
                    <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="2000">
                        <div class="toast-header bg-success text-white">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            <strong class="me-auto">Success</strong>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                        <div class="toast-body">
                            <?= htmlspecialchars($message) ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="2000">
                        <div class="toast-header bg-danger text-white">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <strong class="me-auto">Error</strong>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                        <div class="toast-body">
                            <?= htmlspecialchars($error) ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <h2 class="mt-4 mb-3">
                <?= $editTask ? '<i class="bi bi-pencil-square"></i> Edit Task' : '<i class="bi bi-plus-circle"></i> Create New Task' ?>
            </h2>
            <form method="POST" action="tasks.php" class="bg-light p-4 rounded">
                <input type="hidden" name="action" value="<?= $editTask ? 'update' : 'create' ?>">
                <?php if ($editTask): ?>
                    <input type="hidden" name="id" value="<?= $editTask['id'] ?>">
                <?php endif; ?>
                
                <div class="mb-3">
                    <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="title" name="title" required 
                           value="<?= $editTask ? htmlspecialchars($editTask['title']) : '' ?>">
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"><?= $editTask ? htmlspecialchars($editTask['description']) : '' ?></textarea>
                </div>
                
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="pending" <?= $editTask && $editTask['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="in_progress" <?= $editTask && $editTask['status'] == 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                            <option value="completed" <?= $editTask && $editTask['status'] == 'completed' ? 'selected' : '' ?>>Completed</option>
                        </select>
                    </div>
                    
                    <div class="col-md-4">
                        <label for="priority" class="form-label">Priority</label>
                        <select class="form-select" id="priority" name="priority">
                            <option value="low" <?= $editTask && $editTask['priority'] == 'low' ? 'selected' : '' ?>>Low</option>
                            <option value="medium" <?= $editTask && $editTask['priority'] == 'medium' ? 'selected' : '' ?>>Medium</option>
                            <option value="high" <?= $editTask && $editTask['priority'] == 'high' ? 'selected' : '' ?>>High</option>
                        </select>
                    </div>
                    
                    <div class="col-md-4">
                        <label for="due_date" class="form-label">Due Date</label>
                        <input type="date" class="form-control" id="due_date" name="due_date" 
                               value="<?= $editTask ? htmlspecialchars($editTask['due_date']) : '' ?>">
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-<?= $editTask ? 'save' : 'plus-circle' ?>"></i>
                    <?= $editTask ? 'Update Task' : 'Create Task' ?>
                </button>
                <?php if ($editTask): ?>
                    <a href="tasks.php" class="btn btn-secondary ms-2">
                        <i class="bi bi-x-circle"></i> Cancel
                    </a>
                <?php endif; ?>
            </form>
            
            <h2 class="mt-4 mb-3">
                <i class="bi bi-list-task"></i> All Tasks 
                <span class="badge bg-secondary"><?= count($tasks) ?></span>
            </h2>
            
            <?php if (empty($tasks)): ?>
                <div class="alert alert-info text-center" role="alert">
                    <i class="bi bi-info-circle"></i> No tasks found. Create your first task above!
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Priority</th>
                                <th>Due Date</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tasks as $task): ?>
                                <tr>
                                    <td><?= $task['id'] ?></td>
                                    <td><strong><?= htmlspecialchars($task['title']) ?></strong></td>
                                    <td>
                                        <?php 
                                        $desc = htmlspecialchars($task['description']);
                                        echo strlen($desc) > 50 ? substr($desc, 0, 50) . '...' : $desc;
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        $statusClass = match($task['status']) {
                                            'pending' => 'warning',
                                            'in_progress' => 'info',
                                            'completed' => 'success',
                                            default => 'secondary'
                                        };
                                        ?>
                                        <span class="badge bg-<?= $statusClass ?>">
                                            <?= ucfirst(str_replace('_', ' ', $task['status'])) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php
                                        $priorityClass = match($task['priority']) {
                                            'low' => 'secondary',
                                            'medium' => 'warning',
                                            'high' => 'danger',
                                            default => 'secondary'
                                        };
                                        ?>
                                        <span class="badge bg-<?= $priorityClass ?>">
                                            <?= ucfirst($task['priority']) ?>
                                        </span>
                                    </td>
                                    <td><?= $task['due_date'] ? date('M d, Y', strtotime($task['due_date'])) : '-' ?></td>
                                    <td><?= date('M d, Y', strtotime($task['created_at'])) ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="tasks.php?edit=<?= $task['id'] ?>" class="btn btn-sm btn-success" style="width: 100px;">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" style="width: 100px;"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#deleteModal<?= $task['id'] ?>">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                            <?php if ($task['status'] !== 'completed'): ?>
                                                <form method="POST" style="display: inline;">
                                                    <input type="hidden" name="action" value="complete">
                                                    <input type="hidden" name="id" value="<?= $task['id'] ?>">
                                                    <button type="submit" class="btn btn-sm btn-primary" style="width: 100px;" title="Mark as completed">
                                                        <i class="bi bi-check-circle"></i> Complete
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <!-- Delete Confirmation Modal -->
                                        <div class="modal fade" id="deleteModal<?= $task['id'] ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?= $task['id'] ?>" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel<?= $task['id'] ?>">
                                                            <i class="bi bi-exclamation-triangle text-warning"></i> Confirm Delete
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Are you sure you want to delete the task <strong>"<?= htmlspecialchars($task['title']) ?>"</strong>?
                                                        <br><small class="text-muted">This action cannot be undone.</small>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                            <i class="bi bi-x-circle"></i> Cancel
                                                        </button>
                                                        <form method="POST" style="display: inline;">
                                                            <input type="hidden" name="action" value="delete">
                                                            <input type="hidden" name="id" value="<?= $task['id'] ?>">
                                                            <button type="submit" class="btn btn-danger">
                                                                <i class="bi bi-trash"></i> Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-hide toasts after 2 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const toastElements = document.querySelectorAll('.toast');
            toastElements.forEach(function(toastEl) {
                const toast = new bootstrap.Toast(toastEl);
                toast.show();
            });
        });
    </script>
</body>
</html>
