<?php

/*
 * Copyright (C) 2022 Lukas Buchs
 * license https://github.com/lbuchs/WebAuthn/blob/master/LICENSE MIT
 *
 * Server test script for WebAuthn library. Saves new registrations in serialized file.
 */

require_once '../src/WebAuthn.php';

// Helper to ensure clean JSON output
function sendResponse($data) {
    header('Content-Type: application/json');
    echo json_encode($data, JSON_THROW_ON_ERROR);
    exit;
}

try {
    session_start();

    // Data storage setup
    $dataDir = '/app/data';
    // Fallback if not writable (local dev or misconfig)
    if (!is_dir($dataDir) || !is_writable($dataDir)) {
        $dataDir = sys_get_temp_dir();
    }
    
    // Use .ser extension for serialized data (JSON is not compatible with ByteBuffer objects)
    $registrationsFile = $dataDir . '/registrations.ser';

    // Helper to load registrations
    function loadRegistrations($file) {
        if (file_exists($file)) {
            $content = file_get_contents($file);
            if ($content !== false) {
                $data = unserialize($content);
                if (is_array($data)) {
                    return $data;
                }
            }
        }
        return [];
    }

    // Helper to save registrations
    function saveRegistrations($file, $data) {
        $serialized = serialize($data);
        if (file_put_contents($file, $serialized) === false) {
            error_log("WebAuthn: Failed to save data to " . $file);
            throw new Exception("Failed to write to persistent storage at /app/data");
        }
    }

    // read get argument and post body
    $fn = filter_input(INPUT_GET, 'fn');
    $requireResidentKey = !!filter_input(INPUT_GET, 'requireResidentKey');
    $userVerification = filter_input(INPUT_GET, 'userVerification', FILTER_SANITIZE_SPECIAL_CHARS);

    $userId = filter_input(INPUT_GET, 'userId', FILTER_SANITIZE_SPECIAL_CHARS);
    $userName = filter_input(INPUT_GET, 'userName', FILTER_SANITIZE_SPECIAL_CHARS);
    $userDisplayName = filter_input(INPUT_GET, 'userDisplayName', FILTER_SANITIZE_SPECIAL_CHARS);

    $userId = $userId ? preg_replace('/[^0-9a-f]/i', '', $userId): "";
    $userName = $userName ? preg_replace('/[^0-9a-z]/i', '', $userName): "";
    $userDisplayName = $userDisplayName ? preg_replace('/[^0-9a-z öüäéèàÖÜÄÉÈÀÂÊÎÔÛâêîôû]/i', '', $userDisplayName): "";

    $post = trim(file_get_contents('php://input'));
    if ($post) {
        $post = json_decode($post, null, 512, JSON_THROW_ON_ERROR);
    }

    if ($fn !== 'getStoredDataHtml' && $fn !== 'deleteRegistration' && $fn !== 'checkLogin') {

        // Formats
        $formats = [];
        if (filter_input(INPUT_GET, 'fmt_android-key')) $formats[] = 'android-key';
        if (filter_input(INPUT_GET, 'fmt_android-safetynet')) $formats[] = 'android-safetynet';
        if (filter_input(INPUT_GET, 'fmt_apple')) $formats[] = 'apple';
        if (filter_input(INPUT_GET, 'fmt_fido-u2f')) $formats[] = 'fido-u2f';
        if (filter_input(INPUT_GET, 'fmt_none')) $formats[] = 'none';
        if (filter_input(INPUT_GET, 'fmt_packed')) $formats[] = 'packed';
        if (filter_input(INPUT_GET, 'fmt_tpm')) $formats[] = 'tpm';

        $rpId = 'localhost';
        if (filter_input(INPUT_GET, 'rpId')) {
            $rpId = filter_input(INPUT_GET, 'rpId', FILTER_VALIDATE_DOMAIN);
            if ($rpId === false) throw new Exception('invalid relying party ID');
        }

        $typeUsb = !!filter_input(INPUT_GET, 'type_usb');
        $typeNfc = !!filter_input(INPUT_GET, 'type_nfc');
        $typeBle = !!filter_input(INPUT_GET, 'type_ble');
        $typeInt = !!filter_input(INPUT_GET, 'type_int');
        $typeHyb = !!filter_input(INPUT_GET, 'type_hybrid');

        $crossPlatformAttachment = null;
        if (($typeUsb || $typeNfc || $typeBle || $typeHyb) && !$typeInt) {
            $crossPlatformAttachment = true;
        } else if (!$typeUsb && !$typeNfc && !$typeBle && !$typeHyb && $typeInt) {
            $crossPlatformAttachment = false;
        }

        $WebAuthn = new lbuchs\WebAuthn\WebAuthn('WebAuthn Library', $rpId, $formats);

        // root certs
        $certMapping = [
            'solo' => ['rootCertificates/solo.pem', 'rootCertificates/solokey_f1.pem', 'rootCertificates/solokey_r1.pem'],
            'apple' => ['rootCertificates/apple.pem'],
            'yubico' => ['rootCertificates/yubico.pem'],
            'hypersecu' => ['rootCertificates/hypersecu.pem'],
            'google' => ['rootCertificates/globalSign.pem', 'rootCertificates/googleHardware.pem'],
            'microsoft' => ['rootCertificates/microsoftTpmCollection.pem'],
            'mds' => ['rootCertificates/mds']
        ];

        foreach ($certMapping as $key => $paths) {
            if (filter_input(INPUT_GET, $key)) {
                foreach ($paths as $p) $WebAuthn->addRootCertificates($p);
            }
        }
    }

    // Router
    switch ($fn) {
        case 'checkLogin':
            $response = ['success' => isset($_SESSION['userName'])];
            if ($response['success']) {
                $response['userName'] = $_SESSION['userName'];
                $response['userDisplayName'] = $_SESSION['userDisplayName'] ?? '';
                $response['userId'] = $_SESSION['userId'] ?? '';
            }
            sendResponse($response);
            break;

        case 'getCreateArgs':
            $createArgs = $WebAuthn->getCreateArgs($userName, $userName, $userDisplayName, 60*4, $requireResidentKey, $userVerification, $crossPlatformAttachment);
            $_SESSION['challenge'] = $WebAuthn->getChallenge();
            sendResponse($createArgs);
            break;

        case 'getGetArgs':
            $ids = [];
            $registrations = loadRegistrations($registrationsFile);
            if (!$requireResidentKey && $userName) {
                foreach ($registrations as $reg) {
                    if ($reg->userName === $userName) $ids[] = $reg->credentialId;
                }
                if (count($ids) === 0) throw new Exception('no registrations found for userName ' . $userName);
            }
            if ($requireResidentKey && count($registrations) === 0) throw new Exception('no registrations available');
            
            $getArgs = $WebAuthn->getGetArgs($ids, 60*4, $typeUsb, $typeNfc, $typeBle, $typeHyb, $typeInt, $userVerification);
            $_SESSION['challenge'] = $WebAuthn->getChallenge();
            sendResponse($getArgs);
            break;

        case 'processCreate':
            $clientDataJSON = !empty($post->clientDataJSON) ? base64_decode($post->clientDataJSON) : null;
            $attestationObject = !empty($post->attestationObject) ? base64_decode($post->attestationObject) : null;
            $challenge = $_SESSION['challenge'] ?? null;
            $data = $WebAuthn->processCreate($clientDataJSON, $attestationObject, $challenge, $userVerification === 'required', true, false);
            
            $data->userId = $userId ?: bin2hex($userName);
            $data->userName = $userName;
            $data->userDisplayName = $userDisplayName;
            $data->signatureCounter ??= 0;

            $registrations = loadRegistrations($registrationsFile);
            $registrations[] = $data;
            saveRegistrations($registrationsFile, $registrations);

            sendResponse(['success' => true, 'msg' => 'registration success.']);
            break;

        case 'processGet':
            $clientDataJSON = !empty($post->clientDataJSON) ? base64_decode($post->clientDataJSON) : null;
            $authenticatorData = !empty($post->authenticatorData) ? base64_decode($post->authenticatorData) : null;
            $signature = !empty($post->signature) ? base64_decode($post->signature) : null;
            $id = !empty($post->id) ? base64_decode($post->id) : null;
            $challenge = $_SESSION['challenge'] ?? '';

            $registrations = loadRegistrations($registrationsFile);
            $reg = null;
            foreach ($registrations as $r) {
                if ($r->credentialId instanceof lbuchs\WebAuthn\Binary\ByteBuffer) {
                    if ($r->credentialId->getBinaryString() === $id) {
                        $reg = $r;
                        break;
                    }
                } else if ($r->credentialId === $id) {
                    $reg = $r;
                    break;
                }
            }

            if (!$reg) throw new Exception('Credential ID not found!');
            
            $WebAuthn->processGet($clientDataJSON, $authenticatorData, $signature, $reg->credentialPublicKey, $challenge, null, $userVerification === 'required');

            $_SESSION['userName'] = $reg->userName;
            $_SESSION['userDisplayName'] = $reg->userDisplayName;
            $_SESSION['userId'] = $reg->userId;

            sendResponse([
                'success' => true,
                'userName' => $reg->userName,
                'userDisplayName' => $reg->userDisplayName,
                'userId' => $reg->userId
            ]);
            break;

        case 'logout':
            session_destroy();
            sendResponse(['success' => true]);
            break;

        case 'clearRegistrations':
            saveRegistrations($registrationsFile, []);
            sendResponse(['success' => true, 'msg' => 'all registrations deleted']);
            break;

        case 'getStoredDataHtml':
            $registrations = loadRegistrations($registrationsFile);
            ob_start();
            ?>
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <style>
                    body { font-family: sans-serif; padding: 20px; background: #f9f9f9; }
                    .card { background: white; border: 1px solid #ddd; padding: 15px; margin-bottom: 10px; border-radius: 8px; }
                    .label { font-weight: bold; color: #555; width: 150px; display: inline-block; }
                    .val { font-family: monospace; word-break: break-all; }
                </style>
            </head>
            <body>
                <h3>Server Registrations (Serialized)</h3>
                <?php foreach ($registrations as $reg): ?>
                    <div class="card">
                        <div><span class="label">User:</span> <span class="val"><?= htmlspecialchars($reg->userName) ?></span></div>
                        <div><span class="label">Credential ID:</span> <span class="val"><?= bin2hex($reg->credentialId instanceof lbuchs\WebAuthn\Binary\ByteBuffer ? $reg->credentialId->getBinaryString() : $reg->credentialId) ?></span></div>
                    </div>
                <?php endforeach; ?>
                <?php if (empty($registrations)) echo "<p>No registrations found.</p>"; ?>
            </body>
            </html>
            <?php
            echo ob_get_clean();
            break;

        default:
            throw new Exception('Function not found');
    }

} catch (Throwable $ex) {
    error_log("WebAuthn Server Error: " . $ex->getMessage());
    header('Content-Type: application/json', true, 400);
    echo json_encode(['success' => false, 'msg' => $ex->getMessage()]);
}