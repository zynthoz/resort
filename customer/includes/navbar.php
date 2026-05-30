<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar navbar-expand-lg resort-navbar">
    <div class="container">
        <a class="navbar-brand" href="../index.php">VISTA<span>TROPICAL</span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#resortCustomerNav">
            <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
        </button>
        <div class="collapse navbar-collapse" id="resortCustomerNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0" style="margin-left: 20px;">
                <li class="nav-item">
                    <a class="nav-link <?= $current_page == 'rooms.php' || $current_page == 'reserve.php' ? 'active' : '' ?>" href="rooms.php">Accommodations</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $current_page == 'packages.php' ? 'active' : '' ?>" href="packages.php">Special Packages</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $current_page == 'my_reservations.php' ? 'active' : '' ?>" href="my_reservations.php">My Bookings</a>
                </li>
            </ul>
            <div class="navbar-nav align-items-center">
                <div class="navbar-user-dropdown">
                    <div class="navbar-user-trigger">
                        <span class="navbar-user-avatar"><?= strtoupper(substr($_SESSION['full_name'], 0, 1)) ?></span>
                        <span class="d-none d-md-inline" style="font-size: 0.8rem; letter-spacing:0.05em; font-weight:600;"><?= $_SESSION['full_name'] ?></span>
                    </div>
                </div>
                <li class="nav-item">
                    <a class="nav-link" href="../logout.php" style="font-size:0.8rem; margin-left:15px; color:var(--resort-sand-light) !important;">Sign Out</a>
                </li>
            </div>
        </div>
    </div>
</nav>
