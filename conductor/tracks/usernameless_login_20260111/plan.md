# Implementation Plan - Enable Usernameless Login

This plan outlines the steps to support discoverable credentials (usernameless login) while maintaining mandatory fields for registration.

## Phase 1: Investigation and Comparison [checkpoint: d548516]
Goal: Verify the implementation in the `master` branch.

- [x] Task: Compare `assets/theme/js/webauthn.js` logic with `origin/master:_test/modern_client.html` (or equivalent) to see how it handles empty usernames. d548516
- [x] Task: Inform user of findings and confirm if the implementation should proceed as specified. d548516
- [x] Task: Conductor - User Manual Verification 'Investigation' (Protocol in workflow.md) d548516

## Phase 2: Frontend Logic Update [checkpoint: f3f066a]
Goal: Modify the JS flow to allow empty usernames for login.

- [x] Task: Update `checkRegistration()` in `assets/theme/js/webauthn.js` to remove the username check. 66f8cf7
- [x] Task: Ensure `getGetParams()` returns empty strings for `userName` and `userId` if the fields are empty, rather than null or causing errors. 66f8cf7
- [x] Task: Update `setStatus` or error handling to ignore empty username errors specifically for the login path. 66f8cf7
- [x] Task: Conductor - User Manual Verification 'Frontend Update' (Protocol in workflow.md) f3f066a

## Phase 3: Verification [checkpoint: 9d2f123]
Goal: Test the flow.

- [x] Task: Verify that Registration still requires a username. 53b5624
- [x] Task: Verify that Login initiates WebAuthn even when the username field is empty. 53b5624
- [x] Task: Final audit of the login flow. 53b5624
- [x] Task: Conductor - User Manual Verification 'Final Audit' (Protocol in workflow.md) 9d2f123
