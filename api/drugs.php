<?php

require_once __DIR__ . '/_bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	jsonResponse(['success' => false, 'message' => 'Method not allowed'], 405);
}

$name = trim($_POST['name'] ?? '');
$category = trim($_POST['category'] ?? '');
$stockQty = (int) ($_POST['stock_qty'] ?? 0);
$expiryDate = trim($_POST['expiry_date'] ?? '');
$reorderLevel = max(0, (int) ($_POST['reorder_level'] ?? 10));

if ($name === '' || $category === '' || $stockQty < 0 || $expiryDate === '') {
	jsonResponse(['success' => false, 'message' => 'Please fill in all required drug fields.'], 422);
}

if (!strtotime($expiryDate)) {
	jsonResponse(['success' => false, 'message' => 'Please provide a valid expiry date.'], 422);
}

$database = getDbConnection();
$statement = $database->prepare(
	'INSERT INTO drugs (name, category, stock_qty, reorder_level, expiry_date, created_by)
	 VALUES (?, ?, ?, ?, ?, ?)
	 ON DUPLICATE KEY UPDATE
	 category = VALUES(category),
	 stock_qty = stock_qty + VALUES(stock_qty),
	 reorder_level = VALUES(reorder_level),
	 expiry_date = VALUES(expiry_date),
	 created_by = VALUES(created_by),
	 updated_at = CURRENT_TIMESTAMP'
);

if (!$statement) {
	jsonResponse(['success' => false, 'message' => 'Unable to save the drug right now.'], 500);
}

$userId = currentUserId();
$statement->bind_param('ssiisi', $name, $category, $stockQty, $reorderLevel, $expiryDate, $userId);

if (!$statement->execute()) {
	jsonResponse(['success' => false, 'message' => 'Unable to save the drug right now.'], 500);
}

jsonResponse([
	'success' => true,
	'message' => 'Drug saved successfully.',
	'drug' => [
		'name' => $name,
		'category' => $category,
		'stock_qty' => $stockQty,
	],
]);