<?php
$page_title = 'Packages';
require_once 'includes/header.php';
require_once '../db.php';

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT package_name FROM tbl_packages WHERE package_id = $id"))['package_name'];
    mysqli_query($conn, "DELETE FROM tbl_packages WHERE package_id = $id");
    $action = "Deleted package: $name (ID: $id)";
    mysqli_query($conn, "INSERT INTO tbl_logs (user_id, action, datetime) VALUES ({$_SESSION['user_id']}, '$action', NOW())");
    header("Location: packages.php?msg=deleted");
    exit();
}

$search = $_GET['search'] ?? '';
$sql = "SELECT p.*, a.accommodation_name 
        FROM tbl_packages p 
        LEFT JOIN tbl_accommodations a ON p.accommodation_id = a.accommodation_id 
        WHERE 1=1";
if ($search) {
    $sql .= " AND (p.package_name LIKE '%$search%' OR p.description LIKE '%$search%')";
}
$sql .= " ORDER BY p.package_id DESC";
$result = mysqli_query($conn, $sql);
?>

<?php include 'includes/sidebar.php'; ?>

<div class="main-content">
    <div class="page-header">
        <h1>Packages</h1>
        <p>Manage resort packages and bundles</p>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php
            if ($_GET['msg'] == 'added') echo 'Package added successfully.';
            elseif ($_GET['msg'] == 'updated') echo 'Package updated successfully.';
            elseif ($_GET['msg'] == 'deleted') echo 'Package deleted successfully.';
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="table-card">
        <div class="table-header">
            <h2>All Packages</h2>
            <div class="table-actions">
                <form method="GET" class="search-form">
                    <input type="text" name="search" class="form-control" placeholder="Search packages..." value="<?= $search ?>">
                    <button type="submit" class="btn btn-outline-secondary btn-sm">Search</button>
                </form>
                <a href="add_package.php" class="btn btn-primary btn-sm">+ Add Package</a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Accommodation</th>
                        <th>Nights</th>
                        <th>Inclusions</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= $row['package_name'] ?></td>
                            <td><?= $row['description'] ?></td>
                            <td class="price">₱<?= number_format($row['price'], 2) ?></td>
                            <td><?= $row['accommodation_name'] ?? 'None' ?></td>
                            <td><?= $row['nights'] ?></td>
                            <td><small><?= $row['inclusion_details'] ?></small></td>
                            <td>
                                <a href="edit_package.php?id=<?= $row['package_id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="packages.php?delete=<?= $row['package_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this package?')">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <h3>No packages found</h3>
                                    <p>Create your first package to offer bundled deals.</p>
                                    <a href="add_package.php" class="btn btn-primary btn-sm">+ Add Package</a>
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
