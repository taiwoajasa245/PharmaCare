CREATE DATABASE IF NOT EXISTS pharmacare
	CHARACTER SET utf8mb4
	COLLATE utf8mb4_unicode_ci;

USE pharmacare;


-- Auth is the first backed-up feature, so we keep the schema focused on users.
CREATE TABLE IF NOT EXISTS users (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	full_name VARCHAR(120) NOT NULL,
	email VARCHAR(190) NOT NULL,
	password_hash VARCHAR(255) NOT NULL,
	role VARCHAR(50) NOT NULL DEFAULT 'pharmacist',
	created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (id),
	UNIQUE KEY unique_users_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS drugs (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	name VARCHAR(160) NOT NULL,
	category VARCHAR(80) NOT NULL,
	stock_qty INT UNSIGNED NOT NULL DEFAULT 0,
	reorder_level INT UNSIGNED NOT NULL DEFAULT 10,
	expiry_date DATE NOT NULL,
	created_by INT UNSIGNED NULL,
	created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (id),
	UNIQUE KEY unique_drugs_name (name),
	KEY idx_drugs_stock_qty (stock_qty),
	KEY idx_drugs_expiry_date (expiry_date),
	CONSTRAINT fk_drugs_created_by FOREIGN KEY (created_by) REFERENCES users (id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS patients (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	full_name VARCHAR(160) NOT NULL,
	phone VARCHAR(40) DEFAULT NULL,
	last_drug VARCHAR(160) DEFAULT NULL,
	last_sale_type VARCHAR(40) DEFAULT NULL,
	last_visit_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	notes TEXT DEFAULT NULL,
	created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (id),
	UNIQUE KEY unique_patients_name (full_name),
	UNIQUE KEY unique_patients_phone (phone),
	KEY idx_patients_last_visit_at (last_visit_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS sales (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	patient_name VARCHAR(160) NOT NULL,
	sale_type VARCHAR(40) NOT NULL,
	drug_name VARCHAR(160) NOT NULL,
	quantity INT UNSIGNED NOT NULL DEFAULT 1,
	notes TEXT DEFAULT NULL,
	sold_by INT UNSIGNED NULL,
	created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (id),
	KEY idx_sales_created_at (created_at),
	KEY idx_sales_patient_name (patient_name),
	CONSTRAINT fk_sales_sold_by FOREIGN KEY (sold_by) REFERENCES users (id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS prescriptions (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	patient_name VARCHAR(160) NOT NULL,
	drug_name VARCHAR(160) NOT NULL,
	notes TEXT DEFAULT NULL,
	created_by INT UNSIGNED NULL,
	created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (id),
	KEY idx_prescriptions_patient_name (patient_name),
	KEY idx_prescriptions_created_at (created_at),
	CONSTRAINT fk_prescriptions_created_by FOREIGN KEY (created_by) REFERENCES users (id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


