# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Framework Overview

This project uses **Falt Leap Framework**, a lightweight custom PHP MVC framework with zero external dependencies. The framework is PostgreSQL-specific and follows Active Record and convention-over-configuration patterns.

## Architecture

### Core Components

The framework consists of these core classes in `/lib/`:

- **LeapEngine**: Application bootstrapper and request handler. Entry point is `start($routes)` which processes HTTP requests and dispatches to controllers.
- **LeapRouter**: URL routing with support for static and dynamic parameters using regex pattern matching.
- **LeapController**: Base controller with automatic dependency injection (db, request, session, view) via constructor.
- **LeapModel**: Active Record ORM providing `Where()`, `WhereOne()`, `save()`, `delete()` methods with automatic table/column introspection.
- **LeapView**: Template rendering system with layout inheritance. Uses `.leap.php` extension and placeholder replacement.
- **LeapDB**: PostgreSQL PDO wrapper with schema support via `SET search_path`.
- **LeapRequest**: HTTP request abstraction with `only()`, `isGet()`, `isPost()` methods.
- **LeapSession**: Session management wrapper around `$_SESSION`.
- **LeapWebSocketServer**: Built-in WebSocket server for real-time features (CLI mode only).

### Request Lifecycle

1. Request hits `/public/index.php` (web root)
2. Bootstrap checks for `/conf/db.config.php` - redirects to installer if missing
3. Loads all framework classes from `/lib/` and configuration from `/conf/`
4. `LeapEngine->start($routes)` processes the request
5. `RouterClass->getRoute($url, $routes)` matches URL to `ControllerName@methodName`
6. Controller instantiated with injected dependencies (db, request, session, view)
7. Controller method executes and returns view or redirect
8. View renders template with optional layout wrapping

### Routing

Routes defined in `/conf/router.config.php` as simple array:

```php
$routes = [
    // Simple string — matches ALL HTTP methods
    "/" => "HomeController@index",

    // Array with middleware — matches ALL HTTP methods
    "/users" => ["UsersController@index", "auth"],

    // HTTP method-nested — different controller@method per verb
    "/login" => [
        "GET"  => "AuthController@showLogin",
        "POST" => "AuthController@login",
    ],

    // Methods can use middleware too
    "/users/edit/{id}" => [
        "GET"  => ["UsersController@edit", "auth"],
        "POST" => ["UsersController@update", "auth"],
    ],

    // Single method restriction (POST-only)
    "/users/delete/{id}" => [
        "POST" => ["UsersController@delete", "auth"],
    ],
];
```

Dynamic parameters like `{id}` are extracted and passed to controller methods as arguments.

**HTTP method routing**: A route definition is "method-nested" if its value is an array whose keys include any of `GET`, `POST`, `PUT`, `PATCH`, `DELETE`, `OPTIONS`, `HEAD`. When a URL matches but the HTTP method doesn't match any nested key, the router returns **405 Method Not Allowed** with an `Allow` header listing the valid methods. Non-nested routes (string or `[action, ...middleware]`) continue to match all HTTP methods.

### Database & Models

- **PostgreSQL-only** with schema support (configured via `dbschema` in db.config.php)
- Database config in `/conf/db.config.php` contains: `dbhost`, `dbusername`, `dbpassword`, `dbdatabase`, `dbschema`
- Models auto-generated from database schema using `gen.php`
- Models extend `LeapModel` and use Active Record pattern
- Column metadata stored as protected arrays (`$columns`, `$defaults`, `$nullables`)
- Primary key auto-detected from `information_schema`

Example model usage:

```php
$user = Users::WhereOne("username = :username", [":username" => $username]);
$user->name = "New Name";
$user->save();
```

### View System

- Templates in `/views/` with `.leap.php` extension
- Master layout: `/views/index.leap.php` with `{{content}}` placeholder
- Controllers use `$this->view->render("viewname", $data)` for layout wrapping
- Or `$this->view->single("viewname")` for standalone views
- Flash messages via `$this->view->flash("message")` rendered with `{{flash}}` placeholder
- Data passed to views as object properties: `$data->title` accessible as `$title` in template

