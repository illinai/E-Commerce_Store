USE vpanagsa;

-- Insert Users
INSERT INTO users (first_name, last_name, email, password, role) VALUES
('John', 'Doe', 'john.doe@example.com', '$2y$10$eImiTXuWVxfM37uY4JANjQ==', 'normal'), -- password: password1
('Jane', 'Smith', 'jane.smith@example.com', '$2y$10$eImiTXuWVxfM37uY4JANjQ==', 'admin'); -- password: password1

-- Insert Products
INSERT INTO products (name, description, price, image_url) VALUES
('Handmade Necklace', 'A beautiful handmade necklace.', 29.99, 'imgs/necklace.jpg'),
('Wooden Toy', 'A handcrafted wooden toy.', 19.99, 'imgs/toy.jpg');

-- Insert Orders
INSERT INTO orders (user_id, total) VALUES
(1, 49.98),
(2, 19.99);

-- Insert Order Items
INSERT INTO order_items (order_id, product_id, quantity, price) VALUES
(1, 1, 1, 29.99),
(1, 2, 1, 19.99),
(2, 2, 1, 19.99);