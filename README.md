# Falt Leap Framework

![PHP 8+](https://img.shields.io/badge/PHP-8%2B-777BB4?style=flat-square&logo=php)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-Only-336791?style=flat-square&logo=postgresql)
![Zero Dependencies](https://img.shields.io/badge/Dependencies-0-success?style=flat-square)
![Lines of Code](https://img.shields.io/badge/Lines_of_Code-3,028-blue?style=flat-square)
![License](https://img.shields.io/badge/License-BSD-green?style=flat-square)

**A radically simple, zero-dependency PHP framework for PostgreSQL purists.**

> *Your framework has more dependencies than your app has users.*

Falt Leap is an opinionated MVC framework that throws out the complexity of modern PHP development and gets back to basics. No Composer. No bloated dependencies. No framework-imposed abstractions between you and your database. Just clean, fast PHP that embraces PostgreSQL's power.

**Version 0.3** introduces a full error handling system with a rich debug page, interactive console, and production-safe error screens — on top of v0.2's query builder, middleware, and autoloading. All in **~3,000 lines of core code**.

---

## Table of Contents

- [Quick Start](#quick-start)
- [Feature Comparison](#feature-comparison)
- [What's Different? (Show, Don't Tell)](#whats-different-show-dont-tell)
- [Built for Modern PHP, Not Around Legacy PHP](#built-for-modern-php-not-around-legacy-php)
- [Why Falt Leap?](#why-falt-leap)
- [What's New in Version 0.3?](#whats-new-in-version-03)
- [What Makes This Opinionated?](#what-makes-this-opinionated)
- [Architecture](#architecture)
- [Model Generation](#model-generation)
- [Who Is This For?](#who-is-this-for)
- [Project Structure](#project-structure)
- [Philosophy](#philosophy)
- [Documentation](#documentation)
- [Real Talk: Why Another Framework?](#real-talk-why-another-framework)
- [Performance](#performance)
- [Contributing](#contributing)
- [License](#license)

---

## Quick Start

The fastest way from zero to running app:

```bash
git clone https://github.com/GECKOSTUDIOS/faltleap myapp
cd myapp
./run.sh
# Done. Visit http://localhost:8090
```

That's it. The Docker setup handles PHP, nginx, and everything else. No `composer install`. No `npm install`. No build step.

### Manual Setup (without Docker)

If you'd rather run it on your own PHP + nginx/Apache stack:

```bash
# 1. Clone
git clone https://github.com/GECKOSTUDIOS/faltleap myapp
cd myapp

# 2. Create your .env
cat > .env <<'EOF'
DB_HOST=localhost
DB_USERNAME=postgres
DB_PASSWORD=yourpassword
DB_DATABASE=yourdb
DB_SCHEMA=public
APP_DEBUG=true
EOF

# 3. Generate models from your existing PostgreSQL schema
php gen.php all public

# 4. Point your web server's document root to /public/
#    (nginx: root /path/to/myapp/public; | Apache: DocumentRoot /path/to/myapp/public)
```

Set `APP_DEBUG=false` in production. Errors will log to `storage/logs/error.log` and users see a clean error page instead of stack traces.

---

## Feature Comparison

| Feature | Falt Leap | Laravel 11 | Symfony 7 |
|---------|-----------|------------|-----------|
| **Lines of Code** | ~3,000 | ~500,000+ | ~800,000+ |
| **Dependencies** | 0 | 30+ direct, 100+ total | 50+ direct, 200+ total |
| **Vendor Folder Size** | 0 bytes | ~274 MB | ~350 MB |
| **Fresh Install Time** | < 1 second | 2-5 minutes | 3-7 minutes |
| **Routing** | ✅ Array-based | ✅ Attribute/File-based | ✅ YAML/Annotation |
| **Query Builder** | ✅ With joins & aggregates | ✅ Full Eloquent | ✅ Doctrine |
| **Middleware** | ✅ Simple pipelines | ✅ Complex pipelines | ✅ Complex pipelines |
| **Autoloading** | ✅ PSR-4 | ✅ Composer | ✅ Composer |
| **Database Support** | PostgreSQL only | MySQL, Postgres, SQLite | Any with Doctrine |
| **ORM** | Active Record | Eloquent (Active Record) | Doctrine (Data Mapper) |
| **Template Engine** | Built-in (PHP) | Blade | Twig |
| **Auto-Escaping** | ✅ Safe by default | ✅ Blade `{{ }}` | ✅ Twig `{{ }}` |
| **Zero Config Setup** | ✅ Yes | ❌ No | ❌ No |
| **Error Handling** | ✅ Debug page + console | ✅ Ignition | ✅ Profiler |
| **Code You Can Read** | ✅ In one afternoon | ❌ Months | ❌ Never |
| **Breaking Changes** | Rare | Every major version | Every major version |
| **Learning Curve** | 1 day | 2 weeks | 1 month |

**Not shown:** The peace of mind from owning your entire stack.

---

## What's Different? (Show, Don't Tell)

### Routing: Array vs. Magic

**Laravel:**

```php
// routes/web.php
Route::get('/users/{id}', [UserController::class, 'show'])->middleware(['auth', 'verified']);
Route::resource('posts', PostController::class);
// Plus routes/api.php, routes/channels.php, RouteServiceProvider, etc.
```

**Falt Leap:**

```php
// conf/router.config.php
$routes = [
    "/users/{id}" => ["UsersController@show", ["auth"]],
    "/posts" => "PostsController@index"
];
```

That's it. One array. No service providers. No route caching. No magic.

---

### Models: Generated vs. Hand-Written

**Laravel:**

```php
// app/Models/User.php - 40+ lines of boilerplate
class User extends Model {
    protected $fillable = ['name', 'email', 'password'];
    protected $hidden = ['password', 'remember_token'];
    protected $casts = ['email_verified_at' => 'datetime'];
    // ... and hope it matches your migration
}

// database/migrations/2024_01_create_users_table.php - another 30 lines
// Now keep them in sync manually forever
```

**Falt Leap:**

```php
// Design your schema in PostgreSQL (where it belongs)
CREATE TABLE users (
    idusers SERIAL PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT NOW()
);

// Generate perfect models
$ php gen.php users

// models/Users.model.php - Auto-generated, always in sync
class Users extends LeapModel {
    public string $table = 'users';
    public array $cols = [/* all metadata from schema */];
}
```

Change your schema? Run `php gen.php users` again. Always in sync. No drift. No lies.

---

### Queries: Simple Things Stay Simple

**Laravel Eloquent:**

```php
$topCustomers = Order::select('customer_id', DB::raw('SUM(amount) as total'))
    ->with('customer')
    ->where('status', 'paid')
    ->groupBy('customer_id')
    ->having('total', '>', 1000)
    ->orderByDesc('total')
    ->limit(10)
    ->get();
```

**Falt Leap (same query):**

```php
$topCustomers = Orders::Query()
    ->select('customer_id', 'SUM(amount) as total')
    ->join(Customers::class, 'customer_id')
    ->where("status = :s", [':s' => 'paid'])
    ->groupBy('customer_id')
    ->having('total > :min', [':min' => 1000])
    ->orderBy('total DESC')
    ->limit(10)
    ->get();
```

Similar API, but **you can read the LeapQueryBuilder source in 10 minutes** and understand exactly what's happening.

---

### Configuration: .env vs. Config Hell

**Laravel:**

```
config/app.php (200+ lines)
config/database.php (150+ lines)
config/cache.php (100+ lines)
config/queue.php (100+ lines)
config/mail.php (120+ lines)
... 15+ more config files
```

**Falt Leap:**

```bash
# .env
DB_HOST=localhost
DB_NAME=myapp
DB_USER=postgres
DB_PASS=secret
APP_DEBUG=true

# Done.
```

---

## Built for Modern PHP, Not Around Legacy PHP

Most popular frameworks were born in the PHP 5 era. They carry **years of abstractions that exist to work around language limitations that no longer exist**. Laravel shipped its first version in 2011. Symfony in 2005. The PHP they were designed for didn't have strict types, union types, named arguments, enums, fibers, or even a sane error model.

So what did frameworks do? They built **thousands of lines of code** to compensate:

| What frameworks built | Why it existed | What PHP 8+ has natively |
|---|---|---|
| `Illuminate\Support\Str` (600+ lines) | PHP lacked basic string helpers | `str_contains()`, `str_starts_with()`, `str_ends_with()` |
| Doctrine DBAL abstraction layer | PDO was clunky and error-prone | PDO with `ERRMODE_EXCEPTION`, named params, proper fetch modes |
| Service container with reflection | No way to express dependencies clearly | Typed properties, union types, constructor promotion |
| Collection classes (1,500+ lines) | Arrays were painful to work with | Generators, `array_is_list()`, spread operator, arrow functions |
| Validation layers and type casting | No type safety at the language level | `declare(strict_types=1)`, union types, `mixed`, typed properties |
| Enum packages (`spatie/enum`, etc.) | PHP had no enum support | Native `enum` (PHP 8.1) |
| Null-handling helpers | Null checks were verbose and error-prone | Nullsafe operator `?->`, null coalescing `??` and `??=` |
| Template escaping helpers | Easy to forget `htmlspecialchars()` | We solve this at the architecture level with auto-escaping data wrappers |
| Macro/mixin systems | Extending framework classes was rigid | When your framework is 3,000 lines, you just change the code |

**Falt Leap doesn't carry this baggage.** It was written from scratch for PHP 8+ and leans on the language itself instead of reinventing it.

### What this looks like in practice

**Every file** in the framework uses `declare(strict_types=1)`. Types are enforced by PHP itself, not by a validation layer on top.

**PDO does the heavy lifting.** We don't wrap PDO in an abstraction layer that pretends databases are interchangeable. We use PDO's exception mode, named parameters, and prepared statements directly — because modern PDO is already good:

```php
// This is the entire query method. No DBAL. No query log. No event dispatcher.
$stmt = $this->connection->prepare($sql);
$stmt->execute($params);
return $stmt->fetchAll(PDO::FETCH_ASSOC);
```

**Dependency injection is just typed constructors.** No service container. No binding configuration. No `$this->app->make()`. PHP's type system tells us what a class needs:

```php
class UsersController extends LeapController {
    // db, request, session, view — injected automatically via typed properties
    // No container config. No service provider. No binding.
}
```

**Auto-escaping uses PHP's own magic methods** — not a custom template syntax with a compiler:

```php
// LeapSafeData wraps your data and escapes strings on property access
// No Blade compiler. No Twig lexer. Just __get() and htmlspecialchars().
<?= $this->data->username ?>  <!-- escaped automatically -->
```

The result: features that take thousands of lines in other frameworks take **dozens** in Falt Leap — because we let PHP 8 do what PHP 8 was designed to do.

---

## Why Falt Leap?

### You're Tired of Framework Bloat

You've spent your career dealing with:

- **300MB vendor folders** for a CRUD app
- **Breaking changes** in dependencies you can't control
- **Update fatigue** from packages you don't even use
- **Security alerts** from nested dependencies 8 levels deep
- **Debugging framework code** instead of building features

**Falt Leap says: enough.**

### Zero Dependencies, Zero Compromises

Modern PHP frameworks come with hundreds of dependencies. Falt Leap has **exactly zero**. Every line of code is in this repository. The entire framework is **~3,000 lines**. You can read and understand it in an afternoon.

```bash
# No composer install
# No npm install
# No build process
# No node_modules black hole
# Just clone and code
```

**What you get instead:**

- Full control over every line of code
- No supply chain vulnerabilities
- Instant deployment (just `git pull`)
- Zero dependency conflicts
- Framework code you can actually understand

### PostgreSQL-First (and Only)

We're not trying to support every database under the sun. Falt Leap is built **exclusively for PostgreSQL**, which means:

- Native schema support (`SET search_path`)
- Full embrace of PostgreSQL's type system
- No MySQL compatibility compromises
- Clean, predictable SQL generation
- Introspection that actually works

**If you're not using PostgreSQL, this framework isn't for you. And that's okay.**

### Schema-Driven Development

Stop writing models by hand. Stop maintaining duplicated schema information. With Falt Leap:

1. Design your database schema in PostgreSQL
2. Run `php gen.php all`
3. Get perfect Active Record models with full type information

Models are **generated, not written**. Change your schema? Regenerate. Add a column? Regenerate. Your models stay perfectly in sync with your database schema.

### Convention Over Configuration (Actually)

Routes are a simple array:

```php
$routes = [
    "/" => "HomeController@index",
    "/users/{id}" => "UsersController@show"
];
```

Controllers get automatic dependency injection:

```php
class UsersController extends LeapController {
    public function show($id) {
        $user = Users::WhereOne("id = :id", [":id" => $id]);
        return $this->view->render("users/show", (object)["user" => $user]);
    }
}
```

Models use Active Record with **fluent query building**:

```php
// Simple queries
$user = Users::Query()->where("username = :u", [":u" => $username])->first();
$user->email = "new@email.com";
$user->save();

// Complex joins (NEW in 0.2!)
$posts = Posts::Query()
    ->join(Users::class, 'idusers')
    ->where("posts.status = :status", [":status" => "published"])
    ->orderBy("posts.created_at DESC")
    ->limit(10)
    ->get();

foreach ($posts as $post) {
    echo $post->title;
    echo $post->users->username; // Joined data auto-hydrated!
}

// Aggregates
$totalRevenue = Orders::Sum('amount', 'status = :s', [':s' => 'completed']);
$activeUsers = Users::Count('last_login > NOW() - INTERVAL \'30 days\'');
```

No repositories. No services. No factories. No abstract factories. No query builder learning curve. Just **SQL you already know** with objects that make sense.

### Bootstrap 5 + Vanilla JavaScript

We don't dictate your frontend stack, but we have opinions:

- **Bootstrap 5.3** for UI components and responsive design
- **Vanilla JavaScript** for interactivity (no React, Vue, Angular bloat)
- **No build process** required - write code that runs in the browser
- **WebSockets built-in** for real-time features when you need them

Frontend complexity is optional, not mandatory.

## What's New in Version 0.3?

### 1. **Error Handling System with Debug Page**

A complete error handling system that replaces bare `echo`/`die()` calls with proper exception handling throughout the framework.

**Debug mode** (`APP_DEBUG=true`) gives you a rich, self-contained error page:

- Dark-themed UI with no external dependencies
- Exception class, message, and file:line at a glance
- Clickable stack trace sidebar with source code preview (error line highlighted)
- Tabs for Request, Session, and Environment data (passwords auto-masked)
- Interactive PHP console at the bottom for inspecting runtime state
- Syntax errors caught before execution via `php -l` lint checks on controllers and views

**Production mode** (`APP_DEBUG=false`) shows a clean Bootstrap error page with a reference ID. Full details are logged to `storage/logs/error.log`.

```php
// .env
APP_DEBUG=true   // Rich debug page with stack traces and console
APP_DEBUG=false  // Clean error page, details in storage/logs/error.log
```

The debug console is security-restricted to local/private IPs only. Database connection failures, missing routes, missing views, and PHP errors all flow through the same handler.

### 2. **Advanced Query Builder**

Build complex queries with joins, aggregates, grouping, and nested conditions:

```php
// Multi-table joins with auto-hydration
$orders = Orders::Query()
    ->join(Customers::class, 'idcustomers')
    ->join(Products::class, 'idproducts')
    ->where("orders.total > :min", [":min" => 100])
    ->orderBy("orders.created_at DESC")
    ->get();

// Aggregates: Count, Sum, Avg, Min, Max
$stats = [
    'total' => Orders::Sum('amount'),
    'avg' => Orders::Avg('amount', 'status = :s', [':s' => 'paid']),
    'count' => Orders::Count('created_at > NOW() - INTERVAL \'7 days\'')
];

// GROUP BY and HAVING support
$topCustomers = Orders::Query()
    ->select('customer_id', 'SUM(amount) as total')
    ->groupBy('customer_id')
    ->having('SUM(amount) > :min', [':min' => 1000])
    ->orderBy('total DESC')
    ->limit(10)
    ->get();
```

### 3. **Middleware Pipeline**

Composable middleware with automatic dependency injection:

```php
// Define middleware in routes
$routes = [
    "/admin/users" => ["UsersController@index", ["auth", "admin"]],
    "/api/data" => ["ApiController@data", ["auth", "rate-limit"]]
];

// Create custom middleware
class RateLimitMiddleware extends LeapMiddleware {
    public function handle(callable $next): mixed {
        if ($this->exceedsLimit()) {
            return $this->redirect("/error", "Rate limit exceeded");
        }
        return $next();
    }
}
```

No configuration files. No service providers. Just extend `LeapMiddleware` and use it.

### 4. **PSR-4 Autoloader**

Proper namespacing with automatic class loading:

```php
namespace App\Controllers;
namespace App\Models;
namespace App\Middleware;

// No manual requires. Ever.
```

### 5. **.env Configuration**

Environment-based configuration without external packages:

```php
// .env file
DB_HOST=localhost
DB_NAME=myapp
APP_DEBUG=true

// In code
$debug = LeapEnv::get('APP_DEBUG', false);
```

### 6. **Strict Types Everywhere**

Every framework file uses `declare(strict_types=1)`. Because it's 2025 and we should know our types.

## What Makes This Opinionated?

### 1. **No Composer, By Design**

We're not anti-dependency. We're anti-complexity. Every external package is a potential security risk, maintenance burden, and breaking change waiting to happen. Falt Leap is entirely self-contained.

**The entire framework is ~3,000 lines.** That's less than a single Laravel controller in some projects.

### 2. **PostgreSQL Only**

We don't waste time on database abstraction layers that make every database equally mediocre. We embrace PostgreSQL's features fully:

- Native JSON/JSONB support
- Full-text search capabilities
- Array types
- Advanced indexing
- Schema-aware queries
- `RETURNING` clause support

**If you're not using PostgreSQL, this framework isn't for you. And that's a feature, not a bug.**

### 3. **Generated Models**

Hand-written models get out of sync with your database. Generated models don't. We enforce schema-first development.

```bash
php gen.php all public
# Generates perfectly typed models from your schema
# Re-run after migrations. Always in sync. Always.
```

### 4. **Active Record Over Repository Pattern**

The repository pattern adds indirection without adding value for 90% of applications. Active Record is simpler, clearer, and faster to write.

You're building a web app, not a NASA mission control system.

### 5. **No ORM Magic**

Our "ORM" is a thin wrapper around PDO with schema introspection. You write SQL. We just make it convenient.

```php
// This is fine
$users = Users::Where("age > :age", [":age" => 18]);

// This is also fine
$result = $this->db->query("SELECT * FROM users WHERE created_at > NOW() - INTERVAL '1 day'");
```

We don't try to turn SQL into an object-oriented query language. SQL is already great.

### Your First Route

**1. Add route** in `/conf/router.config.php`:

```php
$routes = [
    "/hello/{name}" => "HelloController@greet"
];
```

**2. Create controller** in `/app/HelloController.php`:

```php
<?php
class HelloController extends LeapController {
    public function greet($name) {
        return $this->view->render("hello", (object)[
            "name" => $name,
            "title" => "Greetings"
        ]);
    }
}
?>
```

**3. Create view** in `/views/hello.leap.php`:

```html
<div class="container mt-5">
    <h1>Hello, <?= $this->data->name ?>!</h1>
</div>
```

Done. No service providers to register. No middleware to configure. No build process to run.

## Architecture

### Core Components (All in `/lib/` - **~3,000 lines total**)

Every component is documented, typed, and readable:

- **LeapEngine**: Application bootstrapper and request dispatcher
- **LeapRouter**: URL routing with regex pattern matching and middleware support
- **LeapController**: Base controller with auto-injected dependencies (db, request, session, view)
- **LeapModel**: Active Record ORM with fluent query builder (~840 lines including joins, aggregates, grouping)
- **LeapQueryBuilder**: Join builder with auto-hydration and complex query support (~350 lines)
- **LeapView**: Template engine with layout inheritance, flash messages, and auto-escaping
- **LeapSafeData**: Auto-escaping wrapper for XSS protection — strings are escaped on access, non-strings pass through
- **LeapDB**: PostgreSQL PDO wrapper with schema support and prepared statements
- **LeapRequest**: HTTP request abstraction with type-safe helpers
- **LeapSession**: Session management with flash message support
- **LeapErrorHandler**: Global error/exception handler with rich debug page and production error page
- **LeapDebugConsole**: Interactive PHP console for inspecting runtime state during errors
- **LeapHttpException**: Base HTTP exception with status code support
- **LeapNotFoundException**: 404 exception for missing routes, controllers, views
- **LeapSyntaxException**: Syntax error exception with source file/line context
- **LeapWebSocketServer**: Built-in WebSocket server for real-time features
- **LeapSafeData**: Auto-escaping data wrapper for XSS protection (~75 lines)
- **LeapAutoloader**: PSR-4 compliant autoloader (~145 lines)
- **LeapMiddleware**: Abstract middleware base class with dependency injection
- **LeapMiddlewareStack**: Middleware pipeline executor (~115 lines)
- **LeapEnv**: Zero-dependency .env file parser (~107 lines)

**Total framework size: ~3,000 lines across 20 files.** You can read it all in one sitting.

### Request Lifecycle

```
HTTP Request
    ↓
/public/index.php (loads PSR-4 autoloader + .env)
    ↓
LeapErrorHandler registers (catches all errors/exceptions from here on)
    ↓
LeapEngine->start($routes)
    ↓
LeapRouter->getRoute($url) → extracts controller, method, middleware
    ↓
Lint check on controller (debug mode) → LeapSyntaxException on failure
    ↓
LeapMiddlewareStack builds pipeline
    ↓
Middleware chain executes (auth, validation, logging, etc.)
    ↓
Controller instantiation (auto-injected: db, request, session, view)
    ↓
Controller method execution
    ↓
View rendering (lint check + layout + flash messages)
    ↓
HTTP Response

    ⚡ Any uncaught exception at any step → LeapErrorHandler
       → Debug mode:  Rich error page with stack trace + interactive console
       → Production:  Clean error page + logged to storage/logs/error.log
```

Straightforward. Predictable. **You can trace every line of execution.**

## Model Generation

Models are **generated from your database schema**, not hand-written:

```bash
# Generate all models from schema
php gen.php all [schema_name]

# Generate single model
php gen.php users
```

Generated models include:

- All columns with proper types
- Default values from database
- Nullable information
- Primary key detection
- Active Record methods (`save()`, `delete()`, `Where()`, `WhereOne()`)

Example generated model:

```php
class Users extends LeapModel {
    protected static $columns = ["id", "username", "email", "created_at"];
    protected static $defaults = ["created_at" => "CURRENT_TIMESTAMP"];
    protected static $nullables = ["email"];
    protected static $primaries = ["id"];
}
```

**Philosophy**: Your database schema is the source of truth. Models reflect it automatically.

## Who Is This For?

### You'll love Falt Leap if you

- ✅ **Value simplicity over abstraction** - You're tired of 10 layers between you and the database
- ✅ **Trust PostgreSQL more than ORMs** - You know PostgreSQL can do things Laravel's query builder can't
- ✅ **Want to understand your framework** - All ~3,000 lines of it
- ✅ **Prefer convention over configuration** - Routes are arrays, not YAML manifests
- ✅ **Think modern frameworks have gotten ridiculous** - When did a TODO app need 400 dependencies?
- ✅ **Build real applications** - CRUD apps, dashboards, internal tools, SaaS backends, APIs
- ✅ **Ship fast** - No build step, no compile, no webpack. Just deploy.
- ✅ **Don't need to support every database** - PostgreSQL is enough (and better than MySQL anyway)
- ✅ **Are tired of breaking changes** - The framework is complete. No surprise v10 rewrite coming.
- ✅ **Want to own your code** - Not rent it from package maintainers who might abandon it

### This is for burned-out senior developers who remember when PHP was fun

When you could understand your entire stack. When "upgrading" didn't mean 3 days of fixing breaking changes. When you shipped features instead of fighting your framework.

**Falt Leap is that world again.**

### You won't like Falt Leap if you

- ❌ Need MySQL/SQLite/MongoDB support (we're PostgreSQL-only by design)
- ❌ Want a massive ecosystem of plugins (we have 14 files, not 14,000 packages)
- ❌ Prefer repository patterns and service layers (we think those add complexity without value)
- ❌ Need queues, events, notifications out of the box (build what you need, when you need it)
- ❌ Want framework-agnostic code (this is intentionally coupled to PostgreSQL)
- ❌ Love microservices architecture (this is for monoliths and we're proud of it)
- ❌ Think TypeScript is the only way (this is PHP with strict types, not JavaScript cosplay)

## Project Structure

```
/app/              Controllers (suffix: Controller.php)
/conf/             Configuration files
  ├── db.config.php         Database connection
  └── router.config.php     Route definitions
/lib/              Framework core (20 classes, zero dependencies)
/models/           Generated model classes
/public/           Web root (index.php entry point)
/storage/          Runtime data (gitignored)
  ├── /logs/               Error logs (error.log)
  └── /debug/              Debug console context files
/views/            Templates (.leap.php extension)
  └── index.leap.php        Master layout
/container/        Docker development environment
.env               Environment config (DB credentials, APP_DEBUG)
gen.php            Model generator
run.sh             Docker dev server launcher
```

## Philosophy

### Less is More (Actually)

Modern frameworks give you everything. Falt Leap gives you **what you actually need**:

- **Routing** - Array-based, regex-powered, dead simple
- **Controllers** - Automatic dependency injection, no configuration
- **Active Record models** - With query builder, joins, aggregates
- **Middleware** - Composable pipelines without complexity
- **Template rendering** - Layouts, partials, flash messages
- **Session management** - Built-in, secure, straightforward
- **Error handling** - Debug page with stack traces, console, and production-safe error screens
- **Database connection** - PostgreSQL-native with schema support
- **Autoloading** - PSR-4 compliant, zero-config
- **.env support** - Configuration without packages

**Total: ~3,000 lines.** Including a full error handling system with interactive debug console.

Everything else? That's your code to write, not framework bloat to maintain. Need queues? Write a queue. Need emails? Send emails. You're a developer, not a framework configurator.

### Database-First, Always

**Your database schema IS your domain model.**

Design it well in PostgreSQL, and Falt Leap reflects it perfectly in code:

1. Design tables with proper types, constraints, defaults
2. Run `php gen.php all`
3. Get perfectly typed models with all metadata
4. Change schema? Regenerate. Always in sync.

No schema drift. No model divergence. No `@ORM` annotations trying to recreate what your database already knows. **Just truth.**

### Boring Technology Wins

We use technology that will still work in 10 years:

- **PHP 8+** - The language that runs 80% of the web (yes, still)
- **PostgreSQL** - The database that's been rock-solid for 30 years
- **PDO** - PHP's standard database interface
- **Bootstrap 5** - The CSS framework that needs no introduction
- **Vanilla JavaScript** - The only frontend dependency that won't have a breaking change next week

No bleeding edge. No "revolutionary" architecture. No framework rewrites every 18 months.

**Just proven, boring technology that works.**

### You Own Your Stack

Every dependency is a liability:

- Security vulnerabilities you didn't write
- Breaking changes you didn't ask for
- Maintenance burden you can't control
- Abandoned packages that break your build

**Falt Leap has zero dependencies.** You own every line. You control every update. You decide when things change.

No one can break your code except you.

## Documentation

- **Framework Guide**: See `/CLAUDE.md` for detailed component documentation
- **Model Generation**: Run `php gen.php` for usage
- **Docker Development**: Run `./run.sh` for instant dev environment

## Real Talk: Why Another Framework?

**Because we're tired.**

Tired of spending more time configuring than coding. Tired of frameworks that need frameworks. Tired of "simple" examples that require 50 lines of boilerplate. Tired of upgrade guides longer than the features they add.

**The Modern Framework Tax:**

```bash
# Laravel 11 - Fresh install
$ composer create-project laravel/laravel myapp
$ du -sh myapp/vendor
274M    myapp/vendor
$ find myapp/vendor -name "*.php" | wc -l
32,847 files
```

**Falt Leap:**

```bash
$ git clone faltleap myapp
$ du -sh myapp/lib
124K    myapp/lib
$ find myapp/lib -name "*.php" | wc -l
20 files
```

**274MB vs 124KB. 32,847 files vs 20 files.**

And you know what? The Falt Leap version will still be working in 5 years. No breaking changes. No deprecated APIs. No surprise rewrites.

### This Framework Is Complete

We're not trying to be everything to everyone. We're trying to be **exactly what you need** for PostgreSQL web applications.

- Want to add Redis? Just use Redis. It's PHP.
- Need queues? Write a queue table and a worker script.
- Want GraphQL? Add a library. Or don't. Up to you.

**You're in control. Not your framework's roadmap.**

### For Senior Developers Who've Seen Enough

You've used:

- Laravel (too much magic)
- Symfony (too much configuration)
- Slim (too little structure)
- CodeIgniter (dated patterns)
- Raw PHP (too much reinventing)

**Falt Leap is the Goldilocks framework.**

Just enough structure. Just enough features. Just enough code to read in one sitting.

And when something goes wrong? You can **actually debug it** because you understand every component.

## Performance

**Fast.** PostgreSQL is fast. PDO is fast. PHP 8 is fast. No ORM overhead. No middleware bloat. No service container reflection magic.

Typical request cycle: **< 5ms** (excluding database queries).

And because there's no Composer autoloader scanning thousands of files, cold starts are instant.

## Contributing

Falt Leap is intentionally minimal. We're not trying to compete with Laravel or Symfony. We're building a **focused tool for a specific philosophy**.

**What we'll accept:**

- Bug fixes in core components
- Better PostgreSQL integration
- Documentation improvements
- Performance optimizations
- Security fixes

**What we won't accept:**

- MySQL/SQLite/MongoDB support
- Composer dependencies
- Complex abstractions
- Enterprise features for the sake of checkboxes
- Breaking changes without extremely good reason

**Code contributions must:**

- Use `declare(strict_types=1)`
- Be fully typed
- Include clear documentation
- Not break backward compatibility
- Stay true to the framework philosophy

## License

BSD License - Use it, fork it, learn from it, build with it.

No strings attached. No corporate ownership. No surprise relicensing.

---

## One More Thing

If you're a burned-out senior developer who clicked on this README thinking "finally, someone gets it" — **you're right.**

This framework exists because we felt the same way.

We were tired of explaining to junior developers why we need 47 config files for a CRUD app. Tired of Stack Overflow answers that just say "use this package." Tired of pretending that complexity equals professionalism. The whole webstack is so broken, that we need AI to make something half decent - when we actually don't need it at all.

**Simple is professional. Boring is reliable. Small is beautiful.**

If that resonates with you, give Falt Leap a try. Read the code. Understand it. Own it.

And then build something great.

---

**Built with conviction. Powered by PostgreSQL. Zero dependencies, zero regrets.**

**Version 0.3** • ~3,000 lines • 20 files • ∞ possibilities
