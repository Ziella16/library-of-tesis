<?php
$servername = "localhost";
$username = "evetubp";
$password = "Z6KJNxBRu4K+547u";
$dbname = "evetubp_libraryoftesis"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Connection for admin database
$admin_dbname = "evetubp_library-of-tesis"; // Replace with your actual admin database name
$admin_conn = new mysqli($servername, $username, $password, $admin_dbname);

// Check connection
if ($admin_conn->connect_error) {
    die("Connection to admin database failed: " . $admin_conn->connect_error);
}