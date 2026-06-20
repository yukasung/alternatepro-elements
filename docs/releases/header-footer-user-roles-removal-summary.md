# Header/Footer User Roles Removal Summary

Date: 2026-06-20

## Summary

Removed Header/Footer template User Roles targeting from the v1.0 rule builder scope.

## Changes Made

- Removed the User Roles row from Header/Footer template settings.
- Removed User Roles save handling from the Header/Footer metabox.
- Removed User Roles condition matching from template resolution.
- Removed User Roles helper methods from `RuleOptions`.
- Removed User Roles JavaScript and CSS support.
- Added schema version `4` cleanup for legacy `_apro_user_roles` metadata.
- Added save-time cleanup for legacy `_apro_user_roles` metadata on Header/Footer templates.

## Reason

User Roles targeting was not part of the approved v1.0 Conditions System scope and created ambiguity with the deferred Role Manager concept.

## Validation

- PHP syntax checks passed.
- `node --check assets/js/header-footer-admin.js` passed.
- `./vendor/bin/phpcs --standard=phpcs.xml` passed.
- `git diff --check` passed.
- Code review passed with no required fixes.
- Testing passed with minor issues limited to unavailable browser/runtime validation while the local WordPress database is unavailable.
- Security review passed with no required fixes.
- Refactor review completed with no refactor needed.

## Remaining Work

- Browser validation should confirm the Header/Footer template settings screen no longer displays User Roles controls.
- Runtime validation should confirm schema `4` removes legacy `_apro_user_roles` metadata.
