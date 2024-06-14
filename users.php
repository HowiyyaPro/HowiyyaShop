<?php
session_start();
include 'dp.php';
global $conn;


// Fetch products from the database

$sql = "SELECT 
            COUNT(o.order_id) as orders_count,
            u.user_name,
            u.email,
            u.name,
            u.created_at,
            u.user_level
        FROM user u
        LEFT JOIN  `order` o ON o.user_name = u.user_name
        GROUP BY u.user_name, u.email, u.name, u.created_at, u.user_level
        ORDER BY orders_count DESC";

$result = $conn->query($sql);

// Check for query errors
if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Products</title>
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
include 'adminSideBar.php';
?>
<!-- End Sidebar-->

<main id="main" class="main">

    <div class="pagetitle">
        <h1>Users</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item">Users</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <div class="container mt-5">
        <div class="row tm-content-row">
            <div class="col-xl-9 col-lg-10 col-md-12 col-sm-12 mx-auto">
                <div class="tm-bg-primary-dark tm-block tm-block-products">
                    <div class="tm-product-table-container">
                        <form method="POST" action="users.php">
                            <div class="table-responsive">
                                <table class="table table-hover tm-table-small tm-product-table">
                                    <thead>
                                    <tr style="text-align: center">
                                        <th scope="col">User Name</th>
                                        <th scope="col">Full Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Level</th>
                                        <th scope="col">Creation Date</th>
                                        <th scope="col">#Orders</th>
                                        <th scope="col">Delete</th>
                                    </tr>
                                    </thead>
                                    <tbody style="text-align: center;">
                                    <?php
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td class='tm-product-name'>" . htmlspecialchars($row['user_name']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['user_level']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['orders_count']) . "</td>";
                                            echo "<td><a href='delete_user.php?id=" . $row['user_name'] . "' class='tm-product-delete-link'><i class='far fa-trash-alt tm-product-delete-icon'></i></a></td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='8'>No users found.</td></tr>";
                                    }
                                    ?>
                                    </tbody>

                                </table>
                            </div>
                        </form>
                    </div>
                    <a style="margin-top: 10px" href="add_user.php" class="btn btn-primary btn-block text-uppercase mb-3">Add new User</a>
                </div>
            </div>
        </div>
    </div>
    <?php
    $conn->close();
    ?>

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