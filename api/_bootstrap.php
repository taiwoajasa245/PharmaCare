<?php

function jsonResponse(array $payload, int $statusCode = 200): void
{
	http_response_code($statusCode);
	echo json_encode($payload);
	exit();
}

session_start();
require_once __DIR__ . '/../config/db.php';

header('Content-Type: application/json');

$sessionUserId = (int) ($_SESSION['user_id'] ?? 0);
if ($sessionUserId < 1) {
	jsonResponse([
		'success' => false,
		'message' => 'Unauthorized',
	], 401);
}

$database = getDbConnection();
$userCheck = $database->prepare('SELECT id FROM users WHERE id = ? LIMIT 1');
if (!$userCheck) {
	jsonResponse([
		'success' => false,
		'message' => 'Unable to validate your session right now.',
	], 500);
}

$userCheck->bind_param('i', $sessionUserId);
$userCheck->execute();
$userResult = $userCheck->get_result();
$userRow = $userResult ? $userResult->fetch_assoc() : null;
if (!$userRow) {
	session_unset();
	session_destroy();
	jsonResponse([
		'success' => false,
		'message' => 'Your session expired. Please sign in again.',
	], 401);
}

function currentUserId(): int
{
	return (int) ($_SESSION['user_id'] ?? 0);
}

function currentUserName(): string
{
	return trim((string) ($_SESSION['user_name'] ?? ''));
}

function currentUserRole(): string
{
	return trim((string) ($_SESSION['user_role'] ?? ''));
}