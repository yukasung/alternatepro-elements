# Widgets List

## Purpose

This document defines the widget set for AlternatePro Elements and the expected behavior of each widget. [Architecture](architecture.md) is the source of truth for v1.0, v1.5, and v2.0 scope.

## Widget Category

All widgets should appear under an Elementor category named:

- AlternatePro Elements

## Common Widget Standards

Each widget should provide:

- Clear widget name, icon, and category.
- Content controls.
- Style controls.
- Responsive controls when layout, spacing, sizing, or visibility changes by device.
- Sanitized control values.
- Escaped frontend output.
- Editor preview support.
- Dynamic data support where relevant.
- Accessibility-first markup.
- No unnecessary frontend assets.

## v1.0 Required Widgets

- Site Logo
- Site Title
- Search Form
- Breadcrumbs
- Image Box
- Icon Box
- Call To Action
- Hero Section
- Team Member

## Conditional v1.0 Widgets

These may ship in v1.0 only if validation confirms accessibility, performance, and Elementor compatibility.

- Nav Menu
- Posts

## v1.5 Widget Candidate

- Testimonial Carousel

## Widget Details

## 1. Site Logo

### Purpose

Displays the WordPress custom logo with optional link behavior.

### Key Controls

- Logo source: site logo or custom image.
- Link: home URL, custom URL, or none.
- Image size.
- Alignment.
- Width controls.
- Alt text fallback.

### Dynamic Support

- Site Logo.

### Accessibility Notes

- Use meaningful alt text.
- If logo is linked to home, avoid duplicate ambiguous labels.

## 2. Site Title

### Purpose

Displays the WordPress site title.

### Key Controls

- HTML tag.
- Link to home.
- Alignment.
- Typography.
- Color.
- Spacing.

### Dynamic Support

- Site Title.

### Accessibility Notes

- Allow proper heading hierarchy by making the tag configurable.

## 3. Nav Menu

Status: Conditional v1.0.

### Purpose

Displays a WordPress navigation menu with responsive behavior.

### Key Controls

- Menu selection.
- Layout: horizontal, vertical, dropdown.
- Mobile breakpoint.
- Toggle icon.
- Submenu behavior.
- Alignment.
- Typography.
- Colors.
- Spacing.

### Dynamic Support

- Not required for v1.0.

### Accessibility Notes

- Keyboard navigation.
- ARIA-expanded for toggles.
- Focus trapping or focus management for mobile menus where needed.
- Visible focus states.

### Risk

Navigation accessibility and submenu behavior are high-risk areas and should be tested manually.

## 4. Search Form

### Purpose

Displays a WordPress search form.

### Key Controls

- Placeholder text.
- Button text or icon.
- Layout.
- Input size.
- Button style.
- Border and radius.
- Alignment.

### Dynamic Support

- Not required for v1.0.

### Accessibility Notes

- Provide accessible labels.
- Ensure button purpose is clear.

## 5. Hero Section

### Purpose

Provides a reusable hero block with headline, text, media/background, and calls to action.

### Key Controls

- Heading.
- Subheading or description.
- Background image or color.
- Overlay.
- Primary button.
- Secondary button.
- Height.
- Content alignment.
- Responsive spacing.

### Dynamic Support

- Page Title.
- Post Title.
- Post Excerpt.
- Featured Image.

### Accessibility Notes

- Maintain color contrast.
- Allow heading level control.

## 6. Call To Action

### Purpose

Displays a compact CTA block with title, description, media or icon, and button.

### Key Controls

- Title.
- Description.
- Image or icon.
- Button text.
- Button link.
- Layout.
- Hover styles.

### Dynamic Support

- Page Title.
- Post Title.
- Post Excerpt.
- Featured Image.

### Accessibility Notes

- Ensure link text is descriptive.
- Avoid making an entire complex card a single ambiguous link unless labels are clear.

## 7. Image Box

### Purpose

Displays an image with title, description, and optional link.

### Key Controls

- Image.
- Image size.
- Title.
- Description.
- Link.
- Layout.
- Image spacing.
- Typography and colors.

### Dynamic Support

- Featured Image.
- Post Title.
- Post Excerpt.

### Accessibility Notes

- Support alt text.
- Avoid duplicate linked content.

## 8. Icon Box

### Purpose

Displays an icon with title, description, and optional link.

### Key Controls

