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

        function toggleDeveloperSettings() {
            const tabs = document.getElementById('developer-tabs');
            const preview = document.getElementById('preview-container');
            const isHidden = tabs.classList.contains('hidden');
            
            if (isHidden) {
                tabs.classList.remove('hidden');
                preview.classList.remove('hidden');
            } else {
                tabs.classList.add('hidden');
                preview.classList.add('hidden');
            }
        }

        window.onload = function() {
            if (!window.isSecureContext && location.protocol !== 'https:') {                
                location.href = location.href.replace('http://', 'https://');
            }
            if (!document.getElementById('rpId').value) {
                document.getElementById('rpId').value = location.hostname;
            }
        }
    </script>
