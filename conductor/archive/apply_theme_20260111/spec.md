# Track Specification: Apply Theme to Root Page with FIDO Functionality

## 1. Overview
The goal of this track is to apply a new visual theme (sourced from the folder `20260111055348006`) to the root `index.php` of the application. The new design will replace the existing frontend presentation while strictly preserving the existing WebAuthn (FIDO) registration and login functionality. The resulting page will serve as a polished landing page focused on authentication.

## 2. Functional Requirements
*   **Theme Migration:**
    *   Import HTML structure from the source folder's `index.html` (or equivalent) into `index.php`.
    *   Migrate all necessary CSS (Bootstrap, custom styles), fonts, and image assets from `20260111055348006` to the project's `assets/` directory.
    *   Migrate UI-related JavaScript (animations, sliders) ensuring no conflict with WebAuthn logic.
*   **WebAuthn Integration:**
    *   Embed the existing WebAuthn registration and login controls (buttons, forms, status messages) into the "Hero" or "Login" section of the new theme.
    *   Ensure all existing JavaScript logic for WebAuthn (registration flow, authentication flow) continues to function correctly.
    *   The visual style of the WebAuthn controls should be updated to match the new theme (using the theme's CSS classes) without breaking functionality.
*   **Content Cleanup:**
    *   Remove unrelated static sections (e.g., "About Us", "Pricing") from the imported theme, keeping the focus on the Authentication/Hero section.

## 3. Non-Functional Requirements
*   **Responsive Design:** The new page must remain fully responsive and mobile-friendly, as per the theme's original design.
*   **Asset Organization:** New assets should be organized cleanly within the `assets/` directory, avoiding clutter in the root.
*   **Performance:** Ensure that the added assets (CSS/JS libraries) do not negatively impact the load time significantly.

## 4. Acceptance Criteria
*   [ ] The root `index.php` displays the new theme correctly (layout, fonts, colors).
*   [ ] All required assets (images, CSS, JS) load without 404 errors.
*   [ ] The WebAuthn "Register" and "Login" buttons are visible and styled according to the new theme.
*   [ ] Clicking "Register" successfully initiates the WebAuthn registration flow and completes without error.
*   [ ] Clicking "Login" successfully initiates the WebAuthn authentication flow and completes without error.
*   [ ] The page is responsive on mobile and desktop viewports.
*   [ ] Unnecessary static sections from the original theme are removed.

## 5. Out of Scope
*   Backend logic changes (PHP classes).
*   Creating new WebAuthn features (only porting existing).
