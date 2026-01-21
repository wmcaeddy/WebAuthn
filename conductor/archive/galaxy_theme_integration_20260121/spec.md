# Specification: Galaxy Macau Theme Integration

## Overview
Transform the existing WebAuthn (FIDO2) server demo into a polished, branded experience using the "Galaxy Macau" theme. This track will move the primary entry point to the root `index.php`, replacing the current redirect and providing a fully functional authentication interface that mimics a real-world luxury resort login.

## Functional Requirements
- **Themed Entry Point**: Replace the root `index.php` with a themed page based on the Galaxy Macau design.
- **WebAuthn Registration**: 
    - Use the theme's form style for "User Name" and "Display Name" inputs.
    - Integrate with `_test/server.php` to handle credential creation.
- **WebAuthn Authentication**:
    - Allow users to sign in using existing passkeys.
    - Provide a "Sign In" vs "Join" (Registration) toggle using the theme's UI components.
- **Authenticated State**:
    - Show a themed "Account" or "Profile" view upon successful login.
    - Display the user's name and login metadata.
- **Session Management**:
    - Implement "Logout" functionality that clears the session and returns to the login view.
- **Configuration & Debugging**:
    - Provide a way to access WebAuthn settings (RP ID, UV, etc.) within the themed UI.
    - Include the "Data Preview" and "Clear All" functions for demonstration purposes.

## UI/UX Requirements
- **Branding**: Use the Poppins font, gold/brown color palette, and iconography from the Galaxy Macau assets.
- **Responsive Design**: Ensure the page is fully functional and visually appealing on mobile devices (iOS/Android).
- **Smooth Transitions**: Use fade-in/out animations for state changes (e.g., between Login and Profile).
- **User Name Only**: Strictly use "User Name" instead of "Email" to maintain consistency with the underlying FIDO logic.

## Technical Constraints
- **Preserve Backend**: Do not modify `_test/server.php` logic unless absolutely necessary for UI compatibility.
- **Vanilla JS**: Maintain the use of Vanilla JavaScript for WebAuthn handshakes to avoid adding large framework dependencies.
- **Asset Organization**: Consolidate theme assets (CSS, Fonts, Images) into the project's structure.

## Out of Scope
- Backend refactoring or database changes (sticking to the serialized file storage).
- Implementing actual email verification or recovery flows.
