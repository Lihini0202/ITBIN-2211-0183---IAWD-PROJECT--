<?php
// Include the database connection
require 'db_connection.php';
error_reporting(E_ALL); 
ini_set('display_errors', 1); 

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 

    // Prepare SQL statement 
    $stmt = $conn->prepare("INSERT INTO farmers (username, email, password) VALUES (?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("sss", $username, $email, $password);

        // Execute and check for errors
        if ($stmt->execute()) {
			
            //  login with success message
            header("Location: farmer_login.html?signup=success");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
    }
}
$conn->close();
?>
