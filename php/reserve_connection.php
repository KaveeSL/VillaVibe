<?php
// Database connection
$servername = "localhost";
$username = "root";  // Your MySQL username
$password = "";      // Your MySQL password
$dbname = "villavibe";  // Your MySQL database

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$room_type = $_POST['rooms_booking'];
$adults = $_POST['adults_booking'];
$children = $_POST['childs_booking'];
$name = $_POST['name_booking'];
$email = $_POST['email_booking'];

// Prepare and bind SQL statement
$stmt = $conn->prepare("INSERT INTO bookings (room_type, adults, children, name, email) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("siiss", $room_type, $adults, $children, $name, $email);

// Execute the statement
if ($stmt->execute()) {
    echo "Booking successful!";
} else {
    echo "Error: " . $stmt->error;
}

// Close connections
$stmt->close();
$conn->close();
?>
