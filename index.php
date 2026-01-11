<?php
// index.php - Fairline Layout with WebAuthn Integration
?>
<!DOCTYPE html>
<html lang="zh-tw">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>中飛科技股份有限公司 ::: WebAuthn 驗證服務</title>

<link href="assets/theme/favicon.ico" rel="icon">
<link href="assets/theme/bootstrap-5.3.3.min.css" rel="stylesheet">
<link href="assets/theme/font-awesome.min.css" rel="stylesheet">
<link href="assets/theme/animate.min.css" rel="stylesheet">
<link href="assets/theme/custom.css" rel="stylesheet">
<link href="assets/theme/plugin-slick.min.css" rel="stylesheet">
<link href="assets/theme/plugin-fix.css" rel="stylesheet">
<link href="assets/theme/plugin-magnific-popup.css" rel="stylesheet">

<script src="assets/theme/js/webauthn.js" defer></script>
<!-- Animations JS -->
<script src="assets/js/animations.js" defer></script>

<style>
    /* FIDO Auth Styles */
    .fido-auth-section {
        padding: 100px 0;
        background: #fdfdfd;
    }
    .auth-wrapper {
        position: relative;
        max-width: 540px;
        margin: 0 auto;
    }
    .auth-card {
        background: #fff;
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.05);
        border: 1px solid #eee;
    }
    .auth-header {
        text-align: center;
        margin-bottom: 30px;
    }
    .auth-header h3 {
        font-size: 1.8rem;
        margin-bottom: 10px;
        color: #333;
        font-weight: 700;
    }
    .auth-header p {
        color: #777;
        font-size: 0.95rem;
    }
    .form-group {
        margin-bottom: 20px;
    }
    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #444;
        font-size: 0.9rem;
    }
    .form-control-theme {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 1rem;
        transition: border-color 0.3s;
    }
    .form-control-theme:focus {
        border-color: #86a5b7;
        outline: none;
    }
    .btn-group-vertical {
        display: flex;
        flex-direction: column;
        gap: 15px;
        margin-top: 30px;
    }
    .btn-theme {
        padding: 15px;
        border-radius: 6px;
        font-weight: 600;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        border: none;
        width: 100%;
        font-size: 1rem;
    }
    .btn-theme-primary {
        background-color: #2c3e50;
        color: #fff;
    }
    .btn-theme-primary:hover {
        background-color: #1a252f;
        transform: translateY(-2px);
    }
    .btn-theme-secondary {
        background-color: #f8f9fa;
        color: #2c3e50;
        border: 1px solid #ddd;
    }
    .btn-theme-secondary:hover {
        background-color: #e9ecef;
    }
    .settings-toggle {
        text-align: center;
        margin-top: 20px;
    }
    .settings-toggle a {
        color: #86a5b7;
        font-size: 0.85rem;
        text-decoration: none;
        font-weight: 500;
    }
    
    /* Status Messages */
    .status-message {
        margin-top: 20px;
        padding: 15px;
        border-radius: 6px;
        font-size: 0.9rem;
        text-align: center;
    }
    
    /* Authenticated User Section */
    .user-section-card {
        text-align: center;
    }
    .avatar-circle {
        width: 80px;
        height: 80px;
        background-color: #86a5b7;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        font-weight: bold;
        margin: 0 auto 20px;
    }
    .user-info h4 {
        margin-bottom: 5px;
        color: #333;
    }
    .user-info p {
        font-size: 0.9rem;
        color: #777;
        margin-bottom: 20px;
    }
    .session-info {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 25px;
    }
    
    .loading-overlay {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(255,255,255,0.9);
        z-index: 10;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
    }
    .spinner {
        width: 40px;
        height: 40px;
        border: 4px solid #f3f3f3;
        border-top: 4px solid #86a5b7;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-bottom: 15px;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    .hidden { display: none !important; }

    /* Advanced Settings Styles */
    #advanced-settings {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px dashed #eee;
        text-align: left;
    }
    .config-group {
        margin-bottom: 15px;
    }
    .config-group h5 {
        font-size: 0.85rem;
        margin-bottom: 10px;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .checkbox-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.85rem;
        margin-bottom: 5px;
        color: #555;
    }
</style>

</head>
<body class="pace-done">
<?php include 'components/header.php'; ?>

