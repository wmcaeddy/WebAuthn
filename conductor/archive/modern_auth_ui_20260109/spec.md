# Specification - Modern Auth UI

## Overview
Transform the current functional `_test/client.html` and `_test/server.php` implementation into a modern, user-centric authentication interface. The goal is to provide a clean, responsive, and mobile-first experience for WebAuthn registration and login.

## Requirements
- **Minimalist Aesthetic:** Clean UI with high contrast and intuitive navigation.
- **Responsive Layout:** Optimized for both desktop (workstation) and mobile devices.
- **Vanilla implementation:** Stick to high-quality HTML5, CSS3, and Vanilla JavaScript to keep the library dependencies low.
- **Maintain Compatibility:** Ensure all existing library features (attestation formats, resident keys, etc.) remain functional.
- **Improved Feedback:** Add clear visual indicators for "Waiting for User", "Success", and "Error" states.

## Technical Details
- Use a single-page approach for the auth flow.
- Implement CSS Flexbox/Grid for layout.
- Decouple UI logic from the core WebAuthn API calls where possible.
