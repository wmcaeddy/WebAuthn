<?php
// index.php - China Airlines Theme with WebAuthn Integration
?>
<!DOCTYPE html>
<html lang="zh-TW" translate="no">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,minimum-scale=1,maximum-scale=1,user-scalable=no">
<meta name="theme-color" content="#000000">
<meta name="description" content="China Airlines">
<title>華夏會員登入</title>
<link rel="icon" href="assets/theme/favicon.ico">
<link rel="stylesheet" href="assets/theme/font-awesome-5.13.0.min.css">
<link href="assets/theme/font-awesome.min.css" rel="stylesheet">
<!-- Bootstrap is included in theme.css, but just in case we need it specifically or if theme.css missed it (it seemed to be inlined) -->
<!-- Main Theme CSS (includes Bootstrap and custom styles) -->
<link href="assets/theme/theme.css" rel="stylesheet">

<script src="assets/theme/js/webauthn.js" defer></script>
<!-- Animations JS -->
<script src="assets/js/animations.js" defer></script>

<style>
    /* Custom overrides for FIDO integration */
    .auth-card {
        padding: 20px;
        background: #fff;
    }
    .auth-header h3 {
        margin-top: 0;
        font-size: 1.5rem;
        color: #2c3e50;
    }
    .auth-header p {
        color: #777;
        font-size: 0.9rem;
    }
    .btn-theme {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border-radius: 4px;
        cursor: pointer;
    }
    .btn-theme-primary {
        background-color: #23569d; /* Match theme blue */
        color: #fff;
        border: none;
    }
    .btn-theme-primary:hover {
        background-color: #1c457e;
    }
    .btn-theme-secondary {
        background-color: #f8f9fa;
        color: #333;
        border: 1px solid #ddd;
    }
    .hidden { display: none !important; }
    .loading-overlay {
        position: absolute; top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(255,255,255,0.9); z-index: 10;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        border-radius: 12px;
    }
    .spinner {
        width: 40px; height: 40px; border: 4px solid #f3f3f3; border-top: 4px solid #23569d;
        border-radius: 50%; animation: spin 1s linear infinite; margin-bottom: 15px;
    }
    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    
    /* Ensure Auth Card fits in the modal/login box */
    .login-box {
        position: relative; /* For loading overlay */
        min-height: 400px;
    }
</style>

