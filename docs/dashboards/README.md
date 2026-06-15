# Dashboard Documentation Rules

## Purpose

`docs/dashboards/` stores human-friendly HTML dashboards and visual project reports for AlternatePro Elements.

Dashboards are visualization layers only. They help developers and stakeholders scan project status quickly, but they do not replace the Markdown documentation.

## Source Of Truth

Markdown files remain the source of truth:

- [Project Status](../status.md)
- [Documentation Index](../index.md)
- [Planning Documents](../planning/)
- [Phase Documents](../phases/)
- [Review Documents](../reviews/)
- [Testing Documents](../testing/)
- [Release Documents](../releases/)

## Dashboard Rules

- Never use HTML dashboards as the source of truth.
- Update Markdown source documents first.
- Regenerate or update dashboards only after source Markdown changes.
- Keep dashboards readable, lightweight, and accessible.
- Use pure HTML and CSS only.
- Do not add external CSS frameworks, JavaScript frameworks, or CDN dependencies.
- Each child dashboard must link back to [Main Dashboard](dashboard.html).

## Dashboard Structure

- `dashboard.html` is the main project dashboard.
- `phases/` contains phase dashboards.
- `reviews/` contains review and audit dashboards.
- `releases/` contains release and phase summary dashboards.
