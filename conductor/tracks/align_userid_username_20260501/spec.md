# Specification: Align UserId with UserName

## Problem Statement
While the recent standardized `userId` logic fixed cross-platform authentication failures by ensuring consistency, it uses random bytes which results in a random hex string being displayed on the user's authenticator (e.g., iPhone passkey list). This makes it difficult for users to identify which passkey belongs to which account.

## Goals
1. Align the `userId` (User Handle) with the `userName` (e.g., `eddy2`).
2. Maintain the robust hex-based verification in the backend to ensure continued cross-platform support.
3. Ensure that new registrations display the human-readable username on the authenticator.

## Requirements
- The `user.id` passed to `navigator.credentials.create` must be the binary representation of the `userName` string.
- The server storage must continue to use the hex-encoded version of this ID for compatibility and ease of lookup.
- The verification logic in `processGet` must correctly handle both string-based IDs and potential future binary-only IDs by using consistent hex comparison.

## Success Criteria
- A user registering as `eddy2` on any platform sees `eddy2` as the account identifier on their hardware token or passkey list.
- Cross-platform authentication (Windows to iPhone) remains functional.
