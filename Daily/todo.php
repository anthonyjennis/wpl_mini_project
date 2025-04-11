<?php
session_start();

// DB Config
$host = "localhost";
$username = "root";
$password = "";
$dbname = "Daily";

// Connect
$conn = mysqli_connect($host, $username, $password, $dbname);
if (!$conn) die("DB connection failed: " . mysqli_connect_error());

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit;
}

// Get the user ID from the session
$user_id = $_SESSION['user_id'];

// Handle category add
if (isset($_POST['add_category'])) {
    $name = mysqli_real_escape_string($conn, $_POST['category_name']);
    mysqli_query($conn, "INSERT INTO categories (user_id, name) VALUES ($user_id, '$name')");
    header("Location: todo.php"); exit;
}

// Handle category update
if (isset($_POST['update_category'])) {
    $id = intval($_POST['cat_id']);
    $name = mysqli_real_escape_string($conn, $_POST['new_name']);
    mysqli_query($conn, "UPDATE categories SET name='$name' WHERE id=$id AND user_id=$user_id");
    header("Location: todo.php"); exit;
}

// Handle category delete
if (isset($_GET['delete_category'])) {
    $id = intval($_GET['delete_category']);
    mysqli_query($conn, "DELETE FROM categories WHERE id=$id AND user_id=$user_id");
    header("Location: todo.php"); exit;
}

// Handle todo add
if (isset($_POST['add_todo'])) {
    $title = mysqli_real_escape_string($conn, $_POST['todo_title']);
    $cat_id = intval($_POST['todo_category']);
    mysqli_query($conn, "INSERT INTO todo (user_id, category_id, title) VALUES ($user_id, $cat_id, '$title')");
    header("Location: todo.php"); exit;
}

// Handle todo delete
if (isset($_GET['delete_todo'])) {
    $id = intval($_GET['delete_todo']);
    mysqli_query($conn, "DELETE FROM todo WHERE id=$id AND user_id=$user_id");
    header("Location: todo.php"); exit;
}

