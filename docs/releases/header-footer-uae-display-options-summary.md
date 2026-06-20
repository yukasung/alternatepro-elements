# Header/Footer UAE Display Options Summary

Date: 2026-06-20

## Summary

Updated the Header/Footer `Display On` combobox to follow the UAE-style option model.

## Changes Made

- Added `Basic` options for Entire Website, All Singulars, and All Archives.
- Added `Special Pages` options for 404 Page, Search Page, Blog / Posts Page, Front Page, Date Archive, and Author Archive.
- Added dynamic public post type groups such as Posts, Pages, and any available public custom post types.
- Added dynamic public taxonomy archive options such as All Categories Archive and All Tags Archive.
- Kept `Specific Target` as a single UAE-style option for pages, posts, taxonomies, and other targets.
- Added matching logic for All Singulars, date archives, author archives, post type archives, and taxonomy archives.
- Expanded specific target search to include public post types and public taxonomies.
- Refined the Specific Target picker so saved targets display as chip-only selections until the field is focused.
- Added UAE-style minimum-character feedback while searching.
- Added grouped search result headings such as Posts, Pages, Categories, and other public target groups.
- Removed the searchable rule helper copy from the selected target state so the row visually matches the UAE plugin more closely.
- Removed the inner search input border and focus shadow so only the outer target picker container is framed, matching the UAE plugin styling more closely.
- Removed WordPress link-style underlines from target search result rows.
- Added result indentation below group labels to better match the UAE plugin result layout.
- Forced Posts and Pages into the first target search groups, matching UAE behavior.
- Removed the global target search result cap that could hide Pages when other target groups returned many matches.
- Added draft Posts and Pages to target search results for users with the corresponding edit permissions.

## Validation

- PHP syntax checks passed.
- `node --check assets/js/header-footer-admin.js` passed.
- `./vendor/bin/phpcs --standard=phpcs.xml` passed.
- `git diff --check` passed.

## Remaining Work

- Browser validation should confirm the combobox option list, saving, reloading, and frontend matching behavior in a live WordPress admin session.
