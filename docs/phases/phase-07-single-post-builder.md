# Phase 7 - Single Post Builder

Source: [Implementation Plan](../planning/implementation-plan.md)

## Goal

Render assigned Single Post templates for individual blog posts.

## Scope

- Single Post template resolution.
- Frontend rendering for individual blog posts.
- All Posts condition support in single post context.
- Specific Post condition support.
- Dynamic post data inside templates.
- Main query and global post preservation.
- Theme content replacement strategy for single posts.
- Public rendering safeguards for private and draft content.

## Deliverables

- Template resolver support for Single Post templates.
- Template renderer behavior for individual blog posts.
- Integration with All Posts and Specific Post conditions.
- Dynamic data context support for post title, excerpt, featured image, date, author, and categories.
- Wrapper template behavior for single post output.
- Safeguards that prevent page requests from matching Single Post templates.
- Safeguards that prevent private or draft content exposure.

## Dependencies

- [Phase 6 - Header/Footer Builder](phase-06-header-footer-builder.md)
- [Phase 4 - Conditions Engine](phase-04-conditions-engine.md)
- [Phase 5 - Dynamic Data Engine](phase-05-dynamic-data-engine.md)
- WordPress main query context.
- [Architecture](../planning/architecture.md)

## Acceptance Criteria

- Single Post templates render for individual blog posts.
- All Posts condition matches single blog posts.
- Specific Post condition matches only the selected post.
- Page requests do not match Single Post templates.
- Custom post type singles are not promised in v1.0 unless naturally supported and tested.
- Post dynamic data resolves correctly inside the template.
- Original query context is preserved.
- Template output does not duplicate theme content.
- No private or draft post content is exposed.

## Excluded Scope

- Page template builder.
- Custom post type single template support as a promised v1.0 feature.
- WooCommerce single product templates.
- Archive, search, or 404 rendering.
- Advanced layout conditions beyond All Posts and Specific Post.
- New widgets not required for Single Post rendering.

## Definition of Done

- Phase 7 deliverables are implemented according to [Architecture](../planning/architecture.md).
- Acceptance criteria are verified on individual blog posts.
- Private and draft content exposure risks are reviewed.
- Code review report is created through [Code Review Agent](../agents/review.md).
- Testing report is created through [Testing Agent](../agents/test.md).
- Security review report is created through [Security Audit Agent](../agents/security.md).
- [Project Status](../status.md) is updated.
- [CHANGELOG.md](../../CHANGELOG.md) is updated.
- Phase release summary is created or updated in `docs/releases/`.
- No Phase 8 work starts until Phase 7 is marked complete.
