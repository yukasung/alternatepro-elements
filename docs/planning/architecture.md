# AlternatePro Elements Final Architecture

Status: final planning baseline
Date: 2026-06-15

## Purpose

This document is the final architecture baseline for AlternatePro Elements before implementation begins. It consolidates the original planning documents and resolves the risks identified in [architecture-review.md](../reviews/architecture-review.md).

This is a planning document only. It does not define implementation code.

## Architecture Decisions

## 1. v1.0 Is A Core Theme Builder Release

v1.0 will not attempt full Elementor Pro parity. It will ship a realistic Elementor Free extension focused on:

- Safe plugin foundation.
- Essential Elementor widgets.
- Header/Footer Builder.
- Core Theme Builder foundations.
- Template conditions.
- Plugin-owned dynamic data resolvers.
- Basic admin settings.

The phrase "Full Theme Builder" should not mean full Elementor Pro parity in v1.0. It means the plugin owns a complete, extensible foundation for core WordPress template areas.

## 2. Dynamic Tags Are Plugin-Owned Resolvers In v1.0

v1.0 will provide Dynamic Data Resolvers used by AlternatePro widgets and templates.

Elementor editor-level Dynamic Tags UI integration is not guaranteed in v1.0. It may ship only if Elementor Free supports the required public APIs cleanly. Otherwise it moves to v1.5.

## 3. Elementor Free Public APIs Only

The plugin must not depend on Elementor Pro classes, Elementor Pro document types, Elementor Pro Theme Builder internals, or undocumented Elementor internals.

## 4. Existing Header/Footer Data Must Be Preserved

The existing `apro_template` post type and `_apro_` meta keys may be reused. If the implementation renames modules or moves classes, it must preserve existing template data and include a schema/version upgrade path.

## 5. Admin Tools Are Narrowed For v1.0

v1.0 admin tools are limited to safe settings, links, and read-only diagnostics. Settings import, full reset tools, and template-kit import/export are deferred.

## Version Scope

## v1.0

Primary goal: stable Elementor Free foundation with core Theme Builder support.

### v1.0 Features

- Safe activation, deactivation, and requirements checks.
- PHP 8.1+ runtime requirement.
- WordPress and Elementor Free compatibility checks.
- Elementor widget category.
- Essential widgets:
  - Site Logo
  - Site Title
  - Search Form
  - Breadcrumbs
  - Image Box
  - Icon Box
  - Call To Action
  - Hero Section
  - Team Member
- Conditional widgets if implementation remains stable:
  - Nav Menu
  - Posts
- Theme Builder template types:
  - Header
  - Footer
  - Single Post
  - Archive
  - Search
  - 404
- Conditions:
  - Entire Site
  - Front Page
  - Blog Page
  - All Posts
  - Specific Post
  - All Pages
  - Specific Page
  - All Categories
  - Specific Category
  - Search Results
  - 404 Page
- Dynamic Data Resolvers:
  - Site Title
  - Site Logo
  - Page Title
  - Post Title
  - Post Excerpt
  - Featured Image
  - Post Date
  - Author Name
  - Categories
  - Breadcrumbs
- Admin screens:
  - Overview
  - Module toggles
  - Widget toggles
  - Theme Builder links
  - Read-only diagnostics

### v1.0 Exclusions

- WooCommerce widgets or templates.
- Popup Builder.
- Mega Menu Builder.
- Loop Builder parity.
- Custom field integrations.
- ACF, Pods, Toolset, or Meta Box integrations.
- Multilingual plugin integrations.
- Role manager.
- Settings import.
- Full reset tools.
- Template-kit import/export.
- Elementor Pro Dynamic Tags parity.
- Elementor Pro Theme Builder parity.
- Tag-specific, author-specific, or date-specific archive conditions.

### v1.0 Compatibility Policy

Exact WordPress and Elementor version numbers must be pinned during Phase 0 implementation validation and recorded in release notes.

The architecture requires:

- WordPress latest stable at implementation validation time.
- Elementor Free latest stable at implementation validation time.
- PHP 8.1 or newer.
- Graceful behavior when Elementor is inactive.
- Graceful behavior when Elementor Pro is active.
- Best-effort compatibility with classic themes and block themes.

## v1.5

Primary goal: deepen editor integration and harden high-complexity features.

### v1.5 Candidates

