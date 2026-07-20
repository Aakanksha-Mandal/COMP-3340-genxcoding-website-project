-- ===================================================
-- GenX Coding Database
-- Import this file into phpMyAdmin or run with mysql cli
-- mysql -u yourusername -p yourdbname < database.sql
-- ===================================================

CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, -- store hashed password, never plain text!
    is_admin TINYINT(1) DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1, -- admin can set this to 0 to disable an account
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL
);

CREATE TABLE IF NOT EXISTS products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(8,2) NOT NULL,
    sale_price DECIMAL(8,2) DEFAULT NULL, -- set this lower than price to show a "Sale" badge + strikethrough
    image VARCHAR(150), -- filename only, e.g. keyboard.jpg
    rating DECIMAL(2,1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(category_id)
);

-- each product can have multiple option groups, e.g. "Size" -> S/M/L
CREATE TABLE IF NOT EXISTS product_options (
    option_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    option_name VARCHAR(50) NOT NULL,  -- e.g. "Size", "Switch Type"
    option_value VARCHAR(50) NOT NULL, -- e.g. "Large", "Blue Switch"
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total DECIMAL(8,2) NOT NULL,
    status VARCHAR(20) DEFAULT 'Pending', -- Pending / Shipped / Delivered
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE IF NOT EXISTS order_items (
    order_item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    option_text VARCHAR(100), -- chosen option, e.g. "Size: Large"
    quantity INT NOT NULL DEFAULT 1,
    price DECIMAL(8,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(order_id),
    FOREIGN KEY (product_id) REFERENCES products(product_id)
);

-- stores site-wide settings, currently just which template (Regular/Dark/Retro)
-- is active. Set/changed only from admin/templates.php, not by regular users.
CREATE TABLE IF NOT EXISTS site_settings (
    setting_id INT AUTO_INCREMENT PRIMARY KEY,
    setting_name VARCHAR(50) NOT NULL UNIQUE,
    setting_value VARCHAR(50) NOT NULL
);

INSERT INTO site_settings (setting_name, setting_value) VALUES ('site_template', 'regular');

CREATE TABLE IF NOT EXISTS reviews (
    review_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    user_id INT NOT NULL,
    rating INT NOT NULL, -- 1 to 5
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(product_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- ============ SAMPLE DATA ============

INSERT INTO categories (name) VALUES
('Keyboards & Mice'), ('Clothing'), ('Drinkware'), ('Desk Accessories'), ('Stickers & Decor'), ('Bags & Comfort');

-- 20 products (image column holds the filename of the matching file in /images)
INSERT INTO products (category_id, name, description, price, image, rating) VALUES
(1, 'Tab Key Mechanical Keyboard', 'A clicky mechanical keyboard for late night coding sessions.', 89.99, 'keyboard.png', 4.5),
(1, 'NullPointer Wireless Mouse', 'Lightweight wireless mouse that never disconnects (we hope).', 29.99, 'mouse.png', 4.2),
(2, 'console.log(hi) T-Shirt', 'Soft cotton shirt every developer needs.', 19.99, 'tshirt.png', 4.7),
(2, 'It Works On My Machine Hoodie', 'Warm hoodie for when your code only works locally.', 44.99, 'hoodie.png', 4.8),
(5, 'Syntax Error Sticker Pack', '10 vinyl stickers for your laptop lid.', 8.99, 'stickers.png', 4.6),
(3, '404 Sleep Not Found Mug', 'Ceramic mug for coffee-fueled debugging.', 14.99, 'mug.png', 4.4),
(4, 'Matrix Green Desk Mat', 'Extended desk mat with a matrix-style print.', 24.99, 'deskmat.png', 4.3),
(4, 'Stack Overflow Monitor Stand', 'Raise your monitor, lower your neck pain.', 34.99, 'monitorstand.png', 4.1),
(4, 'Webcam Cover Slider Pack', 'Privacy sliders in fun colors.', 6.99, 'webcamcover.png', 4.0),
(4, 'Debug Vision Blue Light Glasses', 'Reduce eye strain during long commits.', 22.99, 'glasses.png', 4.2),
(4, 'Multiplexer USB-C Hub', 'One port to rule them all.', 27.99, 'usbhub.png', 4.3),
(4, 'Pseudocode Pad Notebook', 'For planning code before you write code.', 11.99, 'notebook.png', 4.5),
(6, 'Byte Carrier Backpack', 'Durable backpack with a padded laptop sleeve.', 59.99, 'backpack.png', 4.6),
(3, 'Recursion Water Bottle', 'Insulated bottle, keeps recursion... I mean water... cold.', 18.99, 'bottle.png', 4.4),
(4, 'Semicolon Phone Stand', 'Small stand shaped like a semicolon.', 9.99, 'phonestand.png', 4.0),
(4, 'Cable Organizer Set', 'Stop your cables from becoming spaghetti code.', 12.99, 'cableorganizer.png', 4.1),
(4, 'Compile Light LED Lamp', 'Adjustable desk lamp with warm/cool modes.', 32.99, 'lamp.png', 4.5),
(6, 'Sudo Comfort Hoodie Blanket', 'Wearable blanket for marathon coding sessions.', 39.99, 'blanket.png', 4.7),
(5, 'Big O Notation Poster', 'Educational poster for your dorm room wall.', 15.99, 'poster.png', 4.3),
(5, 'Git Commit Enamel Pin Set', 'Pin set featuring git commands.', 13.99, 'pins.png', 4.6);

-- product options (2+ per product) - matched by product_id order above (1-20)
INSERT INTO product_options (product_id, option_name, option_value) VALUES
(1,'Switch Type','Blue'), (1,'Switch Type','Red'), (1,'Switch Type','Brown'),
(2,'Color','Black'), (2,'Color','White'),
(3,'Size','S'), (3,'Size','M'), (3,'Size','L'), (3,'Size','XL'),
(4,'Size','S'), (4,'Size','M'), (4,'Size','L'), (4,'Size','XL'), (4,'Color','Black'), (4,'Color','Grey'),
(5,'Style','Dark'), (5,'Style','Light'),
(6,'Size','11oz'), (6,'Size','15oz'),
(7,'Size','Small'), (7,'Size','Large'),
(8,'Material','Bamboo'), (8,'Material','Metal'),
(9,'Pack Size','3 colors'), (9,'Pack Size','5 colors'),
(10,'Frame','Round'), (10,'Frame','Square'),
(11,'Ports','4-port'), (11,'Ports','7-port'),
(12,'Cover','Hardcover'), (12,'Cover','Softcover'),
(13,'Color','Black'), (13,'Color','Navy'),
(14,'Size','500ml'), (14,'Size','750ml'),
(15,'Color','Black'), (15,'Color','White'),
(16,'Length','Short Pack'), (16,'Length','Long Pack'),
(17,'Light Mode','Warm'), (17,'Light Mode','Cool'),
(18,'Size','One Size'), (18,'Size','XL'),
(19,'Size','A3'), (19,'Size','A2'),
(20,'Set Size','3-pin'), (20,'Set Size','6-pin');

-- a few items on sale, so the site has something to show a "Sale" badge for
UPDATE products SET sale_price = 69.99 WHERE name = 'Tab Key Mechanical Keyboard';
UPDATE products SET sale_price = 34.99 WHERE name = 'It Works On My Machine Hoodie';
UPDATE products SET sale_price = 44.99 WHERE name = 'Byte Carrier Backpack';
UPDATE products SET sale_price = 24.99 WHERE name = 'Compile Light LED Lamp';
UPDATE products SET sale_price = 10.99 WHERE name = 'Big O Notation Poster';

-- newsletter signups from the footer form
CREATE TABLE IF NOT EXISTS newsletter_subscribers (
    subscriber_id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(150) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- default admin account (username: admin / password: admin123)
-- update the password after deployment, same as you would for any admin panel
-- password hash below is a REAL hash for 'admin123', generated with PHP's
-- password_hash() using PASSWORD_DEFAULT (bcrypt) - verified with
-- password_verify('admin123', ...) before being committed here.
INSERT INTO users (username, email, password, is_admin, is_active) VALUES
('admin', 'admin@genxcoding.com', '$2y$10$ZO5hjpCbRXdwKg9BeU2WE.HHnoNvC5SoMO5ujumavJ7dLwnrYCR.S', 1, 1);
-- NOTE: generate your own hash by running: php -r "echo password_hash('yourpassword', PASSWORD_DEFAULT);"
