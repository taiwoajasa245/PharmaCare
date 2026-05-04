<?php

require_once __DIR__ . '/_bootstrap.php';

$database = getDbConnection();

$summary = [
	'totalMedicines' => 0,
	'lowStockAlerts' => 0,
	'expiredMedicines' => 0,
	'totalPatients' => 0,
	'totalUsers' => 0,
	'totalSales' => 0,
];

$summaryQueries = [
	'totalMedicines' => 'SELECT COALESCE(SUM(stock_qty), 0) AS value FROM drugs',
	'lowStockAlerts' => 'SELECT COUNT(*) AS value FROM drugs WHERE stock_qty <= reorder_level',
	'expiredMedicines' => 'SELECT COUNT(*) AS value FROM drugs WHERE expiry_date < CURDATE()',
	'totalPatients' => 'SELECT COUNT(*) AS value FROM patients',
	'totalUsers' => 'SELECT COUNT(*) AS value FROM users',
	'totalSales' => 'SELECT COUNT(*) AS value FROM sales',
];

foreach ($summaryQueries as $key => $query) {
	$result = $database->query($query);
	if ($result && ($row = $result->fetch_assoc())) {
		$summary[$key] = (int) ($row['value'] ?? 0);
	}
}

$recentPatients = [];
$patientResult = $database->query(
	'SELECT full_name, last_drug, last_sale_type, last_visit_at, COALESCE(notes, "") AS notes
	 FROM patients
	 ORDER BY last_visit_at DESC
	 LIMIT 5'
);

if ($patientResult) {
	while ($row = $patientResult->fetch_assoc()) {
		$recentPatients[] = [
			'full_name' => $row['full_name'],
			'last_drug' => $row['last_drug'],
			'last_sale_type' => $row['last_sale_type'],
			'last_visit_at' => date('d M Y, H:i', strtotime($row['last_visit_at'])),
			'notes' => $row['notes'],
		];
	}
}

$recentSales = [];
$salesResult = $database->query(
	'SELECT patient_name, sale_type, drug_name, quantity, created_at
	 FROM sales
	 ORDER BY created_at DESC
	 LIMIT 5'
);

if ($salesResult) {
	while ($row = $salesResult->fetch_assoc()) {
		$recentSales[] = [
			'patient_name' => $row['patient_name'],
			'sale_type' => $row['sale_type'],
			'drug_name' => $row['drug_name'],
			'quantity' => (int) $row['quantity'],
			'created_at' => date('d M Y, H:i', strtotime($row['created_at'])),
		];
	}
}

$lowStockDrugs = [];
$lowStockResult = $database->query(
	'SELECT name, category, stock_qty, reorder_level, expiry_date
	 FROM drugs
	 WHERE stock_qty <= reorder_level
	 ORDER BY stock_qty ASC, name ASC
	 LIMIT 5'
);

if ($lowStockResult) {
	while ($row = $lowStockResult->fetch_assoc()) {
		$lowStockDrugs[] = [
			'name' => $row['name'],
			'category' => $row['category'],
			'stock_qty' => (int) $row['stock_qty'],
			'reorder_level' => (int) $row['reorder_level'],
			'expiry_date' => date('d M Y', strtotime($row['expiry_date'])),
		];
	}
}

$months = [];
$monthTotals = [];
$monthCursor = new DateTimeImmutable('first day of this month');

for ($i = 5; $i >= 0; $i--) {
	$month = $monthCursor->modify("-$i month");
	$months[$month->format('Y-m')] = $month->format('M');
	$monthTotals[$month->format('Y-m')] = 0;
}

$chartResult = $database->query(
	'SELECT DATE_FORMAT(created_at, "%Y-%m") AS month_key, COALESCE(SUM(stock_qty), 0) AS total_stock
	 FROM drugs
	 WHERE created_at >= DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 5 MONTH), "%Y-%m-01")
	 GROUP BY DATE_FORMAT(created_at, "%Y-%m")
	 ORDER BY month_key'
);

if ($chartResult) {
	while ($row = $chartResult->fetch_assoc()) {
		$monthTotals[$row['month_key']] = (int) $row['total_stock'];
	}
}

jsonResponse([
	'success' => true,
	'user' => [
		'name' => currentUserName(),
		'role' => currentUserRole(),
	],
	'summary' => $summary,
	'chart' => [
		'labels' => array_values($months),
		'values' => array_values($monthTotals),
	],
	'recentPatients' => $recentPatients,
	'recentSales' => $recentSales,
	'lowStockDrugs' => $lowStockDrugs,
]);