### Frontend/UI Requirements

- **Bootstrap 5.3**: All views must use Bootstrap 5.3.8+ for styling and UI components. Include from CDN:

  ```html
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
  ```

- **Vanilla JavaScript Only**: No frontend frameworks (React, Vue, Angular). Use vanilla JavaScript or Web Components for interactivity.
- **No build tools required**: All JavaScript should be browser-ready without transpilation or bundling.

### Authentication

Session-based authentication in `AuthController`:

- Auth data stored in `$_SESSION['auth']` with `['idusers' => ..., 'username' => ...]`
- Check authentication: `$this->session->get('auth')`
- Get user ID: `$this->session->getUserId()`
- Logout clears session and redirects to `/login`

Note: Currently uses SHA1 for password hashing - consider migrating to `password_hash()` for new code.

## Development Commands

### Initial Setup

1. Configure database in `/conf/db.config.php` or run web installer at `/public/index.php`
2. Generate models: `php gen.php all [schema]`

### Model Generation

```bash
# Generate all models from schema
php gen.php all [schema_name]

# Generate single model
php gen.php tablename [schema_name]
```

Models are created in `/models/` as `TableName.model.php`

### Docker Development

```bash
# Start development server on http://localhost:8090
./run.sh
```

Uses Alpine Linux, nginx, PHP-FPM stack defined in `/container/`
The root dir inside the container is `/var/www/localhost/htdocs/`

### WebSocket Server (Optional)

```bash
# Start WebSocket server on port 8080
php index.php
```

Runs in CLI mode using LeapWebSocketServer for real-time features.

## File Organization

- `/app/` - Controllers (suffix: `Controller.php`)
- `/conf/` - Configuration files (db.config.php, router.config.php)
- `/lib/` - Framework core classes (LeapEngine, LeapController, LeapModel, etc.)
- `/models/` - Auto-generated model classes (suffix: `.model.php`)
- `/public/` - Web root with `index.php` entry point
- `/storage/` - Session file storage
- `/views/` - View templates (suffix: `.leap.php`)
- `/container/` - Docker configuration (nginx, Dockerfile, entrypoint)

## Important Conventions

1. **Controllers** must extend `LeapController` and use suffix `Controller`
2. **Models** must extend `LeapModel` and use suffix `.model.php`
3. **Views** use `.leap.php` extension and are referenced without extension in controller
4. **Model generation** is mandatory - models are not hand-written but generated from schema
5. **Schema-first approach** - modify database schema then regenerate models
6. **No composer dependencies** - framework is entirely self-contained
7. **PostgreSQL-specific** - uses PostgreSQL features, not compatible with MySQL/SQLite
8. **Bootstrap 5.3** - All views must use Bootstrap 5.3 for styling and components
9. **Vanilla JavaScript only** - Use vanilla JavaScript or Web Components, no frontend frameworks (React, Vue, Angular, etc.)
10. **Library-agnostic** - framework is not tied to any specific library. Do not modify core classes. You can ask to modify them, but never do it yourself.
11. Always make sure you don't use code from similar projects like Laravel, etc.
12. to set data in the view use $this->view->data
13. to render a view use $this->view->render('viewname');
14. STRICT TYPES IS enabled, always use declare(strict_types=1);
15. never execute sql yourself. always write the sql files to the sql/ folder and ask me to execute it
16. Use the @app/UsersController.php as example implementation for the controllers
17. When creating a new controller, always consider adding a widget to the dashbaord in the homecontroller

## Key Patterns

- **Active Record**: Models represent table rows with save/delete methods
- **Dependency Injection**: Controllers receive dependencies via constructor automatically
- **Template Inheritance**: Views can render standalone or wrapped in master layout
- **Convention over Configuration**: Routes map directly to Controller@method patterns
- **Schema Introspection**: Models auto-discover columns, types, defaults from database
