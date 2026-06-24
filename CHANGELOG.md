# Changelog

All notable changes to this project will be documented in this file.

The format is based on Keep a Changelog.

---

## [Unreleased]

### Added

* Composer configuration for PHP 8.1+, PSR-4 autoloading, and development tooling.
* EditorConfig rules for consistent project formatting across editors.
* PHPCS ruleset for WordPress Coding Standards and PHPCompatibilityWP.
* Pre-development audit report for Phase 1 readiness.
* Pre-development audit fixes confirming Phase 1 readiness.
* Project context document as the primary session startup entry point.
* Code Review Agent workflow for post-implementation reviews.
* Testing Agent workflow for post-review validation.
* Security Audit Agent workflow for post-testing security reviews.
* Done Workflow for task completion review, testing, security, documentation, and summary steps.
* Codex command reference for developer workflow prompts.
* Refactor Agent workflow for controlled maintainability and architecture improvements.
* Detailed phase documents generated from the implementation plan.
* Dashboard documentation structure for HTML project visualization.
* Phase 1 foundation implementation for bootstrap, service container, activation, upgrades, capabilities, settings, admin menu, diagnostics, and module toggles.
* Phase 1 foundation testing report with static and CLI validation results.
* Phase 1 browser-based WordPress admin validation results.
* Phase 1 foundation security review report with PASS verdict.
* Composer lockfile and installed development dependencies for project-local PHPCS tooling.
* UAE-style Header/Footer display rule builder with Display On and Do Not Display On rule rows.
* UAE-style Header/Footer Display On combobox options generated from public post types and taxonomies.
* Shared Header/Footer `RuleOptions` helper for rule labels, sanitization, and legacy condition compatibility.
* UAE-style chip/token picker with admin AJAX target search for Header/Footer specific display rules.
* UAE-style grouped target search results with minimum-character feedback for Header/Footer specific display rules.
* HFE/UAE-style theme compatibility wrappers for active Header/Footer template rendering.
* Local WordPress demo content for widget testing, including pages, posts, categories, tags, menus, placeholder featured images, and plugin custom post type samples.
* AP Site Logo Elementor widget foundation with Elementor Free registration, settings toggle support, and escaped placeholder output.
* AP Image Carosel Elementor widget with local Owl Carousel assets, gallery-based image selection, responsive carousel controls, and widget-scoped frontend loading.
* AP Media Carousel Elementor widget foundation with Elementor Free registration, settings toggle support, and escaped placeholder output.
* AP Slides Elementor widget foundation with Elementor Free registration, settings toggle support, and escaped placeholder output.

### Changed

