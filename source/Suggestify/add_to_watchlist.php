<?php
session_start(); // Start the session to get the user data (make sure user is logged in)

require 'db_connection.php'; // Database connection file

if (isset($_POST['submit'])) {
    $userId = $_SESSION['user_id']; // Get user ID from session
    $animeTitle = htmlspecialchars($_POST['anime_title']);
    $animeDescription = htmlspecialchars($_POST['anime_description']);

    // SQL query to insert anime into the database
    $query = "INSERT INTO anime_watchlist (user_id, anime_title, anime_description) 
              VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iss", $userId, $animeTitle, $animeDescription);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Anime added to watchlist!";
    } else {
        echo "Error adding anime to watchlist.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add to Watchlist</title>
</head>
<body>
    <form action="add_to_watchlist.php" method="post">
        <label for="anime_title">Anime Title:</label>
        <input type="text" id="anime_title" name="anime_title" required><br>
        
        <label for="anime_description">Description:</label>
        <textarea id="anime_description" name="anime_description" required></textarea><br>
        
        <button type="submit" name="submit">Add to Watchlist</button>
    </form>
</body>
</html>
