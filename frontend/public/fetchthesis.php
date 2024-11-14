<?php
header('Content-Type: application/json');

$servername = "localhost";  // Database server
$username = "root";         // Database username
$password = "";             // Database password
$dbname = "admin_dashboard"; // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['department'])) {
    $department = $conn->real_escape_string($_GET['department']);

    // Correct SQL query to fetch the theses by department
    $query = "SELECT title, author, year, file_path FROM theses WHERE department = '$department'";
    
    $result = $conn->query($query);

    $theses = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $theses[] = $row;
        }
    }

    echo json_encode($theses);
} else {
    echo json_encode(["error" => "Department not specified"]);
}

$conn->close();
?>
