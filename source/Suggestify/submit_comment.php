<?php
session_start();
require 'db_connection.php'; // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if the user is not logged in
    exit();
}

$userId = $_SESSION['user_id']; // Get the user ID from the session
$seriesAnimeId = $_POST['series_anime_id']; // Get the series/anime ID from the form
$commentText = $_POST['comment']; // Get the comment text from the form
$datePosted = date('Y-m-d'); // Get the current date

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($commentText)) {
    // Prepare and execute the SQL statement to insert the comment
    $stmt = $pdo->prepare("INSERT INTO Comments (user_id, series_anime_id, comment_text, date_posted) VALUES (?, ?, ?, ?)");
    $stmt->execute([$userId, $seriesAnimeId, $commentText, $datePosted]);

    // Redirect back to the review page or send a success response
    header("Location: review.php?drama=" . $seriesAnimeId); // Redirect to the review page
    exit();
} else {
    // If comment is empty, redirect back with a message
    header("Location: review.php?drama=" . $seriesAnimeId . "&error=empty_comment");
    exit();
}
?>
