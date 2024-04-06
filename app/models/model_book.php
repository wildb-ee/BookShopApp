<?php
class Model_Book extends Model {
    
    public function add_book($book_code, $book_name, $book_description, $price, $stock_quantity, $book_type, $author_id) {
        $query = "INSERT INTO books (book_code, book_name, book_description, price, stock_quantity, book_type, author_id) 
                  VALUES (:book_code, :book_name, :book_description, :price, :stock_quantity, :book_type, :author_id)";
        $params = array(
            ':book_code' => $book_code,
            ':book_name' => $book_name,
            ':book_description' => $book_description,
            ':price' => $price,
            ':stock_quantity' => $stock_quantity,
            ':book_type' => $book_type,
            ':author_id' => $author_id
        );
        $this->db->insert($query, $params);
    }
    
    public function update_book($book_id, $book_code, $book_name, $book_description, $price, $stock_quantity, $book_type, $author_id) {
        $query = "UPDATE books 
                  SET book_code = :book_code, book_name = :book_name, book_description = :book_description, 
                      price = :price, stock_quantity = :stock_quantity, book_type = :book_type, author_id = :author_id
                  WHERE book_id = :book_id";
        $params = array(
            ':book_id' => $book_id,
            ':book_code' => $book_code,
            ':book_name' => $book_name,
            ':book_description' => $book_description,
            ':price' => $price,
            ':stock_quantity' => $stock_quantity,
            ':book_type' => $book_type,
            ':author_id' => $author_id
        );
        $this->db->update($query, $params);
    }
    
    public function delete_book($book_id) {
        $query = "DELETE FROM books WHERE book_id = :book_id";
        $params = array(':book_id' => $book_id);
        $this->db->delete($query, $params);
    }
    
    public function get_book_by_id($book_id) {
        $query = "SELECT * FROM books WHERE book_id = :book_id";
        $params = array(':book_id' => $book_id);
        $book = $this->db->getRow($query, $params);
        return $book;
    }
    
    public function get_all_books() {
        $query = "SELECT * FROM books";
        $books = $this->db->getAll($query);
        return $books;
    }

    public function search_books($find, $author_first_name, $author_last_name, $genre, $price_min, $price_max) {
        $query = "SELECT b.*, a.first_name as author_first_name, a.last_name as author_last_name FROM books b JOIN authors a ON b.author_id = a.author_id WHERE 1";

        if (!empty($find)) {
            $query .= " AND (b.book_name LIKE '%$find%' OR b.book_description LIKE '%$find%' OR a.first_name LIKE '%$find%' OR a.last_name LIKE '%$find%')";
        }
        if (!empty($author_first_name)) {
            $query .= " AND a.first_name LIKE '%$author_first_name%'";
        }
        if (!empty($author_last_name)) {
            $query .= " AND a.last_name LIKE '%$author_last_name%'";
        }
        if (!empty($genre)) {
            $query .= " AND b.book_type = '$genre'";
        }
        if (!empty($price_min)) {
            $query .= " AND b.price >= $price_min";
        }
        if (!empty($price_max)) {
            $query .= " AND b.price <= $price_max";
        }

        $search_results = $this->db->getAll($query);

        return $search_results;
    }

}