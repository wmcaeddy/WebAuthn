<?php
// index.php - Legacy Header/Hero with Tidy modular FIDO Login
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Language" content="zh-tw">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>正新電腦有限公司 ::: WebAuthn 驗證服務</title>

<!-- Modern Assets -->
<link href="assets/theme/favicon.ico" rel="icon">
<link href="assets/theme/bootstrap-5.3.3.min.css" rel="stylesheet">
<link href="assets/theme/animate.min.css" rel="stylesheet">
<link href="assets/theme/custom.css" rel="stylesheet">
<script src="assets/theme/js/webauthn.js" defer></script>

<!-- Legacy Styles -->
<link href="20260111141054139/pronew.css" rel="stylesheet" type="text/css">
<link href="20260111141054139/showcut.css" rel="stylesheet" type="text/css">

<style>
    .auth-section-wrapper {
        padding: 80px 0;
        display: flex;
        justify-content: center;
        align-items: center;
        background: #fdf2f7;
    }
    
    .auth-card {
        background: #fff;
        padding: 40px;
        border-radius: 16px;
        box-shadow: 0 30px 60px rgba(132, 24, 82, 0.15);
        max-width: 480px;
        width: 100%;
        text-align: left;
        position: relative;
        min-height: 300px;
    }
    
    .auth-header h3 {
        color: var(--color-primary);
        font-weight: 700;
        margin-bottom: 10px;
        font-size: 1.75rem;
    }
    
    .auth-header p {
        color: #777;
        font-size: 0.95rem;
        margin-bottom: 30px;
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
        display: block;
        margin-bottom: 15px;
    }
    .btn-theme-primary { background-color: var(--color-primary); color: #fff; }
    .btn-theme-primary:hover { background-color: var(--color-secondary); transform: translateY(-2px); }
    .btn-theme-secondary { background-color: #fff; color: var(--color-primary); border: 1px solid var(--color-primary); }
    .btn-theme-secondary:hover { background-color: #fdf2f7; }

    .TitleB { font-size: 11px; color: #841852; font-weight:bold; }
    
    /* Transition Helper */
    .hidden { display: none !important; }
</style>

</head>
<body bgcolor="#f2f2f2" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tbody>
  <tr>
    <td valign="top"> 
      <div align="center">
        <table width="962" border="0" cellpadding="0" cellspacing="0">
          <tbody>
          <tr> 
            <td width="1" height="680" rowspan="2" valign="top" background="20260111141054139/line.gif"><img src="20260111141054139/line.gif" width="1" height="100"></td>
            <td width="1005" valign="top" bgcolor="#FFFFFF">
              
              <!-- Component: Header -->
              <?php include 'components/header.php'; ?>
              
              <!-- Component: Hero (Slider) -->
              <?php include 'components/hero.php'; ?>

              <!-- Tidy modular Auth Section -->
              <div class="auth-section-wrapper">
                  <div class="auth-card">
                      <!-- Loading Overlay -->
                      <div id="loading-overlay" class="loading-overlay hidden">
                          <div class="spinner"></div>
                          <p id="loading-text">請稍候...</p>
                      </div>

                      <!-- State 1: Login Form (Hidden by default until checkLoginStatus) -->
                      <div id="login-flow-container" class="hidden">
                          <div class="auth-header">
                              <h3>Passkey 登入</h3>
                              <p>使用您的安全金鑰或生物辨識進行驗證</p>
                          </div>

                          <div class="form-group">
                              <label class="form-label">使用者名稱</label>
                              <input type="text" id="userName" class="form-control-theme" oninput="updateUserId()" placeholder="例如: eddy">
                          </div>

                          <div class="form-group">
                              <label class="form-label">顯示名稱</label>
                              <input type="text" id="userDisplayName" class="form-control-theme" placeholder="例如: Eddy Chen">
                          </div>

                          <div class="btn-group-vertical">
                              <button type="button" class="btn-theme btn-theme-primary" onclick="createRegistration()">
                                  註冊新裝置 (Register)
                              </button>
                              <button type="button" class="btn-theme btn-theme-secondary" onclick="checkRegistration()">
                                  使用 Passkey 登入 (Login)
                              </button>
                          </div>

                          <!-- Hidden Configuration Fields -->
                          <div class="hidden">
                              <input type="text" id="rpId" value="">
                              <input type="text" id="userId" value="">
                              <input type="checkbox" id="requireResidentKey" checked>
                              <input type="radio" name="uv" id="userVerification_discouraged" checked>
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

                      <!-- State 2: Authenticated (Hidden by default until checkLoginStatus) -->
                      <div id="user-authenticated-section" class="hidden">
                          <div class="user-section-card" style="text-align: center; padding: 20px 0;">
                              <div id="auth-avatar" class="avatar-circle" style="width: 80px; height: 80px; background-color: var(--color-primary); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: bold; margin: 0 auto 20px;">U</div>
                              <div class="user-info">
                                  <h4 id="auth-user-name" style="color: var(--color-primary); margin-bottom: 5px; font-weight: 700;">User Name</h4>
                                  <p id="auth-user-id" style="color: #777; font-size: 0.9rem; margin-bottom: 30px;">@userid</p>
                              </div>
                              
                              <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 30px; border: 1px solid #eee;">
                                  <p style="font-size: 0.85rem; color: #2c3e50; font-weight: 600; margin-bottom: 5px;">驗證成功</p>
                                  <p id="auth-login-time" style="font-size: 0.75rem; color: #999; margin: 0;">登入時間: --</p>
                              </div>

                              <button type="button" class="btn-theme btn-theme-secondary" onclick="logout()">登出 (Logout)</button>
                          </div>
                      </div>
                      
                      <!-- Status Messaging -->
                      <div id="status-container" class="status-message hidden">
                          <p id="status-message" style="margin: 0;"></p>
                      </div>
                  </div>
              </div>

            </td>
            <td width="10" rowspan="2" valign="top" background="20260111141054139/line.gif"><img src="20260111141054139/line.gif" width="1" height="100"></td>
          </tr>
          <tr> 
            <td height="60" valign="top" bgcolor="#FFFFFF">
              <!-- Component: Footer -->
              <?php include 'components/footer.php'; ?>
            </td>
          </tr>
          </tbody>
        </table>
      </div>
    </td>
  </tr>
  </tbody>
</table>

</body>
</html>