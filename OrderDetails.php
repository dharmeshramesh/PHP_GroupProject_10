<?php
require_once 'Database.php'; // Assumes you have a Database class for connection
require_once 'Order.php'; // Assumes you have an Order class for order processing

class OrderDetails {
    private $db;
    private $order;

    public function __construct() {
        $this->db = new Database();
        $this->order = new Order($this->db);
    }

    public function displayOrderDetails($order_id) {
        $details = $this->order->getOrderDetails($order_id);

        echo "<h2 align='center'> ORDER DETAILS </h2>
            <table border='1' bgcolor='#00B8D4' align='center' width='50%' style='margin-top:50px; margin-left:50px;'>
            <tr>
            <td width='20%' align='center'><strong>FIRST NAME</strong></td>
            <td width='20%' align='center'><strong>LAST NAME</strong></td>
            <td width='20%' align='center'><strong>PRODUCT NAME</strong></td>
            <td width='10%' align='center'><strong>QUANTITY</strong></td>
            <td width='10%' align='center'><strong>PRICE</strong></td>
            <td width='10%' align='center'><strong>ORDER DATE</strong></td>
            </tr>";

        foreach ($details as $detail) {
            echo "
            <tr bgcolor='#FFF9C4'>
            <td width='20%' align='center'><strong>{$detail['fname']}</strong></td>
            <td width='20%' align='center'><strong>{$detail['lname']}</strong></td>
            <td width='20%' align='center'><strong>{$detail['p_name']}</strong></td>
            <td width='10%' align='center'><strong>{$detail['quantity']}</strong></td>
            <td width='10%' align='center'><strong>{$detail['price']}</strong></td>
            <td width='20%' align='center'><strong>{$detail['order_date']}</strong></td>
            </tr>";
        }
    }
}
$orderDetails = new OrderDetails();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitvalue'])) {
    $order_id = $_POST['o_id'];
    $orderDetails->displayOrderDetails($order_id);
}
?>