- Elementor Dynamic Tags adapter if public Elementor Free APIs support it.
- Testimonial Carousel.
- Advanced Posts pagination.
- Nav Menu advanced submenu behavior.
- Improved block theme compatibility.
- Settings export.
- Read-only support bundle export.
- Additional archive conditions if requested:
  - Tags
  - Authors
  - Dates
- Template preview object selector.
- More diagnostics and conflict tools.

## v2.0

Primary goal: advanced builder capabilities beyond the v1 core.

### v2.0 Candidates

- WooCommerce template support.
- WooCommerce widgets.
- Custom post type singles and archives.
- Advanced loop builder behavior.
- Template kit import/export.
- Settings import and reset tools.
- Third-party dynamic field integrations.
- Multilingual plugin integrations.
- Mega menu features.
- Popup builder.
- Advanced role and permission controls.

## Folder Structure

The final structure keeps the project modular without requiring one class per small behavior before the behavior is stable.

```text
alternatepro-elements/
  alternatepro-elements.php
  uninstall.php
  readme.txt
  LICENSE
  phpcs.xml
  .editorconfig
  languages/
  assets/
    css/
      admin.css
      editor.css
      frontend.css
      widgets.css
    js/
      admin.js
      editor.js
      frontend.js
      widgets.js
    images/
      placeholder.svg
  includes/
    Autoloader.php
    Plugin.php
    Requirements.php
    Modules.php
    Helpers.php
    Activation.php
    Upgrades.php
    Capabilities.php
    Assets/
      Assets.php
      AdminAssets.php
      EditorAssets.php
      FrontendAssets.php
    Admin/
      Admin.php
      SettingsPage.php
      Notices.php
      Diagnostics.php
    Elementor/
      ElementorService.php
      WidgetCategory.php
      PreviewContext.php
    Widgets/
      WidgetsModule.php
      BaseWidget.php
      SiteLogoWidget.php
      SiteTitleWidget.php
      SearchFormWidget.php
      BreadcrumbsWidget.php
      ImageBoxWidget.php
      IconBoxWidget.php
      CallToActionWidget.php
      HeroSectionWidget.php
      TeamMemberWidget.php
      NavMenuWidget.php
      PostsWidget.php
    ThemeBuilder/
      ThemeBuilderModule.php
      PostType.php
      TemplateTypes.php
      TemplateRepository.php
      TemplateResolver.php
      TemplateRenderer.php
      Preview.php
      MetaBox.php
      AdminColumns.php
      Compatibility/
        ClassicThemeAdapter.php
        BlockThemeAdapter.php
        ElementorProDetector.php
    Conditions/
      ConditionsModule.php
      ConditionRegistry.php
      ConditionEvaluator.php
      ConditionSanitizer.php
      ConditionDefinitions.php
    DynamicData/
      DynamicDataModule.php
      ResolverRegistry.php
      ResolverContext.php
      CoreResolvers.php
    Settings/
      SettingsRepository.php
      SettingsSanitizer.php
  templates/
    theme-builder-wrapper.php
  tests/
  docs/
```

## Naming Rules

- PHP namespace: `AlternatePro\Elements`.
- Text domain: `alternatepro-elements`.
- Function prefix: `apro_elements_`.
- Option prefix: `apro_elements_`.
- Meta prefix: `_apro_`.
- Asset handle prefix: `apro-elements-`.
- Hook prefix: `apro_elements_`.
- Template post type: `apro_template`, unless a future migration explicitly changes it.

Namespaced class files may use class-name file naming if the PHPCS configuration allows it. The project must include `phpcs.xml` before release to make the chosen convention explicit.

## Main Classes And Responsibilities

## Core Classes

### `Plugin`

Owns plugin boot sequence.

Responsibilities:

- Register text domain.
- Initialize requirements checks.
- Initialize activation or upgrade routines when needed.
- Load module registry.
- Avoid duplicate initialization.

### `Requirements`

Owns environment validation.

Responsibilities:

- Check PHP version.
- Check WordPress version.
- Check Elementor availability and version when Elementor-dependent modules load.
- Provide admin notice data.
- Prevent fatal errors when requirements fail.

### `Modules`

Owns module registration.

Responsibilities:

- Register enabled modules.
- Respect settings-based module toggles.
- Initialize modules in dependency order.

### `Activation`

