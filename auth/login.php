<?php

session_start();
require_once __DIR__ . '/../config/db.php';

function isJsonRequest(): bool
{
	$accept = $_SERVER['HTTP_ACCEPT'] ?? '';
	$xRequestedWith = $_SERVER['HTTP_X_REQUESTED_WITH'] ?? '';

	return str_contains($accept, 'application/json') || strtolower($xRequestedWith) === 'xmlhttprequest';
}

function sendAuthResponse(bool $success, string $message = '', array $extra = [], int $statusCode = 200): void
{
	if (isJsonRequest()) {
		http_response_code($statusCode);
		header('Content-Type: application/json');
		echo json_encode(array_merge([
			'success' => $success,
			'message' => $message,
		], $extra));
		exit();
	}
}

// Keep the redirect target small and predictable.
function redirectWithError(string $mode, string $message): void
{
	header('Location: ../index.php?auth=' . rawurlencode($mode) . '&login_error=' . rawurlencode($message));
	exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	sendAuthResponse(false, 'Please sign in with the form first.', [], 405);
	redirectWithError('login', 'Please sign in with the form first.');
}

$email = strtolower(trim($_POST['email'] ?? ''));
$password = $_POST['password'] ?? '';
$role = trim($_POST['role'] ?? 'pharmacist');

if ($email === '' || $password === '') {
	sendAuthResponse(false, 'Email and password are required.', [], 422);
	redirectWithError('login', 'Email and password are required.');
}

$database = getDbConnection();

// Look up the account by email so we can verify the stored password hash.
$statement = $database->prepare('SELECT id, full_name, email, password_hash, role FROM users WHERE email = ? LIMIT 1');
if (!$statement) {
	error_log('Login prepare failed: ' . $database->error);
	sendAuthResponse(false, 'We could not sign you in right now.', [], 500);
	redirectWithError('login', 'We could not sign you in right now.');
}

$statement->bind_param('s', $email);
$statement->execute();
$statement->store_result();
$statement->bind_result($userId, $fullName, $userEmail, $passwordHash, $userRole);
$user = $statement->fetch();
$statement->close();

if (!$user) {
	sendAuthResponse(false, 'Invalid email or password.', [], 401);
	redirectWithError('login', 'Invalid email or password.');
}

if (!password_verify($password, $passwordHash)) {
	sendAuthResponse(false, 'Invalid email or password.', [], 401);
	redirectWithError('login', 'Invalid email or password.');
}

// Refresh the session id so a new login gets a fresh authenticated session.
session_regenerate_id(true);
$_SESSION['user_id'] = (int) $userId;
$_SESSION['user_name'] = $fullName;
$_SESSION['user_email'] = $userEmail;
$_SESSION['user_role'] = $userRole ?: $role;

sendAuthResponse(true, 'Signed in successfully.', ['redirect' => '../pages/dashboard.php']);
header('Location: ../pages/dashboard.php');
exit();
