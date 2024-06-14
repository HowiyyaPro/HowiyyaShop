<?php
session_start();
require 'dp.php'; // Assuming this file contains your database connection
global $conn;
$flag= isset($_SESSION['add_product']) ? $_SESSION['add_product'] : '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = $_POST['product_name'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    // Handle the file upload
    $target_dir = "assets/images/";
    $target_file = $target_dir . basename($_FILES["product_image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is an actual image or fake image
    $check = getimagesize($_FILES["product_image"]["tmp_name"]);
    if ($check !== false) {
        if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
            $product_image = $target_file;

            // Prepare and bind
            $stmt = $conn->prepare("INSERT INTO product (product_name, product_type, description, price, stock) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssd", $product_name, $category, $description, $price, $stock);

            // Execute the statement
            if ($stmt->execute()) {
                $product_id = $stmt->insert_id;

                // Insert image URL into images_url table
                $stmt_image = $conn->prepare("INSERT INTO images_url (product_id, image_url) VALUES (?, ?)");
                $stmt_image->bind_param("is", $product_id, $product_image);
                if ($stmt_image->execute()) {
                    echo "New product and image added successfully";
                } else {
                    echo "Error inserting image URL: " . $stmt_image->error;
                }
                $stmt_image->close();
            } else {
                echo "Error inserting product details: " . $stmt->error;
            }

            $stmt->close();
            $_SESSION['add_product']=true;
            $conn->close();
            header('Location: add-product.php');

        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        echo "File is not an image.";
    }
}
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
include 'adminHeader.php';
?>

<!-- ======= Sidebar ======= -->
<?php
include 'adminSideBar.php';
?>
<!-- End Sidebar-->

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Add Products</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item"> <a href="admin_product.php">Products</a></li>
                <li class="breadcrumb-item">Add Products</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <div class="container tm-mt-big tm-mb-big">
        <div class="row">
            <div class="col-xl-9 col-lg-10 col-md-12 col-sm-12 mx-auto">
                <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
                    <div class="row">
                        <div class="col-12">
                            <h2 class="tm-block-title d-inline-block">Add Product</h2>
                            <?php
                            if($flag!='' )
                            {
                                echo '<p style="color:red;"> ADD SUCCESSFULLY </p>';
                                unset($_SESSION['add_product']); // Unset the session after displaying the message
                            }
                            ?>
                        </div>
                    </div>
                    <div class="row tm-edit-product-row">
                        <div class="col-xl-6 col-lg-6 col-md-12">
                            <form action="add-product.php" method="POST" class="tm-edit-product-form" enctype="multipart/form-data">
                                <div class="form-group mb-3">
                                    <label for="product_name">Product Name</label>
                                    <input id="product_name" name="product_name" type="text" class="form-control validate" required />
                                </div>
                                <div class="form-group mb-3">
                                    <label for="description">Description</label>
                                    <textarea id="description" name="description" class="form-control validate" rows="3" required></textarea>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="category">Category</label>
                                    <select name="category" class="custom-select tm-select-accounts" id="category">
                                        <option selected>Select category</option>
                                        <option value="clothes">clothes</option>
                                        <option value="bags">bags</option>
                                        <option value="accessories">accessories</option>
                                        <option value="home_decor">home_decor</option>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="form-group mb-3 col-xs-12 col-sm-6">
                                        <label for="price">Price</label>
                                        <input id="price" name="price" type="number" class="form-control validate" data-large-mode="true" required />
                                    </div>
                                    <div class="form-group mb-3 col-xs-12 col-sm-6">
                                        <label for="stock">Units In Stock</label>
                                        <input id="stock" name="stock" type="number" class="form-control validate" required />
                                    </div>
                                </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 mx-auto mb-4">
                            <div id="imageUploadBox" class="tm-product-img-dummy mx-auto" onclick="document.getElementById('fileInput').click();">
                                <i class="fas fa-cloud-upload-alt tm-upload-icon"></i>
                            </div>
                            <div class="custom-file mt-3 mb-3">
                                <input id="fileInput" name="product_image" type="file" style="display:none;" required onchange="displayImage(event);" />
                                <input type="button" class="btn btn-primary btn-block mx-auto" value="UPLOAD PRODUCT IMAGE" onclick="document.getElementById('fileInput').click();" />
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 mx-auto mb-4">
                            <!-- Image Preview -->
                            <div id="imagePreview" class="mt-3" style="text-align: center;"></div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block text-uppercase">Add Product Now</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
    #imageUploadBox {
        background-size: cover;
        background-position: center;
        width: 100%;
        height: 300px; /* Set the height of the box */
        border: 2px dashed #ccc; /* Optional: Add a border */
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
<script>
    function displayImage(event) {
        var file = event.target.files[0];
        var reader = new FileReader();

        reader.onload = function(e) {
            var imageUrl = e.target.result;
            var imageUploadBox = document.getElementById('imageUploadBox');
            imageUploadBox.style.backgroundImage = 'url(' + imageUrl + ')';
            imageUploadBox.innerHTML = ''; // Remove the upload icon
        }

        reader.readAsDataURL(file);
    }
</script>
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