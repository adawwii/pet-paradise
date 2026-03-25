# 🐾 PetStore (Laravel E-commerce)

## 📌 Project Overview

PetStore is a Laravel-based e-commerce platform for selling pet supplies such as food, toys, and accessories.

### 👤 Customer Features

* Browse products
* View product details
* Add items to cart
* Checkout and payment
* Track orders

### 🛠️ Admin Features

* Manage products, orders, customers, and reviews
* Moderate reviews
* Export orders
* Role-based access control

---

## 🚀 Key Features

* User authentication & profile management
* Email verification
* Product catalog with category filtering
* Cart & checkout system
* Stripe payment integration
* Order status flow (processing → shipped → delivered)
* Reviews with admin moderation
* Soft delete & restore (users, products, employees)
* CSV export for orders

---

## 🗂️ Folder Structure (High Level)

```
app/
 ├── Http/Controllers   → Web controllers
 ├── Models             → Eloquent models
 ├── Services           → Business logic layer
routes/
 ├── web.php            → Application routes
database/
 ├── migrations         → Database schema
resources/
 ├── views              → Blade templates
tests/
 ├── Feature & Unit tests
```

### 🔧 Services Layer

* ProductService
* CartService
* OrderService
* CheckoutService

---

## ⚙️ Installation

```bash
composer install
npm install && npm run dev
cp .env.example .env
```

### Configure `.env`

* Database credentials
* Mail settings
* Stripe keys (optional)

```bash
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

---

## 🔐 Authentication & Authorization

### Routes

* **Guest only:** register, login
* **Authenticated users:** profile, cart, checkout, orders
* **Admin (employee | Super Admin):** dashboard access
* **Super Admin only:** employee management

### Role Management

* Implemented using `spatie/laravel-permission`

### Policies

* ProductPolicy
* OrderPolicy
* ReviewPolicy
* CartPolicy

---

## 📦 Main Dependencies

* Laravel Framework
* spatie/laravel-permission (RBAC)
* stripe/stripe-php (payments)
* laravel/sanctum (API auth, optional)
* facade/ignition (debugging)
* PHPUnit (testing)

---

## 🔄 MVC + Service Flow Example

1. User visits `/shop`
2. `ProductController` handles request
3. `ProductService` retrieves products
4. View renders products

### Cart Flow

* `/add/cart/{product}`
  → `CartController` → `CartService`

### Checkout Flow

* `/checkout`
  → `CheckoutController` → `CheckoutService`

### Orders

* Orders stored in `orders` table
* Items stored in `order_items`

---

## 🧑‍💼 Admin Endpoints

```
/admin/orders
/admin/products
/admin/customers
/admin/reviews
```

### Actions

```
PATCH /admin/orders/{order}/status
PATCH /admin/products/{product}/toggle
GET   /admin/orders/export
```

### Super Admin Only

* Employee CRUD
* Product restore

---

## 📊 Architecture Notes

* MVC pattern with Service Layer separation
* Clean separation of concerns
* Database transactions for critical operations (orders, payments)
* Queue-ready (Stripe webhook + jobs supported)

---

## 🧪 Testing

```bash
php artisan test
```

Includes:

* Feature tests
* Unit tests

---

## 💡 Future Improvements

* Admin dashboard analytics (charts)
* Real-time order updates
* API for mobile apps
* Caching & performance optimization
* Inventory management system

---

## 👨‍💻 Author

Developed as a full-stack Laravel e-commerce system with scalable architecture and modern best practices.
