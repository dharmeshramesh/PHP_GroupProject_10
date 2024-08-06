<html>
<head>
<link href="login_style.css" rel='stylesheet' type='text/css' />
<title>Sign Up</title>
</head>

<body>
<h1 align="center"> TECH SCOPE </h1>
<?php require_once 'Database.php';
require_once 'user.php';?>
<?php
$fname=$lname=$email=$phone_no=$submit=$password=$address=$user_name="";?>
    <form method='post'>
        <div class="form-group">
            <label for="fname">First Name</label>
            <input type='text' name='fname' id="fname" value='<?php echo $fname; ?>' />
        </div>
        <div class="form-group">
            <label for="lname">Last Name</label>
            <input type='text' name='lname' id="lname" value='<?php echo $lname; ?>' />
        </div>
        <div class="form-group">
            <label for="user_name">Username</label>
            <input type='text' name='user_name' id="user_name" value='<?php echo $user_name; ?>' />
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type='text' name='email' id="email" value='<?php echo $email; ?>' />
        </div>
        <div class="form-group">
            <label for="phone_no">Phone no.:</label>
            <input type='text' name='phone_no' id="phone_no" value='<?php echo $phone_no; ?>' />
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type='password' name='password' id="password" value='<?php echo $password; ?>' />
        </div>
        <div class="form-group">
            <label for="address">Address:</label>
            <input type='text' name='address' id="address" value='<?php echo $address; ?>' />
        </div>
        <input type='submit' name='submitvalue' value='Sign Up' />
        <div class='new1'><a href='login.php'>Already Have an Account?</a></div>
<?php
if (isset($_POST['submitvalue'])) {
    if (!empty($_POST['fname']) && !empty($_POST['lname']) && !empty($_POST['user_name']) && 
        !empty($_POST['email']) && !empty($_POST['phone_no']) && !empty($_POST['password']) && 
        !empty($_POST['address'])) {

        $db = new Database();  // Assuming you have a Database class that handles connections
        $user = new User($db);

        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $user_name = $_POST['user_name'];
        $email = $_POST['email'];
        $phone = $_POST['phone_no'];
        $password = $_POST['password'];
        $address = $_POST['address'];

        try {
            if ($user->register($fname, $lname, $user_name, $email, $phone, $password, $address)) {
                header("Location: login.php");
            }
        } catch (Exception $e) {
            echo "<div class='mydiv'><b>" . $e->getMessage() . "</b></div>";
        }
    } else {
        echo "<div class='mydiv'><b>Please enter all fields</b></div>";
    }
}

if (isset($_POST['login'])) {
    header("Location: login.php");
}

?>

</body>
</html>


