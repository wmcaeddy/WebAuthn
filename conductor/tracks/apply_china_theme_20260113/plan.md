# Plan: Apply China Airlines Theme

## Objective
Apply the new "China Airlines" theme to `index.php` while preserving the existing WebAuthn/FIDO functionality. Use the new Header and Footer, and replace the new theme's login section with the existing `auth-card` component.

## Context
- **Source:** `20260113035330810/` (HTML, CSS, Assets).
- **Target:** `index.php` (PHP, WebAuthn Logic).
- **Reference:** Previous track `apply_theme_20260111`.

## Steps

### 1. Asset Migration
- [ ] Archive current `assets/theme` to `assets/theme_fairline_bak`.
- [ ] Create clean `assets/theme` directory.
- [ ] Copy all contents from `20260113035330810/` to `assets/theme/`.
- [ ] Ensure `webauthn.js` and `bootstrap` (if needed by FIDO logic) are available. *Note: The new theme includes bootstrap-5.3.3.min.css, so we might need to reconcile versions if conflicts arise, but likely we can use the new one.*

### 2. Component Extraction
- [ ] Extract the "Header" HTML from the new `index.html` into `components/header.php`.
- [ ] Extract the "Footer" HTML from the new `index.html` into `components/footer.php`.

### 3. `index.php` Reconstruction
- [ ] Update `index.php` with the new HTML skeleton (Head, Body wrapper).
- [ ] Include `components/header.php`.
- [ ] Identify the main content container in the new theme.
- [ ] Inject the existing FIDO Authentication Card (`.auth-card`) logic into the main container.
- [ ] Include `components/footer.php`.
- [ ] Ensure `webauthn.js` is included in the `<head>` or before `</body>`.

### 4. Logic Preservation
- [ ] Verify `createRegistration()`, `checkRegistration()`, `updateUserId()` functions are bound correctly.
- [ ] Verify PHP session logic (if any) is preserved at the top of `index.php`.

### 5. Cleanup
- [ ] Verify visual appearance and FIDO functionality.
