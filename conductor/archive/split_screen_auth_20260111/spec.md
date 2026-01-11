# Track Specification: Split-Screen Layout with Right-Side Authentication

## 1. Overview
The goal of this track is to refactor the current landing page into a split-screen layout. The existing Hero slider will be constrained to the left half of the screen, while the authentication (FIDO) interface will be moved to a dedicated column on the right side. This layout will adapt to mobile devices by stacking the sections vertically.

## 2. Functional Requirements
*   **Split-Screen Layout:**
    *   Implement a two-column grid/flex layout for desktop viewports.
    *   Left Column: Display the existing "Hero" slider (as defined in `components/hero.php`).
    *   Right Column: Display the WebAuthn/FIDO authentication card.
*   **Component Refactoring:**
    *   Modify `index.php` and `components/hero.php` to accommodate the split-screen structure.
    *   Ensure the Hero section is responsive within its half-width container.
*   **Authentication UI:**
    *   Move the `#auth-section` (currently below the hero) into the right column of the new layout.
    *   Maintain the current "card" visual style (border-radius, shadow, padding) for the login/register box.
    *   Vertically center the authentication card within the right column.
*   **Mobile Responsiveness:**
    *   Implement media queries to switch from a 50/50 split to a 100% width stacked layout on screens smaller than 768px.
    *   In mobile view, the Hero section should appear first, followed by the Authentication section.

## 3. Non-Functional Requirements
*   **Visual Consistency:** Retain the current theme's fonts, colors, and premium aesthetic.
*   **Smooth Transitions:** Ensure the layout shift between desktop and mobile is fluid.
*   **Maintain FIDO Integrity:** Ensure all WebAuthn JavaScript logic remains fully functional in the new layout.

## 4. Acceptance Criteria
*   [ ] On desktop, the page is split 50/50 between the slider and the auth interface.
*   [ ] The authentication card is clearly visible on the right side.
*   [ ] The slider functions correctly within the left half of the screen.
*   [ ] On mobile ( < 768px), the layout stacks vertically (Hero on top, Auth below).
*   [ ] Registration and Login flows continue to work without JS errors.
*   [ ] The visual style (shadows, colors) remains consistent with the current theme.

## 5. Out of Scope
*   Adding new WebAuthn features.
*   Backend PHP logic changes.
*   Changing the header or footer structure.
