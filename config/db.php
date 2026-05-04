<?php

// Create one shared database connection for the auth handlers.
function getDbConnection(): mysqli
{
	static $connection = null;

	if (!class_exists('mysqli')) {
		http_response_code(500);
		exit('The PHP mysqli extension is not installed in this environment. Install php-mysql / php8.x-mysql and try again.');
	}

	if ($connection instanceof mysqli) {
		return $connection;
	}

	$host = getenv('PHARMA_DB_HOST') ?: '127.0.0.1';
	$database = getenv('PHARMA_DB_NAME') ?: 'pharmacare';
	$username = getenv('PHARMA_DB_USER') ?: 'root';
	$password = getenv('PHARMA_DB_PASS') ?: '';

	$connection = new mysqli($host, $username, $password, $database);

	if ($connection->connect_error) {
		error_log('PharmaCare DB connection failed: ' . $connection->connect_error);
		http_response_code(500);
		exit('Database connection failed.');
	}

	$connection->set_charset('utf8mb4');

	return $connection;
}
