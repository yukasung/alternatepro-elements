# Changelog

All notable changes to this project will be documented in this file.

The format is based on Keep a Changelog.

---

## [Unreleased]

### Added

* Composer configuration for PHP 8.1+, PSR-4 autoloading, and development tooling.
* EditorConfig rules for consistent project formatting across editors.
* PHPCS ruleset for WordPress Coding Standards and PHPCompatibilityWP.
* Pre-development audit report for Phase 1 readiness.
* Pre-development audit fixes confirming Phase 1 readiness.
* Project context document as the primary session startup entry point.
* Code Review Agent workflow for post-implementation reviews.
* Testing Agent workflow for post-review validation.
* Security Audit Agent workflow for post-testing security reviews.
* Done Workflow for task completion review, testing, security, documentation, and summary steps.
* Codex command reference for developer workflow prompts.
* Refactor Agent workflow for controlled maintainability and architecture improvements.

### Changed

* Clarified v1.0 changelog scope by separating required, conditional, and deferred features.
* Updated EditorConfig PHP, JavaScript, and CSS indentation to align with WordPress Coding Standards.
* Standardized PHPCS configuration naming on `phpcs.xml`.
* Updated governance references to include `docs/context.md`.
* Enhanced Testing Agent workflow with functional, unit, integration, and regression testing strategy.

### Planned

* Plugin Foundation
* Elementor Integration
* Theme Builder Foundation
* Conditions Engine
* Dynamic Tags Engine
* Header Builder
* Footer Builder
* Single Post Builder
* Archive Builder
* Search Builder
* 404 Builder
* Elementor Widgets
* QA and Security Review

---

## [1.0.0] - Planned

### Added

#### Required Features (v1.0)

##### Theme Builder

* Header Templates
* Footer Templates
* Single Post Templates
* Archive Templates
* Search Templates
* 404 Templates

##### Conditions System

* Entire Site
* Front Page
* Blog Page
* All Posts
* Specific Post
* All Pages
* Specific Page
* All Categories
* Specific Category
* Search Results
* 404 Page

##### Dynamic Data Resolvers

* Site Title
* Site Logo
* Page Title
* Post Title
* Post Excerpt
* Featured Image
* Post Date
* Author Name
* Categories
* Breadcrumbs

##### Required Widgets

* Site Logo
* Site Title
* Search Form
* Breadcrumbs
* Hero Section
* Call To Action
* Image Box
* Icon Box
* Team Member

#### Conditional Features

##### Conditional Widgets

These may ship in v1.0 only after accessibility, performance, and Elementor compatibility validation.

* Nav Menu
* Posts

#### Deferred Features

##### v1.5 Candidates

* Testimonial Carousel

---

## Changelog Rules

When a phase is completed:

1. Update [Unreleased].
2. Record newly added features.
3. Record changed features.
4. Record fixed issues.
5. Record removed features.
6. Record security improvements.

Use these categories:

### Added

New features, modules, widgets, templates, settings, or documentation milestones.

### Changed

Updates to existing behavior, architecture decisions, workflows, documentation, or supported requirements.

### Fixed

Bug fixes, compatibility corrections, documentation corrections, or resolved validation issues.

### Removed

Removed features, deprecated behavior, unsupported scope, or deleted documentation artifacts.

### Security

Security hardening, vulnerability fixes, capability checks, nonce improvements, sanitization, escaping, or review results.

---

## Documentation Policy

The changelog must be updated whenever:

* A phase is completed
* A widget is completed
* A major feature is added
* A security fix is added
* A breaking change is introduced
