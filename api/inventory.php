<?php

require_once __DIR__ . '/_bootstrap.php';

$database = getDbConnection();

function inventoryStatus(array $row): string
{
	$expiryDate = strtotime((string) ($row['expiry_date'] ?? ''));
	$today = strtotime(date('Y-m-d'));
	$inThirtyDays = strtotime('+30 days', $today);
	$stock = (int) ($row['stock_qty'] ?? 0);
	$reorderLevel = (int) ($row['reorder_level'] ?? 0);

	if ($expiryDate !== false && $expiryDate <= $inThirtyDays) {
		return 'expiring';
	}

	if ($stock <= $reorderLevel) {
		return 'low';
	}

	return 'in_stock';
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	$summary = [
		'totalDrugs' => 0,
		'lowStock' => 0,
		'expiringSoon' => 0,
		'stockInToday' => 0,
	];

	$summaryQueries = [
		'totalDrugs' => 'SELECT COUNT(*) AS value FROM drugs',
		'lowStock' => 'SELECT COUNT(*) AS value FROM drugs WHERE stock_qty <= reorder_level',
		'expiringSoon' => 'SELECT COUNT(*) AS value FROM drugs WHERE expiry_date <= DATE_ADD(CURDATE(), INTERVAL 30 DAY)',
		'stockInToday' => 'SELECT COALESCE(SUM(stock_qty), 0) AS value FROM drugs WHERE DATE(updated_at) = CURDATE()',
	];

	foreach ($summaryQueries as $key => $query) {
		$result = $database->query($query);
		if ($result && ($row = $result->fetch_assoc())) {
			$summary[$key] = (int) ($row['value'] ?? 0);
		}
	}

	$search = trim($_GET['search'] ?? '');
	$category = trim($_GET['category'] ?? '');
	$status = trim($_GET['status'] ?? '');
	$sort = trim($_GET['sort'] ?? 'updated_desc');

	$where = [];
	$params = [];
	$types = '';

	if ($search !== '') {
		$where[] = '(name LIKE ? OR category LIKE ?)';
		$searchLike = '%' . $search . '%';
		$params[] = $searchLike;
		$params[] = $searchLike;
		$types .= 'ss';
	}

	if ($category !== '' && strtolower($category) !== 'all categories') {
		$where[] = 'category = ?';
		$params[] = $category;
		$types .= 's';
	}

	$query = 'SELECT id, name, category, stock_qty, reorder_level, expiry_date, created_at, updated_at FROM drugs';
	if (count($where) > 0) {
		$query .= ' WHERE ' . implode(' AND ', $where);
	}

	$sortMap = [
		'updated_desc' => 'updated_at DESC',
		'name_asc' => 'name ASC',
		'stock_desc' => 'stock_qty DESC',
		'stock_asc' => 'stock_qty ASC',
		'expiry_asc' => 'expiry_date ASC',
	];
	$query .= ' ORDER BY ' . ($sortMap[$sort] ?? 'updated_at DESC');

	$statement = $database->prepare($query);
	if (!$statement) {
		jsonResponse(['success' => false, 'message' => 'Unable to load inventory right now.'], 500);
	}

	if (count($params) > 0) {
		$statement->bind_param($types, ...$params);
	}

	$statement->execute();
	$result = $statement->get_result();

	$rows = [];
	while ($result && ($row = $result->fetch_assoc())) {
		$currentStatus = inventoryStatus($row);
		if ($status !== '' && strtolower($status) !== 'all status') {
			$wanted = strtolower($status);
			if ($wanted === 'in stock') {
				$wanted = 'in_stock';
			} elseif ($wanted === 'low stock') {
				$wanted = 'low';
			} elseif ($wanted === 'expiring') {
				$wanted = 'expiring';
			}
			if ($wanted !== $currentStatus) {
				continue;
			}
		}

		$rows[] = [
			'id' => (int) $row['id'],
			'name' => $row['name'],
			'category' => $row['category'],
			'stock_qty' => (int) $row['stock_qty'],
			'reorder_level' => (int) $row['reorder_level'],
			'expiry_date' => date('Y-m-d', strtotime($row['expiry_date'])),
			'expiry_label' => date('d M Y', strtotime($row['expiry_date'])),
			'updated_label' => date('d M Y, H:i', strtotime($row['updated_at'])),
			'status' => $currentStatus,
		];
	}

	jsonResponse([
		'success' => true,
		'summary' => $summary,
		'items' => $rows,
	]);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	jsonResponse(['success' => false, 'message' => 'Method not allowed'], 405);
}

$action = trim($_POST['action'] ?? '');

