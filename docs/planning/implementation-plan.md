# AlternatePro Elements Implementation Plan

Status: planning baseline
Source architecture: [architecture.md](architecture.md)

## Purpose

This document breaks AlternatePro Elements development into implementation phases. It is a planning document only and does not include plugin code.

## Planning Principles

- Build foundation before Elementor-specific features.
- Keep v1.0 realistic and aligned with the final architecture.
- Use Elementor Free public APIs only.
- Preserve existing `apro_template` data and `_apro_` metadata.
- Ship secure, testable increments.
- Defer unstable or high-risk features rather than expanding v1.0 late.

## Phase 1: Plugin Bootstrap, Core Loader, Service Container, Dependency Checks, Admin Menu, Settings Framework

## Goal

Establish the plugin foundation so modules can load safely, requirements can fail gracefully, settings can be stored consistently, and administrators have a basic control surface.

## Dependencies

- Existing plugin bootstrap file.
- Existing autoloader.
- Existing requirements and module loader concepts.
- Final architecture decisions in [architecture.md](architecture.md).

## Files Involved

- `alternatepro-elements.php`
- `includes/Autoloader.php`
- `includes/Plugin.php`
- `includes/Requirements.php`
- `includes/Modules.php`
- `includes/Helpers.php`
- `includes/Activation.php`
- `includes/Upgrades.php`
- `includes/Capabilities.php`
- `includes/Settings/SettingsRepository.php`
- `includes/Settings/SettingsSanitizer.php`
- `includes/Admin/Admin.php`
- `includes/Admin/SettingsPage.php`
- `includes/Admin/Notices.php`
- `includes/Admin/Diagnostics.php`
- `assets/css/admin.css`
- `assets/js/admin.js`
- `uninstall.php`

## Classes Involved

- `AlternatePro\Elements\Plugin`
- `AlternatePro\Elements\Requirements`
- `AlternatePro\Elements\Modules`
- `AlternatePro\Elements\Activation`
- `AlternatePro\Elements\Upgrades`
- `AlternatePro\Elements\Capabilities`
- `AlternatePro\Elements\Settings\SettingsRepository`
- `AlternatePro\Elements\Settings\SettingsSanitizer`
- `AlternatePro\Elements\Admin\Admin`
- `AlternatePro\Elements\Admin\SettingsPage`
- `AlternatePro\Elements\Admin\Notices`
- `AlternatePro\Elements\Admin\Diagnostics`

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

## Phase 2: Elementor Integration, Widget Registration System, Widget Category Registration

## Goal

Create the Elementor integration layer that safely detects Elementor Free, registers the plugin widget category, and provides the registration system used by all future widgets.

## Dependencies

- Phase 1 core loader.
- Phase 1 settings framework.
- Elementor Free installed and active for validation.
- Confirmed Elementor public hooks from Phase 0 validation.

## Files Involved

- `includes/Elementor/ElementorService.php`
- `includes/Elementor/WidgetCategory.php`
- `includes/Elementor/PreviewContext.php`
- `includes/Widgets/WidgetsModule.php`
- `includes/Widgets/BaseWidget.php`
- `includes/Assets/Assets.php`
- `includes/Assets/EditorAssets.php`
- `includes/Assets/FrontendAssets.php`
- `assets/css/editor.css`
- `assets/js/editor.js`
- `assets/css/widgets.css`
- `assets/js/widgets.js`

## Classes Involved

- `AlternatePro\Elements\Elementor\ElementorService`
- `AlternatePro\Elements\Elementor\WidgetCategory`
- `AlternatePro\Elements\Elementor\PreviewContext`
- `AlternatePro\Elements\Widgets\WidgetsModule`
- `AlternatePro\Elements\Widgets\BaseWidget`
- `AlternatePro\Elements\Assets\Assets`
- `AlternatePro\Elements\Assets\EditorAssets`
- `AlternatePro\Elements\Assets\FrontendAssets`

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

## Phase 3: Theme Builder Foundation, Template Post Type, Template Assignment

## Goal

Build the template storage foundation for the Theme Builder, including the `apro_template` post type, template metadata, status, priority, and admin assignment UI.

## Dependencies

- Phase 1 core loader, settings, capabilities, and admin notices.
- Phase 2 Elementor integration for editing templates with Elementor.
- Existing header/footer template data and metadata review.

