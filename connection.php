<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "userregisteration"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Connection for admin database
$admin_dbname = "admin_dashboard"; // Replace with your actual admin database name
$admin_conn = new mysqli($servername, $username, $password, $admin_dbname);

// Check connection
if ($admin_conn->connect_error) {
    die("Connection to admin database failed: " . $admin_conn->connect_error);
}