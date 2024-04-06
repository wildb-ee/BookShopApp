<?php
class Model_Order extends Model {
    
    public function make_order($user_id, $cart_items, $promo_code = null) {
        $order_code = $this->generate_order_code();
        $order_date = date('Y-m-d');
        $estimated_delivery_date = date('Y-m-d', strtotime($order_date . ' + 7 days'));
        $status_id = 1;
        $total_amount = 0;
    
        $address_query = "SELECT CONCAT(street, ', ', city) AS delivery_address
                          FROM addresses
                          WHERE user_id = :user_id
                          ORDER BY address_id ASC
                          LIMIT 1";
        $address_params = [':user_id' => $user_id];
        $user_address = $this->db->getAll($address_query, $address_params);
        $delivery_address = $user_address[0]['delivery_address'] ?? '';
    
        if ($promo_code) {
            $promo_code_details = $this->get_promo_code_details($promo_code);
            if ($promo_code_details) {
                $cart_items = $this->apply_promo_code_to_cart($cart_items, $promo_code_details);
            }
        }
    
        foreach ($cart_items as $item) {
            $total_amount += $item['price'] * $item['quantity'];
        }
    
        $query = "INSERT INTO orders (order_code, estimated_delivery_date, status_id, total_amount, user_id, delivery_address) 
                  VALUES (:order_code, :estimated_delivery_date, :status_id, :total_amount, :user_id, :delivery_address)";
        $params = [
            ':order_code' => $order_code,
            ':estimated_delivery_date' => $estimated_delivery_date,
            ':status_id' => $status_id,
            ':total_amount' => $total_amount,
            ':user_id' => $user_id,
            ':delivery_address' => $delivery_address
        ];
        $order_id = $this->db->insert($query, $params);
    
        foreach ($cart_items as $item) {
            $this->create_order_detail($order_id, $item['book_id'], $item['quantity'], $item['price']);
        }
    
        $transaction_data = $_SESSION['transaction'];
        $this->save_transaction($transaction_data["last_four_digits"], $transaction_data["first_name"], $transaction_data["last_name"], $total_amount, $order_id);
    
        return true;
    }

    public function get_promo_code_details($promo_code) {
        $query = "SELECT * FROM promo_codes WHERE promo_code = :promo_code AND expiration_date >= CURDATE()";
        $params = [':promo_code' => $promo_code];
        $promo_code_details = $this->db->getOne($query, $params);
    
        
        if ($promo_code_details) {
            return $promo_code_details;
        } else {
            return null; 
        }
    }


    public function apply_promo_code_to_cart($cart_items, $promo_code_details) {
        $discounted_cart_items = [];
    
        foreach ($cart_items as $item) {
            if (!$promo_code_details['applicable_book_type'] || $item['book_type'] == $promo_code_details['applicable_book_type']) {
                $discounted_price = $item['price'] * (1 - ($promo_code_details['discount'] / 100));
                $discounted_price = max(0, $discounted_price);
                $item['price'] = $discounted_price;
                $discounted_cart_items[] = $item;
            } else {
                $discounted_cart_items[] = $item;
            }
        }
    
        return $discounted_cart_items;
    }


    public function validate_promo_code($promo_code) {
        $query = "SELECT * FROM promo_codes WHERE promo_code = :promo_code AND expiration_date >= CURDATE()";
        $params = [':promo_code' => $promo_code];
        $result = $this->db->getOne($query, $params);
        
        if ($result) {
            return $result;
        } else {
            return null;
        }
    }


    private function generate_order_code() {
        return 'ORDER_' . uniqid();
    }

    private function create_order_detail($order_id, $book_id, $quantity, $price) {
        $query = "INSERT INTO order_details (order_id, book_id, quantity, price) VALUES (:order_id, :book_id, :quantity, :price)";
        $params = [':order_id' => $order_id, ':book_id' => $book_id, ':quantity' => $quantity, ':price' => $price];
        $this->db->insert($query, $params);
    }

    private function save_transaction($last_four_digits, $first_name, $last_name, $total_amount, $order_id)
    {
        $query = "INSERT INTO transactions (last_four_digits, first_name, last_name, paid_amount, order_id) VALUES (:last_four_digits, :first_name, :last_name, :total_amount, :order_id)";
        $params = array(
            ':last_four_digits' => $last_four_digits,
            ':first_name' => $first_name,
            ':last_name' => $last_name,
            ':total_amount' => $total_amount,
            ':order_id' => $order_id
        );
    
        return $this->db->insert($query, $params);
    }

    public function get_all_user_orders($user_id) {
        $query = "SELECT o.order_id, o.order_code, o.estimated_delivery_date, 
                         o.manager_comment, o.delivery_address, o.total_amount, 
                         u.username, u.first_name, u.last_name, u.phone, 
                         os.status_name
                  FROM orders o
                  INNER JOIN users u ON o.user_id = u.user_id
                  INNER JOIN order_statuses os ON o.status_id = os.status_id
                  WHERE o.user_id = :user_id";
        $params = [':user_id' => $user_id];
        return $this->db->getAll($query, $params);
    }

