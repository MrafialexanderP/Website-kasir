# CI4 Stubs for Website-kasir

This folder contains minimal CodeIgniter 4 stub files to get a simple cashier (kasir) backend working: migration, models and controllers.

Files added:
- Database/Migrations/20251111_CreateProductsTransactions.php — creates products, transactions, transaction_items
- Models/ProductModel.php
- Models/TransactionModel.php
- Controllers/ProductController.php
- Controllers/TransactionController.php
- Config/Routes.php — sample routes to copy into your `app/Config/Routes.php`

How to use (quick):

1. Copy the migration into `app/Database/Migrations/`.
2. Copy the Models into `app/Models/`.
3. Copy the Controllers into `app/Controllers/`.
4. Merge the routes lines into `app/Config/Routes.php`.
5. Configure your database in the project `.env` file.
6. Run migrations from project root (PowerShell):

```powershell
php spark migrate
```

7. Start dev server:

```powershell
php spark serve --host=0.0.0.0 --port=8080
```

Notes:
- These stubs are minimal and meant to be a starting point. Add validation, authentication, CSRF protection, and views.
- The transaction logic decreases stock atomically using SQL `stock = stock - ?` with `WHERE stock >= ?` and runs inside a DB transaction.