## Files Involved

- `includes/ThemeBuilder/ThemeBuilderModule.php`
- `includes/ThemeBuilder/PostType.php`
- `includes/ThemeBuilder/TemplateTypes.php`
- `includes/ThemeBuilder/TemplateRepository.php`
- `includes/ThemeBuilder/MetaBox.php`
- `includes/ThemeBuilder/AdminColumns.php`
- `includes/ThemeBuilder/Preview.php`
- `includes/ThemeBuilder/Compatibility/ElementorProDetector.php`
- `includes/Upgrades.php`
- `assets/css/admin.css`
- `assets/js/admin.js`

## Classes Involved

- `AlternatePro\Elements\ThemeBuilder\ThemeBuilderModule`
- `AlternatePro\Elements\ThemeBuilder\PostType`
- `AlternatePro\Elements\ThemeBuilder\TemplateTypes`
- `AlternatePro\Elements\ThemeBuilder\TemplateRepository`
- `AlternatePro\Elements\ThemeBuilder\MetaBox`
- `AlternatePro\Elements\ThemeBuilder\AdminColumns`
- `AlternatePro\Elements\ThemeBuilder\Preview`
- `AlternatePro\Elements\ThemeBuilder\Compatibility\ElementorProDetector`
- `AlternatePro\Elements\Upgrades`

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
- Upgrade routine can preserve or migrate existing header/footer metadata if required.

## Phase 4: Conditions Engine

## Goal

Implement the reusable Conditions Engine that evaluates where templates should render.

## Dependencies

- Phase 3 template post type and metadata.
- Phase 1 capabilities and sanitization patterns.
- WordPress request context and conditional tags.

## Files Involved

- `includes/Conditions/ConditionsModule.php`
- `includes/Conditions/ConditionRegistry.php`
- `includes/Conditions/ConditionDefinitions.php`
- `includes/Conditions/ConditionSanitizer.php`
- `includes/Conditions/ConditionEvaluator.php`
- `includes/ThemeBuilder/MetaBox.php`
- `includes/ThemeBuilder/AdminColumns.php`
- `includes/ThemeBuilder/TemplateResolver.php`
- `assets/js/admin.js`
- `assets/css/admin.css`

## Classes Involved

- `AlternatePro\Elements\Conditions\ConditionsModule`
- `AlternatePro\Elements\Conditions\ConditionRegistry`
- `AlternatePro\Elements\Conditions\ConditionDefinitions`
- `AlternatePro\Elements\Conditions\ConditionSanitizer`
- `AlternatePro\Elements\Conditions\ConditionEvaluator`
- `AlternatePro\Elements\ThemeBuilder\MetaBox`
- `AlternatePro\Elements\ThemeBuilder\AdminColumns`
- `AlternatePro\Elements\ThemeBuilder\TemplateResolver`

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

## Phase 5: Dynamic Data Engine

## Goal

Create the plugin-owned Dynamic Data Resolver system used by widgets and Theme Builder contexts. This phase does not require Elementor Pro-style Dynamic Tags UI.

## Dependencies

- Phase 2 Elementor preview context.
- Phase 3 template context.
- Phase 4 condition-aware request context.

## Files Involved

- `includes/DynamicData/DynamicDataModule.php`
- `includes/DynamicData/ResolverRegistry.php`
- `includes/DynamicData/ResolverContext.php`
- `includes/DynamicData/CoreResolvers.php`
- `includes/Elementor/PreviewContext.php`
- `includes/Widgets/BaseWidget.php`
- `includes/ThemeBuilder/Preview.php`

## Classes Involved

- `AlternatePro\Elements\DynamicData\DynamicDataModule`
- `AlternatePro\Elements\DynamicData\ResolverRegistry`
- `AlternatePro\Elements\DynamicData\ResolverContext`
- `AlternatePro\Elements\DynamicData\CoreResolvers`
- `AlternatePro\Elements\Elementor\PreviewContext`
- `AlternatePro\Elements\Widgets\BaseWidget`
- `AlternatePro\Elements\ThemeBuilder\Preview`

## Acceptance Criteria

