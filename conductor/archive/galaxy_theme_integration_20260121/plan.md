# Implementation Plan: Galaxy Macau Theme Integration

This plan transforms the FIDO2 demo into a branded Galaxy Macau experience, moving the primary interface to the root `index.php`.

## Phase 1: Foundation and Asset Integration
- [x] Task: Integrate Theme Assets
    - [x] Create `assets/theme/` directory structure for CSS, fonts, and images.
    - [x] Copy fonts (Poppins) and brand assets from the reference files.
    - [x] Consolidate theme CSS files (`style.2a9e0de6901cd2b8a4a6.css`, `style.d597768381caa428f660.css`) into the project.
- [x] Task: Setup Root index.php
    - [x] Replace the redirect in `index.php` with the base HTML structure from the theme.
    - [x] Configure asset paths to point to the new project structure.
    - [x] Verify the basic themed page loads at the root URL.
- [ ] Task: Conductor - User Manual Verification 'Phase 1: Foundation and Asset Integration' (Protocol in workflow.md)

## Phase 2: WebAuthn Interface Implementation
- [x] Task: Implement Themed Login/Registration Forms
    - [x] Adapt the theme's login form to include "User Name" and "Display Name" inputs.
    - [x] Implement the "Sign In" vs "Join" toggle to switch between Authentication and Registration states.
    - [x] Add the FIDO2 branding and instructions to the forms.
- [x] Task: Port and Adapt WebAuthn JS Logic
    - [x] Migrate the core WebAuthn JS handshake logic from `_test/modern_client.html`.
    - [x] Update field selectors to match the new themed HTML structure.
    - [x] Implement status messaging (Loading/Success/Error) using the theme's notification styles.
- [ ] Task: Conductor - User Manual Verification 'Phase 2: WebAuthn Interface Implementation' (Protocol in workflow.md)

## Phase 3: Authenticated State and UI Polish
- [x] Task: Implement Themed Authenticated State
    - [x] Create the "Profile/Account" view using Galaxy Macau dashboard styles.
    - [x] Display User Display Name, User ID, and session metadata (login time).
    - [x] Implement the Logout button and associated JS logic.
- [x] Task: Transitions and UI Polish
    - [x] Add fade-in/out transitions between Login, Registration, and Profile states.
    - [x] Ensure mobile responsiveness for the entire flow (Safari/Chrome on iOS/Android).
    - [x] Audit touch targets and accessibility.
- [ ] Task: Conductor - User Manual Verification 'Phase 3: Authenticated State and UI Polish' (Protocol in workflow.md)

## Phase 4: Advanced Features and Cleanup
- [x] Task: Integrate Settings and Debugging Tools
    - [x] Port the WebAuthn configuration settings (RP ID, UV, Formats) into a themed modal or settings panel.
    - [x] Integrate the "Data Preview" iframe and "Clear All" functionality.
- [x] Task: Final Cleanup
    - [x] Remove or deprecate `_test/modern_client.html` as it is replaced by the root index.
    - [x] Ensure all relative paths for `server.php` are correctly updated for the root context.
- [ ] Task: Conductor - User Manual Verification 'Phase 4: Advanced Features and Cleanup' (Protocol in workflow.md)
