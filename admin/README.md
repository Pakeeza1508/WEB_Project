Admin panel setup

1. Run seed and migrations (one-time):
   - Open in browser: http://localhost/webProj/admin/seed_admin.php
     - Creates `admin_users` table and a default admin (admin/admin123). Delete this file after use.
   - Open: http://localhost/webProj/admin/migrate.php
     - Adds columns and tables (`stock`, `discount`, `description`, `categories`, `order_items`, `is_active` on `users`). Delete after success.

2. Login: http://localhost/webProj/admin/login.php

3. Pages:
   - Dashboard: `admin/index.php`
   - Products: `admin/products.php` (add/edit/delete)
   - Categories: `admin/categories.php`
   - Orders: `admin/orders.php` (view, update status, invoice)
   - Users: `admin/users.php` (block/unblock, delete)
   - Reviews: `admin/reviews.php` (approve/reject, delete)
   - Reports: `admin/reports.php`

4. Assets: shared admin stylesheet is at `admin/assets/css/admin.css`.

Security notes
- Remove `seed_admin.php` and `migrate.php` after use.
- Change default admin password after first login.

Support
- If any page errors, check PHP error logs and ensure `db.php` credentials match your MySQL setup.