- Resolver registry supports all v1.0 dynamic data keys.
- Resolver context can detect frontend request, current post, queried object, and Elementor preview fallback.
- Site Title resolver works.
- Site Logo resolver works with safe fallback.
- Page Title resolver works across pages, posts, archives, search, and 404 fallbacks.
- Post Title, Excerpt, Featured Image, Date, Author Name, and Categories resolve safely.
- Breadcrumbs resolver returns structured data suitable for safe rendering.
- Resolvers return data; widgets remain responsible for context-specific escaping.
- Missing context returns safe empty values or fallbacks.
- No Elementor Pro dynamic tag classes are required.

## Phase 6: Header/Footer Builder

## Goal

Render assigned Header and Footer templates on matching frontend requests using the Theme Builder resolver and Conditions Engine.

## Dependencies

- Phase 3 Theme Builder foundation.
- Phase 4 Conditions Engine.
- Phase 5 Dynamic Data Resolver system.
- Phase 2 Elementor frontend rendering integration.

## Files Involved

- `includes/ThemeBuilder/TemplateResolver.php`
- `includes/ThemeBuilder/TemplateRenderer.php`
- `includes/ThemeBuilder/TemplateRepository.php`
- `includes/ThemeBuilder/Compatibility/ClassicThemeAdapter.php`
- `includes/ThemeBuilder/Compatibility/BlockThemeAdapter.php`
- `includes/ThemeBuilder/Compatibility/ElementorProDetector.php`
- `templates/theme-builder-wrapper.php`
- `assets/css/frontend.css`
- `assets/js/frontend.js`

## Classes Involved

- `AlternatePro\Elements\ThemeBuilder\TemplateResolver`
- `AlternatePro\Elements\ThemeBuilder\TemplateRenderer`
- `AlternatePro\Elements\ThemeBuilder\TemplateRepository`
- `AlternatePro\Elements\ThemeBuilder\Compatibility\ClassicThemeAdapter`
- `AlternatePro\Elements\ThemeBuilder\Compatibility\BlockThemeAdapter`
- `AlternatePro\Elements\ThemeBuilder\Compatibility\ElementorProDetector`

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

## Phase 7: Single Post Builder

## Goal

Render assigned Single Post templates for individual blog posts.

## Dependencies

- Phase 6 rendering path.
- Phase 4 conditions for All Posts and Specific Post.
- Phase 5 dynamic post resolvers.
- WordPress main query context.

## Files Involved

- `includes/ThemeBuilder/TemplateResolver.php`
- `includes/ThemeBuilder/TemplateRenderer.php`
- `includes/ThemeBuilder/TemplateRepository.php`
- `templates/theme-builder-wrapper.php`
- `includes/DynamicData/CoreResolvers.php`
- `assets/css/frontend.css`

## Classes Involved

- `AlternatePro\Elements\ThemeBuilder\TemplateResolver`
- `AlternatePro\Elements\ThemeBuilder\TemplateRenderer`
- `AlternatePro\Elements\ThemeBuilder\TemplateRepository`
- `AlternatePro\Elements\DynamicData\CoreResolvers`

## Acceptance Criteria

- Single Post templates render for individual blog posts.
- All Posts condition matches single blog posts.
- Specific Post condition matches only the selected post.
- Page requests do not match Single Post templates.
- Custom post type singles are not promised in v1.0 unless naturally supported and tested.
- Post dynamic data resolves correctly inside the template.
- Original query context is preserved.
- Template output does not duplicate theme content.
- No private or draft post content is exposed.

## Phase 8: Archive Builder

## Goal

Render assigned Archive templates for supported archive contexts.

## Dependencies

- Phase 6 rendering path.
- Phase 4 conditions for All Categories and Specific Category.
- Phase 5 archive-aware dynamic data.

## Files Involved

- `includes/ThemeBuilder/TemplateResolver.php`
- `includes/ThemeBuilder/TemplateRenderer.php`
- `includes/ThemeBuilder/TemplateRepository.php`
- `templates/theme-builder-wrapper.php`
- `includes/DynamicData/CoreResolvers.php`
- `includes/Widgets/PostsWidget.php`
- `assets/css/frontend.css`

## Classes Involved

- `AlternatePro\Elements\ThemeBuilder\TemplateResolver`
- `AlternatePro\Elements\ThemeBuilder\TemplateRenderer`
- `AlternatePro\Elements\ThemeBuilder\TemplateRepository`
- `AlternatePro\Elements\DynamicData\CoreResolvers`
- `AlternatePro\Elements\Widgets\PostsWidget`

