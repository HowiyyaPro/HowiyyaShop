<?php
session_start();
require 'dp.php';
global $conn;

$sql = "DELETE FROM user WHERE user_name = '".$_GET['id']."'";
$conn->query($sql);
$conn->close();

header('location: users.php');