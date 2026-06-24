# AP Media Carousel Phase 1

Date: 2026-06-24

## Summary

AP Media Carousel Phase 1 creates only the Elementor Free widget foundation requested for the `ap-media-carousel` widget.

Implemented:

- Widget class: `AlternatePro\Elements\Widgets\MediaCarouselWidget`
- Widget slug: `ap-media-carousel`
- Widget title: `AP Media Carousel`
- Elementor category: `AlternatePro Elements`
- Settings toggle key: `media_carousel`
- Content section: `Slides`
- Text control: `Slides Name`
- Repeater: `Slides` with five default image items
- Repeater item controls: `Type`, `Image`, conditional `Video Link`, `Link`, and conditional `Custom URL`
- Inline carousel option controls inside `Slides`: `Effect`, `Slides Per View`, `Slides to Scroll`, `Height`, and `Width`
- Style section: `Slides`
- Style controls: `Space Between`, `Background Color`, `Border Width`, `Border Radius`, `Border Color`, `Padding`
- Style section: `Navigation`
- Navigation controls: arrows size/color, pagination position/spacing/size/color/active color, play icon color/size/shadow
- Overlay controls: background color, text color, and responsive icon size
- Lightbox controls: color, UI color, UI hover color, and responsive video width
- Lightbox runtime for image overlays and video play icons
- Default output: five Elementor placeholder images with working arrow controls and page-based pagination dots
- Video output: video slide thumbnails show a play icon overlay
- Frontend style asset: `assets/css/media-carousel.css`
- Frontend script asset: `assets/js/media-carousel.js`

## Files

- `includes/Widgets/MediaCarouselWidget.php`
- `includes/Widgets/WidgetsModule.php`
- `includes/Settings/SettingsRepository.php`
- `includes/Settings/SettingsSanitizer.php`
- `assets/css/media-carousel.css`
- `assets/js/media-carousel.js`
- `docs/widgets/ap-media-carousel-phase1.md`

## Widget Methods

- `get_name()`
- `get_title()`
- `get_icon()`
- `get_categories()`
- `get_keywords()`
- `get_style_depends()`
- `get_script_depends()`
- `register_controls()`
- `render()`
- `content_template()`

## Phase 1 Scope

The widget foundation:

- Registers with Elementor through `WidgetsModule` when the `media_carousel` widget setting is enabled.
- Uses the existing AlternatePro Elements Elementor widget category.
- Provides an Elementor panel title, icon, category, and search keywords.
- Registers the requested Content tab `Slides` section.
- Adds the `Slides Name` text control with a `Slides` default.
- Adds a `Slides` repeater with five default image items.
- Adds repeater item `Type` icon choices for `Image` and `Video`, with `Image` as the default.
- Adds repeater item `Image` media control with the Elementor placeholder image default for image and video items.
- Adds a conditional repeater item `Video Link` text control for video items with YouTube/Vimeo helper text.
- Adds repeater item `Link` select control with `None`, `Media File`, and `Custom URL` for image items.
- Adds a conditional `Custom URL` field for custom image slide links.
- Adds the requested carousel options inline inside the Content tab `Slides` section after the repeater.
- Does not create a separate collapsible `Carousel Options` section.
- Adds `Effect` with `Slide` and `Fade` options for the future Owl Carousel animation mode.
- Adds responsive `Slides Per View` and `Slides to Scroll` selects with `Default` and `1` through `6` options.
- Adds responsive `Height` and `Width` sliders.
- Adds the requested Content tab `Additional Options` section below `Slides`.
- Adds `Arrows`, `Pagination`, `Transition Duration`, `Autoplay`, `Infinite Loop`, `Overlay`, `Icon`, `Animation`, `Image Resolution`, `Image Fit`, and `Lazy Load` controls.
- Maps Additional Options into the frontend preview so arrows and dots can be hidden, transition timing can change, autoplay and loop are opt-in, a full image overlay with a centered Elementor Free `eicons` icon renders for image slides, selected image size is used when available, image fit updates through CSS, and lazy loading can be enabled.
- Does not add a `Skin` input yet.
- Adds the requested Style tab `Slides` section.
- Adds `Space Between` with a `10px` default.
- Adds `Background Color`, `Border Width`, `Border Radius`, `Border Color`, and `Padding` controls using Elementor-generated selectors.
- Adds the requested Style tab `Navigation` section.
- Adds arrow style controls for `Size` and `Color`, with a `32px` default arrow size.
- Adds pagination style controls for `Position`, `Space Between Dots`, `Size`, `Color`, and `Active Color`, with a `10px` default dot spacing.
- Adds play icon style controls for `Color`, `Size`, and `Shadow`.
- Adds the requested Style tab `Overlay` section below `Navigation`.
- Adds overlay style controls for `Background Color`, `Text Color`, and responsive `Icon Size`.
- Applies overlay background color to the full slide overlay layer and applies text color/icon size to the centered Elementor Free `eicons` overlay icon.
- Adds the requested Style tab `Lightbox` section below `Overlay`.
- Adds lightbox style controls for `Color`, `UI Color`, `UI Hover Color`, and responsive `Video Width`.
- Stores lightbox style values as AP Media Carousel CSS variables and applies them to the runtime lightbox.
- Adds a widget-owned lightbox runtime for image slides and video slides.
- Opens image slides from the AP overlay trigger and opens video slides from the play icon trigger.
- Converts supported YouTube and Vimeo URLs to iframe embed URLs in the lightbox and falls back to an external link for unsupported video URLs.
- Adds lightbox close, previous/next, Escape key, and keyboard arrow navigation behavior.
- Pauses AP Media Carousel autoplay while the lightbox is open and resumes it after close.
- Keeps `content_template()` empty.
- Renders five default Elementor placeholder images when no custom media is selected.
- Renders selected repeater images when provided.
- Wraps slide images when the repeater item link is set to `Media File` or `Custom URL`.
- Renders a play icon overlay on video slide thumbnails.
- Applies the `Slides Per View`, `Height`, and `Width` options to the static preview layout.
- Renders arrows and JavaScript-generated page dots as accessible widget-owned buttons.
- Adds a widget-scoped frontend CSS asset for the default preview layout.
- Adds a widget-scoped frontend JavaScript asset so arrows and page dots move the carousel track to real snap positions.
- Adds widget-scoped JavaScript support for Additional Options-driven arrows, dot pagination, transition duration, opt-in autoplay, and loop behavior.

## Deferred

The following items are intentionally not implemented in Phase 1:

- Additional image controls beyond the item image picker.
- Additional video controls beyond the item video link field.
- Inline video rendering outside the AP lightbox.
- Skin control.
- Additional carousel settings beyond the requested content options.
- Pause-on-hover and pause-on-interaction autoplay settings.
- Additional responsive carousel controls beyond the requested content options.
- Additional style sections beyond the requested Slides, Navigation, and Overlay style controls.
- Owl Carousel integration.

## Validation

Passed:

