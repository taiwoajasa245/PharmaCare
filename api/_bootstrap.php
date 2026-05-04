<?php

session_start();
require_once __DIR__ . '/../config/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
	http_response_code(401);
	echo json_encode([
		'success' => false,
		'message' => 'Unauthorized',
	]);
	exit();
}

function jsonResponse(array $payload, int $statusCode = 200): void
{
	http_response_code($statusCode);
	echo json_encode($payload);
	exit();
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