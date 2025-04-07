USE vpanagsa;
-- Users table
CREATE TABLE users (
 id INT AUTO_INCREMENT PRIMARY KEY,
 first_name VARCHAR(255) NOT NULL,
 last_name VARCHAR(255) NOT NULL,
 email VARCHAR(255) NOT NULL UNIQUE,
 password VARCHAR(255) NOT NULL,
 role ENUM('normal', 'admin') DEFAULT 'normal',
 shop_name VARCHAR(255),
 shop_description TEXT,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products table
CREATE TABLE products (
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(255) NOT NULL,
 description TEXT,
 price DECIMAL(10, 2) NOT NULL,
 image_url VARCHAR(255),
 seller_id INT NOT NULL,
 quantity INT NOT NULL DEFAULT 0,
 tags VARCHAR(255),
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY (seller_id) REFERENCES users(id)
);

-- Orders table
CREATE TABLE orders (
 id INT AUTO_INCREMENT PRIMARY KEY,
 user_id INT NOT NULL,
 seller_id INT NOT NULL,
 total DECIMAL(10, 2) NOT NULL,
 order_status ENUM('pending', 'shipped', 'returned') DEFAULT 'pending',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY (user_id) REFERENCES users(id),
 FOREIGN KEY (seller_id) REFERENCES users(id)
);

-- Order Items table
CREATE TABLE order_items (
 id INT AUTO_INCREMENT PRIMARY KEY,
 order_id INT NOT NULL,
 product_id INT NOT NULL,
 quantity INT NOT NULL,
 price DECIMAL(10, 2) NOT NULL,
 FOREIGN KEY (order_id) REFERENCES orders(id),
 FOREIGN KEY (product_id) REFERENCES products(id)
);