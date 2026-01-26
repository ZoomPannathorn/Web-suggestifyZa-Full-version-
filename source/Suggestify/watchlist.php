<?php
session_start(); // Start the session to get the user data (make sure user is logged in)

require 'db_connection.php'; // Database connection file

// Get the logged-in user's ID
$userId = $_SESSION['user_id'];

// Query to fetch anime from the watchlist for this user
$query = "SELECT * FROM anime_watchlist WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Anime Watchlist</title>
</head>
<body>
    <h1>Your Anime Watchlist</h1>
    <ul>
        <?php while ($anime = $result->fetch_assoc()): ?>
            <li>
                <h3><?php echo htmlspecialchars($anime['anime_title']); ?></h3>
                <p><?php echo htmlspecialchars($anime['anime_description']); ?></p>
                <form action="remove_from_watchlist.php" method="post">
                    <input type="hidden" name="anime_id" value="<?php echo $anime['id']; ?>">
                    <button type="submit" name="remove">Remove from Watchlist</button>
                </form>
            </li>
        <?php endwhile; ?>
    </ul>
</body>
</html>
<?php
$stmt->close();
?>
