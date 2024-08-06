<?php

class Order {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    public function createOrder($user_id) {
        date_default_timezone_set('America/New_York'); 
        $date = date("Y-m-d");

        $m_id_query = "SELECT m.m_id, COUNT(C.quantity) AS quantity 
                       FROM mobile M
                       JOIN cart C ON m.m_id = C.m_id 
                       WHERE C.u_id = ? 
                       GROUP BY C.m_id";
        
        if ($stmt = $this->db->prepare($m_id_query)) {
            $stmt->bind_param('i', $user_id);
            $stmt->execute();
            $m_id_result = $stmt->get_result();

            $o_id = rand(10000, 99999); 
            
            // Insert into orders and update mobile availability
            while ($row = $m_id_result->fetch_assoc()) {
                $m_id = $row['m_id'];
                $quantity = $row['quantity'];
                
                // Insert into orders
                $insert_query = "INSERT INTO orders (order_date, quantity, m_id, u_id, o_id) VALUES (?, ?, ?, ?, ?)";
                if ($stmt_insert = $this->db->prepare($insert_query)) {
                    $stmt_insert->bind_param('siiii', $date, $quantity, $m_id, $user_id, $o_id);
                    $stmt_insert->execute();
                    $stmt_insert->close();
                }
                
                // Update mobile availability
                $update_query = "UPDATE mobile SET availability = availability - ? WHERE m_id = ?";
                if ($stmt_update = $this->db->prepare($update_query)) {
                    $stmt_update->bind_param('ii', $quantity, $m_id);
                    $stmt_update->execute();
                    $stmt_update->close();
                }
            }

            // Insert into orders_details
            $oid_query = "INSERT INTO orders_details (m_id, o_id, p_name, price, order_date, quantity, u_id)
                          SELECT M.m_id, O.o_id, M.model_name, (M.price * O.quantity) AS price, O.order_date, O.quantity, O.u_id
                          FROM mobile M
                          JOIN orders O ON M.m_id = O.m_id
                          WHERE O.o_id = ?";
                          
            if ($stmt_oid = $this->db->prepare($oid_query)) {
                $stmt_oid->bind_param('i', $o_id);
                $stmt_oid->execute();
                $stmt_oid->close();
            }
            
            $stmt->close();
        }

        return $o_id;
    }

    public function getOrderDetails($order_id) {
        $query = "SELECT U.fname, U.lname, O.p_name, O.price, O.order_date, O.quantity, O.o_id
                  FROM orders_details O
                  JOIN user_ids U ON O.u_id = U.u_id
                  WHERE O.o_id = ?";
        
        if ($stmt = $this->db->prepare($query)) {
            $stmt->bind_param('i', $order_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if (!$result) {
                die(mysqli_error($this->db->getConnection()));
            }

            $order_details = [];
            while ($row = $result->fetch_assoc()) {
                $order_details[] = $row;
            }

            $stmt->close();
        }

        return $order_details;
    }

    private function getCartItems($user_id) {
        $query = "SELECT m.m_id, m.model_name, m.price * C.quantity AS total_price, C.quantity
                  FROM mobile m
                  JOIN cart C ON m.m_id = C.m_id
                  WHERE C.u_id = ?
                  GROUP BY C.m_id";
        
        if ($stmt = $this->db->prepare($query)) {
            $stmt->bind_param('i', $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if (!$result) {
                die(mysqli_error($this->db->getConnection()));
            }

            $cart_items = [];
            while ($row = $result->fetch_assoc()) {
                $cart_items[] = $row;
            }

            $stmt->close();
        }

        return $cart_items;
    }
}
?>
