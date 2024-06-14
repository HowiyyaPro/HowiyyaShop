<?php

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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const itemsPerPage = 6;
            const productList = document.querySelectorAll('.product-item');
            const paginationControls = document.getElementById('pagination-controls');

            function showPage(page) {
                productList.forEach((item, index) => {
                    item.style.display = (index >= (page - 1) * itemsPerPage && index < page * itemsPerPage) ? 'block' : 'none';
                });
            }

            function createPaginationControls() {
                const totalPages = Math.ceil(productList.length / itemsPerPage);
                paginationControls.innerHTML = '';

                for (let i = 1; i <= totalPages; i++) {
                    const li = document.createElement('li');
                    const a = document.createElement('a');
                    a.href = "#";
                    a.textContent = i;
                    a.addEventListener('click', (e) => {
                        e.preventDefault();
                        showPage(i);
                        document.querySelector('.pagination .active').classList.remove('active');
                        li.classList.add('active');
                    });
                    li.appendChild(a);
                    paginationControls.appendChild(li);
                }

                if (paginationControls.firstChild) {
                    paginationControls.firstChild.classList.add('active');
                }
            }

            createPaginationControls();
            showPage(1);
        });
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
                    <span>Home / Collections / Home Decor</span>
                    <h2>Home Decor Page</h2>
                    <span>Our Home Decor are handmade, authentic and ethically made by women artisans in Palestine.</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ***** Main Banner Area End ***** -->

<!-- ***** Products Area Starts ***** -->
<section class="section" id="products">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-heading">
                    <h2>Our Latest Products</h2>
                    <span>Check out all of our products.</span>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row" id="product-list">
            <?php

            require "dp.php";
            global $conn;
            $product_type = 'home_decor'; // Example product type

            // Fetch products based on the product type
            $stmt = $conn->prepare("SELECT p.product_id, p.product_name, p.description, p.price, p.stock, i.image_url  
                    FROM product p 
                    LEFT JOIN images_url i ON p.product_id = i.product_id 
                    WHERE p.product_type = ?");
            $stmt->bind_param("s", $product_type);
            $stmt->execute();
            $result = $stmt->get_result();

            $count = 0;
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="col-lg-4 product-item" data-item="' . $count . '">
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
            </div>
        </div>';
                    $count++;
                }
            } else {
                echo '<p>No products found.</p>';
            }

            $stmt->close();
            $conn->close();
            ?>
        </div>

        <div class="col-lg-12">
            <div class="pagination">
                <ul id="pagination-controls">
                    <!-- Pagination controls will be dynamically generated here -->
                </ul>
            </div>
        </div>
    </div>

    <!-- ***** Products Area Ends ***** -->

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

