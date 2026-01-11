<?php
// index.php - Fairline Layout with WebAuthn Integration
?>
<!DOCTYPE html>
<html lang="zh-tw">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>中飛科技股份有限公司 ::: WebAuthn 驗證服務</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Modern Styles -->
    <link rel="stylesheet" href="assets/css/modern.css">

    <!-- Modern Scripts -->
    <script src="assets/js/animations.js" defer></script>

    <script>
        /**
         * WebAuthn Logic
         */
        async function createRegistration() {
            try {
                if (!window.fetch || !navigator.credentials || !navigator.credentials.create) {
                    throw new Error('Browser not supported.');
                }

                updateUserId();

                const userName = document.getElementById('userName').value;
                const userDisplayName = document.getElementById('userDisplayName').value;

                if (!userName) throw new Error('請輸入使用者名稱');
                if (!userDisplayName) throw new Error('請輸入顯示名稱');

                showLoading('準備註冊中...');
                hideStatus();

                let rep = await window.fetch('_test/server.php?fn=getCreateArgs' + getGetParams(), {method:'GET', cache:'no-cache'});
                const createArgs = await rep.json();

                if (createArgs.success === false) {
                    throw new Error(createArgs.msg || 'unknown error occured');
                }

                recursiveBase64StrToArrayBuffer(createArgs);

                showLoading('請使用您的安全金鑰或生物辨識...');
                const cred = await navigator.credentials.create(createArgs);

                const authenticatorAttestationResponse = {
                    transports: cred.response.getTransports  ? cred.response.getTransports() : null,
                    clientDataJSON: cred.response.clientDataJSON  ? arrayBufferToBase64(cred.response.clientDataJSON) : null,
                    attestationObject: cred.response.attestationObject ? arrayBufferToBase64(cred.response.attestationObject) : null
                };

                showLoading('驗證中...');
                rep = await window.fetch('_test/server.php?fn=processCreate' + getGetParams(), {
                    method  : 'POST',
                    body    : JSON.stringify(authenticatorAttestationResponse),
                    cache   : 'no-cache'
                });
                const response = await rep.json();

                hideLoading();
                if (response.success) {
                    reloadServerPreview();
                    setStatus(response.msg || '註冊成功！', 'success');
                } else {
                    throw new Error(response.msg);
                }

            } catch (err) {
                hideLoading();
                reloadServerPreview();
                setStatus(err.message || 'unknown error occured', 'error');
            }
        }

        async function checkRegistration() {
            try {
                if (!window.fetch || !navigator.credentials || !navigator.credentials.get) {
                    throw new Error('Browser not supported.');
                }

                updateUserId();

                const userName = document.getElementById('userName').value;
                if (!userName) throw new Error('請輸入使用者名稱');

                showLoading('準備登入...');
                hideStatus();

                let rep = await window.fetch('_test/server.php?fn=getGetArgs' + getGetParams(), {method:'GET',cache:'no-cache'});
                const getArgs = await rep.json();

                if (getArgs.success === false) {
                    throw new Error(getArgs.msg);
                }

                recursiveBase64StrToArrayBuffer(getArgs);

                showLoading('請驗證您的身份...');
                const cred = await navigator.credentials.get(getArgs);

                const authenticatorAttestationResponse = {
                    id: cred.rawId ? arrayBufferToBase64(cred.rawId) : null,
                    clientDataJSON: cred.response.clientDataJSON  ? arrayBufferToBase64(cred.response.clientDataJSON) : null,
                    authenticatorData: cred.response.authenticatorData ? arrayBufferToBase64(cred.response.authenticatorData) : null,
                    signature: cred.response.signature ? arrayBufferToBase64(cred.response.signature) : null,
                    userHandle: cred.response.userHandle ? arrayBufferToBase64(cred.response.userHandle) : null
                };

                showLoading('驗證登入中...');
                rep = await window.fetch('_test/server.php?fn=processGet' + getGetParams(), {
                    method:'POST',
                    body: JSON.stringify(authenticatorAttestationResponse),
                    cache:'no-cache'
                });
                const response = await rep.json();

                hideLoading();
                if (response.success) {
                    reloadServerPreview();
                    
                    const loginForm = document.getElementById('login-flow-container');
                    const userSection = document.getElementById('user-authenticated-section');
                    
                    const userName = response.userName || document.getElementById('userName').value;
                    const displayName = response.userDisplayName || document.getElementById('userDisplayName').value;
                    
                    document.getElementById('auth-user-name').textContent = displayName || userName;
                    document.getElementById('auth-user-id').textContent = '@' + userName;
                    document.getElementById('auth-login-time').textContent = new Date().toLocaleString();
                    document.getElementById('auth-avatar').textContent = (displayName || userName).charAt(0).toUpperCase();

                    loginForm.style.display = 'none';
                    userSection.classList.remove('hidden');
                    
                    setStatus('登入成功！', 'success');
                } else {
                    throw new Error(response.msg);
                }

            } catch (err) {
                hideLoading();
                reloadServerPreview();
                setStatus(err.message || 'unknown error occured', 'error');
            }
        }

        async function logout() {
            try {
                showLoading('登出中...');
                await window.fetch('_test/server.php?fn=logout', {method:'GET', cache:'no-cache'});
                
                const loginForm = document.getElementById('login-flow-container');
                const userSection = document.getElementById('user-authenticated-section');

                userSection.classList.add('hidden');
                loginForm.style.display = 'block';
                
                hideStatus();
                hideLoading();

            } catch (err) {
                hideLoading();
                setStatus('Logout failed: ' + err.message, 'error');
            }
        }

        function clearRegistration() {
            if (!confirm('Are you sure you want to clear all registrations?')) return;
            
            showLoading('清除資料中...');
            window.fetch('_test/server.php?fn=clearRegistrations' + getGetParams(), {method:'GET',cache:'no-cache'}).then(function(response) {
                return response.json();
            }).then(function(json) {
               hideLoading();
               if (json.success) {
                   reloadServerPreview();
                   setStatus(json.msg, 'success');
               } else {
                   throw new Error(json.msg);
               }
            }).catch(function(err) {
                hideLoading();
                reloadServerPreview();
                setStatus(err.message || 'unknown error occured', 'error');
            });
        }

        function recursiveBase64StrToArrayBuffer(obj) {
            let prefix = '=?BINARY?B?';
            let suffix = '?=';
            if (typeof obj === 'object') {
                for (let key in obj) {
                    if (typeof obj[key] === 'string') {
                        let str = obj[key];
                        if (str.substring(0, prefix.length) === prefix && str.substring(str.length - suffix.length) === suffix) {
                            str = str.substring(prefix.length, str.length - suffix.length);
                            let binary_string = window.atob(str);
                            let len = binary_string.length;
                            let bytes = new Uint8Array(len);
                            for (let i = 0; i < len; i++) {
                                bytes[i] = binary_string.charCodeAt(i);
                            }
                            obj[key] = bytes.buffer;
                        }
                    } else {
                        recursiveBase64StrToArrayBuffer(obj[key]);
                    }
                }
            }
        }

        function arrayBufferToBase64(buffer) {
            let binary = '';
            let bytes = new Uint8Array(buffer);
            let len = bytes.byteLength;
            for (let i = 0; i < len; i++) {
                binary += String.fromCharCode( bytes[ i ] );
            }
            return window.btoa(binary);
        }

        function updateUserId() {
            const username = document.getElementById('userName').value;
            const useridField = document.getElementById('userId');
            if (!username) { useridField.value = ''; return; }
            let hex = '';
            for(let i=0;i<username.length;i++) { hex += ''+username.charCodeAt(i).toString(16); }
            useridField.value = hex;
        }

        function getGetParams() {
            let url = '';
            url += '&rpId=' + encodeURIComponent(document.getElementById('rpId').value || location.hostname);
            url += '&userId=' + encodeURIComponent(document.getElementById('userId').value);
            url += '&userName=' + encodeURIComponent(document.getElementById('userName').value);
            url += '&userDisplayName=' + encodeURIComponent(document.getElementById('userDisplayName').value);
            url += '&requireResidentKey=' + (document.getElementById('requireResidentKey').checked ? '1' : '0');
            if (document.getElementById('userVerification_required').checked) url += '&userVerification=required';
            else if (document.getElementById('userVerification_preferred').checked) url += '&userVerification=preferred';
            else if (document.getElementById('userVerification_discouraged').checked) url += '&userVerification=discouraged';
            ['usb', 'nfc', 'ble', 'hybrid', 'int'].forEach(t => { if (document.getElementById('type_' + t).checked) url += '&type_' + t + '=1'; });
            ['none', 'packed', 'android-key', 'apple', 'tpm'].forEach(f => { if (document.getElementById('fmt_' + f).checked) url += '&fmt_' + f + '=1'; });
            return url;
        }

        function showLoading(message) {
            const overlay = document.getElementById('loading-overlay');
            if(overlay) {
                document.getElementById('loading-text').textContent = message;
                overlay.classList.remove('hidden');
            }
        }

        function hideLoading() {
            const overlay = document.getElementById('loading-overlay');
            if(overlay) overlay.classList.add('hidden');
        }

        function setStatus(message, type) {
            const container = document.getElementById('status-container');
            const msg = document.getElementById('status-message');
            msg.textContent = message;
            container.className = 'status-message status-' + type;
            container.style.display = 'block';
            container.classList.remove('hidden');
            if(type === 'success') {
                container.style.backgroundColor = '#e6f4ea';
                container.style.color = '#1e7e34';
            } else {
                container.style.backgroundColor = '#fce8e6';
                container.style.color = '#d93025';
            }
        }

        function hideStatus() {
            document.getElementById('status-container').classList.add('hidden');
            document.getElementById('status-container').style.display = 'none';
        }

        function reloadServerPreview() {
            let iframe = document.getElementById('serverPreview');
            if (iframe) iframe.src = iframe.src;
        }

        function toggleSettings() {
            document.getElementById('advanced-settings').classList.toggle('hidden');
        }

        function togglePreview() {
            document.getElementById('preview-container').classList.toggle('hidden');
        }

        window.onload = function() {
            if (!window.isSecureContext && location.protocol !== 'https:') {                
                location.href = location.href.replace('http://', 'https://');
            }
            if(document.getElementById('rpId')) {
                document.getElementById('rpId').value = location.hostname;
            }
        }
    </script>
</head>
<body>
    <?php include 'components/header.php'; ?>

    <main class="g-wrap">
        <?php include 'components/hero.php'; ?>
        <?php include 'components/about.php'; ?>
        <?php include 'components/news.php'; ?>
        <?php include 'components/brands.php'; ?>
    </main>

    <?php include 'components/footer.php'; ?>
</body>
</html>