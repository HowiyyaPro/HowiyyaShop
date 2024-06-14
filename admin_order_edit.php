<?php
session_start();
require 'dp.php';
global $conn;

require 'PHPMailer-master/PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/PHPMailer-master/src/SMTP.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendConfirmationEmail($to, $subject, $message)
{
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'caraccessories1224@gmail.com'; // Your Gmail address
        $mail->Password = 'qjrp yumx pcra mhec'; // Your Gmail password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('caraccessories1224@gmail.com', 'HowiyyaShop'); // Set a valid from address
        $mail->addAddress($to);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();
        $mail->SMTPDebug = 2;
//        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    // Update the status in the database
    $stmt = $conn->prepare("UPDATE order_deatails SET status = ? WHERE order_id = ?");
    $stmt->bind_param("si", $status, $order_id);
    $stmt->execute();
    $stmt->close();

    // If the status is changed to "delivered", send an email
    if ($status == 'delivered') {
        // Fetch user email
        $stmt = $conn->prepare("SELECT u.email FROM user u JOIN `order` o ON u.user_name = o.user_name WHERE o.order_id = ?");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $stmt->bind_result($userEmail);
        $stmt->fetch();
        $stmt->close();

        $subject = "Your Order Has Been Delivered";
        $message = "<html>
                        <head>
                            <title>Order Delivered</title>
                        </head>
                        <body>
                            <h1>Good news!</h1>
                            <p>Your order #$order_id has been delivered successfully.</p>
                        </body>
                    </html>";

        sendConfirmationEmail($userEmail, $subject, $message);
    }

    // Redirect back to the admin order page
    header("Location: admin_order.php");
    exit();
}
?>