<!--            <div class="col-lg-4">-->
<!--                <div class="item">-->
<!--                    <div class="thumb">-->
<!--                        <div class="hover-content">-->
<!--                            <ul>-->
<!--                                <li><a href="single-product.html"><i class="fa fa-eye"></i></a></li>-->
<!--                                <li><a href="single-product.html"><i class="fa fa-star"></i></a></li>-->
<!--                                <li><a href="single-product.html"><i class="fa fa-shopping-cart"></i></a></li>-->
<!--                            </ul>-->
<!--                        </div>-->
<!--                        <img src="assets/images/h2.PNG" alt="">-->
<!--                    </div>-->
<!--                    <div class="down-content">-->
<!--                        <h4 name="productName">Dalia Coasters <br> - Palestinian Red</h4>-->
<!--                        <span name="productPrice">40.00 NIS</span>-->
<!--                        <ul class="stars">-->
<!--                            <li><i class="fa fa-star"></i></li>-->
<!--                            <li><i class="fa fa-star"></i></li>-->
<!--                            <li><i class="fa fa-star"></i></li>-->
<!--                            <li><i class="fa fa-star"></i></li>-->
<!--                            <li><i class="fa fa-star"></i></li>-->
<!--                        </ul>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="col-lg-4">-->
<!--                <div class="item">-->
<!--                    <div class="thumb">-->
<!--                        <div class="hover-content">-->
<!--                            <ul>-->
<!--                                <li><a href="single-product.html"><i class="fa fa-eye"></i></a></li>-->
<!--                                <li><a href="single-product.html"><i class="fa fa-star"></i></a></li>-->
<!--                                <li><a href="single-product.html"><i class="fa fa-shopping-cart"></i></a></li>-->
<!--                            </ul>-->
<!--                        </div>-->
<!--                        <img src="assets/images/h3.PNG" alt="">-->
<!--                    </div>-->
<!--                    <div class="down-content">-->
<!--                        <h4>Farah Half Apron <br> - Cream</h4>-->
<!--                        <span>60.00 NIS</span>-->
<!--                        <ul class="stars">-->
<!--                            <li><i class="fa fa-star"></i></li>-->
<!--                            <li><i class="fa fa-star"></i></li>-->
<!--                            <li><i class="fa fa-star"></i></li>-->
<!--                            <li><i class="fa fa-star"></i></li>-->
<!--                            <li><i class="fa fa-star"></i></li>-->
<!--                        </ul>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="col-lg-4">-->
<!--                <div class="item">-->
<!--                    <div class="thumb">-->
<!--                        <div class="hover-content">-->
<!--                            <ul>-->
<!--                                <li><a href="single-product.html"><i class="fa fa-eye"></i></a></li>-->
<!--                                <li><a href="single-product.html"><i class="fa fa-star"></i></a></li>-->
<!--                                <li><a href="single-product.html"><i class="fa fa-shopping-cart"></i></a></li>-->
<!--                            </ul>-->
<!--                        </div>-->
<!--                        <img src="assets/images/h4.PNG" alt="">-->
<!--                    </div>-->
<!--                    <div class="down-content">-->
<!--                        <h4>Noor Table Runner <br>- Palestinian Red</h4>-->
<!--                        <span>70.00 NIS</span>-->
<!--                        <ul class="stars">-->
<!--                            <li><i class="fa fa-star"></i></li>-->
<!--                            <li><i class="fa fa-star"></i></li>-->
<!--                            <li><i class="fa fa-star"></i></li>-->
<!--                            <li><i class="fa fa-star"></i></li>-->
<!--                            <li><i class="fa fa-star"></i></li>-->
<!--                        </ul>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="col-lg-4">-->
<!--                <div class="item">-->
<!--                    <div class="thumb">-->
<!--                        <div class="hover-content">-->
<!--                            <ul>-->
<!--                                <li><a href="single-product.html"><i class="fa fa-eye"></i></a></li>-->
<!--                                <li><a href="single-product.html"><i class="fa fa-star"></i></a></li>-->
<!--                                <li><a href="single-product.html"><i class="fa fa-shopping-cart"></i></a></li>-->
<!--                            </ul>-->
<!--                        </div>-->
<!--                        <img src="assets/images/h1full.jpg" alt="">-->
<!--                    </div>-->
<!--                    <div class="down-content">-->
<!--                        <h4>Tatreez Pillow -<br> Palestinian Red</h4>-->
<!--                        <span>120.00 NIS</span>-->
<!--                        <ul class="stars">-->
<!--                            <li><i class="fa fa-star"></i></li>-->
<!--                            <li><i class="fa fa-star"></i></li>-->
<!--                            <li><i class="fa fa-star"></i></li>-->
<!--                            <li><i class="fa fa-star"></i></li>-->
<!--                            <li><i class="fa fa-star"></i></li>-->
<!--                        </ul>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="col-lg-4">-->
<!--                <div class="item">-->
<!--                    <div class="thumb">-->
<!--                        <div class="hover-content">-->
<!--                            <ul>-->
<!--                                <li><a href="single-product.html"><i class="fa fa-eye"></i></a></li>-->
<!--                                <li><a href="single-product.html"><i class="fa fa-star"></i></a></li>-->
<!--                                <li><a href="single-product.html"><i class="fa fa-shopping-cart"></i></a></li>-->
<!--                            </ul>-->
<!--                        </div>-->
<!--                        <img src="assets/images/h5.png" alt="">-->
<!--                    </div>-->
<!--                    <div class="down-content">-->
<!--                        <h4>Hawthorn Berry Tatreez <br> Tea Towel</h4>-->
<!--                        <span>80.00 NIS</span>-->
<!--                        <ul class="stars">-->
<!--                            <li><i class="fa fa-star"></i></li>-->
<!--                            <li><i class="fa fa-star"></i></li>-->
<!--                            <li><i class="fa fa-star"></i></li>-->
<!--                            <li><i class="fa fa-star"></i></li>-->
<!--                        </ul>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="col-lg-4">-->
<!--                <div class="item">-->
<!--                    <div class="thumb">-->
<!--                        <div class="hover-content">-->
<!--                            <ul>-->
<!--                                <li><a href="single-product.html"><i class="fa fa-eye"></i></a></li>-->
<!--                                <li><a href="single-product.html"><i class="fa fa-star"></i></a></li>-->
<!--                                <li><a href="single-product.html"><i class="fa fa-shopping-cart"></i></a></li>-->
<!--                            </ul>-->
<!--                        </div>-->
<!--                        <img src="assets/images/h6.png" alt="">-->
<!--                    </div>-->
<!--                    <div class="down-content">-->
<!--                        <h4>Tatreez Pillow -<br> Palestinian Red</h4>-->
<!--                        <span>120.00 NIS</span>-->
<!--                        <ul class="stars">-->
<!--                            <li><i class="fa fa-star"></i></li>-->
<!--                            <li><i class="fa fa-star"></i></li>-->
<!--                            <li><i class="fa fa-star"></i></li>-->
<!--                            <li><i class="fa fa-star"></i></li>-->
<!--                            <li><i class="fa fa-star"></i></li>-->
<!--                        </ul>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->