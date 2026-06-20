# Header/Footer Rule Builder Code Review

Date: 2026-06-15

## Summary

Reviewed the latest UAE-style Header/Footer rule builder implementation against the Code Review Agent workflow, project architecture, Phase 1 scope, WordPress standards, Elementor Free compatibility, security requirements, and shared abstraction rules.

The implementation is directionally solid: shared rule option and sanitization logic was extracted into `RuleOptions`, admin save logic uses nonce and capability checks, output is escaped, Elementor Pro APIs are not used, and PHPCS passes.

The review found minor but important completion risks around backward compatibility, UAE-style search behavior expectations, and scope documentation for User Roles.

## Files Reviewed

Source files:

- `assets/css/header-footer-admin.css`
- `assets/js/header-footer-admin.js`
- `includes/modules/header-footer/Assets.php`
- `includes/modules/header-footer/Conditions.php`
- `includes/modules/header-footer/MetaBox.php`
- `includes/modules/header-footer/Module.php`
- `includes/modules/header-footer/PostType.php`
- `includes/modules/header-footer/RuleOptions.php`

Related project documents:

- `CHANGELOG.md`
- `docs/status.md`
- `docs/releases/phase-01-progress.md`
- `docs/testing/test-phase-1-foundation.md`
- `docs/planning/architecture.md`

## Issues Found

### 1. Existing templates with empty condition meta may stop rendering

Severity: Medium

Location:

- `includes/modules/header-footer/Conditions.php:246`
- `includes/modules/header-footer/Conditions.php:247`
- `includes/modules/header-footer/Conditions.php:248`
- `docs/planning/architecture.md:38`
- `docs/planning/architecture.md:40`

The new condition evaluator correctly follows the current architecture rule that templates with no include rules should not render. However, earlier Phase 1 behavior treated an empty condition set as `Entire Site`. Existing local templates or saved `_apro_display_conditions` values created before this change may now disappear from the frontend unless they are resaved with an explicit display rule.

Impact:

- Existing Header/Footer template behavior may change without a migration path.
- This conflicts with the architecture requirement that existing Header/Footer data must be preserved.
- Users may see active Header/Footer templates stop rendering after update.

### 2. Specific target field says "Search" but no search/autocomplete behavior exists

Severity: Low

Location:

- `includes/modules/header-footer/MetaBox.php:294`
- `includes/modules/header-footer/MetaBox.php:295`
- `includes/modules/header-footer/MetaBox.php:296`
- `assets/js/header-footer-admin.js:112`
- `assets/js/header-footer-admin.js:154`

The field placeholder says users can search pages, posts, or categories. The current JavaScript only toggles, clones, removes, and renumbers rule rows. It does not provide UAE-like AJAX search, Select2 behavior, or token insertion.

Impact:

- The UI looks like it supports search, but the field is currently manual text entry.
- Users may expect UAE-equivalent search behavior and think the field is broken.
- Browser testing may pass layout checks while missing expected interaction behavior.

### 3. User Roles rules are implemented but not clearly included in the v1.0 condition scope

Severity: Low

Location:

- `includes/modules/header-footer/RuleOptions.php:105`
- `includes/modules/header-footer/RuleOptions.php:110`
- `includes/modules/header-footer/MetaBox.php:176`
- `includes/modules/header-footer/MetaBox.php:182`
- `includes/modules/header-footer/Conditions.php:406`
- `includes/modules/header-footer/Conditions.php:443`
- `docs/planning/architecture.md:973`
- `docs/planning/architecture.md:985`
- `docs/planning/architecture.md:117`

The builder now supports `User Roles`, including all visitors, logged-in users, logged-out users, and editable WordPress roles. This matches the UAE-style Header/Footer UI, but the v1.0 condition definitions do not list User Roles, and the architecture excludes a Role Manager from v1.0.

Impact:

- The implementation may be perceived as scope expansion unless documented as Header/Footer-specific display targeting.
- Testing requirements for User Roles are not clearly defined in the v1.0 planning docs.
- Future contributors may not know whether to maintain, expand, or defer this behavior.

## Required Fixes

1. Add a backward compatibility decision for empty condition meta before marking the rule builder complete. Preferred fix: add an upgrade/backfill path that converts legacy active templates with missing or empty condition meta to an explicit `Entire Site` include rule, or otherwise document the breaking behavior clearly before release.
2. Align the specific target field with actual behavior. Either implement UAE-style search/autocomplete in a scoped follow-up, or change the placeholder/help text so it clearly says this is manual ID/token entry for now.
3. Decide whether `User Roles` is officially part of Phase 1 Header/Footer Builder behavior. If yes, update architecture, phase, testing, and release documentation. If no, hide or defer the UI and matching logic.

## Recommendations

- Add focused browser checks for adding/removing display rules, revealing exclusion rules, saving values, reloading the edit screen, and confirming frontend render behavior.
- Add unit-level recommendations for `RuleOptions::sanitize_location_rules()`, `RuleOptions::sanitize_user_roles()`, and `Conditions` matching behavior.
- Keep the `RuleOptions` helper as the shared abstraction for Header/Footer rule options and sanitization; it avoids duplicate logic and fits the current module boundary.
- Avoid adding a full Select2/AJAX search layer until the local WordPress browser automation path is stable again.

## Verification Notes

- `git diff --check` passed.
- `node --check assets/js/header-footer-admin.js` passed.
- `./vendor/bin/phpcs --standard=phpcs.xml` passed.
- `composer lint` was not run from the current shell because `composer` is not available in PATH. The equivalent PHPCS command above passed.
- Browser validation for this latest UAE-style rule builder remains pending because the local WordPress database connection issue is still documented in `docs/status.md`.

## Verdict

PASS WITH MINOR FIXES

The implementation is structurally acceptable and follows the shared abstraction direction, but the required fixes above should be resolved or explicitly documented before the Header/Footer rule builder is considered complete and ready for final Phase 1 acceptance.