<div class="g-wrap" id="index">
    <?php include 'components/hero.php'; ?>
    
    <div class="main">
        <section class="sec sec1 fido-auth-section" id="auth-section">
            <div class="container-1400">
                <div class="g-title showrole" data-scroll-view="init">
                    <div class="t1">AUTHENTICATION</div>
                    <h2 class="t2">身份驗證</h2>
                </div>
                
                <div class="auth-wrapper">
                    <!-- Loading Overlay -->
                    <div id="loading-overlay" class="loading-overlay hidden">
                        <div class="spinner"></div>
                        <p id="loading-text">請稍候...</p>
                    </div>

                    <div id="login-flow-container" class="auth-card">
                        <div class="auth-header">
                            <h3>Passkey 登入</h3>
                            <p>使用您的安全金鑰或生物辨識進行驗證</p>
                        </div>

                        <div id="tab-auth" class="auth-form">
                            <div class="form-group">
                                <label class="form-label" for="userName">使用者名稱</label>
                                <input type="text" id="userName" name="userName" class="form-control-theme" value="" required pattern="[0-9a-zA-Z]{2,}" oninput="updateUserId()" placeholder="例如: eddy">
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="userDisplayName">顯示名稱</label>
                                <input type="text" id="userDisplayName" name="userDisplayName" class="form-control-theme" value="" required placeholder="例如: Eddy Chen">
                            </div>

                            <div class="btn-group-vertical">
                                <button type="button" class="btn-theme btn-theme-primary" onclick="createRegistration()">
                                    註冊新裝置 (Register)
                                </button>
                                <button type="button" class="btn-theme btn-theme-secondary" onclick="checkRegistration()">
                                    使用 Passkey 登入 (Login)
                                </button>
                            </div>

                            <div class="settings-toggle">
                                <a href="javascript:void(0);" onclick="toggleSettings()">進階設定 <i class="fa fa-cog"></i></a>
                            </div>

                            <!-- Advanced Settings (Collapsed) -->
                            <div id="advanced-settings" class="hidden">
                                <div class="config-group">
                                    <label class="form-label" for="rpId">RP ID</label>
                                    <input type="text" id="rpId" name="rpId" class="form-control-theme" style="padding: 8px; font-size: 0.8rem;">
                                </div>
                                <div class="config-group">
                                    <label class="form-label" for="userId">User ID (Hex)</label>
                                    <input type="text" id="userId" name="userId" class="form-control-theme" style="padding: 8px; font-size: 0.8rem; background: #f9f9f9;" readonly>
                                </div>
                                <div class="config-group">
                                    <div class="checkbox-item">
                                        <input type="checkbox" id="requireResidentKey" checked>
                                        <label for="requireResidentKey">Discoverable Credential</label>
                                    </div>
                                </div>
                                
                                <div class="config-group">
                                    <h5>User Verification</h5>
                                    <div class="checkbox-item"><input type="radio" id="userVerification_required" name="uv"><label for="userVerification_required">Required</label></div>
                                    <div class="checkbox-item"><input type="radio" id="userVerification_preferred" name="uv"><label for="userVerification_preferred">Preferred</label></div>
                                    <div class="checkbox-item"><input type="radio" id="userVerification_discouraged" name="uv" checked><label for="userVerification_discouraged">Discouraged</label></div>
                                </div>
                                
                                <div class="config-group">
                                    <button type="button" class="btn-theme btn-theme-secondary" style="padding: 8px; font-size: 0.8rem; color: #d93025; border-color: #fce8e6;" onclick="clearRegistration()">
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
                                    <input type="checkbox" id="fmt_none" checked>
                                    <input type="checkbox" id="fmt_packed" checked>
                                    <input type="checkbox" id="fmt_android-key" checked>
                                    <input type="checkbox" id="fmt_apple" checked>
                                    <input type="checkbox" id="fmt_tpm" checked>
                                </div>
                            </div>
                        </div>

                        <!-- Status Message -->
                        <div id="status-container" class="status-message hidden">
                            <p id="status-message" style="margin: 0;"></p>
                        </div>
                    </div>

                    <!-- Authenticated User Section -->
                    <div id="user-authenticated-section" class="auth-card hidden">
                        <div class="user-section-card">
                            <div id="auth-avatar" class="avatar-circle">U</div>
                            <div class="user-info">
                                <h4 id="auth-user-name">User Name</h4>
                                <p id="auth-user-id">@userid</p>
                            </div>
                            
                            <div class="session-info">
                                <p style="font-size: 0.85rem; margin-bottom: 5px; color: #2c3e50; font-weight: 600;">驗證成功</p>
                                <p id="auth-login-time" style="font-size: 0.75rem; color: #999;">登入時間: --</p>
                            </div>

                            <button type="button" class="btn-theme btn-theme-secondary" onclick="logout()">
                                登出 (Logout)
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<?php include 'components/footer.php'; ?>
</body>
</html>