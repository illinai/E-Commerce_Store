USE vpanagsa;
-- Insert Users
INSERT INTO users (first_name, last_name, email, password, role) VALUES
('John', 'Doe', 'john.doe@example.com', '$2y$10$eImiTXuWVxfM37uY4JANjQ==', 'normal'), -- password: password1
('Jane', 'Smith', 'jane.smith@example.com', '$2y$10$eImiTXuWVxfM37uY4JANjQ==', 'admin'); -- password: password1

-- Insert Products (note: added seller_id which was missing)
INSERT INTO products (name, description, price, image_url, seller_id, quantity, tags) VALUES
('Handmade Necklace', 'A beautiful handmade necklace.', 29.99, 'imgs/necklace.jpg', 1, 10, 'jewelry,handmade'),
('Wooden Toy', 'A handcrafted wooden toy.', 19.99, 'imgs/toy.jpg', 2, 15, 'toys,wooden');

-- Insert Orders (note: added seller_id which was missing)
INSERT INTO orders (user_id, seller_id, total, order_status) VALUES
(1, 2, 49.98, 'pending'),
(2, 1, 19.99, 'shipped');

-- Insert Order Items
INSERT INTO order_items (order_id, product_id, quantity, price) VALUES
(1, 1, 1, 29.99),
(1, 2, 1, 19.99),
(2, 2, 1, 19.99);