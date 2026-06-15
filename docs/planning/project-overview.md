# AlternatePro Elements Project Overview

## Purpose

AlternatePro Elements is a WordPress plugin for Elementor Free that brings commonly requested Elementor Pro-like building blocks into a standalone, maintainable plugin.

The v1.0 goal is to provide a Core Theme Builder foundation for Elementor Free:

- Essential Elementor widgets for site identity, marketing content, and theme context.
- Header/Footer Builder.
- Core Theme Builder for WordPress templates.
- Conditions System for assigning templates.
- Dynamic Data Resolvers for site, page, post, author, taxonomy, image, and breadcrumb values.
- Basic admin settings for module control, read-only diagnostics, and safe configuration.

## Product Positioning

The plugin should feel native to WordPress and Elementor Free. It should not try to replace Elementor Pro feature-for-feature in v1.0. Instead, it should deliver a focused set of high-value layout and theme-building capabilities without WooCommerce support.

## MVP v1.0 Scope

[Architecture](architecture.md) is the source of truth where scope differs from earlier planning notes.

### Required Elementor Widgets

- Site Logo
- Site Title
- Search Form
- Breadcrumbs
- Hero Section
- Call To Action
- Image Box
- Icon Box
- Team Member

### Conditional v1.0 Widgets

These may ship in v1.0 only if validation confirms accessibility, performance, and Elementor compatibility.

- Nav Menu
- Posts

### v1.5 Widget Candidate

- Testimonial Carousel

### Core Theme Builder

- Header
- Footer
- Single Post
- Archive
- Search
- 404

### Conditions

- Entire Site
- Front Page
- Blog Page
- All Posts
- Specific Post
- All Pages
- Specific Page
- All Categories
- Specific Category
- Search Results
- 404 Page

### Dynamic Data Resolvers

- Site Title
- Site Logo
- Page Title
- Post Title
- Post Excerpt
- Featured Image
- Post Date
- Author Name
- Categories
- Breadcrumbs

## Non-Goals For v1.0

- WooCommerce widgets or WooCommerce template support.
- Popup builder.
- Form builder.
- Role manager beyond basic permissions checks.
- Mega menu builder.
- Loop builder parity with Elementor Pro.
- Elementor Pro Dynamic Tags parity.
- Settings import, full reset tools, and template-kit import/export.
- Testimonial Carousel unless explicitly promoted after validation.
- Theme-specific hard dependencies.
- Cloud template library.

## Target Users

- Site builders using Elementor Free.
- WordPress implementers who need header, footer, and basic template control without Elementor Pro.
- Theme authors who want predictable plugin-level Elementor integrations.
- Small agencies building brochure sites, blogs, and content-driven websites.

## Product Principles

- Elementor-native experience wherever possible.
- WordPress-first data handling, permissions, escaping, and compatibility.
- Modular architecture so widgets, theme builder, conditions, and dynamic data can evolve independently.
- Secure defaults, clear fallbacks, and graceful degradation.
- Responsive controls for desktop, tablet, and mobile.
- WCAG AA alignment where possible, especially for navigation, search, carousel, and breadcrumbs.

## Existing Project Notes

The current plugin folder already includes:

- Main plugin bootstrap: `alternatepro-elements.php`
- Core classes under `includes/`
- A header/footer module under `includes/modules/header-footer/`
- Header/footer frontend and admin assets under `assets/`

Planning should build on this structure rather than discard it. The current PHP requirement in the plugin header is PHP 7.4, while the requested product requirement is PHP 8.1+. Implementation should align the plugin header, runtime checks, and documentation before release.

## Assumptions

- The plugin will target the latest stable WordPress and Elementor Free versions at the time implementation begins.
- Elementor Pro may be installed on some sites, so the plugin must avoid class, hook, and feature conflicts.
- Users may run a wide variety of themes, including block themes and classic themes.
- v1.0 should be shippable without WooCommerce support.
- The plugin can define its own template custom post type and metadata.
- Admin workflows should be understandable without requiring custom onboarding screens in v1.0.

## Key Risks

- Elementor Free may not expose every Pro-like dynamic tag surface expected by users, so v1.0 uses plugin-owned Dynamic Data Resolvers.
- Core Theme Builder rendering can conflict with themes that heavily customize template loading.
- Navigation widgets carry accessibility and responsive behavior risks.
- Query-heavy widgets such as Posts can cause performance issues without caching and query limits.
- Template conditions can become confusing if precedence and include/exclude rules are not explicit.
- Elementor internal APIs can change, so integrations should rely on documented APIs where possible.

## Implementation Phases

### Phase 0: Discovery And Technical Validation

- Verify current WordPress and Elementor Free APIs.
- Confirm Elementor widget, editor, asset, and preview integration points.
- Test dynamic data surfaces available in Elementor Free.
- Audit the existing header/footer module and decide what can be reused.

### Phase 1: Foundation

- Align plugin requirements with PHP 8.1+.
- Establish module interfaces, service registration, autoloading, admin capabilities, and settings storage.
- Add development standards, linting, and baseline tests.

### Phase 2: Core Theme Builder

- Generalize the existing header/footer builder into a broader theme builder module.
- Add template types for header, footer, single post, archive, search, and 404.
- Implement template resolution and rendering.

### Phase 3: Conditions System

- Build a reusable condition model.
- Add assignment UI and conflict resolution.
- Support all MVP conditions.

### Phase 4: Widgets And Dynamic Data

- Build foundational widgets first: Site Logo, Site Title, Search Form, and Breadcrumbs.
- Add content and marketing widgets.
- Add Nav Menu and Posts only after accessibility, query, and performance rules are finalized.
- Implement Dynamic Data Resolvers and widget integrations.

### Phase 5: Admin Settings

- Add settings page, module toggles, widget controls, and read-only diagnostics.
- Add clear notices for missing Elementor, unsupported PHP, and disabled modules.

### Phase 6: Quality, Compatibility, And Release

- Run manual and automated testing across supported environments.
- Complete accessibility, security, performance, and compatibility checks.
- Prepare release notes, documentation, and upgrade guidance.
