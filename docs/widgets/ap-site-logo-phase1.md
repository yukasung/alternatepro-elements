# AP Site Logo Phase 1

Date: 2026-06-23

## Summary

AP Site Logo Phase 1 creates the Elementor Free widget foundation, the requested Content tab options, and the requested Image style options.

Implemented:

- Widget class: `AlternatePro\Elements\Widgets\SiteLogoWidget`
- Widget slug: `ap-site-logo`
- Widget title: `AP Site Logo`
- Elementor category: `AlternatePro Elements`
- Settings toggle key: `site_logo`
- Escaped placeholder output: `AP Site Logo Widget`
- Content section: `Site Logo`
- Media control: `Site Logo`
- Group control: `Image Resolution`
- Select control: `Caption`
- Select control: `Link`
- Style section: `Image`
- Responsive style controls: `Alignment`, `Width`, `Max Width`, `Height`
- Normal/Hover tabs: `Opacity`, `CSS Filters`
- Image frame controls: `Border Type`, `Border Radius`, `Box Shadow`
- Advanced section: `AP Custom CSS`
- Shared control: `AlternatePro\Elements\Controls\ApCustomCssControl`

## Files

- `includes/Widgets/SiteLogoWidget.php`
- `includes/Widgets/WidgetsModule.php`
- `docs/widgets/ap-site-logo-phase1.md`

## Widget Methods

- `get_name()`
- `get_title()`
- `get_icon()`
- `get_categories()`
- `get_keywords()`
- `register_controls()`
- `render()`
- `content_template()`

## Phase 1 Scope

The widget includes the requested foundation, follow-up Content options, and Image style options:

- Registers with Elementor through `WidgetsModule` when the `site_logo` widget setting is enabled.
- Uses the existing AlternatePro Elements Elementor widget category.
- Provides an Elementor panel title, icon, category, and search keywords.
- Adds the `Site Logo` media control with the current WordPress custom logo as the default when one exists.
- Adds `Image Resolution` with default `Full`.
- Adds `Caption` with default `None`.
- Adds `Link` with default `Site URL`.
- Renders the selected/default logo image wrapped in the configured link.
- Renders the configured caption when enabled.
- Adds Image style controls for alignment, width, max width, height, normal/hover opacity, normal/hover CSS filters, border type, border radius, and box shadow.
- Adds the shared `AP Custom CSS` Advanced tab control with widget-scoped `selector` support.
- Keeps `content_template()` empty.
- Falls back to the placeholder text `AP Site Logo Widget` only when no logo image URL is available.

## Deferred

The following items are intentionally not implemented in Phase 1:

- Widget CSS asset files.
- Widget JavaScript asset files.
- Additional style sections beyond the requested Image style controls.
- Advanced dynamic tag integration.

## Validation

Passed:

- `php -l includes/Widgets/SiteLogoWidget.php`
- `php -l includes/Widgets/WidgetsModule.php`
- `vendor/bin/phpcs --standard=phpcs.xml includes/Widgets/SiteLogoWidget.php includes/Widgets/WidgetsModule.php`
- `vendor/bin/phpcs --standard=phpcs.xml includes/Widgets/SiteLogoWidget.php includes/Controls/ApCustomCssControl.php`
- `node --check assets/js/custom-css-editor.js`
- `git diff --check`
- Static validation confirmed `ap-site-logo`, `AP Site Logo`, `Site Logo`, `Image Resolution`, `Caption`, `Link`, Image style controls, shared AP Custom CSS control usage, escaped fallback placeholder output, `SiteLogoWidget::class` registration through the `site_logo` key, and no widget CSS or JavaScript asset dependencies.
- Local WordPress runtime confirmed the Elementor Free group controls used by the Image style section are available: CSS Filters, Border, and Box Shadow.
- Runtime validation through local WordPress and Elementor confirmed the Elementor registration path returns `ap-site-logo`.
- Temporary Elementor frontend page validation confirmed `widgetType: ap-site-logo`, `.elementor-widget-ap-site-logo`, `.ap-site-logo`, the default Site URL link, and the default logo/placeholder image render on the page.
- Temporary Elementor frontend smoke validation confirmed AP Site Logo renders successfully with Image style settings saved in Elementor data.
- Temporary Elementor frontend smoke validation confirmed AP Site Logo shared AP Custom CSS renders inline CSS and replaces the `selector` token with the current widget wrapper selector.

Not completed:

- Live Elementor editor browser validation for panel search and manual drag/drop remains pending because creating a temporary administrator account for browser login was rejected by the sandbox approval layer.

## Issues

- The requested files `docs/product-overview.md`, `docs/architecture.md`, and `docs/coding-standards.md` do not exist in this checkout. The implementation used `PRODUCT.md`, `docs/planning/project-overview.md`, `docs/planning/architecture.md`, and `docs/development-rules.md` instead.
- Browser validation that requires an authenticated Elementor editor session remains pending for this phase.
