# Laptop & Car Gate Scan System (Management-System)

A secure management system now available as a pure HTML/CSS/JS SPA (no PHP required) + Digital SAP Authorization System.

## Run (HTML/CSS/JS only)
1. Open `index.html` in a browser (double-click) or serve statically:
   - Python: `python3 -m http.server 8080`
   - Node: `npx serve .` then open the printed URL
2. Login with seeded admin: username `admin`, password `admin` (localStorage)
3. Use the top navigation to access scanning and reports
4. Open Digital SAP Authorization via the "SAP Forms" tab (or `SAP forms.html`)

Data is stored locally in your browser (localStorage). Use the Reports page to export CSV.

## Legacy PHP (optional)
The PHP application under `public/` and `application/` can still run, but is not required for the SPA workflow. See previous instructions below.

## Previous PHP Instructions (legacy)
- Requirements: PHP 7.4+, MySQL 5.7+
- DB: Create `iyaloo` and import `sql/schema.sql`
- Config: `application/config/constants.php`
- Serve: `php -S 127.0.0.1:8000 -t public`

## Notes
- This SPA keeps state only in localStorage. For multi-user/server-backed workflows, integrate an API.
- Digital SAP Authorization System remains in `SAP forms.html` and works alongside the SPA.
