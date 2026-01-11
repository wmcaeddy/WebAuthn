# Specification: Fairline Visual Alignment

## Overview
The goal of this track is to overhaul the visual identity and user experience of the Fairline WebAuthn project to align closely with the established brand aesthetic of `https://www.fairline.com.tw/`. This involves updating the landing page, global components, and interaction patterns to create a premium, high-fidelity experience.

## Functional Requirements
- **Branding Integration**: Source the official logo and brand assets from the reference site and integrate them into the application.
- **Global Layout**: Implement a common header and footer mirroring the reference site's navigation and structure. These will be applied to `index.php` and modern UI pages.
- **Visual Styling**:
    - Apply the color palette, typography (fonts, sizing), and component styling (buttons, cards) from `fairline.com.tw`.
    - Ensure a modern, responsive (mobile-first) layout.
- **High-Fidelity Interactions**:
    - Replicate the overall scrolling experience, including section transitions and simple animations.
    - Match standard interactive behaviors like hover states and navigation menu transitions.
- **Landing Page Refresh**: Update `index.php` to follow the grid system and content layout of the reference site's primary sections.

## Non-Functional Requirements
- **Performance**: Optimize any sourced assets (images, fonts) for fast loading.
- **Cross-Browser Consistency**: Ensure the new styling and transitions work smoothly across modern browsers.

## Acceptance Criteria
- [ ] Visual design of the header, footer, and landing page significantly matches `fairline.com.tw`.
- [ ] Brand assets (logo, favicon) are correctly implemented.
- [ ] Navigation is functional and matches the reference site's behavior.
- [ ] Section transitions and scrolling experience are smooth and consistent with the reference site.
- [ ] The site remains fully responsive on mobile devices.
- [ ] **Verification**: `_test/client.html` remains completely untouched and functional in its original state.

## Out of Scope
- **`_test/client.html`**: This file is explicitly excluded from all changes.
- Full replication of all sub-pages found on `fairline.com.tw` (only relevant auth-related or landing pages).
- Modifications to the core WebAuthn logic/backend (unless styling requires template structure changes).
