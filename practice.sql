DROP DATABASE IF EXISTS bookshop_db;
CREATE DATABASE bookshop_db;
USE bookshop_db;

CREATE TABLE order_statuses (
    status_id INT AUTO_INCREMENT PRIMARY KEY,
    status_name VARCHAR(50)
);


CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE,
    email VARCHAR(255) UNIQUE,
    phone VARCHAR(20),
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    password VARCHAR(255),
    is_admin BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE addresses (
    address_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    street VARCHAR(255),
    city VARCHAR(100),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);


CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    order_code VARCHAR(50) UNIQUE,
    estimated_delivery_date DATE,
    status_id INT,
    manager_comment TEXT,
    delivery_address VARCHAR(255),
    total_amount DECIMAL(10, 2),
    user_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (status_id) REFERENCES order_statuses(status_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE transactions (
    transaction_id INT AUTO_INCREMENT PRIMARY KEY,
    last_four_digits VARCHAR(4) NOT NULL,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    paid_amount DECIMAL(10,2),
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    order_id INT,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE
);


CREATE TABLE authors(
    author_id INT AUTO_INCREMENT PRIMARY KEY,
    author_code VARCHAR(50) UNIQUE,
    first_name VARCHAR(100),
    last_name  VARCHAR(100)
);



CREATE TABLE books(
    book_id INT AUTO_INCREMENT PRIMARY KEY,
    book_code VARCHAR(50) UNIQUE,
    book_name VARCHAR(100),
    book_image VARCHAR(100),
    book_description TEXT,
    price DECIMAL(10, 2),
    stock_quantity INT,
    book_type VARCHAR(50),
    author_id INT,
    FOREIGN KEY (author_id) REFERENCES authors(author_id) ON DELETE CASCADE
);


CREATE TABLE order_details (
    order_id INT,
    book_id INT,
    quantity INT,
    price DECIMAL(10, 2),
    PRIMARY KEY (order_id, book_id),
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES books(book_id) ON DELETE CASCADE
);






CREATE TABLE actions (
    idAction INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100)
);

CREATE TABLE logs (
    userId INT,
    idAction INT,
    datetime DATETIME,
    FOREIGN KEY (idAction) REFERENCES actions(idAction) ON DELETE CASCADE
);





CREATE TABLE cart (
    cart_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    book_id INT NOT NULL,
    quantity INT DEFAULT 0,
    price_total INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES books(book_id) ON DELETE CASCADE
);

