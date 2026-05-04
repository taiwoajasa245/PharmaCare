# PharmaCare

PharmaCare is a PHP web app with authentication backed by MySQL.

## MySQL Test Setup (Codespaces or Local)

This project already stores users in the `users` table through:

- `auth/register.php` for inserts
- `auth/login.php` for reads and password verification

Use the setup script below to start MySQL in Docker and import `database/pharma.sql`.

### Fastest option (one command)

```bash
./scripts/start-app-test.sh
```

This command will:

- Run `./scripts/setup-mysql-test.sh`
- Export the `PHARMA_DB_*` variables required by the app
- Start PHP on `0.0.0.0:8080` (or `PHARMA_APP_PORT` if set)

### 1. Start MySQL and load schema

```bash
./scripts/setup-mysql-test.sh
```

The script will:

- Start (or reuse) a container named `pharmacare-mysql`
- Wait until MySQL is ready
- Import the schema from `database/pharma.sql`

### 2. Export DB variables for PHP

```bash
export PHARMA_DB_HOST=127.0.0.1
export PHARMA_DB_NAME=pharmacare
export PHARMA_DB_USER=root
export PHARMA_DB_PASS=
```

### 3. Run the app

```bash
php -S 0.0.0.0:8080
```

Open the forwarded port and create a user via the sign-up form.

### 4. Verify users are being saved

```bash
docker exec -it pharmacare-mysql mysql -uroot pharmacare -e "SELECT id, full_name, email, role, created_at FROM users ORDER BY id DESC LIMIT 10;"
```

If you register a new account and it appears in this query output, your test setup is working.