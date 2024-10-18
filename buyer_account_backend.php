<?php
// Include the database connection
require 'db_connection.php';
session_start();

// Check if the buyer is logged in
if (!isset($_SESSION['buyer_id'])) {
    header("Location: buyer_login.html"); 
    exit();
}


$buyer_id = $_SESSION['buyer_id'];


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $country = $_POST['country'];
    $phone = $_POST['phone'];

    // Prepare SQL statement for updating buyer information
    $stmt = $conn->prepare("UPDATE buyers SET username = ?, email = ?, address = ?, city = ?, country = ?, phone = ? WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("ssssssi", $username, $email, $address, $city, $country, $phone, $buyer_id);
        
      
        if ($stmt->execute()) {
            echo "Account updated successfully.";
        } else {
            echo "Error updating account: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
    }
}

// Handle account deletion
if (isset($_POST['delete'])) {

    $stmt = $conn->prepare("DELETE FROM buyers WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $buyer_id);
        
        // Execute and check for errors
        if ($stmt->execute()) {
            // Logout and redirect to login page
            session_destroy();
            header("Location: buyer_login.html?delete=success");
            exit();
        } else {
            echo "Error deleting account: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
    }
}

$conn->close();
?>
