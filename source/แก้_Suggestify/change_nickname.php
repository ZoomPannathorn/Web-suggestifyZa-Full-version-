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
    // Get the new nickname from the form
    $newNickname = trim($_POST['new_nickname']);

    // Validate the new nickname (you can add more validation rules as needed)
    if (empty($newNickname)) {
        header("Location: profile.php?status=error&message=Nickname cannot be empty.");
        exit;
    }

    // Prepare the SQL statement to update the nickname
    $stmt = $pdo->prepare("UPDATE user SET username = ? WHERE user_id = ?");
    
    // Execute the statement with the new nickname and user ID
    if ($stmt->execute([$newNickname, $userID])) {
        // Update the session variable for the username
        $_SESSION['username'] = $newNickname;

        // Redirect with success message
        header("Location: profile.php?status=success&message=Nickname changed successfully!");
    } else {
        // Redirect with error message
        header("Location: profile.php?status=error&message=Error updating nickname.");
    }
}
?>