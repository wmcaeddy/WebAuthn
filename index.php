<?php
// Galaxy Macau Theme index.php
?>
<!DOCTYPE html><html lang="zh-CHT"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"><title>登錄 | 澳門銀河，世界級的亞洲度假勝地</title>
<link rel="icon" type="image/png" href="assets/theme/images/fav.0f84f8d50c11d05a0665.png">
<link rel="stylesheet" href="assets/theme/css/style.2a9e0de6901cd2b8a4a6.css">
<link rel="stylesheet" href="assets/theme/css/style.d597768381caa428f660.css">
<style>
    /* Custom overrides for FIDO integration */
    .hidden { display: none !important; }
    .fade-out { opacity: 0; transition: opacity 0.3s ease; pointer-events: none; }
    .fade-in { opacity: 1; transition: opacity 0.3s ease; }
    .loading-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(255,255,255,0.8); z-index: 9999;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
    }
    .spinner {
        border: 4px solid rgba(153, 117, 66, 0.1);
        width: 36px; height: 36px;
        border-radius: 50%;
        border-left-color: #a37c3c;
        animation: spin 1s linear infinite;
        margin-bottom: 16px;
    }
    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    
    body { background-color: #f8f8f8; color: #333; font-family: "Poppins", "Noto Sans TC", sans-serif; }
    .layout { min-height: 100vh; display: flex; flex-direction: column; }
    #page { flex: 1; padding: 40px 0; }
    .formLogin-container { 
        background-color: #fff; 
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        border-radius: 8px;
        overflow: hidden;
    }
    
    /* Notification style override */
    #status-container {
        position: fixed; bottom: 20px; right: 20px; z-index: 10000;
        padding: 15px 25px; border-radius: 4px; color: white;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .status-error { background-color: #f44336; }
    .status-success { background-color: #4caf50; }
    .status-info { background-color: #2196f3; }

    /* Profile view styles */
    .user-section-card { text-align: center; padding: 40px 20px; }
    .avatar-circle {
        width: 100px; height: 100px; background-color: #a37c3c; color: white;
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        font-size: 2.5rem; font-weight: bold; margin: 0 auto 24px;
    }
    .user-info h2 { font-size: 1.5rem; margin-bottom: 8px; color: #343434; }
    .user-info p { font-size: 1rem; color: #666; margin-bottom: 32px; }

    /* List 3 cols styles for benefits */
    .list-3-cols { display: flex; font-size: 1rem; flex-wrap: wrap; margin: 20px auto; width: 100%; padding-left: 0; }
    .list-3-cols__item { 
        position: relative; margin: 0 0 18px; line-height: 1.75rem; padding-left: 25px; 
        list-style: none; width: 100%; color: #555;
    }
    .list-3-cols__item::before { 
        content: ""; position: absolute; width: 8px; height: 8px; 
        background-color: #a37c3c; border-radius: 50%; left: 5px; top: 10px; 
    }
    @media (min-width: 760px) {
        .list-3-cols__item { width: 50%; }
    }
    
    /* Footer refinement */
    .FooterLogoContainer-LinkedImage img { max-width: 100%; height: auto; }
</style>
</head>
<body>
    <div id="app">
        <!-- Loading Overlay -->
        <div id="loading-overlay" class="loading-overlay hidden">
            <div class="spinner"></div>
            <p id="loading-text" style="color: #a37c3c; font-weight: 500;">請稍候...</p>
        </div>

        <div class="layout">
            <header id="header" class="partials__ReusedHeader-sc-13o1y4p-0 Header__HeaderContainer-sc-9vacri-0 eNQVmN">
                <div class="header-first">
                    <div class="header-first-left">
                        <span class="ml-2">銀河娛樂集團旗下成員</span>
                        <a href="#" class="header-first-left__link">星際酒店</a>
                        <a href="#" class="header-first-left__link">澳門百老匯</a>
                        <a href="#" class="header-first-left__link">銀河國際會議中心</a>
                    </div>
                    <div class="header-first-right">
                        <ul class="header-first-right__list">
                            <li class="header-first-right__item"><i class="fab fa-weixin mr-2"></i>微信小程序</li>
                            <li class="header-first-right__item"><i class="fas fa-map-marker-alt mr-2"></i>交通指南</li>
                            <li class="header-first-right__item header-first-right__item_user">
                                <i class="fas fa-user-circle mr-2"></i>
                                <a href="javascript:void(0)" id="header-login-link">登錄</a>&nbsp;|&nbsp;<a href="javascript:void(0)" id="header-join-link">加入</a>
                            </li>
                            <li class="header-first-right__item"><i class="fas fa-globe mr-2"></i>Language</li>
                        </ul>
                    </div>
                </div>
                <div class="HeaderSecond__Container-sc-hfrld3-0 iyagoH">
                    <div class="header-second">
                        <div class="header-second-tabs">
                            <div class="HeaderMenu__Container-sc-1qkci8u-0 evkpuI">
                                <div class="HeaderMenuItem__Container-sc-1ta9iu3-0 bDklmS menuItem-container"><div class="menu-toggle menuItem__menuItem"><a href="#">最新優惠</a></div></div>
                                <div class="HeaderMenuItem__Container-sc-1ta9iu3-0 bDklmS menuItem-container"><div class="menu-toggle menuItem__menuItem"><a href="#">酒店住宿</a></div></div>
                                <div class="HeaderMenuItem__Container-sc-1ta9iu3-0 bDklmS menuItem-container"><div class="menu-toggle menuItem__menuItem"><a href="#">餐　飲</a></div></div>
                                <div class="HeaderMenuItem__Container-sc-1ta9iu3-0 bDklmS menuItem-container"><div class="menu-toggle menuItem__menuItem"><a href="#">購　物</a></div></div>
                                <div class="HeaderMenuItem__Container-sc-1ta9iu3-0 bDklmS menuItem-container"><div class="menu-toggle menuItem__menuItem"><a href="#">精彩體驗</a></div></div>
                            </div>
                            <div class="HeaderLogo__Container-sc-torv75-0 fIWwZa">
                                <div class="logoContainer">
                                    <a href="/" class="bigLogo"><img src="assets/theme/images/gm_revwhite_tc_1_251125.png" alt="Galaxy Macau" class="cpRCiS"></a>
                                </div>
                            </div>
                            <div class="HeaderMenu__Container-sc-1qkci8u-0 evkpuI">
                                <div class="HeaderMenuItem__Container-sc-1ta9iu3-0 bDklmS menuItem-container"><div class="menu-toggle menuItem__menuItem"><a href="#">門票及表演</a></div></div>
                                <div class="HeaderMenuItem__Container-sc-1ta9iu3-0 bDklmS menuItem-container"><div class="menu-toggle menuItem__menuItem"><a href="#">會議及活動</a></div></div>
                                <div class="HeaderMenuItem__Container-sc-1ta9iu3-0 bDklmS menuItem-container"><div class="menu-toggle menuItem__menuItem"><a href="#">工銀澳門銀河信用卡</a></div></div>
                            </div>
                        </div>
                        <div class="header-second-hotel">
                            <div class="BookingHotelsTab__Container-sc-k98pmj-0 hLhzew HeaderSecond__BookingWidgetTab-sc-hfrld3-4 cIbHBf">
                                <div class="hotel-form">
                                    <div class="HotelSelect__Container-sc-1ebk9e2-0 jwKePn BookingHotelsTab__HotelsSelect-sc-k98pmj-1 hzFAsK">
                                        <div class="hotel-select">
                                            <div class="hotel-btn">
                                                <img class="hotel-icon" src="assets/theme/images/bookingbar-icon1.12d7d4801102ed7d3a57.png" alt="icon">
                                                <div class="hotel-name">選擇酒店</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="DoubleDatePicker__Container-sc-11xv73i-0 BookingHotelsTab__DatePicker-sc-k98pmj-3 jhxdJx">
                                        <div class="doubleDate-select">
                                            <div class="doubleDate-btn">
                                                <img class="doubleDate-icon" src="assets/theme/images/bookingbar-icon2.be211e66eb18ed9a2b81.png" alt="icon">
                                                <div class="doubleDate-name">選擇日期</div>
                                            </div>
                                        </div>
                                    </div>
                                    <a class="styled__BookNativeLink-sc-tit157-16 cGZQEf bookBtn" href="#">立即預訂</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <div id="page" class="eTPeDE">
                <div id="page-content">
                    <div class="kEcRTH fWXxdP formLogin-container">
                        
                        <!-- Login/Register Flow -->
                        <div id="auth-flow-container" class="formLogin-left">
                            <div class="formLogin-left-container">
                                <h3 class="dwanBp iGKSzW">
                                    <div class="fSxUpu"></div>
                                    <span id="auth-title" class="gHHdZC">登入</span>
                                </h3>
                                <p class="formLogin-left-description">
                                    <img class="formLogin-left-icon" alt="" src="assets/theme/images/icon-enjoy.09264fc967c127554341.svg">
                                    <span id="auth-desc">登入預訂指定酒店*立減更多</span>
                                </p>
                                <p style="font-size: 0.75rem; color: #666; margin-bottom: 20px;">*適用於澳門安達仕酒店、「百老匯酒店」、「銀河酒店」、澳門大倉酒店</p>
                                
                                <div class="login-container">
                                    <form id="auth-form" class="TabLoginByEmail-form" onsubmit="return false;">
                                        <div class="Textfield-root">
                                            <label class="Textfield-field">
                                                <div class="Textfield-icon"><i class="fas fa-envelope"></i></div>
                                                <div class="Textfield-inputWrapper">
                                                    <input type="text" id="userName" name="userName" placeholder="請填寫你的電郵地址" class="Textfield-input" required pattern="[0-9a-zA-Z@.]{2,}">
                                                </div>
                                            </label>
                                        </div>
                                        
                                        <div id="displayName-group" class="Textfield-root hidden">
                                            <label class="Textfield-field">
                                                <div class="Textfield-icon"><i class="fas fa-id-card"></i></div>
                                                <div class="Textfield-inputWrapper">
                                                    <input type="text" id="userDisplayName" name="userDisplayName" placeholder="顯示名稱" class="Textfield-input">
                                                </div>
                                            </label>
                                        </div>

                                        <div class="flex justify-center mt-4">
                                            <button id="auth-submit-btn" class="border border-yellow-750 text-yellow-750 text-xl px-4 py-3 cursor-pointer hover:text-white hover:bg-yellow-750 transition-colors min-w-[200px]">
                                                登入
                                            </button>
                                        </div>
                                    </form>
                                    <div class="formLogin-form-forget mt-4 text-center">
                                        <a class="text-yellow-750 hover:underline" href="#">忘記密碼</a>
                                    </div>
                                </div>

                                <div class="formLogin-form">
                                    <h3 class="dwanBp iGKSzW small-h3">
                                        <div class="fSxUpu small-h3-line"></div>
                                        <span id="toggle-prompt" class="gHHdZC">還未加入?</span>
                                    </h3>
                                    <p id="toggle-desc" class="formLogin-right-info">註冊帳號，即享額外精彩禮遇</p>
                                    <div class="formLogin-form-create">
                                        <button id="auth-toggle-btn" class="border border-yellow-750 text-yellow-750 text-xl px-4 py-3 hover:text-white hover:bg-yellow-750 transition-colors min-w-[200px]" type="button">
                                            創建帳號
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Authenticated Section -->
                        <div id="user-authenticated-section" class="formLogin-left hidden">
                            <div class="formLogin-left-container">
                                <h3 class="dwanBp iGKSzW">
                                    <div class="fSxUpu"></div>
                                    <span class="gHHdZC">歡迎回來</span>
                                </h3>
                                <div class="user-section-card">
                                    <div id="auth-avatar" class="avatar-circle">U</div>
                                    <div class="user-info">
                                        <h2 id="auth-user-name">User Name</h2>
                                        <p id="auth-user-id">@userid</p>
                                    </div>
                                    <div class="flex justify-center">
                                        <button type="button" class="border border-yellow-750 text-yellow-750 text-xl px-4 py-3 hover:text-white hover:bg-yellow-750 transition-colors min-w-[200px]" onclick="logout()">
                                            登出
                                        </button>
                                    </div>
                                    <p id="auth-login-time" style="font-size: 0.75rem; color: #999; margin-top: 24px;">登錄時間: --</p>
                                </div>
                            </div>
                        </div>

                        <div class="column-line"></div>

                        <!-- Right Side Info -->
                        <div class="formLogin-right">
                            <div class="formLogin-right-container">
                                <h2 class="bBFmiX"><p>直接於官網預訂享受</p></h2>
                                <div class="ckeditor__CKEditorText-sc-1hdgwz8-0 fpExem text__value">
                                    <ul class="list-3-cols">
                                        <li class="list-3-cols__item">官網保證房價最優</li>
                                        <li class="list-3-cols__item">優先升級客房</li>
                                        <li class="list-3-cols__item">靈活退改條款</li>
                                        <li class="list-3-cols__item">獲取銀河獎賞積分</li>
                                    </ul>
                                    <div class="flex flex-wrap justify-between mt-8 opacity-80">
                                        <img src="assets/theme/images/hotel_galaxy_4c_tc_251125.png" alt="Galaxy" style="height: 30px; margin-bottom: 10px;">
                                        <img src="assets/theme/images/logo-hotel-okura-macau-tc.png" alt="Okura" style="height: 30px; margin-bottom: 10px;">
                                        <img src="assets/theme/images/logo-banyantree-macau-tc.png" alt="Banyan Tree" style="height: 30px; margin-bottom: 10px;">
                                        <img src="assets/theme/images/logo-jw-marriott-macau-tc.png" alt="JW" style="height: 30px; margin-bottom: 10px;">
                                        <img src="assets/theme/images/logo-the-ritzcarlton-macau.png" alt="Ritz" style="height: 30px; margin-bottom: 10px;">
                                        <img src="assets/theme/images/Andaz logo TC - 501x500.png" alt="Andaz" style="height: 30px; margin-bottom: 10px;">
                                    </div>
                                    <br>
                                    <p style="font-size: 0.75rem; color: #666;">*僅適用於「銀河酒店」。受條款及細則約束。</p>
                                    <div class="flex justify-center border-t border-gray-200 pt-4 mt-4">
                                        <button type="button" class="text-xs text-gray-400 hover:text-yellow-750 transition-colors" onclick="toggleDebug()">
                                            WebAuthn 調試工具
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Debug Tools (Hidden by default) -->
                    <div id="debug-container" class="kEcRTH hidden" style="margin-top: 40px; padding: 20px; border-top: 1px solid #ddd;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                            <h3 style="font-size: 1.2rem; margin: 0;">服務器數據預覽</h3>
                            <button type="button" class="text-red-450 hover:underline" onclick="clearRegistration()">
                                清除所有數據
                            </button>
                        </div>
                        <iframe src="_test/server.php?fn=getStoredDataHtml" id="serverPreview" style="width: 100%; height: 400px; border: 1px solid #ddd; border-radius: 4px;"></iframe>
                    </div>
                </div>
            </div>

            <footer class="byCIhE gkMYuH">
                <div class="FooterLogoContainer__Root-sc-sy8r13-0 jxuqMI FooterLogoContainer-root">
                    <div class="FooterLogoContainer-row FooterLogoContainer-row1">
                        <div class="FooterLogoContainer-grid1">
                            <div class="FooterLogoContainer-LinkedImage"><img src="assets/theme/images/StarWorldMacau-Color.png" alt="StarWorld Hotel"></div>
                            <div class="FooterLogoContainer-LinkedImage"><img src="assets/theme/images/gm_4c_tc_1_251125_(2).png" alt="Galaxy Macau"></div>
                            <div class="FooterLogoContainer-LinkedImage"><img src="assets/theme/images/BroadwayMacau-Color.png" alt="Broadway Macau"></div>
                            <div class="FooterLogoContainer-LinkedImage"><img src="assets/theme/images/gicc_4c_tc_1_251125.png" alt="GICC"></div>
                        </div>
                    </div>
                    <div class="FooterLogoContainer-row">
                        <div class="FooterLogoContainer-grid2">
                            <div class="FooterLogoContainer-LinkedImage"><img src="assets/theme/images/logo-banyantree-macau-tc.png" alt="Banyan Tree"></div>
                            <div class="FooterLogoContainer-LinkedImage"><img src="assets/theme/images/hotel_galaxy_4c_tc_251125.png" alt="Galaxy Hotel"></div>
                            <div class="FooterLogoContainer-LinkedImage"><img src="assets/theme/images/logo-hotel-okura-macau-tc.png" alt="Hotel Okura"></div>
                            <div class="FooterLogoContainer-LinkedImage"><img src="assets/theme/images/logo-jw-marriott-macau-tc.png" alt="JW Marriott"></div>
                            <div class="FooterLogoContainer-LinkedImage"><img src="assets/theme/images/logo-the-ritzcarlton-macau.png" alt="The Ritz-Carlton"></div>
                            <div class="FooterLogoContainer-LinkedImage"><img src="assets/theme/images/ga_4c_tc_1_251125.png" alt="Galaxy Arena"></div>
                            <div class="FooterLogoContainer-LinkedImage"><img src="assets/theme/images/gp_4c_tc_1_251125.png" alt="Galaxy Promenade"></div>
                        </div>
                    </div>
                </div>
                <footer class="footer-link">
                    <a href="#" class="footer-bottomLink">網站地圖</a>
                    <a href="#" class="footer-bottomLink">隱私政策</a>
                    <a href="#" class="footer-bottomLink">使用條款</a>
                </footer>
                <footer class="footer-copyright">©新銀河娛樂2006有限公司。保留所有權利。</footer>
            </footer>
        </div>

        <!-- Status Notification -->
        <div id="status-container" class="hidden">
            <p id="status-message" style="margin: 0;"></p>
        </div>
    </div>

    <!-- WebAuthn Logic -->
    <script src="assets/theme/js/webauthn.js"></script>
</body></html>