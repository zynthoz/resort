<?php
$page_title = 'Users';
require_once 'includes/header.php';
require_once '../db.php';

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM tbl_users WHERE user_id = $id");
    $action = "Deleted user ID $id";
    mysqli_query($conn, "INSERT INTO tbl_logs (user_id, action, datetime) VALUES ({$_SESSION['user_id']}, '$action', NOW())");
    header("Location: users.php?msg=deleted");
    exit();
}

$search = $_GET['search'] ?? '';
$role_filter = $_GET['role'] ?? '';

$sql = "SELECT * FROM tbl_users WHERE 1=1";
if ($search) {
    $sql .= " AND (full_name LIKE '%$search%' OR username LIKE '%$search%')";
}
if ($role_filter) {
    $sql .= " AND role = '$role_filter'";
}
$sql .= " ORDER BY user_id DESC";
$result = mysqli_query($conn, $sql);
?>

<?php include 'includes/sidebar.php'; ?>

<div class="main-content">
    <div class="page-header">
        <h1>Users</h1>
        <p>Manage system users and their roles</p>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php
            if ($_GET['msg'] == 'added') echo 'User added successfully.';
            elseif ($_GET['msg'] == 'updated') echo 'User updated successfully.';
            elseif ($_GET['msg'] == 'deleted') echo 'User deleted successfully.';
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="table-card">
        <div class="table-header">
            <h2>All Users</h2>
            <div class="table-actions">
                <form method="GET" class="search-form">
                    <input type="text" name="search" class="form-control" placeholder="Search users..." value="<?= $search ?>">
                    <?php if ($role_filter): ?>
                        <input type="hidden" name="role" value="<?= $role_filter ?>">
                    <?php endif; ?>
                    <button type="submit" class="btn btn-outline-secondary btn-sm">Search</button>
                </form>
                <form method="GET" class="d-inline">
                    <?php if ($search): ?>
                        <input type="hidden" name="search" value="<?= $search ?>">
                    <?php endif; ?>
                    <select name="role" class="form-select filter-select" onchange="this.form.submit()">
                        <option value="">All Roles</option>
                        <option value="admin" <?= $role_filter == 'admin' ? 'selected' : '' ?>>Admin</option>
                        <option value="employee" <?= $role_filter == 'employee' ? 'selected' : '' ?>>Employee</option>
                        <option value="customer" <?= $role_filter == 'customer' ? 'selected' : '' ?>>Customer</option>
                    </select>
                </form>
                <a href="add_user.php" class="btn btn-primary btn-sm">+ Add User</a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Role</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= $row['full_name'] ?></td>
                            <td><span class="badge-role <?= $row['role'] ?>"><?= ucfirst($row['role']) ?></span></td>
                            <td><?= $row['username'] ?></td>
                            <td><?= $row['email'] ?></td>
                            <td>
                                <a href="edit_user.php?id=<?= $row['user_id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="users.php?delete=<?= $row['user_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <h3>No users found</h3>
                                    <p>Try adjusting your search or add a new user.</p>
                                    <a href="add_user.php" class="btn btn-primary btn-sm">+ Add User</a>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
