# Phase 3 - Theme Builder Foundation

Source: [Implementation Plan](../planning/implementation-plan.md)

## Goal

Build the template storage foundation for the Theme Builder, including the `apro_template` post type, template metadata, status, priority, and admin assignment UI.

## Scope

- `apro_template` post type registration.
- Template type definitions for v1.0 Theme Builder contexts.
- Template metadata storage.
- Template active and inactive status.
- Template priority storage.
- Admin assignment UI foundation.
- Admin columns for template information.
- Elementor edit flow support for template posts.
- Elementor Pro Theme Builder overlap detection.
- Upgrade path review for existing header and footer template data.

## Deliverables

- Safely registered `apro_template` post type.
- Template type registry for header, footer, single post, archive, search, and 404.
- Template repository for reading template records and metadata.
- Admin meta box for template type, status, priority, and assignment placeholders.
- Admin columns for type, status, priority, and condition summary placeholders.
- Elementor preview support foundation for template posts.
- Elementor Pro overlap warning without fatal conflicts.
- Upgrade routine plan for preserving existing header and footer metadata if required.

## Dependencies

- [Phase 1 - Foundation](phase-01-foundation.md)
- [Phase 2 - Elementor Integration](phase-02-elementor-integration.md)
- Existing header and footer template data review.
- [Architecture](../planning/architecture.md)

## Acceptance Criteria

- `apro_template` post type is registered safely.
- Existing template data remains readable.
- Template types are defined for header, footer, single post, archive, search, and 404.
- Template status supports active and inactive values.
- Template priority is stored and sanitized.
- Template assignment metadata can be saved with nonce and capability checks.
- Admin columns show template type, status, priority, and condition summary placeholder.
- Elementor edit flow works for template posts.
- Elementor Pro Theme Builder overlap is detected and reported as a warning, not a fatal conflict.
- Upgrade routine can preserve or migrate existing header and footer metadata if required.

## Excluded Scope

- Conditions Engine evaluation.
- Frontend template rendering.
- Header and footer injection.
- Single, archive, search, or 404 body replacement.
- Dynamic Data resolver implementation.
- WooCommerce templates.
- Global site kit import or export tooling.

## Definition of Done

- Phase 3 deliverables are implemented according to [Architecture](../planning/architecture.md).
- Acceptance criteria are verified.
- Existing template data remains readable after the phase.
- Code review report is created through [Code Review Agent](../agents/review.md).
- Testing report is created through [Testing Agent](../agents/test.md).
- Security review report is created through [Security Audit Agent](../agents/security.md).
- [Project Status](../status.md) is updated.
- [CHANGELOG.md](../../CHANGELOG.md) is updated.
- Phase release summary is created or updated in `docs/releases/`.
- No Phase 4 work starts until Phase 3 is marked complete.