CREATE TABLE feedback (
    message_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    email VARCHAR(255),
    phone VARCHAR(20),
    theme VARCHAR(255),
    message TEXT,
    date_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE promo_codes (
    promo_code_id INT AUTO_INCREMENT PRIMARY KEY,
    promo_code VARCHAR(50) UNIQUE,
    discount DECIMAL(5, 2) NOT NULL,
    applicable_book_type VARCHAR(50),  -- NULL if applies to the entire order
    expiration_date DATE
);



DELIMITER //

CREATE TRIGGER check_user_id_unchanged BEFORE UPDATE ON users
FOR EACH ROW
BEGIN
    IF OLD.user_id != NEW.user_id THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Updating user_id is not allowed';
    END IF;
END;
//

DELIMITER ;






DELIMITER $$

CREATE TRIGGER update_cart_total
BEFORE UPDATE ON cart
FOR EACH ROW
BEGIN
    DECLARE new_price_each DOUBLE;
    DECLARE new_price_total DOUBLE;
    
    IF OLD.quantity <> NEW.quantity THEN
        SET new_price_each = (SELECT price FROM books WHERE book_id = NEW.book_id);
        SET new_price_total = new_price_each * NEW.quantity;
        
        SET NEW.price_total = new_price_total;
    END IF;
END $$

DELIMITER ;

DELIMITER $$

CREATE TRIGGER book_price
BEFORE INSERT on cart FOR EACH ROW 
BEGIN
 DECLARE price_each double;
    SET @price_each = (SELECT price FROM books WHERE book_id = NEW.book_id);
 SET NEW.price_total = @price_each * NEW.quantity;
END $$

DELIMITER ;


INSERT INTO authors (author_code, first_name, last_name) 
VALUES ('A001', 'F. Scott', 'Fitzgerald'),
       ('A002', 'Harper', 'Lee'),
       ('A003', 'George', 'Orwell'),
       ('A004', 'Jane', 'Austen'),
       ('A005', 'J.D.', 'Salinger'),
       ('A006', 'Fyodor', 'Dostoevsky'),
       ('A007', 'Leo', 'Tolstoy'),
       ('A008', 'J.R.R.', 'Tolkien'),
       ('A009', 'Herman', 'Melville'),
       ('A010', 'Charles', 'Dickens');


INSERT INTO books (book_code, book_name, book_description, price, stock_quantity, book_type, author_id, book_image) 
VALUES ('B001', 'The Great Gatsby', 'A novel by F. Scott Fitzgerald', 12.99, 100, 'Fiction', 1, 'https://via.placeholder.com/300'),
       ('B002', 'To Kill a Mockingbird', 'A novel by Harper Lee', 14.99, 80, 'Fiction', 2, 'https://via.placeholder.com/300'),
       ('B003', '1984', 'A dystopian novel by George Orwell', 10.99, 120, 'Dystopian', 3, 'https://via.placeholder.com/300'),
       ('B004', 'Pride and Prejudice', 'A romantic novel by Jane Austen', 11.99, 90, 'Romance', 4, 'https://via.placeholder.com/300'),
       ('B005', 'The Catcher in the Rye', 'A coming-of-age novel by J.D. Salinger', 9.99, 110, 'Coming-of-age', 5,'https://via.placeholder.com/300'),
       ('B006', 'Crime and Punishment', 'A novel by Fyodor Dostoevsky', 13.99, 70, 'Fiction', 6,'https://via.placeholder.com/300'),
       ('B007', 'War and Peace', 'A historical novel by Leo Tolstoy', 15.99, 85, 'Historical', 7,'https://via.placeholder.com/300'),
       ('B008', 'The Hobbit', 'A fantasy novel by J.R.R. Tolkien', 11.99, 95, 'Fantasy', 8, 'https://via.placeholder.com/300'),
       ('B009', 'Moby-Dick', 'A novel by Herman Melville', 12.99, 75, 'Adventure', 9, 'https://via.placeholder.com/300'),
       ('B010', 'The Lord of the Rings', 'A fantasy novel by J.R.R. Tolkien', 19.99, 60, 'Fantasy', 8,'https://via.placeholder.com/300');

INSERT INTO actions (name) VALUES ('Form Submission');
INSERT INTO actions (name) VALUES ('Authentication');
INSERT INTO actions (name) VALUES ('Address Change');

INSERT INTO order_statuses (status_name) VALUES ('Pending');
INSERT INTO order_statuses (status_name) VALUES ('Processing');
INSERT INTO order_statuses (status_name) VALUES ('Shipped');
INSERT INTO order_statuses (status_name) VALUES ('Delivered');
INSERT INTO order_statuses (status_name) VALUES ('Canceled');


INSERT INTO promo_codes (promo_code, discount, applicable_book_type, expiration_date) VALUES ('SUMMER10', 10.00, 'Fiction', '2024-06-30');
INSERT INTO promo_codes (promo_code, discount, applicable_book_type, expiration_date) VALUES ('FALL15', 15.00, 'Non-Fiction', '2024-09-30');
INSERT INTO promo_codes (promo_code, discount, applicable_book_type, expiration_date) VALUES ('WINTER20', 20.00, NULL, '2024-12-31');
INSERT INTO promo_codes (promo_code, discount, applicable_book_type, expiration_date) VALUES ('SPRING25', 25.00, 'Children', '2025-03-31');
INSERT INTO promo_codes (promo_code, discount, applicable_book_type, expiration_date) VALUES ('BACKTOSCHOOL30', 30.00, NULL, '2025-09-30');
INSERT INTO promo_codes (promo_code, discount, applicable_book_type, expiration_date) VALUES ('HALLOWEEN40', 40.00, 'Horror', '2025-10-31');
INSERT INTO promo_codes (promo_code, discount, applicable_book_type, expiration_date) VALUES ('THANKSGIVING50', 50.00, NULL, '2025-11-30');
INSERT INTO promo_codes (promo_code, discount, applicable_book_type, expiration_date) VALUES ('CHRISTMAS60', 60.00, NULL, '2025-12-31');
INSERT INTO promo_codes (promo_code, discount, applicable_book_type, expiration_date) VALUES ('NEWYEAR70', 70.00, NULL, '2026-01-31');
INSERT INTO promo_codes (promo_code, discount, applicable_book_type, expiration_date) VALUES ('VALENTINE75', 75.00, 'Romance', '2026-02-28');
INSERT INTO promo_codes (promo_code, discount, applicable_book_type, expiration_date) VALUES ('EASTER80', 80.00, 'Religion', '2026-04-30');
INSERT INTO promo_codes (promo_code, discount, applicable_book_type, expiration_date) VALUES ('MOTHERSDAY90', 90.00, 'Biography', '2026-05-31');
INSERT INTO promo_codes (promo_code, discount, applicable_book_type, expiration_date) VALUES ('FATHERSDAY100', 100.00, NULL, '2026-06-30');
INSERT INTO promo_codes (promo_code, discount, applicable_book_type, expiration_date) VALUES ('BLACKFRIDAY110', 110.00, NULL, '2026-11-30');
INSERT INTO promo_codes (promo_code, discount, applicable_book_type, expiration_date) VALUES ('CYBERMONDAY120', 120.00, NULL, '2026-12-01');
INSERT INTO promo_codes (promo_code, discount, applicable_book_type, expiration_date) VALUES ('INDEPENDENCEDAY130', 130.00, NULL, '2027-07-04');
INSERT INTO promo_codes (promo_code, discount, applicable_book_type, expiration_date) VALUES ('LABORDAY140', 140.00, NULL, '2027-09-06');
INSERT INTO promo_codes (promo_code, discount, applicable_book_type, expiration_date) VALUES ('COLUMBUSDAY150', 150.00, NULL, '2027-10-11');
INSERT INTO promo_codes (promo_code, discount, applicable_book_type, expiration_date) VALUES ('HALLOWEEN160', 160.00, NULL, '2027-10-31');
INSERT INTO promo_codes (promo_code, discount, applicable_book_type, expiration_date) VALUES ('THANKSGIVING170', 170.00, NULL, '2027-11-25');
