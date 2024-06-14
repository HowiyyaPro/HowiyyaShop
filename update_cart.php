<?php
session_start();
require 'dp.php';
global $conn;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $productId = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $userName = $_SESSION['UserID']; // Assuming you have the user's username stored in the session

    // Update the quantity in the cart
    $stmt = $conn->prepare("UPDATE shopping_cart SET quantity = ?, added_at = CURRENT_TIMESTAMP WHERE user_name = ? AND product_id = ?");
    $stmt->bind_param("isi", $quantity, $userName, $productId);
    $stmt->execute();
    $stmt->close();

    // Redirect back to the cart page
    header("Location: view_cart.php");
    exit();
}
?>
