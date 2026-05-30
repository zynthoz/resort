<?php
$page_title = 'My Reservations';
require_once 'includes/header.php';
require_once '../db.php';

$user_id = $_SESSION['user_id'];

if (isset($_GET['cancel'])) {
    $id = (int)$_GET['cancel'];
    
    $check = mysqli_fetch_assoc(mysqli_query($conn, "SELECT reservation_status FROM tbl_reservations WHERE reservation_id = $id AND user_id = $user_id"));
    if ($check && $check['reservation_status'] == 'pending') {
        mysqli_query($conn, "UPDATE tbl_reservations SET reservation_status = 'cancelled' WHERE reservation_id = $id");
        header("Location: my_reservations.php?msg=cancelled");
        exit();
    }
}

$sql = "SELECT r.*, a.accommodation_name, p.package_name 
        FROM tbl_reservations r 
        JOIN tbl_accommodations a ON r.accommodation_id = a.accommodation_id 
        LEFT JOIN tbl_packages p ON r.package_id = p.package_id
        WHERE r.user_id = $user_id 
        ORDER BY r.check_in_date DESC";
$result = mysqli_query($conn, $sql);
?>

<?php include 'includes/navbar.php'; ?>

<div class="portal-container">
    <div class="portal-section-title">
        <div>
            <h1 class="text-serif m-0">My Reservations</h1>
            <p class="text-muted m-0" style="font-size: 0.95rem;">Track and manage your scheduled tropical stays</p>
        </div>
        <div>
            <a href="rooms.php" class="btn btn-resort btn-resort-teal px-4 py-2" style="font-size: 0.8rem; border-radius: 30px;">Book Another Stay</a>
        </div>
    </div>

    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'cancelled'): ?>
        <div class="alert alert-success alert-dismissible fade show p-3 mb-4" role="alert">
            Reservation cancelled successfully.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <div class="reservation-list">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="reservation-card">
                    <div class="reservation-main">
                        <h3 class="reservation-room-name text-serif text-teal m-0">
                            <?= $row['accommodation_name'] ?>
                            <?php if ($row['package_name']): ?>
                                <span style="font-size: 0.85rem; font-family: var(--font-body); font-weight: normal; color: var(--resort-gold); margin-left: 8px;">
                                    (<?= $row['package_name'] ?> Package)
                                </span>
                            <?php endif; ?>
                        </h3>
                        <div class="reservation-dates mt-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16" style="margin-right: 4px; color: var(--resort-sand);"><path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/></svg>
                            <span><?= date('M d, Y', strtotime($row['check_in_date'])) ?></span>
                            <span class="mx-2">&rarr;</span>
                            <span><?= date('M d, Y', strtotime($row['check_out_date'])) ?></span>
                            <span class="ms-3 text-muted" style="font-size:0.8rem;">(<?= (strtotime($row['check_out_date']) - strtotime($row['check_in_date'])) / 86400 ?> nights)</span>
                        </div>
                    </div>
                    
                    <div class="reservation-price">
                        <span style="font-size: 0.8rem; font-family: var(--font-body); font-weight: normal; color: var(--resort-muted); display: block; margin-bottom: 2px;">Total Price</span>
                        ₱<?= number_format($row['total_price'], 2) ?>
                    </div>
                    
                    <div>
                        <span class="reservation-status-badge status-<?= $row['reservation_status'] ?> d-inline-block">
                            <?= $row['reservation_status'] ?>
                        </span>
                    </div>
                    
                    <div class="reservation-action">
                        <?php if ($row['reservation_status'] == 'pending'): ?>
                            <a href="my_reservations.php?cancel=<?= $row['reservation_id'] ?>" class="btn btn-danger btn-sm px-3 py-2" style="border-radius: 20px; font-size: 0.8rem; font-weight: 700;" onclick="return confirm('Are you sure you want to cancel this reservation?')">Cancel Stay</a>
                        <?php else: ?>
                            <span class="text-muted" style="font-size: 0.9rem;">—</span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="portal-form-card text-center p-5">
            <div class="empty-state">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="var(--resort-muted)" class="bi bi-calendar-x" viewBox="0 0 16 16" style="opacity: 0.3; margin-bottom: 20px;">
                    <path d="M6.146 7.146a.5.5 0 0 1 .708 0L8 8.293l1.146-1.147a.5.5 0 1 1 .708.708L8.707 9l1.147 1.146a.5.5 0 0 1-.708.708L8 9.707l-1.146 1.147a.5.5 0 0 1-.708-.708L7.293 9 6.146 7.854a.5.5 0 0 1 0-.708z"/>
                    <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                </svg>
                <h3 class="text-serif">No reservations found</h3>
                <p class="text-muted">You haven't booked any accommodations yet. Browse our luxury rooms to plan your first stay!</p>
                <a href="rooms.php" class="btn btn-resort btn-resort-teal mt-3">Browse Accommodations</a>
            </div>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
