<?php
session_start();
require 'dp.php';
global $conn;

$sql = "DELETE FROM product WHERE product_id = '".$_GET['id']."'";
$conn->query($sql);
$conn->close();

header('location: admin_product.php');