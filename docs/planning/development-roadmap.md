# Development Roadmap

## Purpose

This roadmap breaks AlternatePro Elements v1.0 into implementation phases and release milestones.

## Guiding Strategy

Build the foundation first, then the Core Theme Builder, then conditions, then widgets and dynamic data, then admin polish and testing. This reduces the chance of building widgets that later need major changes to work inside templates.

## Phase 0: Discovery And Validation

### Goals

- Validate current WordPress and Elementor Free APIs.
- Audit existing plugin code.
- Confirm PHP 8.1+ requirement.
- Confirm Dynamic Data Resolver needs and later Elementor adapter options in Elementor Free.

### Deliverables

- Technical notes.
- Final v1.0 scope confirmation.
- Risk list update.
- Decision on header/footer module migration.

### Exit Criteria

- No unknown blocker remains for widgets, Theme Builder, or conditions.

## Phase 1: Core Foundation

### Goals

- Establish stable plugin architecture.
- Align requirements checks.
- Add module registration conventions.
- Add shared settings and asset systems.

### Deliverables

- Updated bootstrap plan.
- Requirements service.
- Module loader.
- Shared admin notices.
- Shared assets registry.

### Exit Criteria

- Plugin can activate safely and load modules conditionally.

## Phase 2: Theme Builder Foundation

### Goals

- Expand current header/footer approach into a Core Theme Builder.
- Add template types and rendering strategy.

### Deliverables

- Template custom post type.
- Template type registry.
- Status and priority metadata.
- Header rendering.
- Footer rendering.
- Admin list columns.

### Exit Criteria

- Header and footer templates can be created, assigned, and rendered.

## Phase 3: Conditions System

### Goals

- Build reusable conditions for all MVP template types.

### Deliverables

- Condition registry.
- Condition evaluator.
- Include and exclude support.
- Specific post, page, and category selectors.
- Conflict resolution.

### Exit Criteria

- All MVP conditions are testable and correctly resolve matching templates.

## Phase 4: Core Theme Builder

### Goals

- Add non-header/footer template rendering.

### Deliverables

- Single Post template support.
- Archive template support.
- Search template support.
- 404 template support.
- Preview fallbacks.

### Exit Criteria

- All MVP template types render correctly across at least one classic theme and one block theme.

## Phase 5: Dynamic Data Resolvers

### Goals

- Add reusable dynamic data resolvers.
- Connect dynamic data to widgets and templates.

### Deliverables

- Dynamic resolver registry.
- All v1.0 Dynamic Data Resolvers.
- Elementor adapter deferred unless public APIs validate cleanly.
- Widget integration.

### Exit Criteria

- Dynamic values render correctly in editor preview and frontend contexts.

## Phase 6: Widgets

### Goals

- Add required v1.0 widgets.
- Add conditional widgets only if validation supports release readiness.

### Deliverables

- Site Logo
- Site Title
- Search Form
- Hero Section
- Call To Action
- Image Box
- Icon Box
- Team Member
- Breadcrumbs
- Conditional widgets:
  - Nav Menu
  - Posts
- Deferred v1.5 candidate:
  - Testimonial Carousel

### Exit Criteria

- Required widgets are available, configurable, responsive, and pass basic accessibility checks.
- Conditional widgets are either validated and shipped or explicitly deferred.

## Phase 7: Admin Settings

### Goals

- Add settings and diagnostics.

### Deliverables

- Overview screen.
- Module toggles.
- Widget toggles.
- Theme Builder settings.
- Dynamic Data settings.
- Read-only diagnostics.

### Exit Criteria

- Admin settings are permission-safe and persist correctly.

## Phase 8: Testing And Hardening

### Goals

- Prepare the plugin for release.

### Deliverables

- Unit and integration tests where practical.
- Manual QA matrix.
- Accessibility pass.
- Security pass.
- Performance pass.
- Compatibility pass.

### Exit Criteria

- No known critical or high-severity bugs remain.
- Release checklist is complete.

## Phase 9: v1.0 Release

### Goals

- Ship a stable MVP.

### Deliverables

- Final plugin package.
- Readme.
- Changelog.
- Known limitations.
- Upgrade and uninstall notes.

### Exit Criteria

- Plugin is ready for production use within the documented scope.

## Version Roadmap Alignment

### v1.0: Core Theme Builder

- Core plugin foundation.
- Required v1.0 widgets.
- Conditional Nav Menu and Posts if validated.
- Header, Footer, Single Post, Archive, Search, and 404 templates.
- Conditions System.
- Dynamic Data Resolvers.
- Basic admin settings and read-only diagnostics.

### v1.5 Candidates

- Additional archive controls.
- Elementor Dynamic Tags adapter.
- More dynamic data resolvers.
- Testimonial Carousel.
- Advanced Posts pagination.
- Advanced Nav Menu submenu behavior.
- Improved block theme compatibility.
- Settings export and read-only support bundle export.

### v2.0 Candidates

- WooCommerce template support.
- WooCommerce widgets.
- Custom post type singles and archives.
- Custom field integrations.
- Mega menu features.
- Popup builder.
- Settings import and reset tools.
- Import/export template kits.

## Assumptions

- The roadmap may be adjusted after Elementor API validation.
- Existing header/footer code should reduce early Theme Builder effort.
- v1.0 prioritizes stability over feature parity.
- Testing time should be protected, not compressed into implementation phases.

## Key Risks

- Theme Builder rendering may take longer than expected.
- Dynamic Data Resolver behavior must not depend on Elementor Pro-style Dynamic Tags UI.
- Complex widgets could delay release if built before core systems are stable.
- Admin tooling can grow beyond MVP if not scoped carefully.

## Implementation Phases Summary

1. Discovery.
2. Core foundation.
3. Theme Builder foundation.
4. Conditions.
5. Core Theme Builder.
6. Dynamic Data Resolvers.
7. Widgets.
8. Admin settings.
9. Testing and hardening.
10. Release.