Owns activation-only behavior.

Responsibilities:

- Register post types needed for rewrite flushing.
- Set default options.
- Store initial schema version.
- Flush rewrites safely.

### `Upgrades`

Owns versioned data migrations.

Responsibilities:

- Track schema version.
- Migrate legacy header/footer metadata if needed.
- Preserve existing `apro_template` content.
- Run idempotent upgrades.

### `Capabilities`

Owns capability decisions.

Responsibilities:

- Define admin settings capability.
- Define template management capability helpers.
- Centralize permission checks used by admin, AJAX, and REST features.

## Assets Module

### `Assets`

Registers shared asset handles.

### `AdminAssets`

Loads admin-only scripts and styles on plugin screens.

### `EditorAssets`

Loads Elementor-editor-only scripts and styles.

### `FrontendAssets`

Loads frontend assets only when required by active templates or widgets.

## Admin Module

### `Admin`

Coordinates admin screens and admin hooks.

### `SettingsPage`

Renders and saves v1.0 settings.

Responsibilities:

- Overview tab.
- Modules tab.
- Widgets tab.
- Theme Builder links.
- Diagnostics tab.

### `Notices`

Renders admin notices.

Responsibilities:

- Missing Elementor.
- Unsupported PHP.
- Unsupported WordPress.
- Unsupported Elementor version.
- Elementor Pro Theme Builder conflict notice.

### `Diagnostics`

Provides read-only system information.

Responsibilities:

- Plugin version.
- WordPress version.
- PHP version.
- Elementor version.
- Active theme.
- Enabled modules.
- Registered widgets.
- Active template counts.

No secrets, salts, database credentials, auth tokens, or private data may be displayed.

## Elementor Module

### `ElementorService`

Owns integration with Elementor Free.

Responsibilities:

- Wait for Elementor loaded hooks.
- Register widget category.
- Register widgets.
- Provide compatibility wrappers for Elementor API differences.
- Detect editor and preview contexts.
- Avoid Elementor Pro internal APIs.

### `WidgetCategory`

Registers the `AlternatePro Elements` widget category.

### `PreviewContext`

Provides current preview object information for Theme Builder and Dynamic Data.

## Widgets Module

### `WidgetsModule`

Coordinates widget registration.

Responsibilities:

- Register enabled widgets only.
- Respect widget toggles.
- Register widget assets.
- Provide widget availability checks.

### `BaseWidget`

Shared base for plugin widgets.

Responsibilities:

- Shared control helpers.
- Shared responsive controls.
- Shared link attribute handling.
- Shared render helpers.
- Shared dynamic resolver helpers.

### v1.0 Widget Classes

Each widget class owns controls, editor preview, frontend rendering, and widget-specific asset needs.

Required v1.0 widget classes:

- `SiteLogoWidget`
- `SiteTitleWidget`
- `SearchFormWidget`
- `BreadcrumbsWidget`
- `ImageBoxWidget`
- `IconBoxWidget`
- `CallToActionWidget`
- `HeroSectionWidget`
- `TeamMemberWidget`
- `NavMenuWidget`, if accessibility and responsive behavior pass validation
- `PostsWidget`, if query and performance rules pass validation

Deferred widget:

- Testimonial Carousel moves to v1.5 unless implementation is stable and accessible without delaying v1.0.

## Theme Builder Module

### `ThemeBuilderModule`

Coordinates Theme Builder services.

Responsibilities:

- Register template post type.
- Register template metadata.
- Register admin UI.
- Register render hooks.
- Coordinate resolver and renderer.

### `PostType`

Owns `apro_template`.

Responsibilities:

- Register post type.
- Configure labels.
- Configure Elementor editability.
- Keep public query behavior safe.

### `TemplateTypes`

Defines supported template types.

v1.0 types:

- `header`
- `footer`
- `single_post`
- `archive`
- `search`
- `not_found`

### `TemplateRepository`

Fetches template posts.

Responsibilities:

- Query active templates.
- Filter by type.
- Read template meta.
- Avoid repeated database queries during a request.

### `TemplateResolver`

Selects the winning template for a request.

Responsibilities:

- Build request context.
- Fetch candidate templates.
- Ask `ConditionEvaluator` to evaluate candidates.
- Apply specificity.
- Apply priority.
- Return one template ID per template type.
- Cache resolution per request.

