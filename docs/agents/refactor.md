# Refactor Agent

## Purpose

Perform a controlled refactoring review and improvement process.

Use this document by running:

Read `docs/agents/refactor.md`

---

## Required Context

Before refactoring, read:

* [docs/context.md](../context.md)
* [docs/status.md](../status.md)
* [docs/development-rules.md](../development-rules.md)
* [docs/planning/architecture.md](../planning/architecture.md)
* [docs/planning/implementation-plan.md](../planning/implementation-plan.md)

Review latest reports if available:

* `docs/reviews/*`
* `docs/testing/*`
* `docs/releases/*`

---

## Refactoring Scope

Refactor only existing code.

Do not introduce new features.

Do not expand project scope.

Do not start the next phase.

Maintain backward compatibility.

---

## Refactoring Checklist

### Architecture Compliance

Verify:

* Code follows `architecture.md`.
* Module boundaries remain clear.
* No cross-module coupling introduced.
* Responsibilities are correctly separated.

---

### Code Quality

Check for:

* Duplicate Code
* Dead Code
* Unused Classes
* Unused Methods
* Unused Hooks
* Large Classes
* Large Methods
* Excessive Nesting
* Excessive Complexity

---

### OOP Design

Verify:

* Single Responsibility Principle
* Separation of Concerns
* Clear Class Responsibilities
* Consistent Namespaces
* Consistent Naming

---

### WordPress Standards

Verify:

* WordPress Coding Standards
* WordPress APIs used correctly
* Hook registration remains clean
* No unnecessary global state

---

### Elementor Standards

Verify:

* Widget architecture remains clean
* Controls are organized properly
* No duplicate widget logic
* No Elementor Pro dependencies

---

### Performance

Review:

* Unnecessary Queries
* Repeated Calculations
* Asset Loading
* Admin-only Code Loading
* Frontend Performance

---

### Security Preservation

Verify refactoring does not weaken:

* Sanitization
* Escaping
* Nonce Verification
* Capability Checks

---

### Maintainability

Check:

* Folder Structure
* Class Structure
* Service Organization
* Documentation Consistency

---

## Refactor Recommendations

Classify:

### Critical Refactor

Must be fixed.

### Recommended Refactor

Should be fixed.

### Optional Refactor

Can be improved later.

---

## Output

Create:

`docs/reviews/refactor-{feature-name}.md`

Include:

1. Summary
2. Files Reviewed
3. Refactor Opportunities
4. Risks
5. Recommendations
6. Refactor Plan
7. Verdict

Verdict:

* NO REFACTOR NEEDED
* RECOMMENDED REFACTOR
* CRITICAL REFACTOR REQUIRED

---

## Refactor Rules

When applying refactoring:

* Preserve functionality
* Preserve public APIs
* Preserve behavior
* Preserve architecture

Do not introduce breaking changes.

---

## Completion Rule

Refactoring is complete only when:

* Architecture remains compliant
* Tests still pass
* Security remains intact
* Documentation remains accurate

End of workflow.
