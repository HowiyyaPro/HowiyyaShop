<?php
session_start();
require 'dp.php'; // Assuming this file contains your database connection
global $conn;
$flag= isset($_SESSION['add_user']) ? $_SESSION['add_user'] : '';
$registerError = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['user_name'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $confPass = $_POST['conf_password'];
    $fullName = $_POST['full_name'];
    $uerLevel = $_POST['category'];

    $stmt = $conn->prepare("select * from user where user_name = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $registerError="There's already an account with this username.";
    }
    else {

        $stmt = $conn->prepare("select * from user where email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $registerError = "There's already an account with this email.";
        } else if ($password != $confPass) {
            $registerError = "Password must match!!";

        } else {
            // Hash the password
            $hashedPassword = sha1($password);

            // Prepare and bind
            $stmt = $conn->prepare("INSERT INTO user (user_name, password, email, name,user_level) VALUES (?, ?, ?, ?,?)");
            $stmt->bind_param("sssss", $username, $hashedPassword, $email, $fullName, $uerLevel);

            // Execute the query
            if ($stmt->execute()) {
                global $user_name;
                global $flag;
                $flag = 1;
                $user_name = $username;
                $_SESSION['add_user'] = $username;
                header('location: add_user.php');
                exit();
            } else {
                $registerError = "Error: " . $stmt->error;
            }
        }
    }
    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();


?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Products / Add product</title>
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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container {
            margin-top: 50px;
            margin-bottom: 50px;
        }
        .tm-bg-primary-dark {
            background-color: #354458;
            padding: 30px;
            border-radius: 10px;
        }
        .tm-block {
            padding: 20px;
            border-radius: 10px;
        }
        .tm-block-title {
            color: #fff;
            margin-bottom: 20px;
        }
        .tm-edit-product-form label {
            color: #fff;
        }
        .tm-product-img-dummy {
            background-color: #ccc;
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
        }
        .tm-product-img-dummy i {
            font-size: 50px;
            color: #888;
        }
        .tm-product-img-dummy:hover {
            cursor: pointer;
        }

    </style>

</head>

<body>

<!-- ======= Header ======= -->
<?php
include 'adminHeader.php'
?>
<!-- End Header -->

<!-- ======= Sidebar ======= -->
<?php
include 'adminSideBar.php'
?>
<!-- End Sidebar-->

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Add New User</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item"> <a href="users.php">Users</a></li>
                <li class="breadcrumb-item">Add New User</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <div class="container tm-mt-big tm-mb-big">
        <div class="row">
            <div class="col-xl-9 col-lg-10 col-md-12 col-sm-12 mx-auto">
                <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
                    <div class="row">
                        <div class="col-12">
                            <h2 class="tm-block-title d-inline-block">Add New User</h2>
                            <?php
                            if($flag!='' )
                            {
                                echo '<p style="color:darkseagreen;"> ADD SUCCESSFULLY </p>';
                                unset($_SESSION['add_user']); // Unset the session after displaying the message
                            }
                            ?>
                        </div>
                    </div>
                    <div class="row tm-edit-product-row">
                        <div class="col-xl-6 col-lg-6 col-md-12">
                            <form action="add_user.php" method="POST" class="tm-edit-product-form" enctype="multipart/form-data">
                                <div class="form-group mb-3">
                                    <label for="full_name">Full Name</label>
                                    <input id="full_name" name="full_name" type="text" class="form-control validate" required />
                                </div>
                                <div class="form-group mb-3">
                                    <label for="user_name">User Name</label>
                                    <input id="user_name" name="user_name" type="text" class="form-control validate" required />
                                </div>
                                <div class="form-group mb-3">
                                    <label for="email">Email</label>
                                    <input id="email" name="email" type="email" class="form-control validate" required />
                                </div>

                                <div class="form-group mb-3">
                                    <label for="category">User Level</label>
                                    <select name="category" class="custom-select tm-select-accounts" id="category">
                                        <option selected>Select Level</option>
                                        <option value="u">client</option>
                                        <option value="a">admin</option>
                                    </select>
                                </div>

                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 mx-auto mb-4">
                            <div class="form-group mb-3">
                                <label style="color: white" for="password">Password</label>
                                <input id="password" name="password" type="password" class="form-control validate" required />
                            </div>
                            <div class="form-group mb-3">
                                <label style="color: white" for="conf_password">Confirm Password</label>
                                <input id="conf_password" name="conf_password" type="password" class="form-control validate" required />
                            </div>

                        </div>

                        <div class="col-12">
                            <p style="color: red"><?php  echo $registerError?></p>
                            <button type="submit" class="btn btn-primary btn-block text-uppercase">Add New User Now</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>


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
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>



<!-- Template Main JS File -->
<script src="assets/js/main.js"></script>

</body>

</html>