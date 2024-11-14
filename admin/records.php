<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: http://localhost/myproject/frontend/public/index.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "userregisteration";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user activity logs
$activitySql = "SELECT u.username, a.activity_type, a.timestamp 
                FROM user_logs a 
                JOIN users u ON a.user_id = u.id 
                ORDER BY a.timestamp DESC
                LIMIT 10";

$activityResult = $conn->query($activitySql);
$activityRecords = [];
if ($activityResult->num_rows > 0) {
    while($row = $activityResult->fetch_assoc()) {
        $activityRecords[] = $row;
    }
}

$conn->close();
$admin_username = htmlspecialchars($_SESSION['admin_username']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Records - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }
        .sidebar {
            width: 250px;
            background-color: #343a40;
            color: #fff;
            padding-top: 20px;
            position: fixed;
            height: 100%;
        }
        .sidebar a {
            color: #fff;
            padding: 15px;
            text-decoration: none;
            display: block;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
            width: 100%;
            overflow-y: auto;
        }
        .header {
            background-color: #343a40;
            color: #fff;
            padding: 15px;
            text-align: right;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2 class="text-center mb-4">Admin Panel</h2>
        <a href="admin_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="user.php"><i class="fas fa-users"></i> Users</a>
        <a href="manage_thesis.php"><i class="fas fa-book"></i> Theses</a>
        <a href="settings.php"><i class="fas fa-cogs"></i> Settings</a>
        <a href="records.php"><i class="fas fa-file-alt"></i> Records</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="header">
            <span>Welcome, <?php echo $admin_username; ?></span>
        </div>

        <!-- User Activity Log -->
        <div class="container-fluid">
            <h3>User Activity Log</h3>
            <table class="table table-striped mt-3">
                <thead class="table-dark">
                    <tr>
                        <th>User</th>
                        <th>Activity</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($activityRecords)): ?>
                        <?php foreach ($activityRecords as $record): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($record['username']); ?></td>
                                <td><?php echo htmlspecialchars($record['activity_type']); ?></td>
                                <td><?php echo htmlspecialchars($record['timestamp']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3">No recent activity found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
