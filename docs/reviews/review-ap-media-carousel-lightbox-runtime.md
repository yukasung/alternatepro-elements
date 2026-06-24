# AP Media Carousel Lightbox Runtime Review

Date: 2026-06-24
Reviewed commit: `b2b3bd2 Add AP Media Carousel lightbox runtime`

## Summary

Reviewed the AP Media Carousel lightbox runtime implementation against `docs/agents/review.md`, the project context, development rules, architecture baseline, and implementation plan.

The implementation stays within Elementor Free public APIs, keeps the behavior widget-scoped, updates the relevant documentation, and uses escaped PHP output for rendered attributes and markup. Targeted syntax, PHPCS, JS syntax, and diff whitespace checks passed.

## Files Reviewed

- `CHANGELOG.md`
- `assets/css/media-carousel.css`
- `assets/js/media-carousel.js`
- `docs/releases/widget-progress.md`
- `docs/status.md`
- `docs/widgets/ap-media-carousel-phase1.md`
- `includes/Widgets/MediaCarouselWidget.php`
- `task-board.md`

## Issues Found

1. Minor accessibility issue: the runtime creates a modal dialog with `aria-modal="true"` but does not trap keyboard focus inside the dialog.

   Reference: `assets/js/media-carousel.js:158`

   Impact: keyboard users can tab out of the open lightbox and reach controls behind the modal, which is inconsistent with the `aria-modal` contract.

2. Minor i18n issue: dynamically created lightbox UI strings are hard-coded in JavaScript.

   References: `assets/js/media-carousel.js:150`, `assets/js/media-carousel.js:160`, `assets/js/media-carousel.js:318`, `assets/js/media-carousel.js:330`

   Impact: labels such as `Close lightbox`, `Previous media`, `Next media`, `Media lightbox`, `Open video`, and `Video` cannot be translated through WordPress localization.

3. Minor UX issue: video slides without a video URL still render `.ap-media-carousel__play-icon`, and CSS gives that class a pointer cursor.

   References: `includes/Widgets/MediaCarouselWidget.php:1294`, `assets/css/media-carousel.css:69`

   Impact: an empty video item can display a control that appears clickable but has no runtime action.

## Required Fixes

- Add a small focus trap for the AP lightbox while open, including `Tab` and `Shift+Tab` handling between focusable elements inside `.ap-media-carousel-lightbox`.
- Localize runtime lightbox labels through widget data attributes or a localized script object instead of hard-coded JavaScript strings.
- Make the play icon cursor/action state reflect whether a video URL exists. Either hide the play icon for empty video URLs or apply pointer/click styling only to the button trigger variant.

## Follow-Up Fix Status

Fixed after review:

- Added lightbox focus trapping for `Tab` and `Shift+Tab`.
- Moved AP lightbox runtime labels to translated widget data attributes.
- Stopped rendering the video play icon when a video slide has no video URL.

Follow-up validation passed:

- PHP syntax, JS syntax, targeted PHPCS, and `git diff --check`.
- Playwright smoke checks for translated AP lightbox labels, focus trapping in both tab directions, YouTube iframe title labels, and unsupported video fallback link labels.

## Recommendations

- Add a focused browser regression for real pointer clicking on the centered overlay trigger, not only DOM-triggered clicks.
- If more AP widgets need lightbox behavior later, extract the runtime into a small shared widget utility after the second consumer exists.

## Validation

Passed:

- `php -l includes/Widgets/MediaCarouselWidget.php`
- `node --check assets/js/media-carousel.js`
- `vendor/bin/phpcs --standard=phpcs.xml includes/Widgets/MediaCarouselWidget.php assets/css/media-carousel.css assets/js/media-carousel.js`
- `git diff --check HEAD^ HEAD`

## Verdict

PASS WITH MINOR FIXES
