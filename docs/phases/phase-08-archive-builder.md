# Phase 8 - Archive Builder

Source: [Implementation Plan](../planning/implementation-plan.md)

## Goal

Render assigned Archive templates for supported archive contexts.

## Scope

- Archive template resolution.
- Category archive rendering.
- All Categories condition support.
- Specific Category condition support for category archive pages only.
- Archive-aware dynamic data.
- Archive page title fallback.
- Posts widget or default content strategy for archive results.
- Pagination and query stability if archive output includes pagination.

## Deliverables

- Template resolver support for Archive templates.
- Template renderer behavior for supported archive contexts.
- All Categories condition behavior.
- Specific Category condition behavior limited to category archive pages.
- Dynamic data support for archive titles and breadcrumbs.
- Archive result output strategy.
- Query preservation and reset safeguards.
- Prevention of archive template rendering on singular posts or pages.

## Dependencies

- [Phase 6 - Header/Footer Builder](phase-06-header-footer-builder.md)
- [Phase 4 - Conditions Engine](phase-04-conditions-engine.md)
- [Phase 5 - Dynamic Data Engine](phase-05-dynamic-data-engine.md)
- Archive-aware dynamic data.
- [Architecture](../planning/architecture.md)

## Acceptance Criteria

- Archive templates render for supported post archive contexts.
- Category archive templates render for All Categories.
- Specific Category matches category archive pages only.
- Specific Category does not imply single posts in that category.
- Tag-specific, author-specific, and date-specific assignments are not v1.0 requirements.
- Archive page title resolves safely.
- Posts widget or default content strategy can display archive results.
- Pagination and query behavior are stable if included.
- No unexpected template renders on singular posts or pages.

## Excluded Scope

- Tag-specific template assignments.
- Author-specific template assignments.
- Date-specific template assignments.
- Custom taxonomy archive assignments.
- WooCommerce product archives.
- Search results templates.
- Single post or page templates.

## Definition of Done

- Phase 8 deliverables are implemented according to [Architecture](../planning/architecture.md).
- Acceptance criteria are verified on supported archive contexts.
- Query and pagination behavior are reviewed.
- Code review report is created through [Code Review Agent](../agents/review.md).
- Testing report is created through [Testing Agent](../agents/test.md).
- Security review report is created through [Security Audit Agent](../agents/security.md).
- [Project Status](../status.md) is updated.
- [CHANGELOG.md](../../CHANGELOG.md) is updated.
- Phase release summary is created or updated in `docs/releases/`.
- No Phase 9 work starts until Phase 8 is marked complete.
