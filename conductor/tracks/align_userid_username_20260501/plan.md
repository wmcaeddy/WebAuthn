# Implementation Plan: Align UserId with UserName

## Phase 1: Refactoring UserId Logic [checkpoint: 8b42cfe]
- [x] Task: Update `getUserHandle` in `server.php` to return the hex-encoded `userName`.
- [x] Task: Verify that `getCreateArgs` correctly converts this hex back to binary for the client.
- [x] Task: Conductor - User Manual Verification 'Refactoring UserId Logic' (Protocol in workflow.md)

## Phase 2: Verification and Cleanup
- [x] Task: Verify that existing registrations (using random hex IDs) still work for authentication.
- [x] Task: Perform a new registration and verify that the authenticator (e.g., iPhone) shows the username as the account ID.
- [~] Task: Conductor - User Manual Verification 'Verification and Cleanup' (Protocol in workflow.md)
