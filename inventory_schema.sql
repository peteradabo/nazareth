CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    created DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    stock INT DEFAULT 0,
    unitprice DECIMAL(10,2) NOT NULL,
    description TEXT,
    created DATETIME DEFAULT CURRENT_TIMESTAMP,
    category_id INT,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE suppliers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    address VARCHAR(255),
    contactperson VARCHAR(100),
    contact VARCHAR(50),
    email VARCHAR(100),
    notes TEXT,
    created DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE purchases (
    id INT AUTO_INCREMENT PRIMARY KEY,
    supplierid INT NOT NULL,
    purchasedate DATE NOT NULL,
    description TEXT,
    created DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (supplierid) REFERENCES suppliers(id)
);

CREATE TABLE purchase_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    purchaseid INT NOT NULL,
    productid INT NOT NULL,
    quantity INT NOT NULL,
    unitcost DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    description TEXT,
    created DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (purchaseid) REFERENCES purchases(id),
    FOREIGN KEY (productid) REFERENCES products(id)
);