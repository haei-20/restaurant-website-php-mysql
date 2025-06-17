-- Create database
CREATE DATABASE IF NOT EXISTS restaurant_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE restaurant_db;

-- Users table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name NVARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20),
    role ENUM('admin', 'staff', 'customer') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Categories table
CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name NVARCHAR(100) NOT NULL,
    description NVARCHAR(255),
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products (Menu Items) table
CREATE TABLE products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    category_id INT,
    name NVARCHAR(100) NOT NULL,
    description NVARCHAR(500),
    price DECIMAL(10,2) NOT NULL,
    image_url VARCHAR(255),
    is_available BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Orders table
CREATE TABLE orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'confirmed', 'preparing', 'ready', 'delivered', 'cancelled') DEFAULT 'pending',
    payment_status ENUM('unpaid', 'paid') DEFAULT 'unpaid',
    payment_method ENUM('cash', 'card', 'banking') DEFAULT 'cash',
    note NVARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Order Details table
CREATE TABLE order_details (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    product_id INT,
    quantity INT NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    note NVARCHAR(255),
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
);

-- Tables (Restaurant Tables) table
CREATE TABLE tables (
    id INT PRIMARY KEY AUTO_INCREMENT,
    table_number VARCHAR(10) NOT NULL UNIQUE,
    capacity INT NOT NULL,
    status ENUM('available', 'occupied', 'reserved') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Reservations table
CREATE TABLE reservations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    table_id INT,
    reservation_date DATE NOT NULL,
    reservation_time TIME NOT NULL,
    number_of_guests INT NOT NULL,
    status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
    special_requests NVARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (table_id) REFERENCES tables(id) ON DELETE SET NULL
);

-- Reviews table
CREATE TABLE reviews (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    order_id INT,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    comment NVARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE SET NULL
); 
-- Thêm trường total_amount vào bảng placed_orders
ALTER TABLE placed_orders ADD COLUMN total_amount DECIMAL(10,2) DEFAULT 0;

-- Cập nhật giá trị total_amount cho các đơn hàng hiện có
UPDATE placed_orders po 
SET total_amount = (
    SELECT SUM(m.menu_price * io.quantity) 
    FROM in_order io 
    JOIN menus m ON io.menu_id = m.menu_id 
    WHERE io.order_id = po.order_id
)
WHERE EXISTS (
    SELECT 1 
    FROM in_order io 
    WHERE io.order_id = po.order_id
); 