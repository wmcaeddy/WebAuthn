# Implementation Plan - Fix Server Data Management

This plan outlines the steps to fix the individual "Delete" button and verify the "Clear All" functionality in the server data preview.

## Phase 1: Investigation [checkpoint: d548516]
Goal: Identify the root cause of the deletion failure.

- [x] Task: Inspect the HTML source returned by `_test/server.php?fn=getStoredDataHtml` to see how the "Delete" button is defined. d548516
- [x] Task: Check `_test/server.php` for the corresponding delete logic (e.g., `fn=removeRegistration`). d548516
- [x] Task: Check the browser console for errors when clicking "Delete". d548516
- [x] Task: Conductor - User Manual Verification 'Investigation' (Protocol in workflow.md) d548516

## Phase 2: Fix Implementation [checkpoint: 7e5f78b]
Goal: Connect the buttons to the correct logic.

- [x] Task: Update the "Delete" button in `_test/server.php` (or wherever generated) to call a functional JS method. c855440
- [x] Task: Implement/Fix `removeRegistration` logic in `_test/server.php` if it's missing or broken. c855440
- [x] Task: Ensure `assets/theme/js/webauthn.js` has a helper to reload the preview iframe after an action. c855440
- [x] Task: Conductor - User Manual Verification 'Fix Implementation' (Protocol in workflow.md) 7e5f78b

## Phase 3: Verification
Goal: Confirm everything works as expected.

- [ ] Task: Register a few test users.
- [ ] Task: Verify deleting one specific user works.
- [ ] Task: Verify "Clear All" works.
- [ ] Task: Conductor - User Manual Verification 'Verification' (Protocol in workflow.md)
