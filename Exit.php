<html>
<head>
<link href = " products_style.css" rel ="stylesheet" type ="text/css"/>
<title>Exit</title>
<body>

<h1 align="center"> TECH SCOPE </h1>

<h2 align = "center"> Your Order has been placed. Thank you so much for shopping </h2>

</head>
<form method='post'>
<?php
require_once 'Database.php'; // Assumes you have a Database class for connection
require_once 'Order.php'; // Assumes you have an Order class for order processing
require_once 'fpdf/fpdf.php'; // Ensure the path is correct to where FPDF is located
session_start();
class ExitPage {
    private $db;
    private $order;

    public function __construct() {
        $this->db = new Database();
        $this->order = new Order($this->db);
    }

    public function displayOrderDetails() {
        $order_id = $_SESSION['id'];
        $details = $this->order->getOrderDetails($order_id);

        $user_name=$email="";
	if(isset($_SESSION["user_name"]) && isset($_SESSION["email"]))
	{
		$user_name = $_SESSION['user_name'];
		$email = $_SESSION['email'];
	}
echo "<div align = 'right'><input type='submit' class = 'button' name='logout' value='Logout'>
<input type ='submit' class ='button' name = 'continue' value = 'Continue' />
<input type ='submit' class ='button' name = 'download_invoice' value = 'download_invoice' /></div>";

        echo "<h3 align='center'>Order_details</h3>
            <table>
            <tr>
            <td width='10%' align='center'><strong>Order_Id</strong></td>
            <td width='10%' align='center'><strong>Name</strong></td>
            <td width='10%' align='center'><strong>Price </strong></td>
            <td width='10%' align='center'><strong>Order_date</strong></td>
            </tr>";

        foreach ($details as $detail) {
            echo "
            <tr>
            <td width='10%' align='center'><strong>{$detail['o_id']}</strong></td>
            <td width='10%' align='center'><strong>{$detail['p_name']}</strong></td>
            <td width='10%' align='center'><strong>{$detail['price']}</strong></td>
            <td width='10%' align='center'><strong>{$detail['order_date']}</strong></td>
            </tr>";
        }
    }
    public function generatePDFInvoice($order_id) {
        $details = $this->order->getOrderDetails($order_id);
    
        // Create new PDF document
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
    
        // Title
        $pdf->Cell(0, 10, 'Invoice', 0, 1, 'C');
        $pdf->SetFont('Arial', '', 12);
    
        // Order details
        $pdf->Cell(0, 10, "Order ID: $order_id", 0, 1);
        $pdf->Cell(0, 10, "Date: " . date("Y-m-d"), 0, 1);
        $pdf->Ln();
    
        // Table header
        $pdf->Cell(50, 10, 'Product Name', 1);
        $pdf->Cell(30, 10, 'Price', 1);
        $pdf->Cell(30, 10, 'Quantity', 1);
        $pdf->Cell(30, 10, 'Total', 1);
        $pdf->Cell(30, 10, 'Order Date', 1);
        $pdf->Ln();
    
        // Table data
        foreach ($details as $detail) {
            $pdf->Cell(50, 10, $detail['p_name'], 1);
            $pdf->Cell(30, 10, $detail['price'], 1);
            $pdf->Cell(30, 10, $detail['quantity'], 1);
            $pdf->Cell(30, 10, $detail['price'] * $detail['quantity'], 1); // Total price
            $pdf->Cell(30, 10, $detail['order_date'], 1);
            $pdf->Ln();
        }
    
        // Ensure the invoices directory exists
        if (!is_dir('invoices')) {
            mkdir('invoices', 0777, true); // Create the directory if it does not exist
        }
    
        // Save the PDF
        $file_path = "invoices/invoice_$order_id.pdf";
        $pdf->Output("F", $file_path);
    
        // Set the global file path variable
        $GLOBALS['file_path'] = $file_path;
    }
}



$exitPage = new ExitPage();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['continue'])) {
        header("Location: http://localhost/GroupProject/mobile.php");
        exit();
    } elseif (isset($_POST['logout'])) {
        session_destroy();
        header("Location: http://localhost/GroupProject/login.php");
        exit();
    } elseif (isset($_POST['download_invoice'])) {
        $exitPage->generatePDFInvoice($_SESSION['id']);
        if (isset($GLOBALS['file_path'])) {
            $file_path = $GLOBALS['file_path'];
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="'.basename($file_path).'"');
            readfile($file_path);
            exit();
        }
    }
}

$exitPage->displayOrderDetails();
?>
</form>
