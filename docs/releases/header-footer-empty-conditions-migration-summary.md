# Header/Footer Empty Conditions Migration Summary

Date: 2026-06-20

## Summary

Added schema version `3` migration for legacy Header/Footer templates that were active but had empty display conditions.

## Problem Fixed

Earlier Phase 1 behavior treated empty display conditions as equivalent to `Entire Site`. The newer Conditions Engine requires at least one include rule. Without a migration, active legacy Header/Footer templates with empty `_apro_display_conditions` could stop rendering.

## Implementation

- Bumped `APRO_ELEMENTS_SCHEMA_VERSION` from `2` to `3`.
- Added an idempotent upgrade method that queries published, active `apro_template` posts.
- Backfills only templates whose `_apro_display_conditions` meta is missing, empty, or an empty JSON array.
- Uses the shared `RuleOptions::default_display_rules()` helper to avoid duplicate condition structure.
- Does not overwrite templates that already have display conditions.
- Runs from both the versioned upgrade path and activation path.

## Validation

- PHP syntax checks passed for changed PHP files.
- `./vendor/bin/phpcs --standard=phpcs.xml` passed.

## Pending Validation

- Runtime WP-CLI verification is pending because WordPress currently returns `Error establishing a database connection`.
