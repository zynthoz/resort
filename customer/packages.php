<?php
$page_title = 'Browse Packages';
require_once 'includes/header.php';
require_once '../db.php';

$search = $_GET['search'] ?? '';

$sql = "SELECT * FROM tbl_packages WHERE 1=1";
if ($search) {
    $search_escaped = mysqli_real_escape_string($conn, $search);
    $sql .= " AND (package_name LIKE '%$search_escaped%' OR description LIKE '%$search_escaped%')";
}
$sql .= " ORDER BY package_id DESC";
$result = mysqli_query($conn, $sql);

function get_package_image($name) {
    $name = strtolower($name);
    if (strpos($name, 'weekend') !== false) {
        return 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&q=80';
    } else if (strpos($name, 'family') !== false) {
        return 'https://images.unsplash.com/photo-1519046904884-53103b34b206?auto=format&fit=crop&w=800&q=80';
    } else if (strpos($name, 'romantic') !== false || strpos($name, 'retreat') !== false) {
        return 'https://images.unsplash.com/photo-1506929562872-bb421503ef21?auto=format&fit=crop&w=800&q=80';
    } else {
        return 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=800&q=80';
    }
}
?>

<?php include 'includes/navbar.php'; ?>

<div class="portal-container">
    <div class="portal-section-title">
        <div>
            <h1 class="text-serif m-0">Browse Exclusive Packages</h1>
            <p class="text-muted m-0" style="font-size: 0.95rem;">Curated resort bundles designed to give you the ultimate getaway value</p>
        </div>
    </div>

    
    <div class="portal-form-card mb-5 p-4">
        <form method="GET" class="row g-3 align-items-center">
            <div class="col-md-9">
                <input type="text" name="search" class="form-control" placeholder="Search by package name or description..." value="<?= $search ?>">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-resort btn-resort-teal w-100 py-2" style="font-size:0.85rem; border-radius:var(--radius-sm);">Search Packages</button>
            </div>
        </form>
    </div>

    <?php if (mysqli_num_rows($result) > 0): ?>
    <div class="row g-4">
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <div class="col-md-6">
            <div class="resort-card">
                <div class="resort-card-img-wrapper" style="background-image: url('<?= get_package_image($row['package_name']) ?>'); height:220px;"></div>
                <div class="resort-card-body">
                    <h3 class="resort-card-title"><?= $row['package_name'] ?></h3>
                    <p class="resort-card-text"><?= $row['description'] ?></p>
                    
                    <div class="inclusions-title" style="font-weight: 700; font-size: 0.8rem; text-transform: uppercase; color: var(--resort-muted); margin-bottom: 10px;">Inclusions</div>
                    <ul class="inclusions-list">
                        <?php 
                            $inclusions = explode(',', $row['inclusion_details']);
                            foreach($inclusions as $inc) {
                                echo '<li>' . $inc . '</li>';
                            }
                        ?>
                    </ul>

                    <div class="resort-card-footer">
                        <div class="resort-card-price">₱<?= number_format($row['price'], 2) ?> <span>/ package</span></div>
                        <a href="reserve_package.php?id=<?= $row['package_id'] ?>" class="btn btn-resort btn-resort-teal px-4 py-2" style="padding: 8px 20px !important;">Book Now</a>
                    </div>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
    <?php else: ?>
    <div class="portal-form-card text-center p-5">
        <div class="empty-state">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="var(--resort-muted)" class="bi bi-box-seam-fill" viewBox="0 0 16 16" style="opacity: 0.3; margin-bottom: 20px;">
                <path fill-rule="evenodd" d="M15.528 2.973a.75.75 0 0 1 .472.696v8.662a.75.75 0 0 1-.75.75h-14a.75.75 0 0 1-.75-.75V3.669a.75.75 0 0 1 .472-.696l7-2.824a.75.75 0 0 1 .556 0l7 2.824ZM1.5 4.318v7.932h13V4.318L8 2.222 1.5 4.318Z"/>
            </svg>
            <h3 class="text-serif">No packages found</h3>
            <p class="text-muted">No resort packages match your search query. Try searching for something else or view all deals.</p>
            <a href="packages.php" class="btn btn-resort btn-resort-teal mt-3">Show All Packages</a>
        </div>
    </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
