# Conditions System

## Purpose

The Conditions System controls where Theme Builder templates render.

## v1.0 Conditions

- Entire Site
- Front Page
- Blog Page
- All Posts
- Specific Post
- All Pages
- Specific Page
- All Categories
- Specific Category
- Search Results
- 404 Page

## Condition Model

Each condition should have:

- ID.
- Label.
- Group.
- Include or exclude mode.
- Specificity score.
- Optional object ID.
- Optional object type.
- Evaluation callback or class.
- Admin UI configuration.

## Recommended Storage Shape

Conditions should be stored as structured post meta on each template.

Example shape for planning only:

```text
conditions:
  include:
    - type: entire_site
    - type: specific_page
      object_id: 123
  exclude:
    - type: specific_post
      object_id: 456
```

The final implementation should store this in a format that is easy to sanitize, migrate, and inspect.

## Include And Exclude Logic

Recommended behavior:

- A template must match at least one include condition.
- If no include conditions exist, the template should not render by default.
- Any matching exclude condition prevents the template from rendering.
- Exclusions should beat inclusions.

## Specificity

Specificity should be explicit and testable.

Recommended specificity scores:

- Specific Post: 100
- Specific Page: 100
- Specific Category: 90
- Search Results: 80
- 404 Page: 80
- Front Page: 75
- Blog Page: 75
- All Posts: 60
- All Pages: 60
- All Categories: 60
- Entire Site: 10

Template priority should break ties after specificity.

## Condition Definitions

## Entire Site

Matches all frontend requests that WordPress can render.

Use carefully because it is broad.

## Front Page

Matches the site's front page.

Should account for:

- Static front page.
- Latest posts on front page.

## Blog Page

Matches the posts page configured in WordPress reading settings.

## All Posts

Matches individual blog posts.

Does not include pages.

## Specific Post

Matches one selected post.

Requires:

- Post selector.
- Permission-safe admin search.
- Handling for trashed or deleted posts.

## All Pages

Matches all WordPress pages.

## Specific Page

Matches one selected page.

Requires:

- Page selector.
- Handling for front page and blog page overlap.

## All Categories

Matches category archive pages.

## Specific Category

Matches one selected category archive and, if product-approved, posts in that category. For v1.0, this should be defined clearly before implementation.

Recommended v1.0 behavior: Specific Category matches category archive pages only. Use All Posts or Specific Post for single post pages.

## Search Results

Matches search results pages.

## 404 Page

Matches not-found pages.

## Admin UI

The UI should support:

- Add condition.
- Include or exclude selection.
- Condition type selection.
- Object search for specific post, page, and category.
- Priority control at the template level.
- Clear summary of active conditions.
- Conflict warning when multiple templates can match the same route.

## Runtime Evaluation

Evaluation should:

- Use WordPress conditional tags.
- Run after the main query is available.
- Avoid expensive queries.
- Cache results per request.
- Handle admin, REST, AJAX, cron, and CLI contexts safely.

## Conflict Resolution

When multiple templates match:

1. Exclusions remove templates first.
2. Highest specificity wins.
3. Highest priority wins.
4. Most recently modified may be used only as a final deterministic fallback.

The admin UI should warn users about likely conflicts.

## Assumptions

- Conditions are primarily for Theme Builder templates in v1.0.
- Widget-level display conditions are out of scope.
- Include/exclude support should be part of the data model even if the UI is simplified initially.
- Conditions must be extensible for future template types and WooCommerce support.

## Key Risks

- Front page and blog page behavior is easy to mis-handle.
- Category behavior can be ambiguous between archives and single posts in categories.
- Conditions stored as unstructured arrays can be hard to migrate.
- Poor conflict handling can make templates seem unreliable.

## Implementation Phases

### Phase 1: Registry And Schema

- Define condition classes.
- Define storage format.
- Define sanitization and validation.

### Phase 2: Evaluator

- Implement WordPress request context detection.
- Implement include/exclude evaluation.
- Add specificity and priority scoring.

### Phase 3: Admin UI

- Add condition controls to template editing.
- Add object search for posts, pages, and categories.
- Add summaries and warnings.

### Phase 4: Testing

- Test every condition against expected WordPress routes.
- Test conflicts, exclusions, deleted objects, and draft templates.
