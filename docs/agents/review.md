# Code Review Agent

## Purpose

This document defines the required review process after any implementation task.

Use this file by running:

Read `docs/agents/review.md`

## Required Context

Before reviewing code, read:

- [docs/context.md](../context.md)
- [docs/status.md](../status.md)
- [docs/development-rules.md](../development-rules.md)
- [docs/planning/architecture.md](../planning/architecture.md)
- [docs/planning/implementation-plan.md](../planning/implementation-plan.md)

## Review Scope

Review only the files changed in the latest implementation.

Do not refactor code unless explicitly requested.

Do not start the next phase.

## Review Checklist

### Architecture

- Follows `docs/planning/architecture.md`
- Matches current phase scope
- No unrelated modules added
- No premature features added

### Code Quality

- OOP structure is clear
- Class names are consistent
- Namespace is correct
- No duplicate code
- No dead code
- No unnecessary complexity

### Code Reuse and Shared Abstractions

- Repeated logic is not duplicated across files
- Shared logic is moved to an appropriate shared location when needed
- Business logic uses service classes where appropriate
- Stateless utility logic uses helper classes where appropriate
- Traits are limited to small reusable behavior
- Abstract base classes are used only when widgets or modules share the same lifecycle
- Simple one-time logic is not over-abstracted
- Shared classes have clear responsibilities
- Shared classes are namespaced and follow PSR-4 autoloading
- Shared logic is covered by tests when applicable

Allowed shared locations:

- `includes/Core/`
- `includes/Support/`
- `includes/Helpers/`
- `includes/Contracts/`
- `includes/Traits/`
- `includes/Services/`

During code review, check for:

- Duplicate logic
- Repeated sanitization
- Repeated escaping
- Repeated Elementor controls
- Repeated render patterns
- Repeated settings parsing
- Repeated template lookup logic
- Large methods that should be extracted
- Classes with mixed responsibilities

If duplicate logic exists, the review verdict must be `PASS WITH MINOR FIXES` or `FAIL` depending on severity.

### WordPress Standards

- Uses WordPress hooks correctly
- Uses WordPress APIs where appropriate
- Follows WordPress Coding Standards
- Avoids direct file access

### Elementor Compatibility

- Does not break Elementor Free compatibility
- Does not use Elementor Pro-only APIs
- Follows Elementor extension patterns

### Security

- Sanitizes input
- Escapes output
- Uses nonce checks where required
- Uses capability checks for admin actions
- Avoids unsafe database queries

### Performance

- Loads assets only when needed
- Avoids unnecessary queries
- Avoids loading admin code on frontend unless required

### Documentation

- `docs/status.md` updated if work was completed
- `CHANGELOG.md` updated if required
- Release notes updated if required

## Output

Create a review report in:

`docs/reviews/`

File name format:

`review-{phase-or-feature-name}.md`

Report must include:

1. Summary
2. Files Reviewed
3. Issues Found
4. Required Fixes
5. Recommendations
6. Verdict

Verdict must be one of:

- PASS
- PASS WITH MINOR FIXES
- FAIL

## Rules

- Do not modify plugin source code.
- Do not implement new features.
- Do not start the next task.
- Review only.
- If issues are found, list fixes clearly.
- If PASS, state that the implementation is ready for testing.