if ($action === 'add') {
	$name = trim($_POST['name'] ?? '');
	$category = trim($_POST['category'] ?? '');
	$stockQty = max(0, (int) ($_POST['stock_qty'] ?? 0));
	$expiryDate = trim($_POST['expiry_date'] ?? '');
	$reorderLevel = max(0, (int) ($_POST['reorder_level'] ?? 10));

	if ($name === '' || $category === '' || $expiryDate === '') {
		jsonResponse(['success' => false, 'message' => 'Please fill in the required drug fields.'], 422);
	}

	$statement = $database->prepare(
		'INSERT INTO drugs (name, category, stock_qty, reorder_level, expiry_date, created_by)
		 VALUES (?, ?, ?, ?, ?, ?)
		 ON DUPLICATE KEY UPDATE
		 category = VALUES(category),
		 stock_qty = stock_qty + VALUES(stock_qty),
		 reorder_level = VALUES(reorder_level),
		 expiry_date = VALUES(expiry_date),
		 updated_at = CURRENT_TIMESTAMP'
	);

	if (!$statement) {
		jsonResponse(['success' => false, 'message' => 'Unable to save drug right now.'], 500);
	}

	$userId = currentUserId();
	$statement->bind_param('ssiisi', $name, $category, $stockQty, $reorderLevel, $expiryDate, $userId);
	if (!$statement->execute()) {
		jsonResponse(['success' => false, 'message' => 'Unable to save drug right now.'], 500);
	}

	jsonResponse(['success' => true, 'message' => 'Drug saved successfully.']);
}

if ($action === 'update') {
	$id = (int) ($_POST['id'] ?? 0);
	$name = trim($_POST['name'] ?? '');
	$category = trim($_POST['category'] ?? '');
	$stockQty = max(0, (int) ($_POST['stock_qty'] ?? 0));
	$expiryDate = trim($_POST['expiry_date'] ?? '');
	$reorderLevel = max(0, (int) ($_POST['reorder_level'] ?? 10));

	if ($id < 1 || $name === '' || $category === '' || $expiryDate === '') {
		jsonResponse(['success' => false, 'message' => 'Please provide valid drug details to update.'], 422);
	}

	$statement = $database->prepare('UPDATE drugs SET name = ?, category = ?, stock_qty = ?, reorder_level = ?, expiry_date = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?');
	if (!$statement) {
		jsonResponse(['success' => false, 'message' => 'Unable to update drug right now.'], 500);
	}

	$statement->bind_param('ssiisi', $name, $category, $stockQty, $reorderLevel, $expiryDate, $id);
	if (!$statement->execute()) {
		jsonResponse(['success' => false, 'message' => 'Unable to update drug right now.'], 500);
	}

	jsonResponse(['success' => true, 'message' => 'Drug updated successfully.']);
}

if ($action === 'stock_in') {
	$id = (int) ($_POST['id'] ?? 0);
	$quantity = max(1, (int) ($_POST['quantity'] ?? 0));

	if ($id < 1) {
		jsonResponse(['success' => false, 'message' => 'Select a drug for stock-in.'], 422);
	}

	$statement = $database->prepare('UPDATE drugs SET stock_qty = stock_qty + ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?');
	if (!$statement) {
		jsonResponse(['success' => false, 'message' => 'Unable to stock in right now.'], 500);
	}

	$statement->bind_param('ii', $quantity, $id);
	if (!$statement->execute()) {
		jsonResponse(['success' => false, 'message' => 'Unable to stock in right now.'], 500);
	}

	jsonResponse(['success' => true, 'message' => 'Stock in saved successfully.']);
}

if ($action === 'stock_out') {
	$id = (int) ($_POST['id'] ?? 0);
	$quantity = max(1, (int) ($_POST['quantity'] ?? 0));

	if ($id < 1) {
		jsonResponse(['success' => false, 'message' => 'Select a drug for stock-out.'], 422);
	}

	$check = $database->prepare('SELECT stock_qty FROM drugs WHERE id = ? LIMIT 1');
	if (!$check) {
		jsonResponse(['success' => false, 'message' => 'Unable to stock out right now.'], 500);
	}

	$check->bind_param('i', $id);
	$check->execute();
	$result = $check->get_result();
	$row = $result ? $result->fetch_assoc() : null;
	if (!$row) {
		jsonResponse(['success' => false, 'message' => 'Drug not found.'], 404);
	}

	if ((int) $row['stock_qty'] < $quantity) {
		jsonResponse(['success' => false, 'message' => 'Not enough stock for this action.'], 409);
	}

	$statement = $database->prepare('UPDATE drugs SET stock_qty = stock_qty - ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?');
	if (!$statement) {
		jsonResponse(['success' => false, 'message' => 'Unable to stock out right now.'], 500);
	}

	$statement->bind_param('ii', $quantity, $id);
	if (!$statement->execute()) {
		jsonResponse(['success' => false, 'message' => 'Unable to stock out right now.'], 500);
	}

	jsonResponse(['success' => true, 'message' => 'Stock out saved successfully.']);
}

if ($action === 'delete') {
	$id = (int) ($_POST['id'] ?? 0);
	if ($id < 1) {
		jsonResponse(['success' => false, 'message' => 'Select a drug to delete.'], 422);
	}

	$statement = $database->prepare('DELETE FROM drugs WHERE id = ? LIMIT 1');
	if (!$statement) {
		jsonResponse(['success' => false, 'message' => 'Unable to delete drug right now.'], 500);
	}

	$statement->bind_param('i', $id);
	if (!$statement->execute()) {
		jsonResponse(['success' => false, 'message' => 'Unable to delete drug right now.'], 500);
	}

	jsonResponse(['success' => true, 'message' => 'Drug deleted successfully.']);
}

jsonResponse(['success' => false, 'message' => 'Unsupported action.'], 422);
