USE vpanagsa;

-- Insert Users
INSERT INTO users (first_name, last_name, email, password, role) VALUES
('John', 'Doe', 'john.doe@example.com', '$2y$10$eImiTXuWVxfM37uY4JANjQ==', 'normal'), -- password: password1
('Jane', 'Smith', 'jane.smith@example.com', '$2y$10$eImiTXuWVxfM37uY4JANjQ==', 'admin'); -- password: password1

-- Insert Categories first (at least one for products)
INSERT INTO categories (category_name) VALUES
('General'),
('Handmade'),
('Toys');

-- Fixed - changed image_url to image, since the schema uses image
INSERT INTO products (seller_id, name, description, price, category_id) VALUES
(1, 'Handmade Necklace', 'A beautiful handmade necklace.', 29.99, 2),
(1, 'Wooden Toy', 'A handcrafted wooden toy.', 19.99, 3);

-- Insert Orders (need ship entries first)
INSERT INTO ship (user_id, full_name, address, city, state, zip_code, country, phone) VALUES
(1, 'John Doe', '123 Main St', 'Anytown', 'CA', '12345', 'USA', '555-123-4567'),
(2, 'Jane Smith', '456 Oak Ave', 'Somewhere', 'NY', '67890', 'USA', '555-987-6543');

-- Now we can insert orders with valid shipping_id
INSERT INTO orders (user_id, total, shipping_id) VALUES
(1, 49.98, 1),
(2, 19.99, 2);

-- Insert Order Items
INSERT INTO order_items (order_id, product_id, quantity, price) VALUES
(1, 1, 1, 29.99),
(1, 2, 1, 19.99),
(2, 2, 1, 19.99);