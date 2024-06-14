<?php
//session_start();
$emailErr = 0;
$sectionToShow = '';
require "dp.php";
$updateSuccess=0;
// Assuming 'UserID' is used to store the user's unique ID in the session
$user_name = isset($_SESSION['UserID']) ? $_SESSION['UserID'] : '';

$email = "";
$fullName = "";
$password = "";

global $conn;

if ($user_name) {
    // Fetch user data from the database
    $stmt = $conn->prepare("SELECT email, name, password FROM user WHERE user_name = ?");
    $stmt->bind_param("s", $user_name);
    $stmt->execute();
    $stmt->bind_result($email, $fullName, $password);
    $stmt->fetch();
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_profile'])) {
    $email = $_POST["email"];
    $fullName = $_POST["full_name"];

    $stmt = $conn->prepare("SELECT * FROM user WHERE email = ? AND user_name != ?");
    $stmt->bind_param("ss", $email, $user_name);
    $stmt->execute();
    $stmt->store_result();
    $sectionToShow = 'f1';

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
    }
    $conn->close();
}
?>
<?php
session_start();
$passErr = 0;
require "dp.php";
$updatePass=0;
// Assuming 'UserID' is used to store the user's unique ID in the session
$user_name = isset($_SESSION['UserID']) ? $_SESSION['UserID'] : '';

$pass = "";
$new = "";
$conf = "";

global $conn;
global $sectionToShow;


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_password'])) {
    $sectionToShow = 'f2';
    if ($user_name) {
        // Fetch user data from the database
        $stmt = $conn->prepare("SELECT password FROM user WHERE user_name = ?");
        $stmt->bind_param("s", $user_name);
        $stmt->execute();
        $stmt->bind_result( $pass);
        $stmt->fetch();
        $stmt->close();
    }
    if( (password_verify($pass,$_POST['pass8']))){
        $passErr=1;
    }
    else if($_POST['newPass'] != $_POST['confPass'] ){
        $passErr=2;
    }

    else {
        $newPass=$_POST['newPass'];
        $hashedPassword = password_hash($newPass, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("UPDATE user SET password = ? WHERE user_name = ?");
        $stmt->bind_param("ss", $hashedPassword, $user_name);
        $stmt->execute();
        $stmt->close();

        $updatePass=1;
    }
    $conn->close();
}
?>

<?php
require "dp.php";
$user_name = isset($_SESSION['UserID']) ? $_SESSION['UserID'] : '';
global $conn;
global $sectionToShow;

