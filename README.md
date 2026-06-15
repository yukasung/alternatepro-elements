# AlternatePro Elements

AlternatePro Elements is a planned WordPress plugin for Elementor Free that adds Elementor Pro-like building blocks, Core Theme Builder capabilities, display conditions, dynamic data, and admin settings while staying compatible with Elementor Free public APIs.

## Overview

AlternatePro Elements is designed as an Elementor Free extension plugin for building richer WordPress sites without requiring Elementor Pro for the v1.0 feature set.

The project plans to provide:

- Elementor Pro-like widgets for site identity, marketing content, navigation, posts, and theme context.
- A Core Theme Builder for headers, footers, single posts, archives, search results, and 404 pages.
- A Conditions System for assigning templates to specific WordPress routes.
- Dynamic Data Resolvers for site, page, post, author, taxonomy, image, and breadcrumb values.
- Admin Settings for modules, widgets, Theme Builder links, and read-only diagnostics.

## Features

### Widgets

Planned widget family:

- Site Logo
- Site Title
- Nav Menu
- Search Form
- Hero Section
- Call To Action
- Image Box
- Icon Box
- Team Member
- Testimonial Carousel
- Posts
- Breadcrumbs

Scope note: v1.0 required widgets, conditional widgets, and v1.5 candidates are defined in [Architecture](docs/planning/architecture.md) and [Widgets List](docs/planning/widgets-list.md).

### Theme Builder

- Header
- Footer
- Single Post
- Archive
- Search
- 404

### Conditions System

The Conditions System determines where Theme Builder templates render. It supports broad and specific assignments such as Entire Site, Front Page, Blog Page, all posts, specific posts, all pages, specific pages, categories, search results, and 404 pages.

### Dynamic Tags

v1.0 uses plugin-owned Dynamic Data Resolvers rather than Elementor Pro internals. Dynamic values include site title, site logo, page title, post title, excerpt, featured image, post date, author name, categories, and breadcrumbs.

## Project Structure

```text
alternatepro-elements/
  assets/       Plugin CSS and JavaScript assets.
  includes/     Plugin PHP classes and modules.
  languages/    Translation files.
  docs/         Project documentation, planning, reviews, testing, and release notes.
```

## Documentation

- [Documentation Index](docs/index.md): Main documentation navigation page.
- [Project Status](docs/status.md): Official project status reference and current phase tracker.
- [Development Rules](docs/development-rules.md): Mandatory project constitution and development process.
- [Architecture](docs/planning/architecture.md): Primary technical reference for scope, modules, classes, hooks, flows, security, and testing strategy.
- [Implementation Plan](docs/planning/implementation-plan.md): Primary development reference for phase sequencing, files, classes, dependencies, and acceptance criteria.

## Development Workflow

Before any development work:

1. Read [docs/status.md](docs/status.md).
2. Read [docs/index.md](docs/index.md).
3. Read [docs/development-rules.md](docs/development-rules.md).
4. Follow [docs/planning/architecture.md](docs/planning/architecture.md).
5. Update documentation after completing work.

No coding session should begin without reviewing the governance documents.

## Requirements

- WordPress Latest Stable
- Elementor Free Latest Stable
- PHP 8.1+

Exact supported WordPress and Elementor versions must be validated before release.

## Coding Standards

- WordPress Coding Standards
- Elementor Development Standards
- OOP Architecture
- Security First

Security rules include sanitizing input, escaping output, nonce verification, capability checks, direct access protection, and secure database queries.

## Project Status

Read the current project status in [docs/status.md](docs/status.md).

Do not duplicate detailed status information in this README. `docs/status.md` is the official project status reference.

## Roadmap

### v1.0

- Core plugin foundation.
- Elementor Free compatibility.
- Required v1.0 widgets.
- Conditional Nav Menu and Posts if validation supports release readiness.
- Core Theme Builder for Header, Footer, Single Post, Archive, Search, and 404.
- Conditions System.
- Dynamic Data Resolvers.
- Basic admin settings and read-only diagnostics.

### v1.5

- Elementor Dynamic Tags adapter if public Elementor Free APIs support it.
- Testimonial Carousel.
- Advanced Posts pagination.
- Advanced Nav Menu submenu behavior.
- Improved block theme compatibility.
- Settings export and read-only support bundle export.
- Additional archive conditions if requested.

### v2.0

- WooCommerce template support.
- WooCommerce widgets.
- Custom post type singles and archives.
- Advanced loop builder behavior.
- Template kit import/export.
- Settings import and reset tools.
- Third-party dynamic field integrations.
- Multilingual plugin integrations.
- Mega menu features.
- Popup builder.
- Advanced role and permission controls.

## License

License information will be added before release.

## Contributing

All development must follow [docs/development-rules.md](docs/development-rules.md).

Before contributing, review the current phase in [docs/status.md](docs/status.md), the documentation entry point in [docs/index.md](docs/index.md), and the primary technical reference in [docs/planning/architecture.md](docs/planning/architecture.md).
