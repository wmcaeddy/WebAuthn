# Implementation Plan: Fix Cross-Platform Authentication Failure (UserId Alignment)

## Phase 1: Investigation & Root Cause Analysis
- [x] Task: Audit server-side userId generation and persistence logic.
    - [x] Analyze `src/` for `userId` mapping and generation.
    - [x] Analyze `_test/server.php` for session and storage handling of user IDs.
- [x] Task: Audit client-side `userId` handling during registration and login.
    - [x] Inspect `_test/modern_client.html` for `userId` parameter passing and encoding.
    - [x] Inspect `_test/client.html` for legacy `userId` handling.
- [x] Task: Develop a manual reproduction checklist for cross-platform failure to confirm the mismatch.
- [~] Task: Conductor - User Manual Verification 'Investigation & Root Cause Analysis' (Protocol in workflow.md)

## Phase 2: Standardizing User Identification
- [ ] Task: Design and implement a consistent `userId` management logic in the backend.
    - [ ] Write unit tests for the new `userId` generation and retrieval logic.
    - [ ] Implement standardized `userId` management in `src/` or `_test/server.php`.
- [ ] Task: Ensure `userId` is correctly exposed and handled for different platform requirements (e.g., base64url encoding).
    - [ ] Write tests for `userId` encoding/decoding.
    - [ ] Update implementation to ensure cross-platform compatibility.
- [ ] Task: Conductor - User Manual Verification 'Standardizing User Identification' (Protocol in workflow.md)

## Phase 3: Frontend Alignment and Final Verification
- [ ] Task: Refactor frontend clients to strictly adhere to the standardized `userId` flow.
    - [ ] Write unit tests for frontend `userId` processing.
    - [ ] Update `_test/modern_client.html` and `_test/client.html` to handle standardized IDs.
- [ ] Task: Perform end-to-end manual verification on Windows and iPhone using the same hardware token.
- [ ] Task: Conductor - User Manual Verification 'Frontend Alignment and Final Verification' (Protocol in workflow.md)
