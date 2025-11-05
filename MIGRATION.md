# Migration Guide: From Standalone to Composer-based Installation

This guide helps you migrate an existing Falt Leap application to use the new Composer-based structure.

## Overview

**Before (v0.1)**: Framework code in `/lib/`, manual installation via git clone
**After (v0.2)**: Framework distributed via Composer as `faltleap/core`, zero core dependencies maintained

## Benefits of Migration

- ✅ Easy framework updates via `composer update faltleap/core`
- ✅ Version pinning for stability
- ✅ Cleaner separation of framework vs app code
- ✅ Professional dependency management
- ✅ Framework core still has zero dependencies

## Migration Steps

### Step 1: Backup Your Application

```bash
# Create a backup of your entire application
cp -r /path/to/your-app /path/to/your-app-backup
```

### Step 2: Install Composer Dependencies

Create `composer.json` in your application root:

```json
{
    "name": "yourcompany/yourapp",
    "description": "Your Falt Leap Application",
    "type": "project",
    "require": {
        "php": "^8.0",
        "faltleap/core": "^0.2"
    },
    "autoload": {
        "psr-4": {
            "App\\Controllers\\": "app/",
            "App\\Models\\": "models/",
            "App\\Middleware\\": "middleware/"
        }
    }
}
```

Install dependencies:

```bash
composer install
```

### Step 3: Migrate Configuration

#### Old: `/conf/db.config.php`

```php
<?php
$dbhost = "localhost";
$dbusername = "postgres";
$dbpassword = "secret";
$dbdatabase = "myapp";
$dbschema = "public";
```

#### New: `.env` file

```bash
DB_HOST=localhost
DB_USERNAME=postgres
DB_PASSWORD=secret
DB_DATABASE=myapp
DB_SCHEMA=public

APP_DEBUG=true
APP_ENV=production
```

Create `.env.example` for version control:

```bash
# Copy your .env but remove sensitive data
cp .env .env.example
# Edit .env.example and remove passwords
```

**Important**: Add `.env` to `.gitignore` if not already there!

### Step 4: Update Bootstrap

#### Old: `/public/index.php`

```php
<?php
include('../install.php');
include('../index.php');
```

#### New: `/public/index.php`

```php
<?php
declare(strict_types=1);

// Bootstrap the application
require_once __DIR__ . '/../bootstrap.php';
```

Create new `/bootstrap.php`:

```php
<?php
declare(strict_types=1);

// Set session storage path
session_save_path(__DIR__ . '/storage');
session_start();

// Enable error reporting in development
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Load Composer autoloader
require_once __DIR__ . '/vendor/autoload.php';

use FlatLeap\LeapEnv;
use FlatLeap\LeapEngine;

// Load environment variables from .env file
$envPath = __DIR__ . '/.env';
if (!file_exists($envPath)) {
    die("Error: .env file not found. Please copy .env.example to .env and configure your database settings.");
}
LeapEnv::load($envPath);

// Create dbconfig array from environment variables
$dbconfig = [
    'dbhost' => LeapEnv::get('DB_HOST'),
    'dbusername' => LeapEnv::get('DB_USERNAME'),
    'dbpassword' => LeapEnv::get('DB_PASSWORD'),
    'dbdatabase' => LeapEnv::get('DB_DATABASE'),
    'dbschema' => LeapEnv::get('DB_SCHEMA', 'public')
];

// Load routes
require_once __DIR__ . '/conf/router.config.php';

// Start the application
$engine = new LeapEngine();
$engine->start($routes);
```

### Step 5: Update Model Generation

#### Old:

```bash
php gen.php all public
php gen.php users
```

#### New:

```bash
php vendor/bin/gen all public
php vendor/bin/gen users
```

Or add a shortcut to your `composer.json`:

```json
{
    "scripts": {
        "gen": "php vendor/bin/gen"
    }
}
```

Then use:

```bash
composer gen all public
composer gen users
```

### Step 6: Remove Old Files

After verifying everything works:

```bash
# Remove old framework files (now in vendor/)
rm -rf lib/

# Remove old config file (replaced by .env)
rm conf/db.config.php

# Remove old bootstrap files
rm index.php
rm install.php

# Keep these:
# - app/          (your controllers)
# - models/       (your models)
# - views/        (your views)
# - public/       (web root)
# - conf/         (router config)
# - storage/      (sessions)
# - middleware/   (your middleware)
```

### Step 7: Update Docker Configuration (if using Docker)

Update your `run.sh` or Docker configuration to ensure:

1. Composer is installed in the container
2. `composer install` runs on container start
3. Volume mounts include `vendor/`

Example `container/entrypoint.sh`:

```bash
#!/bin/sh

# Run composer install if vendor doesn't exist
if [ ! -d "/var/www/localhost/htdocs/vendor" ]; then
    cd /var/www/localhost/htdocs
    composer install --no-dev --optimize-autoloader
fi

# Start PHP-FPM and Nginx
php-fpm8 -D
nginx -g 'daemon off;'
```

### Step 8: Update .gitignore

Add to `.gitignore`:

```
# Environment
.env

# Composer
/vendor/

# Storage
/storage/*
!/storage/.gitkeep
```

Keep in version control:

```
.env.example
composer.json
composer.lock
bootstrap.php
```

## Verification Checklist

After migration, verify:

- [ ] Application loads without errors
- [ ] Database connection works
- [ ] Sessions are working
- [ ] Routes resolve correctly
- [ ] Controllers execute properly
- [ ] Views render correctly
- [ ] Model generation works: `php vendor/bin/gen all`
- [ ] `.env` is in `.gitignore`
- [ ] `.env.example` is in version control
- [ ] `composer.lock` is committed

## Framework Updates

### To update the framework:

```bash
# Update to latest minor version
composer update faltleap/core

# Update to specific version
composer require faltleap/core:^0.3
```

### To pin a specific version:

Edit `composer.json`:

```json
{
    "require": {
        "faltleap/core": "0.2.5"
    }
}
```

Then run:

```bash
composer update faltleap/core
```

## Rollback

If you need to rollback, restore from your backup:

```bash
rm -rf /path/to/your-app
cp -r /path/to/your-app-backup /path/to/your-app
```

## Common Issues

### Issue: "Class not found"

**Solution**: Run `composer dump-autoload`

### Issue: "vendor/bin/gen not found"

**Solution**: Ensure composer.json has:

```json
{
    "require": {
        "faltleap/core": "^0.2"
    }
}
```

Then run `composer install`

### Issue: "Cannot load .env file"

**Solution**:
1. Ensure `.env` exists in project root
2. Check file permissions: `chmod 644 .env`
3. Verify `LeapEnv::load($envPath)` is called in bootstrap

### Issue: "Database connection failed"

**Solution**:
1. Verify `.env` has correct database credentials
2. Check PostgreSQL is running
3. Ensure database exists: `psql -U postgres -c "CREATE DATABASE myapp;"`

## Need Help?

- Check `CLAUDE.md` for framework conventions
- Review `vendor/faltleap/core/README.md` for core documentation
- Open an issue: https://github.com/yourrepo/faltleap-core/issues

## Next Steps After Migration

1. **Version control**: Commit your changes
   ```bash
   git add .
   git commit -m "Migrate to Composer-based Falt Leap v0.2"
   ```

2. **Deploy**: Update your deployment process to run `composer install --no-dev`

3. **Team**: Share `.env.example` with your team, each developer creates their own `.env`

4. **CI/CD**: Update CI pipelines to install Composer dependencies

---

**Migration completed?** You now have a cleaner, more maintainable Falt Leap application with easy framework updates! 🚀
