# AP Slides Phase 1

Date: 2026-06-22

## Summary

AP Slides Phase 1 creates the Elementor Free widget foundation and the requested control properties for the widget panel.

Implemented:

- Widget class: `AlternatePro\Elements\Widgets\SlidesWidget`
- Widget slug: `ap-slides`
- Widget title: `AP Slides`
- Elementor category: `AlternatePro Elements`
- Settings toggle key: `slides`
- Content control section: `Slides`
- Text control: `Slides Name`
- Repeater control: `Slides`
- Responsive slider control: `Height`
- Select control: `Title HTML Tag`
- Select control: `Description HTML Tag`
- Content section: `Slider Options`
- Select control: `Navigation`
- Switcher controls: `Autoplay`, `Pause on Hover`, `Pause on Interaction`, `Infinite Loop`
- Number controls: `Autoplay Speed`, `Transition Speed (ms)`
- Select controls: `Transition`, `Content Animation`
- Style section: `Slides`
- Responsive slider control: `Content Width`
- Responsive dimensions control: `Padding`
- Responsive choose controls: `Horizontal Position`, `Vertical Position`, `Text Align`
- Group control: `Text Shadow`
- Style section: `Title`
- Slider control: `Spacing`
- Color control: `Text Color`
- Group control: `Typography`
- Style section: `Description`
- Slider control: `Spacing`
- Color control: `Text Color`
- Group control: `Typography`
- Style section: `Button`
- Select control: `Size`
- Slider controls: `Border Width`, `Border Radius`
- Button Normal tab controls: `Text Color`, `Background Type`, `Border Color`
- Button Hover tab controls: `Text Color`, `Background Type`, `Border Color`
- Style section: `Navigation`
- Arrows controls: `Position`, `Size`, `Color`
- Pagination controls: `Position`, `Space Between Dots`, `Size`, `Color`, `Active Color`
- Advanced section: `AP Custom CSS`
- Code control: `AP Custom CSS`
- Multi-slide render output with title, description, and button text.
- OwlCarousel2-backed animated navigation arrows and pagination dots.

## Files

- `includes/Widgets/SlidesWidget.php`
- `includes/Widgets/WidgetsModule.php`
- `includes/Settings/SettingsRepository.php`
- `includes/Settings/SettingsSanitizer.php`
- `includes/Controls/ApCustomCssControl.php`
- `assets/css/slides.css`
- `assets/js/slides.js`
- `assets/js/custom-css-editor.js`

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

The widget includes the requested Phase 1 foundation and follow-up control properties:

- `Slides Name` text field with the default value `Slides`.
- `Slides Name` is rendered as the `.ap-slides` wrapper `aria-label` with a `Slides` fallback.
- The widget renders configured slides with escaped title, escaped description, and escaped button text.
- Slide output uses the configured title and description HTML tag allowlists.
- Navigation arrows and pagination dots trigger OwlCarousel2 slide animation when the `Navigation` option is set to `Arrows`, `Dots`, or `Arrows and Dots`.
- `Slides` repeater with three default items:
  - `Slide 1 Heading`
  - `Slide 2 Heading`
  - `Slide 3 Heading`
- Each slide repeater item includes `Background`, `Content`, and `Style` tabs.
- Slide repeater `Background` tab controls:
  - `Color`
  - `Image`
- Slide repeater `Content` tab controls:
  - `Title`
  - `Description`
  - `Button Text`
  - `Link`
- Slide repeater `Style` tab controls:
  - `Custom`
  - `Horizontal Position`
  - `Vertical Position`
  - `Text Align`
  - `Content Color`
  - `Text Shadow`
- `Height` responsive slider with default `400px`.
- `Title HTML Tag` select with default `div`.
- `Description HTML Tag` select with default `div`.
- `Slider Options` section with plugin-owned Elementor-style controls:
  - `Navigation` select with default `Arrows and Dots`.
  - `Autoplay` switcher disabled by default so automatic motion is opt-in.
  - `Pause on Hover` switcher enabled by default.
  - `Pause on Interaction` switcher enabled by default.
  - `Autoplay Speed` number field with default `5000`.
  - `Infinite Loop` switcher enabled by default.
  - `Transition` select with default `Slide`.
  - `Transition Speed (ms)` number field with default `500`.
  - `Content Animation` select with default `Up`.
- `Slides` Style tab section with plugin-owned Elementor-style controls:
  - `Content Width` responsive slider with default `66%`.
  - `Padding` responsive dimensions control.
  - `Horizontal Position` responsive choose control with default `Center`.
  - `Vertical Position` responsive choose control with default `Middle`.
  - `Text Align` responsive choose control with default `Center`.
  - `Text Shadow` group control.
- `Title` Style tab section with plugin-owned Elementor-style controls:
  - `Spacing` slider with a default `28px`.
  - `Text Color` color picker with a default white value.
  - `Typography` group control with plugin-owned default preview typography.
- `Description` Style tab section with plugin-owned Elementor-style controls:
  - `Spacing` slider with a default `36px`.
  - `Text Color` color picker with a default white value.
  - `Typography` group control with plugin-owned default preview typography.