</head>
<body class="lan-zh-TW individual" dir="ltr">
<div id="root">
    <div class="wrapper">
        <?php include 'components/header.php'; ?>

        <main role="main" class="guest-view page__login">
            <div data-test="LoginComponent" class="container">
                <div class="modal-dialog loginComponent">
                    <div class="modal-content">
                        <div class="">
                            <div class="">
                                <div class="modal-body p-0">
                                    
                                    <div class="login-box">
                                        <div class="login-heading">
                                            <h1 id="login-component-title">華夏會員登入</h1>
                                            <p>登入會員可享完整服務</p>
                                        </div>

                                        <!-- FIDO Authentication Card (Replaces original form) -->
                                        <div id="login-flow-container" class="auth-card">
                                            <!-- Loading Overlay -->
                                            <div id="loading-overlay" class="loading-overlay hidden">
                                                <div class="spinner"></div>
                                                <p id="loading-text">請稍候...</p>
                                            </div>

                                            <div id="tab-auth" class="auth-form">
                                                <div class="form-group">
                                                    <label class="form-label" for="userName">使用者名稱</label>
                                                    <input type="text" id="userName" name="userName" class="form-control" value="" required pattern="[0-9a-zA-Z]{2,}" oninput="updateUserId()" placeholder="例如: eddy">
                                                </div>

                                                <div class="form-group">
                                                    <label class="form-label" for="userDisplayName">顯示名稱</label>
                                                    <input type="text" id="userDisplayName" name="userDisplayName" class="form-control" value="" required placeholder="例如: Eddy Chen">
                                                </div>

                                                <div class="btn-group-vertical">
                                                    <button type="button" class="btn-theme btn-theme-primary" onclick="createRegistration()">
                                                        註冊新裝置 (Register)
                                                    </button>
                                                    <button type="button" class="btn-theme btn-theme-secondary" onclick="checkRegistration()">
                                                        使用 Passkey 登入 (Login)
                                                    </button>
                                                </div>

                                                <div class="text-center" style="margin-top: 15px;">
                                                    <button type="button" style="background:none; border:none; color:#bbb; font-size:0.75rem; cursor:pointer;" onclick="togglePreview()">[ Show Server Data ]</button>
                                                </div>

                                                <div class="settings-toggle text-center mt-3">
                                                    <a href="javascript:void(0);" onclick="toggleSettings()" style="font-size: 0.8rem; color: #999;">進階設定 <i class="fa fa-cog"></i></a>
                                                </div>

                                                <!-- Advanced Settings (Collapsed) -->
                                                <div id="advanced-settings" class="hidden" style="margin-top: 10px; padding: 10px; border-top: 1px dashed #eee;">
                                                    <div class="config-group">
                                                        <label class="form-label" for="rpId" style="font-size: 0.8rem;">RP ID</label>
                                                        <input type="text" id="rpId" name="rpId" class="form-control form-control-sm">
                                                    </div>
                                                    <div class="config-group mt-2">
                                                        <label class="form-label" for="userId" style="font-size: 0.8rem;">User ID (Hex)</label>
                                                        <input type="text" id="userId" name="userId" class="form-control form-control-sm" style="background: #f9f9f9;" readonly>
                                                    </div>
                                                    <div class="config-group mt-2">
                                                        <div class="checkbox-item">
                                                            <input type="checkbox" id="requireResidentKey" checked>
                                                            <label for="requireResidentKey" style="font-size: 0.8rem;">Discoverable Credential</label>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="config-group mt-2">
                                                        <button type="button" class="btn btn-sm btn-danger btn-block" onclick="clearRegistration()">
                                                            清除所有註冊資料
                                                        </button>
                                                    </div>
                                                    
                                                    <!-- Hidden form elements for JS logic -->
                                                    <div class="hidden">
                                                        <input type="checkbox" id="type_usb" checked>
                                                        <input type="checkbox" id="type_nfc" checked>
                                                        <input type="checkbox" id="type_ble" checked>
                                                        <input type="checkbox" id="type_hybrid" checked>
                                                        <input type="checkbox" id="type_int" checked>
                                                        <input type="radio" id="userVerification_required" name="uv">
                                                        <input type="radio" id="userVerification_preferred" name="uv">
                                                        <input type="radio" id="userVerification_discouraged" name="uv" checked>
                                                        <input type="checkbox" id="fmt_none" checked>
                                                        <input type="checkbox" id="fmt_packed" checked>
                                                        <input type="checkbox" id="fmt_android-key" checked>
                                                        <input type="checkbox" id="fmt_apple" checked>
                                                        <input type="checkbox" id="fmt_tpm" checked>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Status Message -->
                                            <div id="status-container" class="status-message hidden alert alert-info mt-3">
                                                <p id="status-message" style="margin: 0; font-size: 0.9rem;"></p>
                                            </div>
                    
                                            <div id="preview-container" class="hidden" style="margin-top: 15px; border: 1px solid #eee; border-radius: 4px; padding: 10px;">
                                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                                    <h4 style="font-size: 0.8rem; margin: 0; color: #666;">Server Data</h4>
                                                </div>
                                                <iframe src="_test/server.php?fn=getStoredDataHtml" id="serverPreview" style="width: 100%; height: 200px; border: 1px solid #eee; background: #fafafa;"></iframe>
                                            </div>
                                        </div>

                                        <!-- Authenticated User Section -->
                                        <div id="user-authenticated-section" class="auth-card hidden">
                                            <div class="user-section-card text-center">
                                                <div id="auth-avatar" class="avatar-circle" style="width: 80px; height: 80px; background-color: #23569d; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: bold; margin: 0 auto 20px;">U</div>
                                                <div class="user-info">
                                                    <h4 id="auth-user-name">User Name</h4>
                                                    <p id="auth-user-id" style="color: #777;">@userid</p>
                                                </div>
                                                
                                                <div class="session-info alert alert-success">
                                                    <p style="font-size: 0.9rem; margin-bottom: 0; font-weight: 600;">驗證成功</p>
                                                    <p id="auth-login-time" style="font-size: 0.8rem; margin-top: 5px;">登入時間: --</p>
                                                </div>

                                                <button type="button" class="btn-theme btn-theme-secondary" onclick="logout()">
                                                    登出 (Logout)
                                                </button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <?php include 'components/footer.php'; ?>
    </div>
</div>
</body>
</html>