<?php
// login.php
session_start();
require 'dp.php';
global $conn;
$flag=0;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and bind
    $stmt = $conn->prepare("SELECT user_name, password FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);

    // Execute the query
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows ==1) {
        // Bind result variables
        $stmt->bind_result($user_name, $hashedPassword);
        $stmt->fetch();

        // Verify the password
        if (sha1($password)==$hashedPassword) {
            // Password is correct, start a session
            $_SESSION['UserID'] = $user_name;
            $_SESSION['Email'] = $email;
header('location: index.php');
            exit();
        } else {
           $flag=1;
        }
    } else {
        $flag=2;
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>
