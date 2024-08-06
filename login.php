<?php
include_once 'Database.php';
include_once 'User.php';

$db = new Database();
$user = new User($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_name = htmlspecialchars($_POST['user_name'] ?? '', ENT_QUOTES, 'UTF-8');
    $password = htmlspecialchars($_POST['password'] ?? '', ENT_QUOTES, 'UTF-8');

    if (!empty($user_name) && !empty($password)) {
        if ($user->authenticate($user_name, $password)) {
            header('Location: mobile.php');
            exit();
        } else {
            $error_message = "Invalid Username or Password";
        }
    } else {
        $error_message = "Please enter all fields";
    }
}
?>

<html>
<head>
    <link href="login_style.css" rel='stylesheet' type='text/css' />
    <title>Login</title>
</head>
<body>
    <h1 align="center"> TECH SCOPE</h1>
    <form method='post'>
        <div class="form-group">
            <label for="user_name">Username</label>
            <input type='text' name='user_name' id="user_name" value='<?php echo htmlspecialchars($user_name ?? "", ENT_QUOTES, 'UTF-8'); ?>' />
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type='password' name='password' id="password" value='<?php echo htmlspecialchars($password ?? "", ENT_QUOTES, 'UTF-8'); ?>' />
        </div>
        <div class="form-group">
            <input type='submit' name='submitvalue' value='Sign In' />
        </div>
        <div class='new'>
            <a href='index.php'>New User?</a>
        </div>
        <?php if (!empty($error_message)): ?>
            <div class='myDiv'><strong><?php echo $error_message; ?></strong></div>
        <?php endif; ?>
    </form>
</body>
</html>
