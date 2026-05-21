CREATE DATABASE IF NOT EXISTS my_store;
USE my_store;

CREATE TABLE category (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT
);

CREATE TABLE product (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    image VARCHAR(255) DEFAULT NULL,
    category_id INT,
    FOREIGN KEY (category_id) REFERENCES category(id)
);

-- Thêm dữ liệu mẫu
INSERT INTO category (name, description) VALUES
('Electronics', 'Thiết bị điện tử'),
('Fashion', 'Thời trang'),
('Books', 'Sách');

INSERT INTO product (name, description, price, category_id, image) VALUES
('Laptop', 'A high-performance laptop', 999.99, 1, NULL),
('Smartphone', 'A latest model smartphone', 699.99, 1, NULL),
('T-Shirt', 'A comfortable cotton t-shirt', 19.99, 2, NULL),
('Novel', 'A captivating novel', 12.99, 3, NULL),
('Laptop 2024', 'Laptop 2024', 9.99, 1, NULL);
