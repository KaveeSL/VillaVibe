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

// Close connection
$stmt->close();
$conn->close();
?>
