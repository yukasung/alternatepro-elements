# Phase 11 - Elementor Widgets

Source: [Implementation Plan](../planning/implementation-plan.md)

## Goal

Build the v1.0 Elementor widget set on top of the registration, asset, dynamic data, and security foundations.

## Scope

- Widget module completion.
- Base widget reuse.
- Required v1.0 widgets that are low-risk and architecture-aligned.
- Conditional widgets that ship only if validation passes.
- Widget setting sanitization.
- Context-specific output escaping.
- Responsive controls and frontend behavior.
- Accessibility validation for shipped widgets.
- Editor preview and frontend consistency.
- Asset loading only where widgets are used.

## Deliverables

- Site Logo widget.
- Site Title widget.
- Search Form widget.
- Breadcrumbs widget.
- Image Box widget.
- Icon Box widget.
- Call To Action widget.
- Hero Section widget.
- Team Member widget.
- Nav Menu widget if keyboard, mobile, and submenu accessibility validation passes.
- Posts widget if query limits, escaping, pagination behavior, and query reset behavior pass validation.
- Testimonial Carousel only if explicitly promoted after validation.
- Widget enable and disable setting support.
- Widget frontend assets.
- Widget editor preview behavior.

## Dependencies

- [Phase 2 - Elementor Integration](phase-02-elementor-integration.md)
- [Phase 5 - Dynamic Data Engine](phase-05-dynamic-data-engine.md)
- [Phase 6 - Header/Footer Builder](phase-06-header-footer-builder.md)
- [Phase 7 - Single Post Builder](phase-07-single-post-builder.md)
- [Phase 8 - Archive Builder](phase-08-archive-builder.md)
- [Phase 9 - Search Builder](phase-09-search-builder.md)
- [Phase 10 - 404 Builder](phase-10-404-builder.md)
- Frontend asset strategy.
- [Architecture](../planning/architecture.md)

## Acceptance Criteria

- Site Logo widget renders site or custom logo safely.
- Site Title widget renders with configurable tag and optional home link.
- Search Form widget is accessible and works on frontend.
- Breadcrumbs widget uses semantic breadcrumb markup.
- Image Box widget renders image, title, description, and link safely.
- Icon Box widget renders decorative or labeled icons safely.
- Call To Action widget renders content and links safely.
- Hero Section widget supports responsive layout and dynamic values.
- Team Member widget renders image, bio, and social links safely.
- Nav Menu ships only if keyboard, mobile, and submenu behavior pass accessibility validation.
- Posts widget ships only if query limits, escaping, pagination behavior, and reset behavior pass validation.
- Testimonial Carousel is not required for v1.0 unless explicitly promoted after validation.
- Every widget respects enable/disable settings.
- Every widget sanitizes settings and escapes output by context.
- Widget assets load only where needed.
- Editor preview and frontend output are acceptably consistent.

## Excluded Scope

- Elementor Pro-only widget APIs.
- WooCommerce widgets.
- Popup Builder widgets.
- Form builder widgets.
- Login, registration, or account widgets.
- Testimonial Carousel if accessibility and interaction validation do not pass.
- Nav Menu if keyboard, mobile, or submenu accessibility validation does not pass.
- Posts widget if query safety, pagination, or reset behavior does not pass.
- New Theme Builder features beyond widget support.

## Definition of Done

- Phase 11 deliverables are implemented according to [Architecture](../planning/architecture.md).
- Acceptance criteria are verified for each shipped widget.
- Conditional widgets are either fully validated or explicitly deferred in release notes.
- Responsive behavior is checked on desktop, tablet, and mobile.
- Accessibility checks are completed for every shipped widget.
- Code review report is created through [Code Review Agent](../agents/review.md).
- Testing report is created through [Testing Agent](../agents/test.md).
- Security review report is created through [Security Audit Agent](../agents/security.md).
- [Project Status](../status.md) is updated.
- [CHANGELOG.md](../../CHANGELOG.md) is updated.
- [Widget Progress](../releases/widget-progress.md) is updated.
- Phase release summary is created or updated in `docs/releases/`.
- No Phase 12 work starts until Phase 11 is marked complete.
