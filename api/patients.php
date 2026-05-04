<?php

require_once __DIR__ . '/_bootstrap.php';

$database = getDbConnection();

function patientStatusLabel(string $saleType, bool $hasReceipt): string
{
	if ($hasReceipt) {
		return 'Receipt Ready';
	}

	if (strtolower($saleType) === 'prescription') {
		return 'Awaiting Pickup';
	}

	return 'Completed';
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	$summary = [
		'salesToday' => 0,
		'prescriptionsToday' => 0,
		'pendingPrescriptions' => 0,
		'voidedSales' => 0,
	];

	$summaryQueries = [
		'salesToday' => 'SELECT COUNT(*) AS value FROM sales WHERE DATE(created_at) = CURDATE()',
		'prescriptionsToday' => 'SELECT COUNT(*) AS value FROM prescriptions WHERE DATE(created_at) = CURDATE()',
		'pendingPrescriptions' => 'SELECT COUNT(*) AS value FROM prescriptions WHERE DATE(created_at) >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)',
		'voidedSales' => 'SELECT 0 AS value',
	];

	foreach ($summaryQueries as $key => $query) {
		$result = $database->query($query);
		if ($result && ($row = $result->fetch_assoc())) {
			$summary[$key] = (int) ($row['value'] ?? 0);
		}
	}

	$recentSales = [];
	$salesResult = $database->query(
		'SELECT s.id, s.patient_name, s.sale_type, s.drug_name, s.quantity, s.notes, s.created_at,
		 (SELECT r.sale_reference FROM receipts r WHERE r.patient_name = s.patient_name ORDER BY r.created_at DESC LIMIT 1) AS last_receipt
		 FROM sales s
		 ORDER BY s.created_at DESC
		 LIMIT 8'
	);

	while ($salesResult && ($row = $salesResult->fetch_assoc())) {
		$status = patientStatusLabel((string) $row['sale_type'], !empty($row['last_receipt']));
		$recentSales[] = [
			'id' => (int) $row['id'],
			'patient_name' => $row['patient_name'],
			'drug_name' => $row['drug_name'],
			'sale_type' => $row['sale_type'],
			'quantity' => (int) $row['quantity'],
			'notes' => $row['notes'] ?? '',
			'created_at' => date('d M Y, H:i', strtotime($row['created_at'])),
			'status' => $status,
			'receipt_reference' => $row['last_receipt'] ?? '',
		];
	}

	$patients = [];
	$patientResult = $database->query(
		'SELECT p.id, p.full_name, p.phone, p.last_drug, p.last_sale_type, p.last_visit_at, p.notes,
		 (SELECT r.sale_reference FROM receipts r WHERE r.patient_name = p.full_name ORDER BY r.created_at DESC LIMIT 1) AS last_receipt,
		 (SELECT pr.notes FROM prescriptions pr WHERE pr.patient_name = p.full_name ORDER BY pr.created_at DESC LIMIT 1) AS last_prescription_note
		 FROM patients p
		 ORDER BY p.last_visit_at DESC, p.updated_at DESC'
	);

	while ($patientResult && ($row = $patientResult->fetch_assoc())) {
		$status = patientStatusLabel((string) ($row['last_sale_type'] ?? ''), !empty($row['last_receipt']));
		$patients[] = [
			'id' => (int) $row['id'],
			'full_name' => $row['full_name'],
			'phone' => $row['phone'] ?? '',
			'last_drug' => $row['last_drug'] ?? '',
			'last_sale_type' => $row['last_sale_type'] ?? '',
			'last_visit_at' => date('d M Y, H:i', strtotime($row['last_visit_at'])),
			'notes' => $row['notes'] ?? '',
			'prescription_note' => $row['last_prescription_note'] ?? '',
			'status' => $status,
		];
	}

	jsonResponse([
		'success' => true,
		'summary' => $summary,
		'recentSales' => $recentSales,
		'patients' => $patients,
	]);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	jsonResponse(['success' => false, 'message' => 'Method not allowed'], 405);
}

$action = trim($_POST['action'] ?? '');



if ($action === 'add_prescription') {
	$patientName = trim($_POST['patient_name'] ?? '');
	$drugName = trim($_POST['drug_name'] ?? '');
	$notes = trim($_POST['notes'] ?? '');

	if ($patientName === '' || $drugName === '') {
		jsonResponse(['success' => false, 'message' => 'Patient and drug are required for prescriptions.'], 422);
	}

	$database->begin_transaction();

	$prescriptionStmt = $database->prepare('INSERT INTO prescriptions (patient_name, drug_name, notes, created_by) VALUES (?, ?, ?, ?)');
	if (!$prescriptionStmt) {
		$database->rollback();
		jsonResponse(['success' => false, 'message' => 'Unable to save prescription right now.'], 500);
	}

	$userId = currentUserId();
	$prescriptionStmt->bind_param('sssi', $patientName, $drugName, $notes, $userId);
	if (!$prescriptionStmt->execute()) {
		$database->rollback();
		jsonResponse(['success' => false, 'message' => 'Unable to save prescription right now.'], 500);
	}

	$patientStmt = $database->prepare(
		'INSERT INTO patients (full_name, last_drug, last_sale_type, last_visit_at, notes)
		 VALUES (?, ?, "Prescription", CURRENT_TIMESTAMP, ?)
		 ON DUPLICATE KEY UPDATE
		 last_drug = VALUES(last_drug),
		 last_sale_type = VALUES(last_sale_type),
		 last_visit_at = VALUES(last_visit_at),
		 notes = VALUES(notes),
		 updated_at = CURRENT_TIMESTAMP'
	);

	if (!$patientStmt) {
		$database->rollback();
		jsonResponse(['success' => false, 'message' => 'Unable to save prescription right now.'], 500);
	}

	$patientStmt->bind_param('sss', $patientName, $drugName, $notes);
	if (!$patientStmt->execute()) {
		$database->rollback();
		jsonResponse(['success' => false, 'message' => 'Unable to save prescription right now.'], 500);
	}

	$database->commit();
	jsonResponse(['success' => true, 'message' => 'Prescription saved successfully.']);
}

