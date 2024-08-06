<?php

class Cart {
    private $db;
    private $user_id;

    public function __construct(Database $db, $user_id) {
        $this->db = $db;
        $this->user_id = $user_id;
    }

    public function displayCart() {
        $query = "SELECT M.brand, M.model_name, M.display_size, M.color, M.storage, M.ram, M.price, M.m_id, COUNT(C.quantity) as quantity
                  FROM Mobile M, cart C
                  WHERE M.m_id = C.m_id AND c.u_id={$this->user_id}
                  GROUP BY C.quantity, M.model_name";

        $result = $this->db->query($query);

        while ($row = $result->fetch_assoc()) {
            extract($row);
            echo "
                <tr>
                    <td width='20%' align='center'><strong>$brand</strong></td>
                    <td width='20%' align='center'><strong>$model_name</strong></td>
                    <td width='10%' align='center'><strong>$color</strong></td>
                    <td width='20%' align='center'><strong>$price</strong></td>
                    <td width='10%' align='center'><strong>$storage</strong></td>
                    <td width='10%' align='center'><strong>$display_size</strong></td>
                    <td width='10%' align='center'><strong>$ram</strong></td>
                    <td width='10%' align='center'><strong>$quantity</strong></td>
                    <td width='10%' align='center'><a href='?action=delete&m_id=$m_id'>Delete</a></td>
                </tr>";
        }
    }

    public function deleteItem($product_id) {
        $query = "DELETE FROM cart WHERE m_id='$product_id' AND u_id='{$this->user_id}'";
        $this->db->query($query);
    }

    public function getUserId($userName) {
        $query = "SELECT u_id FROM user_ids WHERE user_name = ?";
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->bind_param("s", $userName);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return $row['u_id'];
        }
        return null; 
    }

    public function getCartItems($userId) {
        $query = "SELECT M.model_name, m.price * C.quantity AS total_price, C.quantity
              FROM mobile M
              JOIN cart C ON M.m_id = C.m_id
              WHERE C.u_id = ?
              GROUP BY M.model_name, C.quantity";

        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $items = [];
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
        return $items;
    }

    public function clearCart($userId) {
        $query = "DELETE FROM cart WHERE u_id = ?";
        
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->bind_param("i", $userId);
    
        if ($stmt->execute()) {
            return true; 
        } else {
            return false;
        }
    }
    
}
?>
