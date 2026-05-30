<?php
$page_title = 'Add Accommodation';
require_once 'includes/header.php';
require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['accommodation_name'];
    $desc = $_POST['description'];
    $capacity = (int)$_POST['capacity'];
    $price = (float)$_POST['price_per_night'];
    $status = $_POST['availability_status'];

    $sql = "INSERT INTO tbl_accommodations (accommodation_name, description, capacity, price_per_night, availability_status) VALUES ('$name', '$desc', $capacity, $price, '$status')";
    if (mysqli_query($conn, $sql)) {
        $action = "Added accommodation: $name";
        mysqli_query($conn, "INSERT INTO tbl_logs (user_id, action, datetime) VALUES ({$_SESSION['user_id']}, '$action', NOW())");
        header("Location: accommodations.php?msg=added");
        exit();
    }
}
?>

<?php include 'includes/sidebar.php'; ?>

<div class="main-content">
    <div class="page-header"><h1>Add Accommodation</h1><p>Create a new room or accommodation</p></div>
    <div class="form-card card">
        <div class="card-header"><h2>Accommodation Details</h2></div>
        <div class="card-body">
            <form method="POST" action="">
                <div class="mb-3"><label for="accommodation_name" class="form-label">Accommodation Name</label><input type="text" class="form-control" id="accommodation_name" name="accommodation_name" required></div>
                <div class="mb-3"><label for="description" class="form-label">Description</label><textarea class="form-control" id="description" name="description" rows="3"></textarea></div>
                <div class="row mb-3">
                    <div class="col-md-6"><label for="capacity" class="form-label">Capacity (guests)</label><input type="number" class="form-control" id="capacity" name="capacity" min="1" required></div>
                    <div class="col-md-6"><label for="price_per_night" class="form-label">Price per Night (₱)</label><input type="number" class="form-control" id="price_per_night" name="price_per_night" step="0.01" min="0" required></div>
                </div>
                <div class="mb-4"><label for="availability_status" class="form-label">Availability</label><select class="form-select" id="availability_status" name="availability_status" required><option value="available">Available</option><option value="unavailable">Unavailable</option></select></div>
                <div class="d-flex gap-2"><button type="submit" class="btn btn-primary">Save Accommodation</button><a href="accommodations.php" class="btn btn-outline-secondary">Cancel</a></div>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
