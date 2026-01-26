<?php
session_start();
include 'db_connection.php'; // Include your database connection file

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userID = $_SESSION['user_id']; // Get the user ID from the session

// Check if a preset avatar is selected
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['preset_avatar'])) {
    $selectedAvatar = $_POST['preset_avatar'];

    // Validate the selected avatar
    $allowedAvatars = ['aira.jpg', 'ken.jpg', 'momo.jpg', 'jiji.jpg', 'turbo-granny.jpg','seiko.jpg','sarasara.jpg','damon.jpg'
    ,'start-up-poster_cropped.png','start-up-poster_cropped (1).png','start-up-poster_cropped (2).png','start-up-poster_cropped (3).png'
    ,'start-up-kdrama_cropped.png','start-up-kdrama_cropped (1).png','start-up-kdrama_cropped (2).png','st_cropped.png' ];
    
    if (in_array($selectedAvatar, $allowedAvatars)) {
        // Get the avatar image data
        $avatarPath = './images/profile/' . $selectedAvatar; // Adjust the path as necessary
        if (!file_exists($avatarPath)) {
            header("Location: profile.php?status=error&message=Avatar file does not exist.");
            exit;
        }
        
        $fileData = file_get_contents($avatarPath);

        // Prepare the SQL statement to update the profile picture
        $stmt = $pdo->prepare("UPDATE user SET profile_picture = ? WHERE user_id = ?");
        
        // Execute the statement with the binary data and user ID
        try {
            if ($stmt->execute([$fileData, $userID])) {
                // Redirect to profile page with success message
                header("Location: profile.php?status=success&message=Profile picture updated successfully.");
                exit;
            } else {
                // Handle database update error
                header(" Location: profile.php?status=error&message=Failed to update profile picture.");
                exit;
            }
        } catch (PDOException $e) {
            // Handle any exceptions during the database operation
            header("Location: profile.php?status=error&message=" . urlencode($e->getMessage()));
            exit;
        }
    } else {
        // Handle invalid avatar selection
        header("Location: profile.php?status=error&message=Invalid avatar selected.");
        exit;
    }
}
?>