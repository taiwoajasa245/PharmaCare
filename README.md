# ⚕️ PharmaCare

PharmaCare is a PHP web app with authentication backed by MySQL.

## What the app does

PharmaCare helps a pharmacist manage inventory and record sales, while keeping a simple patient history.

## Features

- User authentication (register/login)
- Inventory: add, edit, stock in/out, delete, and filter drugs
- Sales: record prescription or OTC sales
- Patients: store patient details and recent prescriptions/sales
- Dashboard: totals, low stock, and recent activity

## Requirements

- PHP 8.x
- MySQL 8.x
- Docker (optional, for the provided setup scripts)

## Local or Codespaces Setup

The app writes users to the `users` table via:

- `auth/register.php` for inserts
- `auth/login.php` for reads and password verification

### Fastest start (one command)

```bash
./scripts/start-app-test.sh
```

This command:

- Runs `./scripts/setup-mysql-test.sh`
- Exports the `PHARMA_DB_*` variables needed by the app
- Starts PHP on `0.0.0.0:8080` (or `PHARMA_APP_PORT` if set)

### Manual setup

#### 1) Start MySQL and load the schema

```bash
./scripts/setup-mysql-test.sh
```

This script:

- Starts (or reuses) a container named `pharmacare-mysql`
- Waits until MySQL is ready
- Imports the schema from `database/pharma.sql`

#### 2) Export DB variables for PHP

```bash
export PHARMA_DB_HOST=127.0.0.1
export PHARMA_DB_NAME=pharmacare
export PHARMA_DB_USER=root
export PHARMA_DB_PASS=
```

#### 3) Run the app

```bash
php -S 0.0.0.0:8080
```

Open the forwarded port and create a user via the sign-up form.

#### 4) Verify users are being saved

```bash
docker exec -it pharmacare-mysql mysql -uroot pharmacare \
	-e "SELECT id, full_name, email, role, created_at FROM users ORDER BY id DESC LIMIT 10;"
```

If your new account appears in the query output, the setup is working.

## Setup on another machine (no Docker)

1) Create a MySQL database named `pharmacare`.
2) Import the schema:

```bash
mysql -h <HOST> -u <USER> -p <DB_NAME> < database/pharma.sql
```

3) Export the DB variables:

```bash
export PHARMA_DB_HOST=<HOST>
export PHARMA_DB_NAME=pharmacare
export PHARMA_DB_USER=<USER>
export PHARMA_DB_PASS=<PASSWORD>
```

4) Start the PHP server:

```bash
php -S 0.0.0.0:8080
```

## Key pages

- `/auth/register.php` and `/auth/login.php`
- `/pages/dashboard.php`
- `/pages/inventory.php`
- `/pages/patients.php`

## Environment variables

- `PHARMA_DB_HOST`
- `PHARMA_DB_NAME`
- `PHARMA_DB_USER`
- `PHARMA_DB_PASS`