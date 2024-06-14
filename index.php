<?php
require 'dp.php'; // Include your database connection file
global $conn;
// Fetch the latest products based on updated_at column
$stmt = $conn->prepare("SELECT p.product_id, p.product_name, p.price, i.image_url 
                        FROM product p
                        LEFT JOIN images_url i ON p.product_id = i.product_id
                        ORDER BY p.updated_at DESC
                        LIMIT 4"); // Adjust the limit as needed
$stmt->execute();
$result = $stmt->get_result();
$latestProducts = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>



    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">

    <title>HOWIYYA</title>


    <!-- Additional CSS Files -->
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">

    <link rel="stylesheet" type="text/css" href="assets/css/font-awesome.css">

    <link rel="stylesheet" href="assets/css/HowiyyaShop.css">

    <link rel="stylesheet" href="assets/css/owl-carousel.css">

    <link rel="stylesheet" href="assets/css/lightbox.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <link rel="stylesheet" href="assets/css/custom.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

</head>

<body>

<?php
include 'header.php';
?>

<!-- ***** Main Banner Area Start ***** -->
<div class="main-banner" id="top">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6">
                <div class="left-content">
                    <div class="thumb">
                        <div class="image-content">
                            <div class="inner-content" style=" text-align: center;
  padding: 20px;
  max-width: 60%; /* Limit the maximum width of the text box */
  max-height: 60%; /* Limit the maximum height of the text box */
  overflow: auto; /* Add scrollbar if content overflows */">

                                <h4  style="position: relative ;">HOWIYYA SHOP</h4>
                                <span style="position: relative ;">Where each piece empowers artisans and tells a timeless STORY.</span>

                                <div class="main-border-button">
                                    <a href="about.php" style="position: relative ;">ABOUT US!</a>
                                </div>
                            </div>

                        </div>
                        <img src="assets/images/m.jpeg " s class="img-fluid" style="background-size: cover; background-position: center; background-repeat: no-repeat;" alt="" >
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="right-content">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="right-first-image">
                                <div class="thumb">
                                    <div class="inner-content">
                                        <h4>Clothes</h4>
                                        <span> Embroidered Elegance</span>
                                    </div>
                                    <div class="hover-content">
                                        <div class="inner">
                                            <h4>Clothes</h4>
                                            <p>Journey Through Our Enchanted World of Tatreez Apparel.</p>
                                            <div class="main-border-button">
                                                <a href="Clothes.html">Discover More</a>
                                            </div>
                                        </div>
                                    </div>
                                    <img src="assets/images/w1.jpeg">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="right-first-image">
                                <div class="thumb">
                                    <div class="inner-content">
                                        <h4>Accessories</h4>
                                        <span>Unveil Your Perfect Accessory</span>
                                    </div>
                                    <div class="hover-content">
                                        <div class="inner">
                                            <h4>Accessories</h4>
                                            <p>Shop our collection of Tatreez embroidered, ethically made Palestine accessories.</p>
                                            <div class="main-border-button">
                                                <a href="Accessories.html">Discover More</a>
                                            </div>
                                        </div>
                                    </div>
                                    <img src="assets/images/a5.jpg">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="right-first-image">
                                <div class="thumb">
                                    <div class="inner-content">
                                        <h4>Home Decor</h4>
                                        <span>Hand-Embroidered  </span>
                                    </div>
                                    <div class="hover-content">
                                        <div class="inner">
                                            <h4>Home Decor</h4>
                                            <p>Our home decor items are embroidered with tatreez, an ancient Palestinian art form.</p>
                                            <div class="main-border-button">
                                                <a href="Decor.php">Discover More</a>
                                            </div>
                                        </div>
                                    </div>
                                    <img src="assets/images/h5.jpg">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="right-first-image">
                                <div class="thumb">
                                    <div class="inner-content">
                                        <h4>Bags</h4>
                                        <span>Carry Tradition in Style</span>
                                    </div>
                                    <div class="hover-content">
                                        <div class="inner">
                                            <h4>Bags</h4>
                                            <p>Each one of our bags features a unique tatreez design and is handmade by women artisans in Palestine.</p>
                                            <div class="main-border-button">
                                                <a href="Bags.html">Discover More</a>
                                            </div>
                                        </div>
                                    </div>
                                    <img src="assets/images/d22.png">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ***** Main Banner Area End ***** -->

<section class="section" style="  padding-top: 90px; padding-bottom: 90px; border-bottom: 3px dotted #eee;">
    <div class="container">

    <span>
        <h1 style="font-size: 25px; font-weight: 500;  color: #2a2a2a; position: relative; text-align: center" > Bringing You The Best</h1>
        <br>
        <h2 style="font-size: 34px; font-weight: 700;  color: #2a2a2a; position: relative; text-align: center">
            SHOP HANDMADE, FAIRTRADE GIFTS FROM PALESTINE
        </h2>
        <br> <br>
        <h3 style="font-size: 14px;  color: #aaaaaa;  font-weight: 500;display: block; margin-top: 25px; position: relative; text-align: center">
            Buy ethical, unique gifts from Palestinian artisans, shipped worldwide, and make a difference by empowering women artisans in Palestine today
        </h3>
    </span>

    </div>
</section>

<!-- ***** Bestsellers Area Starts ***** -->
<section class="section" id="bestsellers">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="section-heading">
                    <h2>Our Bestsellers</h2>
                    <span>Explore the artistry of tatreez with our handpicked collection of top-rated pieces.</span>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="women-item-carousel">
                    <div class="owl-women-item owl-carousel">
                        <?php
                        require "dp.php";
                        global $conn;

                        // First query: Get top 5 best-selling product IDs
                        $query1 = "
    SELECT p.product_id, SUM(oi.quantity) as total_sold
    FROM product p
    JOIN order_deatails oi ON p.product_id = oi.product_id
    GROUP BY p.product_id
    ORDER BY total_sold DESC
    LIMIT 5";

                        $stmt1 = $conn->prepare($query1);

                        if ($stmt1) {
                            $stmt1->execute();
                            $result1 = $stmt1->get_result();

                            // Collect the product IDs
                            $productIds = [];
                            while ($row = $result1->fetch_assoc()) {
                                $productIds[] = $row['product_id'];
                            }
                            $stmt1->close();

                            if (!empty($productIds)) {
                                // Second query: Get full product details for the top-selling products
                                $placeholders = implode(',', array_fill(0, count($productIds), '?'));
                                $query2 = "SELECT p.product_id, p.product_name, p.description, p.price, p.stock, i.image_url  
                   FROM product p 
                   LEFT JOIN images_url i ON p.product_id = i.product_id 
                   WHERE p.product_id IN ($placeholders)";

                                $stmt2 = $conn->prepare($query2);
                                if ($stmt2) {
                                    // Bind the product IDs to the placeholders
                                    $stmt2->bind_param(str_repeat('i', count($productIds)), ...$productIds);
                                    $stmt2->execute();
                                    $result2 = $stmt2->get_result();

                                    if ($result2->num_rows > 0) {
                                        while ($row = $result2->fetch_assoc()) {
                                            echo '
                    <div class="item">
                        <div class="thumb">
                            <div class="hover-content">
                                <ul>
                                <li><a href="single-product.php?product_id=' . $row['product_id'] . '"><i class="fa fa-eye"></i></a></li>
                                <li>
                                    <form action="add_to_list.php" method="post" style="display:inline;">
                                        <input type="hidden" name="product_id" value="' . $row['product_id'] . '">
                                        <input type="hidden" name="quantity" value="1">
                                        <a>
                                        <button type="submit" style="background:none; border:none; padding:0;">
                                            <i class="fa fa-heart" style="color:black;"></i>
                                        </button>
                                        </a>
                                    </form>
                               </li> 
                               <li>
                                    <form action="cart.php" method="post" style="display:inline;">
                                        <input type="hidden" name="product_id" value="' . $row['product_id'] . '">
                                        <input type="hidden" name="quantity" value="1">
                                        <a>
                                        <button type="submit" style="background:none; border:none; padding:0;">
                                            <i class="fa fa-shopping-cart" style="color:black;"></i>
                                        </button>
                                        </a>
                                    </form>
                                </li>
                           
                            </ul>
                            </div>
                            <img src="' . htmlspecialchars($row['image_url']) . '" alt="' . htmlspecialchars($row['product_name']) . '">
                        </div>
                        <div class="down-content">
                            <h4 name="productName">' . htmlspecialchars($row['product_name']) . '</h4>
                            <span name="productPrice">' . number_format($row['price'], 2) . ' NIS</span>
                            <ul class="stars">
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                            </ul>
                        </div>
                    </div>';
                                        }
                                    } else {
                                        echo '<p>No best-selling products found.</p>';
                                    }
                                    $stmt2->close();
                                } else {
                                    echo "Failed to prepare the second query: " . $conn->error;
                                }
                            } else {
                                echo '<p>No best-selling products found.</p>';
                            }
                        } else {
                            echo "Failed to prepare the first query: " . $conn->error;
                        }
                        ?>





                        <!--                        <div class="item">-->
<!--                            <div class="thumb">-->
<!--                                <div class="hover-content">-->
<!--                                    <ul>-->
<!--                                        <li><a href="single-product.php"><i class="fa fa-eye"></i></a></li>-->
<!--                                        <li><a href="single-product.php"><i class="fa fa-star"></i></a></li>-->
<!--                                        <li><a href="single-product.php"><i class="fa fa-shopping-cart"></i></a></li>-->
<!--                                    </ul>-->
<!--                                </div>-->
<!--                                <img src="assets/images/b1full.PNG" alt="">-->
<!--                            </div>-->
<!--                            <div class="down-content">-->
<!--                                <h4>Palestine Tote Bag</h4>-->
<!--                                <span>250.00 NIS</span>-->
<!--                                <ul class="stars">-->
<!--                                    <li><i class="fa fa-star"></i></li>-->
<!--                                    <li><i class="fa fa-star"></i></li>-->
<!--                                    <li><i class="fa fa-star"></i></li>-->
<!--                                    <li><i class="fa fa-star"></i></li>-->
<!--                                    <li><i class="fa fa-star"></i></li>-->
<!--                                </ul>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                        <div class="item">-->
<!--                            <div class="thumb">-->
<!--                                <div class="hover-content">-->
<!--                                    <ul>-->
<!--                                        <li><a href="single-product.php"><i class="fa fa-eye"></i></a></li>-->
<!--                                        <li><a href="single-product.php"><i class="fa fa-star"></i></a></li>-->
<!--                                        <li><a href="single-product.php"><i class="fa fa-shopping-cart"></i></a></li>-->
<!--                                    </ul>-->
<!--                                </div>-->
<!--                                <img src="assets/images/women-02.jpg" alt="">-->
<!--                            </div>-->
<!--                            <div class="down-content">-->
<!--                                <h4>Teal Silk Thobe</h4>-->
<!--                                <span>500.00 NIS</span>-->
<!--                                <ul class="stars">-->
<!--                                    <li><i class="fa fa-star"></i></li>-->
<!--                                    <li><i class="fa fa-star"></i></li>-->
<!--                                    <li><i class="fa fa-star"></i></li>-->
<!--                                    <li><i class="fa fa-star"></i></li>-->
<!--                                    <li><i class="fa fa-star"></i></li>-->
<!--                                </ul>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                        <div class="item">-->
<!--                            <div class="thumb">-->
<!--                                <div class="hover-content">-->
<!--                                    <ul>-->
<!--                                        <li><a href="single-product.php"><i class="fa fa-eye"></i></a></li>-->
<!--                                        <li><a href="single-product.php"><i class="fa fa-star"></i></a></li>-->
<!--                                        <li><a href="single-product.php"><i class="fa fa-shopping-cart"></i></a></li>-->
<!--                                    </ul>-->
<!--                                </div>-->
<!--                                <img src="assets/images/h3.PNG" alt="">-->
<!--                            </div>-->
<!--                            <div class="down-content">-->
<!--                                <h4>Farah Half Apron</h4>-->
<!--                                <span>340.00 NIS</span>-->
<!--                                <ul class="stars">-->
<!--                                    <li><i class="fa fa-star"></i></li>-->
<!--                                    <li><i class="fa fa-star"></i></li>-->
<!--                                    <li><i class="fa fa-star"></i></li>-->
<!--                                    <li><i class="fa fa-star"></i></li>-->
<!--                                    <li><i class="fa fa-star"></i></li>-->
<!--                                </ul>-->
<!--                            </div>-->
<!--                        </div>-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ***** Bestsellers Area Ends ***** -->

<!-- *** Latest Products Area Starts *** -->
<section class="section" id="Latest">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="section-heading">
                    <h2> Latest Products</h2>
                    <span>Explore Our Newest Treasures.</span>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="men-item-carousel">
                    <div class="owl-men-item owl-carousel">
                        <?php foreach ($latestProducts as $product) : ?>
                            <div class="item">
                                <div class="thumb">
                                    <div class="hover-content">
                                        <ul>
                                            <li><a href="single-product.php?product_id=<?= $product['product_id']; ?>"><i class="fa fa-eye"></i></a></li>
                                            <li><a href="single-product.php?product_id=<?= $product['product_id']; ?>"><i class="fa fa-star"></i></a></li>
                                            <li><a href="single-product.php?product_id=<?= $product['product_id']; ?>"><i class="fa fa-shopping-cart"></i></a></li>
                                        </ul>
                                    </div>
                                    <img src="<?= $product['image_url']; ?>" alt="">
                                </div>
                                <div class="down-content">
                                    <h4><?= htmlspecialchars($product['product_name']); ?></h4>
                                    <span><?= number_format($product['price'], 2); ?> NIS</span>
                                    <ul class="stars">
                                        <li><i class="fa fa-star"></i></li>
                                        <li><i class="fa fa-star"></i></li>
                                        <li><i class="fa fa-star"></i></li>
                                        <li><i class="fa fa-star"></i></li>
                                        <li><i class="fa fa-star"></i></li>
                                    </ul>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <!--                        <div class="item">-->
                        <!--                            <div class="thumb">-->
                        <!--                                <div class="hover-content">-->
                        <!--                                    <ul>-->
                        <!--                                        <li><a href="single-product.php"><i class="fa fa-eye"></i></a></li>-->
                        <!--                                        <li><a href="single-product.php"><i class="fa fa-star"></i></a></li>-->
                        <!--                                        <li><a href="single-product.php"><i class="fa fa-shopping-cart"></i></a></li>-->
                        <!--                                    </ul>-->
                        <!--                                </div>-->
                        <!--                                <img src="assets/images/h2.PNG" alt="">-->
                        <!--                            </div>-->
                        <!--                            <div class="down-content">-->
                        <!--                                <h4>Dalia Coasters <br> - Palestinian Red</h4>-->
                        <!--                                <span>40.00 NIS</span>-->
                        <!--                                <ul class="stars">-->
                        <!--                                    <li><i class="fa fa-star"></i></li>-->
                        <!--                                    <li><i class="fa fa-star"></i></li>-->
                        <!--                                    <li><i class="fa fa-star"></i></li>-->
                        <!--                                    <li><i class="fa fa-star"></i></li>-->
                        <!--                                    <li><i class="fa fa-star"></i></li>-->
                        <!--                                </ul>-->
                        <!--                            </div>-->
                        <!--                        </div>-->
                        <!--                        <div class="item">-->
                        <!--                            <div class="thumb">-->
                        <!--                                <div class="hover-content">-->
                        <!--                                    <ul>-->
                        <!--                                        <li><a href="single-product.php"><i class="fa fa-eye"></i></a></li>-->
                        <!--                                        <li><a href="single-product.php"><i class="fa fa-star"></i></a></li>-->
                        <!--                                        <li><a href="single-product.php"><i class="fa fa-shopping-cart"></i></a></li>-->
                        <!--                                    </ul>-->
                        <!--                                </div>-->
                        <!--                                <img src="assets/images/women-03.jpg" alt="">-->
                        <!--                            </div>-->
                        <!--                            <div class="down-content">-->
                        <!--                                <h4>Pink Silk Thobe</h4>-->
                        <!--                                <span>550.00 NIS</span>-->
                        <!--                                <ul class="stars">-->
                        <!--                                    <li><i class="fa fa-star"></i></li>-->
                        <!--                                    <li><i class="fa fa-star"></i></li>-->
                        <!--                                    <li><i class="fa fa-star"></i></li>-->
                        <!--                                    <li><i class="fa fa-star"></i></li>-->
                        <!--                                    <li><i class="fa fa-star"></i></li>-->
                        <!--                                </ul>-->
                        <!--                            </div>-->
                        <!--                        </div>-->
                        <!--                        <div class="item">-->
                        <!--                            <div class="thumb">-->
                        <!--                                <div class="hover-content">-->
                        <!--                                    <ul>-->
                        <!--                                        <li><a href="single-product.php"><i class="fa fa-eye"></i></a></li>-->
                        <!--                                        <li><a href="single-product.php"><i class="fa fa-star"></i></a></li>-->
                        <!--                                        <li><a href="single-product.php"><i class="fa fa-shopping-cart"></i></a></li>-->
                        <!--                                    </ul>-->
                        <!--                                </div>-->
                        <!--                                <img src="assets/images/b3.PNG" alt="">-->
                        <!--                            </div>-->
                        <!--                            <div class="down-content">-->
                        <!--                                <h4>Gamila Leather <br>Tote</h4>-->
                        <!--                                <span>80.00 NIS</span>-->
                        <!--                                <ul class="stars">-->
                        <!--                                    <li><i class="fa fa-star"></i></li>-->
                        <!--                                    <li><i class="fa fa-star"></i></li>-->
                        <!--                                    <li><i class="fa fa-star"></i></li>-->
                        <!--                                    <li><i class="fa fa-star"></i></li>-->
                        <!--                                    <li><i class="fa fa-star"></i></li>-->
                        <!--                                </ul>-->
                        <!--                            </div>-->
                        <!--                        </div>div-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner"  >
        <div class="carousel-item active" >
            <div class="container" style="padding: 30px" >
                <div class="card shadow position-relative">
                    <div class="row no-gutters">
                        <div class="col-md-8">
                            <img src="assets/images/tat1.PNG" alt="" class="card-image img-fluid"  >
                        </div>
                        <div class="col-md-4">
                            <div class="card-img-overlay d-flex justify-content-center align-items-center">
                                <div class="text-left text-light">
                                    <p class="card-title display-1 font-weight-light mb-2 " style="font-size: 18px; color: #2a2a2a; text-align: center">WE PROUDLY  </p>

                                    <p class="card-title display-1 font-weight-bold mb-2" style="font-size: 18px; color: #2a2a2a; text-align: center">PERSERVE AND PROMOTE CULTURAL HERITAGE  </p>
                                    <br>
                                    <p class="card-title display-4 font-weight-light mb-0" style="font-size: 18px; color: #2a2a2a; text-align: center">in Palestine while supporting our partners through trainings and qualifying work  </p>

                                    <div style="position: relative; text-align: center; padding: 30px;">
                                        <a id="readMore" href="empowringWomen.php" style="color: red;">Read more</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="carousel-item">
            <div class="container"   style="padding: 30px"   >
                <div class="card shadow position-relative">
                    <div class="row no-gutters">
                        <div class="col-md-8">
                            <img src="assets/images/olivePick.jpg" alt="" class=" card-image img-fluid"  >
                        </div>
                        <div class="card-title col-md-4">
                            <div class="card-img-overlay d-flex justify-content-center align-items-center">
                                <div class="text-left text-light">
                                    <p class="card-title display-1 font-weight-light mb-2" style="font-size: 18px; color: #2a2a2a; text-align: center">WITH EVERY PURCHASE YOU MAKE </p>

                                    <p class="card-title display-1 font-weight-bold mb-2" style="font-size: 18px; color: #2a2a2a; text-align: center">WE PLANT TREES </p>
                                    <br>
                                    <p class="card-title display-4 font-weight-light mb-0" style="font-size: 18px; color: #2a2a2a; text-align: center">Handmade Palestine helps fund a large non-profit organization in Palestine called Roots Trees, where local trees are protected and the community comes to learn about Palestine's natural heritage. </p>

                                    <div style="position: relative; text-align: center; padding: 30px;">
                                        <a id="readMore2" href="olive.php" style="color: red;">Read more</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!--<div class="carousel-item">
            <div class="container"  style="padding: 30px"  >
                <div class="card shadow position-relative">
                    <div class="row no-gutters">
                        <div class="col-md-8">
                            <img src="assets/images/tat1.PNG" alt="" class="img-fluid"  style="width: 100%; height: 100%">
                        </div>
                        <div class="col-md-4">
                            <div class="card-img-overlay d-flex justify-content-center align-items-center">
                                <div class="text-left text-light">
                                    <p class="card-title display-1 font-weight-bold mb-2" style="font-size: 18px; color: #2a2a2a; text-align: center">We proudly preserve and promote cultural heritage </p>
                                    <br>
                                    <p class="card-title display-4 font-weight-light mb-0" style="font-size: 18px; color: #2a2a2a; text-align: center">in Palestine while supporting our partners through trainings and qualifying work PRESERVING PALESTINIAN HERITAGE </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>-->
    </div>
    <button  class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev" style="padding: 30px" >
            <span class="" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="black" class="bi bi-arrow-left" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
</svg>
            <span class="visually-hidden">Previous</span>
        <button>
        <button  class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next" >
            <span class="" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="black" class="bi bi-arrow-right" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/>
</svg>
            </span>
            <span class="visually-hidden">Next</span>
        </button>
</div>
<!--   <div class="container" >
       <div class="card shadow position-relative">
           <div class="row no-gutters">
               <div class="col-md-8">
                   <img src="assets/images/tat1.PNG" alt="" class="img-fluid">
               </div>
               <div class="col-md-4">
                   <div class="card-img-overlay d-flex justify-content-center align-items-center">
                       <div class="text-left text-light">
                           <p class="card-title display-1 font-weight-bold mb-2" style="font-size: 18px; color: #2a2a2a; text-align: center">We proudly preserve and promote cultural heritage </p>
                           <br>
                           <p class="card-title display-4 font-weight-light mb-0" style="font-size: 18px; color: #2a2a2a; text-align: center">in Palestine while supporting our partners through trainings and qualifying work PRESERVING PALESTINIAN HERITAGE </p>
                       </div>
                   </div>
               </div>
           </div>
       </div>
   </div>
-->




<!-- ***** Explore Area Starts ***** -->
<section class="section" id="explore">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="left-content" >
                    <h2 style="font-size: 40px">Explore Our Products</h2>
                    <span style="color: black; font-size: 16px">
                            Each <u>HOWIYYA</u> creation is entirely handmade by our talented artisans in the West Bank
                            . Our team has grown to include six full-time and 22 part-time workers from Zababdeh and neighboring regions. <br>
                            These women specialize in Palestinian tatreez embroidery, a traditional art form that has been passed down from mother
                            to daughter for centuries.<br>
                        </span>
                    <div class="quote" >
                        <p style="font-size: 20px; font-weight:normal;">
                            "Tatreez, then, is a living archive that witnesses and documents what Palestine and Palestinians have historically endured and continue to withstand".
                        </p>
                    </div>
                    <span style="color: black; font-size: 16px">
                            Every tatreez motif holds significant meaning that shares a story in each stitch.
                        From intricately embroidered <b>Bags</b>  that carry stories of resilience and tradition to  <b >Elegant Clothing</b>
                            adorned with timeless patterns, each piece encapsulates the artistry and spirit of Palestinian craftsmanship.<br>
                            Explore our collection of <b >Accessories</b> , where every handcrafted item serves as a testament to the enduring beauty of Tatreez.
                            Bring the warmth and charm of Palestinian culture into your home with our exquisite <b >Home Decor</b> pieces,
                            meticulously crafted to add a touch of elegance to any space. <br>
                        </span>



                </div>
            </div>
            <div class="col-lg-6">
                <div class="right-content">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="leather">
                                <img src="assets/images/op1.jpg" alt="" >
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="first-image">
                                <img src="assets/images/op2.JPG" alt="">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="second-image">
                                <img src="assets/images/op4.jpg" alt="">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="types">
                                <img src="assets/images/op3.jpg" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ***** Explore Area Ends ***** -->

<!-- ***** Social Area Starts ***** -->
<section class="section" id="social">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-heading">
                    <h2>Social Media</h2>
                    <span>We own Tomorrow, Tomorrow is a Palestinian Day</span>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row images">
            <div class="col-2">
                <div class="thumb">
                    <div class="icon">
                        <a href="http://instagram.com">
                            <h6>Our Home</h6>
                            <i class="fa fa-instagram"></i>
                        </a>
                    </div>
                    <img src="assets/images/sm1.jpg" alt="">
                </div>
            </div>
            <div class="col-2">
                <div class="thumb">
                    <div class="icon">
                        <a href="http://instagram.com">
                            <h6>Mangel Stitch</h6>
                            <i class="fa fa-instagram"></i>
                        </a>
                    </div>
                    <img src="assets/images/sm3.jpg" alt="">
                </div>
            </div>
            <div class="col-2">
                <div class="thumb">
                    <div class="icon">
                        <a href="http://instagram.com">
                            <h6>Gaza Thobe</h6>
                            <i class="fa fa-instagram"></i>
                        </a>
                    </div>
                    <img src="assets/images/sm2.jpg" alt="">
                </div>
            </div>
            <div class="col-2">
                <div class="thumb">
                    <div class="icon">
                        <a href="http://instagram.com">
                            <h6>Thobe Al Majdal</h6>
                            <i class="fa fa-instagram"></i>
                        </a>
                    </div>
                    <img src="assets/images/sm4.jpg" alt="">
                </div>
            </div>
            <div class="col-2">
                <div class="thumb">
                    <div class="icon">
                        <a href="http://instagram.com">
                            <h6>Mother's Day</h6>
                            <i class="fa fa-instagram"></i>
                        </a>
                    </div>
                    <img src="assets/images/sm5.jpg" alt="">
                </div>
            </div>
            <div class="col-2">
                <div class="thumb">
                    <div class="icon">
                        <a href="http://instagram.com">
                            <h6>Olive's Tree and Occupation</h6>
                            <i class="fa fa-instagram"></i>
                        </a>
                    </div>
                    <img src="assets/images/sm6.JPG" alt="">
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ***** Social Area Ends ***** -->

<!-- ***** Subscribe Area Starts ***** -->
<div class="subscribe">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="section-heading">
                    <h2>By Subscribing To Our Newsletter You Can Get 30% Off</h2>
                    <span>We will let you know all of the new in-store and sales.</span>
                </div>
                <form id="subscribe" action="" method="get">
                    <div class="row">
                        <div class="col-lg-5">
                            <fieldset>
                                <input name="name" type="text" id="name" placeholder="Your Name" required="">
                            </fieldset>
                        </div>
                        <div class="col-lg-5">
                            <fieldset>
                                <input name="email" type="text" id="email" pattern="[^ @]*@[^ @]*" placeholder="Your Email Address" required="">
                            </fieldset>
                        </div>
                        <div class="col-lg-2">
                            <fieldset>
                                <button type="submit" id="form-submit" class="main-dark-button"><i class="fa fa-paper-plane"></i></button>
                            </fieldset>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-4">
                <div class="row">
                    <div class="col-6">
                        <ul>
                            <li>Store Location:<br><span>Bethleem, FL 33160, Palestine</span></li>
                            <li>Phone:<br><span>+970-599665522</span></li>
                            <li>Shop Location:<br><span>Bayt-Jala, HebronRd</span></li>
                        </ul>
                    </div>
                    <div class="col-6">
                        <ul>
                            <li>Work Hours:<br><span>07:30 AM - 9:30 PM Daily</span></li>
                            <li>Email:<br><span>howiyya@gmail.com</span></li>
                            <li>Social Media:<br><span><a href="#">Facebook</a>, <a href="#">Instagram</a>, <a href="#">Behance</a>, <a href="#">Linkedin</a></span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ***** Subscribe Area Ends ***** -->
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