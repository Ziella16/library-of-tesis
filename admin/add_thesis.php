<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Thesis</title>
</head>
<body>
    <h2>Add New Thesis</h2>
    <form action="add_thesis.php" method="POST">
        <label>Title:</label>
        <input type="text" name="title" required><br>
        <label>Author:</label>
        <input type="text" name="author" required><br>
        <label>Year:</label>
        <input type="number" name="year" required><br>
        <label>Department:</label>
        <input type="text" name="department" required><br>
        <label>File Path:</label>
        <input type="text" name="file_path" required><br>
        <button type="submit" name="submit">Add Thesis</button>
    </form>
</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $year = $_POST['year'];
    $department = $_POST['department'];
    $file_path = $_POST['file_path'];

    $stmt = $pdo->prepare("INSERT INTO theses (title, author, year, department, file_path) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$title, $author, $year, $department, $file_path]);

    echo "Thesis added successfully!";
}
?>
