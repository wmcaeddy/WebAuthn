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
            
            if (document.getElementById('auth-user-name')) {
                document.getElementById('auth-user-name').textContent = displayName || userName;
            }
            if (document.getElementById('auth-user-id')) {
                document.getElementById('auth-user-id').textContent = '@' + userName;
            }
            if (document.getElementById('auth-login-time')) {
                document.getElementById('auth-login-time').textContent = new Date().toLocaleString();
            }
            if (document.getElementById('auth-avatar')) {
                document.getElementById('auth-avatar').textContent = (displayName || userName).charAt(0).toUpperCase();
            }

            if (loginForm) loginForm.style.display = 'none';
            if (userSection) userSection.classList.remove('hidden');
            
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

        if (userSection) userSection.classList.add('hidden');
        if (loginForm) loginForm.style.display = 'block';
        
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
    if (!username || !useridField) { 
        if (useridField) useridField.value = ''; 
        return; 
    }
    let hex = '';
    for(let i=0;i<username.length;i++) { hex += ''+username.charCodeAt(i).toString(16); }
    useridField.value = hex;
}

function getGetParams() {
    let url = '';
    const rpIdField = document.getElementById('rpId');
    const userIdField = document.getElementById('userId');
    const userNameField = document.getElementById('userName');
    const userDisplayNameField = document.getElementById('userDisplayName');
    const requireResidentKeyField = document.getElementById('requireResidentKey');
    
    url += '&rpId=' + encodeURIComponent((rpIdField ? rpIdField.value : '') || location.hostname);
    url += '&userId=' + encodeURIComponent(userIdField ? userIdField.value : '');
    url += '&userName=' + encodeURIComponent(userNameField ? userNameField.value : '');
    url += '&userDisplayName=' + encodeURIComponent(userDisplayNameField ? userDisplayNameField.value : '');
    url += '&requireResidentKey=' + (requireResidentKeyField && requireResidentKeyField.checked ? '1' : '0');
    
    const uv_req = document.getElementById('userVerification_required');
    const uv_pref = document.getElementById('userVerification_preferred');
    const uv_disc = document.getElementById('userVerification_discouraged');
    
    if (uv_req && uv_req.checked) url += '&userVerification=required';
    else if (uv_pref && uv_pref.checked) url += '&userVerification=preferred';
    else if (uv_disc && uv_disc.checked) url += '&userVerification=discouraged';
    
    ['usb', 'nfc', 'ble', 'hybrid', 'int'].forEach(t => { 
        const el = document.getElementById('type_' + t);
        if (el && el.checked) url += '&type_' + t + '=1'; 
    });
    ['none', 'packed', 'android-key', 'apple', 'tpm'].forEach(f => { 
        const el = document.getElementById('fmt_' + f);
        if (el && el.checked) url += '&fmt_' + f + '=1'; 
    });
    return url;
}

function showLoading(message) {
    const overlay = document.getElementById('loading-overlay');
    const text = document.getElementById('loading-text');
    if(overlay) {
        if (text) text.textContent = message;
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
    if (!container || !msg) return;
    
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
    const container = document.getElementById('status-container');
    if (container) {
        container.classList.add('hidden');
        container.style.display = 'none';
    }
}

function reloadServerPreview() {
    let iframe = document.getElementById('serverPreview');
    if (iframe) iframe.src = iframe.src;
}

function toggleSettings() {
    const settings = document.getElementById('advanced-settings');
    if (settings) settings.classList.toggle('hidden');
}

function togglePreview() {
    const preview = document.getElementById('preview-container');
    if (preview) preview.classList.toggle('hidden');
}

window.onload = function() {
    if (!window.isSecureContext && location.protocol !== 'https:') {                
        location.href = location.href.replace('http://', 'https://');
    }
    const rpIdField = document.getElementById('rpId');
    if(rpIdField) {
        rpIdField.value = location.hostname;
    }
}
