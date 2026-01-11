# Implementation Plan - Split-Screen Layout with Right-Side Auth

This plan outlines the steps to refactor the landing page into a split-screen layout, constraining the hero slider to the left and moving the FIDO authentication card to the right.

## Phase 1: Layout Refactoring [checkpoint: 3808dfe]
Goal: Implement the core split-screen structure in `index.php`.

- [x] Task: Modify `index.php` to wrap the Hero and Auth sections in a two-column grid/flex container. 3a37447
- [x] Task: Adjust `components/hero.php` to ensure the slider works correctly in a constrained (50% width) container. 3a37447
- [x] Task: Move the `#auth-section` from its current position to the right column of the new container. 3a37447
- [x] Task: Conductor - User Manual Verification 'Layout Refactoring' (Protocol in workflow.md) 3808dfe

## Phase 2: Visual Styling and Centering [checkpoint: 4fe51f0]
Goal: Refine the styling for the right-side authentication column.

- [x] Task: Update the CSS in `index.php` to vertically and horizontally center the `.auth-wrapper` within the right column. 3a37447
- [x] Task: Refine the Hero section CSS to ensure images and overlays adapt to the 50% width. 3a37447
- [x] Task: Conductor - User Manual Verification 'Visual Styling' (Protocol in workflow.md) 4fe51f0

## Phase 3: Mobile Responsiveness [checkpoint: f1e28db]
Goal: Ensure the layout stacks correctly on small screens.

- [x] Task: Add media queries to `index.php` to stack the columns (Hero on top, Auth below) on viewports < 768px. 3a37447
- [x] Task: Adjust mobile-specific padding and margins for the auth card to ensure a clean mobile UX. 4df002a
- [x] Task: Conductor - User Manual Verification 'Mobile Responsiveness' (Protocol in workflow.md) f1e28db

## Phase 4: Verification and Polish [checkpoint: 7b0f602]
Goal: Final audit of functionality and visual integrity.

- [x] Task: Verify that all FIDO JS logic (Registration/Login) targets correctly in the new DOM structure. 1dd6cc1
- [x] Task: Final audit of responsiveness and asset loads. 1dd6cc1
- [x] Task: Conductor - User Manual Verification 'Final Polish' (Protocol in workflow.md) 7b0f602
