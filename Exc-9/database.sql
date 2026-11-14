-- Hands-On Lab: Authentication System menggunakan CodeIgniter 4 (ShopEase)
CREATE DATABASE IF NOT EXISTS db_shopease;
USE db_shopease;

CREATE TABLE IF NOT EXISTS users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('customer', 'admin', 'operator') NOT NULL DEFAULT 'customer',
    phone VARCHAR(20),
    address TEXT,
    created_at DATETIME NULL,
    updated_at DATETIME NULL
);
