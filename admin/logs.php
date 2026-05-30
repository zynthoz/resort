<?php
$page_title = 'Logs';
require_once 'includes/header.php';
require_once '../db.php';

$search = $_GET['search'] ?? '';

$sql = "SELECT l.*, u.full_name 
        FROM tbl_logs l 
        JOIN tbl_users u ON l.user_id = u.user_id 
        WHERE 1=1";
if ($search) {
    $sql .= " AND (u.full_name LIKE '%$search%' OR l.action LIKE '%$search%')";
}
$sql .= " ORDER BY l.datetime DESC";
$result = mysqli_query($conn, $sql);
?>

<?php include 'includes/sidebar.php'; ?>

<div class="main-content">
    <div class="page-header">
        <h1>Activity Logs</h1>
        <p>Track all system actions and changes</p>
    </div>

    <div class="table-card">
        <div class="table-header">
            <h2>All Logs</h2>
            <div class="table-actions">
                <form method="GET" class="search-form">
                    <input type="text" name="search" class="form-control" placeholder="Search by name or action..." value="<?= $search ?>">
                    <button type="submit" class="btn btn-outline-secondary btn-sm">Search</button>
                </form>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Action</th>
                        <th>Date & Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= $row['full_name'] ?></td>
                            <td><?= $row['action'] ?></td>
                            <td><?= date('M d, Y — h:i A', strtotime($row['datetime'])) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3">
                                <div class="empty-state">
                                    <h3>No logs yet</h3>
                                    <p>Activity logs will appear here as users perform actions.</p>
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
