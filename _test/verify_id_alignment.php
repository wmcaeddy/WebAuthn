<?php
require_once __DIR__ . '/../src/WebAuthn.php';

use lbuchs\WebAuthn\WebAuthn;

$rpId = 'localhost';
$userName = 'eddy6';
$userDisplayName = 'Eddy 6';

$webAuthn = new WebAuthn('Test Library', $rpId);

// Simulate getCreateArgs
$createArgs = $webAuthn->getCreateArgs($userName, $userName, $userDisplayName);

// Extract the user ID from the response. 
// In the library, ByteBuffer serializes to "=?BINARY?B?...?=" by default.
$userIdSerialized = $createArgs->publicKey->user->id;
$prefix = '=?BINARY?B?';
$suffix = '?=';

if (str_starts_with($userIdSerialized, $prefix) && str_ends_with($userIdSerialized, $suffix)) {
    $base64 = substr($userIdSerialized, strlen($prefix), -strlen($suffix));
    $decodedId = base64_decode($base64);
    
    echo "Expected ID: " . $userName . "\n";
    echo "Actual ID:   " . $decodedId . "\n";
    
    if ($decodedId === $userName) {
        echo "SUCCESS: User ID matches Username.\n";
        exit(0);
    } else {
        echo "FAILURE: User ID does not match Username.\n";
        exit(1);
    }
} else {
    echo "FAILURE: Unexpected User ID format: " . $userIdSerialized . "\n";
    exit(1);
}

