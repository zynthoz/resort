<?php
$page_title = 'Accommodations';
require_once 'includes/header.php';
require_once '../db.php';

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $room_row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT accommodation_name, image_url FROM tbl_accommodations WHERE accommodation_id = $id"));
    if ($room_row) {
        $name = $room_row['accommodation_name'];
        $image_url = $room_row['image_url'];
        
        
        mysqli_query($conn, "DELETE FROM tbl_accommodations WHERE accommodation_id = $id");
        
        
        if (!empty($image_url) && strpos($image_url, 'images/') === 0) {
            $file_path = '../' . $image_url;
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
        
        $action = "Deleted accommodation: $name (ID: $id)";
        mysqli_query($conn, "INSERT INTO tbl_logs (user_id, action, datetime) VALUES ({$_SESSION['user_id']}, '$action', NOW())");
    }
    header("Location: accommodations.php?msg=deleted");
    exit();
}

$search = $_GET['search'] ?? '';
$status_filter = $_GET['status'] ?? '';

$sql = "SELECT * FROM tbl_accommodations WHERE 1=1";
if ($search) {
    $sql .= " AND accommodation_name LIKE '%$search%'";
}
if ($status_filter) {
    $sql .= " AND availability_status = '$status_filter'";
}
$sql .= " ORDER BY accommodation_id DESC";
$result = mysqli_query($conn, $sql);
?>

<?php include 'includes/sidebar.php'; ?>

<div class="main-content">
    <div class="page-header">
        <h1>Accommodations</h1>
        <p>Manage rooms, villas, and other accommodations</p>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php
            if ($_GET['msg'] == 'added') echo 'Accommodation added successfully.';
            elseif ($_GET['msg'] == 'updated') echo 'Accommodation updated successfully.';
            elseif ($_GET['msg'] == 'deleted') echo 'Accommodation deleted successfully.';
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="table-card">
        <div class="table-header">
            <h2>All Accommodations</h2>
            <div class="table-actions">
                <form method="GET" class="search-form">
                    <input type="text" name="search" class="form-control" placeholder="Search accommodations..." value="<?= $search ?>">
                    <?php if ($status_filter): ?>
                        <input type="hidden" name="status" value="<?= $status_filter ?>">
                    <?php endif; ?>
                    <button type="submit" class="btn btn-outline-secondary btn-sm">Search</button>
                </form>
                <form method="GET" class="d-inline">
                    <?php if ($search): ?>
                        <input type="hidden" name="search" value="<?= $search ?>">
                    <?php endif; ?>
                    <select name="status" class="form-select filter-select" onchange="this.form.submit()">
                        <option value="">All Status</option>
                        <option value="available" <?= $status_filter == 'available' ? 'selected' : '' ?>>Available</option>
                        <option value="unavailable" <?= $status_filter == 'unavailable' ? 'selected' : '' ?>>Unavailable</option>
                    </select>
                </form>
                <a href="add_accommodation.php" class="btn btn-primary btn-sm">+ Add Accommodation</a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th style="width: 80px;">Image</th>
                        <th>Name</th>
                        <th>Capacity</th>
                        <th>Price / Night</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td>
                                <?php if (!empty($row['image_url'])): ?>
                                    <img src="<?= (strpos($row['image_url'], 'http') === 0) ? $row['image_url'] : '../' . $row['image_url'] ?>" alt="Room Thumbnail" style="width: 60px; height: 45px; object-fit: cover; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                                <?php else: ?>
                                    <div style="width: 60px; height: 45px; background-color: #eee; border-radius: 4px; display: flex; align-items: center; justify-content: center; font-size: 0.65rem; color: #888;">No Image</div>
                                <?php endif; ?>
                            </td>
                            <td class="fw-bold"><?= $row['accommodation_name'] ?></td>
                            <td><?= $row['capacity'] ?> guests</td>
                            <td class="price">₱<?= number_format($row['price_per_night'], 2) ?></td>
                            <td><span class="badge-status <?= $row['availability_status'] ?>"><?= ucfirst($row['availability_status']) ?></span></td>
                            <td>
                                <a href="edit_accommodation.php?id=<?= $row['accommodation_id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="accommodations.php?delete=<?= $row['accommodation_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this accommodation?')">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <h3>No accommodations found</h3>
                                    <p>Add your first accommodation to get started.</p>
                                    <a href="add_accommodation.php" class="btn btn-primary btn-sm">+ Add Accommodation</a>
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
