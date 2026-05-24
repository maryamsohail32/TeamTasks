# TeamTasks API

A RESTful task management API built with Laravel 11, demonstrating production-ready patterns including authentication, authorization, queued notifications, and comprehensive testing.

## Tech Stack

- **Laravel 11** — PHP 8.3
- **MySQL** — primary database
- **Redis** — queue driver & caching
- **Laravel Sanctum** — API token authentication
- **Laravel Horizon** — queue monitoring
- **PHPUnit / Pest** — feature & unit testing

## Features

- **Multi-workspace** — users can create and belong to multiple workspaces
- **Task management** — full CRUD with priority levels and due dates
- **Team collaboration** — assign tasks to workspace members
- **Email notifications** — queued email when a task is assigned to you
- **Policy-based authorization** — users can only access their own data
- **Versioned API** — all routes under `/api/v1`
- **API Resources** — consistent, clean JSON responses
- **Form Request validation** — dedicated validation classes per endpoint

## Architecture

```
HTTP Request
    │
    ▼
Router (/api/v1/...)
    │
    ▼
Middleware (auth:sanctum, throttle)
    │
    ▼
Form Request (validation)
    │
    ▼
Controller (thin — delegates to Service)
    │
    ▼
Service Layer (business logic)
    │
    ▼
Eloquent Model → MySQL
    │
    ▼
API Resource (JSON transformation)
```

## Project Structure

```
app/
├── Http/
│   ├── Controllers/Api/V1/
│   │   ├── AuthController.php
│   │   ├── WorkspaceController.php
│   │   └── TaskController.php
│   ├── Requests/
│   │   ├── Auth/          (LoginRequest, RegisterRequest)
│   │   ├── Workspace/     (StoreWorkspaceRequest, UpdateWorkspaceRequest)
│   │   └── Task/          (StoreTaskRequest, UpdateTaskRequest)
│   └── Resources/
│       ├── UserResource.php
│       ├── Workspace/     (WorkspaceResource, WorkspaceCollection)
│       └── Task/          (TaskResource, TaskCollection)
├── Models/
│   ├── User.php
│   ├── Workspace.php
│   └── Task.php
├── Policies/
│   ├── WorkspacePolicy.php
│   └── TaskPolicy.php
├── Services/
│   ├── AuthService.php
│   ├── WorkspaceService.php
│   └── TaskService.php
├── Jobs/
│   └── SendTaskAssignedNotification.php
└── Notifications/
    └── TaskAssignedNotification.php
```

## API Endpoints

### Auth
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/v1/auth/register` | Register a new user |
| POST | `/api/v1/auth/login` | Login & get token |
| POST | `/api/v1/auth/logout` | Revoke current token |
| GET | `/api/v1/auth/me` | Get authenticated user |

### Workspaces
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/v1/workspaces` | List user's workspaces |
| POST | `/api/v1/workspaces` | Create a workspace |
| GET | `/api/v1/workspaces/{id}` | Get a workspace |
| PUT | `/api/v1/workspaces/{id}` | Update a workspace |
| DELETE | `/api/v1/workspaces/{id}` | Delete a workspace |
| POST | `/api/v1/workspaces/{id}/invite` | Invite a member |

### Tasks
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/v1/workspaces/{id}/tasks` | List workspace tasks |
| POST | `/api/v1/workspaces/{id}/tasks` | Create a task |
| GET | `/api/v1/workspaces/{id}/tasks/{taskId}` | Get a task |
| PUT | `/api/v1/workspaces/{id}/tasks/{taskId}` | Update a task |
| DELETE | `/api/v1/workspaces/{id}/tasks/{taskId}` | Delete a task |
| PATCH | `/api/v1/workspaces/{id}/tasks/{taskId}/assign` | Assign task to member |

## Setup

### Requirements
- PHP 8.3+
- MySQL 8+
- Redis
- Composer

### Installation

```bash
# Clone the repository
git clone https://github.com/yourusername/teamtasks.git
cd teamtasks

# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure your .env (DB credentials, Redis, Mail)

# Run migrations and seed demo data
php artisan migrate --seed

# Start the queue worker
php artisan horizon

# Serve the application
php artisan serve
```

### Running Tests

```bash
# Run all tests
php artisan test

# Run with coverage
php artisan test --coverage
```

## Demo Credentials (after seeding)

| Email | Password | Role |
|-------|----------|------|
| admin@teamtasks.test | password | Owner of "Acme Corp" workspace |
| john@teamtasks.test | password | Member of "Acme Corp" workspace |
| sara@teamtasks.test | password | Member of "Acme Corp" workspace |

## Example Request & Response

**POST** `/api/v1/auth/login`

```json
// Request
{
  "email": "admin@teamtasks.test",
  "password": "password"
}

// Response 200
{
  "data": {
    "user": {
      "id": 1,
      "name": "Admin User",
      "email": "admin@teamtasks.test"
    },
    "token": "1|abc123...",
    "token_type": "Bearer"
  }
}
```

**POST** `/api/v1/workspaces/1/tasks`

```json
// Request (Authorization: Bearer {token})
{
  "title": "Design the login page",
  "description": "Create Figma mockups for the auth flow",
  "priority": "high",
  "due_date": "2025-02-01",
  "assigned_to": 2
}

// Response 201
{
  "data": {
    "id": 1,
    "title": "Design the login page",
    "description": "Create Figma mockups for the auth flow",
    "status": "pending",
    "priority": "high",
    "due_date": "2025-02-01",
    "assigned_to": {
      "id": 2,
      "name": "John Doe"
    },
    "created_by": {
      "id": 1,
      "name": "Admin User"
    },
    "created_at": "2025-01-15T10:30:00Z"
  }
}
```

## Key Design Decisions

- **Thin controllers** — controllers only handle HTTP concerns. Business logic lives in Service classes.
- **Form Requests** — validation is never done in controllers, keeping them clean.
- **API Resources** — all responses go through Resource transformers; no raw model output.
- **Policies** — every destructive/sensitive action checks a Policy, not inline `if` statements.
- **Queued notifications** — email dispatch is always async to keep API responses fast.
- **Service Layer** — easy to unit test business logic without HTTP overhead.
