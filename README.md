
# project-management
=======
# 🚀 Project Management API (Laravel 12 + Sanctum)

A RESTful API built with **Laravel 12**, **Sanctum authentication**, and **role-based access**.  
Implements **Projects**, **Tasks**, and **Comments** with notifications, queues, caching, and tests.

---

## ⚡ Features
- **Authentication (Sanctum)**
  - Register / Login / Logout
  - `me` endpoint for current user
- **Role-based Access**
  - `admin`, `manager`, `user`
- **Projects**
  - CRUD APIs (admin only for create/update/delete)
- **Tasks**
  - CRUD APIs (manager + assigned user restrictions)
- **Comments**
  - Add & view comments on tasks
- **Notifications**
  - Task assignment triggers email & DB notification
- **Queues**
  - Notifications processed via queue worker
- **Caching**
  - Project listing cached for performance
- **Testing**
  - Feature tests: Auth, Projects, Tasks, Comments
  - Unit test: TaskAssignmentService
  - ✅ Coverage target: 85%+ (requires Xdebug/PCOV)

---

## 🛠️ Tech Stack
- **Backend**: Laravel 12, PHP 8.2
- **Auth**: Laravel Sanctum
- **Database**: MySQL (InnoDB)
- **Queue**: Database driver
- **Cache**: File / Database
- **Testing**: PHPUnit / Pest

---

## 🔧 Setup Instructions

# Project Management (Laravel 12)

A basic Laravel 12 project setup with Sanctum API authentication.

---

## 🚀 Requirements
- PHP >= 8.2
- Composer
- MySQL (or any database supported by Laravel)
- Node.js & NPM (for frontend / Vite, if used)
- Git

---

## 🔧 Installation

### 1. Clone the repository
```bash
git clone https://github.com/rashminkachhadiya/project-management.git
cd project-management

### 2. Install PHP dependencies
```bash
composer install

### 3. Create environment file
```bash
cp .env.example .env

### 4. Generate application key
```bash
php artisan key:generate

### 5. Configure database
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

### 6. Run migrations
```bash
php artisan migrate 

### 7. Run Seeder
```bash
php artisan db:seed

### Run the Application
```bash
php artisan serve

This will create:
3 Admins
3 Managers
5 Users
Sample Projects, Tasks, and Comments


🔑 API Endpoints
## Auth

POST /api/register – Register user
POST /api/login – Login, returns token
POST /api/logout – Logout (auth required)
GET /api/me – Current user

## Projects

GET /api/projects – List (with filters, cached)
GET /api/projects/{id} – Show
POST /api/projects – Create (admin only)
PUT /api/projects/{id} – Update (admin only)
DELETE /api/projects/{id} – Delete (admin only)

## Tasks

GET /api/projects/{project_id}/tasks – List tasks by project
GET /api/tasks/{id} – Show task
POST /api/projects/{project_id}/tasks – Create (manager only)
PUT /api/tasks/{id} – Update (manager or assigned user)
DELETE /api/tasks/{id} – Delete (manager only)

## Comments

GET /api/tasks/{task_id}/comments – List comments
POST /api/tasks/{task_id}/comments – Add comment

## Postman Collections

## Create enviroment: dev

base_url :: set project url here..
authToken :: auto set when your register and login.

## Testing

php artisan test
php artisan test --coverage

## Feature tests cover:

Registration
Login
Project creation
Task update
Comments addition

