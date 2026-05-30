<?php
$page_title = 'Add User';
require_once 'includes/header.php';
require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $role = $_POST['role'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    $sql = "INSERT INTO tbl_users (full_name, role, username, password, email) VALUES ('$full_name', '$role', '$username', '$password', '$email')";
    if (mysqli_query($conn, $sql)) {
        $action = "Added user: $full_name ($role)";
        mysqli_query($conn, "INSERT INTO tbl_logs (user_id, action, datetime) VALUES ({$_SESSION['user_id']}, '$action', NOW())");
        header("Location: users.php?msg=added");
        exit();
    }
}
?>

<?php include 'includes/sidebar.php'; ?>

<div class="main-content">
    <div class="page-header">
        <h1>Add User</h1>
        <p>Create a new system user</p>
    </div>

    <div class="form-card card">
        <div class="card-header">
            <h2>User Details</h2>
        </div>
        <div class="card-body">
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="full_name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="full_name" name="full_name" required>
                </div>
                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-select" id="role" name="role" required>
                        <option value="">Select role</option>
                        <option value="admin">Admin</option>
                        <option value="employee">Employee</option>
                        <option value="customer">Customer</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-4">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Save User</button>
                    <a href="users.php" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
