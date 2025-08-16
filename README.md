# Laptop & Car Gate Scan System (Management-System)

A secure PHP MVC application for managing laptops and cars scanning in/out, IT inventory, user management (login/register, roles), reports, and a NamWater employee attendance register.

## Features
- Authentication: Login, registration (bcrypt), roles (admin, user), session hardening
- CSRF protection for all forms, input validation, rate-limited login
- Inventory: Laptops and Cars (CRUD)
- Scanning: Laptop in/out, Car in/out
- Reporting: Paginated views, CSV export
- Attendance: NamWater employees register (check-in/out)
- Security headers, prepared statements, basic MVC structure

## Requirements
- PHP 7.4+ (or 8.x)
- MySQL 5.7+ / MariaDB 10.4+
- Apache/Nginx (or PHP built-in server for dev)

## Quick Start
1. Create database and tables:
   - Create database `iyaloo` in MySQL
   - Run SQL at `sql/schema.sql`

2. Configure DB credentials:
   - Edit `application/config/constants.php` (DB_HOST, DB_USER, DB_PASS, DB_NAME)

3. Serve the app:
   - Apache: Point DocumentRoot to `public/`
   - Nginx: Serve `public/` and route all to `index.php`
   - PHP built-in: `php -S 127.0.0.1:8000 -t public`

4. First login:
   - Register a user via `/index.php?r=auth/register` (first user becomes admin)
   - Or insert an admin into `users` table with role `admin`

## Security Notes
- Prepared statements via mysqli
- CSRF tokens on all forms
- Session hardening, login rate-limit
- Security headers in every response

## Structure
```
/application
  /config
  /controllers
  /models
  /views
    /layouts
    /partials
  /assets
    /css
    /js
  /lib
    /helpers
/public
  index.php
/sql
  schema.sql
```

## Environment Variables (optional)
You can override config using environment variables:
- `APP_BASE_URL`
- `DB_HOST`, `DB_USER`, `DB_PASS`, `DB_NAME`
- `ALLOW_SELF_REGISTRATION` ("1"/"0")

## Exports
- Reports CSV endpoints under `reports` (links in UI)

## Backups
- Use `mysqldump iyaloo > backup.sql` regularly. Consider scheduling.

## Testing
- Manual E2E via UI.
- For PHPUnit, add composer and tests directory (not included by default).

## License
Proprietary/internal use.
