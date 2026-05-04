<?php

require_once __DIR__ . '/_bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	jsonResponse(['success' => false, 'message' => 'Method not allowed'], 405);
}

$patientName = trim($_POST['patient_name'] ?? '');
$saleType = trim($_POST['sale_type'] ?? 'Prescription');
$drugName = trim($_POST['drug_name'] ?? '');
$quantity = max(0, (int) ($_POST['quantity'] ?? 0));
$notes = trim($_POST['notes'] ?? '');

if ($patientName === '' || $drugName === '' || $quantity < 1) {
	jsonResponse(['success' => false, 'message' => 'Please fill in the sale details.'], 422);
}

$database = getDbConnection();

$database->begin_transaction();

$drugStatement = $database->prepare('SELECT id, stock_qty FROM drugs WHERE name = ? LIMIT 1');
if (!$drugStatement) {
	$database->rollback();
	jsonResponse(['success' => false, 'message' => 'Unable to create sale right now.'], 500);
}

$drugStatement->bind_param('s', $drugName);
$drugStatement->execute();
$drugResult = $drugStatement->get_result();
$drug = $drugResult ? $drugResult->fetch_assoc() : null;

if (!$drug) {
	$database->rollback();
	jsonResponse(['success' => false, 'message' => 'Select a drug that already exists in the database.'], 404);
}

if ((int) $drug['stock_qty'] < $quantity) {
	$database->rollback();
	jsonResponse(['success' => false, 'message' => 'Not enough stock for this sale.'], 409);
}

$updateStock = $database->prepare('UPDATE drugs SET stock_qty = stock_qty - ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?');
if (!$updateStock) {
	$database->rollback();
	jsonResponse(['success' => false, 'message' => 'Unable to update stock right now.'], 500);
}

$drugId = (int) $drug['id'];
$updateStock->bind_param('ii', $quantity, $drugId);

if (!$updateStock->execute()) {
	$database->rollback();
	jsonResponse(['success' => false, 'message' => 'Unable to update stock right now.'], 500);
}

$patientStatement = $database->prepare(
	'INSERT INTO patients (full_name, last_drug, last_sale_type, last_visit_at, notes)
	 VALUES (?, ?, ?, CURRENT_TIMESTAMP, ?)
	 ON DUPLICATE KEY UPDATE
	 last_drug = VALUES(last_drug),
	 last_sale_type = VALUES(last_sale_type),
	 last_visit_at = VALUES(last_visit_at),
	 notes = VALUES(notes),
	 updated_at = CURRENT_TIMESTAMP'
);

if (!$patientStatement) {
	$database->rollback();
	jsonResponse(['success' => false, 'message' => 'Unable to store patient details right now.'], 500);
}

$patientStatement->bind_param('ssss', $patientName, $drugName, $saleType, $notes);

if (!$patientStatement->execute()) {
	$database->rollback();
	jsonResponse(['success' => false, 'message' => 'Unable to store patient details right now.'], 500);
}

$saleStatement = $database->prepare(
	'INSERT INTO sales (patient_name, sale_type, drug_name, quantity, notes, sold_by)
	 VALUES (?, ?, ?, ?, ?, ?)'
);

if (!$saleStatement) {
	$database->rollback();
	jsonResponse(['success' => false, 'message' => 'Unable to save sale right now.'], 500);
}

$userId = currentUserId();
$saleStatement->bind_param('sssisi', $patientName, $saleType, $drugName, $quantity, $notes, $userId);

if (!$saleStatement->execute()) {
	$database->rollback();
	jsonResponse(['success' => false, 'message' => 'Unable to save sale right now.'], 500);
}

$database->commit();

jsonResponse([
	'success' => true,
	'message' => 'Sale saved successfully.',
	'sale' => [
		'patient_name' => $patientName,
		'drug_name' => $drugName,
		'quantity' => $quantity,
	],
]);