### `TemplateRenderer`

Renders templates.

Responsibilities:

- Confirm template is public, published, active, and allowed to render.
- Render through Elementor frontend APIs.
- Preserve global query context.
- Avoid duplicate render.
- Provide safe fallback when Elementor cannot render.

### `Preview`

Owns Theme Builder preview behavior.

Responsibilities:

- Determine preview object.
- Provide fallback preview context.
- Prevent unauthorized preview access.

### `MetaBox`

Owns template settings UI.

Responsibilities:

- Template type.
- Template status.
- Template priority.
- Conditions.

### `AdminColumns`

Owns template list columns.

Responsibilities:

- Type.
- Status.
- Priority.
- Condition summary.
- Conflict hints.

### Compatibility Adapters

`ClassicThemeAdapter` handles classic theme hook behavior.

`BlockThemeAdapter` handles best-effort block theme behavior.

`ElementorProDetector` detects Elementor Pro Theme Builder conflicts and reports them without using Pro internals.

## Conditions Module

### `ConditionsModule`

Coordinates condition registry, sanitization, and evaluator.

### `ConditionRegistry`

Defines available conditions and metadata.

### `ConditionDefinitions`

Holds v1.0 condition definitions.

This grouped definition file is acceptable for v1.0. One class per condition may be introduced later only if complexity requires it.

### `ConditionSanitizer`

Validates and sanitizes condition payloads.

### `ConditionEvaluator`

Evaluates whether a template matches the current request.

Responsibilities:

- Evaluate includes.
- Evaluate excludes.
- Apply specificity scores.
- Return match details to `TemplateResolver`.

## Dynamic Data Module

### `DynamicDataModule`

Coordinates dynamic resolver registration.

### `ResolverRegistry`

Registers dynamic resolvers.

### `ResolverContext`

Represents current render context.

Context sources:

- Current frontend request.
- Main queried object.
- Current post.
- Elementor preview document.
- Selected preview object, once available.

### `CoreResolvers`

Provides v1.0 dynamic data values.

Resolvers:

- Site Title
- Site Logo
- Page Title
- Post Title
- Post Excerpt
- Featured Image
- Post Date
- Author Name
- Categories
- Breadcrumbs

One class per resolver is deferred until the resolver system becomes more complex.

## Settings Module

### `SettingsRepository`

Owns settings access.

Responsibilities:

- Load plugin settings.
- Provide defaults.
- Update settings.
- Avoid autoloading large data.

### `SettingsSanitizer`

Owns settings validation.

Responsibilities:

- Sanitize module toggles.
- Sanitize widget toggles.
- Sanitize Theme Builder options.
- Enforce allowlists.

## Data Model

## Template Post Type

Post type:

- `apro_template`

Template meta:

- `_apro_template_type`
- `_apro_template_status`
- `_apro_template_priority`
- `_apro_display_conditions`

Existing header/footer meta may remain valid:

- `_apro_page_header_template`
- `_apro_page_footer_template`

## Template Status

Allowed values:

- `active`
- `inactive`

Only templates with WordPress post status `publish` and template status `active` can render publicly.

## Template Priority

Priority is an integer. Higher priority wins when two templates have the same specificity.

The admin UI must describe this rule clearly.

## Conditions Storage

Conditions are stored as structured post meta.

Conceptual shape:

```text
include:
  - type: entire_site
  - type: specific_page
    object_id: 123
exclude:
  - type: specific_post
    object_id: 456
```

Final storage must be sanitized, allowlisted, and migration-friendly.

## Settings Storage

Recommended options:

- `apro_elements_settings`
- `apro_elements_modules`
- `apro_elements_widgets`
- `apro_elements_schema_version`

Large diagnostics data must not be stored or autoloaded.

## Data Lifecycle

## Activation

Activation should:

- Register template post type.
- Create default options.
- Store schema version.
- Flush rewrite rules.

## Upgrade

Upgrade routines should:

- Run idempotently.
- Preserve existing templates.
- Preserve existing header/footer metadata.
- Migrate metadata only when needed.
- Store completed schema version.

## Deactivation

Deactivation should:

- Flush rewrite rules.
- Leave user data intact.
- Not delete templates or settings.

## Uninstall

Uninstall policy must be explicit before release.

Recommended v1.0 policy:

- Remove plugin settings.
- Leave templates intact unless a documented delete-data option is added in a later version.
- Do not delete Elementor document data unexpectedly.

## Elementor Integration Points

## Required Elementor Hooks

Use public Elementor hooks only.

Expected integration points:

- Elementor loaded or initialized hook for checking availability.
- Elementor widget registration hook.
- Elementor widget category registration hook.
- Elementor editor enqueue hook.
- Elementor frontend enqueue hook.
- Elementor frontend rendering APIs for template output.

Exact hook names must be validated against the supported Elementor version during Phase 0.

## Widget Integration

Widgets must:

- Register only after Elementor is available.
- Use Elementor Free widget APIs.
- Use Elementor controls and responsive controls.
- Avoid Elementor Pro-only controls and classes.
- Use plugin-specific widget names.
- Respect widget enable/disable settings.

## Editor Preview Integration

Editor preview must:

- Avoid fatal errors when no frontend object exists.
- Provide preview fallbacks.
- Use `PreviewContext` and `ResolverContext`.
- Keep preview-only behavior permission-aware.

## Elementor Pro Compatibility

When Elementor Pro is active:

- AlternatePro must not unregister Pro widgets.
- AlternatePro must not override Pro classes.
- AlternatePro must not use Pro internals.
- AlternatePro should show admin conflict notices if Pro Theme Builder appears to manage the same template area.
- AlternatePro templates should render only through its own resolver and only when enabled.

Default conflict policy:

- Do not silently disable AlternatePro templates.
- Warn administrators about possible overlap.
- Provide a setting in v1.5 or later if stronger conflict behavior is needed.

## WordPress Hooks

## Core Lifecycle Hooks

Planned lifecycle hooks:

- `plugins_loaded` for plugin initialization.
- `init` for post type registration.
- `admin_init` for settings registration and upgrade checks.
- `admin_menu` for admin pages.
- `admin_notices` for notices.
- `save_post_apro_template` for template metadata.
- `current_screen` or screen checks for admin asset loading.

## Elementor Hooks

Exact hook names must be verified during Phase 0. Planned areas:

- Widget category registration.
- Widget registration.
- Editor assets.
- Frontend assets.

## Theme Builder Rendering Hooks

Header/Footer rendering may use:

- `get_header`
- `wp_body_open`
- `get_footer`
- Theme compatibility hooks where explicitly supported.

Full template rendering may use:

- `template_include`

Rendering must be guarded to prevent:

- Admin rendering.
- REST rendering.
- AJAX rendering.
- Cron rendering.
- CLI rendering.
- Duplicate rendering.

## Internal Extension Hooks

Public extension hooks should be minimal in v1.0.

Candidate internal filters:

- `apro_elements_template_types`
- `apro_elements_condition_definitions`
- `apro_elements_dynamic_resolvers`
- `apro_elements_registered_widgets`

These should be documented as internal until the API is stable.

## Theme Builder Flow

## Admin Creation Flow

1. Administrator opens Theme Builder templates.
2. Administrator creates a new `apro_template`.
3. Administrator selects template type.
4. Administrator sets status and priority.
5. Administrator assigns conditions.
6. Administrator edits template content with Elementor.
7. Template metadata is saved with nonce, capability, and sanitization checks.

## Frontend Resolution Flow

1. WordPress receives a frontend request.
2. Theme Builder determines request context.
3. For each relevant template type, `TemplateResolver` loads active candidates.
4. `ConditionEvaluator` evaluates includes and excludes.
5. Resolver removes excluded templates.
6. Resolver ranks matches by specificity.
7. Resolver ranks ties by priority.
8. Resolver returns one winning template per type.
9. Renderer verifies publish status, active status, and permissions.
10. Renderer outputs the template through Elementor frontend APIs.

## Template Type Behavior

### Header

Renders in the header area. Classic themes are the primary v1.0 target. Block theme support is best effort unless validation confirms reliable behavior.

### Footer

Renders in the footer area. Same compatibility policy as Header.

### Single Post

Renders for individual posts only. Custom post type singles are not a v1.0 promise.

### Archive

Renders for general post archive contexts and category archives. Tag-specific, author-specific, and date-specific assignment is deferred.

### Search

Renders search results pages.

### 404

Renders not-found pages.

## Conditions System Flow

## Condition Definitions

v1.0 condition definitions:

