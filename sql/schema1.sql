-- Add these columns to projects table
ALTER TABLE projects ADD COLUMN IF NOT EXISTS description TEXT;
ALTER TABLE projects ADD COLUMN IF NOT EXISTS status VARCHAR(50) DEFAULT 'Planning';
ALTER TABLE projects ADD COLUMN IF NOT EXISTS start_date DATE;
ALTER TABLE projects ADD COLUMN IF NOT EXISTS end_date DATE;
ALTER TABLE projects ADD COLUMN IF NOT EXISTS budget DECIMAL(15,2) DEFAULT 0.00;


-- Add phone column to admins table
ALTER TABLE admins ADD COLUMN IF NOT EXISTS phone VARCHAR(20);

-- Update existing admin record
UPDATE admins SET phone = '+91-9876543210' WHERE email = 'admin@solartech.com';