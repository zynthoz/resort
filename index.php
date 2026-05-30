<?php
session_start();
require_once 'db.php';

$is_logged_in = isset($_SESSION['user_id']);
$dashboard_link = '';
if ($is_logged_in) {
    if ($_SESSION['role'] == 'admin') {
        $dashboard_link = 'admin/dashboard.php';
    } else if ($_SESSION['role'] == 'employee') {
        $dashboard_link = 'employee/dashboard.php';
    } else {
        $dashboard_link = 'customer/my_reservations.php';
    }
}

$rooms_query = "SELECT * FROM tbl_accommodations WHERE availability_status = 'available' ORDER BY accommodation_id DESC";
$rooms_result = mysqli_query($conn, $rooms_query);

$packages_query = "SELECT * FROM tbl_packages LIMIT 4";
$packages_result = mysqli_query($conn, $packages_query);

$amenities_query = "SELECT * FROM tbl_amenities LIMIT 6";
$amenities_result = mysqli_query($conn, $amenities_query);

function get_accommodation_image($name, $image_url = null) {
    if (!empty($image_url)) {
        return $image_url;
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

function get_amenity_svg($name) {
    $name = strtolower($name);
    if (strpos($name, 'pool') !== false) {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" viewBox="0 0 16 16"><path d="M14 8.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0 0 1h1a.5.5 0 0 0 .5-.5zm-2-3a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0 0 1h1a.5.5 0 0 0 .5-.5zm-2-3a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0 0 1h1a.5.5 0 0 0 .5-.5zM2.5 12a.5.5 0 0 0 0 1h11a.5.5 0 0 0 0-1h-11z"/></svg>';
    } else if (strpos($name, 'spa') !== false || strpos($name, 'massage') !== false) {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" viewBox="0 0 16 16"><path d="M8 10a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/><path d="M13.979 6.27a.5.5 0 0 0-.895-.447 6.442 6.442 0 0 1-5.637 3.487H7.448a6.442 6.442 0 0 1-5.637-3.487.5.5 0 1 0-.895.448 7.442 7.442 0 0 0 6.53 4.039h.108a7.442 7.442 0 0 0 6.425-4.04z"/></svg>';
    } else if (strpos($name, 'kayak') !== false || strpos($name, 'boat') !== false) {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" viewBox="0 0 16 16"><path d="M8 0c-.276 0-.5.224-.5.5v1.238L3.25 4.54a.5.5 0 0 0-.15.353V15.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5V4.894a.5.5 0 0 0-.15-.353L8.5 1.738V.5c0-.276-.224-.5-.5-.5z"/></svg>';
    } else if (strpos($name, 'island') !== false || strpos($name, 'tour') !== false) {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="M10.854 5.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 7.793l2.646-2.647a.5.5 0 0 1 .708 0z"/></svg>';
    } else if (strpos($name, 'bike') !== false || strpos($name, 'bicycle') !== false) {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" viewBox="0 0 16 16"><path d="M4 4.5a.5.5 0 0 1 .5-.5H6a.5.5 0 0 1 0 1H4.5a.5.5 0 0 1-.5-.5zM12 11.5a.5.5 0 0 1-.5.5H10a.5.5 0 0 1 0-1h1.5a.5.5 0 0 1 .5.5z"/></svg>';
    } else {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" viewBox="0 0 16 16"><path d="M2.678 11.894a1 1 0 0 1 .287.801 10.97 10.97 0 0 1-.398 2c-.139.444.24.82 0 .82H1.28a1 1 0 0 1-.22-.024c.27-.41.43-.88.47-1.39a1 1 0 0 1 .707-.905c1-.37 1.8-.75 2.5-1.28v-.022z"/></svg>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vista Tropical Resort — Unwind in Paradise</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/customer.css">
</head>
<body>

    
    <nav class="navbar navbar-expand-lg resort-navbar">
        <div class="container">
            <a class="navbar-brand" href="index.php">VISTA<span>TROPICAL</span></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#resortNav">
                <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
            </button>
            <div class="collapse navbar-collapse" id="resortNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="#about">Our Story</a></li>
                    <li class="nav-item"><a class="nav-link" href="#rooms">Rooms</a></li>
                    <li class="nav-item"><a class="nav-link" href="#amenities">Amenities</a></li>
                    <li class="nav-item"><a class="nav-link" href="#packages">Packages</a></li>
                    <?php if ($is_logged_in): ?>
                        <li class="nav-item">
                            <a class="nav-link nav-btn" href="<?= $dashboard_link ?>">Guest Portal</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php" style="font-size:0.8rem; margin-left:15px; color:var(--resort-sand-light) !important;">Sign Out</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="login.php">Sign In</a></li>
                        <li class="nav-item"><a class="nav-link nav-btn" href="register.php">Book Now</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    
    <header class="hero-section" style="background-image: url('https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=1920&q=80');">
        <div class="hero-overlay"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 hero-content">
                    <span class="hero-subtitle">Breathe. Relax. Reconnect.</span>
                    <h1 class="hero-title">Your Tropical Sanctuary Awaits</h1>
                    <p class="hero-description">Experience pristine sands, crystal clear waters, and world-class hospitality at Vista Tropical Resort.</p>
                    <div class="d-flex gap-3 flex-wrap">
                        <?php if ($is_logged_in): ?>
                            <a href="customer/rooms.php" class="btn-resort btn-resort-primary">Explore Accommodations</a>
                            <a href="<?= $dashboard_link ?>" class="btn-resort btn-resort-outline">My Bookings</a>
                        <?php else: ?>
                            <a href="register.php" class="btn-resort btn-resort-primary">Reserve Your Stay</a>
                            <a href="login.php" class="btn-resort btn-resort-outline">Sign In to Account</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </header>

    
    <section class="section-padding bg-white" id="about">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <span class="text-gold text-serif" style="font-size: 1.25rem; font-style: italic;">Welcome to Vista Tropical</span>
                    <h2 class="mt-2 mb-4 text-serif" style="font-size: 2.5rem; line-height:1.2;">A Paradise Created Just for You</h2>
                    <p class="mb-4">Nestled on a secluded coastline of soft white sand and swaying palm trees, Vista Tropical offers an intimate escape from the everyday noise. Our eco-luxury accommodations and bespoke hospitality ensure a stay that is both rejuvenating and memorable.</p>
                    <p class="mb-4">Whether you are looking to dive into vibrant reef ecosystems, unwind with therapeutic spa massages, or dine under the stars with fresh, local seafood, we tailor every experience to your rhythm.</p>
                    <div class="accent-line" style="margin: 30px 0 0 0;"></div>
                </div>
                <div class="col-lg-6">
                    <div class="row g-3">
                        <div class="col-6">
                            <img src="https://images.unsplash.com/photo-1571003123894-1f0594d2b5d9?auto=format&fit=crop&w=600&q=80" alt="Resort Pool" class="img-fluid rounded-3" style="box-shadow: var(--shadow-sm); transform: translateY(-20px);">
                        </div>
                        <div class="col-6">
                            <img src="https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?auto=format&fit=crop&w=600&q=80" alt="Resort Beach" class="img-fluid rounded-3" style="box-shadow: var(--shadow-md);">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    
    <section class="section-padding bg-cream" id="rooms">
        <div class="container">
            <div class="section-header">
                <h2>Accommodations</h2>
                <p>Designed for comfort, inspired by nature. Choose from our luxury suites, cabanas, and family villas.</p>
                <div class="accent-line"></div>
            </div>
            
            <div class="row g-4">
                <?php if (mysqli_num_rows($rooms_result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($rooms_result)): ?>
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
                                        <?php if ($is_logged_in): ?>
                                            <a href="customer/reserve.php?id=<?= $row['accommodation_id'] ?>" class="btn btn-resort btn-resort-teal px-4 py-2" style="padding: 8px 20px !important;">Book</a>
                                        <?php else: ?>
                                            <a href="register.php" class="btn btn-resort btn-resort-teal px-4 py-2" style="padding: 8px 20px !important;">Book</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12 text-center">
                        <p class="text-muted">No accommodations available at the moment. Please check back later.</p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="text-center mt-5">
                <?php if ($is_logged_in): ?>
                    <a href="customer/rooms.php" class="btn-resort btn-resort-teal">View All Accommodations</a>
                <?php else: ?>
                    <a href="login.php" class="btn-resort btn-resort-teal">View All Accommodations</a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    
    <section class="section-padding bg-white" id="amenities">
        <div class="container">
            <div class="section-header">
                <h2>Resort Experiences</h2>
                <p>Add some adventure or relaxation to your stay with our curated amenities and activities.</p>
                <div class="accent-line"></div>
            </div>

            <div class="row g-4">
                <?php if (mysqli_num_rows($amenities_result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($amenities_result)): ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="amenity-card">
                                <div class="amenity-icon">
                                    <?= get_amenity_svg($row['amenity_name']) ?>
                                </div>
                                <h3 class="amenity-title"><?= $row['amenity_name'] ?></h3>
                                <p class="amenity-desc"><?= $row['description'] ?></p>
                                <span class="badge bg-light text-dark mt-3 px-3 py-2 font-weight-bold" style="border: 1px solid rgba(26, 92, 78, 0.1);">
                                    <?= $row['price_per_use'] > 0 ? '₱' . number_format($row['price_per_use'], 2) : 'Complimentary' ?>
                                </span>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12 text-center">
                        <p class="text-muted">No amenities available at the moment.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    
    <section class="section-padding bg-cream" id="packages">
        <div class="container">
            <div class="section-header">
                <h2>Exclusive Packages</h2>
                <p>Bundled offers designed to give you the ultimate vacation value and convenience.</p>
                <div class="accent-line"></div>
            </div>

            <div class="row g-4">
                <?php if (mysqli_num_rows($packages_result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($packages_result)): ?>
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
                                        <?php if ($is_logged_in): ?>
                                            <a href="customer/packages.php" class="btn btn-resort btn-resort-teal px-4 py-2" style="padding: 8px 20px !important;">Details</a>
                                        <?php else: ?>
                                            <a href="register.php" class="btn btn-resort btn-resort-teal px-4 py-2" style="padding: 8px 20px !important;">View Deals</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12 text-center">
                        <p class="text-muted">No packages available at the moment. Please check back later.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <footer class="resort-footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-5 mb-lg-0">
                    <div class="footer-brand">VISTA<span>TROPICAL</span></div>
                    <p class="footer-desc">Experience eco-luxury beach resort living designed to reconnect you with nature and relaxation.</p>
                    <p class="small text-muted">&copy; <?= date('Y') ?> Vista Tropical Resort. All rights reserved.</p>
                </div>
                <div class="col-md-6 col-lg-4 mb-4 mb-md-0">
                    <h3 class="footer-title">Quick Links</h3>
                    <ul class="footer-links">
                        <li><a href="#about">Our Story</a></li>
                        <li><a href="#rooms">Accommodations</a></li>
                        <li><a href="#amenities">Experiences & Activities</a></li>
                        <li><a href="#packages">Special Bundles</a></li>
                        <li><a href="login.php">Sign In</a></li>
                    </ul>
                </div>
                <div class="col-md-6 col-lg-4">
                    <h3 class="footer-title">Contact Us</h3>
                    <ul class="footer-contact-info">
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/></svg>
                            <span>Tropical Cove, Boracay Island, Philippines</span>
                        </li>
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M3.654 1.328a.678.678 0 0 0-.58-.83l-2.072-.257a.678.678 0 0 0-.58.83l.257 2.072a.678.678 0 0 0 .83.58l2.072-.257a.678.678 0 0 0 .58-.83l-.257-2.072zm3.36 10.97a.678.678 0 0 0-.58-.83l-2.072-.257a.678.678 0 0 0-.58.83l.257 2.072a.678.678 0 0 0 .83.58l2.072-.257a.678.678 0 0 0 .58-.83l-.257-2.072zM11 11.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5zm-4.5 1.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1H7a.5.5 0 0 1-.5-.5z"/></svg>
                            <span>+63 (2) 8888-8888</span>
                        </li>
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2zm13 2.383-4.708 2.825L15 11.105V5.383zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741zM1 11.105l4.708-2.897L1 5.383v5.722z"/></svg>
                            <span>bookings@vistatropical.com</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
