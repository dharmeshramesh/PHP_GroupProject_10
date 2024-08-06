<?php
include_once 'Database.php';
include_once 'User.php';
include_once 'Cart1.php';

session_start();


$db = new Database();
$user = new User($db);
$cart = new Cart($db, $user->getUserId());

if (isset($_POST['logout'])) {
    $user->logout();
}

if (isset($_POST['check_out'])) {
    header("Location: checkout.php");
    exit();
}

if (isset($_POST['back'])) {
    header("Location: mobile.php");
    exit();
}

// Check if the user wants to delete an item from the cart
if (isset($_GET['action']) && $_GET['action'] === 'delete') {
    $cart->deleteItem($_GET['m_id']);
    // Redirect to avoid re-executing the deletion
    header("Location: cart.php");
    exit();
}

?>
<html>
<head>
    <link href="products_style.css" rel="stylesheet" type="text/css" />
    <title>CART</title>
</head>
<body>
    <h1 align="center">TECH SCOPE</h1>
    <form method="post">
        <div align="left">
            <input type="submit" class="button" name="back" value="BACK" />
        </div>
        <div align="right">
            <input type="submit" class="button" name="check_out" value="CHECK OUT" />
            <input type="submit" class="button" name="logout" value="Logout" />
        </div>
        <div class="mydiv">Welcome <?php echo $user->getUserName(); ?> <br> <?php echo $user->getEmail(); ?></div>
        <h4 align="center">CART</h4>
        <table>
            <tr>
                <td width='20%' align='center'><strong>BRAND</strong></td>
                <td width='20%' align='center'><strong>MODEL NAME</strong></td>
                <td width='10%' align='center'><strong>COLOR</strong></td>
                <td width='10%' align='center'><strong>PRICE</strong></td>
                <td width='10%' align='center'><strong>STORAGE(GB)</strong></td>
                <td width='10%' align='center'><strong>DISPLAY SIZE(Inches)</strong></td>
                <td width='10%' align='center'><strong>RAM</strong></td>
                <td width='10%' align='center'><strong>QUANTITY</strong></td>
            </tr>
            <?php
            $cart->displayCart();
            ?>
        </table>
    </form>
</body>
</html>