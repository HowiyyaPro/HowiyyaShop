<?php
session_start();
require 'dp.php';
global $conn;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id'])) {
    $productId = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $userName = $_SESSION['UserID']; // Assuming you have the user's username stored in the session

    // Check if the product is already in the cart for this user
    $stmt = $conn->prepare("SELECT quantity FROM shopping_cart WHERE user_name = ? AND product_id = ?");
    $stmt->bind_param("si", $userName, $productId);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($currentQuantity);
        $stmt->fetch();
        $newQuantity = $currentQuantity + $quantity;

        // Update the quantity in the cart
        $updateStmt = $conn->prepare("UPDATE shopping_cart SET quantity = ?, added_at = CURRENT_TIMESTAMP WHERE user_name = ? AND product_id = ?");
        $updateStmt->bind_param("isi", $newQuantity, $userName, $productId);
        $updateStmt->execute();
        $updateStmt->close();
    } else {
        // Insert new item into the cart
        $insertStmt = $conn->prepare("INSERT INTO shopping_cart (user_name, product_id, quantity) VALUES (?, ?, ?)");
        $insertStmt->bind_param("sii", $userName, $productId, $quantity);
        $insertStmt->execute();
        $insertStmt->close();
    }

    $stmt->close();
    $conn->close();

    // Set the success message
    $_SESSION['message'] = "Product added to cart successfully!";

    // Redirect back to the product page
    $previousPage = $_SERVER['HTTP_REFERER'];
    header("Location: $previousPage");
    exit();
}

