# Header/Footer Language Removal Summary

Date: 2026-06-20

## Summary

Removed the Header/Footer template Language setting from the AlternatePro Elements Header/Footer Builder implementation.

## Changes Made

- Removed the Language row from Header/Footer template settings UI.
- Removed language filtering from Header/Footer template resolution.
- Removed the Language column and sortable mapping from the Header/Footer template list table.
- Removed language labels from page-level Header/Footer override select options.
- Removed `_apro_language` registered post meta.
- Deleted the unused `LanguageResolver` class.
- Bumped schema version to `2`.
- Added upgrade and activation cleanup for legacy `_apro_language` post meta.

## Database Cleanup

Local database cleanup was verified through WP-CLI.

- Schema version: `2`
- `_apro_language` meta count: `0`

## Validation

- PHP syntax checks passed for changed PHP files.
- `./vendor/bin/phpcs --standard=phpcs.xml` passed.
- `node --check assets/js/header-footer-admin.js` passed.
- `git diff --check` passed.
- Repository search confirms no active Header/Footer language UI or resolver references remain.

## Remaining Work

- Browser validation for the latest UAE-style Header/Footer rule builder remains pending.
