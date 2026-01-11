# Specification: Align Username and User ID for Consistent Passkey Selection

## Overview
Currently, the test environment uses a hex-encoded User ID that can be independent of the Username. This causes confusion when using discoverable credentials (passkeys) on FIDO2 hardware tokens, as some authenticators may display the raw or base64-encoded User ID instead of the Display Name, leading to unrecognizable selections like "BtpUqA". This track aims to synchronize these fields so that the identity stored on the token is human-readable and consistent with the login name.

## Functional Requirements
1.  **Identity Synchronization:** Modify the registration flow so that the `user.id` (passed to `navigator.credentials.create`) is the binary equivalent of the `userName` string.
2.  **Client Simplification:** Update `_test/modern_client.html` and `_test/client.html` to remove or hide the manual "User ID (Hex)" field, making the Username the single source of truth for identity.
3.  **Server Logic Update:** Adjust `_test/server.php` to handle the synchronized `userId` consistently during both registration (`processCreate`) and authentication (`processGet`).
4.  **Opaque ID Handling:** Ensure that the string-to-binary conversion for the User ID remains within the WebAuthn limit of 64 bytes.

## Non-Functional Requirements
- **UX Consistency:** The user should only need to manage a "User Name" and "Display Name". The underlying User ID should be handled automatically.
- **Backward Compatibility:** No migration of existing serialized data is required; the focus is on a correct experience for new enrollments.

## Acceptance Criteria
- [ ] Registering a user with name "eddy6" results in a passkey stored on the authenticator where the user handle is exactly "eddy6".
- [ ] During a passkey login (discoverable credential), the authenticator selection prompt clearly identifies the user by their name.
- [ ] The "Settings" tab in the test client no longer requires manual Hex ID entry.
- [ ] Login verification on the server correctly matches the `userHandle` returned by the authenticator against the stored username-based ID.

## Out of Scope
- Migrating or updating old records in `registrations.ser`.
- Changing the core `lbuchs/WebAuthn` library logic (only the test implementation and usage pattern are in scope).