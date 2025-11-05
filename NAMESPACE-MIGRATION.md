# Namespace Migration: lib/ → src/

This document outlines the plan for migrating the framework core from `/lib/` to `/src/` with proper PSR-4 namespacing for Composer distribution.

## Current Structure

```
/lib/
├── LeapEngine.php
├── LeapRouter.php
├── LeapController.php
├── LeapModel.php
├── LeapQueryBuilder.php
├── LeapView.php
├── LeapDB.php
├── LeapRequest.php
├── LeapSession.php
├── LeapWebSocketServer.php
├── LeapAutoloader.php
├── LeapMiddleware.php
├── LeapMiddlewareStack.php
└── LeapEnv.php
```

All classes are already in the `FlatLeap` namespace but located in `/lib/`.

## Target Structure

```
/src/
├── LeapEngine.php
├── LeapRouter.php
├── LeapController.php
├── LeapModel.php
├── LeapQueryBuilder.php
├── LeapView.php
├── LeapDB.php
├── LeapRequest.php
├── LeapSession.php
├── LeapWebSocketServer.php
├── LeapAutoloader.php
├── LeapMiddleware.php
├── LeapMiddlewareStack.php
└── LeapEnv.php
```

## Migration Plan

### Phase 1: Preparation

1. **Create `/src/` directory**
   ```bash
   mkdir -p src
   ```

2. **Verify composer.json**
   ```json
   {
       "autoload": {
           "psr-4": {
               "FlatLeap\\": "src/"
           }
       }
   }
   ```

3. **Verify all classes use namespace**
   All files should have:
   ```php
   <?php
   declare(strict_types=1);

   namespace FlatLeap;
   ```

### Phase 2: File Migration

Move all files from `/lib/` to `/src/`:

```bash
# Move all PHP files
mv lib/*.php src/

# Or copy first for safety
cp lib/*.php src/
```

**Files to migrate:**
- LeapEngine.php
- LeapRouter.php
- LeapController.php
- LeapModel.php
- LeapQueryBuilder.php
- LeapView.php
- LeapDB.php
- LeapRequest.php
- LeapSession.php
- LeapWebSocketServer.php
- LeapAutoloader.php
- LeapMiddleware.php
- LeapMiddlewareStack.php
- LeapEnv.php

### Phase 3: Update Autoloader References

**Before** (in `index.php` or `bootstrap.php`):
```php
require_once __DIR__ . '/lib/LeapAutoloader.php';

$loader = new \FlatLeap\LeapAutoloader();
$loader->register();
$loader->addNamespace('FlatLeap', __DIR__ . '/lib');
```

**After**:
```php
require_once __DIR__ . '/vendor/autoload.php';
// Composer handles everything
```

### Phase 4: Update Development Bootstrap

For framework development itself (not for apps using the package), update any development scripts that reference `/lib/`:

**gen.php** - May need updates if it references lib paths directly.

**index.php** (development) - Update to use Composer autoloader:
```php
<?php
declare(strict_types=1);

// Development mode - load from src/
require_once __DIR__ . '/vendor/autoload.php';

// Or if running without Composer in dev:
require_once __DIR__ . '/src/LeapAutoloader.php';
$loader = new \FlatLeap\LeapAutoloader();
$loader->register();
$loader->addNamespace('FlatLeap', __DIR__ . '/src');
```

### Phase 5: Update Documentation

Update references to `/lib/` in:
- [x] README.md
- [x] CLAUDE.md
- [x] MIGRATION.md
- [ ] Any other documentation files

### Phase 6: Testing

1. **Test Composer autoloading**:
   ```bash
   composer dump-autoload
   php -r "use FlatLeap\LeapEngine; echo 'OK';"
   ```

2. **Test framework functionality**:
   - Database connections
   - Routing
   - Controller execution
   - Model queries
   - View rendering
   - Middleware pipelines
   - Session management

3. **Test model generation**:
   ```bash
   php vendor/bin/gen all public
   ```

