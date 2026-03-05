# Deployment v1.0.0 (Laravel + Angular)

This guide is for hosting where you cannot run `git pull` and cannot build Angular on the server.
Deployment flow: build locally -> upload via FTP -> configure Laravel via SSH.

## 1) Hosting Requirements

- PHP 8.1+
- Composer
- Node.js + npm (for Angular build)
- SSH access to the application directory
- MySQL/MariaDB
- SSH access to the application directory
- FTP/SFTP access to upload files
- MySQL/MariaDB
- `public_html` configured as the project webroot

## 2) Build Angular Locally (production)

Run on your local machine in the project root:

```bash
npm ci
npm run deploy:dashboard
```

This builds Angular and puts production files into `public_html/angular-assets`.

## 3) Configure Angular prod environment locally

Edit [angular-src/src/environments/environment.prod.ts](angular-src/src/environments/environment.prod.ts) before building.

Recommended setup for same-domain hosting:

```ts
export const environment = {
    apiToken: '',
    apiUrl: '/api'
};
```

If API is hosted under another domain/subdomain, set the full URL in `apiUrl`.

## 4) Upload project files via FTP/SFTP

Upload application files from your local project to the remote hosting account.

At minimum, upload updated Laravel source and built frontend assets:

- `app/`
- `bootstrap/`
- `config/`
- `public_html/` (including `angular-assets/`)
- `resources/views`
- `routes/`
- `artisan`
- `composer.json`
- `composer.lock`

Do not overwrite remote secrets/runtime data unless intentional:

- keep remote `.env`
- keep remote `storage/` data

## 5) Install/refresh PHP dependencies on server (SSH)

In the project directory on server:

```bash
/usr/local/php82-fpm/bin/php /usr/bin/composer install --no-dev --optimize-autoloader
```

If hosting requires explicit PHP binary, see section below.

## 6) Configure Laravel `.env` on server (production)

Set or verify at least:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=...
DB_PORT=3306
DB_DATABASE=...
DB_USERNAME=...
DB_PASSWORD=...

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

Generate app key once if missing:

```bash
php artisan key:generate --force
```

## 8) Reset and rebuild Laravel caches

Run after each deployment:

```bash
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 9) Permissions and runtime directories

Make sure web server can write to:

- `storage/`
- `bootstrap/cache/`

Example:

```bash
chmod -R ug+rwx storage bootstrap/cache
```

## 10) Post-deploy smoke test

- Open `/dashboard`
- Verify login and `/api/*` calls
- Verify events/finances lists load correctly (including balances)
- In case of errors, check `storage/logs/laravel.log`

## 11) Minimal rollback

- Re-upload previous stable files and run sections 8 (cache reset) again.

## 12) FTP exclude list (do not upload)

To speed up deployment and avoid accidental overwrites, exclude these from FTP/SFTP upload:

- `.git/`
- `.github/` (if present)
- `node_modules/`
- `angular-src/node_modules/`
- `vendor/` (install on server with Composer instead)
- `tests/`
- `storage/logs/*`
- `storage/framework/cache/*`
- `storage/framework/sessions/*`
- `storage/framework/views/*`
- local IDE/system files (`.idea/`, `.vscode/`, `.DS_Store`, `Thumbs.db`)

Keep these server-side files/directories intact (do not overwrite unless intentional):

- `.env`
- `storage/` user/runtime data

## 13) Using a specific PHP version over SSH

If your hosting requires an explicit PHP binary path, use:

```bash
/usr/local/phpXX-fpm/bin/php
```

Replace `phpXX` with your target version, for example:

```bash
/usr/local/php82-fpm/bin/php
```

You can also define a helper variable for the whole session:

```bash
export PHP_BIN=/usr/local/php82-fpm/bin/php
```

Then run Artisan commands as:

```bash
$PHP_BIN artisan migrate --force
$PHP_BIN artisan optimize:clear
$PHP_BIN artisan config:cache
$PHP_BIN artisan route:cache
$PHP_BIN artisan view:cache
```

## 14) Troubleshooting `419 Page Expired` after login

If login returns `419 Page Expired`, it is usually a session/CSRF configuration issue.

### A) Verify `.env` on server

For HTTPS production, verify:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

SESSION_DRIVER=file
SESSION_DOMAIN=your-domain.com
SESSION_SECURE_COOKIE=true

SANCTUM_STATEFUL_DOMAINS=your-domain.com,www.your-domain.com
CORS_ALLOWED_ORIGINS=https://your-domain.com,https://www.your-domain.com
```

Notes:
- Use real domain values (no protocol in `SESSION_DOMAIN`).
- If you only use one hostname (with or without `www`), keep config consistent everywhere.

### B) Clear and rebuild Laravel cache after `.env` changes

```bash
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Or with custom PHP binary:

```bash
$PHP_BIN artisan optimize:clear
$PHP_BIN artisan config:cache
$PHP_BIN artisan route:cache
$PHP_BIN artisan view:cache
```

### C) Verify write permissions

Laravel must be able to write sessions/cache:

```bash
chmod -R ug+rwx storage bootstrap/cache
```

### D) Browser check

- Open login page in a private/incognito window.
- Ensure cookies are enabled.
- Verify only one canonical domain is used (avoid switching between `www` and non-`www` while testing).
