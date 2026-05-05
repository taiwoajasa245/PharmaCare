<?php

$root = realpath(__DIR__ . '/..');
$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$requestPath = $requestPath ?: '/';

if ($requestPath === '/') {
	$requestPath = '/index.php';
}

$candidate = realpath($root . $requestPath);
if ($candidate === false || strpos($candidate, $root) !== 0 || !is_file($candidate)) {
	http_response_code(404);
	echo 'Not Found';
	exit;
}

$extension = strtolower(pathinfo($candidate, PATHINFO_EXTENSION));

if ($extension === 'php') {
	$previousCwd = getcwd();
	chdir(dirname($candidate));
	require $candidate;
	if ($previousCwd !== false) {
		chdir($previousCwd);
	}
	exit;
}

if (function_exists('mime_content_type')) {
	$mimeType = mime_content_type($candidate);
	if ($mimeType) {
		header('Content-Type: ' . $mimeType);
	}
}

readfile($candidate);
exit;
