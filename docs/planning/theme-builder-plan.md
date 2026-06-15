# Theme Builder Plan

## Purpose

This document defines the v1.0 Core Theme Builder plan for AlternatePro Elements. [Architecture](architecture.md) is the source of truth for final scope.

## v1.0 Template Types

- Header
- Footer
- Single Post
- Archive
- Search
- 404

WooCommerce templates are excluded from v1.0.

## User Workflow

1. User creates a new AlternatePro template.
2. User chooses a template type.
3. User edits the template with Elementor.
4. User assigns display conditions.
5. User sets status to active.
6. Plugin resolves and renders the template on matching frontend requests.

## Template Storage

Templates should be stored as a plugin-owned custom post type.

Recommended metadata:

- Template type.
- Template status.
- Template priority.
- Display conditions.
- Optional language context for future multilingual compatibility.

## Template Types

### Header

Renders near the top of the page and replaces or supplements the theme header.

### Footer

Renders near the bottom of the page and replaces or supplements the theme footer.

### Single Post

Controls the layout for individual blog posts.

### Archive

Controls general post archive contexts and category archives in v1.0. Tag-specific, author-specific, and date-specific assignment is deferred.

### Search

Controls search results pages.

### 404

Controls not-found pages.

## Rendering Strategy

### Header And Footer

Use WordPress hooks and theme compatibility handling to render header and footer templates.

Potential integration points:

- `get_header`
- `wp_body_open`
- `get_footer`
- Theme-specific compatibility hooks where necessary.

The implementation should avoid duplicate headers and footers.

### Single And Archive Templates

Use template interception carefully.

Potential integration points:

- `template_include`
- Elementor frontend rendering APIs.
- Plugin-owned wrapper templates.

The plugin should preserve WordPress query context so Dynamic Data Resolvers and post widgets resolve correctly.

### Search And 404 Templates

Search and 404 should use the same resolution and rendering path as other theme templates, with request-specific condition checks.

## Template Resolution

Resolution should happen per template type and per request.

Recommended process:

1. Detect current request context.
2. Fetch active templates of the requested type.
3. Evaluate include conditions.
4. Evaluate exclude conditions.
5. Rank by specificity.
6. Rank by priority.
7. Select one template.
8. Cache selected template ID for the current request.

## Template Priority

Priority is an integer used as a tie-breaker. Higher priority should win, unless the product chooses WordPress-style lower-number-first behavior. The choice must be documented in the UI.

Recommended: higher number wins because it is easier for users to understand as "more important."

## Condition Specificity

More specific conditions should beat broader conditions.

Recommended order:

1. Specific Post or Specific Page.
2. Specific Category.
3. Search Results or 404 Page.
4. Front Page or Blog Page.
5. All Posts, All Pages, or All Categories.
6. Entire Site.

## Editor Preview

Theme templates need preview context.

Recommended preview controls:

- Preview post for Single Post.
- Preview archive/category for Archive.
- Preview search term for Search.
- Preview 404 state for 404.

If preview controls are deferred, v1.0 should at least provide safe fallback content.

## Admin Experience

Theme Builder admin should include:

- Template list with type, status, conditions, priority, and last modified date.
- Add New Template action.
- Edit with Elementor action.
- Quick status toggle.
- Conditions management.
- Conflict warnings when multiple templates match the same route.

## Compatibility

The Theme Builder should account for:

- Classic themes.
- Block themes.
- Themes with custom header/footer output.
- Elementor canvas and full-width templates.
- Sites where Elementor Pro is active.

## Assumptions

- The existing header/footer module can provide a starting point for post type, metadata, conditions, and rendering.
- Theme Builder templates will use Elementor document data.
- v1.0 can use a single plugin-owned template post type for all template types.
- WooCommerce templates and custom post type archive assignments are out of scope for v1.0.

## Key Risks

- Replacing core templates may break theme layout wrappers.
- Block themes may need special handling.
- Elementor Pro Theme Builder may conflict if active.
- Poor condition precedence can make templates appear on unexpected pages.
- Editor preview can be confusing without context selection.

## Implementation Phases

### Phase 1: Template Foundation

- Confirm template custom post type and metadata.
- Add template type registry.
- Add status and priority handling.

### Phase 2: Header And Footer

- Refactor current header/footer behavior into the new Theme Builder architecture.
- Preserve existing functionality while adding clearer type handling.

### Phase 3: Conditions Integration

- Connect templates to the Conditions System.
- Add conflict detection and display.

### Phase 4: Core Template Rendering

- Implement Single Post, Archive, Search, and 404 rendering.
- Test with multiple themes.

### Phase 5: Preview And Polish

- Add preview context tools.
- Improve admin columns and quick actions.
- Complete compatibility testing.