- `php -l includes/Widgets/MediaCarouselWidget.php`
- `php -l includes/Widgets/WidgetsModule.php`
- `php -l includes/Settings/SettingsRepository.php`
- `php -l includes/Settings/SettingsSanitizer.php`
- `node --check assets/js/media-carousel.js`
- `vendor/bin/phpcs --standard=phpcs.xml includes/Widgets/MediaCarouselWidget.php includes/Widgets/WidgetsModule.php includes/Settings/SettingsRepository.php includes/Settings/SettingsSanitizer.php assets/css/media-carousel.css assets/js/media-carousel.js`
- `vendor/bin/phpcs --standard=phpcs.xml includes/Widgets/MediaCarouselWidget.php assets/css/media-carousel.css assets/js/media-carousel.js`
- `git diff --check`
- Static validation confirmed `ap-media-carousel`, `AP Media Carousel`, `Slides Name`, `Slides`, the five default repeater items, item `Type`, item `Image`, conditional item `Video Link`, item `Link`, conditional item `Custom URL`, `Effect`, `Slides Per View`, `Slides to Scroll`, `Height`, `Width`, video thumbnail play icon output, the requested Slides and Navigation style controls, empty `content_template()`, `MediaCarouselWidget::class` registration through the `media_carousel` key, settings default support, sanitizer allowlist support, frontend style dependency support, and frontend script dependency support.
- Runtime validation through local WordPress and Elementor confirmed the Elementor registration path returns `ap-media-carousel`.
- Temporary Elementor frontend page validation confirmed `.elementor-widget-ap-media-carousel`, `.ap-media-carousel-widget-placeholder`, five default image placeholders, arrows, the pagination container, and `assets/css/media-carousel.css` render on the page.
- Temporary Elementor frontend validation confirmed the previous visible text placeholder `AP Media Carousel Widget` no longer renders.
- Runtime control-stack validation confirmed the Content tab `Slides` section, `Slides Name` text control, `Slides` repeater with five default items, and no `skin` control.
- Static control validation confirmed the Style tab `Slides` section and `Space Between`, `Background Color`, `Border Width`, `Border Radius`, `Border Color`, and `Padding` controls.
- Static control validation confirmed the Style tab `Navigation` section and the requested arrows, pagination, and play icon style controls.
- Static control validation confirmed the Style tab `Overlay` section and the requested overlay background color, text color, and icon size controls.
- Static control validation confirmed the Style tab `Lightbox` section and the requested lightbox color, UI color, UI hover color, and video width controls.
- Temporary Elementor frontend smoke validation confirmed the style selector target classes `.ap-media-carousel`, `.ap-media-carousel__slides`, and `.ap-media-carousel__slide` render with the placeholder.
- Static validation confirmed AP Media Carousel registers `assets/js/media-carousel.js`, renders arrow controls, leaves pagination dot generation to the widget script, and updates active pagination state and track transform through widget-scoped JavaScript.
- Static validation confirmed the Content tab `Additional Options` section and the `Arrows`, `Pagination`, `Transition Duration`, `Autoplay`, `Infinite Loop`, `Overlay`, `Icon`, `Animation`, `Image Resolution`, `Image Fit`, and `Lazy Load` controls.
- Static validation confirmed Additional Options are sanitized before frontend output and serialized for widget-scoped JavaScript behavior.
- Static validation confirmed Additional Options control frontend output for arrows, dot pagination, transition duration, opt-in autoplay, loop wrapping, overlay icon rendering, image size, image fit, and image loading mode.
- Static validation confirmed AP Media Carousel declares the Elementor Free `elementor-icons` style dependency and renders overlay icons with `eicon-search`, `eicon-plus-circle`, `eicon-eye`, and `eicon-link` classes.
- Playwright smoke validation against a temporary AP Media Carousel fixture confirmed Additional Options runtime behavior for generated page dots, hidden arrows/dots, transition duration, loop-enabled arrows, and opt-in autoplay timer creation.
- Playwright smoke validation against a temporary AP Media Carousel overlay fixture confirmed the overlay layer spans the full slide, uses the configured background/text/icon-size values, and fades in on hover.
- Playwright smoke validation against a temporary AP Media Carousel lightbox fixture confirmed image overlay trigger clicks open the AP lightbox, widget lightbox CSS variables are copied into the runtime lightbox, body scrolling is locked while open, Escape closes the lightbox, and lightbox content is cleared after close.
- Playwright smoke validation confirmed the full overlay layer uses `pointer-events: none` while the centered overlay trigger uses `pointer-events: auto`, so the visual overlay does not block existing image links.
- Playwright smoke validation against the same fixture confirmed video play icon clicks open the AP lightbox with a YouTube iframe embed URL and the configured `Video Width` variable.
- Playwright smoke validation loaded the real `assets/css/media-carousel.css` and `assets/js/media-carousel.js` against AP Media Carousel markup and confirmed five items with three visible slides generates three page dots: `Go to slide 1`, `Go to slide 2`, and `Go to slide 3`.
- Playwright smoke validation clicked pagination dot 3 and confirmed the track transform changed to `translate3d(-849px, 0px, 0px)`, dot index 2 was selected, and the next arrow became disabled at the last page.
- Playwright smoke validation clicked the next arrow from the first page and confirmed the active pagination index advanced to 1 with transform `translate3d(-425px, 0px, 0px)`.

Not completed:

- Live Elementor editor browser validation for panel search and manual drag/drop remains pending because it requires an authenticated administrator browser session. Creating a temporary administrator account for this validation was rejected by the sandbox approval layer because it requires explicit user authorization.

## Issues

- The requested files `docs/product-overview.md`, `docs/architecture.md`, and `docs/coding-standards.md` do not exist in this checkout. The implementation used `PRODUCT.md`, `docs/planning/project-overview.md`, `docs/planning/architecture.md`, and `docs/development-rules.md` instead.
- Browser validation that requires an authenticated Elementor editor session remains pending for this phase.
