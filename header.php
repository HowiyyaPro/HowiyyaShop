<?php
session_start();
$user_name = isset($_SESSION['UserID']) ? $_SESSION['UserID'] : '';
$user_level = isset($_SESSION['user_level']) ? $_SESSION['user_level'] : '';

// login.php
require 'dp.php';
global $conn;
$showModal = false;
$error = '';
$flag = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and bind
    $stmt = $conn->prepare("SELECT user_name, password, user_level FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);

    // Execute the query
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        // Bind result variables
        $stmt->bind_result($user_name, $hashedPassword, $user_level);
        $stmt->fetch();

        // Verify the password
        if (sha1($password) == $hashedPassword) {
            // Password is correct, start a session
            $_SESSION['UserID'] = $user_name;
            $_SESSION['Email'] = $email;
            $_SESSION['user_level'] = $user_level; // Store user level in session
            $flag = 1;
            header('location: index.php');
            exit();
        } else {
            $error = "Invalid password.";
            $showModal = true;
        }
    } else {
        $error = "No user found with that email.";
        $showModal = true;
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>

<?php
require 'dp.php';
$registerError = '';
$showRegisterModal = false;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $username = $_POST['user_name_reg'];
    $password = $_POST['pass_reg'];
    $email = $_POST['email_reg'];
    $confPass = $_POST['conf_pass_reg'];
    $fullName = $_POST['name_reg'];

    $stmt = $conn->prepare("SELECT * FROM user WHERE user_name = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $showRegisterModal = true;
        $registerError = "There's already an account with this username.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $showRegisterModal = true;
            $registerError = "There's already an account with this email.";
        } else if ($password != $confPass) {
            $showRegisterModal = true;
            $registerError = "Password must match!!";
        } else {
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Prepare and bind
            $stmt = $conn->prepare("INSERT INTO user (user_name, password, email, name) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $hashedPassword, $email, $fullName);

            // Execute the query
            if ($stmt->execute()) {
                $_SESSION['UserID'] = $username;
                $_SESSION['Email'] = $email;
                $_SESSION['user_level'] = 'u'; // Assuming new users are regular users
                echo '<script> alert("Successfully registered !")</script>';
                header('location: index.php');
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
    <meta charset="UTF-8">
    <title>Login & Register</title>
    <link rel="stylesheet" href="path/to/your/css/styles.css">
</head>
<body>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Check if $_SESSION["UserID"] is set
        <?php if (isset($_SESSION["UserID"])): ?>
        // If set, display the appropriate menu based on user level
        document.getElementById("L1").style.display = "none";
        <?php if ($_SESSION['user_level'] == 'u'): ?>
        document.getElementById("L2").style.display = "block";
        <?php elseif ($_SESSION['user_level'] == 'a'): ?>
        document.getElementById("L3").style.display = "block";
        <?php endif; ?>
        <?php else: ?>
        // If not set, display L1
        document.getElementById("L1").style.display = "block";
        document.getElementById("L2").style.display = "none";
        document.getElementById("L3").style.display = "none";
        <?php endif; ?>
    });
</script>
<!-- ***** Preloader Start ***** -->
<div id="preloader">
    <div class="jumper">
        <div></div>
        <div></div>
        <div></div>
    </div>
</div>
<!-- ***** Preloader End ***** -->

<!-- ***** Header Area Start ***** -->
<header class="header-area header-sticky">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="main-nav">
                    <!-- ***** Logo Start ***** -->
                    <a href="index.php" class="logoo">
                        <img src="assets/images/HOWIYYA.png" alt="Logo" class="img-fluid logoo" style="width:15%">
                    </a>
                    <!-- ***** Logo End ***** -->
                    <!-- ***** Menu Start ***** -->
                    <ul class="nav">
                        <li class="scroll-to-section"><a href="index.php" class="active">Home</a></li>
                        <li><a href="about.php">About Us</a></li>
                        <li class="submenu">
                            <a href="javascript:;">Shop</a>
                            <ul>
                                <li><a href="Clothes.html">Clothes</a></li>
                                <li><a href="Accessories.html">Accessories</a></li>
                                <li><a href="Bags.html">Bags</a></li>
                                <li><a href="Decor.php">Home Decor</a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:;">Bloges</a>
                            <ul>
                                <li><a href="olive.php">OLIVE TREES</a></li>
                                <li><a href="timeline.php">History of Tatreez</a></li>
                            </ul>
                        </li>
                        <li class="scroll-to-section"><a href="index.php#explore">Explore</a></li>
                        <li class="submenu">
                            <a href="javascript:;">Help & informations</a>
                            <ul>
                                <li><a href="contact.php">Contact us</a></li>
                                <li><a href="q&a.php">FAQs</a></li>
                            </ul>
                        </li>
                        <!-- Login/Register menu -->
                        <li><a href="view_cart.php"> <img src="assets/images/shopping-cart.png" alt="" ></a></li>
                        <li><a href="wish-list.php"><img src="assets/images/heart.png " style="width: 40px" alt=""></a></li>
                        <li id="L1" style="align-content: center;">
                            <div id="login-container" class="login-container d-flex align-items-center">
                                <button id="loginBtn" class="login-icon btn btn-primary" style="background-color: black;"><i class="fa fa-user"></i></button>
                                <div id="loginMenu" class="login-menu">
                                    <button id="openLoginModal" class="login-button"><span style="color: white;">Login</span></button>
                                    <button id="openRegisterModal" class="login-button"><span style="color: white">Sign Up</span></button>
                                    <hr>
                                    <a href="#" style="padding-top: 0">Help Center</a>
                                    <a href="#" style="padding-top: 0">Campaigns</a>
                                </div>
                            </div>
                        </li>
                        <li id="L2" class="submenu" style="display: none;">
                            <button id="loginBtn" class="login-icon btn btn-primary" style="background-color: black;"><i class="fa fa-user"></i> <?php echo $user_name; ?></button>
                            <ul>
                                <li><a href="user_info.php" style="padding-top: 0">User Information</a></li>
                                <li><a id="logOut" href="log_out.php" style="padding-top: 0">Log Out</a></li>
                            </ul>
                        </li>
                        <li id="L3" class="submenu" style="display: none;">
                            <button id="loginBtn" class="login-icon btn btn-primary" style="background-color: black;"><i class="fa fa-user"></i> <?php echo $user_name; ?></button>
                            <ul>
                                <li><a href="Dashboard.php" style="padding-top: 0">Admin Panel</a></li>
                                <li><a id="logOut" href="log_out.php" style="padding-top: 0">Log Out</a></li>
                            </ul>
                        </li>
                    </ul>
                    <a class="menu-trigger">
                        <span>Menu</span>
                    </a>
                    <!-- ***** Menu End ***** -->
                </nav>
            </div>
        </div>
    </div>
</header>

<script>
    document.getElementById("logOut").onclick = function() {
        document.getElementById("L1").style.display = "block";
        document.getElementById("L2").style.display = "none";
        document.getElementById("L3").style.display = "none";
    }
</script>

<div id="loginModal" class="modal" style="display: <?php echo $showModal ? 'block' : 'none'; ?>;">
    <div class="modal-content">
        <span class="close" id="closeLoginModal">&times;</span>
        <div class="login-container">
            <form class="login-form" method="post" action="index.php">
                <h2>Login</h2>
                <div class="input-group">
                    <input type="email" placeholder="Email" name="email" required>
                </div>
                <div class="input-group">
                    <input type="password" placeholder="Password" name="password" required>
                </div>
                <div class="input-group">
                    <label class="checkbox-container">
                        <input type="checkbox" checked="checked">
                        <span>Remember me</span>
                    </label>
                </div>
                <button type="submit" class="btn btn-danger" name="login">Login</button>
                <p class="extra-links">
                    <a href="#">Forgot Password?</a>
                    <a href="#" id="switchToRegister">Register</a>
                </p>
            </form>
            <?php if ($error != ""): ?>
                <p style="color:#b30000; text-decoration:underline"><?php echo $error; ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>
<script>
    document.getElementById("closeLoginModal").onclick = function() {
        document.getElementById("loginModal").style.display = "none";
    }
</script>

<!-- Register Modal -->
<div id="registerModal" class="modal" style="display: <?php echo $showRegisterModal ? 'block' : 'none'; ?>;">
    <div class="modal-content">
        <span class="close" id="closeRegisterModal">&times;</span>
        <div class="form-container">
            <form class="registration-form" action="index.php" method="post">
                <h2>Registration</h2>
                <div class="input-group">
                    <input type="text" placeholder="Username" name="user_name_reg" required>
                </div>
                <div class="input-group">
                    <input type="email" placeholder="Email" name="email_reg" required>
                </div>
                <div class="input-group">
                    <input type="text" placeholder="Full name" name="name_reg" required>
                </div>
                <div class="input-group">
                    <input type="password" placeholder="Password" name="pass_reg" required>
                </div>
                <div class="input-group">
                    <input type="password" placeholder="Confirm Password" name="conf_pass_reg" required>
                </div>
                <div class="input-group checkbox">
                    <label class="checkbox-container">
                        <input type="checkbox" required>
                        I agree to the terms & conditions
                    </label>
                </div>
                <button type="submit" class="btn btn-danger" name="register" style="height: 80%">Register</button>
                <p class="login-link">Already have an account? <a href="#" id="switchToLogin">Login</a></p>
            </form>
            <?php if ($registerError != ""): ?>
                <p style="color:#b30000; text-decoration:underline"><?php echo $registerError; ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>
<script>
    document.getElementById("closeRegisterModal").onclick = function() {
        document.getElementById("registerModal").style.display = "none";
    }
</script>
</body>
</html>
