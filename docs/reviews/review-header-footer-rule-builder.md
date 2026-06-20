# Header/Footer Rule Builder Code Review

Date: 2026-06-15

## Summary

Reviewed the latest UAE-style Header/Footer rule builder implementation against the Code Review Agent workflow, project architecture, Phase 1 scope, WordPress standards, Elementor Free compatibility, security requirements, and shared abstraction rules.

The implementation is directionally solid: shared rule option and sanitization logic was extracted into `RuleOptions`, admin save logic uses nonce and capability checks, output is escaped, Elementor Pro APIs are not used, and PHPCS passes.

The review found minor but important completion risks around backward compatibility, UAE-style target search behavior, and the original User Roles scope expansion.

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

Resolution status: fixed in code on 2026-06-20 with schema `3` migration that backfills active published Header/Footer templates with empty display conditions to an explicit `Entire Site` include rule. Runtime WP-CLI verification is pending because WordPress currently returns `Error establishing a database connection`.

Impact:

- Existing Header/Footer template behavior may change without a migration path.
- This conflicts with the architecture requirement that existing Header/Footer data must be preserved.
- Users may see active Header/Footer templates stop rendering after update.

### 2. Specific target field search behavior was incomplete

Severity: Low

Location:

- `includes/modules/header-footer/MetaBox.php:294`
- `includes/modules/header-footer/MetaBox.php:295`
- `includes/modules/header-footer/MetaBox.php:296`
- `assets/js/header-footer-admin.js:112`
- `assets/js/header-footer-admin.js:154`
- `includes/modules/header-footer/TargetSearch.php`

The field placeholder says users can search pages, posts, or categories. The current JavaScript only toggles, clones, removes, and renumbers rule rows. It does not provide UAE-like AJAX search, Select2 behavior, or token insertion.

Resolution status: fixed in code on 2026-06-20 by adding a nonce-protected admin AJAX target search endpoint, debounced frontend search, UAE-style selected target chips, chip-only selected state, minimum-character feedback, grouped result rendering, chip removal, and token insertion for searchable Header/Footer specific target rules. Static validation passes; browser runtime validation remains pending.

Impact:

- The original mismatch is resolved in code.
- Browser validation should now confirm searching pages, posts, and categories, grouped result headings, selecting a result, removing a selected chip, saving, and reloading values.

### 3. User Roles rules were implemented but not clearly included in the v1.0 condition scope

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

The builder previously supported `User Roles`, including all visitors, logged-in users, logged-out users, and editable WordPress roles. This matched part of the UAE-style Header/Footer UI, but the v1.0 condition definitions did not list User Roles, and the architecture excludes a Role Manager from v1.0.

Resolution status: fixed in code on 2026-06-20 by removing Header/Footer User Roles targeting from the settings UI, save flow, condition matching, helper code, JavaScript, CSS, and legacy database metadata with schema `4`.

Impact:

- The scope expansion risk is resolved for v1.0.
- User role targeting can be reconsidered in a later version as a separately scoped feature.

## Required Fixes

1. Completed in code: add a backward compatibility migration for empty condition meta. Runtime verification remains pending until WordPress database connectivity is available again.
2. Completed in code: implement real AJAX search/autocomplete behavior for Header/Footer specific target rules.
3. Completed in code: removed User Roles targeting from Phase 1 Header/Footer Builder behavior.

## Recommendations

- Add focused browser checks for adding/removing display rules, revealing exclusion rules, searching/selecting specific targets, removing target chips, saving values, reloading the edit screen, and confirming frontend render behavior.
- Add unit-level recommendations for `RuleOptions::sanitize_location_rules()` and `Conditions` matching behavior.
- Keep the `RuleOptions` helper as the shared abstraction for Header/Footer rule options and sanitization; it avoids duplicate logic and fits the current module boundary.
- Keep the lightweight native AJAX search implementation unless future UX testing proves a Select2-style dependency is necessary.

## Verification Notes

- `git diff --check` passed.
- `node --check assets/js/header-footer-admin.js` passed.
- `./vendor/bin/phpcs --standard=phpcs.xml` passed.
- `composer lint` was not run from the current shell because `composer` is not available in PATH. The equivalent PHPCS command above passed.
- Browser validation for this latest UAE-style rule builder remains pending because the local WordPress database connection issue is still documented in `docs/status.md`.

## Verdict

PASS WITH MINOR FIXES

The implementation is structurally acceptable and follows the shared abstraction direction, but the required fixes above should be resolved or explicitly documented before the Header/Footer rule builder is considered complete and ready for final Phase 1 acceptance.
