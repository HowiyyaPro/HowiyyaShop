<?php
session_start();
require 'dp.php';
global $conn;
$emailErr =0;
$user_name = isset($_SESSION['UserID']) ? $_SESSION['UserID'] : '';
    if($user_name!='')
        {
            $stmt = $conn->prepare("SELECT email, name, password FROM user WHERE user_name = ?");
            $stmt->bind_param("s", $user_name);
            $stmt->execute();
            $stmt->bind_result($email, $fullName, $password);
            $stmt->fetch();
            $stmt->close();
        }

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit-profile'])) {
    $email = $_POST["email"];
    $fullName = $_POST["fullName"];

    $stmt = $conn->prepare("SELECT * FROM user WHERE email = ? AND user_name != ?");
    $stmt->bind_param("ss", $email, $user_name);
    $stmt->execute();
    $stmt->store_result();


    if ($stmt->num_rows > 0) {
        $emailErr = 1;
    } else {
        $stmt = $conn->prepare("UPDATE user SET email = ?, name = ? WHERE user_name = ?");
        $stmt->bind_param("sss", $email, $fullName, $user_name);
        $stmt->execute();
        $stmt->close();

        $updateSuccess=1;
        $email = $_POST["email"];
        $fullName = $_POST["full_name"];
        // Re-fetch updated user data from the database
        $stmt = $conn->prepare("SELECT email, name, password FROM user WHERE user_name = ?");
        $stmt->bind_param("s", $user_name);
        $stmt->execute();
        $stmt->bind_result($email, $fullName, $password);
        $stmt->fetch();
        $stmt->close();

        header('Location: admin-profile.php');

    }
    $conn->close();
}
?>
<?php
session_start();
require "dp.php"; // Include your database connection file
global $conn;

$passErr = isset($_SESSION['passErr']) ? $_SESSION['passErr'] : 0;
$passSuccess = isset($_SESSION['passSuccess']) ? $_SESSION['passSuccess'] : 0;
unset($_SESSION['passErr']);
unset($_SESSION['passSuccess']);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['change-pass'])) {
    $user_name = isset($_SESSION['UserID']) ? $_SESSION['UserID'] : '';

    if ($user_name) {
        // Fetch user data from the database
        $stmt = $conn->prepare("SELECT password FROM user WHERE user_name = ?");
        $stmt->bind_param("s", $user_name);
        $stmt->execute();
        $stmt->bind_result($storedPassword);
        $stmt->fetch();
        $stmt->close();

        // Verify current password
        if (sha1($_POST['password'])!= $storedPassword) {
            $_SESSION['passErr'] = 1; // Wrong old password
            $_SESSION['passSuccess'] = 0;
        } elseif ($_POST['newpassword'] != $_POST['renewpassword']) {
            $_SESSION['passErr'] = 2; // Passwords do not match
            $_SESSION['passSuccess'] = 0;
        } else {
            // Update the password in the database
            $newpassword = $_POST['newpassword'];
            $hashedPassword = sha1($newpassword);
            $stmt = $conn->prepare("UPDATE user SET password = ? WHERE user_name = ?");
            $stmt->bind_param("ss", $hashedPassword, $user_name);
            $stmt->execute();
            $stmt->close();

            $_SESSION['passSuccess'] = 1; // Password updated successfully
        }
    }

    $conn->close();
    header('Location: admin-profile.php#profile-change-password');
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>

    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            if (window.location.hash) {
                var activeTab = document.querySelector('[data-bs-target="' + window.location.hash + '"]');
                if (activeTab) {
                    var tab = new bootstrap.Tab(activeTab);
                    tab.show();
                }
            }

            var tabButtons = document.querySelectorAll('button[data-bs-toggle="tab"]');
            tabButtons.forEach(function (button) {
                button.addEventListener('shown.bs.tab', function (e) {
                    var hash = e.target.getAttribute('data-bs-target');
                    if (history.pushState) {
                        history.pushState(null, null, hash);
                    } else {
                        location.hash = hash;
                    }
                });
            });
        });
    </script>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Users / Profile - NiceAdmin Bootstrap Template</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">
    <link href="assets/css/Admin.css" rel="stylesheet">

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
    <link href="assets/css/style.css" rel="stylesheet">

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
        <h1>Profile</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active">Profile</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section profile">
        <div class="row">
            <div class="col-xl-4">

                <div class="card">
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

                        <img src="assets/images/admin-panel%20(2).png" alt="Profile" class="rounded-circle">
                        <h2 style="color: red"><?php echo $user_name?></h2>
                        <h3 style="color: cornflowerblue" ><?php echo $email ?></h3>
<!--                        <div class="social-links mt-2">-->
<!--                            <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>-->
<!--                            <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>-->
<!--                            <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>-->
<!--                            <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>-->
<!--                        </div>-->
                    </div>
                </div>

            </div>

            <div class="col-xl-8">

                <div class="card">
                    <div class="card-body pt-3">
                        <!-- Bordered Tabs -->
                        <ul class="nav nav-tabs nav-tabs-bordered">

                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
                            </li>

                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button>
                            </li>

                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change Password</button>
                            </li>

                        </ul>
                        <div class="tab-content pt-2">

                            <div class="tab-pane fade show active profile-overview" id="profile-overview">
                              <h5 class="card-title">Profile Details</h5>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label ">User Name</div>
                                    <div class="col-lg-9 col-md-8"><?php echo $user_name?></div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label ">Full Name</div>
                                    <div class="col-lg-9 col-md-8"><?php echo $fullName?></div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Email</div>
                                    <div class="col-lg-9 col-md-8"><?php echo $email?></div>
                                </div>

                            </div>

                            <div class="tab-pane fade profile-edit pt-3" id="profile-edit">

                                <!-- Profile Edit Form -->
                                <form action="admin-profile.php" method="post">

                                    <div class="row mb-3">
                                        <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Full Name</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="fullName" type="text" class="form-control" id="fullName" value="<?php echo htmlspecialchars($fullName); ?>">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="Email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="email" type="email" class="form-control" id="Email" value=<?= $email?>>
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary" name="edit-profile">Save Changes</button>
                                    </div>
                                </form><!-- End Profile Edit Form -->

                            </div>

                            <div class="tab-pane fade pt-3" id="profile-change-password">
                                <!-- Change Password Form -->
                                <form action="admin-profile.php" method="post">
                                    <div class="row mb-3">
                                        <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Current Password</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="password" type="password" class="form-control" id="currentPassword">
                                            <?php if ($passErr == 1): ?>
                                                <p style="color: red">Wrong Old Password</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="newpassword" type="password" class="form-control" id="newPassword">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Re-enter New Password</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="renewpassword" type="password" class="form-control" id="renewPassword">
                                            <?php if ($passErr == 2): ?>
                                                <p style="color: red">Passwords do not match</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary" name="change-pass">Change Password</button>
                                    </div>
                                    <?php if ($passSuccess == 1): ?>
                                        <p style="color: darkseagreen">Passwords Update Successfully</p>

                                    <?php endif; ?>
                                </form>
                                <!-- End Change Password Form -->
                            </div>

                        </div><!-- End Bordered Tabs -->

                    </div>
                </div>

            </div>
        </div>
    </section>

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