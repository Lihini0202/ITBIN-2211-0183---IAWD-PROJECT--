<?php
// Database connection
require 'db_connection.php';
session_start();

if (isset($_SESSION['buyer_id'])) {
    $buyer_id = $_SESSION['buyer_id'];

    $sql = "SELECT o.id, o.quantity_ordered, o.total_price, o.order_status, o.tracking_number, i.item_name 
            FROM orders o 
            JOIN items i ON o.item_id = i.id 
            WHERE o.buyer_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $buyer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($order = $result->fetch_assoc()) {
        echo "Order ID: " . $order['id'] . " | Item: " . $order['item_name'] .
             " | Status: " . $order['order_status'] . " | Tracking: " . $order['tracking_number'] . "<br>";
    }
}
?>
