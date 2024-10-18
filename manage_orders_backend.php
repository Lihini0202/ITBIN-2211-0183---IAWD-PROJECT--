<?php
// Database connection
require 'db_connection.php';
session_start();

if (isset($_SESSION['farmer_id'])) {
    $farmer_id = $_SESSION['farmer_id'];
    $sql = "SELECT o.id, o.quantity_ordered, o.total_price, o.order_status, o.tracking_number, b.username, i.item_name 
            FROM orders o 
            JOIN buyers b ON o.buyer_id = b.id 
            JOIN items i ON o.item_id = i.id 
            WHERE i.farmer_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $farmer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($order = $result->fetch_assoc()) {
        echo "Order ID: " . $order['id'] . " | Buyer: " . $order['username'] . " | Item: " . $order['item_name'] .
             " | Status: " . $order['order_status'] . "<br>";
        // Add Accept/Reject and Ship buttons
    }
}
?>