$orders = [];
if ($user_name) {
    // Fetch orders related to the user
    $stmt = $conn->prepare("SELECT o.order_id, o.total_amount, o.order_date, p.product_name, od.quantity, p.price
                            FROM `order` o 
                            JOIN order_deatails od ON o.order_id = od.order_id 
                            JOIN product p ON od.product_id = p.product_id 
                            WHERE o.user_name = ?");
    $stmt->bind_param("s", $user_name);
    $stmt->execute();
    $result = $stmt->get_result();
    $sectionToShow = 'f3';

    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CodingDung | Profile Template</title>
    <link rel="stylesheet" href="user_info.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">

    <link rel="stylesheet" type="text/css" href="assets/css/font-awesome.css">

    <link rel="stylesheet" href="assets/css/HowiyyaShop.css">

    <link rel="stylesheet" href="assets/css/owl-carousel.css">

    <link rel="stylesheet" href="assets/css/lightbox.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <link rel="stylesheet" href="assets/css/custom.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <script>
        function showSection(section) {
            document.getElementById('f1').style.display = 'none';
            document.getElementById('f2').style.display = 'none';
            document.getElementById('f3').style.display = 'none';
            document.getElementById(section).style.display = 'block';
        }

        // Ensure the profile settings are shown by default
        window.onload = function() {
            showSection('<?php echo $sectionToShow ?>');
        };
    </script>
</head>

<body>
<?php
include 'header.php';
?>

<div class="container rounded bg-white mt-5 mb-5" style="padding-top:10px">
    <div class="row">
        <div class="col-md-3 border-right"  style="padding-top: 0px">
            <div class="d-flex flex-column align-items-center text-center p-3 py-5"><img class="rounded-circle mt-5" width="100px" src="assets/images/user%20(1).png">
                <?php echo '<span class="font-weight-bold" name="userName">'.$user_name.'</span>' ?>
                <?php echo' <span class="text-black-50" name="email">'.$email.'</span>' ?>
            </div>
            <div>
                <button
                        name="edit_profile"
                        type="button"
                        class="btn custom-btn"
                        onclick="showSection('f1')"
                        style="width:100%; margin-bottom: 20px; background-color: transparent; border: none; color: black; padding: 10px 20px; font-size: 16px; text-align: left; border-radius: 0">
                    Profile Setting
                </button>
            </div>
            <button
                    name="change_pass"
                    type="button"
                    class="btn custom-btn"
                    onclick="showSection('f2')"
                    style="width:100%; margin-bottom: 20px; background-color: transparent; border: none; color: black; padding: 10px 20px; font-size: 16px; text-align: left; border-radius: 0">
                Change Password
            </button>
            <button
                    name="order"
                    type="button"
                    class="btn custom-btn"
                    onclick="showSection('f3')"
                    style="width:100%; margin-bottom: 20px; background-color: transparent; border: none; color: black; padding: 10px 20px; font-size: 16px; text-align: left; border-radius: 0">
                Orders
            </button>
        </div>

        <div class="col-md-5 " style="padding-top: 30px; <?= $sectionToShow == 'f1' ? 'display:block;' : 'display:none;' ?>" id="f1" >
            <div class="p-3 py-5">
                <div class="d-flex justify-content-between align-items-center mb-3" style="padding-top: 10px">
                    <h4 class="text-right">Profile Settings</h4>
                </div>
                <?php if ($updateSuccess == 1): ?> <p style="color:green;">Profile updated successfully!</p> <?php endif; ?>
                <form action="user_info.php" method="post" >
                    <div class="row mt-2">
                        <div class="col-md-6"><label class="labels">User name</label><input readonly type="text" class="form-control" placeholder="enter user name" value=<?= $user_name ?>></div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12"><label class="labels">Name</label><input  name="full_name" type="text" class="form-control" placeholder="Full name" value="<?php echo htmlspecialchars($fullName); ?>" ></div>

                        <div class="col-md-12"><label class="labels">Email</label><input name="email" type="email" class="form-control" placeholder="enter email" value="<?php echo htmlspecialchars($email); ?>" ></div>
                    </div>
                    <!--                <div class="row mt-3">-->
                    <!--                    <div class="col-md-6"><label class="labels">Password </label><input type="text" class="form-control" placeholder="country" value=""></div>-->
                    <!--                    <div class="col-md-6"><label class="labels">State/Region</label><input type="text" class="form-control" value="" placeholder="state"></div>-->
                    <!--                </div>-->
                    <div class="mt-5 text-center">
                        <button type="submit" class="btn btn-primary profile-button" name="edit_profile" > Save Profile</button>
                    </div>
                </form>
                <?php
                if($emailErr)
                    echo '<span style="color: red"> Email is used by another account</span>'
                ?>
            </div>
        </div>


        <div class="col-md-5 " style="padding-top: 30px; <?= $sectionToShow == 'f2' ? 'display:block;' : 'display:none;' ?>" id="f2" >
            <div class="p-3 py-5">
                <div class="d-flex justify-content-between align-items-center mb-3" style="padding-top: 10px">
                    <h4 class="text-right">Change Password</h4>
                </div>
                <?php if ($updatePass == 1): ?> <p style="color:green;">Password updated successfully!</p> <?php endif; ?>
                <form action="user_info.php" method="post" >
                    <div class="row mt-2">
                        <div class="col-md-6"><label class="labels">Password</label><input name="pass8" type="password" class="form-control" placeholder="Enter Old Password" ></div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12"><label class="labels">New Password</label><input  name="newPass" type="password" class="form-control" placeholder="New Password"  ></div>

                        <div class="col-md-12"><label class="labels">Confirm Password</label><input name="confPass" type="password" class="form-control" placeholder="Confirm Password" ></div>
                    </div>

                    <div class="mt-5 text-center">
                        <button type="submit" class="btn btn-primary profile-button" name="edit_password" > Change Password</button>
                    </div>
                </form>
                <?php
                if($passErr==1)
                    echo '<span style="color: red"> Wrong Old Password</span>';
                elseif ($passErr==2)
                    echo '<span style="color: red"> Not Matched</span>';
                ?>
            </div>
        </div>
        <!--        <div class="col-md-4">-->
        <!--            <div class="p-3 py-5">-->
        <!--                <div class="d-flex justify-content-between align-items-center experience"><span>Edit Experience</span><span class="border px-3 p-1 add-experience"><i class="fa fa-plus"></i>&nbsp;Experience</span></div><br>-->
        <!--                <div class="col-md-12"><label class="labels">Experience in Designing</label><input type="text" class="form-control" placeholder="experience" value=""></div> <br>-->
        <!--                <div class="col-md-12"><label class="labels">Additional Details</label><input type="text" class="form-control" placeholder="additional details" value=""></div>-->
        <!--            </div>-->
        <!--        </div>-->

        <!-- Orders Section -->
        <div class="col-md-7" style="padding-top: 30px; <?= $sectionToShow == 'f3' ? 'display:block;' : 'display:none;' ?>" id="f3">
            <div class="p-3 py-5">
                <div class="d-flex justify-content-between align-items-center mb-3" style="padding-top: 10px">
                    <h4 class="text-right">Orders</h4>
                </div>
                <table class="table">
                    <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                        <th>Order Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>#<?php echo htmlspecialchars($order['order_id']); ?></td>
                            <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                            <td>$<?php echo htmlspecialchars(number_format($order['price'], 2)); ?></td>
                            <td>$<?php echo htmlspecialchars(number_format($order['quantity'] * $order['price'], 2)); ?></td>
                            <td><?php echo htmlspecialchars(date("d M, Y", strtotime($order['order_date']))); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
include 'footer.php';
?>


<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
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
</body>

</html>