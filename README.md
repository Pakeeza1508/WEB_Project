# LuxeStore — PHP & MySQL E-Commerce Platform

> **Semester Web Development Project**  
> A database-backed e-commerce application built with **PHP, MySQL, HTML, CSS, JavaScript, AJAX, and JSON**.

LuxeStore was developed as a semester web-development project to demonstrate server-side PHP development, relational database design, authentication, CRUD operations, shopping workflows, and administrative management.

The project includes a customer-facing storefront and a separate admin area for managing products, categories, users, orders, reviews, inventory, and reports.

---

## Key Features

### Customer Side

- User registration and login
- Session-based authentication
- Product browsing
- Featured and catalog product collections
- Shopping cart
- Wishlist
- Quantity updates
- Checkout and order creation
- Order-item tracking
- Product reviews
- Inventory-aware product data

### Admin Side

- Admin authentication
- Dashboard with operational summaries
- Product management
- Category management
- Order management
- User management
- Review moderation
- Inventory monitoring
- Low-stock visibility
- Sales and revenue reports
- Category-wise sales analysis
- Top-selling product reports
- Customer spending reports

---

## AJAX + JSON Cart Update

The cart includes an asynchronous quantity-update flow so the user can update an item without refreshing the page.

```text
cart.php
   ↓
JavaScript fetch()
   ↓
JSON request
   ↓
cart_api.php
   ↓
PHP + session validation
   ↓
Prepared MySQL update
   ↓
JSON response
   ↓
DOM subtotal + grand total update
```

The frontend sends a JSON payload containing the cart item and new quantity:

```javascript
fetch("cart_api.php", {
    method: "POST",
    headers: {
        "Content-Type": "application/json"
    },
    body: JSON.stringify({
        cart_id: Number(cartId),
        qty: Number(qty)
    })
});
```

The PHP endpoint reads the JSON request, verifies the authenticated user, updates the database, recalculates totals, and returns a JSON response.

This provides a practical example of:

- AJAX communication
- `fetch()`
- JSON request/response handling
- PHP API-style endpoint handling
- asynchronous DOM updates
- session-aware database operations

---

## Technology Stack

| Area | Technology |
|---|---|
| Backend | PHP |
| Database | MySQL |
| Database APIs | MySQLi / prepared statements |
| Frontend | HTML5, CSS3, JavaScript |
| Async requests | AJAX via `fetch()` |
| Data exchange | JSON |
| Authentication | PHP sessions |
| Password security | `password_hash()` / `password_verify()` |
| Local development | XAMPP / WAMP / compatible PHP + MySQL environment |

---

## Authentication

The application uses PHP sessions for authenticated user access.

Registration includes:

- input validation;
- email-format validation;
- duplicate username/email checks;
- password hashing with `password_hash()`.

Login uses:

- prepared SQL statements;
- password verification with `password_verify()`;
- PHP session creation after successful authentication.

Simplified flow:

```text
Registration
    ↓
Validate input
    ↓
Check duplicate account
    ↓
password_hash()
    ↓
Store user

Login
    ↓
Fetch account
    ↓
password_verify()
    ↓
Create PHP session
```

---

## Database Architecture

The repository includes a MySQL schema in:

```text
shopping_db_schema.sql
```

Core tables include:

```text
users
products
categories
shop_products
cart
wishlist
orders
order_items
reviews
```

The schema also supports:

- stock quantities;
- product discounts;
- product descriptions;
- ratings;
- timestamps;
- order statuses;
- foreign-key relationships;
- category data.

---

## Cart Architecture

The cart can contain products from two product collections:

```text
products
+
shop_products
```

The `item_type` field identifies the source collection.

The cart display uses conditional joins to resolve the correct product name, price, image, and quantity.

Cart operations include:

- add item;
- increment existing item;
- update quantity;
- remove item;
- calculate subtotal;
- calculate grand total;
- user ownership checks.

The AJAX quantity-update endpoint also validates cart ownership using the authenticated session before updating the database.

---

## Checkout and Orders

The checkout page reads current cart contents directly from the database and calculates the order summary server-side.

The order system stores order ownership, totals, status, individual order items, quantities, and purchase-time price snapshots.

---

## Admin Dashboard

The admin area contains management pages for:

```text
Dashboard
Products
Categories
Orders
Users
Reviews
Reports
Admin Accounts
```

The project includes reporting logic for:

- total revenue;
- total orders;
- total customers;
- pending orders;
- order-status distribution;
- category-wise sales;
- monthly revenue;
- top-selling products;
- top customers.

---

## Security Practices

Implemented security-related practices include:

- prepared SQL statements for sensitive user/database operations;
- password hashing;
- password verification;
- authenticated PHP sessions;
- user ownership validation on cart updates/removals;
- server-side input checks.

This is an academic/portfolio project rather than a production payment system. Additional production hardening such as CSRF protection, stricter output escaping across every view, rate limiting, and production secrets management would be required before real-world deployment.

---

## Repository Structure

```text
WEB_Project/
│
├── admin/
│   ├── assets/
│   ├── inc/
│   ├── categories.php
│   ├── index.php
│   ├── orders.php
│   ├── products.php
│   ├── reports.php
│   ├── reviews.php
│   └── users.php
│
├── add_to_cart.php
├── auth.php
├── auth_check.php
├── cart.php
├── cart_api.php
├── checkout.php
├── db.php
├── login.php
├── manage_cart.php
├── register.php
├── shopping_db_schema.sql
└── README.md
```

The repository also contains additional storefront, order-processing, styling, migration, and administrative files.

---

## Local Setup

### Requirements

Install a local PHP/MySQL environment such as XAMPP, WAMP, MAMP, or a manually configured PHP + MySQL environment.

### 1. Clone the repository

```bash
git clone https://github.com/Pakeeza1508/WEB_Project.git
```

Example XAMPP location:

```text
C:/xampp/htdocs/WEB_Project
```

### 2. Start services

Start Apache and MySQL.

### 3. Import the database

Open phpMyAdmin and import:

```text
shopping_db_schema.sql
```

### 4. Check database configuration

Verify the credentials in:

```text
db.php
```

### 5. Run the project

Open:

```text
http://localhost/WEB_Project/
```

The exact URL may vary depending on the folder name used in your local server.

---

## Testing the AJAX Cart Feature

After logging in:

1. Add a product to the cart.
2. Open the cart.
3. Change the quantity.
4. Click the update button.
5. Confirm the page does not reload.
6. Confirm the row subtotal changes.
7. Confirm the grand total changes.
8. Refresh the page and verify the quantity persisted in MySQL.

Useful edge cases:

- quantity `1`;
- increasing quantity;
- submitting the same quantity;
- invalid quantity such as `0`;
- malformed request;
- unauthenticated request;
- attempting to update another user's cart item.

---

## Academic Scope

This project demonstrates practical work with:

```text
PHP
MySQL
HTML
CSS
JavaScript
AJAX
JSON
CRUD
Authentication
Sessions
Prepared SQL
Relational Database Design
Administrative Dashboards
E-Commerce Workflows
```

It was built as a semester project and is maintained as portfolio evidence of full-stack PHP web-development experience.

---

## Author

**Pakeeza Khalid**

GitHub: https://github.com/Pakeeza1508

---

## Note

The project is intended for academic and portfolio use. Any development seed credentials, migration utilities, or temporary setup scripts should not be used as production authentication mechanisms.
