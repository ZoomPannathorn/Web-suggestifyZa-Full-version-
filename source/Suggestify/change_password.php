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
    // Get form data
    $oldPassword = $_POST['old_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Validate new password confirmation
    if ($newPassword !== $confirmPassword) {
        header("Location: profile.php?status=error&message=New passwords do not match.");
        exit;
    }

    // Fetch the current password from the database
    $stmt = $pdo->prepare("SELECT password FROM user WHERE user_id = ?");
    $stmt->execute([$userID]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if user exists
    if (!$user) {
        header("Location: profile.php?status=error&message=User  not found.");
        exit;
    }

    // Verify the old password
    if (!password_verify($oldPassword, $user['password'])) {
        header("Location: profile.php?status=error&message=Old password is incorrect.");
        exit;
    }

    // Hash the new password
    $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Update the password in the database
    $stmt = $pdo->prepare("UPDATE user SET password = ? WHERE user_id = ?");
    if ($stmt->execute([$hashedNewPassword, $userID])) {
        // Redirect with success message
        header("Location: profile.php?status=success&message=Password changed successfully!");
    } else {
        // Redirect with error message
        header("Location: profile.php?status=error&message=Error updating password.");
    }
}
?>