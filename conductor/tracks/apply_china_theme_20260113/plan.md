# Plan: Apply China Airlines Theme

## Objective
Apply the new "China Airlines" theme to `index.php` while preserving the existing WebAuthn/FIDO functionality. Use the new Header and Footer, and replace the new theme's login section with the existing `auth-card` component.

## Context
- **Source:** `20260113035330810/` (HTML, CSS, Assets).
- **Target:** `index.php` (PHP, WebAuthn Logic).
- **Reference:** Previous track `apply_theme_20260111`.

## Steps

### 1. Asset Migration
- [x] Archive current `assets/theme` to `assets/theme_fairline_bak`.
- [x] Create clean `assets/theme` directory.
- [x] Copy all contents from `20260113035330810/` to `assets/theme/`.
- [x] Ensure `webauthn.js` and `bootstrap` (if needed by FIDO logic) are available. *Note: The new theme includes bootstrap-5.3.3.min.css, so we might need to reconcile versions if conflicts arise, but likely we can use the new one.*

### 2. Component Extraction
- [x] Extract the "Header" HTML from the new `index.html` into `components/header.php`.
- [x] Extract the "Footer" HTML from the new `index.html` into `components/footer.php`.

### 3. `index.php` Reconstruction
- [x] Update `index.php` with the new HTML skeleton (Head, Body wrapper).
- [x] Include `components/header.php`.
- [x] Identify the main content container in the new theme.
- [x] Inject the existing FIDO Authentication Card (`.auth-card`) logic into the main container.
- [x] Include `components/footer.php`.
- [x] Ensure `webauthn.js` is included in the `<head>` or before `</body>`.

### 4. Logic Preservation
- [x] Verify `createRegistration()`, `checkRegistration()`, `updateUserId()` functions are bound correctly.
- [x] Verify PHP session logic (if any) is preserved at the top of `index.php`.

### 5. Cleanup
- [x] Verify visual appearance and FIDO functionality.
