# Phase 2 - Elementor Integration

Source: [Implementation Plan](../planning/implementation-plan.md)

## Goal

Create the Elementor integration layer that safely detects Elementor Free, registers the plugin widget category, and provides the registration system used by all future widgets.

## Scope

- Elementor availability and version detection.
- Elementor-dependent service initialization.
- AlternatePro Elements widget category registration.
- Widget registration system.
- Widget enable and disable handling from settings.
- Base widget foundation for later widget phases.
- Editor and frontend asset registration strategy.
- Elementor preview context foundation.

## Deliverables

- Elementor service that loads only when Elementor is available.
- Graceful admin notices for missing or unsupported Elementor versions.
- `AlternatePro Elements` widget category.
- Widget module that can register enabled widgets only.
- Base widget class with shared helpers for future widgets.
- Editor asset registration.
- Frontend asset registration.
- Preview context helper for editor and frontend differences.
- Compatibility safeguards for sites with Elementor Pro active.

## Dependencies

- [Phase 1 - Foundation](phase-01-foundation.md)
- Phase 1 core loader.
- Phase 1 settings framework.
- Elementor Free installed and active for validation.
- Confirmed Elementor public hooks.
- [Architecture](../planning/architecture.md)

## Acceptance Criteria

- Elementor-dependent code does not run before Elementor is available.
- Missing Elementor produces a graceful admin notice and no fatal error.
- Unsupported Elementor versions produce a graceful admin notice.
- Plugin registers an `AlternatePro Elements` widget category.
- Widget registration system can register enabled widgets only.
- Widget toggles from settings are respected.
- Elementor Pro active sites do not produce class, widget, or hook conflicts.
- Editor and frontend assets are registered but not globally enqueued unnecessarily.
- Base widget helpers are available for future widgets.

## Excluded Scope

- Building final v1.0 widgets.
- Theme Builder template storage or rendering.
- Conditions Engine.
- Dynamic Data resolver implementation.
- Elementor Pro-only APIs.
- Custom Elementor editor panels beyond what is required for safe widget registration.

## Definition of Done

- Phase 2 deliverables are implemented according to [Architecture](../planning/architecture.md).
- Acceptance criteria are verified with Elementor Free active and inactive.
- Code review report is created through [Code Review Agent](../agents/review.md).
- Testing report is created through [Testing Agent](../agents/test.md).
- Security review report is created through [Security Audit Agent](../agents/security.md).
- [Project Status](../status.md) is updated.
- [CHANGELOG.md](../../CHANGELOG.md) is updated.
- Phase release summary is created or updated in `docs/releases/`.
- No Phase 3 work starts until Phase 2 is marked complete.
