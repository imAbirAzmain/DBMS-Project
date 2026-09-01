# Garments Management System

This project is an Oracle-backed garments factory management dashboard built with PHP and OCI8, while keeping the original frontend structure and UI intact.

## Project purpose

- Keep the current Bootstrap-based dashboard and page flow
- Connect the app to the Oracle schema already defined in the project
- Implement a realistic backend subset for login, dashboard metrics, and module data
- Demonstrate the required DBMS features through the website:
  - Function
  - Subquery
  - View
  - ADT
  - PL/SQL procedure
  - Cursor
  - Exception handling

## Tech stack

- PHP
- OCI8 extension for Oracle connectivity
- XAMPP on macOS/Linux/Windows
- Bootstrap 5 frontend

## Setup

1. Start XAMPP and ensure the OCI8 extension is enabled in PHP.
2. Set your Oracle connection values in [config/db.php](config/db.php) or export the matching environment variables before running the app.
3. Create the Oracle schema using the SQL files in the [database](database) folder.
4. Run the app from the project root under your local web server.

## Login

Use the employee ID and password from the Oracle Employee table.

- Incharge pages are for incharge-role user accounts
- Worker pages are for worker-role accounts
- The central auth layer lives in [config/auth.php](config/auth.php)
- All `pages/worker_*.php` routes require a worker session; all other module routes require an incharge session.
- Module "Add" forms submit through [actions.php](actions.php), which validates the session CSRF token and writes to the matching Oracle tables.

## Backend writes

The existing modal forms now create records for accounts, BOMs, buyers, final products,
incharges, inspections, machinery, materials, orders and styles, packaging, payments,
production stages, shipments, suppliers, and workers. The corresponding relationship
records are created where the form supplies the necessary related IDs.

## Feature pages

- [login.php](login.php) – role-aware login
- [pages/dashboard.php](pages/dashboard.php) – live Oracle metrics
- [pages/workers.php](pages/workers.php) – worker roster
- [pages/orders.php](pages/orders.php) – order records
- [pages/buyers.php](pages/buyers.php) – buyer data
- [pages/production.php](pages/production.php) – production stage dashboard
- [pages/payments.php](pages/payments.php) – payment ledger
- [pages/advanced_reports.php](pages/advanced_reports.php) – advanced Oracle DB feature demo

## Oracle feature objects

The Oracle objects for advanced DB features are defined in [database/advanced_features.sql](database/advanced_features.sql).

## Notes

- The app avoids a full rewrite of the existing interface.
- The backend uses Oracle data where available and keeps the working frontend structure intact.
- If Oracle is not reachable, the application shows the configured fallback behavior instead of crashing.
