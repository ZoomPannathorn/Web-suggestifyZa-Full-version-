<?php
session_start();
include 'db_connection.php'; // Include your database connection file

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userID = $_SESSION['user_id']; // Get the user ID from the session

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the new email from the form
    $newEmail = trim($_POST['new_email']);

    // Validate the new email format
    if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
        header("Location: profile.php?status=error&message=Invalid email format.");
        exit;
    }

    // Prepare the SQL statement to update the email
    $stmt = $pdo->prepare("UPDATE user SET email = ? WHERE user_id = ?");
    
    // Execute the statement with the new email and user ID
    if ($stmt->execute([$newEmail, $userID])) {
        // Update the session variable for the email
        $_SESSION['email'] = $newEmail;

        // Redirect with success message
        header("Location: profile.php?status=success&message=Email changed successfully!");
    } else {
        // Redirect with error message
        header("Location: profile.php?status=error&message=Error updating email.");
    }
}
?>