# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Framework Overview

This project uses **Falt Leap Framework**, a lightweight custom PHP MVC framework with zero core dependencies. The framework is PostgreSQL-specific and follows Active Record and convention-over-configuration patterns. The core framework is distributed via Composer for easy installation and updates.

## Architecture

### Core Components

The framework consists of these core classes in the `FlatLeap` namespace (located in `vendor/faltleap/core/src/` or `/lib/` for development):

- **LeapEngine**: Application bootstrapper and request handler. Entry point is `start($routes)` which processes HTTP requests and dispatches to controllers.
- **LeapRouter**: URL routing with support for static and dynamic parameters using regex pattern matching.
- **LeapController**: Base controller with automatic dependency injection (db, request, session, view) via constructor.
- **LeapModel**: Active Record ORM providing `Query()`, `Where()`, `WhereOne()`, `save()`, `delete()` methods with automatic table/column introspection.
- **LeapQueryBuilder**: Fluent query builder with join support, aggregates, grouping, and auto-hydration.
- **LeapView**: Template rendering system with layout inheritance. Uses `.leap.php` extension and placeholder replacement.
- **LeapDB**: PostgreSQL PDO wrapper with schema support via `SET search_path`.
- **LeapRequest**: HTTP request abstraction with `only()`, `isGet()`, `isPost()` methods.
- **LeapSession**: Session management wrapper around `$_SESSION`.
- **LeapWebSocketServer**: Built-in WebSocket server for real-time features (CLI mode only).
- **LeapAutoloader**: PSR-4 compliant autoloader for namespaced classes.
- **LeapMiddleware**: Abstract base class for middleware with dependency injection.
- **LeapMiddlewareStack**: Middleware pipeline executor.
- **LeapEnv**: Zero-dependency .env file parser.

### Request Lifecycle

1. Request hits `/public/index.php` (web root)
2. Bootstrap (`bootstrap.php`) loads Composer autoloader
3. Loads environment variables from `.env` file via `LeapEnv`
4. Includes route configuration from `/conf/router.config.php`
5. `LeapEngine->start($routes)` processes the request
6. `LeapRouter->getRoute($url, $routes)` matches URL to `ControllerName@methodName`
7. Middleware pipeline executes (if defined for route)
8. Controller instantiated with injected dependencies (db, request, session, view)
9. Controller method executes and returns view or redirect
10. View renders template with optional layout wrapping

### Routing

Routes defined in `/conf/router.config.php` as simple array:

```php
$routes = [
    "/" => "HomeController@index",
    "/users/edit/{id}" => "UsersController@edit",
    "/login" => "AuthController@login"
];
```

Dynamic parameters like `{id}` are extracted and passed to controller methods as arguments.

### Database & Models

- **PostgreSQL-only** with schema support
- Database config in `.env` file contains: `DB_HOST`, `DB_USERNAME`, `DB_PASSWORD`, `DB_DATABASE`, `DB_SCHEMA`
- Models auto-generated from database schema using `vendor/bin/gen` (or `php gen.php` in development)
- Models extend `LeapModel` and use Active Record pattern
- Column metadata stored as protected static arrays (`$columns`, `$defaults`, `$nullables`, `$primaries`)
- Primary key auto-detected from `information_schema`
- Supports query builder pattern via `Model::Query()` for complex queries with joins, aggregates, and grouping

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

#### For New Apps (using Composer)

```bash
# Create a new app
composer create-project faltleap/app myapp
cd myapp

# Configure database
cp .env.example .env
# Edit .env with your PostgreSQL credentials

# Generate models
php vendor/bin/gen all public
```

#### For Development (framework itself)

1. Clone the repository
2. Create `.env` file with database config
3. Generate models: `php gen.php all [schema]`

### Model Generation

```bash
# In production apps (installed via Composer)
php vendor/bin/gen all [schema_name]
php vendor/bin/gen tablename [schema_name]

# In development (working on framework)
php gen.php all [schema_name]
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

### For Apps (installed via Composer)

- `/app/` - Controllers (suffix: `Controller.php`)
- `/conf/` - Configuration files (router.config.php)
- `/models/` - Auto-generated model classes (suffix: `.model.php`)
- `/middleware/` - Custom middleware classes
- `/public/` - Web root with `index.php` entry point
- `/storage/` - Session file storage
- `/views/` - View templates (suffix: `.leap.php`)
- `/vendor/` - Composer dependencies (includes faltleap/core)
- `.env` - Environment configuration
- `bootstrap.php` - Application bootstrap
- `composer.json` - Project dependencies

### For Framework Development

- `/lib/` - Framework core classes (LeapEngine, LeapController, LeapModel, etc.)
- `/src/` - Namespaced source (will be created during migration to composer structure)
- `/stubs/` - Template files for new apps
- `/container/` - Docker configuration (nginx, Dockerfile, entrypoint)
- `gen.php` - Model generator script
- `composer.json` - Core package definition

## Important Conventions

1. **Controllers** must extend `LeapController` and use suffix `Controller`
2. **Models** must extend `LeapModel` and use suffix `.model.php`
3. **Views** use `.leap.php` extension and are referenced without extension in controller
4. **Model generation** is mandatory - models are not hand-written but generated from schema
5. **Schema-first approach** - modify database schema then regenerate models
6. **Zero core dependencies** - framework core is entirely self-contained, no external packages
7. **PostgreSQL-specific** - uses PostgreSQL features, not compatible with MySQL/SQLite
8. **Bootstrap 5.3** - All views must use Bootstrap 5.3 for styling and components
9. **Vanilla JavaScript only** - Use vanilla JavaScript or Web Components, no frontend frameworks (React, Vue, Angular, etc.)
10. **Library-agnostic** - framework is not tied to any specific library. Do not modify core classes. You can ask to modify them, but never do it yourself.
11. Always make sure you don't use code from similar projects like Laravel, etc.
12. To set data in the view use `$this->view->data`
13. To render a view use `$this->view->render('viewname');`
14. **STRICT TYPES IS enabled** - always use `declare(strict_types=1);` at the top of every PHP file
15. Never execute SQL yourself. Always write the SQL files to the `sql/` folder and ask me to execute it
16. Use the `app/UsersController.php` as example implementation for controllers
17. When creating a new controller, always consider adding a widget to the dashboard in the HomeController
18. **Composer for distribution** - The framework is distributed via Composer but maintains zero core dependencies

## Key Patterns

- **Active Record**: Models represent table rows with save/delete methods
- **Dependency Injection**: Controllers receive dependencies via constructor automatically
- **Template Inheritance**: Views can render standalone or wrapped in master layout
- **Convention over Configuration**: Routes map directly to Controller@method patterns
- **Schema Introspection**: Models auto-discover columns, types, defaults from database
