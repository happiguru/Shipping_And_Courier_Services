# NEXTGEN Logistics — Shipping and Courier Services

NEXTGEN Logistics is a PHP website with a public marketing site, shipment lookup, and an administrator workspace for creating and managing shipments. It uses PHP PDO with SQLite and requires no paid database or Node.js build step for normal hosting.

## Deploy to InfinityFree

### Requirements
- An InfinityFree account and a domain/subdomain attached to it.
- The newest PHP version offered by the hosting control panel.
- PHP PDO and `pdo_sqlite` enabled.
- A writable directory for the SQLite database.

InfinityFree support discussions report PDO SQLite support, but verify the PHP version and extensions available on your own account. PHP code cannot enable an extension disabled by the host.

### Upload structure
Upload the existing website into the domain's `htdocs/` directory, preserving paths:

```text
htdocs/
  index.php
  admin/
  assets/
  controllers/
  css/
  database/
  js/
  views/
  storage/
    .htaccess
  .htaccess
  health-check.php
```

The database defaults to `storage/nextgen.sqlite`; `storage/.htaccess` denies direct web access. If your host permits a writable directory outside the document root, configure `SQLITE_PATH` to use that private absolute path. Do not commit a production SQLite file, setup key, or other secrets.

### Initialize the database
1. Upload the files.
2. Confirm PHP can write to `storage/`. The connection attempts to create this directory if missing.
3. Open `/health-check.php` once (or another page that actually accesses the database). The homepage alone does not need to connect to SQLite. `database/connection.php` initializes missing tables and indexes using idempotent `CREATE TABLE IF NOT EXISTS` statements.
4. If it fails, check PHP error logs and verify `pdo_sqlite` is enabled and storage is writable. Public errors intentionally do not reveal paths or stack traces.

The schema includes administrators, users, shipments, tracking events, contact messages, quote requests, and login throttling. Existing `admin` and `tracking` column names are retained. This does not automatically migrate records from a separate MySQL database; back up and migrate such records separately before going live.

### Create the first administrator
1. Generate a unique secret with at least 32 characters. Never commit it to GitHub or put it in a public URL.
2. If environment variables are supported, configure `ADMIN_SETUP_KEY` there. Otherwise copy `database/private-config.example.php` to `database/private-config.php` using the hosting file manager, edit the copy to set a unique random `ADMIN_SETUP_KEY` of at least 32 characters, and never upload/commit that private file to GitHub. The private file is excluded by `.gitignore`.
3. Open `/admin-setup.php`, enter the secret, and create a unique username and a strong password of at least 12 characters.
4. Setup refuses to create an administrator if one already exists. After success, remove the environment key or delete `database/private-config.php`, and disable/delete `admin-setup.php` if practical.
5. Sign in at `/admin/index.php`.

Do not leave a hard-coded setup secret in a public repository. If your account cannot provide a private configuration value, do not expose the setup route with a permanent key.

### Verify
- Open homepage, services, about, contact, tracking, and quotation pages.
- Sign in at `/admin/index.php`.
- Create a test shipment and look it up by its generated tracking number.
- Test status updates, including On Hold wherever the status form supports it.
- Contact messages and quote requests are stored in SQLite. Quote email delivery also depends on host mail support and `QUOTE_RECIPIENT_EMAIL` / `QUOTE_FROM_EMAIL`.
- Open `/health-check.php`: it returns only a generic healthy/unavailable status and never exposes database paths. It can initialize the database on first access.
- Confirm `/storage/nextgen.sqlite` cannot be downloaded.

## Local development
Install PHP with PDO SQLite enabled and run `php -S 127.0.0.1:8000` from the project root. The built-in PHP server is for development only.

## Database notes
- Database access should use `database/connection.php` and `app_db()`.
- Use prepared statements for SQL values.
- SQLite is a single-file database; write concurrency is lower than a dedicated database server.
- The PHP website does not require Node.js, MySQL, background workers, or paid services.

The existing frontend, visual assets, and application layout are retained. See `LICENSE` for license details.
