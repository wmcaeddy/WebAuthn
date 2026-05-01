# Reproduction Checklist: Cross-Platform UserId Mismatch

## Environment Setup
- **Server:** Ensure the WebAuthn server is accessible via HTTPS (required for WebAuthn).
- **Windows Workstation:** A PC with a modern browser (Chrome, Edge) and a hardware FIDO2 token (e.g., YubiKey).
- **iPhone:** An iPhone with iOS 15+ and Safari.

## Step 1: Registration on Windows
1. Open the WebAuthn client (`_test/modern_client.html`) on the Windows workstation.
2. Enter a unique **User Name** (e.g., `testuser1`) and **Display Name**.
3. In **Settings**, ensure **Discoverable Credentials (Passkeys)** is **CHECKED**.
4. Click **New Registration**.
5. Follow the browser prompts to register the hardware FIDO token.
6. Verify "Registration successful" is displayed.
7. Click **Toggle Data Preview** and verify that a registration for `testuser1` exists on the server.
8. **Note the User ID** displayed in the server preview (it should be the hex of the username).

## Step 2: Authentication on Windows (Baseline)
1. On the same Windows workstation, click **Sign In with Passkey**.
2. Verify that authentication succeeds.

## Step 3: Authentication on iPhone
1. Open the same WebAuthn client URL in Safari on the iPhone.
2. Plug in or tap the same hardware FIDO token used in Step 1.
3. **Scenario A (Discovery):** Do NOT enter a username. Click **Sign In with Passkey**.
    - *Expected Behavior:* The iPhone should prompt to select the passkey, show the identity, and authenticate.
    - *Failure Mode:* iPhone says "No passkeys found" or fails after selection with a server error.
4. **Scenario B (With Username):** Enter the same username (`testuser1`) and click **Sign In with Passkey**.
    - *Expected Behavior:* Authentication succeeds.
    - *Failure Mode:* Server returns "userId doesnt match" or another error.

## Step 4: Documentation
- Record the exact error message displayed on the iPhone or in the server logs (if accessible).
- Verify if the `userHandle` returned by the iPhone (visible in the network trace if possible) matches the expected `userId`.
