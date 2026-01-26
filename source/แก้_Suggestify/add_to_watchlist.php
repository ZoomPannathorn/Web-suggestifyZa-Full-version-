<?php
session_start();
require 'db_connection.php'; // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User  not logged in']);
    exit();
}

$userId = $_SESSION['user_id']; // Get the user ID from the session

// Get the series_anime_id from the request
$data = json_decode(file_get_contents('php://input'), true);
$seriesAnimeId = $data['series_anime_id'];

if (empty($seriesAnimeId)) {
    echo json_encode(['success' => false, 'message' => 'Invalid series ID']);
    exit();
}

// Get the current date for the dated_added field
$dateAdded = date('Y-m-d H:i:s'); // Format the date as needed

// Prepare the SQL statement to insert the watchlist entry
$stmt = $pdo->prepare("INSERT INTO watchlist (user_id, series_id, dated_added) VALUES (?, ?, ?)");
if ($stmt->execute([$userId, $seriesAnimeId, $dateAdded])) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error adding to watchlist']);
}
?>