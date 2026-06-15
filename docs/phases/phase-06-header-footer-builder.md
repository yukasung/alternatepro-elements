# Phase 6 - Header/Footer Builder

Source: [Implementation Plan](../planning/implementation-plan.md)

## Goal

Render assigned Header and Footer templates on matching frontend requests using the Theme Builder resolver and Conditions Engine.

## Scope

- Header template resolution.
- Footer template resolution.
- Frontend rendering through Elementor frontend APIs.
- Theme adapter strategy for classic themes.
- Block theme behavior validation and documentation.
- Duplicate rendering protection.
- Global post and query preservation.
- Elementor Pro conflict warnings.
- Frontend wrapper and asset support for rendered templates.

## Deliverables

- Template resolver support for header and footer template types.
- Template renderer for assigned header and footer templates.
- Classic theme adapter.
- Block theme adapter or documented best-effort behavior.
- Frontend wrapper template.
- Frontend CSS and JavaScript registration for Theme Builder output.
- Conflict warning behavior for Elementor Pro Theme Builder overlap.
- Rendering safeguards for inactive, draft, private, trashed, or missing templates.

## Dependencies

- [Phase 3 - Theme Builder Foundation](phase-03-theme-builder-foundation.md)
- [Phase 4 - Conditions Engine](phase-04-conditions-engine.md)
- [Phase 5 - Dynamic Data Engine](phase-05-dynamic-data-engine.md)
- [Phase 2 - Elementor Integration](phase-02-elementor-integration.md)
- Elementor frontend rendering integration.
- [Architecture](../planning/architecture.md)

## Acceptance Criteria

- Header template resolves for matching requests.
- Footer template resolves for matching requests.
- Inactive, draft, private, trashed, or missing templates do not render publicly.
- Only one winning header and one winning footer render per request.
- Conditions and priority determine template selection.
- Rendering uses Elementor frontend APIs.
- Rendering preserves global post and query context.
- Duplicate output is prevented.
- Classic theme rendering strategy works in the tested theme matrix.
- Block theme behavior is documented as supported or best effort after validation.
- Elementor Pro conflicts are surfaced to admins without breaking frontend rendering.

## Excluded Scope

- Single Post template rendering.
- Archive template rendering.
- Search template rendering.
- 404 template rendering.
- WooCommerce headers, footers, or templates.
- Full block theme guarantee until validation confirms support.
- Custom header/footer builder UI beyond the planned template assignment and Elementor editing flow.

## Definition of Done

- Phase 6 deliverables are implemented according to [Architecture](../planning/architecture.md).
- Acceptance criteria are verified in the supported theme matrix.
- Header and footer output does not duplicate or expose inactive templates.
- Code review report is created through [Code Review Agent](../agents/review.md).
- Testing report is created through [Testing Agent](../agents/test.md).
- Security review report is created through [Security Audit Agent](../agents/security.md).
- [Project Status](../status.md) is updated.
- [CHANGELOG.md](../../CHANGELOG.md) is updated.
- Phase release summary is created or updated in `docs/releases/`.
- No Phase 7 work starts until Phase 6 is marked complete.
