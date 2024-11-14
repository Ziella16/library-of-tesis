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
$dbname = "userregisteration"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch total number of users
$sql = "SELECT COUNT(*) AS total_users FROM users"; // Adjust the table name if needed
$result = $conn->query($sql);

$total_users = 0;
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_users = $row['total_users'];
}

$activitySql = "SELECT u.username, a.activity_type, a.timestamp 
                FROM user_logs a 
                JOIN users u ON a.user_id = u.id 
                ORDER BY a.timestamp DESC
                LIMIT 10"; // Adjust limit as needed

$activityResult = $conn->query($activitySql);
$activityRecords = [];
if ($activityResult->num_rows > 0) {
    while($row = $activityResult->fetch_assoc()) {
        $activityRecords[] = $row;
    }
}
    $conn->close();

// Admin-specific content here
$admin_username = htmlspecialchars($_SESSION['admin_username']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Custom CSS -->
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
        .card {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="text-center mb-4">
            <h2>Admin Panel</h2>
        </div>
        <a href="admin_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="user.php"><i class="fas fa-users"></i> Users</a>
        <a href="manage_thesis.php"><i class="fas fa-book"></i> Theses</a>
        <a href="#settings"><i class="fas fa-cogs"></i> Settings</a>
        <a href="records.php"><i class="fas fa-file-alt"></i> Records</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="header">
            <span>Welcome, <?php echo $admin_username; ?></span>
        </div>

        <!-- Dashboard Content -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">
                    <div class="card text-white bg-primary">
                        <div class="card-body">
                            <h5 class="card-title">Total Users</h5>
                            <p class="card-text"><?php echo $total_users; ?></p>
                        </div>
                    </div>
                </div>
                <!-- Add more cards as needed -->
                <div class="col-md-3">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <h5 class="card-title">Active Users</h5>
                            <p class="card-text">856</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-danger">
                        <div class="card-body">
                            <h5 class="card-title">Issues Reported</h5>
                            <p class="card-text">23</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-info">
                        <div class="card-body">
                            <h5 class="card-title">Pending Tasks</h5>
                            <p class="card-text">17</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">User Growth</h5>
                            <canvas id="userGrowthChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6" id="activityLog">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">User Activity Log</h5>
            <table class="table table-striped">
                <thead>
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
</div>



    <!-- Bootstrap and Chart.js Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Chart.js example for User Growth
        var ctx = document.getElementById('userGrowthChart').getContext('2d');
        var userGrowthChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June'],
                datasets: [{
                    label: 'User Growth',
                    data: [12, 19, 3, 5, 2, 3],
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Chart.js example for Reports Overview
        var ctx2 = document.getElementById('reportsChart').getContext('2d');
        var reportsChart = new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: ['Spam', 'Bug', 'Feature Request', 'Complaint', 'Other'],
                datasets: [{
                    label: 'Reports',
                    data: [12, 19, 3, 17, 6],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