// Handle todo status update
if (isset($_POST['update_todo_status'])) {
    $todo_id = intval($_POST['todo_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    mysqli_query($conn, "UPDATE todo SET status='$status' WHERE id=$todo_id AND user_id=$user_id");
    // Redirect to reload the page and reflect changes
    header("Location: todo.php"); exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>To-Do with Categories</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="styles.css" class="css">
    <link rel="stylesheet" href="css/todo.css">
</head>
<body>

<header>
    <nav class="navbar">
      <div class="logo"><a href="index.php">Daily</a></div>
      <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
          <li><a href="todo.php">To-Do</a></li>
          <li><a href="rss.php">RSS Feed</a></li>
          <li><a href="tracker.php">Tracker</a></li>
          <li><a href="notes.php">Notes</a></li>
          <li><a href="about.php">About</a></li>
          <li><a href="contact.php">Contact</a></li>
          <li><a href="logout.php" class="btn logout-btn">Sign Out</a></li>
        <?php else: ?>
          <li><a href="about.php">About</a></li>
          <li><a href="contact.php">Contact</a></li>
          <li><a href="login.php" class="btn login-btn">Login</a></li>
          <li><a href="signup.php" class="btn signup-btn">Sign Up</a></li>
        <?php endif; ?>
      </ul>
    </nav>
</header>

<div class="container">
    <h2 class="page-title"><i class="fas fa-tasks"></i> My To-Do Dashboard</h2>
    
    <!-- Add Category Button -->
    <div class="text-end mb-4">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            <i class="fas fa-plus"></i> New Category
        </button>
    </div>

    <!-- Category Navigation -->
    <div class="category-nav" id="category-nav">
        <div class="category-box active" data-category="all">All Categories</div>
        <?php
        $cat_query = mysqli_query($conn, "SELECT * FROM categories WHERE user_id = $user_id ORDER BY name ASC");
        while ($cat = mysqli_fetch_assoc($cat_query)): 
        ?>
            <div class="category-box" data-category="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></div>
        <?php endwhile; ?>
    </div>

    <!-- Categories and Todos -->
    <?php
    $categories = mysqli_query($conn, "SELECT * FROM categories WHERE user_id = $user_id ORDER BY created_at DESC");

    while ($cat = mysqli_fetch_assoc($categories)):
        $cat_id = $cat['id'];
        $todos = mysqli_query($conn, "SELECT * FROM todo WHERE user_id = $user_id AND category_id = $cat_id ORDER BY created_at DESC");
    ?>
    <div class="category-container" data-category-id="<?= $cat_id ?>">
        <div class="category-header">
            <h3 class="category-title"><?= htmlspecialchars($cat['name']) ?></h3>
            <div>
                <button class="btn btn-sm btn-light edit-category-btn" data-category-id="<?= $cat_id ?>">
                    <i class="fas fa-edit"></i>
                </button>
                <a href="?delete_category=<?= $cat_id ?>" class="btn btn-sm btn-light" onclick="return confirm('Are you sure you want to delete this category?')">
                    <i class="fas fa-trash"></i>
                </a>
            </div>
        </div>
        
        <div class="category-edit-form" id="edit-form-<?= $cat_id ?>">
            <form method="POST" class="row g-3">
                <input type="hidden" name="cat_id" value="<?= $cat_id ?>">
                <div class="col-md-8">
                    <input type="text" name="new_name" value="<?= htmlspecialchars($cat['name']) ?>" class="form-control" placeholder="Category name">
                </div>
                <div class="col-md-4">
                    <button name="update_category" class="btn btn-success w-100">Update</button>
                </div>
            </form>
        </div>
        
        <div class="category-body">
            <!-- Todo Items -->
            <div class="todo-list">
                <?php $todo_count = 0; while ($todo = mysqli_fetch_assoc($todos)): $todo_count++; ?>
                    <div class="todo-item">
                        <div class="todo-title <?= 'status-' . str_replace('_', '-', $todo['status']) ?>">
                            <?php if ($todo['status'] == 'completed'): ?>
                                <i class="fas fa-check-circle"></i>
                            <?php elseif ($todo['status'] == 'in_progress'): ?>
                                <i class="fas fa-spinner"></i>
                            <?php else: ?>
                                <i class="far fa-circle"></i>
                            <?php endif; ?>
                            <?= htmlspecialchars($todo['title']) ?>
                        </div>
                        <div class="todo-actions">
                            <form method="POST">
                                <input type="hidden" name="todo_id" value="<?= $todo['id'] ?>">
                                <input type="hidden" name="update_todo_status" value="1">
                                <select name="status" class="form-select form-select-sm todo-status" onchange="this.form.submit()">
                                    <option value="pending" <?= $todo['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="in_progress" <?= $todo['status'] == 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                                    <option value="completed" <?= $todo['status'] == 'completed' ? 'selected' : '' ?>>Completed</option>
                                </select>
                            </form>
                            <a href="?delete_todo=<?= $todo['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this task?')">
                                <i class="fas fa-times"></i>
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
                
                <?php if ($todo_count == 0): ?>
                <div class="text-center text-muted py-4">
                    <i class="fas fa-clipboard fa-2x mb-2"></i>
                    <p>No tasks in this category yet</p>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Add Todo Form -->
            <div class="add-form">
                <form method="POST" class="row g-3">
                    <input type="hidden" name="todo_category" value="<?= $cat_id ?>">
                    <div class="col-md-9">
                        <input type="text" name="todo_title" placeholder="Add new task..." class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <button name="add_todo" class="btn btn-add w-100">
                            <i class="fas fa-plus"></i> Add Task
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endwhile; ?>
</div>

<!-- Enhanced Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-folder-plus"></i> Create New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <div class="mb-4">
                        <label for="category_name" class="form-label category-form-label">
                            <i class="fas fa-tag me-2"></i>Category Name
                        </label>
                        <input type="text" class="form-control category-input" id="category_name" name="category_name" 
                            placeholder="Enter category name..." required>
                        <div class="form-text text-muted mt-2">
                            <i class="fas fa-info-circle me-1"></i>
                            A category helps you organize related tasks together.
                        </div>
                    </div>
                    <div class="form-footer">
                        <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i> Cancel
                        </button>
                        <button type="submit" name="add_category" class="btn btn-modal-submit">
                            <i class="fas fa-check me-1"></i> Create Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Category navigation functionality
    const categoryBoxes = document.querySelectorAll('.category-box');
    const categoryContainers = document.querySelectorAll('.category-container');
    
    categoryBoxes.forEach(box => {
        box.addEventListener('click', function() {
            // Remove active class from all boxes
            categoryBoxes.forEach(b => b.classList.remove('active'));
            
            // Add active class to clicked box
            this.classList.add('active');
            
            const selectedCategory = this.getAttribute('data-category');
            
            // Show/hide category containers based on selection
            if (selectedCategory === 'all') {
                categoryContainers.forEach(container => {
                    container.style.display = 'block';
                });
            } else {
                categoryContainers.forEach(container => {
                    if (container.getAttribute('data-category-id') === selectedCategory) {
                        container.style.display = 'block';
                    } else {
                        container.style.display = 'none';
                    }
                });
            }
        });
    });
    
    // Edit category buttons
    const editBtns = document.querySelectorAll('.edit-category-btn');
    editBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const categoryId = this.getAttribute('data-category-id');
            const editForm = document.getElementById('edit-form-' + categoryId);
            
            if (editForm.style.display === 'block') {
                editForm.style.display = 'none';
            } else {
                editForm.style.display = 'block';
            }
        });
    });
});
</script>
</body>
</html>
