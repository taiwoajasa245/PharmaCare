<?php

require_once __DIR__ . '/_bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	jsonResponse(['success' => false, 'message' => 'Method not allowed'], 405);
}

$fullName = trim($_POST['full_name'] ?? '');
$email = strtolower(trim($_POST['email'] ?? ''));
$role = trim($_POST['role'] ?? 'pharmacist');

if ($fullName === '' || $email === '') {
	jsonResponse(['success' => false, 'message' => 'Full name and email are required.'], 422);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	jsonResponse(['success' => false, 'message' => 'Please enter a valid email address.'], 422);
}

$database = getDbConnection();

$statement = $database->prepare('UPDATE users SET full_name = ?, email = ?, role = ? WHERE id = ?');
if (!$statement) {
	jsonResponse(['success' => false, 'message' => 'Unable to update your profile right now.'], 500);
}

$userId = currentUserId();
$statement->bind_param('sssi', $fullName, $email, $role, $userId);

if (!$statement->execute()) {
	jsonResponse(['success' => false, 'message' => 'Unable to update your profile right now.'], 500);
}

$_SESSION['user_name'] = $fullName;
$_SESSION['user_email'] = $email;
$_SESSION['user_role'] = $role;

jsonResponse([
	'success' => true,
	'message' => 'Profile updated successfully.',
	'user' => [
		'name' => $fullName,
		'email' => $email,
		'role' => $role,
	],
]);