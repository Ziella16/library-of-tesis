<?php
include 'connection.php'; // This should define the $conn variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Password validation regex
    $passwordPattern = '/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';

    // Validate the email domain and ensure the part before '@' is numeric
    if (preg_match('/^[A-Za-z-0-9]+@adtectaiping\.edu\.my$/i', $email)) {
        // Validate the username contains only alphabetic characters
        if (preg_match('/^[A-Za-z]+$/', $username)) {
            // Validate the password
            if (preg_match($passwordPattern, $password)) {
                // Encrypt the password
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                // Check if the email already exists in the pending_users or users table
                $checkEmailQuery = "SELECT * FROM users WHERE email = '$email' UNION SELECT * FROM pending_users WHERE email = '$email'";
                $result = $conn->query($checkEmailQuery);

                if ($result->num_rows > 0) {
                    echo "This email is already registered.";
                } else {
                    // Insert into pending_users instead of users table
                    $sql = "INSERT INTO pending_users (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";

                    if ($conn->query($sql) === TRUE) {
                        // Redirect to verify.php after successful registration
                        header("Location: http://localhost/myproject/verify.php?email=" . urlencode($email));
                        exit(); // Ensure no further processing is done
                    } else {
                        echo "Error: " . $sql . "<br>" . $conn->error;
                    }
                }
            } else {
                // Password does not meet the criteria
                echo "Password must be at least 8 characters long and include at least one letter, one number, and one special character.";
            }
        } else {
            // Username contains invalid characters
            echo "Username must contain only alphabetic characters.";
        }
    } else {
        // Email format is not valid
        echo "This email is not allowed. Ensure the email starts with numbers and ends with '@adtectaiping.edu.my'.";
    }

    $conn->close();
}

