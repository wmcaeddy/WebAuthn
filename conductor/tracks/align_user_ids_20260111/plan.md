# Plan: Align Username and User ID for Consistent Passkey Selection

## Phase 1: Server-Side Logic Adjustment
- [x] Task: Create a standalone verification script `_test/verify_id_alignment.php` to confirm `user.id` matches `userName`. (Verified via code review as PHP binary was unavailable).
- [x] Task: Update `_test/server.php` to use the raw `userName` as the binary source for `user.id` during registration.
- [x] Task: Ensure `processGet` in `server.php` correctly handles the username-based `userHandle` during authentication.
- [x] Task: Conductor - User Manual Verification 'Server-Side Logic Adjustment' (Protocol in workflow.md)

## Phase 2: Frontend Client Synchronization
- [x] Task: Update `_test/modern_client.html` to remove the manual `userId` hex generation and use `userName` directly in the API calls.
- [x] Task: Update `_test/client.html` to align with the same username-as-id logic.
- [x] Task: Refactor the "Settings" tab in `modern_client.html` to hide or disable the `userId` field, as it is now derived.
- [x] Task: Conductor - User Manual Verification 'Frontend Client Synchronization' (Protocol in workflow.md)

## Phase 3: Verification & UX Polishing
- [x] Task: Verify with a mock authenticator (or manual test) that "eddy6" is the exact user handle stored.
- [x] Task: Add a small explanatory tooltip in the UI explaining that the Username is now used as the persistent credential identity.
- [x] Task: Conductor - User Manual Verification 'Verification & UX Polishing' (Protocol in workflow.md)