<?php
// index.php - Fairline Layout with WebAuthn Integration
?>
<!DOCTYPE html>
<html lang="zh-tw">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>中飛科技股份有限公司 ::: WebAuthn 驗證服務</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        :root {
            --color-primary: #1a334a;
            --color-secondary: #86a5b7;
            --color-text-dark: #222222;
            --color-text-light: #ffffff;
            --color-bg-light: #f5f5f5;
            --font-main: "Poppins", "Microsoft JhengHei", sans-serif;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font-main);
            color: var(--color-text-dark);
            background-color: #fff;
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* Layout Structure */
        .page-wrapper {
            display: flex;
            min-height: 100vh;
            flex-direction: row;
        }

        /* Branding Side (Left) */
        .branding-side {
            flex: 1.2;
            background-color: var(--color-primary);
            color: white;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px;
            background-image: linear-gradient(rgba(26, 51, 74, 0.8), rgba(26, 51, 74, 0.8)), url('https://www.fairline.com.tw/data/adv/202405/1714968232414929265.jpg');
            background-size: cover;
            background-position: center;
        }

        .branding-content {
            max-width: 600px;
            z-index: 2;
        }

        .branding-content .t1 {
            font-size: 1.25rem;
            letter-spacing: 2px;
            margin-bottom: 10px;
            color: var(--color-secondary);
        }

        .branding-content h2.t2 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 30px;
            line-height: 1.2;
        }

        .branding-content .describe {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 40px;
        }

        /* Auth Side (Right) */
        .auth-side {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 40px;
            background-color: #fff;
            justify-content: space-between;
        }

        .auth-container {
            width: 100%;
            max-width: 440px;
            margin: auto;
        }

        .auth-header {
            margin-bottom: 40px;
        }

        .auth-header h1 {
            color: var(--color-primary);
            font-size: 1.75rem;
            margin-bottom: 8px;
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 8px;
            color: var(--color-primary);
        }

        input[type="text"] {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-family: inherit;
            font-size: 1rem;
        }

        .btn {
            display: block;
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            font-size: 1rem;
        }

        .btn-primary {
            background-color: var(--color-primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: #2c4a68;
        }

        .btn-secondary {
            background-color: transparent;
            border: 2px solid var(--color-primary);
            color: var(--color-primary);
            margin-top: 15px;
        }

        .btn-secondary:hover {
            background-color: var(--color-primary);
            color: white;
        }

        /* Footer Style */
        .site-footer {
            margin-top: 40px;
            font-size: 0.8rem;
            color: #888;
            text-align: center;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .branding-side {
                display: none; /* Hide branding side on mobile/tablet per requirement B */
            }
            .page-wrapper {
                flex-direction: column;
            }
            .auth-side {
                padding: 40px 20px;
            }
        }

        /* Existing Utilities from modern_client */
        .hidden { display: none; }
        .loading-overlay {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(255,255,255,0.9);
            z-index: 10;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .status-message {
            padding: 12px;
            border-radius: 4px;
            margin-top: 20px;
            font-size: 0.9rem;
        }
        .status-success { background: #e6f4ea; color: #1e7e34; }
        .status-error { background: #fce8e6; color: #d93025; }
    </style>

    <script>
        // Placeholder for WebAuthn JS (will migrate from modern_client.html)
        // ... (Logic to be integrated in Phase 5)
    </script>
</head>
<body>
    <div class="page-wrapper">
        <!-- Left Side: Branding -->
        <div class="branding-side">
            <div class="branding-content">
                <div class="t1">ABOUT FAIRLINE</div>
                <h2 class="t2">關於中飛</h2>
                <div class="describe">
                    中飛科技專精於網路安全與應用層安全技術，<br>
                    整合產品、技術與人才，<br>
                    提供最適合客戶需求且完善的整合性解決方案。
                </div>
            </div>
        </div>

        <!-- Right Side: Authentication -->
        <div class="auth-side">
            <div class="auth-container">
                <header class="auth-header">
                    <h1>WebAuthn 登入驗證</h1>
                    <p>專業的團隊, 精緻的服務</p>
                </header>

                <div id="login-flow-container">
                    <div class="form-group">
                        <label class="form-label">使用者名稱 (User Name)</label>
                        <input type="text" id="userName" placeholder="請輸入您的帳號">
                    </div>
                    <div class="form-group">
                        <label class="form-label">顯示名稱 (Display Name)</label>
                        <input type="text" id="userDisplayName" placeholder="例如：王小明">
                    </div>

                    <button class="btn btn-primary" onclick="alert('Registration coming soon')">註冊新憑證</button>
                    <button class="btn btn-secondary" onclick="alert('Login coming soon')">使用憑證登入</button>
                </div>

                <div id="status-container" class="hidden">
                    <p id="status-message"></p>
                </div>
            </div>

            <footer class="site-footer">
                <p>Copyright © Fairline Technology co., ltd. All rights Reserved.</p>
            </footer>
        </div>
    </div>
</body>
</html>
