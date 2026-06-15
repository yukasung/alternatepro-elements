# Phase 9 - Search Builder

Source: [Implementation Plan](../planning/implementation-plan.md)

## Goal

Render assigned Search templates for search results pages.

## Scope

- Search template resolution.
- Search Results condition support.
- Frontend rendering for WordPress search result requests.
- Safe search query output.
- Empty search and no-results handling.
- Search Form widget support inside Search templates.
- Posts output support for current search query when used in search context.
- Prevention of Search template rendering on non-search routes.

## Deliverables

- Template resolver support for Search templates.
- Template renderer behavior for search results pages.
- Search Results condition integration.
- Safe handling of search query text.
- Empty search and no-results fallback behavior.
- Search Form widget compatibility inside Search templates.
- Posts output compatibility with the current search query.
- Safeguards that prevent Search template rendering on archives, posts, pages, and 404 requests.

## Dependencies

- [Phase 6 - Header/Footer Builder](phase-06-header-footer-builder.md)
- [Phase 4 - Conditions Engine](phase-04-conditions-engine.md)
- [Phase 5 - Dynamic Data Engine](phase-05-dynamic-data-engine.md)
- Optional Posts widget support for search result output.
- [Architecture](../planning/architecture.md)

## Acceptance Criteria

- Search templates render only on search results requests.
- Search Results condition matches correctly.
- Search query text is escaped before output.
- Empty search and no-results states are safe.
- Search Form widget works inside Search templates.
- Posts output respects the current search query when used in search context.
- Template does not render on archives, posts, pages, or 404 requests.

## Excluded Scope

- Custom search ranking.
- Search analytics.
- AJAX search.
- Search filtering UI.
- Archive, single, page, or 404 rendering.
- WooCommerce product search templates.
- New query system beyond the safe current search query behavior.

## Definition of Done

- Phase 9 deliverables are implemented according to [Architecture](../planning/architecture.md).
- Acceptance criteria are verified on search result routes.
- Search query output is reviewed for escaping and no-results behavior.
- Code review report is created through [Code Review Agent](../agents/review.md).
- Testing report is created through [Testing Agent](../agents/test.md).
- Security review report is created through [Security Audit Agent](../agents/security.md).
- [Project Status](../status.md) is updated.
- [CHANGELOG.md](../../CHANGELOG.md) is updated.
- Phase release summary is created or updated in `docs/releases/`.
- No Phase 10 work starts until Phase 9 is marked complete.
