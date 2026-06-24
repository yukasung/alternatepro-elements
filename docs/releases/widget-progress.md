# Widget Progress

This file tracks widget implementation progress once widget development begins.

## Current Status

Widget development has started with conditional and explicitly requested widgets.

## v1.0 Required Widgets

- Site Logo: Phase 1 skeleton plus requested Content, Image style, and shared AP Custom CSS options implemented - Elementor Free widget registration, widget class foundation, settings toggle key, `Site Logo` media control, `Image Resolution`, `Caption`, `Link`, default Site URL linking, responsive Image style controls for alignment, width, max width, height, normal/hover opacity, normal/hover CSS filters, border type, border radius, box shadow, shared Advanced tab `AP Custom CSS` support, escaped fallback placeholder output, and local runtime/frontend rendering validation. CSS files, JavaScript files, additional style sections, and advanced dynamic tag integration are intentionally deferred.
- Site Title: Not Started
- Search Form: Not Started
- Breadcrumbs: Not Started
- Image Box: Not Started
- Icon Box: Not Started
- Call To Action: Not Started
- Hero Section: Not Started
- Team Member: Not Started

## Conditional v1.0 Widgets

These widgets may ship in v1.0 only if validation confirms accessibility, performance, and Elementor compatibility.

- Nav Menu: In Progress - AP Menu widget foundation, scale-based responsive toggle and submenu animation, main menu divider/pointer styling, Elementor-style dropdown typography/styling, Elementor Free eicon toggle defaults, and shared AP Custom CSS support are under active validation.
- Posts: Not Started

## Explicitly Requested Widgets

- AP Image Carosel: Implemented - gallery-based Elementor Free widget using locally vendored Owl Carousel assets, Elementor-style content and image style controls, responsive carousel controls, captions, links, navigation modes, opt-in autoplay with hover/interaction pauses, shared AP Custom CSS support, and widget-scoped asset dependencies.
- AP Media Carousel: Phase 1 skeleton plus initial Content and Style tab options implemented - Elementor Free widget registration, widget class foundation, settings toggle key, `Slides Name` text control, `Slides` repeater with five default image items, per-item Type/Image/Video Link/image Link/Custom URL controls, inline Content tab carousel options for Effect, Slides Per View, Slides to Scroll, Height, and Width inside the Slides section, Content tab Additional Options for arrows, pagination, transition duration, opt-in autoplay, infinite loop, overlay icon/animation, image resolution, image fit, and lazy load, Slides style controls for space between, background color, border width, border radius, border color, and padding, Navigation style controls for arrows, pagination, and play icon styling with a 32px default arrow size and 10px default dot spacing, Overlay style controls for background color, text color, and responsive icon size, Lightbox style controls for color, UI color, UI hover color, and responsive video width, selected/default Elementor placeholder image output, working widget-scoped arrow controls and page-based pagination dots, opt-in autoplay/loop behavior, video thumbnail play icon overlays, optional image link rendering, full-slide hover overlay rendering for image items using Elementor Free `eicons`, widget-owned lightbox runtime for image overlays and video play icons with translated labels, focus trapping, Elementor-style AP header controls, YouTube/Vimeo iframe embeds, and unsupported video fallback links, and widget-scoped frontend CSS/JavaScript assets. Skin input, inline video rendering outside the lightbox, additional style sections, and Owl Carousel integration are intentionally deferred.
- AP Slides: Phase 1 skeleton plus initial content, slider option, style option, and Advanced tab `AP Custom CSS` properties implemented - Elementor Free widget registration, widget class foundation, settings toggle key, `Slides Name` text control, `Slides` repeater with three default heading items, `Height` responsive slider, `Title HTML Tag` and `Description HTML Tag` safe-tag selects, `Slider Options` controls for navigation, opt-in autoplay, pause behavior, loop, transition, speed, and content animation, Style tab controls for content width, padding, positioning, text alignment, text shadow, title spacing/color/typography, description spacing/color/typography, button size/typography/border/normal/hover colors, navigation arrows/pagination position, size, spacing, and color controls, the shared Advanced tab `AP Custom CSS` control with widget-scoped `selector` token support and editor-only bottom ordering, slide output with title, description, and button text, and interactive arrows/pagination dots powered by widget-scoped JavaScript/CSS with locally vendored OwlCarousel2 v2.3.4 used as the slide animation engine only. Elementor Pro code is not copied.

## v1.5 Candidate Widgets

- Testimonial Carousel: Deferred

## Update Rule

Update this file whenever a widget is started, completed, deferred, or moved between release scopes.
