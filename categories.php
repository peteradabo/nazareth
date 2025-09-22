<?php
// Database connection
$host = 'localhost';
$db   = 'your_database';
$user = 'your_username';
$pass = 'your_password';

$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Handle ADD
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $stmt = $pdo->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
    $stmt->execute([$name, $description]);
    header("Location: categories.php");
    exit;
}

// Handle EDIT
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $stmt = $pdo->prepare("UPDATE categories SET name=?, description=? WHERE id=?");
    $stmt->execute([$name, $description, $id]);
    header("Location: categories.php");
    exit;
}

// Handle DELETE
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id=?");
    $stmt->execute([$id]);
    header("Location: categories.php");
    exit;
}

// Fetch categories
$stmt = $pdo->query("SELECT * FROM categories ORDER BY id DESC");
$categories = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Categories CRUD</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2>Categories</h2>
    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addModal">Add Category</button>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($categories as $cat): ?>
            <tr>
                <td><?= htmlspecialchars($cat['id']) ?></td>
                <td><?= htmlspecialchars($cat['name']) ?></td>
                <td><?= htmlspecialchars($cat['description']) ?></td>
                <td><?= htmlspecialchars($cat['created']) ?></td>
                <td>
                    <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#editModal<?= $cat['id'] ?>">Edit</button>
                    <a href="?delete=<?= $cat['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this category?')">Delete</a>
                </td>
            </tr>

            <!-- Edit Modal -->
            <div class="modal fade" id="editModal<?= $cat['id'] ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <form method="post">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Edit Category</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                      </div>
                      <div class="modal-body">
                        <input type="hidden" name="id" value="<?= $cat['id'] ?>">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" value="<?= htmlspecialchars($cat['name']) ?>" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control"><?= htmlspecialchars($cat['description']) ?></textarea>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="submit" name="edit" class="btn btn-success">Save Changes</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                      </div>
                    </div>
                </form>
              </div>
            </div>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="post">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Add Category</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" name="add" class="btn btn-primary">Add</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          </div>
        </div>
    </form>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>