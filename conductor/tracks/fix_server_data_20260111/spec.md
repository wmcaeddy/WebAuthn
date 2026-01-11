# Track Specification: Fix Server Data Management (Delete & Clear All)

## 1. Overview
The goal of this track is to fix and verify the server data management functions within the "Server Data Preview" area. Specifically, the "Delete" button for individual credentials and the "Clear All" button are reported as not working or needing verification.

## 2. Functional Requirements
*   **Fix Individual Delete:**
    *   Identify why the "Delete" button for a specific credential (e.g., `demo`, `eddy`) is failing.
    *   Ensure the button correctly triggers a JavaScript function (e.g., `deleteCredential(credentialId)`) that sends a request to `server.php`.
    *   Ensure the backend correctly processes the deletion of a specific credential.
*   **Verify Clear All:**
    *   Ensure the "Clear All" button in the Server Data Preview header correctly triggers `clearRegistration()`.
    *   Verify the data is successfully removed from the server and the preview iframe refreshes.
*   **Search Functionality (Implicit):**
    *   Ensure the "Search by name or ID..." input (if present) correctly filters the credential list.

## 3. Non-Functional Requirements
*   **User Feedback:** Show a confirmation dialog before deleting/clearing.
*   **Reliability:** The preview should always reflect the actual server state after an action.

## 4. Acceptance Criteria
*   [ ] Clicking "Delete" next to a credential removes that specific entry from the server.
*   [ ] Clicking "Clear All" removes all credentials from the server.
*   [ ] The iframe refreshes automatically after a delete/clear action.
*   [ ] All actions show appropriate success/error messages in the UI.

## 5. Out of Scope
*   Adding new server-side endpoints beyond what's needed for the fix.
