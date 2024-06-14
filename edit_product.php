<?php
session_start();
require 'dp.php';
global $conn;
$flag= isset($_SESSION['edit_product']) ? $_SESSION['edit_product'] : '';
// Check if $_GET['id'] is set and not empty
if(isset($_GET['id']) && !empty($_GET['id'])) {
    // Escape the $_GET value to prevent SQL injection
    $id = $conn->real_escape_string($_GET['id']);

    // Construct the SQL query
    $sql = "SELECT * FROM product
             INNER JOIN images_url i ON i.product_id = product.product_id
             WHERE product.product_id = '$id'";

    // Execute the query
    $result = $conn->query($sql);


    // Check if the query executed successfully
    if($result) {
        // Fetch the result row
        $row = $result->fetch_assoc();
        $image_id=$row['image_id'];
        // Proceed with further processing
    } else {
        // Query failed, handle the error
        echo "Error: " . $conn->error;
    }
} else {
    // Handle the case when $_GET['id'] is not set or empty
    echo "Product ID is not set or empty.";
}
$conn->close();
?>
<?php
require 'dp.php'; // Assuming this file contains your database connection
global $conn;


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = $_POST['product_name'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $product_id=$_GET['id'];
if(isset($_FILES["product_image"]["tmp_name"]) && !empty($_FILES["product_image"]["tmp_name"])) {

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
            $stmt = $conn->prepare("UPDATE product SET product_name=?, product_type=?, description=?, price=?, stock=? WHERE product_id=?");
            $stmt->bind_param("sssdii", $product_name, $category, $description, $price, $stock, $product_id);

            // Execute the statement
            if ($stmt->execute()) {
                global $image_id;

                $stmt_image = $conn->prepare("update images_url set image_url=? where image_id= ?");
                $stmt_image->bind_param("si", $product_image, $image_id);
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
            $_SESSION['edit_product']=true;
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        echo "File is not an image.";
    }
}
else
{
    // If no new image is uploaded, only update the product details without changing the image URL
    $stmt = $conn->prepare("UPDATE product SET product_name=?, product_type=?, description=?, price=?, stock=? WHERE product_id=?");
    $stmt->bind_param("sssdii", $product_name, $category, $description, $price, $stock, $product_id);
    $stmt->execute();
    $stmt->close();
    $_SESSION['edit_product']=true;
}
    header('Location: edit_product.php?id=' . $product_id);
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
<!-- End Header -->

<!-- ======= Sidebar ======= -->
<?php
include 'adminSideBar.php';
?>
<!-- End Sidebar-->

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Edit Product</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="admin_product.php">Products</a></li>
                <li class="breadcrumb-item">Edit Product</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <div class="container tm-mt-big tm-mb-big">
        <div class="row">
            <div class="col-xl-9 col-lg-10 col-md-12 col-sm-12 mx-auto">
                <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
                    <div class="row">
                        <div class="col-12">
                            <h2 class="tm-block-title d-inline-block">Edit Product</h2>
                            <?php
                            if($flag!='' )
                            {
                                echo '<p style="color:red;"> EDIT SUCCESSFULLY </p>';
                                unset($_SESSION['edit_product']); // Unset the session after displaying the message
                            }
                            ?>

                        </div>
                    </div>
                    <div class="row tm-edit-product-row">
                        <div class="col-xl-6 col-lg-6 col-md-12">

                            <form action="edit_product.php?id=<?php echo $row['product_id']; ?>" method="POST" class="tm-edit-product-form" enctype="multipart/form-data">
                                <div class="form-group mb-3">
                                    <label for="product_name">Product Name</label>
                                    <input id="product_name" name="product_name" type="text" class="form-control validate" required value="<?=$row['product_name'] ?>"/>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="description">Description</label>
                                    <textarea id="description" name="description" class="form-control validate" rows="3" required><?=$row['description'] ?></textarea>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="category">Category</label>
                                    <select name="category" class="custom-select tm-select-accounts" id="category">
                                        <option selected><?=$row['product_type'] ?></option>
                                        <option value="clothes">clothes</option>
                                        <option value="bags">bags</option>
                                        <option value="accessories">accessories</option>
                                        <option value="home_decor">home_decor</option>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="form-group mb-3 col-xs-12 col-sm-6">
                                        <label for="price">Price</label>
                                        <input id="price" name="price" type="number" min="0" class="form-control validate" data-large-mode="true" required value="<?=$row['price'] ?>" />
                                    </div>
                                    <div class="form-group mb-3 col-xs-12 col-sm-6">
                                        <label for="stock">Units In Stock</label>
                                        <input id="stock" name="stock" type="number" class="form-control validate" required value="<?=$row['stock'] ?>" />
                                    </div>
                                </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 mx-auto mb-4">
                            <div id="imageUploadBox" class="tm-product-img-dummy mx-auto" onclick="document.getElementById('fileInput').click();">
                                <?php
                                // Check if there is a default image URL available
                                if(!empty($row['image_url'])) {
                                    echo "<img  src='" . htmlspecialchars($row['image_url']) . "' alt='Product Image' class='product-img' style='max-width: 100%; max-height: 100%;'>";
                                } else {
                                    // If no default image URL is available, display a placeholder icon
                                    echo "<i class='fas fa-cloud-upload-alt tm-upload-icon'></i>";
                                }
                                ?>
                            </div>
                            <div class="custom-file mt-3 mb-3">
                                <input id="fileInput" name="product_image" type="file" style="display:none;"  onchange="displayImage(event)"  />
                                <input type="button" class="btn btn-primary btn-block mx-auto" value="UPLOAD PRODUCT IMAGE" onclick="document.getElementById('fileInput').click();"/>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 mx-auto mb-4">
                            <!-- Image Preview -->
                            <div id="imagePreview" class="mt-3" style="text-align: center;"></div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block text-uppercase">Edit Product Now</button>
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
