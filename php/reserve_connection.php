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
    echo "<div style='text-align: center; margin-top: 20px;'>
    <svg width='48' height='48' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'>
      <path d='M1 12L9 20L23 4' stroke='#28a745' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/>
    </svg>
    <p style='font-size: 18px; color: #28a745;'>Message Send Successfully!</p>
    <p style='font-size: 16px;'>Redirecting in 3 seconds...</p>
    <script>
        setTimeout(function(){
           window.location.href = '../index.html';
        }, 3000);
    </script>
  </div>";
} else {
    echo "Error: " . $stmt->error;
}

// Close connections
$stmt->close();
$conn->close();
?>
