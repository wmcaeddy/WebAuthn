<!DOCTYPE html>
<html data-scrapbook-source="https://e.khanbank.com/auth/login" data-scrapbook-create="20260513141120165">
<head>
    <title>ХААН Банк | Иргэд | Интернэт банк</title>
    <meta data-react-helmet="true" charset="UTF-8">
    <link data-react-helmet="true" rel="shortcut icon" href="favicon.png" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="3992.css">
    <style>
        .hidden { display: none !important; }
        .loading-overlay {
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, 0.85);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            border-radius: 8px;
            backdrop-filter: blur(2px);
        }
        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #00a859;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        
        .status-message {
            margin-top: 16px;
            padding: 12px;
            border-radius: 4px;
            text-align: center;
            font-size: 14px;
            word-break: break-all;
        }
        .status-success { background: #f6ffed; border: 1px solid #b7eb8f; color: #52c41a; }
        .status-error { background: #fff1f0; border: 1px solid #ffa39e; color: #f5222d; }
        
        .tabs-nav {
            display: flex;
            border-bottom: 1px solid #f0f0f0;
            margin-bottom: 24px;
        }
        .tab-link {
            flex: 1;
            text-align: center;
            padding: 12px;
            cursor: pointer;
            transition: all 0.3s;
            color: rgba(0, 0, 0, 0.45);
            border-bottom: 2px solid transparent;
            font-weight: 500;
        }
        .tab-link.active {
            color: #00a859;
            border-bottom-color: #00a859;
        }
        
        .settings-section {
            max-height: 400px;
            overflow-y: auto;
            padding-right: 8px;
            text-align: left;
        }
        .settings-section h4 {
            margin: 16px 0 8px;
            font-size: 14px;
            color: rgba(0,0,0,0.85);
            border-bottom: 1px solid #f0f0f0;
            padding-bottom: 4px;
        }
        .settings-row {
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            font-size: 13px;
        }
        .settings-row input[type="checkbox"], .settings-row input[type="radio"] {
            margin-right: 8px;
        }
        
        .avatar-circle {
            width: 72px;
            height: 72px;
            background-color: #00a859;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin: 0 auto 16px;
        }

        .preview-container {
            margin-top: 24px;
            width: 100%;
            background: #fff;
            border: 1px solid #d9d9d9;
            border-radius: 8px;
            overflow: hidden;
        }
        .preview-header {
            background: #fafafa;
            padding: 12px 16px;
            border-bottom: 1px solid #d9d9d9;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        #serverPreview {
            width: 100%;
            height: 400px;
            border: none;
        }
        
        /* Custom scrollbar */
        .settings-section::-webkit-scrollbar { width: 6px; }
        .settings-section::-webkit-scrollbar-track { background: #f1f1f1; }
        .settings-section::-webkit-scrollbar-thumb { background: #ccc; border-radius: 3px; }
        .settings-section::-webkit-scrollbar-thumb:hover { background: #999; }
    </style>
    <script>
        async function createRegistration() {
            try {
                if (!window.fetch || !navigator.credentials || !navigator.credentials.create) throw new Error('Browser not supported.');
                const userName = document.getElementById('userName').value;
                if (!userName) {
                    alert('Please enter a username first.');
                    document.getElementById('userName').focus();
                    return;
                }
                
                showLoading('Preparing registration...');
                hideStatus();
                
                let rep = await window.fetch('_test/server.php?fn=getCreateArgs' + getGetParams(), {method:'GET', cache:'no-cache'});
                const createArgs = await rep.json();
                if (createArgs.success === false) throw new Error(createArgs.msg || 'unknown error occured');
                
                recursiveBase64StrToArrayBuffer(createArgs);
                showLoading('Waiting for authenticator...');
                const cred = await navigator.credentials.create(createArgs);
                
                const response = await window.fetch('_test/server.php?fn=processCreate' + getGetParams(), {
                    method: 'POST',
                    body: JSON.stringify({
                        transports: cred.response.getTransports ? cred.response.getTransports() : null,
                        clientDataJSON: arrayBufferToBase64(cred.response.clientDataJSON),
                        attestationObject: arrayBufferToBase64(cred.response.attestationObject)
                    })
                });
                const res = await response.json();
                hideLoading();
                if (res.success) { 
                    setStatus(res.msg || 'Registration successful!', 'success');
                    reloadServerPreview();
                } else throw new Error(res.msg);
            } catch (err) { hideLoading(); setStatus(err.message, 'error'); }
        }

        async function checkRegistration() {
            try {
                showLoading('Preparing authentication...');
                hideStatus();
                
                let rep = await window.fetch('_test/server.php?fn=getGetArgs' + getGetParams(), {method:'GET',cache:'no-cache'});
                const getArgs = await rep.json();
                if (getArgs.success === false) throw new Error(getArgs.msg);
                
                recursiveBase64StrToArrayBuffer(getArgs);
                showLoading('Waiting for authenticator...');
                const cred = await navigator.credentials.get(getArgs);
                
                const response = await window.fetch('_test/server.php?fn=processGet' + getGetParams(), {
                    method: 'POST',
                    body: JSON.stringify({
                        id: arrayBufferToBase64(cred.rawId),
                        clientDataJSON: arrayBufferToBase64(cred.response.clientDataJSON),
                        authenticatorData: arrayBufferToBase64(cred.response.authenticatorData),
                        signature: arrayBufferToBase64(cred.response.signature),
                        userHandle: cred.response.userHandle ? arrayBufferToBase64(cred.response.userHandle) : null
                    })
                });
                const res = await response.json();
                hideLoading();
                if (res.success) {
                    const loginForm = document.getElementById('login-flow-container');
                    const userSection = document.getElementById('user-authenticated-section');
                    document.getElementById('auth-user-name').textContent = res.userDisplayName || res.userName;
                    document.getElementById('auth-user-id').textContent = '@' + res.userName;
                    document.getElementById('auth-avatar').textContent = (res.userDisplayName || res.userName).charAt(0).toUpperCase();
                    
                    loginForm.classList.add('hidden');
                    userSection.classList.remove('hidden');
                    setStatus('Login successful', 'success');
                    reloadServerPreview();
                } else throw new Error(res.msg);
            } catch (err) { hideLoading(); setStatus(err.message, 'error'); }
        }

        function getGetParams() {
            let url = '';
            url += '&rpId=' + encodeURIComponent(document.getElementById('rpId').value);
            url += '&userName=' + encodeURIComponent(document.getElementById('userName').value);
            url += '&userDisplayName=' + encodeURIComponent(document.getElementById('userDisplayName').value);
            url += '&requireResidentKey=' + (document.getElementById('requireResidentKey').checked ? '1' : '0');

            // Verification
            if (document.getElementById('userVerification_required').checked) url += '&userVerification=required';
            else if (document.getElementById('userVerification_preferred').checked) url += '&userVerification=preferred';
            else if (document.getElementById('userVerification_discouraged').checked) url += '&userVerification=discouraged';

            // Types
            ['usb', 'nfc', 'ble', 'hybrid', 'int'].forEach(t => {
                url += '&type_' + t + '=' + (document.getElementById('type_' + t).checked ? '1' : '0');
            });

            // Formats
            ['none', 'packed', 'android-key', 'android-safetynet', 'apple', 'tpm', 'fido-u2f'].forEach(f => {
                url += '&fmt_' + f + '=' + (document.getElementById('fmt_' + f).checked ? '1' : '0');
            });

            // Root Certs
            ['apple', 'yubico', 'solo', 'hypersecu', 'google', 'microsoft', 'mds'].forEach(c => {
                url += '&' + c + '=' + (document.getElementById('cert_' + c).checked ? '1' : '0');
            });

            return url;
        }

        function recursiveBase64StrToArrayBuffer(obj) {
            let prefix = '=?BINARY?B?'; let suffix = '?=';
            if (typeof obj === 'object') {
                for (let key in obj) {
                    if (typeof obj[key] === 'string') {
                        let str = obj[key];
                        if (str.substring(0, prefix.length) === prefix && str.substring(str.length - suffix.length) === suffix) {
                            str = str.substring(prefix.length, str.length - suffix.length);
                            let binary_string = window.atob(str);
                            let bytes = new Uint8Array(binary_string.length);
                            for (let i = 0; i < binary_string.length; i++) bytes[i] = binary_string.charCodeAt(i);
                            obj[key] = bytes.buffer;
                        }
                    } else recursiveBase64StrToArrayBuffer(obj[key]);
                }
            }
        }
        function arrayBufferToBase64(buffer) {
            let binary = ''; let bytes = new Uint8Array(buffer);
            for (let i = 0; i < bytes.byteLength; i++) binary += String.fromCharCode(bytes[i]);
            return window.btoa(binary);
        }
        function showLoading(msg) { document.getElementById('loading-overlay').classList.remove('hidden'); document.getElementById('loading-text').textContent = msg; }
        function hideLoading() { document.getElementById('loading-overlay').classList.add('hidden'); }
        function setStatus(msg, type) { const c = document.getElementById('status-container'); const m = document.getElementById('status-message'); m.className = 'status-message status-' + type; m.textContent = msg; c.classList.remove('hidden'); }
        function hideStatus() { document.getElementById('status-container').classList.add('hidden'); }
        
        function switchTab(id) { 
            document.querySelectorAll('.tab-content').forEach(e => e.classList.add('hidden')); 
            document.querySelectorAll('.tab-link').forEach(e => e.classList.remove('active')); 
            document.getElementById('content-' + id).classList.remove('hidden');
            event.currentTarget.classList.add('active');
        }

        function reloadServerPreview() { const f = document.getElementById('serverPreview'); if (f) f.src = f.src; }
        
        async function clearRegistrations() {
            if (!confirm('Are you sure you want to clear all registrations?')) return;
            showLoading('Clearing data...');
            const res = await (await window.fetch('_test/server.php?fn=clearRegistrations', {method:'GET',cache:'no-cache'})).json();
            hideLoading();
            if (res.success) { reloadServerPreview(); setStatus(res.msg, 'success'); }
            else setStatus(res.msg, 'error');
        }

        function queryFidoMetaDataService() {
            showLoading('Updating root certificates...');
            window.fetch('_test/server.php?fn=queryFidoMetaDataService', {method:'GET',cache:'no-cache'}).then(res => res.json()).then(json => {
                hideLoading();
                if (json.success) setStatus(json.msg, 'success');
                else throw new Error(json.msg);
            }).catch(err => { hideLoading(); setStatus(err.message, 'error'); });
        }

        async function logout() { await window.fetch('_test/server.php?fn=logout'); location.reload(); }

        window.onload = function() {
            if (!window.isSecureContext && location.protocol !== 'https:') {                
                location.href = location.href.replace('http://', 'https://');
            }
            if (!document.getElementById('rpId').value) {
                document.getElementById('rpId').value = location.hostname;
            }
        }
    </script>
</head>
<body style="display: block;">
    <div id="root">
        <section class="ant-layout">
            <main class="ant-layout-content">
                <div class="ant-row ant-row-center ant-row-middle">
                    <div class="ant-col ant-col-xs-24 ant-col-sm-16 ant-col-md-12 ant-col-lg-10 ant-col-xl-8 ant-col-xxl-6">
                        <header class="ant-layout-header header-login">
                            <button type="button" class="ant-btn ant-btn-link button-language">
                                <img src="lang-mn.svg">
                            </button>
                        </header>
                        <div class="ant-spin-nested-loading">
                            <div class="ant-spin-container">
                                <div class="ant-card ant-card-bordered ant-card-hoverable login-card">
                                    <div class="ant-card-body" style="position: relative; text-align: center;">
                                        <div id="loading-overlay" class="loading-overlay hidden">
                                            <div class="spinner"></div>
                                            <p id="loading-text" style="margin-top: 12px; font-weight: 500;">Please wait...</p>
                                        </div>

                                        <div style="text-align:center; margin-bottom: 24px;">
                                            <img src="logo-en.svg" class="logo" alt="Khan Bank">
                                        </div>

                                        <div id="login-flow-container">
                                            <nav class="tabs-nav">
                                                <div class="tab-link active" onclick="switchTab('login')">Authentication</div>
                                                <div class="tab-link" onclick="switchTab('settings')">Settings</div>
                                            </nav>

                                            <div id="content-login" class="tab-content">
                                                <div class="ant-form-item">
                                                    <input class="ant-input" placeholder="Username" id="userName" value="demo">
                                                </div>
                                                <div class="ant-form-item" id="displayNameGroup">
                                                    <input class="ant-input" placeholder="Display Name" id="userDisplayName" value="Demo User">
                                                </div>
                                                
                                                <div style="margin-top: 24px;">
                                                    <button type="button" class="ant-btn ant-btn-primary ant-btn-lg ant-btn-block login-button" onclick="checkRegistration()">
                                                        <span>Login</span>
                                                    </button>
                                                </div>
                                                
                                                <div style="margin-top: 12px;">
                                                    <button type="button" class="ant-btn ant-btn-default ant-btn-lg ant-btn-block" style="border-color: #00a859; color: #00a859;" onclick="createRegistration()">
                                                        <span>Sign Up</span>
                                                    </button>
                                                </div>
                                            </div>

                                            <div id="content-settings" class="tab-content hidden settings-section">
                                                <h4>Relying Party</h4>
                                                <div class="ant-form-item">
                                                    <input class="ant-input ant-input-sm" id="rpId" placeholder="RP ID" value="">
                                                </div>

                                                <h4>Options</h4>
                                                <div class="settings-row">
                                                    <input type="checkbox" id="requireResidentKey" checked>
                                                    <label for="requireResidentKey">Discoverable Credentials</label>
                                                </div>

                                                <h4>User Verification</h4>
                                                <div class="settings-row"><input type="radio" id="userVerification_required" name="uv"><label for="userVerification_required">Required</label></div>
                                                <div class="settings-row"><input type="radio" id="userVerification_preferred" name="uv" checked><label for="userVerification_preferred">Preferred</label></div>
                                                <div class="settings-row"><input type="radio" id="userVerification_discouraged" name="uv"><label for="userVerification_discouraged">Discouraged</label></div>

                                                <h4>Authenticator Types</h4>
                                                <div style="display: grid; grid-template-columns: 1fr 1fr;">
                                                    <div class="settings-row"><input type="checkbox" id="type_usb" checked><label for="type_usb">USB</label></div>
                                                    <div class="settings-row"><input type="checkbox" id="type_nfc" checked><label for="type_nfc">NFC</label></div>
                                                    <div class="settings-row"><input type="checkbox" id="type_ble" checked><label for="type_ble">BLE</label></div>
                                                    <div class="settings-row"><input type="checkbox" id="type_hybrid" checked><label for="type_hybrid">Hybrid</label></div>
                                                    <div class="settings-row"><input type="checkbox" id="type_int" checked><label for="type_int">Internal</label></div>
                                                </div>

                                                <h4>Attestation Formats</h4>
                                                <div style="display: grid; grid-template-columns: 1fr 1fr;">
                                                    <div class="settings-row"><input type="checkbox" id="fmt_none" checked><label for="fmt_none">None</label></div>
                                                    <div class="settings-row"><input type="checkbox" id="fmt_packed" checked><label for="fmt_packed">Packed</label></div>
                                                    <div class="settings-row"><input type="checkbox" id="fmt_android-key" checked><label for="fmt_android-key">Android Key</label></div>
                                                    <div class="settings-row"><input type="checkbox" id="fmt_android-safetynet" checked><label for="fmt_android-safetynet">SafetyNet</label></div>
                                                    <div class="settings-row"><input type="checkbox" id="fmt_apple" checked><label for="fmt_apple">Apple</label></div>
                                                    <div class="settings-row"><input type="checkbox" id="fmt_tpm" checked><label for="fmt_tpm">TPM</label></div>
                                                    <div class="settings-row"><input type="checkbox" id="fmt_fido-u2f" checked><label for="fmt_fido-u2f">U2F</label></div>
                                                </div>

                                                <h4>Root Certificates</h4>
                                                <div style="display: grid; grid-template-columns: 1fr 1fr;">
                                                    <div class="settings-row"><input type="checkbox" id="cert_mds"><label for="cert_mds">MDS</label></div>
                                                    <div class="settings-row"><input type="checkbox" id="cert_apple"><label for="cert_apple">Apple</label></div>
                                                    <div class="settings-row"><input type="checkbox" id="cert_yubico"><label for="cert_yubico">Yubico</label></div>
                                                    <div class="settings-row"><input type="checkbox" id="cert_solo"><label for="cert_solo">Solo</label></div>
                                                    <div class="settings-row"><input type="checkbox" id="cert_hypersecu"><label for="cert_hypersecu">Hypersecu</label></div>
                                                    <div class="settings-row"><input type="checkbox" id="cert_google"><label for="cert_google">Google</label></div>
                                                    <div class="settings-row"><input type="checkbox" id="cert_microsoft"><label for="cert_microsoft">Microsoft</label></div>
                                                </div>
                                                
                                                <div style="margin-top: 16px;">
                                                    <button type="button" class="ant-btn ant-btn-sm" onclick="queryFidoMetaDataService()">Update MDS</button>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="user-authenticated-section" class="hidden" style="padding: 20px 0;">
                                            <div id="auth-avatar" class="avatar-circle">U</div>
                                            <h2 id="auth-user-name" style="margin-bottom: 4px;">User Name</h2>
                                            <p id="auth-user-id" style="color: rgba(0,0,0,0.45); margin-bottom: 32px;">@userid</p>
                                            
                                            <button type="button" class="ant-btn ant-btn-primary ant-btn-lg ant-btn-block" onclick="logout()">
                                                <span>Logout</span>
                                            </button>
                                        </div>

                                        <div id="status-container" class="hidden">
                                            <div id="status-message"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="preview-container">
                                    <div class="preview-header">
                                        <span style="font-weight: 500; font-size: 14px;">Server Data Preview</span>
                                        <button class="ant-btn ant-btn-sm ant-btn-dangerous" onclick="clearRegistrations()">Clear All</button>
                                    </div>
                                    <iframe src="_test/server.php?fn=getStoredDataHtml" id="serverPreview"></iframe>
                                </div>
                            </div>
                        </div>
                        <footer class="ant-layout-footer kb-text-center">
                            COPYRIGHT © 2026 KHAN BANK CORPORATION. ALL RIGHTS RESERVED.
                        </footer>
                    </div>
                </div>
            </main>
        </section>
    </div>
</body>
</html>
