<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Frequently Asked Questions</title>
    <link rel="stylesheet" href="assets/css/styleq&a.css" />
    <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
            integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
            crossorigin="anonymous"
            referrerpolicy="no-referrer"
    />


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

<!-- ***** Preloader End ***** -->


<!-- ***** Header Area Start ***** -->
<?php
include 'header.php';
?>
<!-- ***** Main Banner Area Start ***** -->
<div class="page-heading empW-page-heading" id="top" style=" margin-bottom: 70px">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="inner-content">
                    <h2 style="">Frequently Asked Questions</h2>
                    <span>Howiyya Assistant is with you 24/7 for enquiries regarding your order, delivery and payment transactions!</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ***** Main Banner Area End ***** -->
<div class="wrapper">
<!--    <p style="padding-top: 100px"></p>-->
<!--    <h1>Frequently Asked Questions</h1>-->

    <div class="faq">
        <button class="accordion">
            What is Tatreez?
            <i class="fa-solid fa-chevron-down"></i>
        </button>
        <div class="pannel">
            <p>
                Tatreez is a traditional Palestinian embroidery
                that uses crossed stitches and repeating motifs to tell a story,
                commemorate an event, or communicate an idea
            </p>
        </div>
    </div>

    <div class="faq">
        <button class="accordion">
           Who are our employee?
            <i class="fa-solid fa-chevron-down"></i>
        </button>
        <div class="pannel">
            <p>
                Our artisans are women from the West Bank who have historically endured marginalization
                in the form of low-income, gender inequality, and lack of economic opportunities. Howiyya’s mission is
                to economically empower these women by providing job opportunities, training, and a fair income
            </p>
        </div>
    </div>

    <div class="faq">
        <button class="accordion">
            How long does a delivery take?
            <i class="fa-solid fa-chevron-down"></i>
        </button>
        <div class="pannel">
            <p>
                Delivery periods of your orders may vary depending on the shipping company, country and address.
                Normally from 20-30 days.
            </p>
        </div>
    </div>

    <div class="faq">
        <button class="accordion">

            Which shipping companies do you work with?
            <i class="fa-solid fa-chevron-down"></i>
        </button>
        <div class="pannel">
            <p>
                We work with the best courier services in the world. This shipping company varies depending on the Shipping Country. Our Main contracted Shipping companies include:
                <br>  <br>
                Aramex<br>
                UPS<br>
                PTS<br>
                Fedex<br>
                Timex<br>
                Janio<br>
                Solmaz
            </p>
        </div>
    </div>

    <div class="faq">
        <button class="accordion">
            What are your payment methods?
            <i class="fa-solid fa-chevron-down"></i>
        </button>
        <div class="pannel">
            <p>
                We accept the following payment methods:
<br> <br>
                Credit Card, Debit Card and PayPal.<br>
                We do not accept payment via bank transfer.
            </p>
        </div>
    </div>


</div>
<?php
include 'footer.php';
?>


<script>
    var acc = document.getElementsByClassName("accordion");
    var i;

    for (i = 0; i < acc.length; i++) {
        acc[i].addEventListener("click", function () {
            this.classList.toggle("active");
            this.parentElement.classList.toggle("active");

            var pannel = this.nextElementSibling;

            if (pannel.style.display === "block") {
                pannel.style.display = "none";
            } else {
                pannel.style.display = "block";
            }
        });
    }
</script>
<!-- ***** Footer Start ***** -->

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