- Icon.
- Title.
- Description.
- Link.
- Icon position.
- Icon size.
- Icon color.
- Spacing.

### Dynamic Support

- Page Title.
- Post Title.

### Accessibility Notes

- Decorative icons should be hidden from assistive technology.
- Meaningful icons should provide labels.

## 9. Team Member

### Purpose

Displays a person profile with photo, name, role, biography, and social links.

### Key Controls

- Photo.
- Name.
- Position or role.
- Bio.
- Social links repeater.
- Layout.
- Image shape.
- Typography.

### Dynamic Support

- Not required for v1.0.

### Accessibility Notes

- Social links require accessible labels.
- Images need alt text.

## 10. Testimonial Carousel

Status: v1.5 candidate unless explicitly promoted after accessibility validation.

### Purpose

Displays testimonials in a carousel or slider.

### Key Controls

- Testimonials repeater.
- Author name.
- Author role.
- Author image.
- Testimonial text.
- Rating optional.
- Slides per view.
- Autoplay toggle.
- Navigation toggle.
- Pagination toggle.

### Dynamic Support

- Not required for v1.0.

### Accessibility Notes

- Pause autoplay.
- Keyboard-accessible controls.
- Announce slide controls.
- Avoid auto-advancing content without user control.

### Risk

Carousel behavior is accessibility-sensitive. Consider using a proven lightweight library or a carefully tested custom implementation.

## 11. Posts

Status: Conditional v1.0.

### Purpose

Displays posts with query controls and card layouts.

### Key Controls

- Post type: posts for v1.0.
- Include or exclude categories.
- Number of posts.
- Order and orderby.
- Columns.
- Image visibility.
- Excerpt visibility and length.
- Meta visibility.
- Read more link.
- Pagination optional.

### Dynamic Support

- Post Title.
- Post Excerpt.
- Featured Image.
- Post Date.
- Author Name.
- Categories.

### Accessibility Notes

- Use semantic article markup.
- Preserve heading hierarchy.
- Ensure pagination is keyboard accessible.

### Performance Notes

- Enforce query limits.
- Avoid unbounded queries.
- Reset post data after custom queries.

## 12. Breadcrumbs

### Purpose

Displays contextual breadcrumbs for site navigation.

### Key Controls

- Home label.
- Separator.
- Current item visibility.
- Link colors.
- Typography.
- Spacing.

### Dynamic Support

- Breadcrumbs.

### Accessibility Notes

- Use `nav` landmark semantics with breadcrumb labeling.
- Mark current item appropriately.

## Widget Build Order

Recommended order:

1. Site Logo
2. Site Title
3. Search Form
4. Breadcrumbs
5. Nav Menu
6. Image Box
7. Icon Box
8. Call To Action
9. Hero Section
10. Team Member
11. Posts
12. Testimonial Carousel, deferred to v1.5 unless explicitly promoted

This order builds simple infrastructure before complex queries and navigation. Carousel behavior is deferred by default because it carries higher accessibility risk.

## Assumptions

- Elementor Free widget registration APIs are available.
- Icons can use Elementor's existing icon control where possible.
- Complex assets for carousel and nav menu should be isolated.
- Widgets should not depend on Theme Builder being enabled, except where context improves dynamic values.

## Key Risks

- Nav Menu requires significant accessibility QA before v1.0 inclusion.
- Testimonial Carousel is deferred to v1.5 unless it passes accessibility validation early.
- Posts widget can become too broad if query controls are not limited for v1.0.
- Dynamic values may behave differently in editor preview and frontend routes.
- Too many style controls can slow development and clutter the editor.

## Implementation Phases

### Phase 1: Widget Framework

- Register widget category.
- Add shared base widget utilities.
- Add module setting checks.
- Add shared asset loading helpers.

### Phase 2: Foundational Widgets

- Build Site Logo, Site Title, Search Form, and Breadcrumbs.

### Phase 3: Content Widgets

- Build Image Box, Icon Box, Call To Action, Hero Section, and Team Member.

### Phase 4: Conditional And Deferred Widgets

- Build Nav Menu and Posts only if validation supports v1.0 inclusion.
- Defer Testimonial Carousel to v1.5 unless explicitly promoted.

### Phase 5: Polish

- Complete responsive controls.
- Complete accessibility pass.
- Complete visual and compatibility testing.
