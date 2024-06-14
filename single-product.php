<?php
session_start();
require "dp.php";
global $conn;

if (isset($_GET['product_id'])) {
    $product_id = intval($_GET['product_id']);

    // Fetch product details based on the product ID
    $stmt = $conn->prepare("SELECT p.product_id, p.product_name, p.description, p.price, p.stock, i.image_url
FROM product p
LEFT JOIN images_url i ON p.product_id = i.product_id
WHERE p.product_id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
$product = $result->fetch_assoc();
} else {
echo '<p>Product not found.</p>';
exit;
}

$stmt->close();
$conn->close();
} else {
echo '<p>No product ID specified.</p>';
exit;
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

    <title><?php echo htmlspecialchars($product['product_name']); ?></title>


    <!-- Additional CSS Files -->
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">

    <link rel="stylesheet" type="text/css" href="assets/css/font-awesome.css">

    <link rel="stylesheet" href="assets/css/HowiyyaShop.css">

    <link rel="stylesheet" href="assets/css/owl-carousel.css">

    <link rel="stylesheet" href="assets/css/lightbox.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <link rel="stylesheet" href="assets/css/custom.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!--

    -->

    <script>
        function total()
        {
            var price = parseFloat(document.getElementById('single_product_price').innerText);
            // Get the quantity value
            var quantity = parseInt(document.getElementById('quantity').value);
            // Calculate the total price
            var total = price * quantity;
            // Display the total price
            document.getElementById('total').innerText = "Total: " + total.toFixed(2) + " NIS";
        }

        function decreaseQuantity() {
            var quantityInput = document.getElementById('quantity');
            var currentValue = parseInt(quantityInput.value);
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
                total();
            }
        }

        function increaseQuantity() {
            var quantityInput = document.getElementById('quantity');
            var currentValue = parseInt(quantityInput.value);
            quantityInput.value = currentValue + 1;
            total();
        }
    </script>
</head>
    <body>
    <?php
    include 'header.php';
    ?>
    <!-- ***** Header Area End ***** -->

    <!-- ***** Main Banner Area Start ***** -->
    <div class="page-heading" id="top">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="inner-content">
                        <h2>Single Product Page</h2>
                        <span>Awesome &amp; Creative HTML CSS layout by TemplateMo</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ***** Main Banner Area End ***** -->


    <!-- ***** Product Area Starts ***** -->
    <section class="section" id="product">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                <div class="left-images">

                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>" style="width: 80%">
                </div>

            </div>
            <div class="col-lg-4">
                <div class="right-content">
                    <h4 name="single_product_name"><?php echo htmlspecialchars($product['product_name']); ?></h4>
                    <span class="price">Price: <span style="display: inline;" class="price" name="single_product_price" id="single_product_price"><?php echo number_format($product['price'], 2); ?></span> NIS</span>
                    <ul class="stars">
                        <li><i class="fa fa-star"></i></li>
                        <li><i class="fa fa-star"></i></li>
                        <li><i class="fa fa-star"></i></li>
                        <li><i class="fa fa-star"></i></li>
                        <li><i class="fa fa-star"></i></li>
                    </ul>
                    <span name="single_product_description"><?php echo htmlspecialchars($product['description']); ?></span>
<!--                    <div class="quote">-->
<!--                        <i class="fa fa-quote-left"></i><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiuski smod.</p>-->
<!--                    </div>-->
                    <div class="quantity-content">
                        <div class="left-content">
                            <h6>No. of Orders</h6>
                        </div>
                        <div class="right-content" >
                            <div class="quantity buttons_added">
                                <input type="button" value="-" class="minus" onclick="decreaseQuantity()">
                                <input onchange="total()" type="number" step="1" min="1" name="quantity" value="1" title="Qty" class="input-text qty text" size="4" id="quantity">
                                <input type="button" value="+" class="plus" onclick="increaseQuantity()">
                            </div>
                        </div>
                    </div>
                    <div class="total">
                        <h4 id="total"></h4>
                        <form action="cart.php" method="post">
                            <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                            <input type="hidden" name="quantity" id="quantity_hidden" value="1">
                            <div class="main-border-button">
                              <a>
                            <button  style="background-color: transparent; width: max-content"  type="submit" onclick="document.getElementById('quantity_hidden').value = document.getElementById('quantity').value">Add to Cart</button>

                              </a>
                            </div>
                        </form>
<!--                        <div class="main-border-button"><a href="#">Add To Cart</a></div>-->
                    </div>
                </div>
            </div>
            </div>
        </div>
    </section>
    <!-- ***** Product Area Ends ***** -->
    
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
