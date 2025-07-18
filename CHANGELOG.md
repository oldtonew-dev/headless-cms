## Audit & Modernisation (par Old to New - Elie Bendier)

# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- Project audit and modernization plan
- Documentation for refactoring approach
- CHANGELOG.md file for tracking changes

### Changed
- Enhanced README with audit section

### Planned
- [ ] Refactor PHP code to OOP architecture
  - [ ] Create refactor/oop-backend branch
  - [ ] Implement improved Page class
  - [ ] Add proper error handling and logging
  - [ ] Create Router class for better separation of concerns
  - [ ] Implement dependency injection pattern
- [ ] Add unit tests with PHPUnit
  - [ ] Test Page class functionality
  - [ ] Test routing logic
  - [ ] Test error handling
  - [ ] Test page settings parsing
- [ ] Implement Composer for dependency management
  - [ ] Create composer.json
  - [ ] Add PHPUnit as dev dependency
  - [ ] Add code quality tools (PHP-CS-Fixer, PHPStan)
- [ ] Enhance security
  - [ ] Input validation and sanitization
  - [ ] Proper error handling (no sensitive data exposure)
  - [ ] Security headers implementation
  - [ ] CSRF protection if needed
- [ ] Modularize JavaScript code
  - [ ] Split client-side-router.js into modules
  - [ ] Add unit tests for JS (Jest/Vitest)
  - [ ] Implement proper error handling
  - [ ] Add TypeScript support
- [ ] Add code quality tools
  - [ ] PHP linting and formatting (PHP-CS-Fixer)
  - [ ] JavaScript linting (ESLint)
  - [ ] Pre-commit hooks
  - [ ] CI/CD pipeline

### Known Limitations
- Procedural PHP code (difficult to maintain and extend)
- No automated testing
- Limited input validation
- No dependency management
- No code quality tools
- JavaScript not modularized
- No security headers or advanced security features