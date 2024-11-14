<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: http://localhost/myproject/frontend/public/index.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "admin_dashboard";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle adding a thesis
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_thesis'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $year = $_POST['year'];
    $department = $_POST['department'];
    
    $targetDir = "C:/xampp/htdocs/myproject/frontend/public/thesis/" . strtolower(str_replace(' ', '_', $department)) . "/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $fileName = basename($_FILES['file']['name']);
        $targetFilePath = "thesis/" . strtolower(str_replace(' ', '_', $department)) . "/" . $fileName;
        if (move_uploaded_file($_FILES['file']['tmp_name'], $targetDir . $fileName)) {
            $sql = "INSERT INTO theses (title, author, year, department, file_path) VALUES ('$title', '$author', '$year', '$department', '$targetFilePath')";
            if ($conn->query($sql)) {
                echo "Thesis added successfully!";
            } else {
                echo "Error: " . $conn->error;
            }
        } else {
            echo "File upload failed.";
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_thesis'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $year = $_POST['year'];
    $department = $_POST['department'];
    
    $sql = "UPDATE theses SET title='$title', author='$author', year='$year', department='$department' WHERE id=$id";
    $conn->query($sql);
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM theses WHERE id=$id");
}

$result = $conn->query("SELECT * FROM theses");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Theses</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
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
            <span>Manage Theses</span>
        </div>

        <!-- Dashboard Content -->
        <div class="container mt-4">
            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addThesisModal">Add New Thesis</button>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Theses</h5>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Year</th>
                                <th>Department</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['title']; ?></td>
                                <td><?php echo $row['author']; ?></td>
                                <td><?php echo $row['year']; ?></td>
                                <td><?php echo $row['department']; ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm edit-btn" data-bs-toggle="modal" data-bs-target="#editThesisModal" 
                                            data-id="<?php echo $row['id']; ?>" data-title="<?php echo $row['title']; ?>" 
                                            data-author="<?php echo $row['author']; ?>" data-year="<?php echo $row['year']; ?>" 
                                            data-department="<?php echo $row['department']; ?>">Edit</button>
                                    <a href="delete_thesis.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modals for Adding and Editing Thesis -->
        <div class="modal fade" id="addThesisModal" tabindex="-1" aria-labelledby="addThesisModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addThesisModalLabel">Add New Thesis</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                            <div class="mb-3">
                                <label for="author" class="form-label">Author</label>
                                <input type="text" class="form-control" id="author" name="author" required>
                            </div>
                            <div class="mb-3">
                                <label for="year" class="form-label">Year</label>
                                <input type="number" class="form-control" id="year" name="year" required>
                            </div>
                            <div class="mb-3">
                                <label for="department" class="form-label">Department</label>
                                <select class="form-control" id="department" name="department" required>
                                    <option value="Bengkel Jaminan Kualiti">Bengkel Jaminan Kualiti</option>
                                    <option value="Bengkel Komputer">Bengkel Komputer</option>
                                    <option value="Bengkel Mikroelektronik">Bengkel Mikroelektronik</option>
                                    <option value="Bengkel Mekatronik">Bengkel Mekatronik</option>
                                    <option value="Bengkel Mekanikal Bahan">Bengkel Mekanikal Bahan</option>
                                    <option value="Bengkel Polimer">Bengkel Polimer</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="file" class="form-label">Upload Thesis File</label>
                                <input type="file" class="form-control" id="file" name="file" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="add_thesis" class="btn btn-primary">Add Thesis</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editThesisModal" tabindex="-1" aria-labelledby="editThesisModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editThesisModalLabel">Edit Thesis</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="edit-id" name="id">
                            <div class="mb-3">
                                <label for="edit-title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="edit-title" name="title" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit-author" class="form-label">Author</label>
                                <input type="text" class="form-control" id="edit-author" name="author" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit-year" class="form-label">Year</label>
                                <input type="number" class="form-control" id="edit-year" name="year" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit-department" class="form-label">Department</label>
                                <select class="form-control" id="edit-department" name="department" required>
                                    <option value="Bengkel Jaminan Kualiti">Bengkel Jaminan Kualiti</option>
                                    <option value="Bengkel Komputer">Bengkel Komputer</option>
                                    <option value="Bengkel Mikroelektronik">Bengkel Mikroelektronik</option>
                                    <option value="Bengkel Mekatronik">Bengkel Mekatronik</option>
                                    <option value="Bengkel Mekanikal Bahan">Bengkel Mekanikal Bahan</option>
                                    <option value="Bengkel Polimer">Bengkel Polimer</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="update_thesis" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script>
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.getElementById('edit-id').value = btn.getAttribute('data-id');
                document.getElementById('edit-title').value = btn.getAttribute('data-title');
                document.getElementById('edit-author').value = btn.getAttribute('data-author');
                document.getElementById('edit-year').value = btn.getAttribute('data-year');
                document.getElementById('edit-department').value = btn.getAttribute('data-department');
            });
        });
    </script>
</body>
</html>
