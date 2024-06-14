<?php
session_start();
require 'dp.php';
global $conn;
if (isset($_SESSION['orderStatus']) && $_SESSION['orderStatus'] == 'success') {
    $title = "Order Successful";
    $message = "Your order is successful!";
    echo '<script type="text/javascript">
            window.onload = function() {
                alert("Your order is successful!  We have send you an email with your order details.");
            };
          </script>';
    unset($_SESSION['orderStatus']); // Clear the session variable
}
$empty = false;

$userName = $_SESSION['UserID']; // Assuming you have the user's username stored in the session

$stmt = $conn->prepare("SELECT sc.product_id, sc.quantity, p.product_name, p.price, i.image_url 
                        FROM shopping_cart sc 
                        JOIN product p ON sc.product_id = p.product_id 
                        LEFT JOIN images_url i ON p.product_id = i.product_id 
                        WHERE sc.user_name = ?");
$stmt->bind_param("s", $userName);
$stmt->execute();
$result = $stmt->get_result();
$products = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$conn->close();

if (empty($products)) {
    $empty=true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">

    <title>Hexashop - Product Listing Page</title>


    <!-- Additional CSS Files -->
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">

    <link rel="stylesheet" type="text/css" href="assets/css/font-awesome.css">

    <link rel="stylesheet" href="assets/css/HowiyyaShop.css">

    <link rel="stylesheet" href="assets/css/owl-carousel.css">

    <link rel="stylesheet" href="assets/css/lightbox.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <link rel="stylesheet" href="assets/css/custom.css">
    <link rel="stylesheet" href="assets/css/cart.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

</head>
<body>
<?php
include 'header.php';
?>
<!-- ***** Header Area End ***** -->

<div class="container px-3 my-5 clearfix" style="align-content: center ; width: 100%; padding-top: 5%;margin-left: 15%">
    <!-- Shopping cart table -->
    <div class="card">
        <div class="card-header">
            <h2>Shopping Cart</h2>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered m-0">
                    <thead>
                    <tr>
                        <th class="text-center">Product</th>
                        <th class="text-center" >Price</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-center" >Total</th>
                        <th class="text-center">Remove</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if($empty==true)
                    {
                        echo '<p style="color: darkred; font-weight: bolder;font-size: 16px">"YOUR CART IS EMPTY !"</p>';
                    }
                    else{
                    foreach ($products as $product) {
                        $productId = $product['product_id'];
                        $quantity = $product['quantity'];
                        $total = $product['price'] * $quantity;
                        echo '<tr>
                        <td class="p-4">
                            <div class="media align-items-center">
                                <img src="' . htmlspecialchars($product['image_url']) . '" class="d-block ui-w-40 ui-bordered mr-4" alt="">
                                <div class="media-body">
                                    <a href="single-product.php?product_id=' . $productId . '" class="d-block text-dark">' . htmlspecialchars($product['product_name']) . '</a>
                                </div>
                            </div>
                        </td>
                        <td class="p-4 text-center align-middle">' . number_format($product['price'], 2) . ' NIS</td>
                        <td class="align-middle p-4">
                            <form action="update_cart.php" method="post" style="display: flex; align-items: center; justify-content: center;">
                            <input type="hidden" name="product_id" value="' . $productId . '">
                             <input type="number" class="form-control text-center" name="quantity" value="' . $quantity . '" min="1" style="max-width: 100px;">
                             <button type="submit" class="btn btn-sm btn-primary ml-2" style="background-color: #2a2a2a;">Update</button>
                                </form>

                        </td>
                        <td class="p-4 text-center align-middle">' . number_format($total, 2) . ' NIS</td>
                        <td class="text-center align-middle px-0">
                            <form action="remove_from_cart.php" method="post">
                                <input type="hidden" name="product_id" value="' . $productId . '">
                                <button type="submit" class="shop-tooltip close float-none text-danger" style="width: 20px; height: 20px;">×</button>
                            </form>
                        </td>
                    </tr>';}
                    }
                    ?>
                    </tbody>
                </table>

            </div>
        </div>
        <a style="background-color: black; width: 250px; align-self: end" href="checkout.php" class="btn btn-sm btn-primary ml-2" >Proceed to CheckOut</a>
    </div>
</div>

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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/5.0.0-beta3/js/bootstrap.bundle.min.js"></script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script src="assets/js/login-menu.js"></script>
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

