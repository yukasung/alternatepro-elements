# Security Audit Agent

## Purpose

Perform a security-focused review of newly implemented features.

Use this document by running:

Read `docs/agents/security.md`

## Required Context

Before starting a security review, read:

- [docs/context.md](../context.md)
- [docs/status.md](../status.md)
- [docs/development-rules.md](../development-rules.md)
- [docs/planning/architecture.md](../planning/architecture.md)
- [docs/planning/implementation-plan.md](../planning/implementation-plan.md)
- [docs/reviews/security-checklist.md](../reviews/security-checklist.md)

## Security Review Scope

Review only the files modified in the latest implementation.

Do not implement fixes.

Do not refactor code.

Security review only.

## WordPress Security Checks

### Direct File Access

Verify:

- `ABSPATH` protection exists
- No PHP file can be accessed directly

Check:

```php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
```

### Input Sanitization

Verify all incoming data is sanitized.

Examples:

- `sanitize_text_field()`
- `sanitize_key()`
- `sanitize_email()`
- `absint()`
- `wp_unslash()`

Check:

- `$_POST`
- `$_GET`
- `$_REQUEST`
- Settings API input
- AJAX requests
- REST requests

### Output Escaping

Verify output is escaped correctly.

Examples:

- `esc_html()`
- `esc_attr()`
- `esc_url()`
- `wp_kses_post()`

Check:

- Admin Pages
- Widget Output
- Settings Output
- Dynamic Tags Output

### Nonce Verification

Verify:

- `wp_nonce_field()`
- `check_admin_referer()`
- `check_ajax_referer()`

Used where required.

### Capability Checks

Verify:

- `current_user_can()`

Exists before:

- Saving Settings
- Admin Actions
- Template Actions
- Theme Builder Actions

### Database Security

Verify:

- `$wpdb->prepare()`

Used when custom SQL exists.

Check for:

- SQL Injection Risk
- Unsafe Queries

## Elementor Security Checks

Verify:

- Widget controls sanitize values
- Dynamic Tags sanitize values
- Theme Builder templates sanitize output
- Conditions data cannot be manipulated by unauthorized users

## Admin Security Checks

Verify:

- Admin pages require permissions
- Settings are registered correctly
- Settings are sanitized before saving

## File Security Checks

Verify:

- No sensitive files exposed
- No debug files committed
- No credentials stored in code

## Dependency Security

Verify:

- Composer dependencies are trusted
- No known high-risk packages introduced

## Security Risk Classification

Classify findings:

### Critical

### High

### Medium

### Low

### Informational

## Output

Create:

`docs/reviews/security-{feature-name}.md`

Report must include:

1. Summary
2. Files Reviewed
3. Checks Performed
4. Issues Found
5. Risk Level
6. Required Fixes
7. Recommendations
8. Verdict

Verdict:

- PASS
- PASS WITH MINOR FIXES
- FAIL

## Rules

- Do not modify source code
- Do not implement fixes
- Do not start next phase
- Security review only

## Completion Rule

Security review is complete only when:

- WordPress security checks completed
- Elementor security checks completed
- Admin security checks completed
- Report generated

End of workflow.
