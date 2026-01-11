<?php
// index.php - Clean modular FIDO layout using latest scripting
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
        min-height: 350px;
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
    .btn-theme-secondary:hover { background-color: var(--color-bg-light); }

    .TitleB { font-size: 11px; color: #841852; font-weight:bold; }
    
    .hidden { display: none !important; }
    
    /* Loading overlay inside card */
    .loading-overlay {
        position: absolute; top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(255,255,255,0.9); z-index: 10;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        border-radius: 16px;
    }
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
              
              <!-- Legacy Components -->
              <?php include 'components/header.php'; ?>
              <?php include 'components/hero.php'; ?>

              <!-- Focused FIDO Card -->
              <div class="auth-section-wrapper">
                  <div class="auth-card">
                      <!-- 1. The Portable Loading Overlay -->
                      <div id="loading-overlay" class="loading-overlay hidden">
                          <div class="spinner"></div>
                          <p id="loading-text" style="color: var(--color-primary); font-weight: 600;">請稍候...</p>
                      </div>

                      <!-- 2. Login Flow Container -->
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

                          <div class="text-center" style="margin-top: 15px;">
                              <button type="button" style="background:none; border:none; color:var(--color-primary); font-size:0.75rem; cursor:pointer;" onclick="document.getElementById('preview-container').classList.toggle('hidden')">
                                  [ Toggle Data Preview ]
                              </button>
                          </div>

                          <!-- Config Fields (Logic Hidden) -->
                          <div class="hidden">
                              <input type="text" id="rpId" value="">
                              <input type="text" id="userId" value="">
                              <input type="checkbox" id="requireResidentKey" checked>
                              <!-- User Verification -->
                              <input type="radio" name="uv" id="userVerification_required">
                              <input type="radio" name="uv" id="userVerification_preferred">
                              <input type="radio" name="uv" id="userVerification_discouraged" checked="">
                              <input type="checkbox" id="type_usb" checked><input type="checkbox" id="type_nfc" checked><input type="checkbox" id="type_ble" checked><input type="checkbox" id="type_hybrid" checked><input type="checkbox" id="type_int" checked>
                              <input type="checkbox" id="fmt_none" checked><input type="checkbox" id="fmt_packed" checked><input type="checkbox" id="fmt_android-key" checked><input type="checkbox" id="fmt_apple" checked><input type="checkbox" id="fmt_tpm" checked>
                          </div>
                      </div>

                      <!-- 3. Authenticated State -->
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

                      <!-- Server Data Preview -->
                      <div id="preview-container" class="hidden" style="margin-top: 25px; border-top: 1px dashed #eee; padding-top: 20px;">
                          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                              <h4 style="font-size: 0.85rem; margin: 0; color: #666; text-transform: uppercase; letter-spacing: 1px;">Server Data</h4>
                              <button type="button" style="background: #fce8e6; border: 1px solid #d93025; color: #d93025; padding: 4px 10px; border-radius: 4px; font-size: 0.7rem; cursor: pointer; font-weight: 600;" onclick="clearRegistration()">
                                  Clear All
                              </button>
                          </div>
                          <iframe src="_test/server.php?fn=getStoredDataHtml" id="serverPreview" style="width: 100%; height: 250px; border: 1px solid #eee; border-radius: 8px; background: #fafafa;"></iframe>
                      </div>
                  </div>
              </div>

            </td>
            <td width="10" rowspan="2" valign="top" background="20260111141054139/line.gif"><img src="20260111141054139/line.gif" width="1" height="100"></td>
          </tr>
          <tr> 
            <td height="60" valign="top" bgcolor="#FFFFFF">
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
