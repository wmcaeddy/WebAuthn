<?php

/*
 * Copyright (C) 2022 Lukas Buchs
 * license https://github.com/lbuchs/WebAuthn/blob/master/LICENSE MIT
 *
 * Server test script for WebAuthn library. Saves new registrations in serialized file.
 */

require_once '../src/WebAuthn.php';
try {
    session_start();

    // Data storage setup
    $dataDir = '/app/data';
    // Fallback if not writable (local dev or misconfig)
    if (!is_dir($dataDir) || !is_writable($dataDir)) {
        $dataDir = sys_get_temp_dir();
        error_log("WebAuthn: /app/data not writable, using " . $dataDir);
    }
    
    // Use .ser extension for serialized data
    $registrationsFile = $dataDir . '/registrations.ser';

    // Helper to load registrations
    function loadRegistrations($file) {
        if (file_exists($file)) {
            $content = file_get_contents($file);
            if ($content !== false) {
                $data = unserialize($content);
                if (is_array($data)) {
                    return $data;
                } else {
                    error_log("WebAuthn: Failed to unserialize data from " . $file);
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
        } else {
            error_log("WebAuthn: Saved " . count($data) . " registrations to " . $file);
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

    if ($fn !== 'getStoredDataHtml' && $fn !== 'deleteRegistration') {

        // Formats
        $formats = [];
        if (filter_input(INPUT_GET, 'fmt_android-key')) {
            $formats[] = 'android-key';
        }
        if (filter_input(INPUT_GET, 'fmt_android-safetynet')) {
            $formats[] = 'android-safetynet';
        }
        if (filter_input(INPUT_GET, 'fmt_apple')) {
            $formats[] = 'apple';
        }
        if (filter_input(INPUT_GET, 'fmt_fido-u2f')) {
            $formats[] = 'fido-u2f';
        }
        if (filter_input(INPUT_GET, 'fmt_none')) {
            $formats[] = 'none';
        }
        if (filter_input(INPUT_GET, 'fmt_packed')) {
            $formats[] = 'packed';
        }
        if (filter_input(INPUT_GET, 'fmt_tpm')) {
            $formats[] = 'tpm';
        }

        $rpId = 'localhost';
        if (filter_input(INPUT_GET, 'rpId')) {
            $rpId = filter_input(INPUT_GET, 'rpId', FILTER_VALIDATE_DOMAIN);
            if ($rpId === false) {
                throw new Exception('invalid relying party ID');
            }
        }

        // types selected on front end
        $typeUsb = !!filter_input(INPUT_GET, 'type_usb');
        $typeNfc = !!filter_input(INPUT_GET, 'type_nfc');
        $typeBle = !!filter_input(INPUT_GET, 'type_ble');
        $typeInt = !!filter_input(INPUT_GET, 'type_int');
        $typeHyb = !!filter_input(INPUT_GET, 'type_hybrid');

        // cross-platform: true, if type internal is not allowed
        //                 false, if only internal is allowed
        //                 null, if internal and cross-platform is allowed
        $crossPlatformAttachment = null;
        if (($typeUsb || $typeNfc || $typeBle || $typeHyb) && !$typeInt) {
            $crossPlatformAttachment = true;

        } else if (!$typeUsb && !$typeNfc && !$typeBle && !$typeHyb && $typeInt) {
            $crossPlatformAttachment = false;
        }


        // new Instance of the server library.
        // make sure that $rpId is the domain name.
        $WebAuthn = new lbuchs\WebAuthn\WebAuthn('WebAuthn Library', $rpId, $formats);

        // add root certificates to validate new registrations
        if (filter_input(INPUT_GET, 'solo')) {
            $WebAuthn->addRootCertificates('rootCertificates/solo.pem');
            $WebAuthn->addRootCertificates('rootCertificates/solokey_f1.pem');
            $WebAuthn->addRootCertificates('rootCertificates/solokey_r1.pem');
        }
        if (filter_input(INPUT_GET, 'apple')) {
            $WebAuthn->addRootCertificates('rootCertificates/apple.pem');
        }
        if (filter_input(INPUT_GET, 'yubico')) {
            $WebAuthn->addRootCertificates('rootCertificates/yubico.pem');
        }
        if (filter_input(INPUT_GET, 'hypersecu')) {
            $WebAuthn->addRootCertificates('rootCertificates/hypersecu.pem');
        }
        if (filter_input(INPUT_GET, 'google')) {
            $WebAuthn->addRootCertificates('rootCertificates/globalSign.pem');
            $WebAuthn->addRootCertificates('rootCertificates/googleHardware.pem');
        }
        if (filter_input(INPUT_GET, 'microsoft')) {
            $WebAuthn->addRootCertificates('rootCertificates/microsoftTpmCollection.pem');
        }
        if (filter_input(INPUT_GET, 'mds')) {
            $WebAuthn->addRootCertificates('rootCertificates/mds');
        }

    }

    // ------------------------------------
    // request for create arguments
    // ------------------------------------

    if ($fn === 'getCreateArgs') {
        // Use userName directly as the binary source for userId to ensure human-readable identities on tokens.
        $createArgs = $WebAuthn->getCreateArgs($userName, $userName, $userDisplayName, 60*4, $requireResidentKey, $userVerification, $crossPlatformAttachment);

        header('Content-Type: application/json');
        print(json_encode($createArgs));

        // save challange to session. you have to deliver it to processGet later.
        $_SESSION['challenge'] = $WebAuthn->getChallenge();



    // ------------------------------------
    // request for get arguments
    // ------------------------------------

    } else if ($fn === 'getGetArgs') {
        $ids = [];
        $registrations = loadRegistrations($registrationsFile);
        error_log("WebAuthn: getGetArgs - Found " . count($registrations) . " total registrations.");

        if ($requireResidentKey) {
            if (count($registrations) === 0) {
                throw new Exception('we do not have any registrations to check the registration');
            }

        } else {
            if ($userName) {
                foreach ($registrations as $reg) {
                    if ($reg->userName === $userName) {
                        $ids[] = $reg->credentialId;
                    }
                }

                if (count($ids) === 0) {
                    error_log("WebAuthn: No registrations matched userName: " . $userName);
                    throw new Exception('no registrations found for userName ' . $userName);
                }
            }
        }

        $getArgs = $WebAuthn->getGetArgs($ids, 60*4, $typeUsb, $typeNfc, $typeBle, $typeHyb, $typeInt, $userVerification);

        header('Content-Type: application/json');
        print(json_encode($getArgs));

        // save challange to session. you have to deliver it to processGet later.
        $_SESSION['challenge'] = $WebAuthn->getChallenge();



    // ------------------------------------
    // process create
    // ------------------------------------

    } else if ($fn === 'processCreate') {
        $clientDataJSON = !empty($post->clientDataJSON) ? base64_decode($post->clientDataJSON) : null;
        $attestationObject = !empty($post->attestationObject) ? base64_decode($post->attestationObject) : null;
        $challenge = $_SESSION['challenge'] ?? null;

        $data = $WebAuthn->processCreate($clientDataJSON, $attestationObject, $challenge, $userVerification === 'required', true, false);

        // add user infos
        $data->userId = $userId ?: bin2hex($userName);
        $data->userName = $userName;
        $data->userDisplayName = $userDisplayName;
        //set Null to 0
        $data->signatureCounter ??= 0;

        $registrations = loadRegistrations($registrationsFile);
        $registrations[] = $data;
        saveRegistrations($registrationsFile, $registrations);

        $msg = 'registration success.';
        if ($data->rootValid === false) {
            $msg = 'registration ok, but certificate does not match any of the selected root ca.';
        }

        $return = new stdClass();
        $return->success = true;
        $return->msg = $msg;

        header('Content-Type: application/json');
        print(json_encode($return));



    // ------------------------------------
    // proccess get
    // ------------------------------------

    } else if ($fn === 'processGet') {
        $clientDataJSON = !empty($post->clientDataJSON) ? base64_decode($post->clientDataJSON) : null;
        $authenticatorData = !empty($post->authenticatorData) ? base64_decode($post->authenticatorData) : null;
        $signature = !empty($post->signature) ? base64_decode($post->signature) : null;
        $userHandle = !empty($post->userHandle) ? base64_decode($post->userHandle) : null;
        $id = !empty($post->id) ? base64_decode($post->id) : null;
        $challenge = $_SESSION['challenge'] ?? '';
        $credentialPublicKey = null;

        $registrations = loadRegistrations($registrationsFile);
        $reg = null;
        foreach ($registrations as $r) {
            if ($r->credentialId === $id) {
                $credentialPublicKey = $r->credentialPublicKey;
                $reg = $r;
                break;
            }
        }

        if ($credentialPublicKey === null) {
            throw new Exception('Public Key for credential ID not found!');
        }

        // if we have resident key, we have to verify that the userHandle is the provided userId at registration
        if ($requireResidentKey && $userHandle !== $reg->userName) {
            throw new \Exception('userId doesnt match (is ' . $userHandle . ' but expect ' . $reg->userName . ')');
        }

        // process the get request. throws WebAuthnException if it fails
        $WebAuthn->processGet($clientDataJSON, $authenticatorData, $signature, $credentialPublicKey, $challenge, null, $userVerification === 'required');

        $return = new stdClass();
        $return->success = true;
        
        // Add user info to response
        if ($reg) {
            $return->userName = $reg->userName;
            $return->userDisplayName = $reg->userDisplayName;
            $return->userId = $reg->userId;
        }

        header('Content-Type: application/json');
        print(json_encode($return));

    // ------------------------------------
    // proccess clear registrations
    // ------------------------------------

    } else if ($fn === 'clearRegistrations') {
        saveRegistrations($registrationsFile, []);
        $_SESSION['challenge'] = null;

        $return = new stdClass();
        $return->success = true;
        $return->msg = 'all registrations deleted';

        header('Content-Type: application/json');
        print(json_encode($return));

    // ------------------------------------
    // logout
    // ------------------------------------
    } else if ($fn === 'logout') {
        $_SESSION['challenge'] = null;
        // Optionally clear other session data if needed
        
        $return = new stdClass();
        $return->success = true;

        header('Content-Type: application/json');
        print(json_encode($return));

    // ------------------------------------
    // delete single registration
    // ------------------------------------
    } else if ($fn === 'deleteRegistration') {
        $credentialIdHex = filter_input(INPUT_GET, 'credentialId', FILTER_SANITIZE_SPECIAL_CHARS);
        
        if ($credentialIdHex) {
            $registrations = loadRegistrations($registrationsFile);
            $newRegistrations = [];
            $deleted = false;
            
            foreach ($registrations as $reg) {
                if (bin2hex($reg->credentialId) !== $credentialIdHex) {
                    $newRegistrations[] = $reg;
                } else {
                    $deleted = true;
                }
            }
            
            if ($deleted) {
                saveRegistrations($registrationsFile, $newRegistrations);
                $msg = 'Registration deleted.';
            } else {
                $msg = 'Registration not found.';
            }
        } else {
            $msg = 'No credential ID provided.';
        }

        // Redirect back to the HTML view
        header('Location: server.php?fn=getStoredDataHtml');
        exit;

    // ------------------------------------
    // display stored data as HTML
    // ------------------------------------

    } else if ($fn === 'getStoredDataHtml') {
        $html = '<!DOCTYPE html>' . "\n";
        $html .= '<html lang="en"><head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="assets/css/style.css">
            <style>
                body { background-color: transparent; padding: 0; margin: 0; }
                .search-container { position: sticky; top: 0; background: var(--color-bg); padding: 10px 0; z-index: 5; margin-bottom: 16px; border-bottom: 1px solid var(--color-border); }
                .search-input { width: 100%; padding: 8px 12px; border: 1px solid var(--color-border); border-radius: var(--radius-md); font-size: 0.875rem; outline: none; }
                .search-input:focus { border-color: var(--color-primary); }
                .data-card { background: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-md); padding: 16px; margin-bottom: 16px; }
                .data-row { display: flex; border-bottom: 1px solid #f0f0f0; padding: 4px 0; font-size: 0.8125rem; }
                .data-row:last-child { border-bottom: none; }
                .data-label { width: 140px; font-weight: 600; color: var(--color-text-primary); flex-shrink: 0; }
                .data-value { font-family: monospace; word-break: break-all; color: var(--color-text-secondary); }
                .card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
                .user-badge { background: #eef6ff; color: var(--color-primary); padding: 2px 8px; border-radius: 12px; font-size: 0.75rem; font-weight: 600; }
            </style>
            <script>
                function confirmDelete(credentialId) {
                    if (confirm("Are you sure you want to delete this registration?")) {
                        window.location.href = "server.php?fn=deleteRegistration&credentialId=" + credentialId;
                    }
                }
                
                function filterData() {
                    const term = document.getElementById("searchBar").value.toLowerCase();
                    const cards = document.querySelectorAll(".data-card");
                    cards.forEach(card => {
                        const text = card.innerText.toLowerCase();
                        card.style.display = text.includes(term) ? "block" : "none";
                    });
                }
            </script>
        </head>';
        $html .= '<body>';
        $html .= '<div class="search-container"><input type="text" id="searchBar" class="search-input" placeholder="Search by name or ID..." oninput="filterData()"></div>';
        $registrations = loadRegistrations($registrationsFile);
        if (count($registrations) > 0) {
            foreach ($registrations as $reg) {
                $credIdHex = bin2hex($reg->credentialId);
                $html .= '<div class="data-card">';
                $html .= '<div class="card-header">';
                $html .= '<span class="user-badge">' . htmlspecialchars($reg->userName) . '</span>';
                $html .= '<button class="btn-danger" onclick="confirmDelete(\\' . $credIdHex . '\")">Delete</button>';
                $html .= '</div>';
                
                foreach ($reg as $key => $value) {
                    if (is_bool($value)) {
                        $value = $value ? 'yes' : 'no';
                    } else if (is_null($value)) {
                        $value = 'null';
                    } else if (is_object($value)) {
                        $value = chunk_split(strval($value), 64);
                    } else if (is_string($value) && strlen($value) > 0 && htmlspecialchars($value, ENT_QUOTES) === '') {
                        $value = chunk_split(bin2hex($value), 64);
                    }
                    
                    $html .= '<div class="data-row">';
                    $html .= '<div class="data-label">' . htmlspecialchars($key) . '</div>';
                    $html .= '<div class="data-value">' . nl2br(htmlspecialchars($value)) . '</div>';
                    $html .= '</div>';
                }
                $html .= '</div>';
            }
        } else {
            $html .= '<div class="text-center" style="padding: 40px; color: var(--color-text-secondary);">';
            $html .= '<p>No registrations stored on server.</p>';
            $html .= '</div>';
        }
        $html .= '</body></html>';

        header('Content-Type: text/html');
        print $html;

    // ------------------------------------
    // get root certs from FIDO Alliance Metadata Service
    // ------------------------------------

    } else if ($fn === 'queryFidoMetaDataService') {

        $mdsFolder = 'rootCertificates/mds';
        $success = false;
        $msg = null;

        // fetch only 1x / 24h
        $lastFetch = \is_file($mdsFolder .  '/lastMdsFetch.txt') ? \strtotime(\file_get_contents($mdsFolder .  '/lastMdsFetch.txt')) : 0;
        if ($lastFetch + (3600*48) < \time()) {
            $cnt = $WebAuthn->queryFidoMetaDataService($mdsFolder);
            $success = true;
            \file_put_contents($mdsFolder .  '/lastMdsFetch.txt', date('r'));
            $msg = 'successfully queried FIDO Alliance Metadata Service - ' . $cnt . ' certificates downloaded.';

        } else {
            $msg = 'Fail: last fetch was at ' . date('r', $lastFetch) . ' - fetch only 1x every 48h';
        }

        $return = new stdClass();
        $return->success = $success;
        $return->msg = $msg;

        header('Content-Type: application/json');
        print(json_encode($return));
    }

} catch (Throwable $ex) {
    // Log exception to stderr for Railway logs
    error_log("WebAuthn Exception: " . $ex->getMessage());
    
    $return = new stdClass();
    $return->success = false;
    $return->msg = $ex->getMessage();

    header('Content-Type: application/json');
    print(json_encode($return));
}