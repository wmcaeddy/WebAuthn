<?php
// index.php - Combined Modular Layout with ProNew Branding and FIDO WebAuthn
?>
<!DOCTYPE html>
<html lang="zh-tw">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>正新電腦有限公司 ::: WebAuthn 驗證服務</title>

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
    /* 1. The Split-Screen Strategy */
    .g-wrap {
        width: 100%;
        max-width: 100%;
        overflow-x: hidden;
    }
    .split-screen-container {
        display: flex;
        flex-wrap: nowrap;
        min-height: 100vh;
        width: 100%;
        position: relative;
    }
    .split-hero {
        flex: 1; /* Occupies majority of screen space */
        position: relative;
        overflow: hidden;
        min-width: 0;
    }
    .split-auth {
        flex: 0 0 480px; /* Fixed-width column on desktop */
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 40px;
        background: var(--color-bg-light);
        border-left: 1px solid rgba(132, 24, 82, 0.1);
        z-index: 2;
    }

    /* 2. Component Encapsulation (.auth-card) */
    .auth-wrapper {
        position: relative;
        width: 100%;
        max-width: 440px;
    }
    .auth-card {
        background: #fff;
        padding: 40px 30px;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-card);
        border: 1px solid rgba(0,0,0,0.03);
        transition: transform 0.3s ease;
    }
    .auth-card:hover {
        transform: translateY(-5px);
    }
    .auth-header h3 {
        font-size: 2rem;
        margin-bottom: 12px;
        color: var(--color-primary);
        font-weight: 700;
        letter-spacing: -0.5px;
    }
    .auth-header p {
        color: var(--color-text-muted);
        font-size: 0.95rem;
    }
    
    /* 3. Responsive "Reverse-Stack" Adaptation */
    @media (max-width: 991px) {
        .split-screen-container { 
            flex-direction: column-reverse; /* Card at top on mobile */
            min-height: auto;
        }
        .split-hero {
            height: 400px;
            width: 100%;
        }
        .split-auth {
            flex: auto;
            width: 100%;
            padding: 60px 20px;
            border-left: none;
            background: #fff;
        }
        .auth-card {
            box-shadow: none;
            border: none;
            padding: 20px 0;
        }
    }

    /* Common Form Elements within Card */
    .form-group { margin-bottom: 20px; }
    .form-label { display: block; margin-bottom: 8px; font-weight: 600; color: #444; font-size: 0.9rem; }
    .form-control-theme {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: var(--radius-md);
        font-size: 1rem;
        transition: border-color 0.3s;
    }
    .form-control-theme:focus { border-color: var(--color-primary); outline: none; }
    
    .btn-group-vertical { display: flex; flex-direction: column; gap: 15px; margin-top: 30px; }
    .btn-theme {
        padding: 15px;
        border-radius: var(--radius-md);
        font-weight: 600;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        border: none;
        width: 100%;
        font-size: 1rem;
    }
    .btn-theme-primary { background-color: var(--color-primary); color: #fff; }
    .btn-theme-primary:hover { background-color: var(--color-secondary); transform: translateY(-2px); }
    .btn-theme-secondary { background-color: #fff; color: var(--color-primary); border: 1px solid var(--color-primary); }
    .btn-theme-secondary:hover { background-color: var(--color-bg-light); }
    
    .settings-toggle { text-align: center; margin-top: 20px; }
    .settings-toggle a { color: var(--color-primary); font-size: 0.85rem; text-decoration: none; font-weight: 500; }
    .status-message { margin-top: 20px; padding: 15px; border-radius: var(--radius-md); font-size: 0.9rem; text-align: center; }
    
    .hidden { display: none !important; }
    .loading-overlay {
        position: absolute; top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(255,255,255,0.9); z-index: 10;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        border-radius: var(--radius-lg);
    }
    .spinner {
        width: 40px; height: 40px; border: 4px solid #f3f3f3; border-top: 4px solid var(--color-primary);
        border-radius: 50%; animation: spin 1s linear infinite; margin-bottom: 15px;
    }
    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

    #advanced-settings { margin-top: 20px; padding-top: 20px; border-top: 1px dashed #eee; text-align: left; }
    .config-group { margin-bottom: 15px; }
    .config-group h5 { font-size: 0.85rem; margin-bottom: 10px; color: #666; text-transform: uppercase; letter-spacing: 1px; }
    .checkbox-item { display: flex; align-items: center; gap: 8px; font-size: 0.85rem; margin-bottom: 5px; color: #555; }
    
    .user-section-card { text-align: center; }
    .avatar-circle { width: 80px; height: 80px; background-color: var(--color-primary); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: bold; margin: 0 auto 20px; }
    .user-info h4 { margin-bottom: 5px; color: #333; }
    .user-info p { font-size: 0.9rem; color: #777; margin-bottom: 20px; }
    .session-info { background: var(--color-bg-light); padding: 15px; border-radius: var(--radius-md); margin-bottom: 25px; border: 1px solid rgba(132, 24, 82, 0.1); }
</style>

</head>
<body class="pace-done">
<?php include 'components/header.php'; ?>

<div class="g-wrap" id="index">
    <div class="split-screen-container">
        <!-- Hero Side: Branding -->
        <div class="split-hero">
            <?php include 'components/hero.php'; ?>
        </div>

        <!-- Auth Side: FIDO Login Card -->
        <div class="split-auth">
            <div class="auth-wrapper">
                <!-- Loading Overlay -->
                <div id="loading-overlay" class="loading-overlay hidden">
                    <div class="spinner"></div>
                    <p id="loading-text">請稍候...</p>
                </div>

                <!-- 4. Logic-UI Decoupling (Ensured IDs) -->
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

                        <div class="text-center" style="margin-top: 15px;">
                            <button type="button" style="background:none; border:none; color:var(--color-primary); font-size:0.75rem; cursor:pointer;" onclick="togglePreview()">[ Show Server Data ]</button>
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

                    <div id="preview-container" class="hidden" style="margin-top: 15px; border: 1px solid #eee; border-radius: var(--radius-md); padding: 10px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                            <h4 style="font-size: 0.8rem; margin: 0; color: #666;">Server Data</h4>
                            <button type="button" style="background: #fce8e6; border: 1px solid #d93025; color: #d93025; padding: 2px 8px; border-radius: 4px; font-size: 0.7rem; cursor: pointer;" onclick="clearRegistration()">Clear All</button>
                        </div>
                        <iframe src="_test/server.php?fn=getStoredDataHtml" id="serverPreview" style="width: 100%; height: 200px; border: 1px solid #eee; background: #fafafa;"></iframe>
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
                            <p style="font-size: 0.85rem; margin-bottom: 5px; color: var(--color-primary); font-weight: 600;">驗證成功</p>
                            <p id="auth-login-time" style="font-size: 0.75rem; color: #999;">登入時間: --</p>
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

<?php include 'components/about.php'; ?>
<?php include 'components/brands.php'; ?>
<?php include 'components/news.php'; ?>

<?php include 'components/footer.php'; ?>
</body>
</html>