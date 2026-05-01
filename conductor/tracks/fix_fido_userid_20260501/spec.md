# Specification: Fix Cross-Platform Authentication Failure (UserId Alignment)

## Problem Statement
A hardware FIDO token registered on a Windows workstation works for authentication on that same environment, but fails when used on an iPhone. The hypothesis is that there is an inconsistency in how the `userId` is generated, stored, or retrieved across different platforms/browsers, leading to the iPhone being unable to look up the correct user associated with the token.

## Goals
1. Identify the root cause of the `userId` mismatch between Windows and iOS Safari.
2. Standardize `userId` management to ensure cross-platform compatibility.
3. Verify successful registration and authentication on both Windows and iPhone using the same hardware token.

## Requirements
- The `userId` must be persistent and consistently mapped to the user regardless of the platform used for registration or authentication.
- The system must handle cases where the platform (like iOS) requires a specific `userId` format or handling to correctly identify the passkey.
- Maintain compatibility with the existing WebAuthn (FIDO2) server library.

## Success Criteria
- User can register a hardware FIDO token on a Windows PC.
- User can successfully authenticate with the same hardware FIDO token on an iPhone.
- Automated tests verify the consistency of `userId` generation and retrieval.
