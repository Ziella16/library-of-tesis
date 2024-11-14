<?php
include 'db.php'; // Database connection file

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // Get the file path from the database
        $stmt = $pdo->prepare("SELECT file_path FROM theses WHERE id = ?");
        $stmt->execute([$id]);
        $thesis = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($thesis) {
            // Full path to the file
            $filePath = "C:/xampp/htdocs/myproject/frontend/public/" . $thesis['file_path'];

            // Check if the file exists and delete it
            if (file_exists($filePath)) {
                unlink($filePath); // Delete the file
            }

            // Now delete the record from the database
            $deleteStmt = $pdo->prepare("DELETE FROM theses WHERE id = ?");
            $deleteStmt->execute([$id]);

            if ($deleteStmt->rowCount() > 0) {
                // Redirect to manage_thesis.php with success message
                header("Location: manage_thesis.php?message=Thesis+deleted+successfully");
                exit;
            } else {
                header("Location: manage_thesis.php?error=Failed+to+delete+thesis+record");
                exit;
            }
        } else {
            // No thesis found with that ID
            header("Location: manage_thesis.php?error=Thesis+not+found");
            exit;
        }
    } catch (PDOException $e) {
        // If there's an error with the query
        echo "Error: " . $e->getMessage();
        exit;
    }
} else {
    // If no valid ID is provided
    echo "No valid thesis ID specified!";
    exit;
}
?>
