<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $code = $_POST['code'];

    // Fetch the code and expiration time from the database
    $stmt = $conn->prepare('SELECT code, expires_at FROM verification_codes WHERE email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->bind_result($stored_code, $expires_at);
    $stmt->fetch();
    $stmt->close();

    // Debugging output
    error_log("Email: $email");
    error_log("User entered code: $code");
    error_log("Stored code: $stored_code");
    error_log("Expires at: $expires_at");

    // Check if the code matches and is not expired
    $current_time = new DateTime(); // Current server time
    $expiration_time = new DateTime($expires_at); // Expiration time from the database

    if ($code === $stored_code && $current_time < $expiration_time) {
        // Move data from pending_users to users
        $stmt = $conn->prepare('INSERT INTO users (username, email, password) SELECT username, email, password FROM pending_users WHERE email = ?');
        $stmt->bind_param('s', $email);
        if ($stmt->execute()) {
            // Delete the user from pending_users after successful transfer
            $stmt = $conn->prepare('DELETE FROM pending_users WHERE email = ?');
            $stmt->bind_param('s', $email);
            $stmt->execute();

            echo 'Registration complete and verified!';
        } else {
            echo 'Failed to complete registration.';
        }
        $stmt->close();
    } else {
        echo 'Invalid or expired code.';
    }

    $conn->close();
}

