<?php
session_start();

// Database connection settings
$servername = "localhost";
$username = "root"; // Default MySQL username
$password = ""; // Default MySQL password (change if needed)
$dbname = "villavibe";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$input_username = $_POST['username'];
$input_password = $_POST['password'];

// Prepare and execute SQL statement
$stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
$stmt->bind_param("s", $input_username);
$stmt->execute();
$result = $stmt->get_result();

// Check if user exists
if ($result->num_rows > 0) {
    // Fetch the password hash
    $row = $result->fetch_assoc();
    $stored_password = $row['password'];

    // Verify the password
    if ($input_password === $stored_password) {
        // Store user information in session
        $_SESSION['username'] = $input_username;
        
        // Redirect to the dashboard
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Invalid username or password. Please try again.";
    }
} else {
    echo "Invalid username or password. Please try again.";
}

// Close the connection
$stmt->close();
$conn->close();
?>
