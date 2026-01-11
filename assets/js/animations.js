document.addEventListener('DOMContentLoaded', function() {
    /**
     * Scroll Animation Observer
     */
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1
    };

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('scroll-view');
                // Optional: stop observing after animation triggered
                // observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Elements to observe
    const animatedElements = document.querySelectorAll('.scroll-fade, .scroll-fade-up, .scroll-fade-down, .scroll-fade-left, .scroll-fade-right, .stagger-container');
    animatedElements.forEach(el => observer.observe(el));

    /**
     * Header Scroll Effect (Already in header.php, but moved here for centralization if needed)
     */
    const header = document.querySelector('.header');
    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });
});
