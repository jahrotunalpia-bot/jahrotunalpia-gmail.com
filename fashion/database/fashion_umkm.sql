CREATE DATABASE IF NOT EXISTS fashion_umkm CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE fashion_umkm;
DROP TABLE IF EXISTS transaction_items;
DROP TABLE IF EXISTS transactions;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS users;
CREATE TABLE users(id INT AUTO_INCREMENT PRIMARY KEY,nama VARCHAR(100) NOT NULL,username VARCHAR(50) UNIQUE NOT NULL,email VARCHAR(120) UNIQUE NOT NULL,hp VARCHAR(30),password VARCHAR(255) NOT NULL,role ENUM('admin','pelanggan') NOT NULL DEFAULT 'pelanggan',created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP);
CREATE TABLE products(id INT AUTO_INCREMENT PRIMARY KEY,nama VARCHAR(150) NOT NULL,kategori VARCHAR(50) NOT NULL,harga DECIMAL(12,0) NOT NULL,stok INT NOT NULL DEFAULT 0,ukuran VARCHAR(100),deskripsi TEXT,gambar VARCHAR(255),created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP);
CREATE TABLE transactions(id INT AUTO_INCREMENT PRIMARY KEY,user_id INT NOT NULL,total DECIMAL(12,0) NOT NULL,pembayaran VARCHAR(50),pengiriman VARCHAR(50),status VARCHAR(30),created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE);
CREATE TABLE transaction_items(id INT AUTO_INCREMENT PRIMARY KEY,transaction_id INT NOT NULL,product_id INT NOT NULL,qty INT NOT NULL,harga DECIMAL(12,0) NOT NULL,FOREIGN KEY(transaction_id) REFERENCES transactions(id) ON DELETE CASCADE,FOREIGN KEY(product_id) REFERENCES products(id));
INSERT INTO users(nama,username,email,hp,password,role) VALUES('Administrator','admin','admin@stylekita.local','0800000000','admin123','admin');
INSERT INTO products(nama,kategori,harga,stok,ukuran,deskripsi,gambar) VALUES
('Kaos Oversize Basic','baju',85000,20,'M,L,XL','Kaos oversize nyaman untuk sehari-hari.','baju/kaos.jpg'),
('Kemeja Casual Linen','baju',120000,15,'S,M,L,XL','Kemeja casual ringan dan nyaman.','baju/kemeja.jpg'),
('Jeans Straight Fit','celana',150000,12,'28,30,32,34','Celana jeans straight fit.','celana/jeans.jpg'),
('Celana Cargo Relaxed','celana',135000,14,'28,30,32,34','Celana cargo dengan potongan relaxed.','celana/cargo.jpg');
