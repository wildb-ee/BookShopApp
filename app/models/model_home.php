<?php
class Model_Home extends Model {
    
    public function suggest_books($is_authorized, $user_id = -1) {
        if ($is_authorized) {
            $cart_items = $this->get_cart_items($user_id);
            
            $genre_counts = array();
            foreach ($cart_items as $item) {
                $genre = $item['book_type'];
                if (isset($genre_counts[$genre])) {
                    $genre_counts[$genre]++;
                } else {
                    $genre_counts[$genre] = 1;
                }
            }
            
            $most_common_genre = $genre_counts ? array_search(max($genre_counts), $genre_counts) : null;
            
            $suggested_books = $most_common_genre ? $this->get_suggested_books($most_common_genre) : $this->get_random_books();
        } else {
            $suggested_books = $this->get_random_books();
        }
        
        return $suggested_books;
    }
    
    private function get_random_books() {
        $query = "SELECT * FROM books ORDER BY RAND() LIMIT 5";
        $suggested_books = $this->db->getAll($query);
        
        return $suggested_books;
    }
    
    private function get_cart_items($user_id) {
        $query = "SELECT c.*, b.book_name, b.price, b.book_description, b.book_image, b.book_type, a.first_name, a.last_name
                  FROM cart c
                  JOIN books b ON c.book_id = b.book_id
                  JOIN authors a ON b.author_id = a.author_id
                  WHERE c.user_id = :user_id";
        $params = array(':user_id' => $user_id);
        $cart_items = $this->db->getAll($query, $params);
        
        return $cart_items;
    }
    
    private function get_suggested_books($genre) {
        $query = "SELECT * FROM books WHERE book_type = :genre LIMIT 5";
        $params = array(':genre' => $genre);
        $suggested_books = $this->db->getAll($query, $params);
        
        return $suggested_books;
    }
}