## Acceptance Criteria

- Archive templates render for supported post archive contexts.
- Category archive templates render for All Categories.
- Specific Category matches category archive pages only.
- Specific Category does not imply single posts in that category.
- Tag-specific, author-specific, and date-specific assignments are not v1.0 requirements.
- Archive page title resolves safely.
- Posts widget or default content strategy can display archive results.
- Pagination and query behavior are stable if included.
- No unexpected template renders on singular posts or pages.

## Phase 9: Search Builder

## Goal

Render assigned Search templates for search results pages.

## Dependencies

- Phase 6 rendering path.
- Phase 4 Search Results condition.
- Phase 5 page title and breadcrumb resolvers.
- Optional Posts widget support for search result output.

## Files Involved

- `includes/ThemeBuilder/TemplateResolver.php`
- `includes/ThemeBuilder/TemplateRenderer.php`
- `templates/theme-builder-wrapper.php`
- `includes/DynamicData/CoreResolvers.php`
- `includes/Widgets/SearchFormWidget.php`
- `includes/Widgets/PostsWidget.php`
- `assets/css/frontend.css`

## Classes Involved

- `AlternatePro\Elements\ThemeBuilder\TemplateResolver`
- `AlternatePro\Elements\ThemeBuilder\TemplateRenderer`
- `AlternatePro\Elements\DynamicData\CoreResolvers`
- `AlternatePro\Elements\Widgets\SearchFormWidget`
- `AlternatePro\Elements\Widgets\PostsWidget`

## Acceptance Criteria

- Search templates render only on search results requests.
- Search Results condition matches correctly.
- Search query text is escaped before output.
- Empty search and no-results states are safe.
- Search Form widget works inside Search templates.
- Posts output respects the current search query when used in search context.
- Template does not render on archives, posts, pages, or 404 requests.

## Phase 10: 404 Builder

## Goal

Render assigned 404 templates for not-found requests.

## Dependencies

- Phase 6 rendering path.
- Phase 4 404 Page condition.
- Phase 5 page title and breadcrumb resolvers.

## Files Involved

- `includes/ThemeBuilder/TemplateResolver.php`
- `includes/ThemeBuilder/TemplateRenderer.php`
- `templates/theme-builder-wrapper.php`
- `includes/DynamicData/CoreResolvers.php`
- `includes/Widgets/SearchFormWidget.php`
- `assets/css/frontend.css`

## Classes Involved

- `AlternatePro\Elements\ThemeBuilder\TemplateResolver`
- `AlternatePro\Elements\ThemeBuilder\TemplateRenderer`
- `AlternatePro\Elements\DynamicData\CoreResolvers`
- `AlternatePro\Elements\Widgets\SearchFormWidget`

## Acceptance Criteria

- 404 template renders only on not-found requests.
- 404 Page condition matches correctly.
- Template does not render on search, archive, post, page, or front page requests.
- Search Form widget can be used safely inside the 404 template.
- Page title and breadcrumbs return safe 404 fallbacks.
- HTTP 404 status remains intact.

## Phase 11: Elementor Widgets

## Goal

Build the v1.0 Elementor widget set on top of the registration, asset, dynamic data, and security foundations.

## Dependencies

- Phase 2 Elementor integration.
- Phase 5 Dynamic Data Resolver system.
- Phase 6 frontend asset strategy.
- Phase 7 through Phase 10 template contexts for theme widgets.

## Files Involved

- `includes/Widgets/WidgetsModule.php`
- `includes/Widgets/BaseWidget.php`
- `includes/Widgets/SiteLogoWidget.php`
- `includes/Widgets/SiteTitleWidget.php`
- `includes/Widgets/SearchFormWidget.php`
- `includes/Widgets/BreadcrumbsWidget.php`
- `includes/Widgets/ImageBoxWidget.php`
- `includes/Widgets/IconBoxWidget.php`
- `includes/Widgets/CallToActionWidget.php`
- `includes/Widgets/HeroSectionWidget.php`
- `includes/Widgets/TeamMemberWidget.php`
- `includes/Widgets/NavMenuWidget.php`
- `includes/Widgets/PostsWidget.php`
- `includes/DynamicData/CoreResolvers.php`
- `includes/Assets/FrontendAssets.php`
- `assets/css/widgets.css`
- `assets/js/widgets.js`

