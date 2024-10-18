<?php
// Database connection
require 'db_connection.php';
session_start();

if (isset($_SESSION['farmer_id']) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $farmer_id = $_SESSION['farmer_id'];
    $name = $_POST['item_name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $weight_unit = $_POST['weight_unit']; 

    // Handle file upload
    $item_image = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['image']['tmp_name'];
        $fileName = $_FILES['image']['name'];
        $fileSize = $_FILES['image']['size'];
        $fileType = $_FILES['image']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $allowedfileExtensions = array('jpg', 'gif', 'png', 'jpeg');
        if (in_array($fileExtension, $allowedfileExtensions)) {
			
			
           
            $uploadFileDir = 'uploads/';
            $dest_path = $uploadFileDir . $fileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $item_image = $dest_path;
            } else {
                echo "There was an error moving the uploaded file.";
                exit();
            }
        } else {
            echo "Upload failed. Allowed file types: " . implode(',', $allowedfileExtensions);
            exit();
        }
    }

  
    $sql = "INSERT INTO items (farmer_id, name, price, quantity, item_image, weight_unit) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Adjusted bind_param to match correct variable types: i -> integer, s -> string, d -> double
    $stmt->bind_param("isdiss", $farmer_id, $name, $price, $quantity, $item_image, $weight_unit);

    if ($stmt->execute()) {
        header("Location: farmer_dashboard.html?item_added=success");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
