Admin testing checklist

Before testing
- Run `seed_admin.php` and `migrate.php` once. Delete them after.
- Ensure `uploads/products/` is writable for image uploads.

Login and auth
- [ ] Open `admin/login.php` and sign in with admin credentials.
- [ ] Verify protected pages redirect to login when not authenticated.

Products
- [ ] Add a new product with image, price, stock.
- [ ] Edit product details and confirm changes persist.
- [ ] Delete a product and confirm image file is removed locally.

Categories
- [ ] Add, edit, delete categories.
- [ ] Create product with new category via product form.

Orders
- [ ] Place an order as a user; confirm it appears in `admin/orders.php`.
- [ ] View order items and update status through `order_view.php`.
- [ ] Generate invoice and print preview.

Users
- [ ] Block a user and verify they cannot login (if applicable).
- [ ] Unblock a user and verify access restored.
- [ ] Delete a user and confirm related records are handled.

Inventory
- [ ] Create product with low stock (<= threshold) and verify it appears in dashboard alerts.

Reviews
- [ ] Approve a pending review and confirm it is marked approved.
- [ ] Reject/delete abusive review and confirm removal.

Reports
- [ ] Open `admin/reports.php` and verify monthly revenue and top products data.

Final cleanup
- [ ] Delete `seed_admin.php` and `migrate.php` from the server.
- [ ] Change default admin password.

If you want, I can run through the checklist and make fixes — tell me which tests to run first.