# Upgrade Guide

## Upgrading from v1.x to v2.0

### Requirements Changes

**Before (v1.x):**
- PHP 8.1+
- Laravel 8.x+

**After (v2.0):**
- PHP 8.2+ (8.3+ recommended)
- Laravel 11.x+

### Breaking Changes

#### 1. Minimum PHP Version
The minimum PHP version has been upgraded from **8.1** to **8.2**. While we recommend PHP 8.3+ for the best experience, PHP 8.2 is the minimum requirement.

#### 2. Minimum Laravel Version
The minimum Laravel version has been upgraded from **8.x** to **11.x**. This provides access to the latest Laravel features and security updates.

#### 3. Updated Dependencies
All development dependencies have been updated to their latest versions compatible with Laravel 11:

- `spatie/laravel-package-tools`: ^1.16.0 (from ^1.14.0)
- `illuminate/contracts`: ^11.0 (from ^10.0)
- `pestphp/pest`: ^2.35.0 (from ^2.0)
- And many more...

### What's Improved

#### 1. Modern PHP Features
- Enhanced readonly properties for better immutability
- Better type safety with modern PHP features
- Performance improvements from PHP 8.2/8.3 features

#### 2. Comprehensive Test Suite
- 31 tests with 103 assertions
- All core functionality tested
- Removed flaky integration tests with Guzzle dependencies

#### 3. Better Developer Experience
- Updated PHPStan configuration for better static analysis
- Latest Laravel Pint for code formatting
- Modern Pest testing framework
- Enhanced GitHub Actions workflows with matrix testing
- Security audit automation
- Automated dependency management

### Migration Steps

#### 1. Check System Requirements
Ensure your system meets the new requirements:

```bash
php -v  # Should show PHP 8.2+
```

#### 2. Update Your Laravel Project
If your Laravel application is not yet on Laravel 11, upgrade it first:

```bash
composer update laravel/framework
```

#### 3. Update Passage
Update the package to v2.0:

```bash
composer update morcen/passage
```

#### 4. Run Tests
Ensure your application still works correctly:

```bash
php artisan test
```

### Configuration Changes

No configuration changes are required. All existing `config/passage.php` configurations remain compatible.

### API Changes

There are **no breaking API changes**. All existing:
- Service configurations
- Custom controllers implementing `PassageControllerInterface`  
- Route macros
- Facade usage

...will continue to work exactly as before.

### Deprecations

None. This is a clean upgrade focused on modernizing the underlying platform requirements.

### Rollback Plan

If you need to rollback for any reason:

```bash
composer require "morcen/passage:^1.0"
```

Note that this will also require downgrading your PHP/Laravel versions to meet the v1.x requirements.

### Support

- v1.x will receive security updates for 6 months after v2.0 release
- v2.0+ receives full feature and security support
- For issues, please file them on [GitHub](https://github.com/morcen/passage/issues)
