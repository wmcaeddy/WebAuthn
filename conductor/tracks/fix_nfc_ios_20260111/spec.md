# Specification: Fix NFC Registration Failure on iOS Safari

## Overview
Investigate and resolve a `NotAllowedError` occurring during FIDO token registration via NFC on iOS Safari. The error message ("request is not allowed by the user agent or the platform in the current context") suggests a platform-level restriction or a violation of WebAuthn security requirements (like a missing user gesture).

## Functional Requirements
- **User Gesture Validation:** Ensure that all WebAuthn `navigator.credentials.create` calls in the test clients (`_test/client.html`, `_test/modern_client.html`) are triggered directly by a user interaction (e.g., a `click` event) to satisfy Safari's security requirements.
- **Context Verification:** Verify that the registration request parameters (RP ID, origin) are correctly configured for the current environment.
- **Error Handling:** Improve the feedback in the UI when a platform-level denial occurs, specifically providing guidance for iOS users (e.g., checking if NFC is enabled or if the browser has necessary permissions).

## Non-Functional Requirements
- **Browser Compatibility:** Ensure the fix maintains compatibility with other supported browsers (Chrome, Firefox).
- **Security:** Maintain the strict security standards of the WebAuthn protocol.

## Acceptance Criteria
- WebAuthn registration successfully initiates on iOS Safari when triggered by a user action.
- The `NotAllowedError` is no longer thrown due to code-level issues (like auto-triggering on page load).
- If the platform still denies the request for environmental reasons, the UI displays a clear, actionable error message.

## Out of Scope
- Fixing hardware-level NFC defects or iOS system-level bugs.
- Implementation of new authentication features beyond fixing the registration flow.