    public function change_delivery_status($order_id, $new_status_name) {
        $query = "SELECT status_id FROM order_statuses WHERE status_name = :status_name";
        $params = [':status_name' => $new_status_name];
        $status_id = $this->db->getOne($query, $params)['status_id'];
        

        if (!$status_id) {
            return false;
        }
    
        $query = "UPDATE orders SET status_id = :status_id WHERE order_id = :order_id";
        $params = [':status_id' => $status_id, ':order_id' => $order_id];
        return $this->db->update($query, $params);
    }


    public function update_order_details($order_id, $book_id, $new_quantity) {
        $query = "UPDATE order_details SET quantity = :quantity WHERE order_id = :order_id AND book_id = :book_id";
        $params = [':quantity' => $new_quantity, ':order_id' => $order_id, ':book_id' => $book_id];
        return $this->db->update($query, $params);
    }


    public function change_delivery_address($order_id, $new_address) {
        $query = "UPDATE orders SET delivery_address = :delivery_address WHERE order_id = :order_id";
        $params = [':delivery_address' => $new_address, ':order_id' => $order_id];
        return $this->db->update($query, $params);
    }


    public function cancel_order($order_id) {
        $query = "DELETE FROM orders WHERE order_id = :order_id";
        $params = [':order_id' => $order_id];
        $this->db->delete($query, $params);
    }

    public function get_all_orders() {
        $query = "SELECT o.order_id, o.order_code, o.estimated_delivery_date, 
                         o.manager_comment, o.delivery_address, o.total_amount, 
                         u.username, u.first_name, u.last_name, u.phone, u.user_id, 
                         os.status_name
                  FROM orders o
                  INNER JOIN users u ON o.user_id = u.user_id
                  INNER JOIN order_statuses os ON o.status_id = os.status_id";
        return $this->db->getAll($query);
    }


    public function get_all_addresses() {
        $query = "SELECT * FROM addresses";
        return $this->db->getAll($query);
    }


    public function get_order_details() {
        $query = "SELECT od.order_id, od.book_id, od.quantity, od.price, 
                         b.book_name, b.book_description, 
                         CONCAT(a.first_name, ' ', a.last_name) AS author_name
                  FROM order_details od
                  INNER JOIN books b ON od.book_id = b.book_id
                  INNER JOIN authors a ON b.author_id = a.author_id";
        return $this->db->getAll($query);
    }

    public function delete_order_detail($order_id, $book_id) {
        $query = "DELETE FROM order_details WHERE order_id = :order_id AND book_id = :book_id";
        $params = [':order_id' => $order_id, ':book_id' => $book_id];
        return $this->db->delete($query, $params);
    }



    public function get_orders_in_delivery() {
        
        $query = "SELECT COUNT(*) AS count FROM orders WHERE status_id = (SELECT status_id FROM order_statuses WHERE status_name = 'Shipped')";
        $result = $this->db->getOne($query);
        if ($result === false) {
            throw new Exception("Error executing query");
        }
    
        if (!isset($result)) {
            return 0; 
        }
    
        return $result["count"];
    }
    
    public function get_orders_delivered() {
        
        $query = "SELECT COUNT(*) AS count FROM orders WHERE status_id = (SELECT status_id FROM order_statuses WHERE status_name = 'Delivered')";
        $result = $this->db->getOne($query);
        if ($result === false) {
            throw new Exception("Error executing query");
        }
    
        if (!isset($result)) {
            return 0; 
        }
    
        return $result["count"];
    }
    
    public function get_orders_canceled() {
        
        $query = "SELECT COUNT(*) AS count FROM orders WHERE status_id = (SELECT status_id FROM order_statuses WHERE status_name = 'Canceled')";
        $result = $this->db->getOne($query);
        

        if ($result === false) {
            throw new Exception("Error executing query");
        }
    
        if (!isset($result)) {
            return 0; 
        }
    
        return $result["count"];
    }

    public function get_orders_made_today() {
        $query = "SELECT COUNT(*) AS count FROM orders WHERE DATE(created_at) = CURDATE()";
        $result = $this->db->getOne($query);
        if ($result === false) {
            throw new Exception("Error executing query");
        }
    
        if (!isset($result)) {
            return 0; 
        }
    
        return $result["count"];
    }

    public function get_average_order_amount_today() {
        $query = "SELECT AVG(total_amount) AS average FROM orders WHERE DATE(created_at) = CURDATE()";
        $result = $this->db->getOne($query);
    
        if ($result === false) {
            throw new Exception("Error executing query");
        }
    
        if (!isset($result)) {
            return 0; 
        }
    
        return $result["average"];
    }


}
?>
