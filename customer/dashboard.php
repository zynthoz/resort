<?php
$page_title = 'Dashboard';
require_once 'includes/header.php';
require_once '../db.php';

$user_id = $_SESSION['user_id'];
$upcoming = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM tbl_reservations WHERE user_id = $user_id AND reservation_status = 'approved' AND check_in_date >= CURDATE()"))['c'];
$pending = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM tbl_reservations WHERE user_id = $user_id AND reservation_status = 'pending'"))['c'];
$total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM tbl_reservations WHERE user_id = $user_id"))['c'];
$available_rooms = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM tbl_accommodations WHERE availability_status = 'available'"))['c'];
?>

<?php include 'includes/navbar.php'; ?>

<div class="portal-container">
    <div class="portal-banner">
        <h1>Welcome back, <?= $_SESSION['full_name'] ?></h1>
        <p>Breathe, relax, and manage your sanctuary details here.</p>
    </div>

    <div class="portal-stat-grid">
        <div class="portal-stat-card">
            <div class="portal-stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16"><path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM6.79 5.093A.5.5 0 0 0 6 5.5v5a.5.5 0 0 0 .79.407l3.5-2.5a.5.5 0 0 0 0-.814l-3.5-2.5z"/></svg>
            </div>
            <div class="portal-stat-details">
                <h4>Upcoming Stays</h4>
                <p class="portal-stat-number"><?= $upcoming ?></p>
            </div>
        </div>
        <div class="portal-stat-card">
            <div class="portal-stat-icon warning">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16"><path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.399l-.244.025-.015-.07L8.7 6.36h1.598l-1.37 6.228zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/></svg>
            </div>
            <div class="portal-stat-details">
                <h4>Pending Bookings</h4>
                <p class="portal-stat-number"><?= $pending ?></p>
            </div>
        </div>
        <div class="portal-stat-card">
            <div class="portal-stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16"><path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm-3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm-5 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z"/><path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/></svg>
            </div>
            <div class="portal-stat-details">
                <h4>Total Bookings</h4>
                <p class="portal-stat-number"><?= $total ?></p>
            </div>
        </div>
        <div class="portal-stat-card">
            <div class="portal-stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16"><path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5ZM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5 5 5Z"/></svg>
            </div>
            <div class="portal-stat-details">
                <h4>Rooms Available</h4>
                <p class="portal-stat-number"><?= $available_rooms ?></p>
            </div>
        </div>
    </div>

    
    <h3 class="portal-section-title">Quick Actions</h3>
    <div class="row g-4">
        <div class="col-md-4">
            <a href="rooms.php" style="text-decoration:none;">
                <div class="portal-stat-card" style="flex-direction: column; text-align:center; padding:32px; justify-content: center; gap: 12px; height: 100%;">
                    <div class="portal-stat-icon" style="margin: 0 auto;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5ZM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5 5 5Z"/>
                        </svg>
                    </div>
                    <h3 style="font-size:1.25rem; margin: 5px 0 0 0; font-family: var(--font-heading);">Browse Rooms</h3>
                    <p style="color:var(--resort-muted); font-size:0.9rem; margin:0;">Find your perfect accommodation</p>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="packages.php" style="text-decoration:none;">
                <div class="portal-stat-card" style="flex-direction: column; text-align:center; padding:32px; justify-content: center; gap: 12px; height: 100%;">
                    <div class="portal-stat-icon" style="margin: 0 auto;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5 8 5.961 14.154 3.5 8.186 1.113zM15 4.239l-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923l6.5 2.6z"/>
                        </svg>
                    </div>
                    <h3 style="font-size:1.25rem; margin: 5px 0 0 0; font-family: var(--font-heading);">View Packages</h3>
                    <p style="color:var(--resort-muted); font-size:0.9rem; margin:0;">Explore bundled deals and offers</p>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="my_reservations.php" style="text-decoration:none;">
                <div class="portal-stat-card" style="flex-direction: column; text-align:center; padding:32px; justify-content: center; gap: 12px; height: 100%;">
                    <div class="portal-stat-icon" style="margin: 0 auto;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm-3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm-5 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z"/>
                            <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                        </svg>
                    </div>
                    <h3 style="font-size:1.25rem; margin: 5px 0 0 0; font-family: var(--font-heading);">My Reservations</h3>
                    <p style="color:var(--resort-muted); font-size:0.9rem; margin:0;">Track your booking status</p>
                </div>
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
