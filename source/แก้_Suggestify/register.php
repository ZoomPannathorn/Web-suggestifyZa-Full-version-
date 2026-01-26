<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection parameters
$servername = "10.1.3.40";
$username = "66102010584";
$password = "66102010584";
$dbname = "66102010584";  // Database name is "user"

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Get form data
    $userid = $_POST['userid'];
    $pass = $_POST['pass'];
    $repass = $_POST['repass'];
    $email = $_POST['email'];
    $sex = isset($_POST['sex']) ? $_POST['sex'] : '';  // This will now be 'Male', 'Female', 'Other', or 'Prefer not to say'
    $terms = isset($_POST['terms']) ? 1 : 0; // Check if terms checkbox is ticked

    // Validate password confirmation
    if ($pass !== $repass) {
        // Redirect with error status
        header("Location: register-status.php?status=error&message=Passwords do not match.");
        exit;
    }

    // Validate sex value (it should be one of the valid ENUM values)
    $validSexValues = ['male', 'female', 'other', 'prefer not to say'];
    if (!in_array(strtolower($sex), $validSexValues)) {  // Use strtolower to avoid case-sensitive issues
        // Redirect with error status
        header("Location: register-status.php?status=error&message=Invalid gender selected.");
        exit;
    }

    // Validate email format (basic email validation)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Redirect with error status
        header("Location: register-status.php?status=error&message=Invalid email format.");
        exit;
    }

    // Check if the username already exists
    $stmt = $conn->prepare("SELECT COUNT(*) FROM user WHERE username = ?");
    if ($stmt === false) {
        // Redirect with error status
        header("Location: register-status.php?status=error&message=SQL query failed: " . $conn->error);
        exit;
    }

    $stmt->bind_param("s", $userid);
    $stmt->execute();
    
    // Get the result and check if the username exists
    $stmt->bind_result($user_count);
    $stmt->fetch();
    $stmt->close();

    // Check if the username already exists
    if ($user_count > 0) {
        // Redirect with error status
        header("Location: register-status.php?status=error&message=Username is already taken.");
        exit;
    }

    // Prepare the SQL query to insert the data
    $stmt = $conn->prepare("INSERT INTO user (username, email, password, sex) VALUES (?, ?, ?, ?)");
    if ($stmt === false) {
        // Redirect with error status
        header("Location: register-status.php?status=error&message=SQL query failed: " . $conn->error);
        exit;
    }

    $stmt->bind_param("ssss", $userid, $email, password_hash($pass, PASSWORD_DEFAULT), $sex);

    // Execute the query
    if ($stmt->execute()) {
        // Redirect with success status
        header("Location: register-status.php?status=success&message=Registration successful!");
    } else {
        // Redirect with error status
        header("Location: register-status.php?status=error&message=Error: " . $stmt->error);
    }

    // Close the prepared statement and connection
    $stmt->close();
    $conn->close();
}
?>
