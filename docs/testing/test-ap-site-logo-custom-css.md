# AP Site Logo Custom CSS Test Report

Date: 2026-06-23

## Summary

Tested the AP Site Logo widget updates, with emphasis on the shared `AP Custom CSS` integration, Image style controls, Elementor registration, frontend output, and regression coverage for widgets already using the shared control.

The available automated, static, WordPress runtime, and Elementor frontend smoke tests passed. Live Elementor editor validation remains pending because an authenticated editor session could not be created in the sandbox environment.

## Functional Test Results

Passed:

- `php -l includes/Widgets/SiteLogoWidget.php`
- `php -l includes/Controls/ApCustomCssControl.php`
- `php -l includes/Widgets/WidgetsModule.php`
- `node --check assets/js/custom-css-editor.js`
- `vendor/bin/phpcs --standard=phpcs.xml includes/Widgets/SiteLogoWidget.php includes/Controls/ApCustomCssControl.php includes/Widgets/WidgetsModule.php`
- Static assertions confirmed AP Site Logo:
  - Uses `ApCustomCssControl`.
  - Registers the shared `AP Custom CSS` control.
  - Renders shared custom CSS.
  - Includes the requested Image style control markers.
  - Registers through `WidgetsModule` with the `site_logo` widget key.
  - Does not add `get_style_depends()` or `get_script_depends()`.
- Temporary Elementor frontend page confirmed:
  - `ap-site-logo` is registered by `WidgetsModule`.
  - `.elementor-widget-ap-site-logo` renders.
  - `.ap-site-logo` renders.
  - Default Site URL link renders.
  - Shared `AP Custom CSS` renders inline CSS.
  - The `selector` token is replaced with the current widget wrapper selector.
  - The editor-only `custom-css-editor.js` script is not loaded on the frontend.
- Temporary validation pages were deleted after testing.

Not completed:

- Live Elementor editor panel, drag/drop, Image style controls, and `AP Custom CSS` section validation remain pending because temporary administrator account creation for browser login is blocked by the sandbox approval layer.

## Unit Test Requirements

No persistent PHPUnit test suite exists for this widget yet.

Unit-style runtime validation was performed for the shared AP Custom CSS sanitizer using an anonymous class with the same trait:

- Confirmed `selector` is replaced with `.elementor-element-{id}`.
- Confirmed safe CSS declarations remain.
- Confirmed risky CSS constructs are stripped:
  - `@import`
  - `url(...)`
  - `expression(...)`
  - `behavior`
  - `javascript:`
  - inline `<script>` tags

Recommended future PHPUnit coverage:

- Add focused tests for `ApCustomCssControl::prepare_ap_custom_css()`.
- Add widget registration tests for enabled and disabled widget settings.
- Add render tests for Site URL, custom URL, captions, and missing image fallback.

## Integration Test Results

Passed:

- WordPress runtime confirmed Elementor Free group controls used by AP Site Logo are available:
  - `\Elementor\Group_Control_Css_Filter`
  - `\Elementor\Group_Control_Border`
  - `\Elementor\Group_Control_Box_Shadow`
- `WidgetsModule::register_widgets()` registers `ap-site-logo` when the `site_logo` widget toggle is enabled.
- Elementor frontend smoke validation confirmed AP Site Logo can render from saved Elementor data with Image style settings and shared AP Custom CSS settings.
- Temporary WordPress validation pages were cleaned up successfully.

## Regression Test Results

Passed:

- Existing widgets that use the shared AP Custom CSS control still pass PHP syntax checks:
  - `includes/Widgets/SlidesWidget.php`
  - `includes/Widgets/ImageCarouselWidget.php`
  - `includes/Widgets/NavMenuWidget.php`
- `assets/js/custom-css-editor.js` passes JavaScript syntax validation.
- `git diff --check` passes.
- AP Site Logo does not add widget-specific frontend CSS or JavaScript dependencies.

## Risks

- Live Elementor editor validation is still pending. The frontend and runtime paths pass, but the visual editor panel cannot be fully confirmed until authenticated browser access is available.
- AP Custom CSS intentionally permits sanitized editor-authored CSS. This is expected behavior for users with Elementor editing capability, but it should remain limited to trusted editors.

## Recommendations

- Run browser-based Elementor editor validation once temporary admin access is available:
  - Confirm AP Site Logo appears in the panel.
  - Confirm widget drag/drop works.
  - Confirm Content controls render.
  - Confirm Style > Image controls render.
  - Confirm Advanced > AP Custom CSS appears at the bottom and stays in its collapse section.
- Add PHPUnit coverage for the shared AP Custom CSS sanitizer before broadening AP Custom CSS to more widgets.

## Verdict

PASS WITH MINOR ISSUES

The implementation is ready to continue, with the remaining issue limited to unavailable live Elementor editor validation in the current sandbox.