## Classes Involved

- `AlternatePro\Elements\Widgets\WidgetsModule`
- `AlternatePro\Elements\Widgets\BaseWidget`
- `AlternatePro\Elements\Widgets\SiteLogoWidget`
- `AlternatePro\Elements\Widgets\SiteTitleWidget`
- `AlternatePro\Elements\Widgets\SearchFormWidget`
- `AlternatePro\Elements\Widgets\BreadcrumbsWidget`
- `AlternatePro\Elements\Widgets\ImageBoxWidget`
- `AlternatePro\Elements\Widgets\IconBoxWidget`
- `AlternatePro\Elements\Widgets\CallToActionWidget`
- `AlternatePro\Elements\Widgets\HeroSectionWidget`
- `AlternatePro\Elements\Widgets\TeamMemberWidget`
- `AlternatePro\Elements\Widgets\NavMenuWidget`
- `AlternatePro\Elements\Widgets\PostsWidget`
- `AlternatePro\Elements\DynamicData\CoreResolvers`
- `AlternatePro\Elements\Assets\FrontendAssets`

## Acceptance Criteria

- Site Logo widget renders site or custom logo safely.
- Site Title widget renders with configurable tag and optional home link.
- Search Form widget is accessible and works on frontend.
- Breadcrumbs widget uses semantic breadcrumb markup.
- Image Box widget renders image, title, description, and link safely.
- Icon Box widget renders decorative or labeled icons safely.
- Call To Action widget renders content and links safely.
- Hero Section widget supports responsive layout and dynamic values.
- Team Member widget renders image, bio, and social links safely.
- Nav Menu ships only if keyboard, mobile, and submenu behavior pass accessibility validation.
- Posts widget ships only if query limits, escaping, pagination behavior, and reset behavior pass validation.
- Testimonial Carousel is not required for v1.0 unless explicitly promoted after validation.
- Every widget respects enable/disable settings.
- Every widget sanitizes settings and escapes output by context.
- Widget assets load only where needed.
- Editor preview and frontend output are acceptably consistent.

## Phase 12: QA, Performance, Security Review

## Goal

Harden the plugin for v1.0 release through compatibility, accessibility, performance, security, and regression testing.

## Dependencies

- All prior phases.
- Final supported WordPress, Elementor, PHP, and theme matrix.
- Release packaging requirements.

## Files Involved

- [architecture.md](architecture.md)
- [requirements.md](requirements.md)
- [testing-plan.md](../testing/testing-plan.md)
- [security-checklist.md](../reviews/security-checklist.md)
- [development-roadmap.md](development-roadmap.md)
- `readme.txt`
- `phpcs.xml`
- `.editorconfig`
- `uninstall.php`
- All implementation files created in Phases 1 through 11.

## Classes Involved

- All production classes.
- Test classes and test fixtures if test tooling is added.

## Acceptance Criteria

- Plugin passes PHP syntax checks.
- Plugin passes configured WordPress Coding Standards checks.
- Plugin passes PHP 8.1+ compatibility checks.
- Requirements checks work for supported and unsupported environments.
- Elementor inactive behavior is graceful.
- Elementor Pro active behavior is graceful.
- All v1.0 widgets render in editor and frontend.
- Header, Footer, Single Post, Archive, Search, and 404 templates render correctly in the supported matrix.
- Every v1.0 condition matches expected WordPress routes.
- Exclusions override inclusions.
- Priority resolves conflicts predictably.
- Dynamic data resolves correctly or shows safe fallbacks.
- Frontend assets are not loaded globally when unnecessary.
- Posts queries are bounded and reset correctly.
- Admin object searches are permission-protected and query-limited if implemented.
- No known high-severity security issues remain.
- No secrets appear in diagnostics.
- Accessibility checks pass for Search Form, Breadcrumbs, and all shipped widgets.
- Nav Menu is either accessible enough to ship or moved out of v1.0.
- Testimonial Carousel is either explicitly deferred or fully accessibility-tested.
- Deactivation and uninstall behavior match documented policy.
- Release notes clearly document v1.0 limitations.
