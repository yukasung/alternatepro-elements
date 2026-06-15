# Testing Agent

## Purpose

This document defines the required testing workflow after implementation has passed code review.

Use this file by running:

Read `docs/agents/test.md`

## Required Context

Before testing, read:

- [docs/context.md](../context.md)
- [docs/status.md](../status.md)
- [docs/development-rules.md](../development-rules.md)
- [docs/planning/architecture.md](../planning/architecture.md)
- [docs/planning/implementation-plan.md](../planning/implementation-plan.md)

# Testing Strategy

Testing Types:

1. Functional Testing
2. Unit Testing
3. Integration Testing
4. Regression Testing

## Functional Testing

Required for all implementations.

Verify:

- Acceptance Criteria
- Expected Behavior
- Frontend Output
- Backend Functionality
- Responsive Behavior
- Elementor Editor Compatibility
- Elementor Frontend Compatibility

## Unit Testing

Required when implementation contains:

- Business Logic
- Conditional Logic
- Service Classes
- Data Transformation
- Helper Classes
- Managers
- Resolvers

Examples:

- Dependency Checker
- Service Container
- Template Resolver
- Condition Matcher
- Dynamic Tag Manager

Verify:

- Expected Inputs
- Expected Outputs
- Edge Cases
- Failure Cases

When applicable:

- Create PHPUnit test recommendations.

## Integration Testing

Required when implementation interacts with:

- WordPress Core APIs
- Elementor APIs
- Theme Builder
- Conditions Engine
- Dynamic Tags
- Admin Settings

Verify:

- Component Interaction
- Hook Registration
- Service Registration
- Dependency Loading

## Regression Testing

Verify:

- Existing functionality still works
- No previously completed phase is broken
- No existing widgets are affected
- No existing templates are affected

## Test Report

Create:

`docs/testing/test-{feature-name}.md`

Include:

1. Summary
2. Functional Test Results
3. Unit Test Requirements
4. Integration Test Results
5. Regression Test Results
6. Risks
7. Recommendations
8. Verdict

Verdict:

- PASS
- PASS WITH MINOR ISSUES
- FAIL

## Rules

Do not modify source code.

Do not implement fixes.

Testing only.

If issues are found:

- Describe the issue
- Describe the impact
- Recommend a fix

Do not apply the fix.

## Completion Rule

Testing is considered complete only when:

- Functional Testing completed
- Unit Test evaluation completed
- Integration Testing completed
- Regression Testing completed
- A test report has been created
