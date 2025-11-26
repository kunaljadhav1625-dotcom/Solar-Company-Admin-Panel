-- -- MySQL Database Schema for Solar Admin System

-- -- Create Database if not exists
-- CREATE DATABASE IF NOT EXISTS solar_company;
-- USE solar_company;

-- CREATE TABLE admins (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     email VARCHAR(255) UNIQUE NOT NULL,
--     password VARCHAR(255) NOT NULL,
--     name VARCHAR(100) NOT NULL,
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- CREATE TABLE clients (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     name VARCHAR(255) NOT NULL,
--     email VARCHAR(255),
--     phone VARCHAR(20),
--     address TEXT,
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- CREATE TABLE products (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     sku VARCHAR(100) UNIQUE NOT NULL,
--     name VARCHAR(255) NOT NULL,
--     description TEXT,
--     unit_price DECIMAL(10,2) NOT NULL,
--     stock_quantity INT NOT NULL,
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- CREATE TABLE projects (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     name VARCHAR(255) NOT NULL,
--     client_id INT,
--     created_date DATE DEFAULT CURRENT_DATE,
--     status VARCHAR(50) DEFAULT 'Active',
--     FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE SET NULL
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- CREATE TABLE bills (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     bill_no VARCHAR(100) UNIQUE NOT NULL,
--     project_id INT,
--     client_id INT,
--     bill_date DATE DEFAULT CURRENT_DATE,
--     total_amount DECIMAL(10,2) NOT NULL,
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
--     FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE SET NULL,
--     FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE SET NULL
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- CREATE TABLE bill_items (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     bill_id INT,
--     product_id INT,
--     quantity INT NOT NULL,
--     unit_price DECIMAL(10,2) NOT NULL,
--     line_total DECIMAL(10,2) NOT NULL,
--     FOREIGN KEY (bill_id) REFERENCES bills(id) ON DELETE CASCADE,
--     FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -- Insert default admin
-- -- INSERT INTO admins (email, password, name) VALUES (
-- --     'admin@solartech.com', 
-- --     '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password
-- --     'System Administrator'
-- -- );
-- INSERT INTO admins (email, password, name) VALUES (
--     'kunaljadhav1625@gmail.com', 
--     'kunal123', -- password
--     'System Administrator'
-- );

-- -- Insert sample data
-- INSERT INTO clients (name, email, phone, address) VALUES 
-- ('Rajesh Sharma', 'rajesh@email.com', '9876543210', 'Pune, Maharashtra'),
-- ('Priya Patel', 'priya@email.com', '9876543211', 'Mumbai, Maharashtra');

-- INSERT INTO products (sku, name, description, unit_price, stock_quantity) VALUES 
-- ('SOLAR-PANEL-100W', '100W Solar Panel', 'High efficiency monocrystalline solar panel', 5000.00, 100),
-- ('INVERTER-1KW', '1KW Solar Inverter', 'Hybrid solar inverter with battery backup', 15000.00, 50),
-- ('BATTERY-100AH', '100AH Solar Battery', 'Deep cycle solar battery', 8000.00, 30);



-- Create database (run in psql or your DB manager)
-- CREATE DATABASE solar_company;
-- \c solar_company   -- (psql meta-command to connect)

-- Enable extension for password hashing (run once in the database)



CREATE EXTENSION IF NOT EXISTS pgcrypto;

-- ---------- TABLES ----------
CREATE TABLE admins (
    id INT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT now()
);

CREATE TABLE clients (
    id INT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    phone VARCHAR(20),
    address TEXT,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT now()
);

CREATE TABLE products (
    id INT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    sku VARCHAR(100) UNIQUE NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    unit_price NUMERIC(10,2) NOT NULL,
    stock_quantity INT NOT NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT now()
);

-- You can use a native enum for project status if you want stricter validation:
DO $$
BEGIN
    IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'project_status') THEN
        CREATE TYPE project_status AS ENUM ('Active','Completed','On Hold','Cancelled');
    END IF;
END$$;

CREATE TABLE projects (
    id INT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    client_id INT NULL,
    created_date DATE DEFAULT CURRENT_DATE,
    status project_status DEFAULT 'Active',
    CONSTRAINT fk_projects_client FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE SET NULL
);

CREATE TABLE bills (
    id INT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    bill_no VARCHAR(100) UNIQUE NOT NULL,
    project_id INT NULL,
    client_id INT NULL,
    bill_date DATE DEFAULT CURRENT_DATE,
    total_amount NUMERIC(10,2) NOT NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT now(),
    CONSTRAINT fk_bills_project FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE SET NULL,
    CONSTRAINT fk_bills_client FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE SET NULL
);

CREATE TABLE bill_items (
    id INT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    bill_id INT NOT NULL,
    product_id INT NULL,
    quantity INT NOT NULL CHECK (quantity >= 0),
    unit_price NUMERIC(10,2) NOT NULL,
    line_total NUMERIC(10,2) NOT NULL,
    CONSTRAINT fk_billitems_bill FOREIGN KEY (bill_id) REFERENCES bills(id) ON DELETE CASCADE,
    CONSTRAINT fk_billitems_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
);

-- ---------- SAMPLE DATA ----------
-- Note: use crypt() with gen_salt() for hashing passwords. Example below inserts an admin with a bcrypt hash.
INSERT INTO admins (email, password, name)
VALUES (
    'kunaljadhav1625@gmail.com',
    crypt('kunal123', gen_salt('bf')), -- hashed password using bcrypt
    'System Administrator'
);

INSERT INTO clients (name, email, phone, address) VALUES 
('Rajesh Sharma', 'rajesh@email.com', '9876543210', 'Pune, Maharashtra'),
('Priya Patel', 'priya@email.com', '9876543211', 'Mumbai, Maharashtra');

INSERT INTO products (sku, name, description, unit_price, stock_quantity) VALUES 
('SOLAR-PANEL-100W', '100W Solar Panel', 'High efficiency monocrystalline solar panel', 5000.00, 100),
('INVERTER-1KW', '1KW Solar Inverter', 'Hybrid solar inverter with battery backup', 15000.00, 50),
('BATTERY-100AH', '100AH Solar Battery', 'Deep cycle solar battery', 8000.00, 30);

-- Example: how to verify login (compare provided password to stored hash)
-- SELECT id, email FROM admins WHERE email = 'kunaljadhav1625@gmail.com' AND password = crypt('kunal123', password);

-- Optional: create an index to speed searching by created_at or bill_no etc.
CREATE INDEX IF NOT EXISTS idx_products_sku ON products(sku);
CREATE INDEX IF NOT EXISTS idx_bills_bill_no ON bills(bill_no);

-- End of script
