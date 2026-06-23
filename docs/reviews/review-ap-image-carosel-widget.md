# AP Image Carosel Widget Review

Date: 2026-06-22

## Summary

Reviewed the explicitly requested AP Image Carosel Elementor widget implementation. The widget uses Elementor Free public widget APIs, owns its PHP/JS/CSS rendering logic, uses locally vendored Owl Carousel assets, and declares frontend assets through widget dependencies instead of enqueueing Owl globally.

Targeted static validation passed:

- `php -l includes/Widgets/ImageCarouselWidget.php`
- `node --check assets/js/image-carousel.js`
- `vendor/bin/phpcs --standard=phpcs.xml includes/Widgets/ImageCarouselWidget.php assets/js/image-carousel.js assets/css/image-carousel.css`
- `git diff --check`

## Files Reviewed

- `includes/Widgets/ImageCarouselWidget.php`
- `includes/Widgets/WidgetsModule.php`
- `includes/Settings/SettingsRepository.php`
- `includes/Settings/SettingsSanitizer.php`
- `assets/js/image-carousel.js`
- `assets/css/image-carousel.css`
- `assets/vendor/owl-carousel/LICENSE`
- `assets/vendor/owl-carousel/owl.carousel.min.css`
- `assets/vendor/owl-carousel/owl.theme.default.min.css`
- `assets/vendor/owl-carousel/owl.carousel.min.js`
- `CHANGELOG.md`
- `docs/status.md`
- `docs/planning/widgets-list.md`
- `docs/releases/widget-progress.md`

Unrelated dirty worktree files such as Header/Footer module files and `.gitignore` were not reviewed for this widget report.

## Issues Found

### 1. Widget toggle setting is not enforced during registration

Severity: Medium

`image_carousel` was added to the settings defaults and sanitizer allowlist, but `WidgetsModule::register_widgets()` still registers `ImageCarouselWidget` unconditionally.

References:

- `includes/Settings/SettingsRepository.php:55`
- `includes/Settings/SettingsSanitizer.php:41`
- `includes/Widgets/WidgetsModule.php:154`
- `includes/Widgets/WidgetsModule.php:160`

Why this matters:

- `docs/planning/architecture.md` requires `WidgetsModule` to register enabled widgets only and respect widget toggles.
- `docs/planning/implementation-plan.md` Phase 11 requires every widget to respect enable/disable settings.
- Admin users can disable `image_carousel`, but the widget will still appear in Elementor.

### 2. Autoplay defaults on without a persistent user pause/stop control

Severity: Medium

The widget defaults `autoplay` to `yes`, and after the visible Pause button was removed there is no persistent keyboard-accessible pause/stop control rendered with the carousel. Reduced-motion users are protected in JS, and autoplay stops after nav/dot interaction, but users who do not have reduced motion enabled still receive automatic motion by default without a direct stop control.

References:

- `includes/Widgets/ImageCarouselWidget.php:256`
- `includes/Widgets/ImageCarouselWidget.php:264`
- `assets/js/image-carousel.js:146`
- `assets/js/image-carousel.js:163`

Why this matters:

- Widget development rules require accessibility validation before widgets ship.
- Automatic carousel motion should either be opt-in by default or provide a user-facing pause/stop mechanism when enabled.

## Required Fixes

1. Wire widget registration to the settings repository so `image_carousel` only registers when enabled. Prefer a small helper in `WidgetsModule` so existing and future widgets use the same toggle path.

2. Resolve autoplay accessibility before release. Acceptable options include defaulting autoplay to off, or rendering a keyboard-accessible pause/stop control only when autoplay is enabled and styling it so it does not conflict with the requested Elementor-style visual baseline.

## Recommendations

- Add a focused runtime or browser validation note for the widget once the local Elementor editor is available, covering editor search by `ap image carosel`, frontend asset loading only on widget pages, dots sizing/spacing, and responsive `Slides to Show` defaults.
- Keep the vendored Owl Carousel license with the assets, and record the exact Owl Carousel version in release notes before packaging.
- Consider a later shared carousel helper only if another carousel widget is added. The current one-widget implementation does not need extra abstraction yet.

## Verdict

PASS WITH MINOR FIXES
