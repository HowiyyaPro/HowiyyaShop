<?php
// Start the session
session_start();
require 'dp.php';
global $conn;
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch orders and related details
$sql = "SELECT 
            o.order_id,
            o.user_name AS customer,
            GROUP_CONCAT(p.product_name SEPARATOR ', ') AS products,
            o.order_date,
            o.total_amount AS amount,
            o.paymnet_method,
            od.status
        FROM `order` o
        JOIN `order_deatails` od ON o.order_id = od.order_id
        JOIN `product` p ON od.product_id = p.product_id
        GROUP BY o.order_id";

$result = $conn->query($sql);

$conn->close();
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
                <li class="breadcrumb-item">Orders</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <div class="container mt-5">
        <div class="table-wrapper">
            <table class="order-table">
                <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Product</th>
                    <th>Order Date</th>
                    <th>Amount</th>
                    <th>Payment Method</th>
                    <th>Delivery Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>#<?php echo htmlspecialchars($row['order_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['customer']); ?></td>
                            <td><?php echo htmlspecialchars($row['products']); ?></td>
                            <td><?php echo htmlspecialchars(date("d M, Y", strtotime($row['order_date']))); ?></td>
                            <td>$<?php echo htmlspecialchars(number_format($row['amount'], 2)); ?></td>
                            <td><?php echo htmlspecialchars($row['paymnet_method']); ?></td>
                            <td><span style="color: black" class="badge <?php echo strtolower(htmlspecialchars($row['status'])); ?>"><?php echo htmlspecialchars($row['status']); ?></span></td>
                            <td>
                                <a href="admin_order_view.php?order_id=<?php echo $row['order_id']; ?>" class="view-btn"><i class="bi bi-eye"></i> </a>
                                <button class="edit-btn" data-order-id="<?php echo $row['order_id']; ?>" data-status="<?php echo $row['status']; ?>"><i class="bi bi-pencil-square"></i></button>
                                <form action="admin_order_delete.php" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this order?');">
                                    <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                                    <button type="submit" class="delete-btn"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">No orders found</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="editStatusModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Edit Order Status</h2>
            <form id="editStatusForm" method="POST" action="admin_order_edit.php">
                <input type="hidden" name="order_id" id="order_id">
                <label for="status">Status:</label>
                <select name="status" id="status" class="form-select">
                    <option value="pending">Pending</option>
                    <option value="inprogress">In Progress</option>
                    <option value="delivered">Delivered</option>
                    <option value="cancelled">Cancelled</option>
                    <option value="returns">Returns</option>
                    <option value="pickups">Pickups</option>
                </select>
                <button type="submit" class="btn-save">Save</button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var modal = document.getElementById("editStatusModal");
            var closeBtn = document.querySelector(".close");
            var editButtons = document.querySelectorAll(".edit-btn");

            editButtons.forEach(function(button) {
                button.addEventListener("click", function() {
                    var orderId = this.getAttribute("data-order-id");
                    var status = this.getAttribute("data-status");
                    document.getElementById("order_id").value = orderId;
                    document.getElementById("status").value = status;
                    modal.style.display = "block";
                });
            });

            closeBtn.addEventListener("click", function() {
                modal.style.display = "none";
            });

            window.addEventListener("click", function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            });
        });
    </script>


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
