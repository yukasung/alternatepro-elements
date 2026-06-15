# Phase 1 - Foundation

Source: [Implementation Plan](../planning/implementation-plan.md)

## Goal

Establish the plugin foundation so modules can load safely, requirements can fail gracefully, settings can be stored consistently, and administrators have a basic control surface.

## Scope

- Plugin bootstrap and metadata alignment.
- Core loader initialization.
- Simple service container or registry.
- PHP, WordPress, and Elementor dependency checks.
- Admin notices for unsupported environments.
- Admin menu structure.
- Settings repository and sanitizer.
- Module and widget toggle storage.
- Read-only diagnostics foundation.
- Activation, deactivation, upgrade, and uninstall policy foundations.

## Deliverables

- Runtime compatibility checks for PHP 8.1+, WordPress, and Elementor.
- Predictable plugin boot sequence.
- Shared service registry for core services.
- Admin notices for missing or unsupported dependencies.
- Admin menu and settings page shell.
- Sanitized settings persistence for modules and widget toggles.
- Default settings created during activation.
- Schema version storage for future upgrades.
- Read-only diagnostics surface that avoids exposing secrets.
- Documented uninstall policy before release.

## Dependencies

- Existing plugin bootstrap file.
- Existing autoloader.
- Existing requirements and module loader concepts.
- [Architecture](../planning/architecture.md)
- [Implementation Plan](../planning/implementation-plan.md)
- WordPress admin APIs.

## Acceptance Criteria

- Plugin activates without fatal errors on supported PHP and WordPress versions.
- Plugin fails gracefully with clear admin notices when requirements are not met.
- PHP 8.1+ requirement is enforced in runtime checks and plugin metadata.
- Core loader initializes modules in a predictable order.
- Service container or registry provides shared services without global sprawl.
- Default settings are created on activation.
- Schema version is stored for future upgrades.
- Admin menu appears only for authorized users.
- Settings page can save module and widget settings with nonce and capability checks.
- Settings are sanitized through allowlists.
- Read-only diagnostics are available and do not expose secrets.
- Deactivation leaves user data intact.
- Uninstall policy is documented before release.

## Excluded Scope

- Elementor widget implementation.
- Theme Builder template rendering.
- Conditions Engine implementation.
- Dynamic Data resolver implementation.
- Advanced settings import, export, reset, or migration tooling.
- Public extension APIs.
- WooCommerce support.
- Popup Builder support.

## Definition of Done

- Phase 1 deliverables are implemented according to [Architecture](../planning/architecture.md).
- Acceptance criteria are verified.
- Code review report is created through [Code Review Agent](../agents/review.md).
- Testing report is created through [Testing Agent](../agents/test.md).
- Security review report is created through [Security Audit Agent](../agents/security.md).
- [Project Status](../status.md) is updated.
- [CHANGELOG.md](../../CHANGELOG.md) is updated.
- Phase release summary is created or updated in `docs/releases/`.
- No Phase 2 work starts until Phase 1 is marked complete.
