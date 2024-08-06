<?php
include_once 'Database.php';
include_once 'User.php';

class Mobile {
    private $db;
    private $mobiles = [];

    public function __construct(Database $db) {
        $this->db = $db;
    }

    public function fetchMobilePhones($max_price = null) {
        $query = "SELECT brand, model_name, color, storage, display_size, price, ram, m_id, m.availability AS qty
                  FROM mobile m";

        if ($max_price) {
            $query .= " WHERE price <= ?";
        }

        if ($stmt = $this->db->prepare($query)) {
            if ($max_price) {
                $stmt->bind_param('d', $max_price);
            }
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $this->mobiles[] = $row;
            }

            $stmt->close();
        }
    }

    public function displayMobiles($user_id) {
        foreach ($this->mobiles as $mobile) {
            if ($mobile['qty'] > 0) {
                echo "
                    <div class='card'>
                        <img src='images/{$mobile['model_name']}.jpg' alt='{$mobile['brand']} {$mobile['model_name']}'>
                        <h3>{$mobile['brand']} {$mobile['model_name']}</h3>
                        <p>Color: {$mobile['color']}</p>
                        <p>Price: \${$mobile['price']}</p>
                        <p>RAM: {$mobile['ram']} GB</p>
                        <p>Storage: {$mobile['storage']} GB</p>
                        <p>Display: {$mobile['display_size']} inches</p>
                        <a href='?action=add_to_cart&m_id={$mobile['m_id']}&user_id=$user_id&qty=1'>Add to CART</a>
                    </div>
                ";
            }
        }
    }

    public function addToCart($mobile_id, $user_id, $quantity) {
        // Ensure quantity is a valid integer
        $quantity = (int)$quantity;
        $query = "INSERT INTO cart (quantity, m_id, u_id) VALUES (?, ?, ?)";

        if ($stmt = $this->db->prepare($query)) {
            $stmt->bind_param('iii', $quantity, $mobile_id, $user_id);
            $stmt->execute();
            $stmt->close();
        }
    }
}

$db = new Database();
$user = new User($db);
$mobile = new Mobile($db);

$max_price = null;
if (isset($_POST['filter'])) {
    $max_price = $_POST['max_price'];
}

if (isset($_GET['action']) && $_GET['action'] === 'add_to_cart') {
    $mobile_id = $_GET['m_id'];
    $user_id = $_GET['user_id'];
    $quantity = $_GET['qty'];
    $mobile->addToCart($mobile_id, $user_id, $quantity);

    header("Location: mobile.php");
    exit();
}

if (isset($_POST['logout'])) {
    $user->logout();
}

if (isset($_POST['ocart'])) {
    header("Location: cart.php");
    exit();
}
$mobile->fetchMobilePhones($max_price);
?>

<html>
<head>
    <link href="products_style.css" rel="stylesheet" type="text/css" />
    <title>MOBILE PHONES</title>
</head>
<body>
    <h1>TECH SCOPE</h1>
    <form method="post">
        <div class="button-container">
            <div>
                <input type="submit" class="button" name="ocart" value="CART"  />
                <input type="submit" class="button" name="logout" value="Logout" />
            </div>
        </div>
        <div class="myDiv">Welcome <?php echo $user->getUserName(); ?> <br> <?php echo $user->getEmail(); ?></div>
        <div class="filter-container">
            <label for="max_price">Filter by Price:</label>
            <select name="max_price" id="max_price">
                <option value="">Select Price</option>
                <option value="200">Up to $200</option>
                <option value="500">Up to $500</option>
                <option value="1000">Up to $1000</option>
            </select>
            <input type="submit" name="filter" value="Apply Filter">
        </div>
        <div class="container">
            <?php $mobile->displayMobiles($user->getUserId()); ?>
        </div>
    </form>
</body>
</html>
