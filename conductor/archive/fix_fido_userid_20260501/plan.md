# Implementation Plan: Fix Cross-Platform Authentication Failure (UserId Alignment)

## Phase 1: Investigation & Root Cause Analysis [checkpoint: 81c682d]
- [x] Task: Audit server-side `userId` generation and persistence logic.
    - [x] Analyze `src/` for `userId` mapping and generation.
    - [x] Analyze `_test/server.php` for session and storage handling of user IDs.
- [x] Task: Audit client-side `userId` handling during registration and login.
    - [x] Inspect `_test/modern_client.html` for `userId` parameter passing and encoding.
    - [x] Inspect `_test/client.html` for legacy `userId` handling.
- [x] Task: Develop a manual reproduction checklist for cross-platform failure to confirm the mismatch.
- [x] Task: Conductor - User Manual Verification 'Investigation & Root Cause Analysis' (Protocol in workflow.md)

## Phase 2: Standardizing User Identification [checkpoint: c9d8ac7]
- [x] Task: Design and implement a consistent `userId` management logic in the backend.
    - [x] Write unit tests for the new `userId` generation and retrieval logic.
    - [x] Implement standardized `userId` management in `src/` or `_test/server.php`.
- [x] Task: Ensure `userId` is correctly exposed and handled for different platform requirements (e.g., base64url encoding).
    - [x] Write tests for `userId` encoding/decoding.
    - [x] Update implementation to ensure cross-platform compatibility.
- [x] Task: Conductor - User Manual Verification 'Standardizing User Identification' (Protocol in workflow.md)

## Phase 3: Frontend Alignment and Final Verification [checkpoint: 05cddd7]
- [x] Task: Refactor frontend clients to strictly adhere to the standardized `userId` flow.
    - [x] Write unit tests for frontend `userId` processing.
    - [x] Update `_test/modern_client.html` and `_test/client.html` to handle standardized IDs.
- [x] Task: Perform end-to-end manual verification on Windows and iPhone using the same hardware token.
- [x] Task: Conductor - User Manual Verification 'Frontend Alignment and Final Verification' (Protocol in workflow.md)
