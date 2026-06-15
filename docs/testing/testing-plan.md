# Testing Plan

## Purpose

This document defines the testing approach for AlternatePro Elements v1.0.

## Testing Goals

- Confirm the plugin works with Elementor Free.
- Confirm all required v1.0 widgets render correctly.
- Confirm conditional v1.0 widgets are either validated and shipped or explicitly deferred.
- Confirm Theme Builder templates resolve and render correctly.
- Confirm conditions behave predictably.
- Confirm dynamic values are context-aware.
- Confirm settings are secure and reliable.
- Confirm frontend output is responsive and accessible.

## Test Environments

Recommended matrix:

- Latest stable WordPress.
- Latest stable Elementor Free.
- PHP 8.1.
- PHP 8.2.
- PHP 8.3 or newer if supported by WordPress at implementation time.
- One default block theme.
- One default classic theme.
- One popular Elementor-friendly theme.

Optional compatibility:

- Elementor Pro active.
- Multisite.
- Object cache enabled.
- Pretty permalinks disabled and enabled.

## Automated Tests

### Unit Tests

Target:

- Condition classes.
- Template resolver.
- Dynamic resolvers.
- Settings sanitization.
- Helper methods.

### Integration Tests

Target:

- Plugin activation.
- Requirement checks.
- Template post type registration.
- Metadata save and load.
- Template resolution against WordPress query contexts.
- Admin settings save flow.

### Static Analysis And Standards

Recommended checks:

- PHP syntax checks.
- WordPress coding standards.
- PHP compatibility checks for PHP 8.1+.
- JavaScript linting if build tooling is introduced.
- CSS linting if build tooling is introduced.

## Manual QA

## Plugin Activation

Test:

- Activate with Elementor active.
- Activate with Elementor inactive.
- Activate with unsupported PHP in a controlled environment.
- Deactivate and reactivate.
- Uninstall behavior.

## Widgets

For every widget:

- Widget appears in Elementor.
- Controls save correctly.
- Frontend output matches editor preview.
- Responsive controls work.
- Empty states are safe.
- Output is escaped.
- No unexpected PHP notices.

## Theme Builder

Test:

- Create Header template.
- Create Footer template.
- Create Single Post template.
- Create Archive template.
- Create Search template.
- Create 404 template.
- Enable and disable templates.
- Change template priority.
- Edit templates with Elementor.
- Confirm no duplicate output.

## Conditions

Test every MVP condition:

- Entire Site.
- Front Page.
- Blog Page.
- All Posts.
- Specific Post.
- All Pages.
- Specific Page.
- All Categories.
- Specific Category.
- Search Results.
- 404 Page.

Also test:

- Include only.
- Exclude behavior.
- Conflicting templates.
- Deleted target post or page.
- Draft target post or page.
- Priority tie.

## Dynamic Data Resolvers

Test:

- Site Title.
- Site Logo.
- Page Title.
- Post Title.
- Post Excerpt.
- Featured Image.
- Post Date.
- Author Name.
- Categories.
- Breadcrumbs.

For each:

- Frontend context.
- Elementor editor context.
- Missing value fallback.
- Escaping behavior.

## Responsive Testing

Breakpoints:

- Desktop.
- Tablet.
- Mobile.

Check:

- Nav Menu behavior.
- Hero spacing.
- Cards and columns.
- Posts grid.
- Carousel controls only if Testimonial Carousel is explicitly promoted before v1.5.
- Search form layout.
- Breadcrumb wrapping.

## Accessibility Testing

Target WCAG AA where possible.

Check:

- Keyboard navigation.
- Focus visible.
- Color contrast.
- Heading hierarchy.
- ARIA usage.
- Search labels.
- Menu toggles.
- Carousel pause and controls only if Testimonial Carousel is explicitly promoted before v1.5.
- Breadcrumb semantics.

## Security Testing

Check:

- Capability checks.
- Nonce checks.
- Sanitization.
- Escaping.
- URL validation.
- Attachment ID validation.
- Template ID validation.
- AJAX endpoint permissions if any.
- Import validation only if import tools are added in a later version.

## Performance Testing

Check:

- Frontend assets load only when required.
- Template resolution is not repeated excessively.
- Posts widget queries are bounded.
- Admin screens do not load frontend-only assets.
- No major layout shifts caused by widget scripts.

## Regression Testing

Create a small regression checklist for:

- Activation.
- Elementor editor loads.
- Header renders.
- Footer renders.
- Single post template renders.
- Conditions resolve.
- Widgets render.
- Settings save.

## Release Testing

Before release:

- Fresh install test.
- Upgrade test from previous internal build.
- Deactivation test.
- Uninstall test.
- Zip package install test.
- Readme review.
- Changelog review.

## Assumptions

- Automated tests will be added where practical, but manual QA is still required for Elementor editor and frontend rendering.
- Exact PHP and WordPress test matrix should be updated at implementation time.
- Accessibility testing may combine automated checks and manual keyboard testing.
- Browser automation may be used for critical flows.

## Key Risks

- Elementor editor behavior can be difficult to test fully with automated tools.
- Theme compatibility issues may appear late.
- Dynamic preview behavior can differ from frontend behavior.
- Accessibility issues in complex widgets can require design changes.

## Implementation Phases

### Phase 1: Test Foundation

- Add test tooling.
- Add standards checks.
- Add basic activation and requirements tests.

### Phase 2: Core Feature Tests

- Test conditions, dynamic resolvers, and template resolution.

### Phase 3: Widget QA

- Test each widget manually and, where practical, with browser automation.

### Phase 4: Release QA

- Run full environment matrix.
- Complete security and accessibility checklists.
- Verify package install and uninstall.
