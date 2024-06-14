<?php
require 'dp.php'; // Include your database connection file
global $conn;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];

    // Delete from order_details table
    $stmt = $conn->prepare("DELETE FROM order_deatails WHERE order_id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $stmt->close();

    // Delete from order table
    $stmt = $conn->prepare("DELETE FROM `order` WHERE order_id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $stmt->close();

    // Redirect back to the orders page after deletion
    header("Location: admin_order.php");
    exit();
}
?>
