/**
 * WebAuthn Logic - Tidy & Modernized
 */

// UI State Management
const UI = {
    loginForm: () => document.getElementById('login-flow-container'),
    userSection: () => document.getElementById('user-authenticated-section'),
    loadingOverlay: () => document.getElementById('loading-overlay'),
    loadingText: () => document.getElementById('loading-text'),
    statusContainer: () => document.getElementById('status-container'),
    statusMessage: () => document.getElementById('status-message'),
    
    showLoading: (msg) => {
        const overlay = UI.loadingOverlay();
        const text = UI.loadingText();
        if (overlay && text) {
            text.textContent = msg;
            overlay.classList.remove('hidden');
        }
    },
    
    hideLoading: () => {
        const overlay = UI.loadingOverlay();
        if (overlay) overlay.classList.add('hidden');
    },
    
    setStatus: (msg, type) => {
        const container = UI.statusContainer();
        const text = UI.statusMessage();
        if (!container || !text) return;
        
        text.textContent = msg;
        container.className = `status-message status-${type}`;
        container.classList.remove('hidden');
        
        // Optional auto-hide for success
        if (type === 'success') {
            setTimeout(() => container.classList.add('hidden'), 5000);
        }
    },
    
    hideStatus: () => {
        const container = UI.statusContainer();
        if (container) container.classList.add('hidden');
    },
    
    updateAuthenticatedView: (user) => {
        const nameEl = document.getElementById('auth-user-name');
        const idEl = document.getElementById('auth-user-id');
        const avatarEl = document.getElementById('auth-avatar');
        
        if (nameEl) nameEl.textContent = user.userDisplayName || user.userName;
        if (idEl) idEl.textContent = '@' + user.userName;
        if (avatarEl) avatarEl.textContent = (user.userDisplayName || user.userName).charAt(0).toUpperCase();
        
        UI.loginForm().classList.add('hidden');
        UI.userSection().classList.remove('hidden');
    },
    
    showLoginForm: () => {
        UI.userSection().classList.add('hidden');
        UI.loginForm().classList.remove('hidden');
    }
};

// Initial state check
async function checkLoginStatus() {
    try {
        const rep = await window.fetch('_test/server.php?fn=checkLogin', {cache: 'no-cache'});
        const res = await rep.json();
        if (res.success) {
            UI.updateAuthenticatedView(res);
        } else {
            UI.showLoginForm();
        }
    } catch (err) {
        console.error('Failed to check login status', err);
        UI.showLoginForm();
    }
}

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

        UI.showLoading('準備註冊中...');
        UI.hideStatus();

        let rep = await window.fetch('_test/server.php?fn=getCreateArgs' + getGetParams(), {method:'GET', cache:'no-cache'});
        const createArgs = await rep.json();

        if (createArgs.success === false) throw new Error(createArgs.msg || 'unknown error');

        recursiveBase64StrToArrayBuffer(createArgs);

        UI.showLoading('請使用您的安全金鑰或生物辨識...');
        const cred = await navigator.credentials.create(createArgs);

        const response = {
            transports: cred.response.getTransports  ? cred.response.getTransports() : null,
            clientDataJSON: cred.response.clientDataJSON  ? arrayBufferToBase64(cred.response.clientDataJSON) : null,
            attestationObject: cred.response.attestationObject ? arrayBufferToBase64(cred.response.attestationObject) : null
        };

        UI.showLoading('驗證中...');
        rep = await window.fetch('_test/server.php?fn=processCreate' + getGetParams(), {
            method: 'POST',
            body: JSON.stringify(response),
            cache: 'no-cache'
        });
        const res = await rep.json();

        UI.hideLoading();
        if (res.success) {
            UI.setStatus(res.msg || '註冊成功！', 'success');
        } else {
            throw new Error(res.msg);
        }

    } catch (err) {
        UI.hideLoading();
        UI.setStatus(err.name === 'NotAllowedError' ? '操作取消或逾時。' : (err.message || 'unknown error'), 'error');
    }
}

