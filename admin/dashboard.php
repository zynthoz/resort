<?php
$page_title = 'Dashboard';
require_once 'includes/header.php';
require_once '../db.php';

$users_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM tbl_users"))['c'];
$reservations_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM tbl_reservations"))['c'];
$accommodations_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM tbl_accommodations"))['c'];
$pending_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM tbl_reservations WHERE reservation_status = 'pending'"))['c'];
?>

<?php include 'includes/sidebar.php'; ?>

<div class="main-content">
    <div class="page-header">
        <h1>Dashboard</h1>
        <p>Overview of your resort operations</p>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0zM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-.779.357-1.85 1.006-2.705.38-.498.838-.933 1.368-1.286A5.69 5.69 0 0 1 6.936 9.28zM4.92 10A2 2 0 1 1 4.92 10zM5 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/>
                    </svg>
                </div>
                <div class="stat-value"><?= $users_count ?></div>
                <div class="stat-label">Total Users</div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon info">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm-3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm-5 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z"/>
                        <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                    </svg>
                </div>
                <div class="stat-value"><?= $reservations_count ?></div>
                <div class="stat-label">Total Reservations</div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon accent">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5ZM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5 5 5Z"/>
                    </svg>
                </div>
                <div class="stat-value"><?= $accommodations_count ?></div>
                <div class="stat-label">Accommodations</div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon warning">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.399l-.244.025-.015-.07L8.7 6.36h1.598l-1.37 6.228zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
                    </svg>
                </div>
                <div class="stat-value"><?= $pending_count ?></div>
                <div class="stat-label">Pending Reservations</div>
            </div>
        </div>
    </div>

    
    <div class="table-card">
        <div class="table-header">
            <h2>Recent Reservations</h2>
            <a href="reservations.php" class="btn btn-outline-secondary btn-sm">View All</a>
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
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT r.*, u.full_name, a.accommodation_name, p.package_name 
                            FROM tbl_reservations r 
                            JOIN tbl_users u ON r.user_id = u.user_id 
                            JOIN tbl_accommodations a ON r.accommodation_id = a.accommodation_id 
                            LEFT JOIN tbl_packages p ON r.package_id = p.package_id
                            ORDER BY r.check_in_date DESC LIMIT 5";
                    $result = mysqli_query($conn, $sql);
                    
                    if (mysqli_num_rows($result) > 0):
                        while ($row = mysqli_fetch_assoc($result)):
                    ?>
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
                    </tr>
                    <?php 
                        endwhile;
                    else: 
                    ?>
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <h3>No reservations yet</h3>
                                <p>Reservations will appear here once customers start booking.</p>
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
