
# 🚀 TeamTasks

[![Laravel](https://img.shields.io/badge/Laravel-11-red?logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3-blue?logo=php)](https://www.php.net/)
[![Node](https://img.shields.io/badge/Node.js-18-green?logo=node.js)](https://nodejs.org/)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)
[![Build Status](https://img.shields.io/github/actions/workflow/status/your-username/teamtasks/ci.yml?branch=main)](https://github.com/your-username/teamtasks/actions)

A collaborative task management web application built with **Laravel 11**.  
Teams can be created, members invited, and tasks tracked across a **Kanban-style board** with role-based access and a modern UI.

---

## ✨ Features

- 🔐 User authentication (register, login, logout)
- 👥 Create and manage teams
- 📧 Invite team members by email
- ✅ Create tasks with title, description, priority, status, assignee, and due date
- 📊 Kanban board with To Do / In Progress / Done columns
- 🔑 Role-based access (owner vs member)
- ⏰ Overdue task highlighting
- 🌙 Modern UI with dark sidebar navigation

---

## 🛠 Tech Stack

- **Backend:** PHP 8.3, Laravel 11  
- **Frontend:** Blade templates, Tailwind CSS, Vite  
- **Database:** MySQL / MariaDB  
- **Auth:** Laravel Breeze  

---

## 📋 Requirements

- PHP >= 8.2  
- Composer  
- Node.js >= 18 and npm  
- MySQL or MariaDB  
- XAMPP (or any local server stack)  

---

## ⚡ Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/your-username/teamtasks.git
   cd teamtasks
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node dependencies**
   ```bash
   npm install
   ```

4. **Set up environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure your database in `.env`**
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=teamtasks
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. **Create the database**
   ```sql
   CREATE DATABASE teamtasks;
   ```

7. **Run migrations**
   ```bash
   php artisan migrate
   ```

8. **Build frontend assets**
   ```bash
   npm run build
   ```

9. **Start the development server**
   ```bash
   php artisan serve
   ```

Visit 👉 `http://127.0.0.1:8000`

---

## 📚 Database Structure

| Table       | Description                          |
|-------------|--------------------------------------|
| `users`     | Registered users                     |
| `teams`     | Teams created by users               |
| `team_user` | Pivot table linking users to teams   |
| `tasks`     | Tasks belonging to teams             |
| `workspaces`| (Legacy) Workspace groupings         |
| `cache`     | Laravel cache table                  |

---

## 📂 Project Structure

```
teamtasks/
├── app/
│   ├── Http/Controllers/
│   │   ├── TeamController.php      # Teams CRUD + invite
│   │   └── TaskController.php      # Tasks CRUD
│   ├── Models/
│   │   ├── Team.php
│   │   ├── Task.php
│   │   └── User.php
│   ├── Policies/
│   │   └── TeamPolicy.php          # Authorization rules
│   └── Providers/
│       └── AppServiceProvider.php  # Policy registration
├── database/
│   ├── migrations/                 # All database migrations
│   └── seeders/
│       └── DatabaseSeeder.php
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php       # Main layout with sidebar
│       ├── teams/
│       │   ├── index.blade.php     # Teams dashboard
│       │   ├── show.blade.php      # Kanban board
│       │   └── create.blade.php    # Create team form
│       └── tasks/
│           ├── create.blade.php    # Create task form
│           └── edit.blade.php      # Edit task form
└── routes/
    ├── web.php                     # App routes
    └── auth.php                    # Auth routes
```

---

## 🎯 Usage

- **Creating a team** → Log in → Teams Dashboard → New Team  
- **Inviting members** → Open team → Team Members → Invite by email  
- **Managing tasks** → Add Task → Fill details → Appears in Kanban column  

---

## 👤 Roles

| Role   | Permissions                                   |
|--------|-----------------------------------------------|
| Owner  | Manage team, tasks, invite members            |
| Member | View team and manage tasks                    |

---

## 📝 Notes

- `tasks` table originally had `workspace_id` (legacy).  
- `team_user` pivot table extended with `role`, timestamps.  
- Laravel Tinker not installed.  

---

## 📌 Roadmap

- Task comments & file attachments  
- Email & Slack notifications  
- Activity logs & audit trails  

---

## 📜 License

MIT License — free to use and modify.

---

## 👩‍💻 Author

**Maryam Sohail Ahmed**  
Built with Laravel · May 2026


