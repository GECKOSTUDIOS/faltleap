# Falt Leap Framework

![PHP 8+](https://img.shields.io/badge/PHP-8%2B-777BB4?style=flat-square&logo=php)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-Only-336791?style=flat-square&logo=postgresql)
![Zero Core Dependencies](https://img.shields.io/badge/Core_Dependencies-0-success?style=flat-square)
![Lines of Code](https://img.shields.io/badge/Lines_of_Code-2,191-blue?style=flat-square)
![License](https://img.shields.io/badge/License-BSD-green?style=flat-square)

**A radically simple, zero-dependency PHP framework for PostgreSQL purists.**

> *"Finally, a framework that doesn't fight me. I can read the entire codebase in an afternoon and actually understand what's happening. No magic. No surprises. Just clean code that does what it says."*
> — Every burned-out senior developer who finds this

Falt Leap is an opinionated MVC framework that throws out the complexity of modern PHP development and gets back to basics. The core has zero dependencies. Clean, fast PHP that embraces PostgreSQL's power. Distributed via Composer for your convenience.

**Version 0.2** introduces advanced query building, PSR-4 autoloading, middleware pipelines, and `.env` support—all while staying under **2,200 lines of core code**.

---

## Get Started in 60 Seconds

```bash
# Create a new app
composer create-project faltleap/app myapp
cd myapp

# Configure your database
cp .env.example .env
# Edit .env with your PostgreSQL credentials

# Generate models from your schema
php vendor/bin/gen all public

# Start the dev server (Docker)
./run.sh

# Done. Visit http://localhost:8090
```

**One `composer` command. No `npm install`. No build step. No bloated vendor folder. Just code.**

---

## Feature Comparison

| Feature | Falt Leap | Laravel 11 | Symfony 7 |
|---------|-----------|------------|-----------|
| **Lines of Code** | 2,191 | ~500,000+ | ~800,000+ |
| **Core Dependencies** | 0 | 30+ direct, 100+ total | 50+ direct, 200+ total |
| **Vendor Folder Size** | ~200 KB | ~274 MB | ~350 MB |
| **Fresh Install Time** | ~10 seconds | 2-5 minutes | 3-7 minutes |
| **Routing** | ✅ Array-based | ✅ Attribute/File-based | ✅ YAML/Annotation |
| **Query Builder** | ✅ With joins & aggregates | ✅ Full Eloquent | ✅ Doctrine |
| **Middleware** | ✅ Simple pipelines | ✅ Complex pipelines | ✅ Complex pipelines |
| **Autoloading** | ✅ PSR-4 | ✅ Composer | ✅ Composer |
| **Database Support** | PostgreSQL only | MySQL, Postgres, SQLite | Any with Doctrine |
| **ORM** | Active Record | Eloquent (Active Record) | Doctrine (Data Mapper) |
| **Template Engine** | Built-in (PHP) | Blade | Twig |
| **Zero Config Setup** | ✅ Yes | ❌ No | ❌ No |
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

# Done.
```

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

### Zero Core Dependencies, Maximum Control

Modern PHP frameworks come with hundreds of dependencies. Falt Leap's core has **exactly zero**. Every line of framework code is in the core package. The entire framework is **2,191 lines**. You can read and understand it in an afternoon.

```bash
# One composer command to get started
composer create-project faltleap/app myapp

# Vendor folder: ~200KB (just the core framework)
# No transitive dependencies
# No npm install
# No build process
# No node_modules black hole
```

**What you get:**

- Full control over every line of framework code
- No supply chain vulnerabilities in core
- Easy updates via `composer update faltleap/core`
- Version pinning when you need stability
- Framework code you can actually understand
- Composer for distribution, not for dependency hell

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

## What's New in Version 0.2?

### 1. **Advanced Query Builder**

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

### 2. **Middleware Pipeline**

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

### 3. **PSR-4 Autoloader**

Proper namespacing with automatic class loading:

```php
namespace App\Controllers;
namespace App\Models;
namespace App\Middleware;

// No manual requires. Ever.
```

### 4. **.env Configuration**

Environment-based configuration without external packages:

```php
// .env file
DB_HOST=localhost
DB_NAME=myapp
APP_DEBUG=true

// In code
$debug = LeapEnv::get('APP_DEBUG', false);
```

### 5. **Strict Types Everywhere**

Every framework file uses `declare(strict_types=1)`. Because it's 2025 and we should know our types.

## What Makes This Opinionated?

### 1. **Zero Core Dependencies, By Design**

We're not anti-dependency. We're anti-complexity. Every external package is a potential security risk, maintenance burden, and breaking change waiting to happen. Falt Leap's core is entirely self-contained with **zero dependencies**.

**The entire framework is 2,191 lines.** That's less than a single Laravel controller in some projects.

We use Composer for distribution and convenience, not for pulling in hundreds of packages. Your `vendor` folder stays tiny.

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

## Quick Start

### Installation

```bash
# Create a new app
composer create-project faltleap/app myapp
cd myapp

# Configure database
cp .env.example .env
# Edit .env with your PostgreSQL credentials

# Generate models from your schema
php vendor/bin/gen all public

# Start dev server
./run.sh  # Docker on http://localhost:8090
```

Or configure manually:

1. Your `.env` file:

```bash
DB_HOST=localhost
DB_NAME=myapp
DB_USER=postgres
DB_PASS=secret
DB_SCHEMA=public
```

2. Generate models from your schema:

```bash
php vendor/bin/gen all
```

3. Point your web server to `/public/`

That's it. One `composer` command. No `npm install`. No webpack config.

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
    <h1>Hello, <?= htmlspecialchars($name) ?>!</h1>
</div>
```

Done. No service providers to register. No middleware to configure. No build process to run.

## Architecture

### Core Components (All in `/lib/` - **2,191 lines total**)

Every component is documented, typed, and readable:

- **LeapEngine**: Application bootstrapper and request dispatcher
- **LeapRouter**: URL routing with regex pattern matching and middleware support
- **LeapController**: Base controller with auto-injected dependencies (db, request, session, view)
- **LeapModel**: Active Record ORM with fluent query builder (~840 lines including joins, aggregates, grouping)
- **LeapQueryBuilder**: Join builder with auto-hydration and complex query support (~350 lines)
- **LeapView**: Template engine with layout inheritance and flash messages
- **LeapDB**: PostgreSQL PDO wrapper with schema support and prepared statements
- **LeapRequest**: HTTP request abstraction with type-safe helpers
- **LeapSession**: Session management with flash message support
- **LeapWebSocketServer**: Built-in WebSocket server for real-time features
- **LeapAutoloader**: PSR-4 compliant autoloader (~145 lines)
- **LeapMiddleware**: Abstract middleware base class with dependency injection
- **LeapMiddlewareStack**: Middleware pipeline executor (~115 lines)
- **LeapEnv**: Zero-dependency .env file parser (~107 lines)

**Total framework size: 2,191 lines.** You can read it all in one sitting.

### Request Lifecycle

```
HTTP Request
    ↓
/public/index.php (loads PSR-4 autoloader + .env)
    ↓
LeapEngine->start($routes)
    ↓
LeapRouter->getRoute($url) → extracts controller, method, middleware
    ↓
LeapMiddlewareStack builds pipeline
    ↓
Middleware chain executes (auth, validation, logging, etc.)
    ↓
Controller instantiation (auto-injected: db, request, session, view)
    ↓
Controller method execution
    ↓
View rendering (with optional layout + flash messages)
    ↓
HTTP Response
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
- ✅ **Want to understand your framework** - All 2,191 lines of it
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
/lib/              Framework core (8 classes, zero dependencies)
/models/           Generated model classes
/public/           Web root (index.php entry point)
/storage/          Session storage
/views/            Templates (.leap.php extension)
  └── index.leap.php        Master layout
/container/        Docker development environment
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
- **Database connection** - PostgreSQL-native with schema support
- **Autoloading** - PSR-4 compliant, zero-config
- **.env support** - Configuration without packages

**Total: 2,191 lines.**

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

**Falt Leap's core has zero dependencies.** You own every line of framework code. You control updates with `composer.json` version constraints. You decide when things change.

The framework can't break your code with surprise updates.

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
$ composer create-project faltleap/app myapp
$ du -sh myapp/vendor
200K    myapp/vendor
$ find myapp/vendor/faltleap -name "*.php" | wc -l
14 files
```

**274MB vs 200KB. 32,847 files vs 14 files.**

And you know what? The Falt Leap version will still be working in 5 years. No breaking changes. No deprecated APIs. No surprise rewrites. Just pin the version in your `composer.json` and you're done.

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
- External dependencies in the core
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

**Version 0.2** • 2,191 lines • 14 files • ∞ possibilities
