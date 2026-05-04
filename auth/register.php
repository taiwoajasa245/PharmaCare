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

function redirectWithError(string $mode, string $message): void
{
    $email = strtolower(trim($_POST['email'] ?? ''));
    $role = trim($_POST['role'] ?? 'pharmacist');

    header(
        'Location: ../index.php?signup_error=' . rawurlencode($message)
        . '&signup_email=' . rawurlencode($email)
        . '&signup_role=' . rawurlencode($role)
    );
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendAuthResponse(false, 'Please create your account with the form first.', [], 405);
    redirectWithError('signup', 'Please create your account with the form first.');
}

$fullName = trim($_POST['full_name'] ?? '');
$email = strtolower(trim($_POST['email'] ?? ''));
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';
$role = trim($_POST['role'] ?? 'pharmacist');

if ($email === '' || $password === '') {
    sendAuthResponse(false, 'Email and password are required.', [], 422);
    redirectWithError('signup', 'Email and password are required.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    sendAuthResponse(false, 'Please enter a valid email address.', [], 422);
    redirectWithError('signup', 'Please enter a valid email address.');
}

if ($confirmPassword !== '' && $password !== $confirmPassword) {
    sendAuthResponse(false, 'Passwords do not match.', [], 422);
    redirectWithError('signup', 'Passwords do not match.');
}

if (strlen($password) < 8) {
    sendAuthResponse(false, 'Password must be at least 8 characters long.', [], 422);
    redirectWithError('signup', 'Password must be at least 8 characters long.');
}

if ($fullName === '') {
    // Use the part before @ as fallback so existing signup steps still work without full name.
    $derivedName = strstr($email, '@', true) ?: 'PharmaCare User';
    $fullName = ucwords(str_replace(['.', '_', '-'], ' ', $derivedName));
}

$database = getDbConnection();

// Check for an existing account first so we can give a clear message.
$checkStatement = $database->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
if (!$checkStatement) {
    error_log('Signup duplicate check failed: ' . $database->error);
    sendAuthResponse(false, 'We could not create your account right now.', [], 500);
    redirectWithError('signup', 'We could not create your account right now.');
}

$checkStatement->bind_param('s', $email);
$checkStatement->execute();
$checkStatement->store_result();

if ($checkStatement->num_rows > 0) {
    $checkStatement->close();
    sendAuthResponse(false, 'That email is already registered.', [], 409);
    redirectWithError('signup', 'That email is already registered.');
}

$checkStatement->close();

$passwordHash = password_hash($password, PASSWORD_DEFAULT);
$statement = $database->prepare('INSERT INTO users (full_name, email, password_hash, role) VALUES (?, ?, ?, ?)');
if (!$statement) {
    error_log('Signup insert prepare failed: ' . $database->error);
    sendAuthResponse(false, 'We could not create your account right now.', [], 500);
    redirectWithError('signup', 'We could not create your account right now.');
}

$statement->bind_param('ssss', $fullName, $email, $passwordHash, $role);

if (!$statement->execute()) {
    error_log('Signup insert failed: ' . $statement->error);
    $statement->close();
    sendAuthResponse(false, 'We could not create your account right now.', [], 500);
    redirectWithError('signup', 'We could not create your account right now.');
}

$newUserId = $database->insert_id;
$statement->close();

// Logging the user in immediately keeps the signup flow smooth.
session_regenerate_id(true);
$_SESSION['user_id'] = $newUserId;
$_SESSION['user_name'] = $fullName;
$_SESSION['user_email'] = $email;
$_SESSION['user_role'] = $role;

// Clear application data for a fresh start for this new user.
// This removes existing inventory, patients, sales, prescriptions and receipts.
// WARNING: This will erase data for all users in this development environment.
$clearTables = ['drugs', 'patients', 'sales', 'prescriptions', 'receipts'];
// Temporarily disable foreign key checks while truncating.
$database->query('SET FOREIGN_KEY_CHECKS = 0');
foreach ($clearTables as $t) {
    // Use TRUNCATE to reset auto-increment counters as well.
    @$database->query("TRUNCATE TABLE `" . $database->real_escape_string($t) . "`");
}
$database->query('SET FOREIGN_KEY_CHECKS = 1');

sendAuthResponse(true, 'Account created successfully.', ['redirect' => '../pages/dashboard.php']);
header('Location: ../pages/dashboard.php');
exit();
