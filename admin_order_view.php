<?php
require 'dp.php'; // Include your database connection file
global $conn;

$order_id = $_GET['order_id'];

// Fetching order details
$order_query = "SELECT o.order_id, u.user_name, o.total_amount, o.order_date, o.paymnet_method, od.status
                FROM `order` o
                JOIN user u ON o.user_name = u.user_name
                JOIN order_deatails od ON o.order_id = od.order_id
                WHERE o.order_id = $order_id";
$order_result = $conn->query($order_query);
$order = $order_result->fetch_assoc();

// Fetching products in the order
$product_query = "SELECT p.product_name, od.price, od.quantity, i.image_url
                  FROM product p
                  JOIN order_deatails od ON p.product_id = od.product_id
                  LEFT JOIN images_url i ON p.product_id = i.product_id
                  WHERE od.order_id = $order_id";
$product_result = $conn->query($product_query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">
    <link href="assets/css/admin-order.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <!-- Template Main CSS File -->
    <link href="assets/css/Admin.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .tm-block-products { background-color: #2c3e50; padding: 20px; border-radius: 10px; color: #fff; }
        .tm-product-table th, .tm-product-table td { color: black; }
        .tm-product-delete-link { color: #ff6b6b; }
        .tm-product-edit-link { color:cornflowerblue; }
        .product-img {
            width: 50px;
            height: auto;
        }
        .tm-product-img-container {
            vertical-align: middle;
            text-align: center;
        }

        .product-img {
            max-width: 100px; /* Adjust the width as needed */
            max-height: 100px; /* Adjust the height as needed */
            display: inline-block;
        }

    </style>

</head>

<body>

<!-- ======= Header ======= -->
<?php
include 'adminHeader.php';
?>
<!-- End Header -->

<!-- ======= Sidebar ======= -->
<?php
include 'adminSidebar.php';
?>
<!-- End Sidebar-->

<main id="main" class="main">

    <div class="pagetitle">
        <h1>Order History</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="admin_order.php">Orders</a></li>
                <li class="breadcrumb-item">Order View</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <div class="container mt-5">
        <div class="table-wrapper">
            <table class="order-table">
                <thead>
                <tr>
                    <th>Product details</th>
                    <th>Item Price</th>
                    <th>Quantity</th>
                    <th>Total Amount</th>
                </tr>
                </thead>
                <tbody>
                <?php while($product = $product_result->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <img src="<?php echo $product['image_url']; ?>" alt="<?php echo $product['product_name']; ?>" class="product-img">
                            <strong><?php echo $product['product_name']; ?></strong><br>
                        </td>
                        <td>$<?php echo number_format($product['price'], 2); ?></td>
                        <td><?php echo $product['quantity']; ?></td>
                        <td>$<?php echo number_format($product['price'] * $product['quantity'], 2); ?></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>



</main><!-- End #main -->

<!-- ======= Footer ======= -->
<?php
include 'adminFooter.php';
?>
<!-- End Footer -->

<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<!-- Vendor JS Files -->
<script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/chart.js/chart.umd.js"></script>
<script src="assets/vendor/echarts/echarts.min.js"></script>
<script src="assets/vendor/quill/quill.js"></script>
<script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
<script src="assets/vendor/tinymce/tinymce.min.js"></script>
<script src="assets/vendor/php-email-form/validate.js"></script>

<!-- Template Main JS File -->
<script src="assets/js/main.js"></script>

</body>

</html>
