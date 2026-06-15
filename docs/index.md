# AlternatePro Elements Documentation

## Project Name

AlternatePro Elements

## Current Version

1.0.0 planned

## Purpose

This file is the master documentation navigation page for the AlternatePro Elements project.

## Quick Navigation

- Session startup context: [Project Context](context.md)
- Completion workflow: [Done Workflow](done.md)
- Primary technical reference: [Architecture](planning/architecture.md)
- Primary development reference: [Implementation Plan](planning/implementation-plan.md)
- Detailed phase documents: [Phase Documents](#phase-documents)
- Mandatory development rules: [Development Rules](development-rules.md)
- Project status: [Status](status.md)
- Release documents: [Release Documentation](releases/README.md)

## Documentation Structure Overview

- `agents/` contains reusable AI agent workflows for implementation support, reviews, and future task automation.
- `phases/` contains detailed phase documents generated from the implementation plan.
- `planning/` contains project planning, architecture, implementation, and feature design documents.
- `reviews/` contains review history, architecture review notes, and security review checklists.
- `testing/` contains QA, testing, performance, and accessibility planning.
- `releases/` contains release notes, release checklists, packaging notes, and future phase summaries.
- `context.md` is the primary session startup document.
- `done.md` is the reusable completion workflow for development tasks.
- `status.md` is the primary project status tracking file.
- `development-rules.md` is the mandatory process and project constitution.

## Project Context

- [Project Context](context.md)

Read this file first at the start of future development sessions.

## Done Workflow

- [Done Workflow](done.md)

Use this workflow after completing implementation, bug fix, refactor, or feature development work.

## Agent Workflows

- [Code Review Agent](agents/review.md)
- [Refactor Agent](agents/refactor.md)
- [Security Audit Agent](agents/security.md)
- [Testing Agent](agents/test.md)

Use these workflows after implementation tasks are completed.

## Project Overview

- [Project Overview](planning/project-overview.md)

## Requirements

- [Requirements](planning/requirements.md)

## Architecture

- [Architecture](planning/architecture.md)

The architecture document is the primary technical reference for module boundaries, class responsibilities, Elementor integration points, WordPress hooks, Theme Builder flow, Conditions flow, Dynamic Data flow, security rules, and testing strategy.

## Plugin File Structure

- [Plugin File Structure](planning/plugin-file-structure.md)

## Widgets

- [Widgets List](planning/widgets-list.md)

## Theme Builder

- [Theme Builder Plan](planning/theme-builder-plan.md)

## Conditions System

- [Conditions System](planning/conditions-system.md)

## Dynamic Tags And Dynamic Data

- [Dynamic Tags And Dynamic Data Resolvers](planning/dynamic-tags.md)

## Admin Settings

- [Admin Settings](planning/admin-settings.md)

## Development Roadmap

- [Development Roadmap](planning/development-roadmap.md)

## Development Rules

- [development-rules.md](development-rules.md)

The official project constitution and mandatory development process for all future work.

## Implementation Plan

- [Implementation Plan](planning/implementation-plan.md)

The implementation plan is the primary development reference for phase sequencing, dependencies, files, classes, and acceptance criteria.

## Phase Documents

Detailed phase documents are generated from [Implementation Plan](planning/implementation-plan.md) and provide goal, scope, deliverables, dependencies, acceptance criteria, excluded scope, and definition of done for each phase.

- [Phase 1 - Foundation](phases/phase-01-foundation.md)
- [Phase 2 - Elementor Integration](phases/phase-02-elementor-integration.md)
- [Phase 3 - Theme Builder Foundation](phases/phase-03-theme-builder-foundation.md)
- [Phase 4 - Conditions Engine](phases/phase-04-conditions-engine.md)
- [Phase 5 - Dynamic Data Engine](phases/phase-05-dynamic-data-engine.md)
- [Phase 6 - Header/Footer Builder](phases/phase-06-header-footer-builder.md)
- [Phase 7 - Single Post Builder](phases/phase-07-single-post-builder.md)
- [Phase 8 - Archive Builder](phases/phase-08-archive-builder.md)
- [Phase 9 - Search Builder](phases/phase-09-search-builder.md)
- [Phase 10 - 404 Builder](phases/phase-10-404-builder.md)
- [Phase 11 - Elementor Widgets](phases/phase-11-elementor-widgets.md)
- [Phase 12 - QA, Performance, Security Review](phases/phase-12-qa-performance-security.md)

## Architecture Review

- [Architecture Review](reviews/architecture-review.md)
- [Documentation Audit](reviews/documentation-audit.md)
- [Documentation Audit Fixes](reviews/documentation-audit-fixes.md)
- [Pre-Development Audit](reviews/pre-development-audit.md)
- [Pre-Development Audit Fixes](releases/pre-development-audit-fixes.md)

## Security Checklist

- [Security Checklist](reviews/security-checklist.md)

## Testing Plan

- [Testing Plan](testing/testing-plan.md)

## Project Status

- [Status](status.md)

`status.md` must be updated at the end of every completed phase.

## Release Documents

- [Release Documentation](releases/README.md)

Future phase summaries must be stored in `releases/`.
