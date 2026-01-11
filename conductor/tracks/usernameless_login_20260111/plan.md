# Implementation Plan - Enable Usernameless Login

This plan outlines the steps to support discoverable credentials (usernameless login) while maintaining mandatory fields for registration.

## Phase 1: Investigation and Comparison [checkpoint: d548516]
Goal: Verify the implementation in the `master` branch.

- [x] Task: Compare `assets/theme/js/webauthn.js` logic with `origin/master:_test/modern_client.html` (or equivalent) to see how it handles empty usernames. d548516
- [x] Task: Inform user of findings and confirm if the implementation should proceed as specified. d548516
- [x] Task: Conductor - User Manual Verification 'Investigation' (Protocol in workflow.md) d548516

## Phase 2: Frontend Logic Update
Goal: Modify the JS flow to allow empty usernames for login.

- [ ] Task: Update `checkRegistration()` in `assets/theme/js/webauthn.js` to remove the username check.
- [ ] Task: Ensure `getGetParams()` returns empty strings for `userName` and `userId` if the fields are empty, rather than null or causing errors.
- [ ] Task: Update `setStatus` or error handling to ignore empty username errors specifically for the login path.
- [ ] Task: Conductor - User Manual Verification 'Frontend Update' (Protocol in workflow.md)

## Phase 3: Verification
Goal: Test the flow.

- [ ] Task: Verify that Registration still requires a username.
- [ ] Task: Verify that Login initiates WebAuthn even when the username field is empty.
- [ ] Task: Final audit of the login flow.
- [ ] Task: Conductor - User Manual Verification 'Final Audit' (Protocol in workflow.md)
