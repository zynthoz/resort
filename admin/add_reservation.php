<?php
$page_title = 'Add Reservation';
require_once 'includes/header.php';
require_once '../db.php';

$customers = mysqli_query($conn, "SELECT user_id, full_name FROM tbl_users WHERE role = 'customer' ORDER BY full_name");
$accommodations = mysqli_query($conn, "SELECT accommodation_id, accommodation_name, price_per_night FROM tbl_accommodations WHERE availability_status = 'available' ORDER BY accommodation_name");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = (int)$_POST['user_id'];
    $accommodation_id = (int)$_POST['accommodation_id'];
    $check_in = $_POST['check_in_date'];
    $check_out = $_POST['check_out_date'];

    
    $acc = mysqli_fetch_assoc(mysqli_query($conn, "SELECT price_per_night FROM tbl_accommodations WHERE accommodation_id = $accommodation_id"));
    $nights = (strtotime($check_out) - strtotime($check_in)) / 86400;
    $total = $nights * $acc['price_per_night'];

    $sql = "INSERT INTO tbl_reservations (user_id, accommodation_id, check_in_date, check_out_date, total_price, reservation_status) 
            VALUES ($user_id, $accommodation_id, '$check_in', '$check_out', $total, 'pending')";
    if (mysqli_query($conn, $sql)) {
        $action = "Added reservation for user ID $user_id, accommodation ID $accommodation_id";
        mysqli_query($conn, "INSERT INTO tbl_logs (user_id, action, datetime) VALUES ({$_SESSION['user_id']}, '$action', NOW())");
        header("Location: reservations.php?msg=added");
        exit();
    }
}
?>

<?php include 'includes/sidebar.php'; ?>

<div class="main-content">
    <div class="page-header">
        <h1>Add Reservation</h1>
        <p>Create a reservation on behalf of a customer</p>
    </div>

    <div class="form-card card">
        <div class="card-header"><h2>Reservation Details</h2></div>
        <div class="card-body">
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="user_id" class="form-label">Customer</label>
                    <select class="form-select" id="user_id" name="user_id" required>
                        <option value="">Select customer</option>
                        <?php while ($c = mysqli_fetch_assoc($customers)): ?>
                            <option value="<?= $c['user_id'] ?>"><?= $c['full_name'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="accommodation_id" class="form-label">Accommodation</label>
                    <select class="form-select" id="accommodation_id" name="accommodation_id" required>
                        <option value="">Select accommodation</option>
                        <?php while ($a = mysqli_fetch_assoc($accommodations)): ?>
                            <option value="<?= $a['accommodation_id'] ?>" data-price="<?= $a['price_per_night'] ?>">
                                <?= $a['accommodation_name'] ?> — ₱<?= number_format($a['price_per_night'], 2) ?>/night
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="check_in_date" class="form-label">Check-in Date</label>
                        <input type="date" class="form-control" id="check_in_date" name="check_in_date" required>
                    </div>
                    <div class="col-md-6">
                        <label for="check_out_date" class="form-label">Check-out Date</label>
                        <input type="date" class="form-control" id="check_out_date" name="check_out_date" required>
                    </div>
                </div>

                <div class="total-display mb-4">
                    <span class="total-label">Estimated Total</span>
                    <span class="total-amount" id="total-price">₱0.00</span>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Create Reservation</button>
                    <a href="reservations.php" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function calcTotal() {
    var sel = document.getElementById('accommodation_id');
    var opt = sel.options[sel.selectedIndex];
    var price = parseFloat(opt.getAttribute('data-price')) || 0;
    var checkIn = new Date(document.getElementById('check_in_date').value);
    var checkOut = new Date(document.getElementById('check_out_date').value);
    
    if (checkIn && checkOut && checkOut > checkIn) {
        var nights = (checkOut - checkIn) / (1000 * 60 * 60 * 24);
        var total = nights * price;
        document.getElementById('total-price').textContent = '₱' + total.toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    } else {
        document.getElementById('total-price').textContent = '₱0.00';
    }
}

document.getElementById('accommodation_id').addEventListener('change', calcTotal);
document.getElementById('check_in_date').addEventListener('change', calcTotal);
document.getElementById('check_out_date').addEventListener('change', calcTotal);
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
