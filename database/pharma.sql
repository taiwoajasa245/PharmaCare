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

CREATE TABLE IF NOT EXISTS receipts (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	patient_name VARCHAR(160) NOT NULL,
	sale_reference VARCHAR(120) NOT NULL,
	note TEXT DEFAULT NULL,
	created_by INT UNSIGNED NULL,
	created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (id),
	UNIQUE KEY unique_receipts_sale_reference (sale_reference),
	KEY idx_receipts_patient_name (patient_name),
	CONSTRAINT fk_receipts_created_by FOREIGN KEY (created_by) REFERENCES users (id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO drugs (name, category, stock_qty, reorder_level, expiry_date) VALUES
('Paracetamol 500mg', 'Tablets', 180, 40, '2027-02-28'),
('Amoxicillin 250mg', 'Capsules', 64, 20, '2026-12-31'),
('Metformin 500mg', 'Tablets', 95, 25, '2027-05-15'),
('Ibuprofen 200mg', 'Tablets', 22, 30, '2026-10-10'),
('Vitamin C Syrup', 'Syrups', 48, 15, '2026-09-30');

INSERT IGNORE INTO patients (full_name, phone, last_drug, last_sale_type, last_visit_at, notes) VALUES
('Fatima Abubakar', '07035551122', 'Paracetamol 500mg', 'Prescription', '2026-05-04 09:10:00', 'Follow-up in two weeks'),
('Emeka Okonkwo', '08024447788', 'Amoxicillin 250mg', 'Prescription', '2026-05-04 10:15:00', 'Responding well to treatment'),
('Ngozi Kalu', '08092223344', 'Metformin 500mg', 'Prescription', '2026-05-03 15:30:00', 'Routine refill'),
('Bola Mustapha', '08030014455', 'Ibuprofen 200mg', 'OTC', '2026-05-03 18:05:00', 'Pain relief consult'),
('Chinwe Ibe', '08112223344', 'Vitamin C Syrup', 'OTC', '2026-05-02 14:20:00', 'Seasonal wellness');

INSERT IGNORE INTO sales (patient_name, sale_type, drug_name, quantity, notes, sold_by, created_at) VALUES
('Fatima Abubakar', 'Prescription', 'Paracetamol 500mg', 2, 'Initial relief sale', NULL, '2026-05-04 09:10:00'),
('Emeka Okonkwo', 'Prescription', 'Amoxicillin 250mg', 1, 'Treatment started', NULL, '2026-05-04 10:15:00'),
('Ngozi Kalu', 'Prescription', 'Metformin 500mg', 1, 'Monthly refill', NULL, '2026-05-03 15:30:00'),
('Bola Mustapha', 'OTC', 'Ibuprofen 200mg', 3, 'Pain management', NULL, '2026-05-03 18:05:00'),
('Chinwe Ibe', 'OTC', 'Vitamin C Syrup', 1, 'Wellness support', NULL, '2026-05-02 14:20:00');

INSERT IGNORE INTO prescriptions (patient_name, drug_name, notes, created_by, created_at) VALUES
('Fatima Abubakar', 'Paracetamol 500mg', '2 tablets after meals', NULL, '2026-05-04 09:10:00'),
('Emeka Okonkwo', 'Amoxicillin 250mg', '1 capsule three times daily', NULL, '2026-05-04 10:15:00'),
('Ngozi Kalu', 'Metformin 500mg', '1 tablet daily after breakfast', NULL, '2026-05-03 15:30:00');

INSERT IGNORE INTO receipts (patient_name, sale_reference, note, created_by, created_at) VALUES
('Fatima Abubakar', 'SALE-1001', 'Paid in full', NULL, '2026-05-04 09:12:00'),
('Emeka Okonkwo', 'SALE-1002', 'Pending pickup confirmation', NULL, '2026-05-04 10:17:00');
