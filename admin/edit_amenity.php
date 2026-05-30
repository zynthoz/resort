<?php
$page_title = 'Edit Amenity';
require_once 'includes/header.php';
require_once '../db.php';

$id = (int)($_GET['id'] ?? 0);
$row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tbl_amenities WHERE amenity_id = $id"));
if (!$row) { header("Location: amenities.php"); exit(); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['amenity_name'];
    $desc = $_POST['description'];
    $price = (float)$_POST['price_per_use'];

    $sql = "UPDATE tbl_amenities SET amenity_name='$name', description='$desc', price_per_use=$price WHERE amenity_id = $id";
    if (mysqli_query($conn, $sql)) {
        $action = "Updated amenity: $name (ID: $id)";
        mysqli_query($conn, "INSERT INTO tbl_logs (user_id, action, datetime) VALUES ({$_SESSION['user_id']}, '$action', NOW())");
        header("Location: amenities.php?msg=updated");
        exit();
    }
}
?>

<?php include 'includes/sidebar.php'; ?>

<div class="main-content">
    <div class="page-header">
        <h1>Edit Amenity</h1>
        <p>Update amenity details</p>
    </div>

    <div class="form-card card">
        <div class="card-header"><h2>Amenity Details</h2></div>
        <div class="card-body">
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="amenity_name" class="form-label">Amenity Name</label>
                    <input type="text" class="form-control" id="amenity_name" name="amenity_name" required value="<?= $row['amenity_name'] ?>">
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"><?= $row['description'] ?></textarea>
                </div>
                <div class="mb-4">
                    <label for="price_per_use" class="form-label">Price per Use (₱)</label>
                    <input type="number" class="form-control" id="price_per_use" name="price_per_use" step="0.01" min="0" required value="<?= $row['price_per_use'] ?>">
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Update Amenity</button>
                    <a href="amenities.php" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
