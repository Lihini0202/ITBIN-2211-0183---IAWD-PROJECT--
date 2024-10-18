<?php
// Database connection
require 'db_connection.php';
session_start();

if (isset($_SESSION['buyer_id']) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $buyer_id = $_SESSION['buyer_id'];
    $item_id = $_POST['item_id'];
    $quantity = $_POST['quantity'];
    
    // Fetch item details
    $sql = "SELECT price FROM items WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $item = $result->fetch_assoc();
    
    $total_price = $item['price'] * $quantity;

    $sql = "INSERT INTO orders (item_id, buyer_id, quantity_ordered, total_price) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiid", $item_id, $buyer_id, $quantity, $total_price);

    if ($stmt->execute()) {
        header("Location: buyer_dashboard.html?order=success");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
