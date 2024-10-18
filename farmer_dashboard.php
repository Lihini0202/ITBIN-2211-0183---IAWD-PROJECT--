<?php
session_start();

//Verify that the session is working and farmer_id is set
if (!isset($_SESSION['farmer_id'])) {
    die("Debug: Farmer is not logged in or session has expired. Redirecting to login page.");
} else {
    echo "Debug: Logged-in farmer ID is: " . $_SESSION['farmer_id'] . "<br>";
}

// Include database connection
require 'db_connection.php'; // Make sure this file is correctly pointing to your database

//  Verify if the database connection is successful
if ($conn->connect_error) {
    die("Debug: Database connection failed: " . $conn->connect_error); // Show connection error 
} else {
    echo "Debug: Database connection successful!<br>"; 
}

// Store the farmer_id from the session
$farmer_id = $_SESSION['farmer_id'];

// Prepare SQL query to fetch farmer and their items
$sql = "SELECT f.username, f.email, f.profile_image, i.name AS item_name, i.price, i.quantity, i.weight_unit, i.item_image
        FROM farmers f
        LEFT JOIN items i ON f.id = i.farmers_id
        WHERE f.id = ?";

// Prepare the SQL query
$stmt = $conn->prepare($sql);


if (!$stmt) {
    die("Debug: SQL query preparation failed: " . $conn->error); // Show error 
} else {
    echo "Debug: SQL query prepared successfully.<br>"; 
}

// Bind the farmer_id to the query
$stmt->bind_param("i", $farmer_id);

// Execute the query
$stmt->execute();

// STEP 5: Get the result of the query
$result = $stmt->get_result();

// STEP 6: Check if the query returned any results
if ($result->num_rows > 0) {
    echo "Debug: Farmer and item data found!<br>";
    
    // Loop through the results and display farmer and item information
    while ($row = $result->fetch_assoc()) {
        // Display each item with detailed information
        echo "Farmer: " . $row['username'] . "<br>";
        echo "Email: " . $row['email'] . "<br>";
        echo "Profile Image: " . $row['profile_image'] . "<br>";
        echo "Item: " . $row['item_name'] . "<br>";
        echo "Price: " . $row['price'] . "<br>";
        echo "Quantity: " . $row['quantity'] . "<br>";
        echo "Weight Unit: " . $row['weight_unit'] . "<br>";
        echo "Item Image: " . $row['item_image'] . "<br><br>";
    }
} else {
    // If no data was found for the farmer
    echo "Debug: No farmer or item data found for farmer ID " . $farmer_id . ".<br>";
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
