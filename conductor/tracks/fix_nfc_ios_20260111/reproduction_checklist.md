# Reproduction Checklist: NFC Registration Failure on iOS Safari

## Prerequisites
- An iOS device (iPhone) with NFC support.
- A FIDO2 security key with NFC support.
- Access to the WebAuthn test site over **HTTPS**.

## Steps to Reproduce
1.  Open Safari on the iOS device.
2.  Navigate to the `_test/modern_client.html` page (or `_test/client.html`).
3.  Enter a "User Name" and "Display Name".
4.  Ensure "NFC" is checked under "Authenticator Types".
5.  Click the **"New Registration"** button.
6.  Observe if the iOS native "Ready to Scan" dialog appears or if an error message is displayed immediately.
7.  If an error message appears, check if it says `NotAllowedError` or "The request is not allowed by the user agent...".

## Expected Result
The iOS native NFC scanning dialog should appear, allowing the user to tap their security key.

## Actual Result (Reported)
A `NotAllowedError` is thrown, potentially because the asynchronous `fetch` call between the button click and the `navigator.credentials.create` call is causing Safari to invalidate the user gesture context.
