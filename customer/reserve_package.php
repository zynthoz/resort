<?php
$page_title = 'Reserve Package';
require_once 'includes/header.php';
require_once '../db.php';

$id = (int)($_GET['id'] ?? 0);

$sql = "SELECT p.*, a.accommodation_name, a.description as accommodation_desc, a.capacity, a.availability_status, a.image_url 
        FROM tbl_packages p 
        LEFT JOIN tbl_accommodations a ON p.accommodation_id = a.accommodation_id 
        WHERE p.package_id = $id";
$result = mysqli_query($conn, $sql);
$package = mysqli_fetch_assoc($result);

if (!$package) {
    header("Location: packages.php");
    exit();
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $check_in = $_POST['check_in_date'];
    $nights = (int)$package['nights'];
    
    
    $check_out = date('Y-m-d', strtotime($check_in . " + $nights days"));
    
    if (empty($check_in)) {
        $error = 'Please select a check-in date.';
    } else {
        $accommodation_id = $package['accommodation_id'] ? $package['accommodation_id'] : 'NULL';
        $total = $package['price'];

        $sql = "INSERT INTO tbl_reservations (user_id, accommodation_id, check_in_date, check_out_date, total_price, reservation_status, package_id) 
                VALUES ({$_SESSION['user_id']}, $accommodation_id, '$check_in', '$check_out', $total, 'pending', $id)";
        if (mysqli_query($conn, $sql)) {
            $success = 'Reservation submitted! Your package booking is pending approval.';
        } else {
            $error = 'Something went wrong. Please try again.';
        }
    }
}

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
            <h1 class="text-serif m-0">Book Special Package</h1>
            <p class="text-muted m-0" style="font-size: 0.95rem;">Review package contents and choose your stay dates</p>
        </div>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success p-3 mb-4"><?= $success ?> <a href="my_reservations.php" style="font-weight: 700;">View your reservations</a></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger p-3 mb-4"><?= $error ?></div>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="resort-card mb-4">
                <div class="resort-card-img-wrapper" style="background-image: url('<?= get_package_image($package['package_name']) ?>'); height:200px;"></div>
                <div class="resort-card-body">
                    <span class="badge bg-warning text-dark mb-2" style="font-size: 0.75rem; letter-spacing:0.05em; font-weight:700; text-transform:uppercase;">Special Package</span>
                    <h3 class="resort-card-title text-serif" style="font-size: 1.4rem; color: var(--resort-teal);"><?= $package['package_name'] ?></h3>
                    <p class="resort-card-text" style="font-size: 0.9rem; margin-bottom: 20px;"><?= $package['description'] ?></p>
                    
                    <div class="mb-3" style="background-color: var(--resort-cream); border-radius: var(--radius-sm); padding: 15px 20px; font-size: 0.9rem;">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Included Room</span>
                            <span class="fw-bold"><?= $package['accommodation_name'] ?? 'Not specified' ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Duration</span>
                            <span class="fw-bold"><?= $package['nights'] ?> Nights</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Package Price</span>
                            <span class="fw-bold text-teal">₱<?= number_format($package['price'], 2) ?></span>
                        </div>
                    </div>

                    <div style="font-size: 0.9rem;">
                        <div style="font-weight: 700; font-size: 0.8rem; text-transform: uppercase; color: var(--resort-muted); margin-bottom: 8px;">Package Inclusions:</div>
                        <ul style="padding-left: 20px; margin-bottom: 0;">
                            <?php 
                                $inclusions = explode(',', $package['inclusion_details']);
                                foreach($inclusions as $inc) {
                                    echo '<li style="margin-bottom: 4px;">' . $inc . '</li>';
                                }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-7">
            <div class="portal-form-card">
                <h3 class="text-serif mb-4" style="border-bottom: 1px solid rgba(26,92,78,0.1); padding-bottom: 15px;">Choose Check-in Date</h3>
                <form method="POST" action="">
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="check_in_date" class="form-label">Check-in Date</label>
                            <input type="date" class="form-control" id="check_in_date" name="check_in_date" required min="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="check_out_date" class="form-label">Check-out Date (Calculated)</label>
                            <input type="date" class="form-control" id="check_out_date" name="check_out_date" readonly style="background-color: #e9ecef; cursor: not-allowed;">
                            <small class="text-muted">Checkout is automatically set based on the package's <?= $package['nights'] ?>-night stay.</small>
                        </div>
                    </div>

                    <div class="booking-summary-box mb-4">
                        <h3 class="text-serif">Booking Summary</h3>
                        <div class="booking-summary-row">
                            <span>Package Name</span>
                            <span><?= $package['package_name'] ?></span>
                        </div>
                        <div class="booking-summary-row">
                            <span>Included Stay</span>
                            <span><?= $package['accommodation_name'] ?? 'Room' ?> (<?= $package['nights'] ?> Nights)</span>
                        </div>
                        <div class="booking-summary-row total">
                            <span>Total Package Price</span>
                            <span class="price-amount">₱<?= number_format($package['price'], 2) ?></span>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-resort btn-resort-teal px-4 py-2" style="font-size:0.85rem; border-radius:30px;">Request Package Booking</button>
                        <a href="packages.php" class="btn btn-outline-secondary py-2 px-4" style="border-radius: 30px; font-weight: 700; font-size: 0.85rem;">Back to Packages</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
var packageNights = <?= (int)$package['nights'] ?>;

function calcCheckout() {
    var checkInVal = document.getElementById('check_in_date').value;
    if (checkInVal) {
        var checkInDate = new Date(checkInVal);
        checkInDate.setDate(checkInDate.getDate() + packageNights);
        
        var year = checkInDate.getFullYear();
        var month = String(checkInDate.getMonth() + 1).padStart(2, '0');
        var day = String(checkInDate.getDate()).padStart(2, '0');
        
        document.getElementById('check_out_date').value = year + '-' + month + '-' + day;
    } else {
        document.getElementById('check_out_date').value = '';
    }
}

document.getElementById('check_in_date').addEventListener('change', calcCheckout);
// Run on load in case browser pre-fills value
calcCheckout();
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
