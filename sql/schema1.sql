-- Add missing columns to admins table
ALTER TABLE admins ADD COLUMN IF NOT EXISTS phone VARCHAR(20);
ALTER TABLE admins ADD COLUMN IF NOT EXISTS role VARCHAR(50) DEFAULT 'System Administrator';

-- Add these columns to projects table
ALTER TABLE projects ADD COLUMN IF NOT EXISTS description TEXT;
ALTER TABLE projects ADD COLUMN IF NOT EXISTS start_date DATE;
ALTER TABLE projects ADD COLUMN IF NOT EXISTS end_date DATE;
ALTER TABLE projects ADD COLUMN IF NOT EXISTS budget DECIMAL(15,2) DEFAULT 0.00;

-- Create company_settings table if it doesn't exist
CREATE TABLE IF NOT EXISTS company_settings (
    id SERIAL PRIMARY KEY,
    company_name VARCHAR(255) NOT NULL,
    company_address TEXT,
    company_phone VARCHAR(20),
    company_email VARCHAR(255),
    company_gst VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Update existing admin record with phone number
UPDATE admins SET phone = '+91-9876543210', role = 'System Administrator' WHERE email = 'kunaljadhav1625@gmail.com';