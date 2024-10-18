<?php
// farmer_login_backend.php

session_start();
require 'db_connection.php'; 

// Check if the request is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

 
    $sql = "SELECT * FROM farmers WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username); 
    $stmt->execute();
    $result = $stmt->get_result();
    $farmer = $result->fetch_assoc(); // Fetch the farmer record

    if ($farmer) {
        // Verify the password
        if (password_verify($password, $farmer['password'])) {
			
            // Store the farmer ID in the session
            $_SESSION['farmer_id'] = $farmer['id'];
            header("Location: farmer_dashboard.html");
            exit();
        } else {
            echo "Invalid username or password.";
        }
    } else {
        echo "No account found with that username.";
    }

    $stmt->close(); 
}
$conn->close(); // Close the connection
?>
