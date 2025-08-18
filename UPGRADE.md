# Upgrade Guide

This guide will help you upgrade your Laravel One-Click Installer package to newer versions.

## General Upgrade Process

### Step 1: Update the Package

```bash
composer update clevpro/laravel-one-click-installer
```

### Step 2: Publish New Assets (if needed)

```bash
# Republish configuration (backup your custom config first)
php artisan vendor:publish --tag=installer-config --force

# Republish views (backup your custom views first)
php artisan vendor:publish --tag=installer-views --force

# Republish assets
php artisan vendor:publish --tag=installer-assets --force
```

### Step 3: Clear Caches

```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

### Step 4: Test Installation

```bash
# Check installer status
php artisan installer:status

# Test installer functionality
# Visit /install to ensure everything works correctly
```

---

## Version-Specific Upgrade Instructions

### Upgrading to v1.0.0

This is the initial release, so no upgrade needed.

**New Features:**
- Complete 5-step installation wizard
- InstallationService with comprehensive status management
- Artisan commands for installation management
- Advanced middleware system
- Facade support for easy access

**Breaking Changes:**
- None (initial release)

**Required Actions:**
- None

---

## Future Version Upgrades

### Upgrading to v1.1.0 (Planned)

**Expected Features:**
- Multi-language installer interface
- Enhanced UI components
- Additional configuration options

**Potential Breaking Changes:**
- Configuration structure may change
- Some view templates may be updated

**Required Actions:**
- Review and update custom configurations
- Test custom view templates
- Update language files if using custom translations

### Upgrading to v2.0.0 (Planned)

**Expected Features:**
- Major architecture improvements
- New installation methods
- Enhanced developer experience

**Potential Breaking Changes:**
- Service provider interface changes
- Configuration file structure changes
- Middleware signature updates

**Required Actions:**
- Review all custom implementations
- Update configuration files
- Test all custom middleware integrations

---

## Configuration Migration

### Backing Up Custom Configuration

Before upgrading, always backup your custom configuration:

```bash
# Backup your config file
cp config/one-click-installer.php config/one-click-installer.php.backup

# Backup custom views (if published)
cp -r resources/views/vendor/installer resources/views/vendor/installer.backup
```

### Merging Configuration Changes

After upgrading, compare your backup with the new configuration:

```bash
# Compare configurations
diff config/one-click-installer.php.backup config/one-click-installer.php

# Manually merge your custom settings with new options
```

---

## Common Upgrade Issues

### Issue: Configuration Not Found

**Symptoms:**
- Error: "Configuration file not found"
- Installer not loading

**Solution:**
```bash
# Republish configuration
php artisan vendor:publish --tag=installer-config --force

# Clear config cache
php artisan config:clear
```

### Issue: Views Not Loading

**Symptoms:**
- Blank installer pages
- View not found errors

**Solution:**
```bash
# Republish views
php artisan vendor:publish --tag=installer-views --force

# Clear view cache
php artisan view:clear
```

### Issue: Middleware Not Working

**Symptoms:**
- Installer accessible when already installed
- No redirect to installer

**Solution:**
```bash
# Clear route cache
php artisan route:clear

# Verify middleware registration
php artisan route:list --path=install
```

### Issue: Service Not Registered

**Symptoms:**
- InstallationService not found
- Facade not working

**Solution:**
```bash
# Clear config and cache
php artisan config:clear
php artisan cache:clear

# Regenerate autoloader
composer dump-autoload
```

---

## Testing After Upgrade

### 1. Installation Status Check

```bash
# Verify installation service is working
php artisan installer:status
```

### 2. Route Verification

```bash
# Check installer routes are registered
php artisan route:list --path=install
```

### 3. Functional Testing

1. **Reset Installation** (in development):
   ```bash
   php artisan installer:status --reset
   ```

2. **Access Installer**: Visit `/install` and complete the wizard

3. **Verify Protection**: Try accessing `/install` after installation completes

4. **Test Commands**:
   ```bash
   php artisan installer:status
   php artisan installer:status --mark-installed
   ```

### 4. Integration Testing

Test with your application:

1. **Middleware Integration**: Verify routes are properly protected
2. **Custom Configuration**: Ensure custom settings work correctly
3. **User Model**: Verify admin creation works with your User model
4. **Database**: Test with your specific database configuration

---

## Rollback Instructions

If you encounter issues after upgrading, you can rollback:

### Step 1: Downgrade Package

```bash
# Rollback to specific version
composer require clevpro/laravel-one-click-installer:^1.0.0
```

### Step 2: Restore Backups

```bash
# Restore configuration
cp config/one-click-installer.php.backup config/one-click-installer.php

# Restore views (if applicable)
rm -rf resources/views/vendor/installer
cp -r resources/views/vendor/installer.backup resources/views/vendor/installer
```

### Step 3: Clear Caches

```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

---

## Getting Help During Upgrades

If you encounter issues during the upgrade process:

1. **Check the Changelog**: Review [CHANGELOG.md](CHANGELOG.md) for detailed changes
2. **Review Documentation**: Check the updated [README.md](README.md)
3. **Search Issues**: Look for similar issues on [GitHub](https://github.com/clevpro/laravel-one-click-installer/issues)
4. **Create Issue**: If you can't find a solution, create a new issue with:
   - Laravel version
   - PHP version
   - Package version (before and after)
   - Error messages
   - Steps to reproduce

5. **Contact Support**: For enterprise customers, contact support@clevpro.com

---

## Best Practices

### 1. Development Environment Testing

Always test upgrades in a development environment first:

```bash
# Create a test branch
git checkout -b upgrade-installer-v2

# Test upgrade process
composer update clevpro/laravel-one-click-installer

# Verify functionality
php artisan installer:status
```

### 2. Staging Environment Validation

Test in staging with production-like data before upgrading production.

### 3. Backup Strategy

- Backup database before upgrading
- Backup custom configuration files
- Backup custom view templates
- Document custom implementations

### 4. Version Pinning

Consider pinning to specific versions in production:

```json
{
    "require": {
        "clevpro/laravel-one-click-installer": "1.0.*"
    }
}
```

### 5. Update Schedule

- Monitor releases on GitHub
- Subscribe to release notifications
- Plan upgrade windows during low-traffic periods
- Test thoroughly in development first

---

This upgrade guide will be updated with each new release to provide specific instructions for version transitions.