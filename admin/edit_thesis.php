<?php
include 'db.php';

// Check if thesis ID is passed via GET
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the thesis from the database
    $stmt = $pdo->prepare("SELECT * FROM theses WHERE id = ?");
    $stmt->execute([$id]);
    $thesis = $stmt->fetch();

    // If thesis not found, display an error message
    if (!$thesis) {
        echo "Thesis not found!";
        exit;
    }
}

// Process form submission (update thesis)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $year = $_POST['year'];
    $department = $_POST['department'];
    $file_path = $_POST['file_path'];  // Assuming you just update the file path

    // Update the thesis record in the database
    $stmt = $pdo->prepare("UPDATE theses SET title = ?, author = ?, year = ?, department = ?, file_path = ? WHERE id = ?");
    $stmt->execute([$title, $author, $year, $department, $file_path, $id]);

    // Redirect to the manage_thesis.php page after updating
    header("Location: manage_thesis.php?message=Thesis+updated+successfully");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Thesis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Thesis</h2>

        <!-- Display Success/Error messages -->
        <?php if (isset($_GET['message'])): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($_GET['message']); ?>
            </div>
        <?php endif; ?>

        <!-- Edit Thesis Form -->
        <form action="edit_thesis.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $thesis['id']; ?>">

            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars($thesis['title']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="author" class="form-label">Author</label>
                <input type="text" class="form-control" name="author" value="<?php echo htmlspecialchars($thesis['author']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="year" class="form-label">Year</label>
                <input type="number" class="form-control" name="year" value="<?php echo htmlspecialchars($thesis['year']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="department" class="form-label">Department</label>
                <input type="text" class="form-control" name="department" value="<?php echo htmlspecialchars($thesis['department']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="file_path" class="form-label">File Path</label>
                <input type="text" class="form-control" name="file_path" value="<?php echo htmlspecialchars($thesis['file_path']); ?>" required>
            </div>

            <button type="submit" class="btn btn-success">Update Thesis</button>
        </form>
    </div>
</body>
</html>