- `Button` Style tab section with plugin-owned Elementor-style controls:
  - `Size` select with default `Small`.
  - `Typography` group control with plugin-owned default preview typography.
  - `Border Width` slider with a default `2px`.
  - `Border Radius` slider with a default `3px`.
  - `Normal` tab with `Text Color`, `Background Type`, and `Border Color` using white text and border defaults.
  - `Hover` tab with `Text Color`, `Background Type`, and `Border Color`.
- `Navigation` Style tab section with plugin-owned Elementor-style controls:
  - `Arrows` heading.
  - `Position` select with default `Inside`.
  - `Size` responsive slider with default `44px`.
  - `Color` picker with a default white value.
  - `Pagination` heading.
  - `Position` select with default `Inside`.
  - `Space Between Dots` responsive slider with default `6px`.
  - `Size` responsive slider with default `8px`.
  - `Color` picker with a default translucent black value.
  - `Active Color` picker with a default black value.
- `AP Custom CSS` Advanced tab section with plugin-owned Elementor Free controls:
  - `AP Custom CSS` code editor using CSS language mode.
  - `selector` token support for scoping CSS to the current widget instance.
  - Shared control trait and editor-only panel ordering script move the `AP Custom CSS` section and code editor body to the bottom of the Advanced tab list, after Elementor's common controls.

The widget uses the locally vendored OwlCarousel2 v2.3.4 package as the AP Slides animation engine only. AP Slides keeps plugin-owned markup, arrows, pagination, styling, accessibility state, and Elementor Free controls. Transition, speed, autoplay, hover pause, interaction pause, and loop settings are mapped into sanitized OwlCarousel2 options; reduced-motion preferences disable autoplay and transition speed. No Elementor Pro code is copied. Style controls target the rendered AP Slides structure through Elementor's generated widget selectors. `AP Custom CSS` renders sanitized inline CSS for the current widget instance only when CSS is provided.

## Validation

Passed:

