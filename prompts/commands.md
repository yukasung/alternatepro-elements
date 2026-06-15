# AlternatePro Elements - Codex Commands

## How To Use

Copy one command block and paste it into Codex.

Use **Quick Workflow** for normal development.

Use **Individual Commands** for one-off actions.

---

## Important Rule

Every completed implementation task must update:

- `docs/status.md`
- `CHANGELOG.md`
- relevant `docs/releases/*` file if applicable
- relevant `docs/dashboards/*` file if applicable

A task is not complete until `docs/status.md` is accurate.

---

## Architecture Rules

Follow shared abstraction rules.

Do not duplicate logic.

When logic is reused across multiple locations:

- Create a shared Service
- Create a shared Helper
- Create a shared Support class
- Create a shared Trait when appropriate
- Create a shared Base Class when appropriate

Use:

- `includes/Core/`
- `includes/Support/`
- `includes/Services/`
- `includes/Helpers/`
- `includes/Traits/`

Avoid copy-paste implementations.

---

## Standard Workflow

Read docs/context.md

↓

Verify current phase dashboard

↓

Implement task

↓

Review

↓

Test

↓

Security Review

↓

Update dashboards

↓

Read docs/done.md

---

## Quick Workflow

### Start Work

```text
Read docs/context.md

Verify current phase dashboard.

Implement current task.

Requirements:
- Follow architecture.md
- Follow shared abstraction rules
- Avoid duplicate logic
```

---

### Review

```text
Read docs/agents/review.md
```

---

### Testing

```text
Read docs/agents/test.md
```

---

### Security

```text
Read docs/agents/security.md
```

---

### Update Dashboards

```text
Update:
- docs/dashboards/dashboard.html
- current phase dashboard
```

---

### Complete Task

```text
Read docs/done.md

Verify:
- docs/status.md updated
- CHANGELOG.md updated
- dashboards updated
```

---

## Individual Commands

## Start Session

```text
Read docs/context.md

Verify current phase dashboard.

Continue current phase.
```

---

## Implement Current Task

```text
Read docs/context.md

Verify current phase dashboard exists.

Implement current task.

Requirements:
- Follow architecture.md
- Follow shared abstraction rules
- Reuse existing services/helpers when possible
- Extract duplicate logic into shared classes

After implementation:
- Update docs/status.md
```

---

## Implement Next Task

```text
Read docs/context.md

Verify current phase dashboard exists.

Implement next task.

Requirements:
- Follow architecture.md
- Follow shared abstraction rules
- Reuse existing services/helpers when possible
- Extract duplicate logic into shared classes

After implementation:
- Update docs/status.md
```

---

## Continue Current Phase

```text
Read docs/context.md

Verify current phase dashboard.

Continue current phase.

After implementation, update:
- docs/status.md
- CHANGELOG.md
- relevant docs/releases/* file if applicable
- relevant docs/dashboards/* file if applicable
```

---

## Continue Next Phase

```text
Read docs/context.md

Verify current phase dashboard.

Review current status and begin the next phase.

After implementation, update:
- docs/status.md
- CHANGELOG.md
- relevant docs/releases/* file if applicable
- relevant docs/dashboards/* file if applicable
```

---

## Review Code

```text
Read docs/agents/review.md

Verify:
- No duplicate logic
- No copy-paste code
- Shared abstractions are used correctly
- Class responsibilities are clear
```

---

## Test Implementation

```text
Read docs/agents/test.md
```

---

## Security Review

```text
Read docs/agents/security.md
```

---

## Refactor Code

```text
Read docs/agents/refactor.md

Review for:
- Duplicate logic
- Large classes
- Large methods
- Repeated Elementor controls
- Repeated settings handling
- Repeated render logic

Move reusable logic into shared abstractions.
```

---

## Update Dashboards

```text
Update:
- docs/dashboards/dashboard.html
- current phase dashboard

Use:
- docs/status.md
- current phase documentation

as the source of truth.
```

---

## Complete Current Task

```text
Read docs/done.md

Verify:
- Phase dashboard updated
- Project dashboard updated
- docs/status.md updated
- CHANGELOG.md updated
- Release documentation updated if applicable
- Next task identified
```

---

## Update Project Status

```text
Read docs/status.md

Update current project status based on completed work.

Include:
- Current Phase
- Phase Status
- Completed Work
- Remaining Work
- Open Issues
- Next Task
```

---

## Review Project Status

```text
Read docs/status.md

Summarize:
- Current Phase
- Completed Work
- Remaining Work
- Open Issues
- Recommended Next Task
```

---

## Documentation Audit

```text
Read docs/reviews/documentation-audit.md

Verify documentation consistency.
```

---

## Pre-Development Audit

```text
Read docs/reviews/pre-development-audit.md

Verify project readiness.
```

---

## Release Preparation

```text
Read docs/done.md

Prepare project for release.
```
