<?php
session_start();

// Check if the farmer is logged in
if (!isset($_SESSION['farmer_id'])) {
    header("Location: farmer_login.html");
    exit();
}

// Include the database connection
require 'db_connection.php';

// Fetch farmer ID from session
$farmer_id = $_SESSION['farmer_id'];

// Handle the POST request when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $country = $_POST['country'];
    $telephone = $_POST['telephone'];
    $gender = $_POST['gender']; 

    // Handle the image upload
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["profile_image"]["name"]);

        // Check if the file is an image
        $check = getimagesize($_FILES["profile_image"]["tmp_name"]);
        if ($check !== false) {
            // Move the uploaded directory
            if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
                // Update the profile image path to database
                $sql = "UPDATE farmers SET profile_image = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $target_file, $farmer_id);
                $stmt->execute();
                $stmt->close();
            } else {
                echo "Error uploading profile image.";
            }
        } else {
            echo "File is not an image.";
        }
    }

    // Update the farmer's details in the database, including gender
    $sql = "UPDATE farmers SET username = ?, email = ?, address = ?, city = ?, country = ?, telephone = ?, gender = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssi", $username, $email, $address, $city, $country, $telephone, $gender, $farmer_id);

    if ($stmt->execute()) {
        echo "Account updated successfully!";
        // Redirect to the dashboard or show success message
    } else {
        echo "Error updating account: " . $conn->error;
    }

    $stmt->close();
}

// Handle the account deletion
if (isset($_POST['delete_account'])) {
    // Delete the farmer's record from the database
    $sql = "DELETE FROM farmers WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $farmer_id);

    if ($stmt->execute()) {
        // Clear the session and redirect to the signup/login page
        session_destroy();
        header("Location: farmer_signup.html");
        exit();
    } else {
        echo "Error deleting account: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>
