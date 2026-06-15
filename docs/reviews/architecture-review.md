# Architecture Review

Review date: 2026-06-15

## Review Scope

This review history covers the planning documents that led to the final architecture:

- [Project Overview](../planning/project-overview.md)
- [Requirements](../planning/requirements.md)
- [Architecture](../planning/architecture.md)
- [Plugin File Structure](../planning/plugin-file-structure.md)
- [Widgets List](../planning/widgets-list.md)
- [Theme Builder Plan](../planning/theme-builder-plan.md)
- [Conditions System](../planning/conditions-system.md)
- [Dynamic Tags](../planning/dynamic-tags.md)
- [Admin Settings](../planning/admin-settings.md)
- [Development Roadmap](../planning/development-roadmap.md)
- [Testing Plan](../testing/testing-plan.md)
- [Security Checklist](security-checklist.md)

## Executive Summary

The planning set was directionally strong, but v1.0 was too broad for a reliable Elementor Free release. The final architecture narrows v1.0 into a Core Theme Builder release, treats dynamic behavior as plugin-owned resolvers first, and defers riskier tooling and parity features to later versions.

## Highest Priority Findings

## 1. v1.0 Scope Was Too Ambitious

Severity: High

The original MVP combined 12 widgets, Theme Builder, Conditions System, Dynamic Tags, admin settings, responsive behavior, accessibility targets, and broad compatibility with Elementor Free, Elementor Pro active sites, classic themes, and block themes.

Resolution:

- [Architecture](../planning/architecture.md) now separates v1.0, v1.5, and v2.0.
- v1.0 is positioned as a realistic Core Theme Builder release.
- High-risk widgets and advanced integrations can move to v1.5.

## 2. Dynamic Tags Needed A Clear Compatibility Decision

Severity: High

Elementor Free may not expose the same Dynamic Tags UI surfaces as Elementor Pro.

Resolution:

- v1.0 uses plugin-owned Dynamic Data Resolvers.
- Elementor editor-level Dynamic Tags adapter is optional and deferred unless public Elementor Free APIs validate cleanly.
- Elementor Pro dynamic tag classes and internals are excluded.

## 3. Theme Builder Rendering Needed A Compatibility Matrix

Severity: High

The original plan named possible rendering hooks but did not define a final rendering contract for classic themes, block themes, Elementor Canvas, Elementor Full Width, and Elementor Pro active sites.

Resolution:

- [Architecture](../planning/architecture.md) defines rendering adapters, conflict policy, and Phase 0 validation.
- Block theme support is best effort unless validation proves reliable.
- Elementor Pro conflicts should warn administrators without relying on Pro internals.

## 4. Existing Header/Footer Migration Was Under-Specified

Severity: High

The existing `apro_template` post type and `_apro_` metadata needed a preservation path.

Resolution:

- [Architecture](../planning/architecture.md) preserves existing template data.
- Upgrade routines and schema versioning are part of the core architecture.
- The implementation plan includes migration-aware Theme Builder foundation work.

## 5. Admin Tools Expanded The v1.0 Attack Surface

Severity: Medium

Import, export, reset, and advanced diagnostics add security and QA burden.

Resolution:

- v1.0 admin is narrowed to overview, module toggles, widget toggles, Theme Builder links, and read-only diagnostics.
- Settings import, full reset tools, and template-kit import/export are deferred.

## Missing Requirements Identified

- Exact WordPress and Elementor version baselines.
- Data lifecycle policy.
- Uninstall behavior.
- Release packaging plan.
- Translation workflow.
- Concrete accessibility acceptance criteria.
- Concrete performance limits.
- Elementor Pro conflict policy.

Resolution:

- These items are now represented in [architecture.md](../planning/architecture.md), [implementation-plan.md](../planning/implementation-plan.md), and [docs/releases](../releases/README.md).

## Scope Conflicts Resolved

## Full Theme Builder Versus Focused MVP

Resolution:

- v1.0 is a Core Theme Builder release, not Elementor Pro parity.

## Archive Scope Ambiguity

Resolution:

- v1.0 supports general post archive contexts and category archives.
- Tag-specific, author-specific, and date-specific assignments move later.

## Specific Category Ambiguity

Resolution:

- Specific Category matches category archive pages only in v1.0.
- It does not imply single posts inside that category.

## Dynamic Tags Versus Dynamic Resolvers

Resolution:

- Internal architecture uses Dynamic Data Resolvers.
- Elementor Dynamic Tags adapter is a later compatibility layer if validated.

## Over-Complex Architecture Risks

The review warned against too many classes too early and public extensibility hooks before stabilization.

Resolution:

- v1.0 groups condition definitions and core resolvers.
- One class per condition or resolver is deferred until complexity justifies it.
- Public extension hooks are minimal and considered internal until stable.

## Security Risks Identified

- Template rendering could expose draft or private templates.
- Object search endpoints could leak private content.
- Dynamic HTML output could be escaped incorrectly.
- Import and reset tools could expand the attack surface.

Resolution:

- Public rendering requires published and active templates.
- Preview is permission-aware.
- Object search must be authenticated, nonce-protected, permission-checked, and query-limited if implemented.
- Widgets and renderers own context-specific escaping.
- Import and reset tools are not part of v1.0.

## Elementor Compatibility Risks Identified

- Elementor Pro Theme Builder may conflict.
- Elementor Pro internals must not be used.
- Widget assets must not depend on private Elementor handles.
- Editor preview context can differ from frontend context.

Resolution:

- Elementor Free public APIs only.
- Elementor Pro conflict warning policy.
- Phase 0 validates hooks and rendering APIs before production coding.
- Preview context is treated as a required part of the architecture.

## WordPress Standards Issues Identified

- File naming and autoloading convention must be explicit.
- PHPCS configuration is needed.
- Translation workflow is needed.
- Sanitization and escaping must be acceptance criteria.

Resolution:

- [Architecture](../planning/architecture.md) defines naming rules.
- [Implementation Plan](../planning/implementation-plan.md) includes PHPCS and compatibility checks.
- Release documentation now has a home in [docs/releases](../releases/README.md).

## Features Recommended For Later Versions

Moved to v1.5 or later:

- Elementor Dynamic Tags adapter.
- Testimonial Carousel.
- Advanced Posts pagination.
- Advanced Nav Menu submenu behavior.
- Settings export and support bundle export.
- Additional archive conditions.
- Template preview object selector.

Moved to v2.0 or later:

- WooCommerce widgets and templates.
- Custom post type singles and archives.
- Advanced loop builder behavior.
- Template-kit import/export.
- Settings import and reset tools.
- Third-party dynamic field integrations.
- Multilingual plugin integrations.
- Mega menu features.
- Popup builder.
- Advanced role controls.

## Final Recommendation

The plan is ready for Phase 0 validation, not immediate broad feature coding. Implementation should start with the core foundation, Elementor API validation, Theme Builder storage, conditions, and rendering before complex widgets or advanced editor integrations.
