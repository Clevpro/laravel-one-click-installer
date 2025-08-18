# Contributing to Laravel One-Click Installer

Thank you for considering contributing to the Laravel One-Click Installer! We welcome contributions from the community and are excited to see what improvements you can bring to the project.

## 🤝 Code of Conduct

This project and everyone participating in it is governed by our Code of Conduct. By participating, you are expected to uphold this code. Please report unacceptable behavior to support@clevpro.com.

### Our Standards

- **Be Respectful**: Treat all contributors with respect and kindness
- **Be Inclusive**: Welcome newcomers and help them feel at home
- **Be Collaborative**: Work together and help each other succeed
- **Be Professional**: Maintain a professional tone in all communications

## 🚀 How to Contribute

### 🐛 Reporting Bugs

Before creating bug reports, please check the existing issues to avoid duplicates. When you create a bug report, include as many details as possible:

#### Bug Report Template

```markdown
**Describe the bug**
A clear and concise description of what the bug is.

**To Reproduce**
Steps to reproduce the behavior:
1. Go to '...'
2. Click on '....'
3. Scroll down to '....'
4. See error

**Expected behavior**
A clear and concise description of what you expected to happen.

**Environment:**
- Laravel version: [e.g. 11.0]
- PHP version: [e.g. 8.2]
- Package version: [e.g. 1.0.0]
- Database: [e.g. MySQL 8.0]
- Browser (if applicable): [e.g. Chrome 91]

**Additional context**
Add any other context about the problem here.
```

### 💡 Suggesting Features

We love feature suggestions! Please check existing feature requests first, then create a new issue with:

#### Feature Request Template

```markdown
**Is your feature request related to a problem?**
A clear and concise description of what the problem is.

**Describe the solution you'd like**
A clear and concise description of what you want to happen.

**Describe alternatives you've considered**
A clear and concise description of any alternative solutions.

**Additional context**
Add any other context or screenshots about the feature request.
```

### 🔄 Pull Requests

#### Before You Start

1. **Check existing PRs**: Make sure no one else is working on the same thing
2. **Create an issue**: For significant changes, create an issue first to discuss
3. **Fork the repository**: Create your own fork to work on

#### Development Setup

1. **Fork and clone the repository**:
   ```bash
   git clone https://github.com/your-username/laravel-one-click-installer.git
   cd laravel-one-click-installer
   ```

2. **Install dependencies**:
   ```bash
   composer install
   ```

3. **Create a test Laravel application**:
   ```bash
   composer create-project laravel/laravel test-app
   cd test-app
   ```

4. **Add your local package**:
   ```bash
   composer config repositories.local path ../
   composer require clevpro/laravel-one-click-installer:@dev
   ```

5. **Create a feature branch**:
   ```bash
   git checkout -b feature/your-feature-name
   ```

#### Making Changes

1. **Follow PSR-12 coding standards**
2. **Write tests for new functionality**
3. **Update documentation when necessary**
4. **Keep commits atomic and well-described**

#### Testing Your Changes

```bash
# Run code style checks
composer cs-check

# Fix code style issues
composer cs-fix

# Run static analysis
composer analyse

# Run tests
composer test

# Test with coverage
composer test-coverage
```

#### Manual Testing

1. **Test the installer wizard**:
   - Reset installation: `php artisan installer:status --reset`
   - Visit `/install` and complete all steps
   - Verify all functionality works

2. **Test the InstallationService**:
   ```bash
   php artisan installer:status
   php artisan installer:status --mark-installed
   php artisan installer:status --reset
   ```

3. **Test middleware**:
   - Verify redirect to installer when not installed
   - Verify installer protection when already installed

#### Submitting Your PR

1. **Push your changes**:
   ```bash
   git push origin feature/your-feature-name
   ```

2. **Create a Pull Request** with:
   - Clear title describing the change
   - Detailed description of what changed and why
   - Reference to any related issues
   - Screenshots (if UI changes)
   - Checklist of what was tested

#### PR Template

```markdown
## Description
Brief description of the changes.

## Type of Change
- [ ] Bug fix (non-breaking change which fixes an issue)
- [ ] New feature (non-breaking change which adds functionality)
- [ ] Breaking change (fix or feature that would cause existing functionality to not work as expected)
- [ ] Documentation update

## Related Issues
Fixes #(issue number)

## How Has This Been Tested?
- [ ] Unit tests pass
- [ ] Manual testing completed
- [ ] Installation wizard tested
- [ ] Service commands tested

## Screenshots (if applicable)
Add screenshots to help explain your changes.

## Checklist
- [ ] My code follows the project's coding standards
- [ ] I have performed a self-review of my code
- [ ] I have commented my code, particularly in hard-to-understand areas
- [ ] I have made corresponding changes to the documentation
- [ ] My changes generate no new warnings
- [ ] I have added tests that prove my fix is effective or that my feature works
- [ ] New and existing unit tests pass locally with my changes
```

