<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Dokan Test App

A simple Laravel REST API for managing posts, categories, comments, and users. This project demonstrates a typical blog-like backend with authentication, policies, resourceful endpoints, and interactive API documentation via Swagger.

## Features
- User registration and authentication
- CRUD for Posts, Categories, and Comments
- Policy-based authorization
- JSON API responses
- Interactive API documentation (Swagger/OpenAPI)
- Comprehensive test suite

## Getting Started

### Prerequisites
- PHP >= 8.1
- Composer
- SQLite (default for testing)

### Installation
```bash
# Clone the repository
git clone <repo-url>
cd dokan-test-app

# Install dependencies
composer install

# Copy and edit environment variables
cp .env.example .env
php artisan key:generate

# Run migrations and seeders
php artisan migrate --seed

# (Optional) Run the development server
php artisan serve
```

## API Documentation (Swagger)

This project uses [L5 Swagger](https://github.com/DarkaOnLine/L5-Swagger) for OpenAPI documentation.

- **Interactive API docs:** Visit [`/api/documentation`](http://localhost:8000/api/documentation) after running the server.
- You can view all endpoints, schemas, and try requests directly from the browser.
- The OpenAPI spec is generated from PHP annotations in the codebase (see `app/Swagger/Schemas.php` and controller classes).

## API Endpoints

### Public Endpoints
- `GET    /api/posts`                 — List all posts
- `GET    /api/posts/{id}`            — Show a post with comments
- `GET    /api/categories/{id}/posts` — List posts by category
- `GET    /api/comments`              — List all comments
- `GET    /api/comments/{id}`         — Show a single comment

### Protected Endpoints (require auth)
- `POST   /api/posts`                 — Create a new post
- `POST   /api/posts/{id}/comments`   — Add a comment to a post
- `PUT    /api/posts/{id}`            — Update a post (owner only)
- `DELETE /api/posts/{id}`            — Delete a post (owner only)
- `PUT    /api/comments/{id}`         — Update a comment (owner only)
- `DELETE /api/comments/{id}`         — Delete a comment (owner only)

## Example Requests

### List Posts
```bash
curl -X GET http://localhost:8000/api/posts
```

### Create Post
```bash
curl -X POST http://localhost:8000/api/posts \
     -H "Authorization: Bearer <token>" \
     -H "Content-Type: application/json" \
     -d '{"title": "My Post", "content": "Hello World", "category_id": 1}'
```

### Add Comment
```bash
curl -X POST http://localhost:8000/api/posts/1/comments \
     -H "Authorization: Bearer <token>" \
     -H "Content-Type: application/json" \
     -d '{"content": "Nice post!"}'
```

## Running Tests

```bash
php artisan test
```

Tests use an in-memory SQLite database and cover all major API endpoints and policies.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
