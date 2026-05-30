<?php
$page_title = 'Amenities';
require_once 'includes/header.php';
require_once '../db.php';

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT amenity_name FROM tbl_amenities WHERE amenity_id = $id"))['amenity_name'];
    mysqli_query($conn, "DELETE FROM tbl_amenities WHERE amenity_id = $id");
    $action = "Deleted amenity: $name (ID: $id)";
    mysqli_query($conn, "INSERT INTO tbl_logs (user_id, action, datetime) VALUES ({$_SESSION['user_id']}, '$action', NOW())");
    header("Location: amenities.php?msg=deleted");
    exit();
}

$search = $_GET['search'] ?? '';
$sql = "SELECT * FROM tbl_amenities WHERE 1=1";
if ($search) $sql .= " AND (amenity_name LIKE '%$search%' OR description LIKE '%$search%')";
$sql .= " ORDER BY amenity_id DESC";
$result = mysqli_query($conn, $sql);
?>

<?php include 'includes/sidebar.php'; ?>

<div class="main-content">
    <div class="page-header"><h1>Amenities</h1><p>Manage resort amenities and services</p></div>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php if ($_GET['msg'] == 'added') echo 'Amenity added successfully.'; elseif ($_GET['msg'] == 'updated') echo 'Amenity updated successfully.'; elseif ($_GET['msg'] == 'deleted') echo 'Amenity deleted successfully.'; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="table-card">
        <div class="table-header">
            <h2>All Amenities</h2>
            <div class="table-actions">
                <form method="GET" class="search-form"><input type="text" name="search" class="form-control" placeholder="Search amenities..." value="<?= $search ?>"><button type="submit" class="btn btn-outline-secondary btn-sm">Search</button></form>
                <a href="add_amenity.php" class="btn btn-primary btn-sm">+ Add Amenity</a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th>Name</th><th>Description</th><th>Price / Use</th><th>Actions</th></tr></thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $row['amenity_name'] ?></td>
                        <td><?= $row['description'] ?></td>
                        <td class="price"><?= $row['price_per_use'] > 0 ? '₱' . number_format($row['price_per_use'], 2) : 'Free' ?></td>
                        <td>
                            <a href="edit_amenity.php?id=<?= $row['amenity_id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="amenities.php?delete=<?= $row['amenity_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this amenity?')">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; else: ?>
                    <tr><td colspan="4"><div class="empty-state"><h3>No amenities found</h3><p>Add your first amenity to get started.</p><a href="add_amenity.php" class="btn btn-primary btn-sm">+ Add Amenity</a></div></td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
