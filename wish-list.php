<?php
session_start();
require "dp.php";
global $conn;
// Assuming 'UserID' is used to store the user's unique ID in the session
$user_name = isset($_SESSION['UserID']) ? $_SESSION['UserID'] : '';

if ($user_name) {
    $stmt = $conn->prepare("SELECT p.product_id, p.product_name, p.description, p.price, p.stock, i.image_url 
    FROM wishing_list w
    JOIN product p ON w.product_id = p.product_id
    JOIN images_url i ON p.product_id = i.product_id
    WHERE w.user_name = ?
");
    $stmt->bind_param("s", $user_name);
    $stmt->execute();
    $result = $stmt->get_result();
    $wishlistItems = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_from_wishlist'])) {
    $product_id = $_POST['product_id'];
    if ($user_name && $product_id) {
        // Remove from wish list
        $stmt = $conn->prepare("DELETE FROM wishing_list WHERE user_name = ? AND product_id = ?");
        $stmt->bind_param("si", $user_name, $product_id);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: wish-list.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Table</title>
    <link rel="stylesheet" href="wish.css">
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

<div class="container px-3 my-5 clearfix" style="align-content: center ; padding-top: 5%;margin-left: 15%">
    <div class="card">
      <div class="card-header">
            <h2>Wish List</h2>
      </div>
    <table class="product-table">
    <thead>
    <tr>
        <th>Images</th>
        <th>Product</th>
        <th>Unit Price</th>
        <th>Stock Status</th>
        <th>Add to cart</th>
        <th>Remove</th>
    </tr>
    </thead>
    <tbody>
    <?php if (!empty($wishlistItems)): ?>
    <?php foreach ($wishlistItems as $item): ?>
    <tr>
        <td><img src="<?php echo htmlspecialchars($item['image_url']); ?>" style="width: 150px; height: auto;" alt="Product Image"></td>
        <td><?php echo htmlspecialchars($item['product_name']); ?></td>
<!--        <td>--><?php //echo htmlspecialchars($item['description']); ?><!--</td>-->
        <td><?php echo htmlspecialchars($item['price']); ?></td>
        <td class="<?php echo $item['stock'] > 0 ? 'in-stock' : 'out-stock'; ?>"><?php echo $item['stock'] > 0 ? 'In Stock' : 'Out of Stock'; ?></td>
        <td>
            <form action="cart.php" method="post" style="display:inline;">
                <input type="hidden" name="product_id" value="<?=$item['product_id'] ?>">
                <input type="hidden" name="quantity" value="1">
                <a>
                    <button style="background-color: black" type="submit" name="add_to_cart" class="add-to-cart">Add to Cart</button>
                </a>
            </form>
<!--            <form action="cart.php" method="post" style="display:inline;">-->
<!--                <input type="hidden" name="product_id" value="--><?php //echo $item['product_id']; ?><!--">-->
<!--                <button style="background-color: black" type="submit" name="add_to_cart" class="add-to-cart">Add to Cart</button>-->
<!--            </form>-->
        </td>
        <td>
            <form action="wish-list.php" method="post" style="display:inline;">
                <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                <button style="color: black; background-color: white" type="submit" name="remove_from_wishlist" class="remove">&times;</button>
            </form>
        </td>
    </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="6">Your wish list is empty.</td>
        </tr>
    <?php endif; ?>
<!--    <tr>-->
<!--        <td><img src="https://via.placeholder.com/80x102" alt="Product Image"></td>-->
<!--        <td>Natus erro</td>-->
<!--        <td>$30.50</td>-->
<!--        <td class="in-stock">in stock</td>-->
<!--        <td><button class="add-to-cart">Add To Cart</button></td>-->
<!--        <td class="remove">&times;</td>-->
<!--    </tr>-->
<!--    <tr>-->
<!--        <td><img src="https://via.placeholder.com/80x102" alt="Product Image"></td>-->
<!--        <td>Sit voluptatem</td>-->
<!--        <td>$40.19</td>-->
<!--        <td class="out-stock">out stock</td>-->
<!--        <td><button class="add-to-cart">Add To Cart</button></td>-->
<!--        <td class="remove">&times;</td>-->
<!--    </tr>-->
    </tbody>
</table>
</div>
</div>

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
