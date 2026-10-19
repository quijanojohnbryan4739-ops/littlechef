CREATE DATABASE IF NOT EXISTS littlechef;
USE littlechef;

CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin','staff') DEFAULT 'admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE products (
    product_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    availability_status ENUM('available','unavailable') DEFAULT 'available',
    image_url VARCHAR(255) DEFAULT 'default.jpg'
) ENGINE=InnoDB;

CREATE TABLE inventory (
    ingredient_id INT PRIMARY KEY AUTO_INCREMENT,
    ingredient_name VARCHAR(100) NOT NULL,
    quantity_in_stock DECIMAL(10,2) NOT NULL DEFAULT 0,
    unit VARCHAR(20) NOT NULL,
    low_stock_threshold DECIMAL(10,2) NOT NULL DEFAULT 10
) ENGINE=InnoDB;

CREATE TABLE orders (
    order_id INT PRIMARY KEY AUTO_INCREMENT,
    order_reference VARCHAR(20) UNIQUE NOT NULL,
    customer_name VARCHAR(100) NOT NULL,
    contact_number VARCHAR(20) NOT NULL,
    order_type ENUM('standard','bulk') NOT NULL DEFAULT 'standard',
    fulfillment ENUM('pickup','delivery') NOT NULL,
    delivery_address TEXT,
    delivery_fee DECIMAL(10,2) DEFAULT 0,
    payment_method ENUM('gcash','cash') NOT NULL,
    gcash_ref VARCHAR(100),
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending','confirmed','preparing','out_for_delivery','completed','rejected') DEFAULT 'pending',
    special_instructions TEXT,
    desired_datetime DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE order_items (
    item_id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id)
) ENGINE=InnoDB;

-- Default Admin: username=admin | password=admin123
INSERT INTO users (username, password_hash) VALUES 
('admin', '$2y$10$EixZaYb4UuK9rM5WvXzR/.tG5h0qP7sNnOeLdXmYwVbTnUJzHqC5m');

-- Sample Products
INSERT INTO products (name, description, price) VALUES
('California Maki', 'Cucumber, crab stick, avocado; sesame outside', 180.00),
('Spicy Tuna Roll', 'Fresh tuna with spicy mayo', 220.00),
('Salmon Sashimi', 'Premium fresh salmon slices', 250.00),
('Mixed Sushi Platter', 'Assorted 30pcs best-sellers', 950.00);

-- Sample Inventory
INSERT INTO inventory (ingredient_name, quantity_in_stock, unit, low_stock_threshold) VALUES
('Sushi Rice', 20, 'kg', 5),
('Nori Sheets', 100, 'sheets', 20),
('Fresh Salmon', 5, 'kg', 2),
('Crab Sticks', 30, 'pcs', 10),
('Avocado', 15, 'pcs', 5),
('Soy Sauce', 3, 'L', 1);