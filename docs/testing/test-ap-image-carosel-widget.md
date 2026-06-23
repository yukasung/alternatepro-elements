# AP Image Carosel Widget Test Report

Date: 2026-06-22

## Summary

Tested the AP Image Carosel Elementor widget after review fixes for widget toggle registration and autoplay accessibility. Targeted static validation passed, widget toggle behavior passed in an isolated PHP integration-style test, a public frontend smoke test confirmed carousel assets are not loaded globally on the home page, and Elementor editor browser testing confirmed widget search, drag/drop behavior, and the corrected autoplay default.

Verdict: PASS WITH MINOR ISSUES

## Functional Test Results

Passed:

- `php -l includes/Modules.php`
- `php -l includes/Widgets/WidgetsModule.php`
- `php -l includes/Widgets/ImageCarouselWidget.php`
- `node --check assets/js/image-carousel.js`
- `vendor/bin/phpcs --standard=phpcs.xml includes/Modules.php includes/Widgets/WidgetsModule.php includes/Widgets/ImageCarouselWidget.php includes/Settings/SettingsRepository.php includes/Settings/SettingsSanitizer.php assets/js/image-carousel.js assets/css/image-carousel.css`
- `git diff --check`
- Owl Carousel vendor files are present with `LICENSE`, CSS, theme CSS, and JavaScript files.
- `curl -I http://alternatepro.local/` returned `HTTP/1.1 200 OK`.
- Downloaded the home page and confirmed no `apro-image-carousel`, `apro-owl-carousel`, `owl.carousel`, or `image-carousel` asset references appear on a page that does not use the widget.
- Elementor editor search for `ap image carosel` returned the `AP Image Carosel` widget.
- Dragging `AP Image Carosel` into the editor canvas succeeded.
- The widget rendered its empty editor preview message: `Choose images to build this carousel.`
- The Content control panel opened as `Edit AP Image Carosel` and exposed the expected Image Carousel controls.
- The Style control panel opened and exposed Image controls for vertical align, spacing, border type, border radius, height, and object fit.
- Browser DOM inspection confirmed `input[data-setting=loop]` remains checked by default and `input[data-setting=autoplay]` is unchecked by default.

Partially validated:

- Responsive carousel behavior was statically covered through responsive controls and Owl responsive options, but was not browser-tested on a page containing the widget during this run.
- Autoplay browser interaction was not tested with selected gallery images because this run focused on Elementor editor controls and default state.

## Unit Test Requirements

Unit-style coverage is recommended for:

- `WidgetsModule` widget toggle registration behavior.
- `SettingsRepository::is_widget_enabled()` behavior with merged defaults and saved values.
- Carousel option normalization in `ImageCarouselWidget`, especially default slide counts, slides-to-scroll fallback, spacing fallback, navigation modes, and autoplay default.

Temporary isolated PHP test executed:

- `/private/tmp/ap-image-carousel-toggle-test.php`

Result:

```text
off=alternatepro-nav-menu
on=alternatepro-nav-menu,alternatepro-image-carousel
```

This confirms `ImageCarouselWidget` does not register when `image_carousel` is disabled and does register when enabled, without modifying the WordPress database.

## Integration Test Results

Passed:

- WordPress runtime smoke test loaded successfully once with `wp loaded 7.0`.
- Public frontend HTTP smoke test returned 200 OK.
- Home page asset smoke test confirms Image Carousel and Owl Carousel assets are not globally printed on the home page.
- Isolated production-class test confirms `WidgetsModule` respects the `image_carousel` admin widget toggle.
- Elementor editor opened for a temporary draft page.
- Temporary admin user and temporary test page were removed after testing; database cleanup confirmed zero remaining test pages and zero remaining test users.

Minor issue:

- Full WordPress class-level runtime checks that touched plugin or Elementor classes were inconclusive because the local WordPress database intermittently returned `Error establishing a database connection`. The basic `wp-load.php` smoke test and public HTTP smoke test did pass, so this appears to be a local runtime stability limitation rather than a confirmed widget regression.
- Elementor emitted unrelated console output while loading the editor: one `@elementor/editor-site-navigation` warning and a 404 for the Elementor Angie kit schema route.

Not completed:

- Frontend rendered carousel visual/responsive test on a page containing the widget.

## Regression Test Results

Passed:

- Targeted PHPCS for the changed widget, settings, module, CSS, and JS files passed.
- `git diff --check` passed.
- Homepage smoke test indicates the new widget assets are not loaded globally.

Post-refactor smoke test passed:

- `php -l includes/Widgets/WidgetsModule.php`
- `vendor/bin/phpcs --standard=phpcs.xml includes/Widgets/WidgetsModule.php`
- `git diff --check`
- Isolated PHP smoke confirmed Image Carousel asset dependencies still register after the `WidgetsModule` asset helper extraction.
- Isolated PHP smoke confirmed `image_carousel` disabled state registers only `alternatepro-nav-menu`, while enabled state registers `alternatepro-nav-menu,alternatepro-image-carousel`.
- `curl -I http://alternatepro.local/` returned `HTTP/1.1 200 OK`.
- Downloaded homepage HTML and confirmed no `apro-image-carousel`, `apro-owl-carousel`, `owl.carousel`, or `image-carousel` references appear on a page that does not use the widget.

Known existing issue:

- Full `vendor/bin/phpcs --standard=phpcs.xml` fails on pre-existing `assets/js/nav-menu.js` formatting and loop-condition findings: 46 errors and 50 warnings. This file is outside the AP Image Carosel implementation and was already known from previous widget validation.

## Risks

- Frontend carousel sizing, dots spacing, navigation controls, and responsive behavior still need browser validation on a page that contains the widget with images selected.
- Local WordPress database instability can produce false negatives for class-level runtime checks.
- No permanent PHPUnit suite exists yet for widget registration or carousel option normalization.

## Recommendations

- Add PHPUnit or isolated integration tests for `WidgetsModule` toggle behavior and settings merging.
- Add a browser test page with AP Image Carosel content so editor search, frontend rendering, responsive breakpoints, and asset loading can be validated repeatably.
- Keep full PHPCS failure tracked separately against `assets/js/nav-menu.js` so it does not mask regressions in newly changed files.

## Verdict

PASS WITH MINOR ISSUES
