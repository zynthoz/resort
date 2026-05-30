<?php
$page_title = 'Reservations';
require_once 'includes/header.php';
require_once '../db.php';

if (isset($_GET['approve'])) {
    $id = (int)$_GET['approve'];
    mysqli_query($conn, "UPDATE tbl_reservations SET reservation_status = 'approved' WHERE reservation_id = $id");
    $action = "Approved reservation ID $id";
    mysqli_query($conn, "INSERT INTO tbl_logs (user_id, action, datetime) VALUES ({$_SESSION['user_id']}, '$action', NOW())");
    header("Location: reservations.php?msg=approved");
    exit();
}
if (isset($_GET['reject'])) {
    $id = (int)$_GET['reject'];
    mysqli_query($conn, "UPDATE tbl_reservations SET reservation_status = 'rejected' WHERE reservation_id = $id");
    $action = "Rejected reservation ID $id";
    mysqli_query($conn, "INSERT INTO tbl_logs (user_id, action, datetime) VALUES ({$_SESSION['user_id']}, '$action', NOW())");
    header("Location: reservations.php?msg=rejected");
    exit();
}

$search = $_GET['search'] ?? '';
$status_filter = $_GET['status'] ?? '';

$sql = "SELECT r.*, u.full_name, a.accommodation_name, p.package_name 
        FROM tbl_reservations r 
        JOIN tbl_users u ON r.user_id = u.user_id 
        JOIN tbl_accommodations a ON r.accommodation_id = a.accommodation_id 
        LEFT JOIN tbl_packages p ON r.package_id = p.package_id
        WHERE 1=1";
if ($search) {
    $sql .= " AND u.full_name LIKE '%$search%'";
}
if ($status_filter) {
    $sql .= " AND r.reservation_status = '$status_filter'";
}
$sql .= " ORDER BY r.check_in_date DESC";
$result = mysqli_query($conn, $sql);
?>

<?php include 'includes/sidebar.php'; ?>

<div class="main-content">
    <div class="page-header">
        <h1>Reservations</h1>
        <p>Manage all guest reservations</p>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php
            if ($_GET['msg'] == 'approved') echo 'Reservation approved successfully.';
            elseif ($_GET['msg'] == 'rejected') echo 'Reservation rejected.';
            elseif ($_GET['msg'] == 'added') echo 'Reservation added successfully.';
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="table-card">
        <div class="table-header">
            <h2>All Reservations</h2>
            <div class="table-actions">
                <form method="GET" class="search-form">
                    <input type="text" name="search" class="form-control" placeholder="Search by customer..." value="<?= $search ?>">
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
                        <option value="pending" <?= $status_filter == 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="approved" <?= $status_filter == 'approved' ? 'selected' : '' ?>>Approved</option>
                        <option value="rejected" <?= $status_filter == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                        <option value="cancelled" <?= $status_filter == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                    </select>
                </form>
                <a href="add_reservation.php" class="btn btn-primary btn-sm">+ Add Reservation</a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Accommodation</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= $row['full_name'] ?></td>
                            <td>
                                <?= $row['accommodation_name'] ?>
                                <?php if (!empty($row['package_name'])): ?>
                                    <br><small class="text-warning" style="font-weight: 600;">(<?= $row['package_name'] ?> Package)</small>
                                <?php endif; ?>
                            </td>
                            <td><?= date('M d, Y', strtotime($row['check_in_date'])) ?></td>
                            <td><?= date('M d, Y', strtotime($row['check_out_date'])) ?></td>
                            <td class="price">₱<?= number_format($row['total_price'], 2) ?></td>
                            <td><span class="badge-status <?= $row['reservation_status'] ?>"><?= ucfirst($row['reservation_status']) ?></span></td>
                            <td>
                                <?php if ($row['reservation_status'] == 'pending'): ?>
                                    <a href="reservations.php?approve=<?= $row['reservation_id'] ?>" class="btn btn-success btn-sm" onclick="return confirm('Approve this reservation?')">Approve</a>
                                    <a href="reservations.php?reject=<?= $row['reservation_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Reject this reservation?')">Reject</a>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <h3>No reservations found</h3>
                                    <p>Reservations will appear here once guests start booking.</p>
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
