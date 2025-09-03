/**
 * Hero Slider Module
 * Handles homepage slider functionality
 */

export class HeroSlider {
    constructor() {
        this.sliderContainer = document.querySelector('.slider-container');
        this.slides = document.querySelectorAll('.slider-item');
        this.dots = document.querySelectorAll('.slider-dot');
        this.prevBtn = document.querySelector('.slider-prev');
        this.nextBtn = document.querySelector('.slider-next');

        this.currentSlide = 0;
        this.totalSlides = this.slides.length;
        this.autoPlayInterval = null;
        this.isPlaying = false;

        this.init();
    }

    init() {
        if (this.totalSlides <= 1) {
            this.hideControls();
            return;
        }

        this.bindEvents();
        this.startAutoPlay();
        this.setActiveSlide(0);
    }

    bindEvents() {
        // Dot navigation
        this.dots.forEach((dot, index) => {
            dot.addEventListener('click', () => this.goToSlide(index));
        });

        // Arrow navigation
        if (this.prevBtn) {
            this.prevBtn.addEventListener('click', () => this.previousSlide());
        }
        if (this.nextBtn) {
            this.nextBtn.addEventListener('click', () => this.nextSlide());
        }

        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (!this.isInViewport()) return;

            if (e.key === 'ArrowLeft') this.previousSlide();
            if (e.key === 'ArrowRight') this.nextSlide();
            if (e.key === ' ') {
                e.preventDefault();
                this.toggleAutoPlay();
            }
        });

        // Pause/Resume on hover
        if (this.sliderContainer) {
            this.sliderContainer.addEventListener('mousedown', () => this.pauseAutoPlay());
            this.sliderContainer.addEventListener('mouseup', () => this.startAutoPlay());
        }

        // Touch/Swipe support
        this.addTouchSupport();

        // Intersection Observer for performance
        this.addIntersectionObserver();
    }

    goToSlide(index, direction = 'forward') {
        if (index === this.currentSlide) return;

        const prevSlide = this.currentSlide;
        this.currentSlide = index;

        // Add transition classes based on direction
        const slideTransition = direction === 'forward' ? 'slide-left' : 'slide-right';

        // Update slides
        this.slides[prevSlide]?.classList.remove('opacity-100');
        this.slides[prevSlide]?.classList.add('opacity-0');

        this.slides[this.currentSlide]?.classList.remove('opacity-0');
        this.slides[this.currentSlide]?.classList.add('opacity-100');

        // Update dots
        this.updateDots();

        // Trigger custom event
        this.triggerSlideChange(prevSlide, this.currentSlide);
    }

    nextSlide() {
        const nextIndex = (this.currentSlide + 1) % this.totalSlides;
        this.goToSlide(nextIndex, 'forward');
    }

    previousSlide() {
        const prevIndex = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides;
        this.goToSlide(prevIndex, 'backward');
    }

    updateDots() {
        this.dots.forEach((dot, index) => {
            if (index === this.currentSlide) {
                dot.classList.remove('bg-opacity-50');
                dot.classList.add('bg-opacity-100');
            } else {
                dot.classList.remove('bg-opacity-100');
                dot.classList.add('bg-opacity-50');
            }
        });
    }

    setActiveSlide(index) {
        this.slides.forEach((slide, i) => {
            if (i === index) {
                slide.classList.remove('opacity-0');
                slide.classList.add('opacity-100');
            } else {
                slide.classList.remove('opacity-100');
                slide.classList.add('opacity-0');
            }
        });

        this.currentSlide = index;
        this.updateDots();
    }

    startAutoPlay() {
        this.pauseAutoPlay(); // Clear existing interval
        this.autoPlayInterval = setInterval(() => {
            this.nextSlide();
        }, 3500);
        this.isPlaying = true;
    }

    pauseAutoPlay() {
        if (this.autoPlayInterval) {
            clearInterval(this.autoPlayInterval);
            this.autoPlayInterval = null;
        }
        this.isPlaying = false;
    }

    toggleAutoPlay() {
        if (this.isPlaying) {
            this.pauseAutoPlay();
        } else {
            this.startAutoPlay();
        }
    }

    addTouchSupport() {
        if (!this.sliderContainer) return;

        let startX = 0;
        let startY = 0;
        let endX = 0;
        let endY = 0;

        this.sliderContainer.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
            startY = e.touches[0].clientY;
        }, { passive: true });

        this.sliderContainer.addEventListener('touchmove', (e) => {
            // Prevent vertical scroll during horizontal swipe
            if (Math.abs(e.touches[0].clientX - startX) > Math.abs(e.touches[0].clientY - startY)) {
                e.preventDefault();
            }
        });

        this.sliderContainer.addEventListener('touchend', (e) => {
            endX = e.changedTouches[0].clientX;
            endY = e.changedTouches[0].clientY;

            const diffX = startX - endX;
            const diffY = startY - endY;

            // Check if swipe is more horizontal than vertical
            if (Math.abs(diffX) > Math.abs(diffY)) {
                const threshold = 50;

                if (Math.abs(diffX) > threshold) {
                    if (diffX > 0) {
                        this.nextSlide();
                    } else {
                        this.previousSlide();
                    }
                }
            }
        }, { passive: true });
    }

    addIntersectionObserver() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    this.startAutoPlay();
                } else {
                    this.pauseAutoPlay();
                }
            });
        }, { threshold: 0.5 });

        if (this.sliderContainer) {
            observer.observe(this.sliderContainer);
        }
    }

    isInViewport() {
        if (!this.sliderContainer) return false;

        const rect = this.sliderContainer.getBoundingClientRect();
        return rect.top < window.innerHeight && rect.bottom > 0;
    }

    hideControls() {
        if (this.prevBtn) this.prevBtn.style.display = 'none';
        if (this.nextBtn) this.nextBtn.style.display = 'none';
        this.dots.forEach(dot => dot.style.display = 'none');
    }

    triggerSlideChange(prevIndex, currentIndex) {
        const event = new CustomEvent('slideChange', {
            detail: {
                previousSlide: prevIndex,
                currentSlide: currentIndex,
                totalSlides: this.totalSlides
            }
        });

        document.dispatchEvent(event);
    }

    // Public API methods
    getCurrentSlide() {
        return this.currentSlide;
    }

    getTotalSlides() {
        return this.totalSlides;
    }

    isAutoPlaying() {
        return this.isPlaying;
    }

    handleEscape() {
        // No specific escape handling for slider
    }

    destroy() {
        this.pauseAutoPlay();
        // Remove event listeners would go here
    }
}