* Added AP Slides initial Content controls: `Slides Name` and a `Slides` repeater with three default heading items.
* Added AP Slides slide repeater item tabs for Background, Content, and Style controls.
* Added AP Slides Elementor-style options for `Height`, `Title HTML Tag`, and `Description HTML Tag` using plugin-owned Elementor Free control definitions.
* Added AP Slides `Slider Options` controls for navigation, autoplay, pause behavior, loop, transition, speed, and content animation using plugin-owned Elementor Free control definitions.
* Added AP Slides Style tab controls for content width, padding, horizontal position, vertical position, text alignment, and text shadow using plugin-owned Elementor Free control definitions.
* Added AP Slides Title style controls for spacing, text color, and typography using plugin-owned Elementor Free control definitions.
* Added AP Slides Description style controls for spacing, text color, and typography using plugin-owned Elementor Free control definitions.
* Added AP Slides Button style controls for size, typography, border width, border radius, and normal/hover colors using plugin-owned Elementor Free control definitions.
* Added AP Slides Navigation style controls for arrows and pagination position, size, spacing, and colors using plugin-owned Elementor Free control definitions.
* Added AP Slides Advanced tab `AP Custom CSS` code editor with widget-scoped `selector` token support using plugin-owned Elementor Free control definitions.
* Added AP Site Logo Content options for site logo media, image resolution, caption, and link using Elementor Free controls.
* Added AP Site Logo Image style controls for alignment, width, max width, height, normal/hover opacity, normal/hover CSS filters, border type, border radius, and box shadow using Elementor Free controls.
* Applied the shared AP Custom CSS control to AP Site Logo with widget-scoped `selector` support.
* Added AP Media Carousel initial Content tab options for `Slides Name` and a `Slides` repeater shell with five default empty items, while keeping the Skin input deferred.
* Added AP Media Carousel repeater item controls for Type, Image, conditional Video Link, image Link, and conditional Custom URL using Elementor Free controls.
* Added AP Media Carousel inline Content tab carousel options for Effect, Slides Per View, Slides to Scroll, Height, and Width inside the Slides section.
* Added AP Media Carousel play icon overlay rendering for video slide thumbnails.
* Added AP Media Carousel Slides style options for space between, background color, border width, border radius, border color, and padding using Elementor-generated selectors.
* Added AP Media Carousel Navigation style options for arrows, pagination, and play icon controls without adding navigation rendering or JavaScript behavior.
* Updated AP Media Carousel default navigation arrow size to better match Elementor-style media carousel arrows.
* Updated AP Media Carousel default pagination dot spacing to better match Elementor-style media carousel dots.
* Added AP Media Carousel default Elementor placeholder image output with arrows, pagination markers, and widget-scoped frontend CSS.
* Fixed AP Media Carousel pagination by registering a widget-scoped frontend script and rendering arrow/dot controls as accessible buttons that move the carousel track.
* Moved AP Slides `AP Custom CSS` to the bottom of the Elementor Advanced tab with an editor-only panel ordering script.
* Extracted AP Custom CSS into a shared Elementor widget trait and shared editor asset for AP Slides, AP Menu, AP Image Carosel, and AP Site Logo reuse.
* Extracted shared widget setting parsing helpers for carousel-style widgets.
* Removed the AP Slides global `Background Color` control from Style > Slides; slide backgrounds remain configured per slide.
* Replaced AP Slides placeholder-only output with multi-slide output that renders title, description, and button text using escaped plugin-owned markup.
* Updated AP Slides to use locally vendored OwlCarousel2 v2.3.4 as the animation engine while keeping plugin-owned AP arrows, pagination, markup, and styling.
* Updated AP Image Carosel controls to match the requested Elementor-style option set: Carousel Name, Image Resolution, Slides to Show, Slides to Scroll, Image Stretch, Navigation, Link, Caption, and Additional Options.
* Added AP Image Carosel Image style controls for vertical alignment, image spacing mode, border type, and border radius.
* Refactored widget asset registration into per-widget helper methods while preserving existing asset handles and widget-scoped loading behavior.
* Replaced Header/Footer condition checkbox cards with UAE-style add/remove rule rows.
* Updated Header/Footer condition evaluation to support Blog Page, All Categories, Specific Category, UAE-style specific target tokens, and exclusion rules.
* Updated Header/Footer condition evaluation to support UAE-style All Singulars, Date Archive, Author Archive, post type archive, and taxonomy archive rules.
* Changed empty Header/Footer display rule sets to not render templates automatically.
* Moved Header/Footer template Display Conditions into a UAE-style `Display On` row below Type of Template.
* Forced the Header/Footer template settings metabox into the main editor column when user preferences previously placed it in the sidebar.
* Bumped the plugin schema version to remove legacy Header/Footer template language metadata.
* Bumped the plugin schema version to backfill legacy active Header/Footer templates with empty display conditions.
* Refined the Header/Footer specific target picker layout to match UAE-style selected chips, search focus state, and grouped dropdown results.
* Removed the inner border from the Header/Footer Display On target search input to better match the UAE plugin field styling.
* Removed link-style underlines from Header/Footer Display On target search results and added UAE-style result indentation.
* Updated Header/Footer template editing to use Elementor's active switch panel by default and hide the native WordPress content editor UI.
* Updated Header/Footer template preview handling so Elementor can load template previews through a queryable template URL and Elementor canvas template.
* Updated Header/Footer frontend rendering to replace the active theme header/footer template when a matching AlternatePro template is active.
* Updated AP Menu toggle button defaults to use Elementor Free eicons and normalize legacy saved Font Awesome default icons at render time.
* Updated AP Menu default text colors to use a darker Elementor-style baseline while preserving user color controls.
* Updated AP Menu mobile toggle and nested submenu behavior to use scale-based expand and collapse so items reveal together.
* Added AP Menu main menu divider style controls for divider style, width, height, and color.
* Updated AP Menu desktop submenu default font size and line height to match the Elementor-style dropdown baseline.
* Added AP Menu main menu pointer color controls for hover and active underline states.
* Refactored AP Menu frontend direct-child lookup to reuse a shared helper for class and link matching.
* Clarified v1.0 changelog scope by separating required, conditional, and deferred features.
* Updated EditorConfig PHP, JavaScript, and CSS indentation to align with WordPress Coding Standards.
* Standardized PHPCS configuration naming on `phpcs.xml`.
* Updated PHPCS configuration to support PSR-4 class filenames while retaining WordPress Coding Standards checks.
* Updated governance references to include `docs/context.md`.
* Enhanced Testing Agent workflow with functional, unit, integration, and regression testing strategy.
* Expanded Codex command quick workflow with start, implementation, review, testing, security, refactor, completion, next task, and status update steps.
* Updated plugin metadata and runtime checks to require PHP 8.1+.

### Fixed