async function checkRegistration() {
    try {
        if (!window.fetch || !navigator.credentials || !navigator.credentials.get) {
            throw new Error('Browser not supported.');
        }

        updateUserId();
        const userName = document.getElementById('userName').value;
        // userName is optional for resident keys, but we often require it for non-resident flow

        UI.showLoading('準備登入...');
        UI.hideStatus();

        let rep = await window.fetch('_test/server.php?fn=getGetArgs' + getGetParams(), {method:'GET',cache:'no-cache'});
        const getArgs = await rep.json();

        if (getArgs.success === false) throw new Error(getArgs.msg);

        recursiveBase64StrToArrayBuffer(getArgs);

        UI.showLoading('請驗證您的身份...');
        const cred = await navigator.credentials.get(getArgs);

        const response = {
            id: cred.rawId ? arrayBufferToBase64(cred.rawId) : null,
            clientDataJSON: cred.response.clientDataJSON  ? arrayBufferToBase64(cred.response.clientDataJSON) : null,
            authenticatorData: cred.response.authenticatorData ? arrayBufferToBase64(cred.response.authenticatorData) : null,
            signature: cred.response.signature ? arrayBufferToBase64(cred.response.signature) : null,
            userHandle: cred.response.userHandle ? arrayBufferToBase64(cred.response.userHandle) : null
        };

        UI.showLoading('驗證登入中...');
        rep = await window.fetch('_test/server.php?fn=processGet' + getGetParams(), {
            method: 'POST',
            body: JSON.stringify(response),
            cache: 'no-cache'
        });
        const res = await rep.json();

        UI.hideLoading();
        if (res.success) {
            UI.updateAuthenticatedView(res);
            UI.setStatus('登入成功！', 'success');
        } else {
            throw new Error(res.msg);
        }

    } catch (err) {
        UI.hideLoading();
        UI.setStatus(err.name === 'NotAllowedError' ? '操作取消或逾時。' : (err.message || 'unknown error'), 'error');
    }
}

async function logout() {
    try {
        UI.showLoading('登出中...');
        await window.fetch('_test/server.php?fn=logout', {method:'GET', cache:'no-cache'});
        UI.showLoginForm();
        UI.hideStatus();
        UI.hideLoading();
    } catch (err) {
        UI.hideLoading();
        UI.setStatus('Logout failed: ' + err.message, 'error');
    }
}

// Utility Helpers
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
                    let bytes = new Uint8Array(binary_string.length);
                    for (let i = 0; i < binary_string.length; i++) bytes[i] = binary_string.charCodeAt(i);
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
    for (let i = 0; i < bytes.byteLength; i++) binary += String.fromCharCode(bytes[i]);
    return window.btoa(binary);
}

function updateUserId() {
    const username = document.getElementById('userName').value;
    const useridField = document.getElementById('userId');
    if (!username || !useridField) { if (useridField) useridField.value = ''; return; }
    let hex = '';
    for(let i=0;i<username.length;i++) hex += username.charCodeAt(i).toString(16);
    useridField.value = hex;
}

function getGetParams() {
    let url = '';
    const fields = ['rpId', 'userId', 'userName', 'userDisplayName'];
    fields.forEach(f => {
        const el = document.getElementById(f);
        if (el) url += `&${f}=${encodeURIComponent(el.value)}`;
    });
    
    const rk = document.getElementById('requireResidentKey');
    if (rk) url += `&requireResidentKey=${rk.checked ? '1' : '0'}`;
    
    const uv = document.querySelector('input[name="uv"]:checked');
    if (uv) url += `&userVerification=${uv.id.replace('userVerification_', '')}`;
    
    ['usb', 'nfc', 'ble', 'hybrid', 'int'].forEach(t => { 
        if (document.getElementById(`type_${t}`)?.checked) url += `&type_${t}=1`; 
    });
    ['none', 'packed', 'android-key', 'apple', 'tpm'].forEach(f => { 
        if (document.getElementById(`fmt_${f}`)?.checked) url += `&fmt_${f}=1`; 
    });
    return url;
}

// Auto-run on load
window.addEventListener('load', () => {
    if (!window.isSecureContext && location.protocol !== 'https:') {                
        location.href = location.href.replace('http://', 'https://');
    }
    const rpIdField = document.getElementById('rpId');
    if(rpIdField) rpIdField.value = location.hostname;
    
    checkLoginStatus();
});