## 📋 Development Guidelines

### Coding Standards

- **PSR-12**: Follow PSR-12 coding style
- **Laravel conventions**: Use Laravel naming conventions
- **Type hints**: Use strict type hints where possible
- **Documentation**: Use comprehensive DocBlocks

### Code Style

```php
<?php

namespace Clevpro\LaravelOneClickInstaller\Services;

use Illuminate\Support\Facades\Config;

/**
 * Service for managing installation status
 */
class InstallationService
{
    /**
     * Check if the application is installed
     */
    public function isInstalled(): bool
    {
        // Implementation
    }
}
```

### Commit Messages

Use conventional commit format:

```
type(scope): description

[optional body]

[optional footer]
```

Examples:
- `feat(service): add installation reset functionality`
- `fix(middleware): resolve redirect loop issue`
- `docs(readme): update installation instructions`
- `test(service): add unit tests for installation service`

### Branch Naming

- `feature/feature-name` - New features
- `fix/bug-description` - Bug fixes
- `docs/documentation-update` - Documentation changes
- `refactor/component-name` - Code refactoring

## 🧪 Testing Guidelines

### Writing Tests

1. **Unit Tests**: Test individual methods and classes
2. **Feature Tests**: Test complete workflows
3. **Integration Tests**: Test package integration with Laravel

### Test Structure

```php
<?php

namespace Clevpro\LaravelOneClickInstaller\Tests\Feature;

use Clevpro\LaravelOneClickInstaller\Services\InstallationService;
use Orchestra\Testbench\TestCase;

class InstallationServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Setup test environment
    }

    /** @test */
    public function it_detects_when_app_is_not_installed(): void
    {
        $service = new InstallationService();
        
        $this->assertFalse($service->isInstalled());
    }
}
```

### Test Coverage

Aim for high test coverage, especially for:
- Core installation logic
- Security-related functionality
- Error handling scenarios
- Edge cases

## 📚 Documentation

### README Updates

When adding features, update:
- Feature list
- Installation instructions
- Usage examples
- Configuration options

### Code Documentation

- Use clear, descriptive DocBlocks
- Document parameters and return types
- Explain complex logic
- Add usage examples for public methods

### Changelog

Update `CHANGELOG.md` following Keep a Changelog format:

```markdown
## [Unreleased]

### Added
- New awesome feature

### Changed
- Improved existing functionality

### Fixed
- Fixed important bug
```

## 🏷️ Release Process

### Version Numbering

We use [Semantic Versioning](https://semver.org/):
- **MAJOR**: Breaking changes
- **MINOR**: New features (backward compatible)
- **PATCH**: Bug fixes (backward compatible)

### Release Checklist

- [ ] All tests pass
- [ ] Documentation updated
- [ ] Changelog updated
- [ ] Version bumped in relevant files
- [ ] GitHub release created
- [ ] Packagist updated

## 🆘 Getting Help

### Before Asking for Help

1. Check the [README](README.md)
2. Search existing [issues](https://github.com/clevpro/laravel-one-click-installer/issues)
3. Check [discussions](https://github.com/clevpro/laravel-one-click-installer/discussions)

### Ways to Get Help

- **GitHub Issues**: For bugs and feature requests
- **GitHub Discussions**: For questions and community help
- **Email**: support@clevpro.com for enterprise support

### Providing Context

When asking for help, include:
- Laravel version
- PHP version
- Package version
- Relevant code snippets
- Error messages
- Steps to reproduce

## 🎉 Recognition

Contributors will be recognized in:
- GitHub contributors list
- Release notes for significant contributions
- Documentation credits

### Types of Contributions

We appreciate all types of contributions:
- 🐛 Bug reports and fixes
- 💡 Feature suggestions and implementations
- 📖 Documentation improvements
- 🧪 Writing tests
- 🎨 UI/UX improvements
- 🔍 Code reviews
- 💬 Helping others in discussions

## 📞 Contact

- **GitHub**: [Laravel One-Click Installer](https://github.com/clevpro/laravel-one-click-installer)
- **Email**: support@clevpro.com
- **Website**: [Clevpro](https://clevpro.com)

Thank you for contributing to Laravel One-Click Installer! 🚀