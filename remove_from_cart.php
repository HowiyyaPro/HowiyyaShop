<?php
session_start();
require 'dp.php';
global $conn;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id'])) {
    $productId = $_POST['product_id'];
    $userName = $_SESSION['UserID']; // Assuming you have the user's username stored in the session

    // Remove the item from the cart
    $stmt = $conn->prepare("DELETE FROM shopping_cart WHERE user_name = ? AND product_id = ?");
    $stmt->bind_param("si", $userName, $productId);
    $stmt->execute();
    $stmt->close();

    // Redirect back to the cart page
    header("Location: view_cart.php");
    exit();
}
?>
