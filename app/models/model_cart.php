<?php
class Model_Cart extends Model {
    
    public function add_to_cart($user_id, $book_id, $quantity) {

        $query = "SELECT * FROM cart WHERE user_id = :user_id AND book_id = :book_id";
        $params = array(':user_id' => $user_id, ':book_id' => $book_id);
        $existing_item = $this->db->getRow($query, $params);
        
        if ($existing_item) {

            $new_quantity = $existing_item['quantity'] + $quantity;
            $query = "UPDATE cart SET quantity = :quantity WHERE user_id = :user_id AND book_id = :book_id";
            $params = array(':quantity' => $new_quantity, ':user_id' => $user_id, ':book_id' => $book_id);
            $this->db->update($query, $params);
        } else {
           
            $query = "INSERT INTO cart (user_id, book_id, quantity) VALUES (:user_id, :book_id, :quantity)";
            $params = array(':user_id' => $user_id, ':book_id' => $book_id, ':quantity' => $quantity);
            $this->db->insert($query, $params);
        }
    }
    
    public function update_cart_item($user_id, $cart_id, $new_quantity) {
        $query = "UPDATE cart SET quantity = :quantity WHERE user_id = :user_id AND cart_id = :cart_id";
        $params = array(':quantity' => $new_quantity, ':user_id' => $user_id, ':cart_id' => $cart_id);
        $this->db->update($query, $params);        
    }
    
    public function get_cart_items($user_id) {
        $query = "SELECT c.*, b.book_name, b.price, b.book_description, b.book_image, b.book_type, a.first_name, a.last_name
                  FROM cart c
                  JOIN books b ON c.book_id = b.book_id
                  JOIN authors a ON b.author_id = a.author_id
                  WHERE c.user_id = :user_id";
        $params = array(':user_id' => $user_id);
        $cart_items = $this->db->getAll($query, $params);
        
        return $cart_items;
    }
    
    public function remove_from_cart($user_id, $cart_id) {
        $query = "DELETE FROM cart WHERE user_id = :user_id AND cart_id = :cart_id";
        $params = array(':user_id' => $user_id, ':cart_id' => $cart_id);
        $this->db->delete($query, $params);
    }
    
    public function clear_cart($user_id) {
        $query = "DELETE FROM cart WHERE user_id = :user_id";
        $params = array(':user_id' => $user_id);
        $this->db->delete($query, $params);
    }

}