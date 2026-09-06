# GST Invoice & Inventory Management System

[![Laravel Version](https://img.shields.io/badge/Laravel-13.x-red.svg)](https://laravel.com)
[![PHP Version](https://img.shields.io/badge/PHP-8.3%2B-blue.svg)](https://php.net)
[![Livewire](https://img.shields.io/badge/Livewire-3.x-pink.svg)](https://livewire.laravel.com)
[![License: MIT](https://img.shields.io/badge/License-MIT-green.svg)](https://opensource.org/licenses/MIT)

An enterprise-grade, high-performance **GST Invoice & Inventory ERP Application** tailored for Indian Small and Medium Businesses (SMBs). Built on **Laravel 13**, **Livewire 3**, **Tailwind CSS**, and **DomPDF**, with automated intra-state (CGST + SGST) and inter-state (IGST) tax calculation, sequential invoice numbering, stock audit trails, asynchronous PDF generation, and multi-tenant isolation.

---

## 🌟 Key Features

- **⚡ Real-Time GST Engine (Livewire 3)**:
  - Automated state detection based on 15-digit GSTIN (e.g., `27` for Maharashtra, `24` for Gujarat).
  - Dynamic intra-state (`50% CGST + 50% SGST`) and inter-state (`100% IGST`) tax calculations without full-page reloads.
  - Indian currency words converter (Lakhs & Crores) for legal tax compliance.

- **📦 Inventory & Double-Entry Stock Ledger**:
  - Immutable audit trail in `stock_moves` table for every sale, purchase, and stock adjustment.
  - Pessimistic locking (`lockForUpdate`) to prevent negative inventory during simultaneous billings.
  - Low stock warning badges and reorder notifications.

- **📄 Official GST Tax Invoice PDF (`barryvdh/laravel-dompdf`)**:
  - Compliant GST Rule 46 printable tax invoices with HSN/SAC breakdowns, seller/buyer state codes, terms, and signature blocks.
  - Asynchronous background rendering and customer email notifications via queued jobs (`SendInvoiceEmailJob`).

- **📊 High-Performance Executive Dashboard**:
  - Multi-level caching with automatic Model Observer invalidation (`InvoiceObserver`, `StockMoveObserver`).
  - Real-time summaries of monthly turnover, total GST collected, active invoices, and low stock warnings.

- **🛡️ Enterprise Security & RBAC**:
  - Role-Based Access Control (`admin`, `sales`, `accountant`) with Laravel Policies (`InvoicePolicy`, `ProductPolicy`, `CustomerPolicy`).
  - Strict multi-tenant data boundaries preventing cross-tenant data access (IDOR protection).
  - Custom validation rules for Indian GSTIN (`GstinRule`) and HSN/SAC codes (`HsnCodeRule`).

- **🔌 REST API & Performance**:
  - Clean API Resource transformers (`ProductResource`, `InvoiceResource`) with cursor pagination and rate limiting (`throttle:60,1`).

- **🧪 100% Automated Test Coverage (Pest PHP)**:
  - Unit tests for tax algorithms and currency formatters.
  - Feature tests for invoice creation, stock deduction, and tenant isolation policies.

---

## 🏗️ Architecture & Technology Stack

| Component | Technology |
|---|---|
| **Backend Framework** | Laravel 13 (PHP 8.3+) |
| **Reactivity Layer** | Livewire 3 + Volt + Alpine.js |
| **Styling & UI** | Tailwind CSS (Emerald/Slate Theme) |
| **PDF Generation** | `barryvdh/laravel-dompdf` (v3.1+) |
| **Testing Engine** | Pest PHP (v5.x) |
| **Authentication** | Laravel Breeze (Livewire Stack) |
| **Database** | MySQL / MariaDB / SQLite with indexed composite lookups |

---

## 🚀 Local Installation & Setup

### Prerequisites
- PHP >= 8.3 with `bcmath`, `ctype`, `fileinfo`, `json`, `mbstring`, `pdo`, `tokenizer`, `xml` extensions.
- Composer 2.x
- Node.js (v18+) & NPM

### Setup Instructions
```bash
# 1. Clone the repository
git clone https://github.com/kmtech183/gst-invoice.git
cd gst-invoice

# 2. Install Composer dependencies
composer install

# 3. Setup environment configuration
cp .env.example .env
php artisan key:generate

# 4. Configure Database in .env and run migrations with seed data
php artisan migrate:fresh --seed

# 5. Install frontend packages and build assets
npm install
npm run build

# 6. Start development server
php artisan serve
```

### Default Demo Credentials (from Seeder)
| Role | Email | Password |
|---|---|---|
| **Admin** | `admin@apextech.in` | `password` |
| **Sales** | `sales@apextech.in` | `password` |
| **Accountant** | `accountant@apextech.in` | `password` |

---

## 🧪 Running Automated Tests

```bash
php artisan test
```

---

## 📄 License
This project is open-sourced software licensed under the [MIT License](LICENSE).

