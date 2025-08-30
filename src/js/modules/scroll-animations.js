/**
 * Scroll Animations Module
 * Handles scroll-triggered animations and effects
 */

export class ScrollAnimations {
    constructor() {
        this.observer = null;
        this.scrollToTopBtn = document.getElementById('scroll-to-top');
        this.animatedElements = new Set();

        this.init();
    }

    init() {
        this.setupIntersectionObserver();
        this.observeElements();
        this.setupScrollToTop();
        this.setupParallaxEffects();
    }

    setupIntersectionObserver() {
        const options = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        this.observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !this.animatedElements.has(entry.target)) {
                    this.animateElement(entry.target);
                    this.animatedElements.add(entry.target);
                }
            });
        }, options);
    }

    observeElements() {
        // Find elements with animation classes
        const animateOnScrollElements = document.querySelectorAll('.animate-on-scroll');
        animateOnScrollElements.forEach(el => {
            this.prepareElement(el);
            this.observer.observe(el);
        });

        // Auto-detect elements that should animate
        const autoAnimateSelectors = [
            '.candidate-card',
            '.hall-card',
            '.manifesto-card',
            '.content-section',
            '.stats-card',
            '.feature-card'
        ];

        autoAnimateSelectors.forEach(selector => {
            const elements = document.querySelectorAll(selector);
            elements.forEach((el, index) => {
                if (!el.classList.contains('animate-on-scroll')) {
                    el.classList.add('animate-on-scroll');
                    el.style.animationDelay = `${index * 0.1}s`;
                    this.prepareElement(el);
                    this.observer.observe(el);
                }
            });
        });
    }

    prepareElement(element) {
        element.style.opacity = '0';
        element.style.transform = 'translateY(30px)';
        element.style.transition = 'all 0.7s ease-out';
    }

    animateElement(element) {
        // Determine animation type based on element classes or data attributes
        const animationType = element.dataset.animation || 'fadeInUp';

        switch (animationType) {
            case 'fadeInUp':
                this.fadeInUp(element);
                break;
            case 'fadeInLeft':
                this.fadeInLeft(element);
                break;
            case 'fadeInRight':
                this.fadeInRight(element);
                break;
            case 'scaleIn':
                this.scaleIn(element);
                break;
            case 'slideInUp':
                this.slideInUp(element);
                break;
            default:
                this.fadeInUp(element);
        }

        // Add animated class for CSS-based animations
        element.classList.add('animate-fade-in-up');
    }

    fadeInUp(element) {
        element.style.opacity = '1';
        element.style.transform = 'translateY(0)';
    }

    fadeInLeft(element) {
        element.style.opacity = '1';
        element.style.transform = 'translateX(0)';
    }

    fadeInRight(element) {
        element.style.opacity = '1';
        element.style.transform = 'translateX(0)';
    }

    scaleIn(element) {
        element.style.opacity = '1';
        element.style.transform = 'scale(1)';
    }

    slideInUp(element) {
        element.style.opacity = '1';
        element.style.transform = 'translateY(0)';
    }

    setupScrollToTop() {
        if (!this.scrollToTopBtn) return;

        // Show/hide scroll to top button
        window.addEventListener('scroll', this.throttle(() => {
            this.toggleScrollToTop();
        }, 100));

        // Click handler
        this.scrollToTopBtn.addEventListener('click', () => {
            this.scrollToTop();
        });
    }

    toggleScrollToTop() {
        if (!this.scrollToTopBtn) return;

        const scrollY = window.scrollY;
        const threshold = 300;

        if (scrollY > threshold) {
            this.scrollToTopBtn.classList.remove('opacity-0', 'translate-y-4', 'pointer-events-none');
            this.scrollToTopBtn.classList.add('opacity-100', 'translate-y-0', 'pointer-events-auto');
        } else {
            this.scrollToTopBtn.classList.add('opacity-0', 'translate-y-4', 'pointer-events-none');
            this.scrollToTopBtn.classList.remove('opacity-100', 'translate-y-0', 'pointer-events-auto');
        }
    }

    scrollToTop() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }

    setupParallaxEffects() {
        const parallaxElements = document.querySelectorAll('.parallax');

        if (parallaxElements.length === 0) return;

        window.addEventListener('scroll', this.throttle(() => {
            parallaxElements.forEach(element => {
                this.applyParallaxEffect(element);
            });
        }, 16)); // ~60fps
    }

    applyParallaxEffect(element) {
        const scrolled = window.pageYOffset;
        const rect = element.getBoundingClientRect();
        const speed = element.dataset.speed || 0.5;

        // Only apply parallax if element is in viewport
        if (rect.bottom >= 0 && rect.top <= window.innerHeight) {
            const yPos = -(scrolled * speed);
            element.style.transform = `translateY(${yPos}px)`;
        }
    }

    // Utility function for throttling
    throttle(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }

    // Public API methods
    animateElementManually(element, animation = 'fadeInUp') {
        element.dataset.animation = animation;
        this.animateElement(element);
    }

    resetElement(element) {
        this.animatedElements.delete(element);
        this.prepareElement(element);
    }

    observeNewElement(element) {
        if (this.observer) {
            this.prepareElement(element);
            this.observer.observe(element);
        }
    }

    unobserveElement(element) {
        if (this.observer) {
            this.observer.unobserve(element);
        }
    }

    handleEscape() {
        // No specific escape handling for scroll animations
    }

    destroy() {
        if (this.observer) {
            this.observer.disconnect();
            this.observer = null;
        }

        this.animatedElements.clear();

        // Remove event listeners would go here in a full implementation
    }
}