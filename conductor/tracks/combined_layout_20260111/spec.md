# Specification: Combined Modular ProNew Layout

## Overview
Implement a modern, modular layout for the WebAuthn registration/login page using ProNew branding elements from `@20260111141054139/**`. The layout will feature a split-screen design with a portable, self-contained FIDO login card.

## Functional Requirements
1.  **Split-Screen Layout:**
    *   Left side (`.split-hero`): Responsive slider with ProNew hero images (`main_1.jpg` to `main_5.jpg`) and titles.
    *   Right side (`.split-auth`): Fixed-width (480px on desktop) container for the FIDO login card.
2.  **Modular FIDO Login Card (`.auth-card`):**
    *   Self-contained component with its own styles (shadows, background, rounded corners).
    *   Must remain functional even if moved to different layout positions (modal, inline, sidebar).
3.  **Responsive Design:**
    *   Mobile: Switch to `flex-direction: column-reverse`.
    *   Mobile: Login card appears at the top, hero slider follows.
4.  **Logic-UI Decoupling:**
    *   The WebAuthn logic must communicate with the UI exclusively through specific DOM IDs (`userName`, `userDisplayName`, `login-flow-container`, etc.).
5.  **Branding:**
    *   Primary Color: ProNew Maroon (`#841852`).
    *   Secondary Color: Dark Maroon (`#620030`).
    *   Typography: Arial, Helvetica, Microsoft JhengHei.

## Technical Implementation
*   **HTML/PHP:** Modular structure using components.
*   **CSS:** Flexbox for layout, Media Queries for responsiveness.
*   **JavaScript:** Re-use `webauthn.js` with ensured ID consistency.

## Acceptance Criteria
*   The page displays a split-screen layout on desktop.
*   The layout reverses on mobile (auth card on top).
*   The FIDO login card is visually distinct and floating.
*   Registration and login flows remain functional.
*   All branding assets (logo, hero images) are from the ProNew reference folder.
