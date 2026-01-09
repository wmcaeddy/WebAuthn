# Specification - User Post-Login Section

## Overview
Implement a dedicated user section that appears after a successful WebAuthn login. This section will replace the registration/login form and settings box to provide a clear authenticated state for the user.

## Functional Requirements
- **Dynamic UI Transition:** Upon successful authentication, the login/settings form will fade out, and the user section will fade in.
- **Authenticated Information:**
    - Display the user's Full Name (Display Name).
    - Display the User ID (e.g., in @username format).
    - Display the timestamp of the current successful login.
- **Logout Functionality:**
    - Provide a prominent 'Logout' button.
    - Behavior: Trigger a server-side request to clear the PHP session, then return the user to the login/registration form with a smooth transition.

## UI/UX Requirements
- Follow the minimalist, high-contrast aesthetic established in the product guidelines.
- Use CSS transitions for the fade-in/fade-out effect.
- Ensure the user section is as responsive as the login form.

## Out of Scope
- Persistent 'Remember Me' sessions (beyond basic PHP session handling).
- Editing user profile details within this section.