* Fixed AP Image Carosel accessibility baseline by making autoplay opt-in instead of enabled by default.
* Fixed AP Slides autoplay accessibility baseline by making autoplay opt-in instead of enabled by default.
* Fixed AP Slides navigation arrow hover background so arrows remain transparent in hover, focus, and active states.
* Adjusted AP Slides inside navigation arrow edge spacing to better match the Elementor-style visual inset.
* Fixed AP Slides Content Animation option so the selected mode animates the visible `.owl-item.active` slide title, description, and button after OwlCarousel2 finishes the slide transition, while cleaning up the temporary slide animation state after completion.
* Refined AP Slides `Content Animation` Up mode so the content group starts below the slide viewport, moves upward smoothly into place, and fades child content in with a short stagger.
* Updated AP Slides content animation timing so slide content is hidden before OwlCarousel2 translate/drag movement starts, then revealed after the new slide is active.
* Updated AP Slides `Content Animation` hiding behavior so the outgoing slide content fades out without moving downward before slide movement.
* Slowed AP Slides content animation timing for a smoother 950ms entrance with wider title, description, and button stagger.
* Fixed AP Slides outgoing content visibility so AP arrow and dot controls hide content immediately before triggering OwlCarousel2 movement.
* Slowed AP Slides content animation reveal again to a smoother 1200ms entrance with wider 180ms and 360ms child stagger timing.
* Updated AP Slides `Content Animation` Left and Right modes to slide the content group in from outside the slide area with a slower 1500ms minimum reveal.
* Refined AP Slides `Content Animation` Down mode so the content group moves downward together from above while child content fades in with the staged reveal.
* Fixed AP Image Carosel Elementor registration so the widget is only registered when the `image_carousel` widget setting is enabled.
* Updated AP Image Carosel default navigation dot colors to use black active dots and gray inactive dots instead of inheriting the site accent color.
* Updated AP Image Carosel default navigation dot size and spacing to better match Elementor-style carousel pagination.
* Hid Theme Builder admin menu, tab, and links when the Header/Footer Builder module is disabled.
* Fixed Header/Footer template metabox interactivity by using the classic editor for the `apro_template` post type while keeping Elementor editing available.
* Added a schema migration that fills active Header/Footer templates with empty display conditions using an explicit `Entire Site` include rule.
* Replaced placeholder-only Header/Footer specific target behavior with real AJAX search/autocomplete, selected target chips, and token insertion.
* Fixed Header/Footer Display On target search so Posts and Pages are searched first and Pages are not dropped by the global result limit.
* Fixed Header/Footer Display On target search to include draft Posts and Pages for users with edit permissions.
* Fixed Elementor editor preview 404 errors for Header/Footer templates by making `apro_template` publicly queryable while blocking direct frontend access for non-editors.
* Fixed active Header/Footer templates not appearing on the frontend after being set to Active.
* Resolved remaining PHPCS findings after aligning PHPCS configuration with PSR-4 class filenames.

### Removed

* Removed the Header/Footer template Language setting from the template settings UI, admin columns, matching logic, page override labels, registered meta, and local database metadata.
* Removed Header/Footer template User Roles targeting from the template settings UI, condition matching, helper code, JavaScript, CSS, and legacy database metadata.
* Removed the Demo Content import/remove form from the AlternatePro admin UI after importing the local demo content.

### Security

* Added allowlist-based settings sanitization, Settings API persistence, capability checks, and read-only diagnostics output for Phase 1 foundation.
* Completed Phase 1 foundation security review with no required fixes.
* Completed Header/Footer User Roles removal security review with no required fixes.

### Planned

* Plugin Foundation
* Elementor Integration
* Theme Builder Foundation
* Conditions Engine
* Dynamic Tags Engine
* Header Builder
* Footer Builder
* Single Post Builder
* Archive Builder
* Search Builder
* 404 Builder
* Elementor Widgets
* QA and Security Review

---

## [1.0.0] - Planned

### Added

#### Required Features (v1.0)

##### Theme Builder

* Header Templates
* Footer Templates
* Single Post Templates
* Archive Templates
* Search Templates
* 404 Templates

##### Conditions System

* Entire Site
* Front Page
* Blog Page
* All Posts
* Specific Post
* All Pages
* Specific Page
* All Categories
* Specific Category
* Search Results
* 404 Page

##### Dynamic Data Resolvers

* Site Title
* Site Logo
* Page Title
* Post Title
* Post Excerpt
* Featured Image
* Post Date
* Author Name
* Categories
* Breadcrumbs

##### Required Widgets

* Site Logo
* Site Title
* Search Form
* Breadcrumbs
* Hero Section
* Call To Action
* Image Box
* Icon Box
* Team Member

#### Conditional Features

##### Conditional Widgets

These may ship in v1.0 only after accessibility, performance, and Elementor compatibility validation.

* Nav Menu
* Posts

#### Deferred Features

##### v1.5 Candidates

* Testimonial Carousel

---

## Changelog Rules

When a phase is completed:

1. Update [Unreleased].
2. Record newly added features.
3. Record changed features.
4. Record fixed issues.
5. Record removed features.
6. Record security improvements.

Use these categories:

### Added

New features, modules, widgets, templates, settings, or documentation milestones.

### Changed

Updates to existing behavior, architecture decisions, workflows, documentation, or supported requirements.

### Fixed

Bug fixes, compatibility corrections, documentation corrections, or resolved validation issues.

### Removed

Removed features, deprecated behavior, unsupported scope, or deleted documentation artifacts.

### Security

Security hardening, vulnerability fixes, capability checks, nonce improvements, sanitization, escaping, or review results.

---

## Documentation Policy

The changelog must be updated whenever:

* A phase is completed
* A widget is completed
* A major feature is added
* A security fix is added
* A breaking change is introduced