- PHP syntax checks for changed PHP files.
- Targeted PHPCS for changed PHP files.
- Direct all-plugin PHP syntax fallback with `rg --files ... | xargs php -l`.
- `git diff --check`.
- Runtime settings merge includes `"slides": true`.
- Elementor editor panel shows `AP Slides` under `AlternatePro Elements`.
- `AP Slides` can be dragged into a page.
- Elementor selected the dropped widget as `Edit AP Slides`.
- Earlier skeleton validation confirmed the canvas rendered the placeholder text `AP Slides Widget`.
- Elementor control panel shows `Slides Name` with default `Slides`.
- Elementor control panel shows three default repeater rows: `Slide 1 Heading`, `Slide 2 Heading`, and `Slide 3 Heading`.
- Repeater duplicate, remove, and `Add Item` controls are available.
- Elementor control panel shows `Height` with responsive device controls and default `400px`.
- Elementor control panel shows `Title HTML Tag` with default `div`.
- Elementor control panel shows `Description HTML Tag` with default `div`.
- Elementor control panel shows the `Slider Options` section.
- Elementor control panel shows `Navigation` default `Arrows and Dots`.
- Elementor control panel shows `Autoplay` disabled by default and enabled defaults for `Pause on Hover`, `Pause on Interaction`, and `Infinite Loop`.
- Elementor control panel shows `Autoplay Speed` default `5000` and `Transition Speed (ms)` default `500`.
- Elementor control panel shows `Transition` default `Slide` and `Content Animation` default `Up`.
- Elementor Style tab shows the `Slides` section.
- Elementor Style tab no longer includes a global `Background Color` control in the `Slides` section; slide backgrounds remain configured inside each repeater item's `Background` tab.
- Elementor Style tab shows `Content Width` default `66%`.
- Elementor Style tab shows `Padding`, `Horizontal Position`, `Vertical Position`, `Text Align`, and `Text Shadow` controls.
- Elementor Style tab shows the `Title` section.
- Elementor Style tab shows `Spacing`, `Text Color`, and `Typography` controls in the `Title` section.
- Earlier validation confirmed the preview placeholder included the title style target class `ap-slides__title`.
- Earlier validation confirmed the preview applied style defaults to the placeholder wrapper: `400px` minimum height, centered flex alignment, centered text, and 66% content width.
- Static validation for the `Description` Style tab controls passed PHP syntax, targeted PHPCS, and `git diff --check`.
- Static validation for the `Button` Style tab controls passed PHP syntax, targeted PHPCS, all-plugin PHP syntax fallback, and `git diff --check`.
- Static validation for the `Navigation` Style tab controls passed PHP syntax, targeted PHPCS, all-plugin PHP syntax fallback, and `git diff --check`.
- Static validation for the `AP Custom CSS` Advanced tab control passed PHP syntax, targeted PHPCS, all-plugin PHP syntax fallback, and `git diff --check`.
- Static validation for the AP Custom CSS section order update passed PHP syntax, targeted PHPCS, all-plugin PHP syntax fallback, and `git diff --check`.
- Static validation for the AP Custom CSS editor panel ordering script passed `node --check`, PHP syntax, targeted PHPCS, all-plugin PHP syntax fallback, and `git diff --check`.
- Static validation for the AP Custom CSS collapse body ordering fix passed `node --check`, PHP syntax, targeted PHPCS, all-plugin PHP syntax fallback, and `git diff --check`.
- Static validation for the AP Custom CSS collapsed-position fix passed `node --check assets/js/custom-css-editor.js` and `git diff --check`.
- Static validation for extracting AP Custom CSS into a shared widget trait passed PHP syntax, targeted PHPCS, `node --check assets/js/custom-css-editor.js`, and `git diff --check`.
- Static validation for applying the shared AP Custom CSS control to AP Slides, AP Menu, and AP Image Carosel passed PHP syntax, targeted PHPCS, and `node --check assets/js/custom-css-editor.js`.
- Static validation for rendering `Slides Name` as the AP Slides wrapper `aria-label` passed PHP syntax, targeted PHPCS, and `git diff --check`.
- Static validation for removing the global AP Slides Style tab `Background Color` control passed PHP syntax, targeted PHPCS, all-plugin PHP syntax fallback, and `git diff --check`.
- Static validation for the AP Slides slide repeater `Background`, `Content`, and `Style` tabs passed PHP syntax, targeted PHPCS, all-plugin PHP syntax fallback, and `git diff --check`.
- Static validation for rendering slide title, description, and button output passed PHP syntax, targeted PHPCS, all-plugin PHP syntax fallback, `node --check assets/js/custom-css-editor.js`, and `git diff --check`.
- Static validation for OwlCarousel2-backed navigation arrows and pagination dots passed PHP syntax, targeted PHPCS, all-plugin PHP syntax fallback, `node --check assets/js/slides.js`, `node --check assets/js/custom-css-editor.js`, and `git diff --check`.
- Playwright smoke validation loaded jQuery, the vendored OwlCarousel2 script, and the real `assets/js/slides.js` against AP Slides markup and confirmed next arrow, previous arrow, and pagination dot clicks update the Owl active index, AP visible slide state, and dot `aria-selected` state.
- Playwright smoke validation confirmed the `Fade` transition maps to OwlCarousel2 `animateIn` and `animateOut` classes owned by AP Slides.
- Static validation for the review fixes passed after extracting shared widget setting helpers and making AP Slides autoplay opt-in by default.
- Follow-up review passed after the AP Slides Owl animation findings were fixed.
- Follow-up Playwright smoke validation confirmed OwlCarousel2 initializes with autoplay disabled by default, arrows and dots update the active slide and dot `aria-selected` state, fade transition classes are applied, and reduced-motion mode disables autoplay, drag, and transition speed.
- Validation for the AP Slides arrow hover background fix passed after locking arrow hover, focus, and active background states to transparent; Playwright computed style testing confirmed hover background remains transparent even when a global `button:hover` rule is present.
- Static validation confirmed the `Content Animation` slider option now applies the selected frontend mode after OwlCarousel2 finishes the slide transition, targets the visible `.owl-item.active` slide first, animates title, description, and button with a short staged reveal, cleans up the temporary animation class after completion, disables animation for `None`, and keeps reduced-motion mode animation-free.

Notes:

- The shell does not currently expose `composer`, so `composer lint` could not run. Direct PHP linting was used as a fallback.
- Elementor logged the existing unrelated Angie REST schema 404 and editor-site-navigation warning during browser validation.
- Temporary validation page `232` and temporary user `codex_ap_slides` were deleted after testing.
- 2026-06-23 controls validation used temporary page `237` and temporary user `codex_ap_slides`; both were deleted after testing.
- 2026-06-23 options validation used temporary page `239` and temporary user `codex_ap_slides`; both were deleted after testing.
- 2026-06-23 slider options validation used temporary page `243` and temporary user `codex_ap_slides`; both were deleted after testing.
- 2026-06-23 style options validation used temporary page `244` and temporary user `codex_ap_slides`; both were deleted after testing.
- 2026-06-23 title style options validation used temporary page `246` and temporary user `codex_ap_slides`; both were deleted after testing.
- 2026-06-23 description style options browser validation is pending because creating a new temporary administrator account was rejected by the sandbox approval layer. Static validation passed.
- 2026-06-23 button style options browser validation is pending for the same temporary administrator account approval reason. Static validation passed.
- 2026-06-23 navigation style options browser validation is pending for the same temporary administrator account approval reason. Static validation passed.
- 2026-06-23 AP Custom CSS browser validation is pending for the same temporary administrator account approval reason. Static validation passed.

## Verdict

Phase 1 widget foundation and requested controls are implemented. AP Slides now renders configured slides with title, description, button, OwlCarousel2-backed animation, working arrows, and working pagination dots. Static validation and follow-up Playwright smoke validation passed for the latest animation review fixes; Elementor browser validation remains pending for controls added after temporary administrator account creation was blocked by the sandbox approval layer.
