# Project Status

## Single Source of Truth

This file is the official project status reference.

Mandatory process reference:

- [Project Context](context.md)
- [Done Workflow](done.md)
- [Development Rules](development-rules.md)

All future development sessions must:

1. Read docs/context.md first
2. Read docs/status.md
3. Verify current phase
4. Verify completed phases
5. Verify open issues
6. Verify next planned work

No development work should start without reviewing this file.

## Project Governance

Primary Governance Documents:

- [docs/context.md](context.md)
- [docs/done.md](done.md)
- [docs/status.md](status.md)
- [docs/index.md](index.md)
- [docs/development-rules.md](development-rules.md)
- [docs/planning/architecture.md](planning/architecture.md)
- [docs/planning/implementation-plan.md](planning/implementation-plan.md)
- [docs/phases/](index.md#phase-documents)
- [docs/dashboards/dashboard.html](dashboards/dashboard.html)

All development work must follow the rules defined in [docs/development-rules.md](development-rules.md).

No development session should begin without reviewing the governance documents.

## Project Information

- Project Name: AlternatePro Elements
- Current Version: 1.0.0 planned
- Current Phase: Phase 1 - Foundation
- Phase Status: In Progress
- Last Updated: 2026-06-23

## Overall Progress

Planning:

- Status: Completed
- Completion Percentage: 100%

Development:

- Status: In Progress
- Completion Percentage: 8%

Testing:

- Status: In Progress
- Completion Percentage: 8%

Release:

- Status: Not Started
- Completion Percentage: 0%

## Completed Phases

- 2026-06-15: Planning baseline completed. Created and organized project planning, requirements, architecture, implementation plan, review history, testing, security, and release documentation structure.

## Current Phase

Phase 1 - Foundation

## Phase Documentation

Detailed phase documents are stored in `docs/phases/` and are generated from [Implementation Plan](planning/implementation-plan.md).

- Current phase reference: [Phase 1 - Foundation](phases/phase-01-foundation.md)
- Phase 2 reference: [Elementor Integration](phases/phase-02-elementor-integration.md)
- Full phase navigation is available in [Documentation Index](index.md#phase-documents).

## Objective

Establish the plugin foundation so modules can load safely, requirements can fail gracefully, settings can be stored consistently, and administrators have a basic control surface.

## Scope

- Plugin Bootstrap
- Core Loader
- Service Container
- Dependency Checks
- Admin Menu
- Settings Framework

## Tasks

- Completed: Align plugin metadata and runtime checks with PHP 8.1+.
- Pending validation: Confirm WordPress and Elementor compatibility baselines.
- Completed: Define or refine the core loader and service container.
- Completed: Add dependency checks for PHP, WordPress, and Elementor.
- Completed: Add admin notices for missing or unsupported dependencies.
- Completed: Add admin menu structure.
- Completed: Add settings repository and settings sanitizer.
- Completed: Add module and widget toggle storage.
- Completed: Add read-only diagnostics foundation.
- Completed: Run Phase 1 security review.
- Completed: Run browser-based WordPress admin functional validation.

## Current Phase Progress

- Implemented Phase 1 foundation code for plugin bootstrap, service container, activation, upgrades, capabilities, settings, admin menu, diagnostics, and module toggles.
- PHP syntax checks pass for all plugin PHP files.
- Initial Phase 1 foundation code review finding was fixed and review verdict is PASS.
- Phase 1 testing report was created with PASS WITH MINOR ISSUES; PHP syntax, Composer validation, Composer lint, and WordPress runtime smoke validation pass.
- Composer dependencies are installed and `vendor/bin/phpcs` is available.
- PHPCS is aligned with the project's PSR-4 file naming policy and passes.
- Latest re-run confirms `composer lint` and `composer phpcs` both pass.
- Phase 1 security review was created with PASS verdict and no required fixes.
- Browser-based WordPress admin validation passed 22 of 22 checks for admin login, settings pages, diagnostics, module toggles, widget settings save, and Header/Footer Builder disabled behavior.
- Fixed Header/Footer template metabox interactivity by using the classic editor for `apro_template`.
- Metabox revalidation passed 8 of 8 checks for visibility, collapse/expand, condition checkbox toggling, template type selection, and Elementor edit button availability.
- Moved Header/Footer template display conditions into a UAE-style `Display On` row directly below `Type of Template`.
- Display Conditions layout validation passed 6 of 6 desktop browser checks for row order, label/control columns, condition card placement, and Elementor edit button availability.
- Forced the Header/Footer template settings metabox into the main editor column even when a user account previously saved it in the sidebar.
- Forced-position validation passed 6 of 6 browser checks and confirmed the metabox renders in `normal-sortables`.
- Implemented a UAE-style Header/Footer display rule builder with `Display On` and `Do Not Display On` rows.
- Added shared Header/Footer rule helpers for grouped rule options, sanitization, and legacy condition compatibility.
- Header/Footer condition evaluation now supports Blog Page, All Categories, Specific Category, UAE-style specific target tokens, and exclusion rows.
- Header/Footer Display On combobox options now follow UAE-style grouping for Basic, Special Pages, public post types, public taxonomies, and Specific Target.
- Header/Footer condition evaluation now supports UAE-style All Singulars, Date Archive, Author Archive, post type archive, and taxonomy archive rules.
- Static validation for the UAE-style rule builder passed: `composer lint`, `composer phpcs`, `node --check assets/js/header-footer-admin.js`, and `git diff --check`.
- Header/Footer rule builder code review was created with PASS WITH MINOR FIXES verdict.
- Removed the Header/Footer template Language setting from UI, resolver code, admin columns, registered meta, and page override labels.
- Added schema version `2` cleanup for legacy `_apro_language` post meta.
- Local database cleanup was verified with WP-CLI: schema version is `2` and `_apro_language` meta count is `0`.
- Added schema version `3` migration to backfill active Header/Footer templates with empty display conditions to an explicit `Entire Site` include rule.
- Static validation for the schema `3` migration passed: PHP syntax checks and PHPCS.
- Runtime WP-CLI verification for the schema `3` migration is pending because WordPress currently returns `Error establishing a database connection`.
- Implemented Header/Footer specific target AJAX search/autocomplete with UAE-style selected target chips and token insertion for searchable display rules.
- Refined the Header/Footer `Specific Target` picker to match UAE-style selected chip display, search focus behavior, minimum-character feedback, and grouped search results.
- Removed the inner border from the Header/Footer `Display On` target search input so only the outer UAE-style target picker container is framed.
- Removed link-style underlines from Header/Footer `Display On` target search results and added UAE-style indentation below group labels.
- Fixed Header/Footer `Display On` target search ordering so Posts and Pages are searched first, matching UAE behavior and preventing Pages from being dropped by a global result cap.
- Header/Footer `Display On` target search now includes draft Posts and Pages for users with the corresponding edit permissions.
- Removed Header/Footer template User Roles targeting from the settings UI, matching code, helper code, JavaScript, CSS, and legacy database metadata with schema version `4`.
- Header/Footer User Roles removal code review passed with no required fixes.
- Header/Footer User Roles removal testing passed with minor issues limited to unavailable browser/runtime validation while the local WordPress database is unavailable.
- Header/Footer User Roles removal security review passed with no required fixes.
- Header/Footer User Roles removal refactor review completed with no refactor needed.
- Header/Footer template edit screen now uses Elementor's active switch panel by default and hides the native WordPress content editor UI.
- Header/Footer templates keep `editor` and `elementor` post type support so Elementor Free can render its edit panel correctly.
- Header/Footer Elementor edit-panel update passed PHP syntax checks, JavaScript syntax check, PHPCS, Composer lint, Composer PHPCS, and `git diff --check`.
- Fixed Header/Footer Elementor preview 404 errors by making `apro_template` publicly queryable, loading Elementor's canvas template for template previews, and redirecting non-editors away from direct template frontend access.
- Header/Footer preview fix passed PHP syntax checks, JavaScript syntax check, PHPCS, Composer lint, Composer PHPCS, and `git diff --check`.
- HTTP validation confirms `?post_type=apro_template&p=142` now resolves to the canonical `?apro_template=header-2` URL instead of returning 404; unauthenticated direct access redirects to the home page.
- Fixed active Header/Footer templates not appearing on the frontend by adding an HFE/UAE-style theme compatibility layer that replaces active theme header/footer templates when a matching AlternatePro template is active.
- Header/Footer frontend rendering validation passed in headless Chrome: active Header template `142` with `Entire Website` condition outputs `.apro-header-footer-template--header` and Elementor content `Header 2` before the main content.
- Demo content was imported into the local WordPress database: 9 pages, 8 posts, 4 categories, 5 tags, 4 menus, 22 menu items, 4 placeholder images, and 2 plugin custom post type samples.
- Demo content duplicate prevention was verified by running the importer a second time with zero new records created.
- Demo content featured image coverage was verified for generated posts and pages.
- Demo Content admin form/tab was removed from the plugin UI after the local data import.
- Browser validation for the latest UAE-style rule builder and Elementor edit-panel layout remains pending.
- Browser validation for active Header/Footer frontend rendering passed after adding theme compatibility wrappers.
- Runtime baseline observed during admin validation: WordPress 7.0 and Elementor Free 4.1.3.
- Temporary validation user `codex_admin` was deleted after browser testing.
- AP Menu toggle button defaults now use Elementor Free eicons, with legacy saved Font Awesome default toggle icons normalized at render time.
- AP Menu default main and dropdown text colors now use a darker Elementor-style baseline while remaining overrideable from Style controls.
- AP Menu mobile toggle and nested submenus now use scale-based expand and collapse so menu items reveal together instead of the last item appearing late.
- AP Menu Main Menu divider styling now includes Elementor-style controls for divider style, width, height, and color.
- AP Menu desktop submenu default typography now uses a 13px font size with a 20px line height to match the Elementor-style dropdown baseline.
- AP Menu Main Menu pointer styling now includes hover and active pointer color controls for underline states.
- AP Menu refactor review was created with a recommended future control-registration split and a completed low-risk frontend helper cleanup.
- Added the explicitly requested AP Image Carosel Elementor widget using Elementor Free public APIs, locally vendored Owl Carousel assets, gallery-based image selection, responsive carousel controls, accessible control labels, and widget-scoped asset dependencies.
- Updated AP Image Carosel controls to match the requested Elementor-style option set while keeping original plugin-owned rendering logic and Owl Carousel behavior.
- Added AP Image Carosel Image style controls for vertical alignment, image spacing mode, border type, and border radius.
- Updated AP Image Carosel default navigation dot colors to use black active dots and gray inactive dots instead of inheriting the Elementor/site accent color.
- Updated AP Image Carosel default navigation dot size and spacing to better match Elementor-style carousel pagination.
- Fixed AP Image Carosel widget registration to respect the `image_carousel` admin widget toggle.
- Fixed AP Image Carosel autoplay accessibility baseline after browser validation by keeping Infinite Loop enabled by default while making Autoplay disabled by default in the live Elementor control panel.
- AP Image Carosel static validation passed PHP syntax checks, `node --check assets/js/image-carousel.js`, targeted PHPCS for changed PHP/CSS/JS files, and `git diff --check`; full project PHPCS remains blocked by existing `assets/js/nav-menu.js` formatting findings outside this widget change.
- AP Image Carosel browser testing report was updated with PASS WITH MINOR ISSUES verdict; Elementor editor search, drag/drop, content panel, style panel, and Autoplay default-off checks passed, while frontend visual testing with selected images remains pending.
- AP Image Carosel security review was created with PASS verdict and no required fixes.
- AP Image Carosel refactor review was created with NO REFACTOR NEEDED verdict after a low-risk `WidgetsModule` asset registration helper extraction passed targeted validation.
- AP Image Carosel post-refactor smoke test passed targeted `WidgetsModule` syntax/PHPCS checks, isolated asset and widget toggle checks, homepage HTTP 200 validation, and no-global-carousel-asset validation.
- Added the required AP Site Logo Phase 1 Elementor widget skeleton using Elementor Free public widget registration, the existing `site_logo` settings toggle key, and escaped placeholder output only.
- AP Site Logo Phase 1 validation passed PHP syntax, targeted PHPCS, `git diff --check`, static registration checks, local Elementor runtime registration, and temporary Elementor frontend placeholder rendering. Live editor panel/drag validation remains pending because temporary administrator account creation was rejected by the sandbox approval layer.
- Added AP Site Logo Content options for `Site Logo`, `Image Resolution`, `Caption`, and `Link`; temporary Elementor frontend validation confirmed `widgetType: ap-site-logo`, `.elementor-widget-ap-site-logo`, `.ap-site-logo`, default Site URL linking, and default logo/placeholder image rendering.
- Added AP Site Logo Style tab Image controls for alignment, width, max width, height, normal/hover opacity, normal/hover CSS filters, border type, border radius, and box shadow using Elementor Free controls without adding widget CSS or JavaScript asset dependencies.
- AP Site Logo Image style controls validation passed targeted PHP syntax, targeted PHPCS, static control/dependency checks, `git diff --check`, and temporary Elementor frontend smoke validation with saved Image style settings.
- Applied the shared AP Custom CSS control to AP Site Logo so it reuses the common Advanced tab section, bottom panel ordering, sanitization, `selector` token handling, and inline render helper.
- AP Site Logo shared AP Custom CSS validation passed targeted PHP syntax, targeted PHPCS, `node --check assets/js/custom-css-editor.js`, static trait usage checks, and temporary Elementor frontend smoke validation for inline CSS rendering.
- AP Site Logo shared AP Custom CSS testing report was created with PASS WITH MINOR ISSUES verdict; static, unit-style sanitizer, integration, frontend smoke, and regression checks passed, with live Elementor editor validation still pending because authenticated editor access is unavailable in the sandbox.
- AP Site Logo shared AP Custom CSS refactor review was created with NO REFACTOR NEEDED verdict after confirming the shared control reuse is appropriate and additional link/control abstractions would be premature.
- Added the required AP Media Carousel Phase 1 Elementor widget skeleton using Elementor Free public widget registration, a new `media_carousel` settings toggle key, and escaped placeholder output only.
- AP Media Carousel Phase 1 validation passed targeted PHP syntax, targeted PHPCS, `git diff --check`, static registration checks, local Elementor runtime registration, and temporary Elementor frontend placeholder rendering. Live editor panel/drag validation remains pending because it requires an authenticated administrator browser session, and creating a temporary administrator account requires explicit user authorization.
- Added AP Media Carousel initial Content tab options for `Slides Name` and a `Slides` repeater shell with five default empty items. The Skin input and Owl Carousel integration remain deferred.
- AP Media Carousel now renders the sanitized `Slides Name` setting as the carousel viewport `aria-label` with a `Slides` fallback.
- AP Media Carousel Content tab controls validation passed targeted PHP syntax, targeted PHPCS, `git diff --check`, static control checks, and local Elementor runtime control-stack smoke validation.
- Added AP Media Carousel Style tab `Slides` controls for space between, background color, border width, border radius, border color, and padding using Elementor-generated selectors without adding widget CSS or JavaScript assets.
- AP Media Carousel Slides style controls validation passed targeted PHP syntax, targeted PHPCS, `git diff --check`, static control checks, and temporary Elementor frontend smoke validation for the style selector target classes.
- Added AP Media Carousel Style tab `Navigation` controls for arrows size/color, pagination position/spacing/size/color/active color, and play icon color/size/shadow without adding JavaScript or Owl Carousel integration.
- AP Media Carousel Navigation style controls validation passed targeted PHP syntax, targeted PHPCS, `git diff --check`, and static control checks.
- Added AP Media Carousel default Elementor placeholder image output with five slides, arrow controls, page-based pagination dots, and widget-scoped frontend CSS while keeping Owl Carousel integration deferred.
- AP Media Carousel default image output validation passed targeted PHP syntax, targeted PHPCS for PHP/CSS, `git diff --check`, and temporary Elementor frontend smoke validation for five placeholder images, arrows, five pagination markers, and the `apro-media-carousel-css` asset.
- Fixed AP Media Carousel pagination by registering a widget-scoped frontend script, rendering arrows and pagination dots as accessible buttons, and moving the flex track from dot/arrow clicks.
- AP Media Carousel pagination fix validation passed targeted PHP syntax, targeted PHPCS for changed PHP/CSS/JS files, `node --check assets/js/media-carousel.js`, `git diff --check`, and Playwright smoke validation for dot and arrow clicks.
- Fixed AP Media Carousel pagination dot count and movement so dots are generated from real snap/page positions. Playwright confirmed five items with three visible slides renders three dots and the third dot moves to the last page.
- Added AP Media Carousel lightbox runtime for image overlays and video play icons, including image display, YouTube/Vimeo iframe embeds, lightbox UI controls, keyboard close/navigation, and autoplay pause while open. Validation passed targeted PHP syntax, targeted PHPCS for changed PHP/CSS/JS files, `node --check assets/js/media-carousel.js`, `git diff --check`, and Playwright smoke validation for image and video lightbox flows.
- Fixed AP Media Carousel lightbox review findings by adding focus trapping, moving lightbox runtime labels to translated widget data attributes, and hiding the video play icon when a video URL is empty. Validation passed targeted PHP syntax, targeted PHPCS, JS syntax checks, `git diff --check`, and Playwright smoke checks for translated labels, focus trapping, and unsupported video fallback behavior.
- Refined the AP Media Carousel lightbox close button so the control remains visible in the top-right header action group with a larger clickable area.
- Fixed AP Media Carousel lightbox close button focus color so the close icon stays visible over the default lightbox background.
- Adjusted AP Media Carousel lightbox video sizing so the Video Width control maps to viewport width with an 86vw Elementor-style default.
- Refined AP Media Carousel hover states so image overlays fade in with centered icons and video play buttons use an Elementor-style transparent play ring.
- Refined AP Media Carousel video play hover so hovering the play icon reveals the full-slide Elementor-style overlay while keeping inactive video play buttons visible.
- Refined AP Media Carousel video slide hover so hovering outside the play icon still reveals the full-slide Elementor-style overlay.
- Refined AP Media Carousel video play icon default state so it no longer uses a red background.
- Added AP Media Carousel lightbox header controls inspired by Elementor's slideshow header, including a counter plus share, zoom, fullscreen, and a close action using AP-owned markup and translated labels.
- Added the explicitly requested AP Slides Phase 1 Elementor widget skeleton using Elementor Free public widget registration, a settings toggle key, and escaped placeholder output only.
- AP Slides validation passed targeted PHP syntax checks, targeted PHPCS for changed PHP files, all-plugin PHP syntax fallback, `git diff --check`, runtime settings merge validation, and Elementor editor browser validation for panel visibility, drag/drop, and placeholder rendering.
- AP Slides review, testing, security, widget documentation, and task-board updates were created.
- Added AP Slides initial Content controls for `Slides Name` and a `Slides` repeater with three default heading rows matching the requested Elementor panel layout.
- AP Slides controls validation passed targeted PHP syntax, targeted PHPCS, `git diff --check`, and Elementor browser validation for `Slides Name`, default repeater rows, duplicate/remove controls, `Add Item`, and placeholder rendering.
- AP Slides now renders the `Slides Name` setting as the wrapper `aria-label` with a `Slides` fallback.
- Added AP Slides slide repeater item tabs for `Background`, `Content`, and `Style` controls using plugin-owned Elementor Free control definitions.
- Added AP Slides Elementor-style options for `Height`, `Title HTML Tag`, and `Description HTML Tag` using plugin-owned Elementor Free control definitions and a safe HTML tag allowlist.
- AP Slides options validation passed targeted PHP syntax, targeted PHPCS, `git diff --check`, and Elementor browser validation for the `400px` height default, responsive device toggles, `div` HTML tag defaults, and unchanged placeholder rendering.
- Added AP Slides `Slider Options` controls for navigation, autoplay, pause behavior, loop, transition, speed, and content animation using plugin-owned Elementor Free control definitions.
- AP Slides slider options validation passed targeted PHP syntax, targeted PHPCS, `git diff --check`, and Elementor browser validation for the `Arrows and Dots`, pause/loop `Yes`, `5000`, `Slide`, `500`, and `Up` defaults while leaving placeholder rendering unchanged. AP Slides Autoplay was later changed to disabled by default for accessibility.
- Added AP Slides Style tab controls for content width, padding, horizontal position, vertical position, text alignment, and text shadow using plugin-owned Elementor Free control definitions.
- AP Slides style options validation passed targeted PHP syntax, targeted PHPCS, Elementor browser validation for the Style tab `Slides` section, `66%` content width default, positioning/text controls, text shadow control, placeholder rendering, and default generated styles.
- Removed the AP Slides global Style tab `Background Color` control; slide backgrounds remain configured per slide in the repeater `Background` tab.
- Added AP Slides Title style controls for spacing, text color, and typography using plugin-owned Elementor Free control definitions.
- AP Slides title style validation passed targeted PHP syntax, targeted PHPCS, Elementor browser validation for the Style tab `Title` section, `Spacing`, `Text Color`, and `Typography` controls, plus placeholder rendering with the `ap-slides__title` target class.
- Added AP Slides Description style controls for spacing, text color, and typography using plugin-owned Elementor Free control definitions.
- AP Slides description style static validation passed targeted PHP syntax, targeted PHPCS, all-plugin PHP syntax fallback, and `git diff --check`; Elementor browser validation is pending because temporary administrator account creation was rejected by the sandbox approval layer.
- Added AP Slides Button style controls for size, typography, border width, border radius, and normal/hover text/background/border colors using plugin-owned Elementor Free control definitions.
- AP Slides button style static validation passed targeted PHP syntax, targeted PHPCS, all-plugin PHP syntax fallback, and `git diff --check`; Elementor browser validation remains pending for the same temporary administrator account approval reason.
- Added AP Slides Navigation style controls for arrows and pagination position, size, spacing, and color options using plugin-owned Elementor Free control definitions.
- AP Slides navigation style static validation passed targeted PHP syntax, targeted PHPCS, all-plugin PHP syntax fallback, and `git diff --check`; Elementor browser validation remains pending for the same temporary administrator account approval reason.
- Added AP Slides Advanced tab `AP Custom CSS` code editor with widget-scoped `selector` token support and sanitized inline CSS output.
- AP Slides AP Custom CSS static validation passed targeted PHP syntax, targeted PHPCS, all-plugin PHP syntax fallback, and `git diff --check`; Elementor browser validation remains pending for the same temporary administrator account approval reason.
- Moved AP Slides `AP Custom CSS` to the bottom of the widget control stack so it appears after Elementor common Advanced controls.
- Added an editor-only shared AP Custom CSS panel ordering script that places the `AP Custom CSS` section and code editor body as the bottom Advanced tab item.
- Applied the shared AP Custom CSS control to AP Slides, AP Menu, AP Image Carosel, and AP Site Logo.
- AP Slides now renders configured slides with escaped title, description, and button text, using the existing safe HTML tag controls and default Elementor-generated preview styling.
- AP Slides now renders interactive navigation arrows and pagination dots from the `Navigation` setting using widget-scoped JavaScript/CSS and the locally vendored OwlCarousel2 v2.3.4 dependency as the slide animation engine only, without copied Elementor Pro code.
- Fixed AP Slides Owl animation review findings by extracting shared widget setting parsing helpers for AP Slides and AP Image Carosel, and by making AP Slides Autoplay disabled by default so automatic motion is opt-in.
- AP Slides Owl animation follow-up review passed, and follow-up testing passed with only the saved Elementor editor validation still pending because temporary administrator account creation remains blocked by the sandbox approval layer.
- Fixed AP Slides arrow hover styling so navigation arrows keep a transparent background on hover, focus, and active states; Playwright computed style testing confirmed the AP rule wins over a global `button:hover` background.
- Adjusted AP Slides inside navigation arrow edge spacing so the visible chevrons sit closer to the slide edges in line with the Elementor-style reference.
- Fixed AP Slides `Content Animation` so the selected slider option targets the visible `.owl-item.active` slide, animates title, description, and button after OwlCarousel2 finishes the slide transition, then cleans up the temporary slide animation state after completion.
- Refined AP Slides `Content Animation` Up mode so the content group starts below the clipped slide area, moves upward smoothly into place, and fades the title, description, and button in with a short stagger.
- Updated AP Slides `Content Animation` timing so content is hidden before OwlCarousel2 starts translating or dragging to the next slide and revealed only after the new slide is active.
- Updated AP Slides `Content Animation` hiding behavior so outgoing content fades out without a downward movement before the slide transition.
- Slowed AP Slides `Content Animation` timing to use a smoother 950ms minimum reveal and wider title, description, and button stagger.
- Fixed AP Slides outgoing content visibility so AP arrow and dot controls hide content immediately before triggering OwlCarousel2 movement.
- Slowed AP Slides `Content Animation` reveal again to a smoother 1200ms minimum entrance with wider 180ms and 360ms child stagger timing.
- Updated AP Slides `Content Animation` Left and Right modes so the content group starts outside the clipped slide area and enters smoothly with a 1500ms minimum duration.
- Refined AP Slides `Content Animation` Down mode so the content group moves downward together from above and keeps the child title, description, and button opacity stagger.
- Phase 1 must not be marked complete until exact minimum version baselines, final release summary, dashboards, and acceptance criteria verification are complete.

## Dependencies

- [Architecture](planning/architecture.md)
- [Implementation Plan](planning/implementation-plan.md)
- [Phase 1 - Foundation](phases/phase-01-foundation.md)
- [Requirements](planning/requirements.md)
- Existing plugin bootstrap and autoloader.
- WordPress admin APIs.

## Risks

- Exact WordPress and Elementor Free compatibility baselines still need validation before Phase 1 completion.
- Elementor version checks must avoid fatal errors when Elementor is inactive.
- Service container design must remain simple enough for v1.0.
- Settings framework must avoid overbuilding import, reset, or advanced tooling in Phase 1.

## Next Phase

Phase 2 - Elementor Integration

## Planned Work

- Build Elementor integration layer.
- Register AlternatePro Elements widget category.
- Create widget registration system.
- Prepare editor and frontend asset registration.
- Confirm Elementor Free public hooks and compatibility.

## Expected Deliverables

- Elementor service foundation.
- Widget category registration.
- Widget module registration system.
- Base widget foundation.
- Elementor availability and version safeguards.

## Open Issues

## Current Implementation Issues

- Exact minimum supported WordPress and Elementor Free versions remain pending decisions.
- Header/Footer User Roles scope decision is resolved: User Roles targeting was removed from v1.0.
- Runtime verification for the schema `3` empty condition migration is pending until WordPress database connectivity is available again.
- Runtime verification for the schema `4` User Roles metadata cleanup is pending until WordPress database connectivity is available again.
- Browser validation for the newly implemented UAE-style Header/Footer rule builder, including absence of User Roles controls, remains pending.
- Active Header/Footer frontend output now passes browser validation for Hello Elementor using theme wrapper replacement.

## Pending Decisions

- Exact minimum supported WordPress version for v1.0.
- Exact minimum supported Elementor Free version for v1.0.
- Whether Nav Menu and Posts remain conditional v1.0 widgets after validation.
- Whether block theme support is documented as best effort or fully supported in v1.0.

## Technical Risks

- Elementor Free public APIs may not support all desired editor integrations.
- Elementor editor preview context may differ from frontend rendering context.
- Theme Builder rendering may vary across classic and block themes.
- Existing header/footer data may need an upgrade routine.

## Architecture Risks

- Overbuilding the service container or module system too early.
- Creating public extension hooks before the API is stable.
- Allowing Dynamic Data wording to drift back toward Elementor Pro parity.
- Expanding admin tools beyond safe v1.0 settings and diagnostics.

## Change Log

- 2026-06-15: Created initial planning documentation set.
- 2026-06-15: Created architecture review and resolved scope, compatibility, and security risks.
- 2026-06-15: Promoted final architecture to the primary architecture document.
- 2026-06-15: Created implementation plan with 12 development phases.
- 2026-06-15: Reorganized documentation into `docs/planning/`, `docs/reviews/`, `docs/testing/`, and `docs/releases/`.
- 2026-06-15: Created project status tracker and updated documentation entry point.
- 2026-06-15: Created development rules as the mandatory project constitution.
- 2026-06-15: Added project governance references for mandatory development documents.
- 2026-06-15: Resolved documentation audit findings and aligned planning docs with architecture.
- 2026-06-15: Updated root README as the main repository entry point for developers.
- 2026-06-15: Created root CHANGELOG.md following Keep a Changelog format.
- 2026-06-15: Updated development rules with mandatory changelog and release management requirements.
- 2026-06-15: Created Composer configuration with PSR-4 autoloading and development tooling.
- 2026-06-15: Created PHPCS configuration for WordPress Coding Standards and PHPCompatibilityWP.
- 2026-06-15: Created EditorConfig configuration for consistent project formatting.
- 2026-06-15: Completed pre-development audit with PASS WITH MINOR FIXES verdict.
- 2026-06-15: Resolved pre-development audit findings with PASS verdict. Project ready for Phase 1 Foundation development.
- 2026-06-15: Created project context document as the primary session startup entry point.
- 2026-06-22: Added AP Slides Phase 1 Elementor widget skeleton and verification documentation.
- 2026-06-23: Added AP Slides initial content controls and Elementor editor validation notes.
- 2026-06-23: Added AP Slides wrapper `aria-label` output from the `Slides Name` setting.
- 2026-06-23: Added AP Slides slide repeater item tabs for Background, Content, and Style.
- 2026-06-23: Added AP Slides height and HTML tag options with Elementor editor validation notes.
- 2026-06-23: Added AP Slides slider options controls with Elementor editor validation notes.
- 2026-06-23: Added AP Slides style options controls with Elementor editor validation notes.
- 2026-06-23: Removed AP Slides global Style tab Background Color control.
- 2026-06-23: Added AP Slides title style controls with Elementor editor validation notes.
- 2026-06-23: Added AP Slides description style controls with static validation notes.
- 2026-06-23: Added AP Slides button style controls with static validation notes.
- 2026-06-23: Added AP Slides navigation style controls with static validation notes.
- 2026-06-23: Added AP Slides Advanced tab AP Custom CSS control with static validation notes.
- 2026-06-23: Moved AP Slides AP Custom CSS to the bottom of the Advanced control list.
- 2026-06-23: Added shared AP Custom CSS editor-only panel ordering script for bottom placement.
- 2026-06-23: Fixed AP Slides AP Custom CSS panel ordering so the code editor body moves with the bottom collapse section.
- 2026-06-23: Extracted AP Custom CSS into a shared Elementor widget trait for reuse by other AlternatePro widgets.
- 2026-06-23: Applied the shared AP Custom CSS control to AP Slides, AP Menu, and AP Image Carosel.
- 2026-06-23: Replaced AP Slides placeholder-only output with configured slide title, description, and button output.
- 2026-06-23: Added AP Slides interactive navigation arrows and pagination dots with widget-scoped vanilla JavaScript and CSS.
- 2026-06-23: Updated AP Slides navigation animation to use locally vendored OwlCarousel2 v2.3.4 as the slide animation engine while keeping AP-owned arrows, pagination, markup, and styling.
- 2026-06-23: Fixed AP Slides Owl animation review findings by adding shared widget setting parsing helpers and making AP Slides Autoplay opt-in by default.
- 2026-06-23: Completed AP Slides Owl animation follow-up review and testing after fixes.
- 2026-06-23: Removed AP Slides arrow hover background color.
- 2026-06-23: Adjusted AP Slides inside navigation arrow edge spacing.
- 2026-06-23: Refined AP Slides Content Animation to trigger a staged title, description, and button reveal after OwlCarousel2 marks the new slide active.
- 2026-06-23: Refined AP Slides Content Animation Up mode to move the content group upward from below the slide viewport for smoother entrance motion.
- 2026-06-23: Updated AP Slides Content Animation timing to hide content before OwlCarousel2 translate/drag movement and reveal content after translation completes.
- 2026-06-23: Updated AP Slides Content Animation hiding behavior so outgoing content fades out without moving downward.
- 2026-06-23: Slowed AP Slides Content Animation reveal timing for smoother motion.
- 2026-06-23: Fixed AP Slides outgoing content visibility during arrow and dot slide movement.
- 2026-06-23: Slowed AP Slides Content Animation reveal timing again for a smoother Elementor-style entrance.
- 2026-06-23: Updated AP Slides Content Animation Left and Right modes to enter from outside the slide area with slower timing.
- 2026-06-23: Refined AP Slides Content Animation Down mode to move the content group together from above.
- 2026-06-23: Added AP Site Logo Phase 1 Elementor widget skeleton.
- 2026-06-23: Added AP Site Logo Content options for site logo media, image resolution, caption, and link.
- 2026-06-23: Added AP Site Logo Image style options for alignment, sizing, opacity, CSS filters, border, radius, and shadow.
- 2026-06-23: Validated AP Site Logo Image style options with targeted static checks and a temporary Elementor frontend smoke test.
- 2026-06-23: Applied the shared AP Custom CSS control to AP Site Logo.
- 2026-06-23: Created AP Site Logo shared AP Custom CSS testing report with PASS WITH MINOR ISSUES verdict.
- 2026-06-23: Created AP Site Logo shared AP Custom CSS refactor review with NO REFACTOR NEEDED verdict.
- 2026-06-23: Added AP Media Carousel Phase 1 Elementor widget skeleton with settings toggle support and placeholder runtime validation.
- 2026-06-23: Added AP Media Carousel initial Content tab Slides options without adding the Skin input.
- 2026-06-23: Added AP Media Carousel Style tab Slides options without adding carousel CSS or JavaScript assets.
- 2026-06-23: Added AP Media Carousel Style tab Navigation options without adding navigation rendering or JavaScript behavior.
- 2026-06-24: Added AP Media Carousel default placeholder image output with arrows/dots and widget-scoped frontend CSS.
- 2026-06-24: Added AP Media Carousel repeater item controls for Type, Image, conditional Video Link, image Link, and conditional Custom URL, with selected image/link rendering.
- 2026-06-24: Added AP Media Carousel inline Content tab carousel options for Effect, Slides Per View, Slides to Scroll, Height, and Width inside the Slides section, with preview support for Slides Per View, Height, and Width.
- 2026-06-24: Added AP Media Carousel play icon overlay rendering for video slide thumbnails while keeping inline video playback deferred.
- 2026-06-24: Increased AP Media Carousel default navigation arrow size to 32px to better match Elementor-style media carousel arrows.
- 2026-06-24: Increased AP Media Carousel default pagination dot spacing to 10px to better match Elementor-style media carousel dots.
- 2026-06-24: Fixed AP Media Carousel pagination and arrow controls with widget-scoped JavaScript.
- 2026-06-24: Fixed AP Media Carousel pagination dot count and movement to use carousel page/snap positions.
- 2026-06-24: Added AP Media Carousel Content tab Additional Options for arrows, pagination, transition duration, opt-in autoplay, loop, overlay icon/animation, image resolution, image fit, and lazy load.
- 2026-06-24: Added AP Media Carousel Style tab Overlay controls for overlay background color, text color, and responsive icon size.
- 2026-06-24: Fixed AP Media Carousel overlay behavior so the overlay background covers the full slide and fades in on hover with a centered icon.
- 2026-06-24: Updated AP Media Carousel image overlay icons to use Elementor Free `eicons` classes with the `elementor-icons` frontend style dependency.
- 2026-06-24: Added AP Media Carousel Style tab Lightbox controls for color, UI color, UI hover color, and responsive video width.
- 2026-06-24: Added AP Media Carousel lightbox runtime for image overlays and video play icons with image display, YouTube/Vimeo iframe embeds, close/previous/next controls, keyboard close/navigation, and autoplay pause while open.
- 2026-06-24: Fixed AP Media Carousel lightbox review findings for modal focus trapping, translated runtime labels, and empty video play icon behavior.
- 2026-06-24: Refined AP Media Carousel lightbox close button header positioning and clickable area.
- 2026-06-24: Fixed AP Media Carousel lightbox close button focus color visibility over the default lightbox background.
- 2026-06-24: Adjusted AP Media Carousel lightbox video sizing to use an 86vw viewport-relative Video Width default.
- 2026-06-24: Refined AP Media Carousel image and video hover states to match Elementor-style overlay behavior.
- 2026-06-24: Refined AP Media Carousel video play hover so the play icon reveals a full-slide overlay state.
- 2026-06-24: Refined AP Media Carousel video slide hover so the full-slide overlay appears outside the play icon.
- 2026-06-24: Added AP Media Carousel lightbox header controls for counter, share, zoom, fullscreen, and a close action.
- 2026-06-15: Created Code Review Agent workflow for post-implementation reviews.
- 2026-06-15: Created Testing Agent workflow for post-review validation.
- 2026-06-15: Enhanced Testing Agent workflow with functional, unit, integration, and regression testing strategy.
- 2026-06-15: Created Security Audit Agent workflow for post-testing security reviews.
- 2026-06-15: Created Done Workflow for task completion review, testing, security, documentation, and summary steps.
- 2026-06-15: Created Codex command reference for developer workflow prompts.
- 2026-06-15: Created Refactor Agent workflow for controlled maintainability and architecture improvements.
- 2026-06-15: Created detailed phase documents from the implementation plan and added phase navigation references.
- 2026-06-15: Expanded Codex command quick workflow with start, implementation, review, testing, security, refactor, completion, next task, and status update steps.
- 2026-06-15: Created dashboard documentation structure for HTML project visualization.
- 2026-06-15: Implemented Phase 1 foundation code and moved Phase 1 status to In Progress.
- 2026-06-15: Created Phase 1 foundation code review report with PASS WITH MINOR FIXES verdict.
- 2026-06-15: Fixed Phase 1 Theme Builder menu/link visibility when Header/Footer Builder is disabled.
- 2026-06-15: Created Phase 1 foundation testing report with PASS WITH MINOR ISSUES verdict.
- 2026-06-15: Restored local WordPress database connectivity and updated Phase 1 testing notes with runtime smoke validation results.
- 2026-06-15: Installed Composer dependencies, generated composer.lock, and enabled project-local PHPCS tooling.
- 2026-06-15: Updated PHPCS configuration to support the project's PSR-4 file naming policy.
- 2026-06-15: Resolved remaining PHPCS findings and confirmed `composer phpcs` passes.
- 2026-06-15: Re-ran `composer lint` and `composer phpcs`; both checks pass.
- 2026-06-15: Created Phase 1 foundation security review report with PASS verdict and no required fixes.
- 2026-06-15: Completed browser-based WordPress admin validation for Phase 1 with 22 of 22 checks passing.
- 2026-06-15: Fixed Header/Footer template metabox interactivity and verified metabox controls with 8 of 8 browser checks passing.
- 2026-06-15: Moved Header/Footer template display conditions to a UAE-style Display On row and verified the layout with 6 of 6 browser checks passing.
- 2026-06-15: Forced the Header/Footer template settings metabox into the main editor column and verified the position with 6 of 6 browser checks passing.
- 2026-06-15: Implemented UAE-style Header/Footer display rule builder rows for Display On and Do Not Display On with shared rule sanitization and condition evaluation support.
- 2026-06-15: Created Header/Footer rule builder code review report with PASS WITH MINOR FIXES verdict.
- 2026-06-20: Removed Header/Footer template Language setting from UI, code, registered meta, and local database metadata.
- 2026-06-20: Implemented schema `3` migration to backfill active Header/Footer templates with empty display conditions to `Entire Site`.
- 2026-06-20: Implemented Header/Footer specific target AJAX search/autocomplete with UAE-style selected target chips and token insertion for searchable display rules.
- 2026-06-20: Refined Header/Footer Specific Target picker UI to match UAE-style chip-only selected state, search state, and grouped results.
- 2026-06-20: Removed the inner border from the Header/Footer Display On target search input to match UAE plugin styling.
- 2026-06-20: Removed link-style underlines from Header/Footer Display On target search results and added UAE-style result indentation.
- 2026-06-20: Fixed Header/Footer Display On target search ordering so Posts and Pages are searched first and Pages are not dropped by the global result cap.
- 2026-06-20: Updated Header/Footer Display On target search to include draft Posts and Pages for users with edit permissions.
- 2026-06-20: Removed Header/Footer User Roles targeting from v1.0 scope and added schema `4` cleanup for legacy `_apro_user_roles` metadata.
- 2026-06-20: Completed Header/Footer User Roles removal review, testing, security, refactor, and dashboard documentation updates.
- 2026-06-20: Generated WordPress sample content used in widget testing.
- 2026-06-20: Imported AlternatePro demo content into the local WordPress database and verified duplicate prevention.
- 2026-06-20: Removed the Demo Content import/remove form from the AlternatePro admin UI after completing the local demo content import.
- 2026-06-20: Updated Header/Footer template add/edit screens to use Elementor's active switch panel by default while hiding the native WordPress content editor UI.
- 2026-06-20: Fixed Header/Footer Elementor preview 404 errors by enabling queryable template previews with Elementor canvas and frontend access blocking for non-editors.
- 2026-06-20: Fixed active Header/Footer templates not appearing on the frontend by adding HFE/UAE-style theme compatibility wrappers and validating Header template output in headless Chrome.

## Status Rules

- `docs/status.md` must be updated at the end of every completed phase.
- `docs/index.md` must remain the master documentation navigation page.
- `docs/phases/` must stay aligned with `docs/planning/implementation-plan.md`.
- `docs/dashboards/` must remain a visualization layer only; Markdown files remain the source of truth.
- `docs/context.md` must be read first at the start of future development sessions.
- `docs/done.md` must be used after implementation, bug fix, refactor, or feature development work.
- `docs/development-rules.md` must be reviewed before future code changes.
- Future phase summaries must be stored in `docs/releases/`.
- Plugin source code changes must not be made as part of documentation-only tasks.
