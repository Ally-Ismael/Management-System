# Management System

- Authentication with roles, CSRF, session hardening
- Assets (Laptops, Cars), Scans, Attendance
- Reporting with CSV export
- New: Users management (pending verification, CSV export), Drivers, Activity logs

## Database

Run `sql/schema.sql` to create/update tables. New tables: `drivers`, `activity_logs`. `users` now has `verified` column.
