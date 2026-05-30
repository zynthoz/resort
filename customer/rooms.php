<?php
$page_title = 'Browse Rooms';
require_once 'includes/header.php';
require_once '../db.php';

$search = $_GET['search'] ?? '';

$sql = "SELECT * FROM tbl_accommodations WHERE availability_status = 'available'";
if ($search) {
    $search_escaped = mysqli_real_escape_string($conn, $search);
    $sql .= " AND (accommodation_name LIKE '%$search_escaped%' OR description LIKE '%$search_escaped%')";
}
$sql .= " ORDER BY accommodation_id DESC";
$result = mysqli_query($conn, $sql);

function get_accommodation_image($name, $image_url = null) {
    if (!empty($image_url)) {
        if (strpos($image_url, 'http') === 0) {
            return $image_url;
        }
        return '../' . $image_url;
    }
    $name = strtolower($name);
    if (strpos($name, 'ocean') !== false || strpos($name, 'suite') !== false) {
        return 'https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=800&q=80';
    } else if (strpos($name, 'villa') !== false || strpos($name, 'garden') !== false) {
        return 'https://images.unsplash.com/photo-1582268611958-ebfd161ef9cf?auto=format&fit=crop&w=800&q=80';
    } else if (strpos($name, 'cabana') !== false || strpos($name, 'beach') !== false) {
        return 'https://images.unsplash.com/photo-1439066615861-d1af74d74000?auto=format&fit=crop&w=800&q=80';
    } else if (strpos($name, 'cottage') !== false || strpos($name, 'honeymoon') !== false) {
        return 'https://images.unsplash.com/photo-1544644181-1484b3fdfc62?auto=format&fit=crop&w=800&q=80';
    } else if (strpos($name, 'bunk') !== false || strpos($name, 'backpacker') !== false) {
        return 'https://images.unsplash.com/photo-1555854877-bab0e564b8d5?auto=format&fit=crop&w=800&q=80';
    } else {
        return 'https://images.unsplash.com/photo-1566665797739-1674de7a421a?auto=format&fit=crop&w=800&q=80';
    }
}
?>

<?php include 'includes/navbar.php'; ?>

<div class="portal-container">
    <div class="portal-section-title">
        <div>
            <h1 class="text-serif m-0">Browse Accommodations</h1>
            <p class="text-muted m-0" style="font-size: 0.95rem;">Select your sanctuary for an unforgettable tropical experience</p>
        </div>
    </div>

    
    <div class="portal-form-card mb-5 p-4">
        <form method="GET" class="row g-3 align-items-center">
            <div class="col-md-9">
                <input type="text" name="search" class="form-control" placeholder="Search by room name or description..." value="<?= $search ?>">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-resort btn-resort-teal w-100 py-2" style="font-size:0.85rem; border-radius:var(--radius-sm);">Search Rooms</button>
            </div>
        </form>
    </div>

    <?php if (mysqli_num_rows($result) > 0): ?>
    <div class="row g-4">
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <div class="col-md-6 col-lg-4">
            <div class="resort-card">
                <div class="resort-card-img-wrapper" style="background-image: url('<?= get_accommodation_image($row['accommodation_name'], $row['image_url']) ?>');">
                    <span class="resort-card-tag"><?= $row['capacity'] ?> Guests</span>
                </div>
                <div class="resort-card-body">
                    <h3 class="resort-card-title"><?= $row['accommodation_name'] ?></h3>
                    <p class="resort-card-text"><?= $row['description'] ?></p>
                    <div class="resort-card-footer">
                        <div class="resort-card-price">₱<?= number_format($row['price_per_night'], 2) ?> <span>/ night</span></div>
                        <a href="reserve.php?id=<?= $row['accommodation_id'] ?>" class="btn btn-resort btn-resort-teal px-4 py-2" style="padding: 8px 20px !important;">Reserve</a>
                    </div>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
    <?php else: ?>
    <div class="portal-form-card text-center p-5">
        <div class="empty-state">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="var(--resort-muted)" class="bi bi-house-exclamation-fill" viewBox="0 0 16 16" style="opacity: 0.3; margin-bottom: 20px;">
                <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5Z"/>
            </svg>
            <h3 class="text-serif">No accommodations found</h3>
            <p class="text-muted">No rooms match your search query. Try typing something else or check out all available options.</p>
            <a href="rooms.php" class="btn btn-resort btn-resort-teal mt-3">Show All Rooms</a>
        </div>
    </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
