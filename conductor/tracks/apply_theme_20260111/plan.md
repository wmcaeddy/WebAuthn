# Implementation Plan - Apply Theme to Root Page

This plan outlines the steps to migrate the visual theme from the `20260111055348006` folder to the root `index.php` while preserving existing FIDO (WebAuthn) functionality.

## Phase 1: Asset Preparation and Organization
Goal: Organize and migrate all theme-related assets to the project structure.

- [x] Task: Migrate CSS, JS, Fonts, and Image assets from `20260111055348006/` to `assets/theme/`. 0e7f295
- [x] Task: Verify all asset paths and ensure they are accessible via the web server. 181c102
- [ ] Task: Conductor - User Manual Verification 'Asset Preparation' (Protocol in workflow.md)

## Phase 2: Core Layout Migration
Goal: Replace the existing `index.php` structure with the themed Hero section.

- [ ] Task: Extract the main HTML structure and "Hero" section from `20260111055348006/index.html`.
- [ ] Task: Update `index.php` with the new HTML structure, including `<head>` links for CSS and scripts.
- [ ] Task: Remove unnecessary static sections (About, Features, etc.) identified in the spec.
- [ ] Task: Conductor - User Manual Verification 'Core Layout Migration' (Protocol in workflow.md)

## Phase 3: FIDO Functionality Integration
Goal: Re-integrate the WebAuthn JavaScript logic and UI controls into the new theme.

- [ ] Task: Port existing WebAuthn JavaScript logic (from current `index.php` or `_test/`) into the new page.
- [ ] Task: Map the themed "Login" and "Register" buttons (or create new ones) to the WebAuthn JS functions.
- [ ] Task: Ensure FIDO status messages and error feedback are styled and visible within the new layout.
- [ ] Task: Conductor - User Manual Verification 'FIDO Functionality Integration' (Protocol in workflow.md)

## Phase 4: Final Polishing and Mobile Optimization
Goal: Ensure the final result is polished and fully responsive.

- [ ] Task: Refine CSS to ensure the WebAuthn controls look native to the theme.
- [ ] Task: Verify responsiveness on mobile viewports.
- [ ] Task: Perform a final audit of asset loads to ensure no 404s or console errors.
- [ ] Task: Conductor - User Manual Verification 'Final Polishing' (Protocol in workflow.md)
