# Admin Settings

## Purpose

This document defines the planned admin settings experience for AlternatePro Elements v1.0.

## Admin Goals

- Let site administrators understand plugin status quickly.
- Allow modules and widgets to be enabled or disabled.
- Provide Theme Builder management entry points.
- Surface environment diagnostics.
- Keep settings safe, simple, and recoverable.

## Menu Placement

Recommended placement:

- Top-level menu: AlternatePro
- Submenu: Elements

Alternative:

- WordPress Settings submenu for a smaller footprint.

Recommended for v1.0: use a dedicated AlternatePro Elements admin page if Theme Builder management needs visibility.

## Settings Sections

## 1. Overview

Displays:

- Plugin version.
- WordPress version.
- PHP version.
- Elementor version and status.
- Active theme.
- Module status.
- Requirement warnings.

## 2. Modules

Controls:

- Widgets module.
- Theme Builder module.
- Conditions module.
- Dynamic Data module.

Behavior:

- Disabling a module should not delete data.
- Disabled modules should stop registering related features.
- Required dependencies should be explained.

## 3. Widgets

Controls:

- Enable or disable each widget.
- Bulk enable all.
- Bulk disable all.

Required v1.0 widgets:

- Site Logo
- Site Title
- Search Form
- Breadcrumbs
- Hero Section
- Call To Action
- Image Box
- Icon Box
- Team Member

Conditional v1.0 widgets:

- Nav Menu
- Posts

v1.5 widget candidate:

- Testimonial Carousel

## 4. Theme Builder

Controls and links:

- Enable Theme Builder.
- Template list link.
- Add Header.
- Add Footer.
- Add Single Post.
- Add Archive.
- Add Search.
- Add 404.
- Default template status for new templates.
- Priority behavior description.

## 5. Conditions

Controls:

- Enable conflict warnings.
- Enable exclude conditions.
- Show condition summaries in admin columns.
- Optional debug mode for matched condition output visible to admins only.

## 6. Dynamic Data

Controls:

- Enable dynamic data in plugin widgets.
- Enable Elementor dynamic tag adapter only if promoted after validation.
- Fallback behavior for empty values.
- Featured image fallback placeholder optional.

## 7. Tools And Diagnostics

v1.0 tools:

- Copy system info.

Diagnostics:

- Requirement status.
- Active modules.
- Registered widgets.
- Registered template types.
- Active templates count.
- Potential conflicts with Elementor Pro Theme Builder.

Deferred tools:

- Settings export: v1.5 candidate.
- Settings import: v2.0 candidate.
- Reset tools: v2.0 candidate.
- Template-kit import/export: v2.0 candidate.
- Cache clearing: only if persistent caching is added in a later version.

## Settings Storage

Recommended options:

- `apro_elements_settings`
- `apro_elements_modules`
- `apro_elements_widgets`

Settings should be autoloaded only if needed globally. Larger diagnostic or tool data should not be autoloaded.

## Permissions

Recommended capability:

- `manage_options` for settings.
- Elementor edit capabilities for editing templates.

All settings actions require:

- Capability checks.
- Nonce validation.
- Sanitization.
- Redirect with admin notice.

## Admin Notices

Notices should cover:

- Elementor inactive.
- Unsupported Elementor version.
- Unsupported PHP version.
- Theme Builder conflict warning.
- Settings saved.
- Export/import/reset notices only if those tools are added in a later version.

## UX Principles

- Avoid overwhelming the user with too many controls at once.
- Use clear labels and short descriptions.
- Keep destructive actions out of v1.0.
- Require confirmation for reset operations if they are added in a later version.
- Make diagnostics copyable for support.

## Assumptions

- Admin settings are for administrators, not editors.
- Disabling modules should preserve existing templates and widget data.
- Settings can be implemented with standard WordPress admin UI in v1.0.
- A custom React admin app is not required for v1.0.

## Key Risks

- Too many settings can make the plugin feel complex.
- Reset and import tools can be dangerous without careful validation and are deferred beyond v1.0.
- Module toggles can create confusing states if dependencies are hidden.
- Elementor Pro conflict notices must be helpful without being alarmist.

## Implementation Phases

### Phase 1: Basic Settings

- Add admin menu.
- Add overview page.
- Add module and widget toggles.

### Phase 2: Theme Builder Admin

- Add template management links.
- Add condition summaries and conflict notices.

### Phase 3: Diagnostics

- Add system info.
- Add registered feature status.
- Add support-friendly copy output.

### Phase 4: Future Tools

- Add export, import, reset, and cache-clearing tools only in later versions after validation.
