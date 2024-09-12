<?php
// Database connection
$servername = "localhost";
$username = "root";  // MySQL username
$password = "";      // MySQL password
$dbname = "villavibe";  // MySQL database

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data from POST request
$name = $_POST['name_contact'];
$lastname = $_POST['lastname_contact'];
$email = $_POST['email_contact'];
$phone = $_POST['phone_contact'];
$message = $_POST['message_contact'];

// Prepare and execute the insert statement
$stmt = $conn->prepare("INSERT INTO contacts (name, lastname, email, phone, message) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $name, $lastname, $email, $phone, $message);

if ($stmt->execute()) {
    echo "Success";
} else {
    echo "Error: " . $stmt->error;
}

// Close connection
$stmt->close();
$conn->close();
?>
