<section class="hero">
    <div class="hero-content container-1400">
        <!-- Auth Card -->
        <div class="card">
            <header class="auth-header" style="margin-bottom: 30px; text-align: center;">
                <h1 style="color: var(--color-primary); font-size: 1.75rem; margin-bottom: 8px;">WebAuthn 登入驗證</h1>
                <p style="color: #666; font-size: 0.9rem;">專業的團隊, 精緻的服務</p>
            </header>

            <div id="login-flow-container">
                <div id="loading-overlay" class="hidden" style="margin-bottom: 20px;">
                    <div class="spinner"></div>
                    <p id="loading-text" style="color: var(--color-primary); font-weight: 500;">Please wait...</p>
                </div>

                <div class="form-group">
                    <label class="form-label">使用者名稱 (User Name)</label>
                    <input type="text" id="userName" class="form-control" placeholder="請輸入您的帳號" oninput="updateUserId()">
                </div>
                <div class="form-group">
                    <label class="form-label">顯示名稱 (Display Name)</label>
                    <input type="text" id="userDisplayName" class="form-control" placeholder="例如：王小明">
                </div>

                <button class="btn btn-primary btn-block" style="width: 100%; margin-bottom: 15px;" onclick="createRegistration()">註冊新憑證</button>
                <button class="btn btn-secondary btn-block" style="width: 100%;" onclick="checkRegistration()">使用憑證登入</button>
                
                <div style="text-align: center; margin-top: 20px;">
                    <button type="button" style="background:none; border:none; color:#888; font-size:0.8rem; cursor:pointer; text-decoration: underline;" onclick="toggleSettings()">進階設定 (Advanced)</button>
                </div>

                <!-- Advanced Settings (Hidden) -->
                <div id="advanced-settings" class="hidden" style="margin-top: 20px; border-top: 1px solid #eee; padding-top: 20px; text-align: left;">
                    <div class="form-group">
                        <label class="form-label">Relying Party ID</label>
                        <input type="text" id="rpId" class="form-control" value="">
                    </div>

                    <div class="form-group">
                        <label class="form-label">User ID (Hex)</label>
                        <input type="text" id="userId" class="form-control" readonly style="background-color: #f0f0f0; color: #666;">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" style="display:inline-block; margin-right:10px;">Resident Key:</label>
                        <input type="checkbox" id="requireResidentKey">
                    </div>

                    <div class="form-group">
                        <label class="form-label">User Verification</label>
                        <div style="font-size: 0.9rem;">
                            <label><input type="radio" name="userVerification" id="userVerification_required"> Required</label>
                            <label style="margin-left: 10px;"><input type="radio" name="userVerification" id="userVerification_preferred"> Preferred</label>
                            <label style="margin-left: 10px;"><input type="radio" name="userVerification" id="userVerification_discouraged" checked> Discouraged</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Authenticator Types</label>
                        <div style="font-size: 0.85rem; display: grid; grid-template-columns: 1fr 1fr; gap: 5px;">
                            <label><input type="checkbox" id="type_usb" checked> USB</label>
                            <label><input type="checkbox" id="type_nfc" checked> NFC</label>
                            <label><input type="checkbox" id="type_ble" checked> BLE</label>
                            <label><input type="checkbox" id="type_hybrid" checked> Hybrid</label>
                            <label><input type="checkbox" id="type_int" checked> Internal</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Attestation Formats</label>
                        <div style="font-size: 0.85rem; display: grid; grid-template-columns: 1fr 1fr; gap: 5px;">
                            <label><input type="checkbox" id="fmt_none" checked> None</label>
                            <label><input type="checkbox" id="fmt_packed" checked> Packed</label>
                            <label><input type="checkbox" id="fmt_android-key" checked> Android Key</label>
                            <label><input type="checkbox" id="fmt_apple" checked> Apple</label>
                            <label><input type="checkbox" id="fmt_tpm" checked> TPM</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Authenticated Section -->
            <div id="user-authenticated-section" class="hidden">
                <div class="user-section-card" style="text-align: center;">
                    <div id="auth-avatar" class="avatar-circle" style="width: 80px; height: 80px; background: var(--color-primary); color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin: 0 auto 20px;">U</div>
                    <div class="user-info">
                        <h2 id="auth-user-name" style="color: var(--color-primary);">User Name</h2>
                        <p id="auth-user-id" style="color: #666; margin-bottom: 20px;">@userid</p>
                    </div>
                    
                    <div style="background: var(--color-bg); padding: 16px; border-radius: var(--radius-md); margin-bottom: 24px;">
                        <p style="font-size: 0.875rem; margin-bottom: 4px; color: var(--color-text-dark); font-weight: 500;">目前狀態</p>
                        <p id="auth-login-time" class="login-meta" style="font-size: 0.8rem; color: #888;">Login time: --</p>
                        <p style="color: #1e7e34; font-weight: 500; margin-top: 8px;">登入成功</p>
                    </div>

                    <button type="button" class="btn btn-secondary btn-block" style="width: 100%;" onclick="logout()">
                        登出
                    </button>
                </div>
            </div>

            <div id="status-container" class="hidden" style="margin-top: 20px; padding: 10px; border-radius: 4px; font-size: 0.9rem;">
                <p id="status-message"></p>
            </div>
            
            <div style="text-align: center; margin-top: 15px;">
                <button type="button" style="background:none; border:none; color:#bbb; font-size:0.75rem; cursor:pointer;" onclick="togglePreview()">[ Show Server Data ]</button>
            </div>

            <!-- Server Preview (Hidden) -->
            <div id="preview-container" class="hidden" style="margin-top: 15px; border: 1px solid #eee; border-radius: 4px; padding: 10px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                    <h4 style="font-size: 0.8rem; margin: 0; color: #666;">Server Data</h4>
                    <button type="button" style="background: #fce8e6; border: 1px solid #d93025; color: #d93025; padding: 2px 8px; border-radius: 4px; font-size: 0.7rem; cursor: pointer;" onclick="clearRegistration()">Clear All</button>
                </div>
                <iframe src="_test/server.php?fn=getStoredDataHtml" id="serverPreview" style="width: 100%; height: 150px; border: 1px solid #eee; background: #fafafa;"></iframe>
            </div>
        </div>
    </div>
</section>
