<?php
session_start();
require "dp.php";
global $conn;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $user_name = isset($_SESSION['UserID']) ? $_SESSION['UserID'] : '';

    if ($user_name && $product_id) {
        // Check if the product is already in the wish list
        $stmt = $conn->prepare("SELECT * FROM wishing_list WHERE user_name = ? AND product_id = ?");
        $stmt->bind_param("si", $user_name, $product_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 0) {
            // Add to wish list
            $stmt = $conn->prepare("INSERT INTO wishing_list (user_name, product_id) VALUES (?, ?)");
            $stmt->bind_param("si", $user_name, $product_id);
            $stmt->execute();
        }
        $stmt->close();
    }
    header("Location: Decor.php");
    exit();
}
?>