- Entire Site
- Front Page
- Blog Page
- All Posts
- Specific Post
- All Pages
- Specific Page
- All Categories
- Specific Category
- Search Results
- 404 Page

Specific Category matches category archive pages only in v1.0. It does not mean single posts inside that category.

## Include And Exclude Rules

- A template must match at least one include condition.
- A template with no include conditions does not render.
- Any matching exclude condition prevents render.
- Exclusions win over inclusions.
- More specific conditions win over broader conditions.
- Priority resolves ties at the same specificity level.

## Specificity Scores

Recommended v1.0 specificity:

- Specific Post: 100
- Specific Page: 100
- Specific Category: 90
- Search Results: 80
- 404 Page: 80
- Front Page: 75
- Blog Page: 75
- All Posts: 60
- All Pages: 60
- All Categories: 60
- Entire Site: 10

## Evaluation Flow

1. Build request context from WordPress conditional tags and queried object.
2. Sanitize stored conditions.
3. Evaluate includes.
4. Evaluate excludes.
5. Return match status, specificity, and matched condition IDs.
6. Template Resolver performs priority tie-breaking.

## Dynamic Data Flow

## v1.0 Dynamic Data Contract

Dynamic data is resolved by plugin-owned resolvers. Widgets and templates consume these resolvers directly.

This is not the same as promising Elementor Pro-style Dynamic Tags UI in v1.0.

## Resolver Flow

1. Widget or template requests a dynamic value.
2. ResolverRegistry identifies the resolver.
3. ResolverContext provides current object and preview state.
4. Resolver returns typed data.
5. Consuming widget escapes data for its output context.
6. Empty values return safe fallbacks.

## Resolver Return Types

Allowed return types:

- Text.
- URL.
- Image data.
- Date.
- List.
- Limited HTML where explicitly allowed.

## Escaping Ownership

Resolvers provide data. Widgets and renderers escape output based on context.

Rules:

- Text is escaped as text.
- URLs are escaped as URLs.
- Attributes are escaped as attributes.
- Lists escape every item.
- Limited HTML uses an allowlist.
- Raw HTML is not allowed unless intentionally sanitized.

## Security Rules

## General Rules

- Block direct file access.
- Check capabilities for all admin actions.
- Verify nonces for all save actions.
- Sanitize every input.
- Escape every output.
- Use allowlists for enum-like values.
- Avoid direct SQL; if unavoidable, use prepared queries.
- Do not expose secrets in diagnostics.
- Do not collect external telemetry in v1.0.

## Template Security

- Render only published `apro_template` posts.
- Render only templates with active template status.
- Do not render draft, private, trash, or unauthorized templates publicly.
- Validate template IDs before rendering.
- Keep preview permission-aware.
- Preserve global post state during rendering.

## Condition Security

- Allowlist condition types.
- Validate object IDs as positive integers.
- Validate post, page, and term existence.
- Restrict object search to authorized users.
- Limit object search result fields.
- Limit object search query size.

## Widget Security

- Sanitize Elementor control values before use.
- Escape rendered settings by context.
- Validate media IDs.
- Validate URLs.
- Add safe link attributes where appropriate.
- Avoid unsafe inline scripts.

## Admin Security

- Settings require `manage_options`.
- Template metadata saves require edit permissions for the template.
- Diagnostics are read-only in v1.0.
- Import and reset tools are not included in v1.0.
- Admin notices are escaped.

## AJAX And REST Policy

v1.0 should avoid custom REST endpoints unless necessary.

If AJAX is required for object search:

- Require authentication.
- Check capabilities.
- Verify nonce.
- Sanitize search terms.
- Limit result count.
- Return only safe fields.

## Performance Rules

- Register assets centrally.
- Enqueue frontend assets only when needed.
- Keep widget scripts out of pages that do not use those widgets.
- Cache template resolution per request.
- Avoid persistent caching in v1.0 unless clearly needed.
- Limit Posts widget queries.
- Reset post data after custom queries.
- Limit admin object selector queries.

Recommended v1.0 Posts widget limits:

- Default posts per page: 6.
- Maximum posts per query: 50.
- Pagination optional and conservative.

## Accessibility Rules

Target WCAG AA where possible.

Required v1.0 checks:

