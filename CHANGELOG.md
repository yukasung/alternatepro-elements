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
* Detailed phase documents generated from the implementation plan.
* Dashboard documentation structure for HTML project visualization.
* Phase 1 foundation implementation for bootstrap, service container, activation, upgrades, capabilities, settings, admin menu, diagnostics, and module toggles.
* Phase 1 foundation testing report with static and CLI validation results.
* Phase 1 browser-based WordPress admin validation results.
* Phase 1 foundation security review report with PASS verdict.
* Composer lockfile and installed development dependencies for project-local PHPCS tooling.
* UAE-style Header/Footer display rule builder with Display On and Do Not Display On rule rows.
* UAE-style Header/Footer Display On combobox options generated from public post types and taxonomies.
* Shared Header/Footer `RuleOptions` helper for rule labels, sanitization, and legacy condition compatibility.
* UAE-style chip/token picker with admin AJAX target search for Header/Footer specific display rules.
* UAE-style grouped target search results with minimum-character feedback for Header/Footer specific display rules.

### Changed

* Replaced Header/Footer condition checkbox cards with UAE-style add/remove rule rows.
* Updated Header/Footer condition evaluation to support Blog Page, All Categories, Specific Category, UAE-style specific target tokens, and exclusion rules.
* Updated Header/Footer condition evaluation to support UAE-style All Singulars, Date Archive, Author Archive, post type archive, and taxonomy archive rules.
* Changed empty Header/Footer display rule sets to not render templates automatically.
* Moved Header/Footer template Display Conditions into a UAE-style `Display On` row below Type of Template.
* Forced the Header/Footer template settings metabox into the main editor column when user preferences previously placed it in the sidebar.
* Bumped the plugin schema version to remove legacy Header/Footer template language metadata.
* Bumped the plugin schema version to backfill legacy active Header/Footer templates with empty display conditions.
* Refined the Header/Footer specific target picker layout to match UAE-style selected chips, search focus state, and grouped dropdown results.
* Removed the inner border from the Header/Footer Display On target search input to better match the UAE plugin field styling.
* Removed link-style underlines from Header/Footer Display On target search results and added UAE-style result indentation.
* Clarified v1.0 changelog scope by separating required, conditional, and deferred features.
* Updated EditorConfig PHP, JavaScript, and CSS indentation to align with WordPress Coding Standards.
* Standardized PHPCS configuration naming on `phpcs.xml`.
* Updated PHPCS configuration to support PSR-4 class filenames while retaining WordPress Coding Standards checks.
* Updated governance references to include `docs/context.md`.
* Enhanced Testing Agent workflow with functional, unit, integration, and regression testing strategy.
* Expanded Codex command quick workflow with start, implementation, review, testing, security, refactor, completion, next task, and status update steps.
* Updated plugin metadata and runtime checks to require PHP 8.1+.

### Fixed

* Hid Theme Builder admin menu, tab, and links when the Header/Footer Builder module is disabled.
* Fixed Header/Footer template metabox interactivity by using the classic editor for the `apro_template` post type while keeping Elementor editing available.
* Added a schema migration that fills active Header/Footer templates with empty display conditions using an explicit `Entire Site` include rule.
* Replaced placeholder-only Header/Footer specific target behavior with real AJAX search/autocomplete, selected target chips, and token insertion.
* Fixed Header/Footer Display On target search so Posts and Pages are searched first and Pages are not dropped by the global result limit.
* Fixed Header/Footer Display On target search to include draft Posts and Pages for users with edit permissions.
* Resolved remaining PHPCS findings after aligning PHPCS configuration with PSR-4 class filenames.

### Removed

* Removed the Header/Footer template Language setting from the template settings UI, admin columns, matching logic, page override labels, registered meta, and local database metadata.
* Removed Header/Footer template User Roles targeting from the template settings UI, condition matching, helper code, JavaScript, CSS, and legacy database metadata.

### Security

* Added allowlist-based settings sanitization, Settings API persistence, capability checks, and read-only diagnostics output for Phase 1 foundation.
* Completed Phase 1 foundation security review with no required fixes.
* Completed Header/Footer User Roles removal security review with no required fixes.

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
