<?php
session_start();
require 'dp.php';
global $conn;

require 'PHPMailer-master/PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/PHPMailer-master/src/SMTP.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$errorStock=false;
$outOfStockProducts = [];
$userName = $_SESSION['UserID']; // Ensure user_name is stored in session


// Fetch cart items for the user
$stmt = $conn->prepare("SELECT sc.product_id, sc.quantity, p.product_name, p.price, i.image_url
FROM shopping_cart sc
JOIN product p ON sc.product_id = p.product_id
LEFT JOIN images_url i ON p.product_id = i.product_id
WHERE sc.user_name = ?");
$stmt->bind_param("s", $userName);
$stmt->execute();
$result = $stmt->get_result();
$cartItems = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$totalAmount = 0;
foreach ($cartItems as $item) {
    $totalAmount += $item['price'] * $item['quantity'];
}
// If cart is empty, display a message
if (empty($cartItems)) {
echo '<p>Your cart is empty.</p>';
exit();
}

function validateUserInfo($conn, $email)
{
    $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $isValid = $stmt->num_rows > 0;
    $stmt->close();
    return $isValid;
}

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
    // Fetch cart items for the user
    $stmt = $conn->prepare("SELECT sc.product_id, sc.quantity, p.price,p.stock,p.product_name
                            FROM shopping_cart sc
                            JOIN product p ON sc.product_id = p.product_id
                            WHERE sc.user_name = ?");
    $stmt->bind_param("s", $userName);
    $stmt->execute();
    $result = $stmt->get_result();
    $cartItems = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    $errorStock = false;


    foreach ($cartItems as $item) {
        $productId = $item['product_id'];
        $quantity = $item['quantity'];
        $price = $item['price'];
        $stock = $item['stock'];
        $name = $item['product_name'];

        if ($quantity > $stock) {
            $errorStock = true;
            $outOfStockProducts[] = $name; // Collect product names that are out of stock
        }
    }

    $email = $_POST['email'];

    if (validateUserInfo($conn, $email) and  !$errorStock) {
        // Insert order details into the database
        $stmt = $conn->prepare("INSERT INTO `order` (total_amount, paymnet_method, transaction_id, address, city, state, country, zip_code, user_name) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $paymentMethod = $_POST['payment_method'];
        $transactionId = $_POST['transaction'];
        $address = $_POST['address'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $country = $_POST['city'];
        $zipCode = $_POST['zip'];
        $stmt->bind_param("dssssssss", $totalAmount, $paymentMethod, $transactionId, $address, $city, $state, $country, $zipCode, $userName);

        $stmt->execute();
        $orderId = $stmt->insert_id;
        $stmt->close();
        $userName = $_SESSION['UserID']; // Ensure user_name is stored in session

        $subject = "Order Confirmation";
        $message = "<html>
                        <head>
                            <title>Order Confirmation</title>
                        </head>
                        <body>
                            <h1>Thank you for your order!</h1>
                            <p>Your order has been placed successfully. Below are your order details:</p>
                            <table>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                </tr>";

        foreach ($cartItems as $item) {
            $message .= "<tr>
                            <td>" . htmlspecialchars($item['product_name']) . "</td>
                            <td>" . $item['quantity'] . "</td>
                            <td>" . number_format($item['price'], 2) . " NIS</td>
                         </tr>";
        }

        $message .= "   </table>
                        <p>Total Amount: " . number_format($totalAmount, 2) . " NIS</p>
                        <p>We will send you another email once your order is shipped.</p>
                        </body>
                      </html>";


            // Insert order details
            foreach ($cartItems as $item) {
                $productId = $item['product_id'];
                $quantity = $item['quantity'];
                $price = $item['price'];
                $stock = $item['stock'] - $quantity; // Update stock

                $detailStmt = $conn->prepare("INSERT INTO order_deatails (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
                if (!$detailStmt) {
                    die('Prepare statement failed: ' . $conn->error);
                }

                $detailStmt->bind_param("iiid", $orderId, $productId, $quantity, $price);
                if (!$detailStmt->execute()) {
                    die('Execute statement failed: ' . $detailStmt->error);
                }
                $detailStmt->close();

                // Update the product stock
                $updateStockStmt = $conn->prepare("UPDATE product SET stock = ? WHERE product_id = ?");
                if (!$updateStockStmt) {
                    die('Prepare statement failed: ' . $conn->error);
                }

                $updateStockStmt->bind_param("ii", $stock, $productId);
                if (!$updateStockStmt->execute()) {
                    die('Execute statement failed: ' . $updateStockStmt->error);
                }
                $updateStockStmt->close();
            }

            sendConfirmationEmail($email, $subject, $message);

            // Clear the cart for the user
            $clearCartStmt = $conn->prepare("DELETE FROM shopping_cart WHERE user_name = ?");
            if (!$clearCartStmt) {
                die('Prepare statement failed: ' . $conn->error);
            }

            $clearCartStmt->bind_param("s", $userName);
            if (!$clearCartStmt->execute()) {
                die('Execute statement failed: ' . $clearCartStmt->error);
            }
            $clearCartStmt->close();

            $conn->close();


            $_SESSION['orderStatus'] = 'success';

            header("Location: view_cart.php");
            exit();
        } else {
            if (!empty($outOfStockProducts)) {
                $_SESSION['outOfStockProducts'] = $outOfStockProducts;
            header("Location: checkout.php");
            exit();}

        }

}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CheckOut</title>
    <link rel="stylesheet" href="assets/css/checkout.css">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">

    <!-- Additional CSS Files -->
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">

    <link rel="stylesheet" type="text/css" href="assets/css/font-awesome.css">

    <link rel="stylesheet" href="assets/css/HowiyyaShop.css">

    <link rel="stylesheet" href="assets/css/owl-carousel.css">

    <link rel="stylesheet" href="assets/css/lightbox.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <link rel="stylesheet" href="assets/css/custom.css">
    <link rel="stylesheet" href="assets/css/checkout.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script>
        var cardDrop = document.getElementById('card-dropdown');
        var activeDropdown;
        cardDrop.addEventListener('click',function(){
            var node;
            for (var i = 0; i < this.childNodes.length-1; i++)
                node = this.childNodes[i];
            if (node.className === 'dropdown-select') {
                node.classList.add('visible');
                activeDropdown = node;
            };
        })

        window.onclick = function(e) {
            console.log(e.target.tagName)
            console.log('dropdown');
            console.log(activeDropdown)
            if (e.target.tagName === 'LI' && activeDropdown){
                if (e.target.innerHTML === 'Master Card') {
                    document.getElementById('credit-card-image').src = 'https://dl.dropboxusercontent.com/s/2vbqk5lcpi7hjoc/MasterCard_Logo.svg.png';
                    activeDropdown.classList.remove('visible');
                    activeDropdown = null;
                    e.target.innerHTML = document.getElementById('current-card').innerHTML;
                    document.getElementById('current-card').innerHTML = 'Master Card';
                }
                else if (e.target.innerHTML === 'American Express') {
                    document.getElementById('credit-card-image').src = 'https://dl.dropboxusercontent.com/s/f5hyn6u05ktql8d/amex-icon-6902.png';
                    activeDropdown.classList.remove('visible');
                    activeDropdown = null;
                    e.target.innerHTML = document.getElementById('current-card').innerHTML;
                    document.getElementById('current-card').innerHTML = 'American Express';
                }
                else if (e.target.innerHTML === 'Visa') {
                    document.getElementById('credit-card-image').src = 'https://dl.dropboxusercontent.com/s/ubamyu6mzov5c80/visa_logo%20%281%29.png';
                    activeDropdown.classList.remove('visible');
                    activeDropdown = null;
                    e.target.innerHTML = document.getElementById('current-card').innerHTML;
                    document.getElementById('current-card').innerHTML = 'Visa';
                }
            }
            else if (e.target.className !== 'dropdown-btn' && activeDropdown) {
                activeDropdown.classList.remove('visible');
                activeDropdown = null;
            }
        }

    </script>
    <script>
        function showSection(sectionId) {
            var sections = document.getElementsByClassName('section');
            for (var i = 0; i < sections.length; i++) {
                sections[i].style.display = 'none';
            }
            document.getElementById(sectionId).style.display = 'block';
        }

        window.onload = function() {
            showSection('billing-address');
        }
    </script>
    <style>
        .order-info-content {
            max-height: 400px; /* Adjust this value as needed */
            width: 100%;
            overflow-y: auto;
        }

        .order-table img.full-width {
            max-width: 100px; /* Adjust as needed */
            height: auto;
        }

        .order-table td {
            vertical-align: middle;
        }

        .order-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .line {
            border-bottom: 1px solid #ccc;
            margin: 10px 0;
        }

        .total {
            font-weight: bold;
            margin-top: 20px;
        }

        .order-table-container {
            max-height: 400px; /* Adjust height for scrolling */
            overflow-y: auto;
        }
    </style>

</head>

<body>

<?php
include 'header.php';
?>

<?php
// Display out-of-stock products if they exist in the session
if (isset($_SESSION['outOfStockProducts']) && !empty($_SESSION['outOfStockProducts'])) {
    echo '<div class="alert alert-danger" style="color: darkred; padding-top: 10%"><strong>Out of Stock:</strong><ul>';

    foreach ($_SESSION['outOfStockProducts'] as $productName) {
        echo '<li style="color: black">' . htmlspecialchars($productName) . '</li>';
    }
    echo '</ul></div>';
    echo '<a href="view_cart.php"> GO BACK TO CART! </a>';
    // Clear the session variable
    unset($_SESSION['outOfStockProducts']);
}

?>
<div class='container' style="padding-top:10%;">
    <div class='window' style="width: 100%; height: 100%">
        <div class='order-info' style="width: 100%; height: 100%">
            <div class='order-info-content' style="width: 100%; height: 100%">
                <h2>Order Summary</h2>
                <h1>Checkout</h1>
                <div class='order-table-container' style="width: 100%; height: 100%">
                    <?php
                    $totalAmount = 0;
                    foreach ($cartItems as $item) {
                        $productName = isset($item['product_name']) ? $item['product_name'] : 'Unknown Product';
                        $productImage = isset($item['image_url']) ? $item['image_url'] : 'default.png'; // Default image
                        $total = $item['price'] * $item['quantity'];
                        $totalAmount += $total;
                        echo '<table class="order-table">
                    <tbody>
                    <tr>
                    
                        <td>
                            <img src="' . $productImage. '" class="full-width">
                        </td>
                        <td>
                            <br> <span class="thin">' . $productName. '</span>
                            <br> Quantity: ' . $item['quantity'] . '<br> <span class="thin small"> Price: ' . number_format($item['price'], 2) . ' NIS<br><br></span>
                        </td>
                        <td class="price">' . number_format($total, 2) . ' NIS</td>
                    </tr>
                    </tbody>
                    </table>
                    <div class="line"></div>';
                    }
                    ?>
                </div>
                <div class='total'>
                    <span style='float:left;'>
                        TOTAL Amount
                    </span>
                    <span style='float:right; text-align:right;'>
                        <div class='thin dense'><?php echo number_format($totalAmount, 2) . ' NIS'; ?></div>
                    </span>
                </div>
            </div>
        </div>
    </div>


        <div class='credit-info'>
            <div class='credit-info-content'>
                <?php if (isset($error)): ?>
                    <p style="color: red;"><?php echo $error; ?></p>
                <?php endif; ?>
                <?php if (isset($success)): ?>
                    <p style="color: green;"><?php echo $success; ?></p>
                <?php endif; ?>

                <form action="" method="post">
                    <div id="billing-address" class="section">
                        <h1 style="margin-top:0;margin-bottom:0; padding-top:0;font-family: 'Adobe Caslon Pro',serif; font-size: 25px;">Billing Address</h1>
                        <div class='line'></div>
                        Full Name
                        <input name="full_name" required class='input-field'></input>
                        Email
                        <input name="email" required class='input-field'></input>
                        Address
                        <input name="address" required class='input-field'></input>
                        City
                        <input name="city" required class='input-field'></input>
                        <table class='half-input-table'>
                            <tr>
                                <td style="color: white"> State
                                    <input name="state" class='input-field'></input>
                                </td>
                                <td style="color: white">Zip
                                    <input name="zip" class='input-field'></input>
                                </td>
                            </tr>
                        </table>
                        <button type="button" class='pay-btn' onclick="showSection('additional-info')" style="height: 50px; text-align: center">Next</button>
                        <!--                    <button class='pay-btn'>Checkout</button>-->
                    </div>

                    <div id="additional-info" class="section" style="display:none;">
                        <table class='half-input-table'>
                            <tr><td style="color:white; ">Please select your card: </td><td><div class='dropdown' id='card-dropdown'><div class='dropdown-btn' id='current-card'>Visa</div>
                                        <div class='dropdown-select'>
                                            <ul>
                                                <li>Master Card</li>
                                                <li>American Express</li>
                                            </ul></div>
                                    </div>
                                </td></tr>
                        </table>
                        <img src='https://dl.dropboxusercontent.com/s/ubamyu6mzov5c80/visa_logo%20%281%29.png' height='80' class='credit-card-image' id='credit-card-image'></img>
                        <h1 style="margin-top:0px;margin-bottom:0px;font-family: 'Adobe Caslon Pro',serif">Payment</h1>
                        <div class='line'></div>
                        <table>
                            <tr><td style="color:white; ">
                                    Payment Method
                                    <select style="color: white" name="payment_method" required class='input-field'>
                                        <option value="Visa" style="color: black">Visa</option>
                                        <option value="Master Card" style="color: black">Master Card</option>
                                        <option value="American Express" style="color: black">American Express</option>
                                        <option value="COD" style="color: black"> Cash on Delivery (COD)</option>
                                    </select>
                                </td></tr>
                            <tr><td style="color: white">
                                    Transaction
                                    <input name="transaction" required class='input-field'></input>
                                </td></tr>
                            <tr><td>
                                    <a style="color: white" href="javascritpt:void(0)" onclick="showSection('billing-address')">&larr; Back</a>
                                </td></tr>
                        </table>
                        <button type="submit" name="checkout"  class='pay-btn' style="height: 50px">Checkout</button>
                    </div>
            </div>
        </div>
    </div>
</div>

<!-- ***** Footer Start ***** -->
<?php
include 'footer.php';
?>

<!-- jQuery -->
<script src="assets/js/jquery-2.1.0.min.js"></script>

<!-- Bootstrap -->
<script src="assets/js/popper.js"></script>
<script src="assets/js/bootstrap.min.js"></script>

<!-- Plugins -->
<script src="assets/js/owl-carousel.js"></script>
<script src="assets/js/accordions.js"></script>
<script src="assets/js/datepicker.js"></script>
<script src="assets/js/scrollreveal.min.js"></script>
<script src="assets/js/waypoints.min.js"></script>
<script src="assets/js/jquery.counterup.min.js"></script>
<script src="assets/js/imgfix.min.js"></script>
<script src="assets/js/slick.js"></script>
<script src="assets/js/lightbox.js"></script>
<script src="assets/js/isotope.js"></script>

<!-- Global Init -->
<script src="assets/js/custom.js"></script>

<script>

    $(function() {
        var selectedClass = "";
        $("p").click(function(){
            selectedClass = $(this).attr("data-rel");
            $("#portfolio").fadeTo(50, 0.1);
            $("#portfolio div").not("."+selectedClass).fadeOut();
            setTimeout(function() {
                $("."+selectedClass).fadeIn();
                $("#portfolio").fadeTo(50, 1);
            }, 500);

        });
    });

</script>
</body>
</html>