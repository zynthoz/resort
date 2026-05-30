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
    $image_url = null;

    
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_name = $_FILES['image']['name'];
        $file_size = $_FILES['image']['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        $allowed_exts = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        
        if (in_array($file_ext, $allowed_exts)) {
            if ($file_size <= 5242880) { 
                $target_dir = "../images/";
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                
                $new_file_name = uniqid('room_', true) . '.' . $file_ext;
                $target_file = $target_dir . $new_file_name;
                
                if (move_uploaded_file($file_tmp, $target_file)) {
                    $image_url = 'images/' . $new_file_name;
                } else {
                    $error_msg = "Failed to upload image file.";
                }
            } else {
                $error_msg = "Image size exceeds 5MB limit.";
            }
        } else {
            $error_msg = "Invalid file type. Only JPG, JPEG, PNG, WEBP, and GIF are allowed.";
        }
    }

    if (!isset($error_msg)) {
        $name_escaped = mysqli_real_escape_string($conn, $name);
        $desc_escaped = mysqli_real_escape_string($conn, $desc);
        $image_sql_val = $image_url ? "'" . mysqli_real_escape_string($conn, $image_url) . "'" : "NULL";

        $sql = "INSERT INTO tbl_accommodations (accommodation_name, description, capacity, price_per_night, availability_status, image_url) 
                VALUES ('$name_escaped', '$desc_escaped', $capacity, $price, '$status', $image_sql_val)";
        if (mysqli_query($conn, $sql)) {
            $action = "Added accommodation: $name";
            mysqli_query($conn, "INSERT INTO tbl_logs (user_id, action, datetime) VALUES ({$_SESSION['user_id']}, '$action', NOW())");
            header("Location: accommodations.php?msg=added");
            exit();
        } else {
            $error_msg = "Database error: " . mysqli_error($conn);
        }
    }
}
?>

<?php include 'includes/sidebar.php'; ?>

<div class="main-content">
    <div class="page-header">
        <h1>Add Accommodation</h1>
        <p>Create a new room or accommodation</p>
    </div>

    <div class="form-card card">
        <div class="card-header">
            <h2>Accommodation Details</h2>
        </div>
        <div class="card-body">
            <?php if (isset($error_msg)): ?>
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <?= $error_msg ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <form method="POST" action="" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="accommodation_name" class="form-label">Accommodation Name</label>
                    <input type="text" class="form-control" id="accommodation_name" name="accommodation_name" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="capacity" class="form-label">Capacity (guests)</label>
                        <input type="number" class="form-control" id="capacity" name="capacity" min="1" required>
                    </div>
                    <div class="col-md-6">
                        <label for="price_per_night" class="form-label">Price per Night (₱)</label>
                        <input type="number" class="form-control" id="price_per_night" name="price_per_night" step="0.01" min="0" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="availability_status" class="form-label">Availability</label>
                    <select class="form-select" id="availability_status" name="availability_status" required>
                        <option value="available">Available</option>
                        <option value="unavailable">Unavailable</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="image" class="form-label">Accommodation Image</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    <div class="form-text">Choose a high-quality photo. Max size: 5MB. Allowed formats: JPG, JPEG, PNG, WEBP, GIF.</div>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Save Accommodation</button>
                    <a href="accommodations.php" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
