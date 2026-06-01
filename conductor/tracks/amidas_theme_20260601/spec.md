# Specification: Amidas Theme Migration

## Overview
Migrate the project's user interface from the existing "Khan Bank" theme to the new "Amidas" theme, ensuring that all existing WebAuthn (FIDO2) functionality (registration and authentication) remains fully operational.

## Functional Requirements
- **Theme Application:** Apply the "Amidas" visual style to the primary authentication interface.
- **FIDO2 Consistency:** Maintain existing JavaScript-based WebAuthn registration and login flows.
- **UI State Handling:** Port loading indicators, error/success messages, and status updates to the new theme.
- **Responsive Design:** Ensure the new theme is mobile-friendly and responsive.

## Non-Functional Requirements
- **Maintainability:** Keep the PHP backend logic decoupled from the theme as much as possible.
- **Performance:** Optimize asset loading (CSS/JS/Fonts) from the `amidas` folder.

## Acceptance Criteria
- [ ] The "Amidas" theme is correctly displayed as the default UI.
- [ ] Users can successfully register a new WebAuthn credential using the new UI.
- [ ] Users can successfully authenticate with a WebAuthn credential using the new UI.
- [ ] Error messages and loading states are clearly visible and contextually appropriate in the new theme.
- [ ] Automated tests for WebAuthn registration and login pass with the new UI.

## Out of Scope
- Adding new FIDO2 features (e.g., support for new credential types).
- Refactoring the core WebAuthn PHP library.
