# 🚀 Approval Workflow API

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-13-red?style=for-the-badge&logo=laravel">
  <img src="https://img.shields.io/badge/PHP-8.3-blue?style=for-the-badge&logo=php">
  <img src="https://img.shields.io/badge/Laravel-Sanctum-green?style=for-the-badge">
  <img src="https://img.shields.io/badge/MySQL-Database-orange?style=for-the-badge&logo=mysql">
  <img src="https://img.shields.io/badge/REST-API-success?style=for-the-badge">
</p>

---

# 📖 About Project

Approval Workflow API adalah RESTful API yang dibangun menggunakan **Laravel 13**, **Laravel Sanctum**, dan **MySQL** untuk mengelola proses pengajuan (Request) dan persetujuan (Approval Workflow).

Project ini menerapkan **Role-Based Access Control (RBAC)** dengan tiga role utama:

- 👨‍🔧 Employee
- 👨‍💼 Manager
- 👨‍💻 Admin

Workflow yang digunakan:

```
Draft
   │
   ▼
Submitted
   │
 ┌─┴────────┐
 ▼          ▼
Approved  Rejected
```

Setiap perubahan status akan dicatat ke dalam **Approval History**, sehingga seluruh proses approval dapat ditelusuri kembali.

---

# ✨ Features

## 🔐 Authentication

- Register
- Login
- Logout
- Get Profile
- Update Profile
- Change Password

---

## 📝 Approval Request

Employee dapat:

- Create Request
- Get All Request
- Get Detail Request
- Update Request
- Delete Request

Status Request

- Draft
- Submitted
- Approved
- Rejected

---

## ✅ Approval Workflow

Employee

- Submit Request

Manager

- Approve Request
- Reject Request
- Memberikan Comment / Notes pada proses approval

Workflow

```
Draft
   │
Submit
   │
Submitted
   │
 ┌──────────────┐
 │              │
Approve      Reject
 │              │
 ▼              ▼
Approved    Rejected
```

---

## 📜 Approval History

Menampilkan riwayat perubahan status setiap request.

Informasi yang disimpan:

- Previous Status
- Current Status
- Approval Notes
- Approved / Rejected By
- Timestamp

---

## 📊 Dashboard

Dashboard menyediakan ringkasan data approval workflow.

Menampilkan:

- Total Requests
- Draft Requests
- Submitted Requests
- Approved Requests
- Rejected Requests
- Requests Today
- Approved Today
- Rejected Today
- Approval Rate
- Total Users
- Total Managers
- Total Employees
- Total Admins

---

## 👥 User (Admin Only)

Admin memiliki hak untuk mengelola data pengguna.

Fitur:

- Get All Users
- Get User Detail
- Create User
- Update User
- Delete User

---

## 🔎 Search

Cari request berdasarkan judul.

Example

```
GET /api/approval-requests?search=laptop
```

---

## 🔍 Filter

Filter berdasarkan status request.

Example

```
GET /api/approval-requests?status=submitted
```

---

## 📑 Sorting

Newest

```
GET /api/approval-requests
```

Oldest

```
GET /api/approval-requests?sort=oldest
```

---

## 📄 Pagination

```
GET /api/approval-requests?page=2
```

atau

```
GET /api/approval-requests?per_page=5
```

---

# 📂 API Collections

Project ini terdiri dari beberapa kelompok endpoint:

```
📂 Approval Workflow API
│
├── 🔐 Authentication
│
├── 📝 Approval Request
│
├── ✅ Approval Workflow
│
├── 📊 Dashboard
│
├── 📜 Approval History
│
└── 👥 User (Admin Only)
```

---

# 🛠 Tech Stack

- Laravel 13
- PHP 8.3
- Laravel Sanctum
- MySQL
- RESTful API
- Postman

---

# ⚙️ Installation

Clone repository

```bash
git clone https://github.com/USERNAME/approval-workflow-api.git
```

Masuk ke project

```bash
cd approval-workflow-api
```

Install dependency

```bash
composer install
```

Copy file environment

```bash
cp .env.example .env
```

Generate application key

```bash
php artisan key:generate
```

Migrasi database

```bash
php artisan migrate
```

Jalankan server

```bash
php artisan serve
```

---

# 🔑 Authentication

Semua endpoint yang bersifat private menggunakan **Bearer Token**.

Header Authorization

```
Authorization: Bearer YOUR_TOKEN
```

---

# 📌 Main Endpoints

| Method | Endpoint |
|---------|----------|
| POST | /api/register |
| POST | /api/login |
| POST | /api/logout |
| GET | /api/profile |
| PUT | /api/profile |
| PUT | /api/change-password |
| GET | /api/dashboard |
| GET | /api/users |
| GET | /api/users/{id} |
| POST | /api/users |
| PUT | /api/users/{id} |
| DELETE | /api/users/{id} |
| GET | /api/approval-requests |
| POST | /api/approval-requests |
| GET | /api/approval-requests/{id} |
| PUT | /api/approval-requests/{id} |
| DELETE | /api/approval-requests/{id} |
| POST | /api/approval-requests/{id}/submit |
| POST | /api/approval-requests/{id}/approve |
| POST | /api/approval-requests/{id}/reject |
| GET | /api/approval-requests/{id}/history |

---

# 📁 Project Structure

```
app
├── Http
│   ├── Controllers
│   ├── Middleware
│   └── Requests
│
├── Models
│
├── Traits
│
routes
└── api.php

database
├── migrations
└── seeders
```

---

# 🧪 API Testing

Seluruh endpoint telah diuji menggunakan **Postman**.

Collection terdiri dari:

- Authentication
- Approval Request
- Approval Workflow
- Dashboard
- Approval History
- User (Admin Only)

---

# 👨‍💻 Author

**Albar**

Internship Project – PT Dahana

GitHub:

https://github.com/USERNAME

---

⭐ Jika repository ini bermanfaat, jangan lupa berikan **Star**.
