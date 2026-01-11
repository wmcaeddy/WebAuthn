<header class="header clearfix">
  <h1 class="logo">
    <a href="index.php" title="中飛科技股份有限公司">
        <!-- Logo Placeholder: Replace with actual image if available -->
        <span style="display: flex; align-items: center; gap: 10px;">
            <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="40" height="40" rx="4" fill="#86a5b7"/>
                <path d="M10 20L20 10L30 20L20 30L10 20Z" fill="white"/>
            </svg>
            <span style="font-size: 1.2rem; letter-spacing: 1px;">FAIRLINE</span>
        </span>
    </a>
  </h1>
  <div class="menu">
    <ul class="reset">
      <li>
        <a href="#" title="About Us">
          <p><span>關於我們</span></p>
        </a>
      </li>
      <li>
        <a href="#" title="News">
          <p><span>消息與報導</span></p>
        </a>
      </li>
      <li>
        <a href="#" title="Solutions">
          <p><span>解決方案</span></p>
        </a>
      </li>
      <li>
        <a href="#" title="Contract">
          <p><span>共契專區</span></p>
        </a>
      </li>
      <li>
        <a href="#" title="Download">
          <p><span>下載專區</span></p>
        </a>
      </li>
      <li class="contact">
        <a href="#" title="Contact">
          <i class="fa fa-envelope" aria-hidden="true" style="margin-right: 8px;"></i>
          <p><span>聯絡我們</span></p>
        </a>
      </li>
    </ul>
  </div>
  <a class="switch" href="javascript:void(0);" onclick="document.querySelector('.menu').classList.toggle('active');">
    <i class="fa fa-bars fa-2x"></i>
  </a>
</header>
<script>
    // Header Scroll Effect
    window.addEventListener('scroll', function() {
        const header = document.querySelector('.header');
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });
</script>
