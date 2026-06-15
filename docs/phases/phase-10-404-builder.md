# Phase 10 - 404 Builder

Source: [Implementation Plan](../planning/implementation-plan.md)

## Goal

Render assigned 404 templates for not-found requests.

## Scope

- 404 template resolution.
- 404 Page condition support.
- Frontend rendering for not-found requests.
- Safe 404 page title fallback.
- Safe breadcrumb fallback for 404 requests.
- Search Form widget support inside 404 templates.
- Preservation of HTTP 404 status.
- Prevention of 404 template rendering on non-404 routes.

## Deliverables

- Template resolver support for 404 templates.
- Template renderer behavior for not-found requests.
- 404 Page condition integration.
- Dynamic data fallbacks for 404 page title and breadcrumbs.
- Search Form widget compatibility inside 404 templates.
- HTTP status preservation.
- Safeguards that prevent 404 template rendering on search, archive, post, page, or front page requests.

## Dependencies

- [Phase 6 - Header/Footer Builder](phase-06-header-footer-builder.md)
- [Phase 4 - Conditions Engine](phase-04-conditions-engine.md)
- [Phase 5 - Dynamic Data Engine](phase-05-dynamic-data-engine.md)
- [Architecture](../planning/architecture.md)

## Acceptance Criteria

- 404 template renders only on not-found requests.
- 404 Page condition matches correctly.
- Template does not render on search, archive, post, page, or front page requests.
- Search Form widget can be used safely inside the 404 template.
- Page title and breadcrumbs return safe 404 fallbacks.
- HTTP 404 status remains intact.

## Excluded Scope

- Redirect handling.
- Custom 404 logging or analytics.
- Search result rendering.
- Archive or singular template rendering.
- WooCommerce 404-specific behavior.
- Custom status-code templates beyond 404.

## Definition of Done

- Phase 10 deliverables are implemented according to [Architecture](../planning/architecture.md).
- Acceptance criteria are verified on not-found routes.
- HTTP 404 status preservation is confirmed.
- Code review report is created through [Code Review Agent](../agents/review.md).
- Testing report is created through [Testing Agent](../agents/test.md).
- Security review report is created through [Security Audit Agent](../agents/security.md).
- [Project Status](../status.md) is updated.
- [CHANGELOG.md](../../CHANGELOG.md) is updated.
- Phase release summary is created or updated in `docs/releases/`.
- No Phase 11 work starts until Phase 10 is marked complete.
