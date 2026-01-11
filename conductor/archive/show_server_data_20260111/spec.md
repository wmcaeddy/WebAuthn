# Track Specification: Implement 'Show Server Data'

## 1. Overview
The goal of this track is to implement the "Show Server Data" functionality in the `fairlinev2` branch, replicating the specific style and behavior found in the `fairlinelayout` branch. This provides a subtle way for developers to inspect stored WebAuthn data.

## 2. Functional Requirements
*   **Toggle Button:**
    *   Text: `[ Show Server Data ]`
    *   Style: Subtle, text-only button (`background:none; border:none; color:#bbb;`).
    *   Location: Inside the Authentication Card, centered below the "Login" and "Register" buttons.
    *   Action: Toggles the visibility of the `#preview-container`.
*   **Preview Container:**
    *   ID: `preview-container`
    *   Content: Header with "Server Data" title and "Clear All" button, plus an `<iframe>` loading the server data.
    *   Style: Replicate the `fairlinelayout` style (simple border, light background iframe).
    *   Location: Inside the Authentication Card, appearing below the toggle button when active.

## 3. Non-Functional Requirements
*   **Visual Consistency:** Must match the minimal aesthetic of the `fairlinelayout` implementation.
*   **Responsiveness:** Ensure the iframe fits within the fixed-width auth card (480px).

## 4. Acceptance Criteria
*   [ ] A `[ Show Server Data ]` button appears below the login buttons.
*   [ ] Clicking the button reveals the server data preview inside the card.
*   [ ] The preview contains a "Clear All" button that works.
*   [ ] The styling matches the provided reference from `fairlinelayout`.

## 5. Out of Scope
*   New server-side logic.
