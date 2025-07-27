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
- PHPUnit test suite for backend classes
  - PageTest: tests for Page class functionality
  - PageParserTest: tests for content and settings parsing
  - RouterTest: tests for routing logic and error handling
  - ErrorHandlerTest: tests for custom error pages and fallbacks

### Changed
- Enhanced README with audit section and link to AUDIT.md
- Fully migrated backend to OOP architecture (Router, Page, PageParser, ErrorHandler)
- Centralized error handling and page parsing
- Fixed content parsing to normalize whitespace with trim()

### Removed
- Legacy procedural file headless-cms.php (all logic migrated to OOP classes)

### In Progress
- [x] Refactor PHP code to OOP architecture
  - [x] Create refactor/oop-backend branch
  - [x] Implement improved Page class
  - [x] Add proper error handling and logging
  - [x] Create Router class for better separation of concerns
  - [x] Implement dependency injection pattern
- [x] Add unit tests with PHPUnit
  - [x] Test Page class functionality
  - [x] Test routing logic
  - [x] Test error handling
  - [x] Test page settings parsing

### Planned
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