# Review Agent Summary

Date: 2026-06-15

## Changes Made

- Created `docs/agents/review.md`.
- Added an `agents/` documentation area for reusable AI agent workflows.
- Added the Code Review Agent workflow to `docs/index.md`.
- Documented the required code review process after implementation tasks.

## Workflow Added

The Code Review Agent workflow defines:

- Required context documents to read before review.
- Review scope limited to the latest implementation changes.
- Architecture, code quality, WordPress standards, Elementor compatibility, security, performance, and documentation checklists.
- Required review report location and filename format.
- Review verdict values:
  - PASS
  - PASS WITH MINOR FIXES
  - FAIL

## Files Updated

- `docs/agents/review.md`
- `docs/index.md`
- `docs/releases/README.md`
- `docs/releases/review-agent-summary.md`
- `docs/status.md`
- `CHANGELOG.md`

## Validation Results

- `docs/agents/review.md` uses relative links.
- The review workflow does not authorize plugin source code changes.
- The review workflow does not allow starting the next phase.
- The review output format is defined.
- No plugin source code was modified.
