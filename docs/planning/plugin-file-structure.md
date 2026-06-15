# Plugin File Structure

## Purpose

This document proposes a maintainable file structure for AlternatePro Elements v1.0.

[Architecture](architecture.md) is the source of truth for final v1.0 module names and scope.

## Current Structure Summary

The current plugin includes:

- `alternatepro-elements.php`
- `includes/Autoloader.php`
- `includes/Plugin.php`
- `includes/Requirements.php`
- `includes/Modules.php`
- `includes/Helpers.php`
- `includes/modules/header-footer/`
- `assets/css/`
- `assets/js/`
- `uninstall.php`

This is a useful foundation. v1.0 should expand it into clearer module boundaries.

## Proposed Root Structure

```text
alternatepro-elements/
  alternatepro-elements.php
  uninstall.php
  readme.txt
  languages/
  assets/
    css/
    js/
    images/
  includes/
    Autoloader.php
    Plugin.php
    Requirements.php
    Modules.php
    Helpers.php
    Admin/
    Assets/
    Conditions/
    DynamicData/
    ThemeBuilder/
    Widgets/
  templates/
  tests/
  docs/
```

## Proposed Includes Structure

```text
includes/
  Admin/
    Admin.php
    SettingsPage.php
    Notices.php
    Diagnostics.php
    Diagnostics.php
  Assets/
    Assets.php
    FrontendAssets.php
    AdminAssets.php
    EditorAssets.php
  Conditions/
    ConditionRegistry.php
    ConditionEvaluator.php
    ConditionSanitizer.php
    ConditionDefinitions.php
  DynamicData/
    DynamicDataModule.php
    ResolverRegistry.php
    ResolverContext.php
    CoreResolvers.php
  ThemeBuilder/
    ThemeBuilder.php
    PostType.php
    TemplateTypes.php
    TemplateRepository.php
    TemplateResolver.php
    TemplateRenderer.php
    TemplatePreview.php
    MetaBox.php
    AdminColumns.php
  Widgets/
    Widgets.php
    WidgetCategory.php
    BaseWidget.php
    SiteLogoWidget.php
    SiteTitleWidget.php
    NavMenuWidget.php
    SearchFormWidget.php
    HeroSectionWidget.php
    CallToActionWidget.php
    ImageBoxWidget.php
    IconBoxWidget.php
    TeamMemberWidget.php
    PostsWidget.php
    BreadcrumbsWidget.php
```

Deferred v1.5 widget files:

```text
includes/
  Widgets/
    TestimonialCarouselWidget.php
```

## Proposed Asset Structure

```text
assets/
  css/
    admin.css
    editor.css
    frontend.css
    widgets/
      nav-menu.css
      search-form.css
      posts.css
  js/
    admin.js
    editor.js
    frontend.js
    widgets/
      nav-menu.js
  images/
    placeholder.svg
```

Testimonial Carousel assets are deferred to v1.5 unless the widget is promoted after accessibility validation.

## Proposed Documentation Structure

Planning documents live under `docs/`:

- `docs/planning/`
- `docs/reviews/`
- `docs/testing/`
- `docs/releases/`

Recommended future docs:

- `docs/developer-guide.md`
- `docs/release-checklist.md`
- `docs/widget-authoring.md`
- `docs/theme-builder-hooks.md`

## Naming Conventions

- Namespace prefix: `AlternatePro\Elements`.
- PHP class files should use class names that match the autoloader strategy.
- Template post type should remain plugin-specific, such as `apro_template`, unless a migration is planned.
- Meta keys should use the `_apro_` prefix.
- Options should use the `apro_elements_` prefix.
- Asset handles should use the `apro-elements-` prefix.
- Action and filter hooks should use the `apro_elements_` prefix.

## Migration From Existing Header/Footer Module

The existing `includes/modules/header-footer/` directory can be migrated in one of two ways:

### Option A: Keep And Extend

Keep the current module and add more template types to it. This is faster but may make the module name inaccurate.

### Option B: Rename To Theme Builder

Create a new `includes/ThemeBuilder/` module and migrate reusable classes from `header-footer`. This is clearer for long-term maintenance and better matches the v1.0 scope.

Recommended v1.0 direction: Option B, with a compatibility layer if existing template data must be preserved.

## Assumptions

- A custom autoloader will continue to be used.
- The project does not require Composer for v1.0.
- Existing template metadata can either be reused or migrated safely.
- Planning files can remain in the plugin root until implementation begins.

## Key Risks

- Renaming module directories during implementation can break references if done late.
- Too many shared helpers can hide dependencies.
- Widget files can become large if controls and rendering are not separated carefully.
- Asset splitting can become noisy if every small widget gets unnecessary files.

## Implementation Phases

### Phase 1: Confirm Structure

- Decide whether to keep `includes/modules/` or move toward top-level module directories.
- Confirm autoloading rules.
- Confirm naming conventions.

### Phase 2: Move Theme Builder Pieces

- Generalize header/footer code into Theme Builder classes.
- Preserve existing constants and metadata where practical.
- Add compatibility notes for existing templates.

### Phase 3: Add New Modules

- Add Widgets, Conditions, DynamicData, Admin, and Assets modules.
- Register modules through the central module loader.

### Phase 4: Cleanup

- Remove obsolete names after migration is complete.
- Update documentation and tests.