- Keyboard-accessible Search Form.
- Semantic Breadcrumbs with nav landmark.
- Visible focus states.
- Configurable heading levels for title and hero widgets.
- Sufficient color contrast in default styles.
- Responsive layouts without text overlap.

Nav Menu may ship in v1.0 only if:

- Mobile toggle uses clear expanded/collapsed state.
- Keyboard navigation works.
- Focus states are visible.
- Submenus are reachable.

Testimonial Carousel is deferred to v1.5 unless:

- Autoplay can be paused.
- Controls are keyboard-accessible.
- Reduced motion is respected.
- Slide changes are not disorienting.

## Testing Strategy

## Phase 0 Validation

Before production coding:

- Pin exact supported WordPress version.
- Pin exact supported Elementor Free version.
- Validate Elementor public hooks.
- Validate Elementor widget registration APIs.
- Validate Elementor frontend rendering APIs.
- Validate editor preview behavior.
- Validate classic theme rendering.
- Validate block theme behavior.
- Validate Elementor Pro active-site conflict behavior.

## Automated Testing

Recommended automated coverage:

- Requirements checks.
- Settings sanitization.
- Condition sanitization.
- Condition evaluation.
- Template resolution.
- Dynamic resolver output.
- Activation defaults.
- Upgrade routines.

## Standards Testing

Release must include:

- PHP syntax checks.
- WordPress Coding Standards through PHPCS.
- PHP compatibility checks for PHP 8.1+.
- JavaScript linting if build tooling is added.
- CSS linting if build tooling is added.
- Translation string extraction check.

## Manual QA

Manual QA must cover:

- Elementor editor loads.
- Every v1.0 widget appears.
- Every v1.0 widget renders in editor and frontend.
- Header template renders.
- Footer template renders.
- Single Post template renders.
- Archive template renders.
- Search template renders.
- 404 template renders.
- Each condition matches expected routes.
- Exclusions override inclusions.
- Priority resolves conflicts.
- Dynamic values resolve in frontend context.
- Dynamic values resolve in editor preview or show safe fallback.
- Admin settings save correctly.
- Missing Elementor behavior is graceful.
- Elementor Pro active behavior is graceful.

## Compatibility Matrix

v1.0 release testing must include:

- PHP 8.1.
- PHP 8.2.
- PHP 8.3 or current supported PHP version at release time.
- Latest stable WordPress at validation time.
- Latest stable Elementor Free at validation time.
- Elementor inactive.
- Elementor Pro active.
- One default block theme.
- One default classic theme.
- One Elementor-friendly theme.
- Pretty permalinks on and off.

## Security Testing

Security QA must verify:

- Capability checks.
- Nonce checks.
- Input sanitization.
- Output escaping.
- Template status checks.
- Private/draft content protection.
- AJAX object search permissions if implemented.
- Diagnostics do not expose sensitive data.

## Release Criteria

v1.0 may release only when:

- All must-ship v1.0 features are complete.
- High-severity security issues are fixed.
- Elementor editor and frontend rendering are stable.
- Theme Builder conditions are predictable.
- No known fatal errors occur in supported environments.
- Uninstall and deactivation behavior is documented.
- Release notes document unsupported features clearly.

## Implementation Order

1. Phase 0 compatibility validation.
2. Core boot, requirements, activation, and upgrade foundation.
3. Settings repository and admin notices.
4. Elementor service and widget category.
5. Theme Builder post type and metadata.
6. Conditions registry, sanitizer, and evaluator.
7. Template repository, resolver, and renderer.
8. Header and Footer templates.
9. Dynamic Data registry and core resolvers.
10. Essential widgets.
11. Single, Archive, Search, and 404 rendering.
12. Admin settings and diagnostics.
13. Optional v1.0 widgets if stable.
14. Security, accessibility, performance, and release QA.

## Open Questions For Phase 0

These must be answered before production implementation:

- What exact WordPress version is the minimum supported v1.0 baseline?
- What exact Elementor Free version is the minimum supported v1.0 baseline?
- Which Elementor hooks and classes are verified public and stable?
- Can Elementor Free support an editor-level Dynamic Tags adapter without Pro internals?
- What is the most reliable block theme Header/Footer strategy?
- Should Elementor Pro Theme Builder conflicts warn only, or should admins choose precedence?
- Does existing header/footer data need an upgrade routine immediately?
- Should Nav Menu and Posts be must-ship or conditional v1.0 features after validation?
