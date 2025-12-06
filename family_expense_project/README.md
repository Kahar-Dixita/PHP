# Multi-Family Expense Manager (PHP + MySQL)

Files included:
- schema.sql : database schema (merged admin table)
- config.php : DB connection and session start
- functions.php : helper functions and color palette
- public/styles.css : pastel theme (colors inspired by your image)
- register.php : admin (family head) registration
- login.php : admin login
- admin_dashboard.php : manage members, allocations, view recent expenses
- add_member.php / edit_member.php / delete_member.php : member CRUD
- member_dashboard.php : member simple login and dashboard
- spend.php : record an expense (updates member spent_amount)
- logout.php

Setup:
1. Create a MySQL database or import `schema.sql` (phpMyAdmin or CLI).
2. Update DB credentials in config.php.
3. Place files in your PHP web root (e.g., htdocs/family_expense_project).
4. Ensure the webserver can write sessions and the project folders.

This is a demo skeleton. For production, secure member authentication, CSRF protection, and proper password hashes are required.
