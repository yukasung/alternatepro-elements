# Phase 4 - Conditions Engine

Source: [Implementation Plan](../planning/implementation-plan.md)

## Goal

Implement the reusable Conditions Engine that evaluates where templates should render.

## Scope

- Condition module registration.
- v1.0 condition definitions.
- Condition registry.
- Include and exclude condition groups.
- Condition payload sanitization.
- Specificity scoring.
- Priority tie-breaking support.
- Request-context evaluation against WordPress conditional tags.
- Condition summaries for admin display.
- Template resolver integration point.

## Deliverables

- Conditions module.
- Registry for v1.0 condition types.
- Definitions for Entire Site, Front Page, Blog Page, All Posts, Specific Post, All Pages, Specific Page, All Categories, Specific Category, Search Results, and 404 Page.
- Sanitizer for condition payloads.
- Evaluator that can be tested without rendering templates.
- Specificity scoring model.
- Include and exclude condition behavior.
- Admin condition summary support.
- Template resolver integration for later rendering phases.

## Dependencies

- [Phase 3 - Theme Builder Foundation](phase-03-theme-builder-foundation.md)
- Phase 1 capabilities and sanitization patterns.
- WordPress request context and conditional tags.
- [Architecture](../planning/architecture.md)

## Acceptance Criteria

- All v1.0 condition types are registered.
- Include and exclude condition groups are supported in the data model.
- A template with no include conditions does not render.
- Exclusions override inclusions.
- Specificity scores are implemented according to architecture.
- Priority is available for tie-breaking after specificity.
- Specific Category is clearly treated as category archive only.
- Condition payloads are sanitized through allowlists.
- Specific post, page, and category object IDs are validated.
- Condition summaries display in admin.
- Condition evaluator is testable without rendering templates.

## Excluded Scope

- Rendering templates on the frontend.
- Dynamic Data resolver implementation.
- Custom taxonomy, tag, author, date, or WooCommerce conditions.
- Advanced condition groups beyond the v1.0 model.
- Public condition extension API.
- Visual condition builder beyond required admin assignment controls.

## Definition of Done

- Phase 4 deliverables are implemented according to [Architecture](../planning/architecture.md).
- Acceptance criteria are verified.
- Condition evaluation is covered by unit or focused integration test recommendations.
- Code review report is created through [Code Review Agent](../agents/review.md).
- Testing report is created through [Testing Agent](../agents/test.md).
- Security review report is created through [Security Audit Agent](../agents/security.md).
- [Project Status](../status.md) is updated.
- [CHANGELOG.md](../../CHANGELOG.md) is updated.
- Phase release summary is created or updated in `docs/releases/`.
- No Phase 5 work starts until Phase 4 is marked complete.
