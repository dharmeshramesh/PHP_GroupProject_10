<?php
class User {
    private $db;
    private $user_name;
    private $email;
    private $user_id;

    public function __construct(Database $db) {
        $this->db = $db;
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->user_name = $_SESSION['user_name'] ?? '';
        $this->email = $_SESSION['email'] ?? '';
        $this->setUserId();
    }

    private function setUserId() {
        $query = "SELECT u_id FROM user_ids WHERE user_name = '{$this->user_name}'";
        $result = $this->db->query($query);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $this->user_id = $row['u_id'];
        }
    }

    public function authenticate($user_name, $password) {
       
        $query = "SELECT email FROM user_ids WHERE user_name = ? AND password = ?";
        $email = null;

        if ($stmt = $this->db->prepare($query)) {
            
            $stmt->bind_param('ss', $user_name, $password);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows === 1) {
                $stmt->bind_result($email);
                $stmt->fetch();
                $_SESSION['user_name'] = $user_name;
                $_SESSION['email'] = $email;
                $stmt->close();
                
                return true;
            }
            $stmt->close();
        }
        
        return false;
    }
    

    public function register($fname, $lname, $user_name, $email, $phone, $password, $address) {
        // Data validation (moved here for encapsulation)
        if (!preg_match("/^[a-zA-Z]*$/", $fname)) {
            throw new Exception("Only letters and white space allowed in First Name.");
        }
        if (!preg_match("/^[a-zA-Z]*$/", $lname)) {
            throw new Exception("Only letters and white space allowed in Last Name.");
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format.");
        }
        if (!preg_match('/^[0-9]{10}+$/', $phone)) {
            throw new Exception("Phone number should be of 10 digits.");
        }

        // Generate user ID
        $u_id = rand(10000, 99999);

        // Insert user data into the database
        $query = "INSERT INTO user_ids (u_id, fname, lname, email, user_name, address, phone_no, password) 
              VALUES ('$u_id', '$fname', '$lname', '$email', '$user_name', '$address', '$phone', '$password')";

        $result = $this->db->query($query);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function isLoggedIn() {
        return isset($_SESSION['user_name']);
    }

    public function getUserName() {
        return $this->user_name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getUserId() {
        return $this->user_id;
    }

    public function logout() {
        session_destroy();
        header("Location: login.php");
        exit();
    }
}
?>
