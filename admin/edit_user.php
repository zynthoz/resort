<?php
$page_title = 'Edit User';
require_once 'includes/header.php';
require_once '../db.php';

$id = (int)($_GET['id'] ?? 0);
$row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tbl_users WHERE user_id = $id"));

if (!$row) {
    header("Location: users.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $role = $_POST['role'];
    $username = $_POST['username'];
    $email = $_POST['email'];

    $sql = "UPDATE tbl_users SET full_name='$full_name', role='$role', username='$username', email='$email'";
    
    
    if (!empty($_POST['password'])) {
        $password = $_POST['password'];
        $sql .= ", password='$password'";
    }
    
    $sql .= " WHERE user_id = $id";
    
    if (mysqli_query($conn, $sql)) {
        $action = "Updated user: $full_name (ID: $id)";
        mysqli_query($conn, "INSERT INTO tbl_logs (user_id, action, datetime) VALUES ({$_SESSION['user_id']}, '$action', NOW())");
        header("Location: users.php?msg=updated");
        exit();
    }
}
?>

<?php include 'includes/sidebar.php'; ?>

<div class="main-content">
    <div class="page-header">
        <h1>Edit User</h1>
        <p>Update user information</p>
    </div>

    <div class="form-card card">
        <div class="card-header">
            <h2>User Details</h2>
        </div>
        <div class="card-body">
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="full_name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="full_name" name="full_name" required value="<?= $row['full_name'] ?>">
                </div>
                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-select" id="role" name="role" required>
                        <option value="admin" <?= $row['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                        <option value="employee" <?= $row['role'] == 'employee' ? 'selected' : '' ?>>Employee</option>
                        <option value="customer" <?= $row['role'] == 'customer' ? 'selected' : '' ?>>Customer</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required value="<?= $row['username'] ?>">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password <small class="text-muted">(leave blank to keep current)</small></label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>
                <div class="mb-4">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required value="<?= $row['email'] ?>">
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Update User</button>
                    <a href="users.php" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