### Phase 7: Cleanup

After verification:

```bash
# Remove old lib directory
rm -rf lib/

# Update .gitignore if needed
echo "# Old framework location" >> .gitignore
echo "/lib/" >> .gitignore
```

## Compatibility Considerations

### For Existing Apps

Apps already using the framework will need to:

1. Update to Composer-based installation (see MIGRATION.md)
2. No code changes required - namespace remains `FlatLeap`
3. Framework location changes from `/lib/` to `/vendor/faltleap/core/src/`

### For Framework Contributors

1. Clone the repository
2. Run `composer install` for development dependencies (if any)
3. Framework source is now in `/src/` not `/lib/`
4. PSR-4 autoloading via Composer

## Backward Compatibility

### What Stays the Same

- ✅ Namespace: `FlatLeap`
- ✅ Class names: `LeapEngine`, `LeapController`, etc.
- ✅ Public APIs: No breaking changes
- ✅ Usage in applications: No code changes needed

### What Changes

- ❌ File location: `/lib/` → `/src/` (framework development only)
- ❌ Autoloading: Manual → Composer PSR-4 (in apps)
- ✅ Apps use `vendor/autoload.php` instead of manual requires

## Implementation Checklist

- [ ] Create `/src/` directory
- [ ] Copy/move all files from `/lib/` to `/src/`
- [ ] Verify all classes have `namespace FlatLeap;`
- [ ] Test Composer autoloading: `composer dump-autoload`
- [ ] Update `gen.php` if needed
- [ ] Update development bootstrap files
- [ ] Update all documentation references
- [ ] Test all framework features
- [ ] Test model generation
- [ ] Test in a sample app
- [ ] Remove `/lib/` directory
- [ ] Commit changes
- [ ] Tag new version: `v0.2.0`
- [ ] Publish to Packagist

## Timeline

**Recommended approach**: Do this migration BEFORE publishing to Packagist

1. Complete migration locally
2. Test thoroughly
3. Update documentation
4. Commit to git
5. Tag version 0.2.0
6. Publish to Packagist
7. Update installation instructions

## Commands Summary

```bash
# 1. Create src directory
mkdir -p src

# 2. Move files
mv lib/*.php src/

# 3. Update Composer autoloader
composer dump-autoload

# 4. Test autoloading
php -r "use FlatLeap\LeapEngine; echo 'Autoload works\n';"

# 5. Test model generation
php vendor/bin/gen all public

# 6. Remove old directory
rm -rf lib/

# 7. Commit
git add src/ composer.json
git rm -r lib/
git commit -m "Migrate framework core to src/ with PSR-4 autoloading"

# 8. Tag release
git tag v0.2.0
git push origin master --tags
```

## Post-Migration

### Publishing to Packagist

1. Create GitHub repository for `faltleap/core`
2. Submit to Packagist: https://packagist.org/packages/submit
3. Configure GitHub webhook for auto-updates
4. Users can install: `composer require faltleap/core`

### Creating App Skeleton

Create separate repository: `faltleap/app`

```bash
# In faltleap/app repo
composer init
composer require faltleap/core

# Copy stub files
cp -r ../faltleap-core/stubs/* .
mv bootstrap.php.stub bootstrap.php
mv public/index.php.stub public/index.php
# etc...

# Configure as project template
# Users install: composer create-project faltleap/app myapp
```

## Benefits After Migration

✅ **Clean structure**: Framework in `vendor/`, app code in project root
✅ **Easy updates**: `composer update faltleap/core`
✅ **Version control**: Pin versions in `composer.json`
✅ **Professional**: Standard PHP package structure
✅ **Separation**: Framework development separate from app development
✅ **Distribution**: Easy installation for new users

## Questions?

See:
- `MIGRATION.md` - For migrating existing apps
- `README.md` - For framework overview
- `CLAUDE.md` - For development conventions

---

**Status**: 📝 Planning complete, ready for implementation
**Next step**: Execute Phase 1 & 2 (create /src/ and move files)
