-- Database: iyaloo
-- Create with: CREATE DATABASE IF NOT EXISTS iyaloo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- Then: USE iyaloo;

SET NAMES utf8mb4;

-- Users
CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('admin','user') NOT NULL DEFAULT 'user',
  verified TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Laptops (company)
CREATE TABLE IF NOT EXISTS laptops (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  laptop_number VARCHAR(100) NOT NULL UNIQUE,
  brand VARCHAR(120) NOT NULL,
  model VARCHAR(120) NOT NULL,
  assigned_to VARCHAR(160) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Private laptops (optional)
CREATE TABLE IF NOT EXISTS private_laptops (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  owner_name VARCHAR(160) NOT NULL,
  laptop_number VARCHAR(100) NOT NULL,
  brand VARCHAR(120) NOT NULL,
  model VARCHAR(120) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uk_private_laptop (owner_name, laptop_number)
) ENGINE=InnoDB;

-- Cars (company)
CREATE TABLE IF NOT EXISTS cars (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  registration_number VARCHAR(50) NOT NULL UNIQUE,
  make VARCHAR(120) NOT NULL,
  model VARCHAR(120) NOT NULL,
  assigned_to VARCHAR(160) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Drivers
CREATE TABLE IF NOT EXISTS drivers (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  driver_id VARCHAR(64) NOT NULL UNIQUE,
  full_name VARCHAR(160) NOT NULL,
  gender ENUM('male','female','other') NOT NULL,
  car_color VARCHAR(64) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Private cars (optional)
CREATE TABLE IF NOT EXISTS private_cars (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  owner_name VARCHAR(160) NOT NULL,
  registration_number VARCHAR(50) NOT NULL,
  make VARCHAR(120) NOT NULL,
  model VARCHAR(120) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uk_private_car (owner_name, registration_number)
) ENGINE=InnoDB;

-- Laptop scans (in/out)
CREATE TABLE IF NOT EXISTS laptop_scans (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  laptop_number VARCHAR(100) NOT NULL,
  direction ENUM('in','out') NOT NULL,
  scanned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  scanned_by INT UNSIGNED NULL,
  INDEX idx_laptop_number (laptop_number),
  INDEX idx_scan_date (scanned_at),
  CONSTRAINT fk_laptop_number FOREIGN KEY (laptop_number) REFERENCES laptops(laptop_number) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT fk_scan_user FOREIGN KEY (scanned_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Car transactions (in/out)
CREATE TABLE IF NOT EXISTS transactions (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  registration_number VARCHAR(50) NOT NULL,
  direction ENUM('in','out') NOT NULL,
  scanned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  scanned_by INT UNSIGNED NULL,
  INDEX idx_registration_number (registration_number),
  CONSTRAINT fk_car_reg FOREIGN KEY (registration_number) REFERENCES cars(registration_number) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT fk_car_scan_user FOREIGN KEY (scanned_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- General assets (optional)
CREATE TABLE IF NOT EXISTS assets (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  asset_tag VARCHAR(100) NOT NULL UNIQUE,
  name VARCHAR(160) NOT NULL,
  type VARCHAR(80) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Password resets (token store)
CREATE TABLE IF NOT EXISTS password_resets (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(190) NOT NULL,
  token VARCHAR(190) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX (email),
  INDEX (token)
) ENGINE=InnoDB;

-- Attendance module (NamWater)
CREATE TABLE IF NOT EXISTS employees (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  employee_number VARCHAR(50) NOT NULL UNIQUE,
  full_name VARCHAR(160) NOT NULL,
  department VARCHAR(120) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS attendance_logs (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  employee_id INT UNSIGNED NOT NULL,
  status ENUM('in','out') NOT NULL,
  occurred_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_attendance_time (occurred_at),
  CONSTRAINT fk_att_employee FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Activity logs
CREATE TABLE IF NOT EXISTS activity_logs (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NULL,
  action VARCHAR(100) NOT NULL,
  entity VARCHAR(100) NULL,
  entity_id BIGINT UNSIGNED NULL,
  details TEXT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_action_time (created_at),
  CONSTRAINT fk_log_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;