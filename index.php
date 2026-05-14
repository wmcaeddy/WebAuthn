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
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            border-radius: 8px;
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
        }
        .status-success { background: #f6ffed; border: 1px solid #b7eb8f; color: #52c41a; }
        .status-error { background: #fff1f0; border: 1px solid #ffa39e; color: #f5222d; }
        
        /* Custom styles for authenticated state */
        .authenticated-card { text-align: center; padding: 20px 0; }
        .avatar-circle {
            width: 80px;
            height: 80px;
            background-color: #00a859;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 20px;
        }
    </style>
    <script>
        async function createRegistration() {
            try {
                if (!window.fetch || !navigator.credentials || !navigator.credentials.create) throw new Error('Browser not supported.');
                const userName = document.getElementById('username').value;
                if (!userName) {
                    alert('Please enter a username first.');
                    document.getElementById('username').focus();
                    return;
                }
                
                const userDisplayName = prompt('Enter your Display Name:', userName) || userName;
                
                showLoading('Preparing registration...');
                hideStatus();
                
                let rep = await window.fetch('_test/server.php?fn=getCreateArgs&userName=' + encodeURIComponent(userName) + '&userDisplayName=' + encodeURIComponent(userDisplayName) + '&requireResidentKey=1', {method:'GET', cache:'no-cache'});
                const createArgs = await rep.json();
                if (createArgs.success === false) throw new Error(createArgs.msg || 'unknown error occured');
                
                recursiveBase64StrToArrayBuffer(createArgs);
                showLoading('Waiting for authenticator...');
                const cred = await navigator.credentials.create(createArgs);
                
                const response = await window.fetch('_test/server.php?fn=processCreate&userName=' + encodeURIComponent(userName), {
                    method: 'POST',
                    body: JSON.stringify({
                        transports: cred.response.getTransports ? cred.response.getTransports() : null,
                        clientDataJSON: arrayBufferToBase64(cred.response.clientDataJSON),
                        attestationObject: arrayBufferToBase64(cred.response.attestationObject)
                    })
                });
                const res = await response.json();
                hideLoading();
                if (res.success) { setStatus('Registration successful! You can now login.', 'success'); }
                else throw new Error(res.msg);
            } catch (err) { hideLoading(); setStatus(err.message, 'error'); }
        }

        async function checkRegistration() {
            try {
                const userName = document.getElementById('username').value;
                showLoading('Preparing authentication...');
                hideStatus();
                
                let rep = await window.fetch('_test/server.php?fn=getGetArgs&userName=' + encodeURIComponent(userName), {method:'GET',cache:'no-cache'});
                const getArgs = await rep.json();
                if (getArgs.success === false) throw new Error(getArgs.msg);
                
                recursiveBase64StrToArrayBuffer(getArgs);
                showLoading('Waiting for authenticator...');
                const cred = await navigator.credentials.get(getArgs);
                
                const response = await window.fetch('_test/server.php?fn=processGet', {
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
                    const loginForm = document.getElementById('fido-login-form');
                    const userSection = document.getElementById('user-authenticated-section');
                    document.getElementById('auth-user-name').textContent = res.userDisplayName || res.userName;
                    document.getElementById('auth-user-id').textContent = '@' + res.userName;
                    document.getElementById('auth-avatar').textContent = (res.userDisplayName || res.userName).charAt(0).toUpperCase();
                    
                    loginForm.classList.add('hidden');
                    userSection.classList.remove('hidden');
                    setStatus('Login successful', 'success');
                } else throw new Error(res.msg);
            } catch (err) { hideLoading(); setStatus(err.message, 'error'); }
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
        async function logout() { await window.fetch('_test/server.php?fn=logout'); location.reload(); }
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
                                    <div class="ant-card-body" style="position: relative;">
                                        <div id="loading-overlay" class="loading-overlay hidden">
                                            <div class="spinner"></div>
                                            <p id="loading-text" style="margin-top: 12px; font-weight: 500;">Please wait...</p>
                                        </div>
                                        <div style="text-align:center">
                                            <img src="logo-en.svg" class="logo" alt="Khan Bank">
                                        </div>

                                        <!-- FIDO Login Form -->
                                        <div id="fido-login-form">
                                            <form autocomplete="off" class="ant-form ant-form-horizontal" onsubmit="event.preventDefault(); checkRegistration();">
                                                <div class="login-username-container">
                                                    <div class="ant-form-item">
                                                        <div class="ant-row ant-form-item-row">
                                                            <div class="ant-col ant-form-item-control">
                                                                <div class="ant-form-item-control-input">
                                                                    <div class="ant-form-item-control-input-content">
                                                                        <input class="ant-input" placeholder="Username" id="username" value="" aria-required="true">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="ant-form-item">
                                                    <div class="ant-row ant-form-item-row">
                                                        <div class="ant-col ant-form-item-control">
                                                            <div class="ant-form-item-control-input">
                                                                <div class="ant-form-item-control-input-content">
                                                                    <button type="button" class="ant-btn ant-btn-primary ant-btn-lg ant-btn-block login-button" onclick="checkRegistration()">
                                                                        <span>Login</span>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="ant-form-item register-link">
                                                    <div class="ant-row ant-form-item-row">
                                                        <div class="ant-col ant-form-item-control">
                                                            <div class="ant-form-item-control-input">
                                                                <div class="ant-form-item-control-input-content">
                                                                    <a href="javascript:void(0)" onclick="createRegistration()">Sign Up</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>

                                        <!-- User Authenticated Section -->
                                        <div id="user-authenticated-section" class="hidden authenticated-card">
                                            <div id="auth-avatar" class="avatar-circle">U</div>
                                            <h2 id="auth-user-name" style="margin-bottom: 4px;">User Name</h2>
                                            <p id="auth-user-id" style="color: rgba(0,0,0,0.45); margin-bottom: 24px;">@userid</p>
                                            <button type="button" class="ant-btn ant-btn-primary ant-btn-lg ant-btn-block" onclick="logout()">
                                                <span>Logout</span>
                                            </button>
                                        </div>

                                        <!-- Status Message -->
                                        <div id="status-container" class="hidden">
                                            <div id="status-message"></div>
                                        </div>
                                    </div>
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
