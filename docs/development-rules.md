# AlternatePro Elements Development Rules

## Purpose

This document defines the mandatory development process, coding standards, documentation standards, and project workflow for AlternatePro Elements. It is the project constitution for all future planning, implementation, review, testing, and release work.

## Project Principles

1. Architecture First
2. Documentation First
3. Security First
4. WordPress Standards First
5. Elementor Compatibility First
6. Backward Compatibility First
7. Maintainability Over Speed

## Single Source of Truth

Primary Documents:

- [docs/context.md](context.md)
- [docs/done.md](done.md)
- [docs/status.md](status.md)
- [docs/index.md](index.md)
- [docs/planning/architecture.md](planning/architecture.md)
- [docs/planning/implementation-plan.md](planning/implementation-plan.md)

Rules:

- Never ignore these documents.
- Verify them before development begins.
- Follow them during implementation.

## Session Startup Rules

Before any development work:

1. Read docs/context.md
2. Read docs/status.md
3. Read docs/index.md
4. Read docs/planning/architecture.md
5. Read docs/planning/implementation-plan.md
6. Review current phase
7. Review open issues
8. Review pending tasks

No coding should begin before this review.

## Documentation Rules

Documentation is mandatory.

Every completed task must update:

- [docs/status.md](status.md)

Release management documents must be updated according to the Release Management Rules.

When applicable update:

- [docs/index.md](index.md)
- [docs/releases/](releases/README.md)
- [docs/testing/](testing/testing-plan.md)
- [docs/reviews/](reviews/architecture-review.md)

Documentation is part of the Definition of Done.

A task is not complete until documentation is updated.

After implementation, bug fix, refactor, or feature development work is completed, run the [Done Workflow](done.md).

## Release Management Rules

Release management is mandatory for all completed development work.

### Phase Completion Requirements

Every completed phase must update:

- [docs/status.md](status.md)
- [CHANGELOG.md](../CHANGELOG.md)
- `docs/releases/phase-summary.md`

A phase cannot be marked as completed until all three documents have been updated.

### Widget Completion Requirements

Every completed widget must update:

- [docs/status.md](status.md)
- [CHANGELOG.md](../CHANGELOG.md)
- [docs/releases/widget-progress.md](releases/widget-progress.md)

### Feature Completion Requirements

Every completed feature must update:

- [docs/status.md](status.md)
- [CHANGELOG.md](../CHANGELOG.md)

If the feature introduces a major capability:

- Create or update the appropriate release summary document.

### Changelog Rules

Use the following sections:

- Added
- Changed
- Fixed
- Removed
- Security

Record all significant development work.

## Widget Development Rules

Before creating a widget:

1. Verify architecture compatibility
2. Verify Elementor compatibility
3. Verify responsive requirements
4. Verify accessibility requirements

After widget completion:

1. Update docs/status.md
2. Update [CHANGELOG.md](../CHANGELOG.md)
3. Update [docs/releases/widget-progress.md](releases/widget-progress.md)
4. Update release documentation

## Theme Builder Rules

All Theme Builder features must:

- Follow [architecture.md](planning/architecture.md)
- Follow Conditions System design
- Follow Dynamic Data Resolver design
- Remain compatible with Elementor Free

No shortcuts allowed.

## Coding Standards

Requirements:

- PHP 8.1+
- OOP Architecture
- WordPress Coding Standards
- Elementor Coding Standards
- PSR-4 Autoloading
- Strict Namespacing
- No duplicate code
- No dead code
- No hardcoded values where configuration is required

## Security Standards

Mandatory:

- Sanitize Input
- Escape Output
- Nonce Verification
- Capability Checks
- Direct Access Protection
- Secure Database Queries

Security reviews are required before phase completion.

## Git Rules

When commits are made during a phase:

- Use meaningful commit messages
- Ensure the required changelog and release summary updates are included before phase completion

## Phase Completion Rules

Before marking a phase complete:

1. Code implemented
2. Code reviewed
3. Documentation updated
4. Status updated
5. Changelog updated
6. Release summary created
7. Acceptance criteria verified

Only then may the phase be marked completed.

## Definition of Done

A task is considered complete only when:

- Required implementation or documentation work is complete
- Code follows architecture when code changes are made
- Security requirements are met
- Documentation is updated
- Status is updated
- Changelog is updated
- Release documentation is updated if applicable
- Acceptance criteria pass

## Mandatory Instruction For Future Sessions

Every future development session must:

1. Read docs/context.md
2. Read docs/status.md
3. Read docs/index.md
4. Read docs/development-rules.md

before making any code changes.

Failure to follow these rules is considered a project process violation.
