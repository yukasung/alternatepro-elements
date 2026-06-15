# Phase 5 - Dynamic Data Engine

Source: [Implementation Plan](../planning/implementation-plan.md)

## Goal

Create the plugin-owned Dynamic Data Resolver system used by widgets and Theme Builder contexts. This phase does not require Elementor Pro-style Dynamic Tags UI.

## Scope

- Dynamic Data module registration.
- Resolver registry.
- Resolver context detection.
- Core v1.0 dynamic data resolvers.
- Frontend request context support.
- Elementor preview fallback support.
- Widget helper integration.
- Theme Builder preview integration.
- Safe fallback behavior for missing context.

## Deliverables

- Dynamic Data module.
- Resolver registry for all v1.0 dynamic data keys.
- Resolver context object for frontend, queried object, current post, and Elementor preview contexts.
- Site Title resolver.
- Site Logo resolver.
- Page Title resolver.
- Post Title resolver.
- Post Excerpt resolver.
- Featured Image resolver.
- Post Date resolver.
- Author Name resolver.
- Categories resolver.
- Breadcrumbs resolver returning structured data.
- Base widget integration point for resolver usage.
- Theme Builder preview integration point.

## Dependencies

- [Phase 2 - Elementor Integration](phase-02-elementor-integration.md)
- [Phase 3 - Theme Builder Foundation](phase-03-theme-builder-foundation.md)
- [Phase 4 - Conditions Engine](phase-04-conditions-engine.md)
- Elementor preview context.
- Template context.
- Condition-aware request context.
- [Architecture](../planning/architecture.md)

## Acceptance Criteria

- Resolver registry supports all v1.0 dynamic data keys.
- Resolver context can detect frontend request, current post, queried object, and Elementor preview fallback.
- Site Title resolver works.
- Site Logo resolver works with safe fallback.
- Page Title resolver works across pages, posts, archives, search, and 404 fallbacks.
- Post Title, Excerpt, Featured Image, Date, Author Name, and Categories resolve safely.
- Breadcrumbs resolver returns structured data suitable for safe rendering.
- Resolvers return data; widgets remain responsible for context-specific escaping.
- Missing context returns safe empty values or fallbacks.
- No Elementor Pro dynamic tag classes are required.

## Excluded Scope

- Elementor Pro-style Dynamic Tags UI.
- Elementor Pro dynamic tag classes.
- User-created dynamic tags.
- WooCommerce dynamic data.
- Advanced custom fields or third-party field integrations.
- Template rendering.
- Widget implementation beyond helper integration points.

## Definition of Done

- Phase 5 deliverables are implemented according to [Architecture](../planning/architecture.md).
- Acceptance criteria are verified across supported request contexts.
- Resolver behavior is covered by unit or focused integration test recommendations.
- Code review report is created through [Code Review Agent](../agents/review.md).
- Testing report is created through [Testing Agent](../agents/test.md).
- Security review report is created through [Security Audit Agent](../agents/security.md).
- [Project Status](../status.md) is updated.
- [CHANGELOG.md](../../CHANGELOG.md) is updated.
- Phase release summary is created or updated in `docs/releases/`.
- No Phase 6 work starts until Phase 5 is marked complete.
