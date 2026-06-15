# Security Checklist

## Purpose

This checklist defines the security expectations for AlternatePro Elements v1.0.

## Foundational Checks

- Direct file access is blocked in PHP files.
- Plugin requirements fail gracefully.
- Admin-only functionality checks user capabilities.
- Frontend rendering does not expose admin-only data.
- Debug information is visible only to authorized users.
- Errors do not reveal sensitive paths or internals.

## Capabilities

Check capability enforcement for:

- Saving plugin settings.
- Creating templates.
- Editing templates.
- Deleting templates.
- Changing template status.
- Saving conditions.
- Importing settings.
- Exporting settings.
- Resetting settings.
- Running diagnostic tools.

Recommended capabilities:

- `manage_options` for plugin settings.
- WordPress post editing capabilities for template content.

## Nonces

Require nonce validation for:

- Settings forms.
- Template metadata saves.
- Condition saves.
- AJAX requests.
- Import actions.
- Reset actions.
- Bulk actions.

Nonce failures should stop the action and return a safe error message.

## Sanitization

Sanitize:

- Text fields.
- Select values.
- Boolean toggles.
- Numeric IDs.
- URLs.
- CSS-related values.
- Icon identifiers.
- Date format settings.
- Template type values.
- Condition type values.
- Condition object IDs.
- Import payloads.

Use strict allowlists for:

- Template types.
- Condition types.
- Widget IDs.
- Module IDs.
- Status values.
- Priority values.

## Escaping

Escape output based on context:

- HTML text.
- HTML attributes.
- URLs.
- JavaScript data.
- Inline styles.
- Admin notices.
- Widget output.
- Dynamic tag output.
- Breadcrumb output.

Avoid raw HTML unless it has been intentionally sanitized.

## Database And Options

- Use WordPress APIs for post meta and options.
- Use prepared statements for any direct SQL.
- Keep option names prefixed.
- Avoid autoloading large settings.
- Validate imported settings before saving.
- Delete plugin-owned options only during confirmed uninstall.

## Elementor Widgets

For each widget:

- Sanitize control settings before use.
- Escape all rendered values.
- Validate media attachment IDs.
- Validate links and link attributes.
- Add rel attributes for external links where appropriate.
- Avoid unsafe inline scripts.
- Avoid rendering untrusted HTML from repeaters.

## Theme Builder

Check:

- Template IDs are validated.
- Only published and active templates render.
- Draft, private, trash, or unauthorized templates do not render publicly.
- Template rendering cannot be triggered for arbitrary post IDs by unauthorized users.
- Preview mode is permission-aware.
- Conditions cannot be manipulated to expose private content.

## Conditions System

Check:

- Condition types are allowlisted.
- Object IDs are absolute integers.
- Object types are validated.
- Deleted objects are handled safely.
- Exclusions cannot cause errors.
- Admin condition search endpoints require permissions.

## Dynamic Data Resolvers

Check:

- Values come from trusted WordPress APIs.
- Missing context does not cause fatal errors.
- Post data is escaped according to usage.
- Excerpts are sanitized.
- Category links are escaped.
- Breadcrumb item labels and URLs are escaped.
- Featured image IDs and URLs are validated.

## Admin Settings

Check:

- Settings forms use nonces.
- Settings save actions check capabilities.
- Imports validate file type, size, and structure.
- Reset actions require confirmation.
- Diagnostics do not expose secrets such as salts, database passwords, or auth tokens.
- Admin notices are escaped.

## AJAX And REST

If AJAX or REST endpoints are added:

- Register endpoints intentionally.
- Check authentication where required.
- Check capabilities.
- Validate nonces for cookie-authenticated requests.
- Sanitize request data.
- Escape response data.
- Avoid exposing private posts, drafts, or user data.
- Rate-limit expensive searches where practical.

## Assets

Check:

- Scripts and styles use registered handles.
- Asset URLs are generated through WordPress APIs.
- No user input is concatenated into script paths.
- Localized script data is escaped and minimized.
- Inline scripts are avoided unless necessary.

## Privacy

v1.0 should not collect external telemetry by default.

If future telemetry is added:

- Make it opt-in.
- Explain what is collected.
- Avoid collecting personal data unless necessary.
- Provide disable controls.

## Dependencies

Check:

- Third-party libraries are reviewed before inclusion.
- Library versions are documented.
- Minified assets have source references where practical.
- Unused dependencies are removed.

## Release Gate

Before v1.0 release:

- Complete this checklist.
- Run standards checks.
- Run security-focused manual QA.
- Verify no debug output remains.
- Verify no development files are packaged accidentally.
- Verify uninstall removes plugin-owned data only when appropriate.

## Assumptions

- The plugin will not process payments, login credentials, or sensitive personal data in v1.0.
- The plugin will not call external APIs by default.
- Admin users are trusted to create templates, but all inputs still require validation.
- Security checks should be repeated whenever new widgets or condition types are added.

## Key Risks

- Template rendering can accidentally expose draft or private content if status checks are weak.
- Dynamic data can be unsafe if escaped incorrectly for the current context.
- Import tools can become a security risk without strict validation.
- Admin AJAX search can leak post titles if permissions are too broad.

## Implementation Phases

### Phase 1: Secure Foundation

- Add requirement checks.
- Add capability and nonce helpers.
- Define sanitization helpers and allowlists.

### Phase 2: Secure Feature Development

- Apply security rules to widgets, Theme Builder, conditions, and Dynamic Data Resolvers during implementation.

### Phase 3: Security Review

- Review every form, endpoint, render path, and dynamic value.
- Fix issues before feature freeze.

### Phase 4: Release Verification

- Complete release gate checks.
- Document known limitations and safe usage expectations.
