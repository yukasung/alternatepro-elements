# Dynamic Tags And Dynamic Data Resolvers

## Purpose

Dynamic Data Resolvers provide context-aware values that can be used by widgets and theme templates. Elementor Dynamic Tags adapter support is a later compatibility layer unless Elementor Free public APIs validate cleanly.

## v1.0 Dynamic Data Resolvers

- Site Title
- Site Logo
- Page Title
- Post Title
- Post Excerpt
- Featured Image
- Post Date
- Author Name
- Categories
- Breadcrumbs

## Product Goal

Users should be able to build templates once and have text, images, links, and contextual content update automatically based on the current page or post.

## Technical Approach

Elementor Pro is known for a full Dynamic Tags UI. Elementor Free may not expose the same surfaces. AlternatePro Elements therefore separates v1.0 resolver behavior from later Elementor editor adapter behavior:

- v1.0: Dynamic value resolvers owned by this plugin.
- v1.5 candidate: Elementor integration adapter where Elementor Free supports it.

This keeps the data layer stable even if the UI integration must vary by Elementor version.

## Dynamic Resolver Model

Each dynamic value should have:

- ID.
- Label.
- Return type: text, image, URL, HTML, date, list, or mixed.
- Context requirements.
- Fallback value.
- Sanitization rules.
- Escaping guidance.
- Editor preview behavior.

## Contexts

Dynamic Data Resolvers should resolve values from:

- Current frontend request.
- Current post in the main query.
- Current Elementor preview document.
- Selected preview object, if preview controls are implemented.

## Dynamic Data Definitions

## Site Title

Returns the WordPress site name.

Source:

- `get_bloginfo( 'name' )`

Expected type:

- Text.

## Site Logo

Returns the WordPress custom logo image or attachment data.

Source:

- Custom logo theme mod.
- Attachment metadata.

Expected type:

- Image.

Fallback:

- Site title text when no logo exists.

## Page Title

Returns the current page title for the current route.

Source:

- WordPress document title functions.
- Current queried object.

Expected type:

- Text.

## Post Title

Returns the current post title.

Source:

- Current post object.

Expected type:

- Text.

## Post Excerpt

Returns the current post excerpt or generated excerpt.

Source:

- Post excerpt.
- Trimmed post content fallback.

Expected type:

- Text or HTML depending on widget context.

## Featured Image

Returns the current post featured image.

Source:

- Post thumbnail ID.

Expected type:

- Image.

Fallback:

- Empty or configured placeholder.

## Post Date

Returns the current post date.

Source:

- Post date APIs.

Expected type:

- Text or date.

Controls:

- Date format.
- Link to archive optional in future.

## Author Name

Returns the current post author display name.

Source:

- Post author user data.

Expected type:

- Text.

## Categories

Returns post categories.

Source:

- Post terms for category taxonomy.

Expected type:

- List or linked HTML.

Controls:

- Separator.
- Link categories.
- Maximum number.

## Breadcrumbs

Returns contextual breadcrumb items.

Source:

- Plugin breadcrumb resolver.
- WordPress queried object.

Expected type:

- Structured list or HTML depending on consuming widget.

## Widget Integration

Dynamic values should be available first in plugin widgets where relevant:

- Site Logo widget: Site Logo.
- Site Title widget: Site Title.
- Hero Section: Page Title, Post Title, Post Excerpt, Featured Image.
- Posts widget: Post Title, Post Excerpt, Featured Image, Post Date, Author Name, Categories.
- Breadcrumbs widget: Breadcrumbs.

## Escaping Rules

- Text: escape as text.
- URLs: escape as URL.
- Attributes: escape as attributes.
- HTML: allow only approved markup when needed.
- Images: validate attachment IDs and URLs.
- Lists: escape each item individually.

## Editor Preview

Preview should:

- Resolve against the current edited document when possible.
- Use a selected preview object when available.
- Display clear fallback values when no context exists.
- Avoid fatal errors for missing posts or terms.

## Assumptions

- The plugin can provide dynamic behavior inside its own widgets regardless of Elementor Pro availability.
- Elementor Free dynamic tag API support must be verified before any editor-level adapter is promoted.
- Dynamic data should be reusable by Theme Builder and widgets.
- v1.0 does not need custom field, ACF, Pods, Toolset, or WooCommerce dynamic tags.

## Key Risks

- Elementor Free may not expose a complete Dynamic Tags UI, so v1.0 must not depend on it.
- Preview context may be inaccurate without explicit preview settings.
- Breadcrumbs can vary by permalink structure and taxonomy configuration.
- Generated excerpts can expose unexpected formatting if not sanitized.

## Implementation Phases

### Phase 1: Resolver Registry

- Define dynamic resolver interface.
- Add registry and shared context object.
- Add all MVP resolvers.

### Phase 2: Widget Integration

- Connect resolvers to relevant widget controls and render methods.
- Add fallbacks for missing context.

### Phase 3: Elementor Adapter, v1.5 Candidate

- Verify Elementor Free support.
- Register dynamic tags where supported.
- Gracefully disable unavailable integration points.

### Phase 4: Preview And QA

- Test in editor and frontend.
- Test posts, pages, archives, search, and 404 routes.
- Test empty values and deleted objects.
