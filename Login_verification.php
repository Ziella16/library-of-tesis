<?php
include 'connection.php'; // Ensure this file contains the correct database connection code

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Check in admin table first using the admin connection
        $stmt = $admin_conn->prepare("SELECT * FROM admins WHERE email = ?");
        if ($stmt === false) { die("Prepare failed: " . $admin_conn->error);}
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $admin_result = $stmt->get_result();

        if ($admin_result->num_rows > 0) {
            // Fetch the admin data
            $admin = $admin_result->fetch_assoc();


            // Verify the password with the hashed password in the database
            if (password_verify($password, $admin['password'])) {
                // Password is correct, start a session and redirect to the admin page
                session_start();
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                header("Location: http://localhost/myproject/admin/admin_dashboard.php");
                exit();
            } else {
                // Incorrect password for admin
                echo "<script>alert('Incorrect password for admin. Please try again.');</script>";
            }
        } else {
            // Not an admin, check in users table using the user connection
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $user_result = $stmt->get_result();

            if ($user_result->num_rows > 0) {
                // Fetch the user data
                $user = $user_result->fetch_assoc();

                // Verify the password with the hashed password in the database
                if (password_verify($password, $user['password'])) {
                    // Password is correct, start a session and redirect to user dashboard
                    session_start();
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    header("Location: http://localhost/myproject/frontend/public/dashboard.php");
                    exit();
                } else {
                    // Incorrect password for user
                    echo "<script>alert('Incorrect password for user. Please try again.');</script>";
                }
            } else {
                // Email not found in either table
                echo "<script>alert('No user or admin found with this email. Please register first.');</script>";
            }
        }

        // Close the statement
        $stmt->close();
    } else {
        // Invalid email format
        echo "<script>alert('Invalid email format.');</script>";
    }

    // Close the connections
    $admin_conn->close();
    $conn->close();
}
