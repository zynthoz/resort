<?php
$page_title = 'Reserve';
require_once 'includes/header.php';
require_once '../db.php';

$id = (int)($_GET['id'] ?? 0);
$room = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tbl_accommodations WHERE accommodation_id = $id AND availability_status = 'available'"));

if (!$room) {
    header("Location: rooms.php");
    exit();
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $check_in = $_POST['check_in_date'];
    $check_out = $_POST['check_out_date'];

    if (strtotime($check_out) <= strtotime($check_in)) {
        $error = 'Check-out date must be after check-in date.';
    } else {
        $nights = (strtotime($check_out) - strtotime($check_in)) / 86400;
        $total = $nights * $room['price_per_night'];

        $sql = "INSERT INTO tbl_reservations (user_id, accommodation_id, check_in_date, check_out_date, total_price, reservation_status) 
                VALUES ({$_SESSION['user_id']}, $id, '$check_in', '$check_out', $total, 'pending')";
        if (mysqli_query($conn, $sql)) {
            $success = 'Reservation submitted! Your booking is pending approval.';
        } else {
            $error = 'Something went wrong. Please try again.';
        }
    }
}

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
            <h1 class="text-serif m-0">Book Your Sanctuary</h1>
            <p class="text-muted m-0" style="font-size: 0.95rem;">Review details and select check-in/check-out dates</p>
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
            <div class="resort-card">
                <div class="resort-card-img-wrapper" style="background-image: url('<?= get_accommodation_image($room['accommodation_name'], $room['image_url']) ?>'); height:200px;"></div>
                <div class="resort-card-body">
                    <h3 class="resort-card-title"><?= $room['accommodation_name'] ?></h3>
                    <p class="resort-card-text" style="font-size: 0.9rem; margin-bottom: 20px;"><?= $room['description'] ?></p>
                    
                    <div style="background-color: var(--resort-cream); border-radius: var(--radius-sm); padding: 15px 20px; font-size: 0.9rem;">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Capacity</span>
                            <span class="fw-bold"><?= $room['capacity'] ?> guests</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Price per Night</span>
                            <span class="fw-bold text-teal">₱<?= number_format($room['price_per_night'], 2) ?></span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Status</span>
                            <span class="badge bg-success text-white">Available</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="portal-form-card">
                <h3 class="text-serif mb-4" style="border-bottom: 1px solid rgba(26,92,78,0.1); padding-bottom: 15px;">Reservation Dates</h3>
                <form method="POST" action="">
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="check_in_date" class="form-label">Check-in Date</label>
                            <input type="date" class="form-control" id="check_in_date" name="check_in_date" required min="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="check_out_date" class="form-label">Check-out Date</label>
                            <input type="date" class="form-control" id="check_out_date" name="check_out_date" required min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
                        </div>
                    </div>

                    <div class="booking-summary-box mb-4">
                        <h3 class="text-serif">Booking Summary</h3>
                        <div class="booking-summary-row">
                            <span>Room rate / night</span>
                            <span>₱<?= number_format($room['price_per_night'], 2) ?></span>
                        </div>
                        <div class="booking-summary-row">
                            <span>Number of nights</span>
                            <span id="nights-display">0 nights</span>
                        </div>
                        <div class="booking-summary-row total">
                            <span>Estimated Total</span>
                            <span class="price-amount" id="total-price">₱0.00</span>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-resort btn-resort-teal px-4 py-2" style="font-size:0.85rem; border-radius:30px;">Submit Booking Request</button>
                        <a href="rooms.php" class="btn btn-outline-secondary py-2 px-4" style="border-radius: 30px; font-weight: 700; font-size: 0.85rem;">Back to Rooms</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
var pricePerNight = <?= $room['price_per_night'] ?>;

function calcTotal() {
    var checkInVal = document.getElementById('check_in_date').value;
    var checkOutVal = document.getElementById('check_out_date').value;
    if (checkInVal && checkOutVal) {
        var checkIn = new Date(checkInVal);
        var checkOut = new Date(checkOutVal);
        if (checkOut > checkIn) {
            var nights = (checkOut - checkIn) / (1000 * 60 * 60 * 24);
            var total = nights * pricePerNight;
            document.getElementById('nights-display').textContent = nights + (nights === 1 ? ' night' : ' nights');
            document.getElementById('total-price').textContent = '₱' + total.toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        } else {
            document.getElementById('nights-display').textContent = '0 nights';
            document.getElementById('total-price').textContent = '₱0.00';
        }
    } else {
        document.getElementById('nights-display').textContent = '0 nights';
        document.getElementById('total-price').textContent = '₱0.00';
    }
}

document.getElementById('check_in_date').addEventListener('change', calcTotal);
document.getElementById('check_out_date').addEventListener('change', calcTotal);
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
