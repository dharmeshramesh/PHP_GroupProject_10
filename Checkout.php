<html>
<head>
<link href = " products_style.css" rel ="stylesheet" type ="text/css"/>
<title>CHECK OUT</title>

</head>

<body>

<h1 align="center"> TECH SCOPE </h1>

<form action="" method="POST">
<?php
require_once 'Database.php'; 
require_once 'Cart1.php'; 
require_once 'Order.php'; 
include_once 'User.php';

class Checkout
{
    private $db;
    private $cart;
    private $order;

    

    public function __construct()
    {
        $this->db = new Database();
        $user=new User($this->db);
        $this->cart = new Cart($this->db,$user->getUserId());
        $this->order = new Order($this->db);
    }

    public function displayFinalCheck()
    {
        $user_name = $_SESSION['user_name'];
        $email = $_SESSION['email'];
        $user_id = $this->cart->getUserId($user_name);
        $items = $this->cart->getCartItems($user_id);
        if(isset($_POST['logout']))
	    {
		    session_destroy();
		    header("location: http://localhost/Group_Project/login.php");
		    exit();
	    }
        
        $card_no=$cvv=$valid="";
        
        echo "<div align='right'>
            <input type='submit' class='button' name='logout' value='Logout'>
          </div>
          <div class='mydiv'>
            Welcome $user_name <br> $email
          </div>";

    echo "<h3 align='center'>FINAL CHECK</h3>
          <table>
          <tr>
            <td width='10%' align='center'><strong>Product Name</strong></td>
            <td width='10%' align='center'><strong>Quantity</strong></td>
            <td width='10%' align='center'><strong>Total Price</strong></td>
          </tr>";

    $total = 0;
    foreach ($items as $item) {
        echo "<tr>
                <td width='10%' align='center'><strong>{$item['model_name']}</strong></td>
                <td width='10%' align='center'><strong>{$item['quantity']}</strong></td>
                <td width='10%' align='center'><strong>{$item['total_price']}</strong></td>
              </tr>";
        $total += $item['total_price'];
    }

    echo "<tr>
            <td width='49.5%' align='center'><strong>Total </strong></td>
            <td width='20%' align='center'><strong>$total</strong></td>
          </tr>
          </table>";

    echo "<div class='myDiv4'>
            Enter Card Number: <input type='text' name='card_no' value='$card_no'/><br>
            CVV: <input type='text' name='cvv' value='$cvv' /><br>
            Valid Thru: <input type='text' name='valid' value='$valid'/><br>
            <input type='submit' class='button1' name='submit' value='SUBMIT' />
          </div>";
    }

    public function processCheckout($card_no, $cvv, $valid)
    {
        $user_name = $_SESSION['user_name'];
        $user_id = $this->cart->getUserId($user_name);
        $order_id = $this->order->createOrder($user_id);

        $this->cart->clearCart($user_id);
        $_SESSION['id'] = $order_id;

        header("Location: exit.php");
        exit();
    }
}
$checkout = new Checkout();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit'])) {
        $checkout->processCheckout($_POST['card_no'], $_POST['cvv'], $_POST['valid']);
    } elseif (isset($_POST['logout'])) {
        session_destroy();
        header("Location: http://localhost/Group_Project/login.php");
        exit();
    }
}

$checkout->displayFinalCheck();
?>
</form>