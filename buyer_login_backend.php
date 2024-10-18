<?php
// Database connection
require 'db_connection.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM buyers WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $buyer = $result->fetch_assoc();
        
        if (password_verify($password, $buyer['password'])) {
            $_SESSION['buyer_id'] = $buyer['id'];
            header("Location: buyer_dashboard.html");
            exit();
        } else {
            echo "Invalid username or password";
        }
    } else {
        echo "No account found with that username";
    }
}
?>