if ($action === 'record_sale') {
	$patientName = trim($_POST['patient_name'] ?? '');
	$saleType = trim($_POST['sale_type'] ?? 'Prescription');
	$drugName = trim($_POST['drug_name'] ?? '');
	$quantity = max(0, (int) ($_POST['quantity'] ?? 0));
	$notes = trim($_POST['notes'] ?? '');

	if ($patientName === '' || $drugName === '' || $quantity < 1) {
		jsonResponse(['success' => false, 'message' => 'Please fill in the sale details.'], 422);
	}

	$database->begin_transaction();

	$drugStmt = $database->prepare('SELECT id, stock_qty FROM drugs WHERE name = ? LIMIT 1');
	if (!$drugStmt) {
		$database->rollback();
		jsonResponse(['success' => false, 'message' => 'Unable to create sale right now.'], 500);
	}

	$drugStmt->bind_param('s', $drugName);
	$drugStmt->execute();
	$drugResult = $drugStmt->get_result();
	$drug = $drugResult ? $drugResult->fetch_assoc() : null;

	if (!$drug) {
		$database->rollback();
		jsonResponse(['success' => false, 'message' => 'Drug does not exist in inventory.'], 404);
	}

	if ((int) $drug['stock_qty'] < $quantity) {
		$database->rollback();
		jsonResponse(['success' => false, 'message' => 'Not enough stock for this sale.'], 409);
	}

	$stockStmt = $database->prepare('UPDATE drugs SET stock_qty = stock_qty - ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?');
	if (!$stockStmt) {
		$database->rollback();
		jsonResponse(['success' => false, 'message' => 'Unable to update stock right now.'], 500);
	}

	$drugId = (int) $drug['id'];
	$stockStmt->bind_param('ii', $quantity, $drugId);
	if (!$stockStmt->execute()) {
		$database->rollback();
		jsonResponse(['success' => false, 'message' => 'Unable to update stock right now.'], 500);
	}

	$saleStmt = $database->prepare('INSERT INTO sales (patient_name, sale_type, drug_name, quantity, notes, sold_by) VALUES (?, ?, ?, ?, ?, ?)');
	if (!$saleStmt) {
		$database->rollback();
		jsonResponse(['success' => false, 'message' => 'Unable to save sale right now.'], 500);
	}

	$userId = currentUserId();
	$saleStmt->bind_param('sssisi', $patientName, $saleType, $drugName, $quantity, $notes, $userId);
	if (!$saleStmt->execute()) {
		$database->rollback();
		jsonResponse(['success' => false, 'message' => 'Unable to save sale right now.'], 500);
	}

	$patientStmt = $database->prepare(
		'INSERT INTO patients (full_name, last_drug, last_sale_type, last_visit_at, notes)
		 VALUES (?, ?, ?, CURRENT_TIMESTAMP, ?)
		 ON DUPLICATE KEY UPDATE
		 last_drug = VALUES(last_drug),
		 last_sale_type = VALUES(last_sale_type),
		 last_visit_at = VALUES(last_visit_at),
		 notes = VALUES(notes),
		 updated_at = CURRENT_TIMESTAMP'
	);

	if (!$patientStmt) {
		$database->rollback();
		jsonResponse(['success' => false, 'message' => 'Unable to save patient sale details.'], 500);
	}

	$patientStmt->bind_param('ssss', $patientName, $drugName, $saleType, $notes);
	if (!$patientStmt->execute()) {
		$database->rollback();
		jsonResponse(['success' => false, 'message' => 'Unable to save patient sale details.'], 500);
	}

	$database->commit();
	jsonResponse(['success' => true, 'message' => 'Sale recorded successfully.']);
}



if ($action === 'delete_patient') {
	$id = (int) ($_POST['id'] ?? 0);
	if ($id < 1) {
		jsonResponse(['success' => false, 'message' => 'Select a patient to delete.'], 422);
	}

	$statement = $database->prepare('DELETE FROM patients WHERE id = ? LIMIT 1');
	if (!$statement) {
		jsonResponse(['success' => false, 'message' => 'Unable to delete patient right now.'], 500);
	}

	$statement->bind_param('i', $id);
	if (!$statement->execute()) {
		jsonResponse(['success' => false, 'message' => 'Unable to delete patient right now.'], 500);
	}

	jsonResponse(['success' => true, 'message' => 'Patient deleted successfully.']);
}

jsonResponse(['success' => false, 'message' => 'Unsupported action.'], 422);
