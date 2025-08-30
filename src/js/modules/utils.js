/**
 * Utility Functions Module
 * Common utility functions used across the application
 */

export class Utils {
    constructor() {
        this.init();
    }

    init() {
        // Initialize utility functions that need setup
        this.setupGlobalHelpers();
        this.setupPerformanceOptimizations();
    }

    setupGlobalHelpers() {
        // Make some utility functions globally available if needed
        window.ducsuUtils = {
            debounce: this.debounce,
            throttle: this.throttle,
            formatNumber: this.formatNumber,
            formatDate: this.formatDate,
            convertToBengali: this.convertToBengali
        };
    }

    setupPerformanceOptimizations() {
        // Lazy load images
        this.lazyLoadImages();

        // Optimize animations for reduced motion
        this.respectReducedMotion();

        // Add error boundary for JavaScript errors
        this.setupErrorHandling();
    }

    /**
     * Debounce function to limit function calls
     */
    debounce(func, wait, immediate = false) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                timeout = null;
                if (!immediate) func(...args);
            };
            const callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func(...args);
        };
    }

    /**
     * Throttle function to limit function calls
     */
    throttle(func, limit) {
        let inThrottle;
        return function(...args) {
            if (!inThrottle) {
                func.apply(this, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }

    /**
     * Format numbers for display
     */
    formatNumber(num, options = {}) {
        const defaults = {
            locale: 'bn-BD',
            minimumFractionDigits: 0,
            maximumFractionDigits: 2
        };

        const config = { ...defaults, ...options };

        try {
            return new Intl.NumberFormat(config.locale, config).format(num);
        } catch (error) {
            console.warn('Number formatting failed:', error);
            return num.toString();
        }
    }

    /**
     * Format dates for display
     */
    formatDate(date, options = {}) {
        const defaults = {
            locale: 'bn-BD',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };

        const config = { ...defaults, ...options };

        try {
            const dateObj = typeof date === 'string' ? new Date(date) : date;
            return new Intl.DateTimeFormat(config.locale, config).format(dateObj);
        } catch (error) {
            console.warn('Date formatting failed:', error);
            return date.toString();
        }
    }

    /**
     * Convert English numbers to Bengali
     */
    convertToBengali(text) {
        const englishDigits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        const bengaliDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];

        let result = text.toString();
        englishDigits.forEach((digit, index) => {
            result = result.replace(new RegExp(digit, 'g'), bengaliDigits[index]);
        });

        return result;
    }

    /**
     * Convert Bengali numbers to English
     */
    convertToEnglish(text) {
        const bengaliDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        const englishDigits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

        let result = text.toString();
        bengaliDigits.forEach((digit, index) => {
            result = result.replace(new RegExp(digit, 'g'), englishDigits[index]);
        });

        return result;
    }

    /**
     * Sanitize HTML content
     */
    sanitizeHTML(str) {
        const temp = document.createElement('div');
        temp.textContent = str;
        return temp.innerHTML;
    }

    /**
     * Create element with attributes
     */
    createElement(tag, attributes = {}, content = '') {
        const element = document.createElement(tag);

        Object.keys(attributes).forEach(key => {
            if (key === 'className') {
                element.className = attributes[key];
            } else if (key === 'innerHTML') {
                element.innerHTML = attributes[key];
            } else {
                element.setAttribute(key, attributes[key]);
            }
        });

        if (content) {
            element.textContent = content;
        }

        return element;
    }

    /**
     * Get element position relative to viewport
     */
    getElementPosition(element) {
        const rect = element.getBoundingClientRect();
        return {
            top: rect.top,
            left: rect.left,
            right: rect.right,
            bottom: rect.bottom,
            width: rect.width,
            height: rect.height,
            centerX: rect.left + rect.width / 2,
            centerY: rect.top + rect.height / 2
        };
    }

    /**
     * Check if element is in viewport
     */
    isInViewport(element, threshold = 0) {
        const rect = element.getBoundingClientRect();
        const windowHeight = window.innerHeight || document.documentElement.clientHeight;
        const windowWidth = window.innerWidth || document.documentElement.clientWidth;

        return (
            rect.top >= -threshold &&
            rect.left >= -threshold &&
            rect.bottom <= windowHeight + threshold &&
            rect.right <= windowWidth + threshold
        );
    }

    /**
     * Smooth scroll to element
     */
    scrollToElement(element, options = {}) {
        const defaults = {
            behavior: 'smooth',
            block: 'start',
            inline: 'nearest'
        };

        const config = { ...defaults, ...options };

        if (element && typeof element.scrollIntoView === 'function') {
            element.scrollIntoView(config);
        }
    }

    /**
     * Copy text to clipboard
     */
    async copyToClipboard(text) {
        try {
            if (navigator.clipboard) {
                await navigator.clipboard.writeText(text);
                return true;
            } else {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = text;
                textArea.style.position = 'fixed';
                textArea.style.opacity = '0';
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();
                const success = document.execCommand('copy');
                document.body.removeChild(textArea);
                return success;
            }
        } catch (error) {
            console.error('Failed to copy to clipboard:', error);
            return false;
        }
    }

    /**
     * Get URL parameters
     */
    getUrlParams() {
        const params = new URLSearchParams(window.location.search);
        const result = {};

        for (let [key, value] of params) {
            result[key] = value;
        }

        return result;
    }

    /**
     * Update URL without page reload
     */
    updateUrl(params, title = null) {
        const url = new URL(window.location);

        Object.keys(params).forEach(key => {
            if (params[key] === null || params[key] === undefined) {
                url.searchParams.delete(key);
            } else {
                url.searchParams.set(key, params[key]);
            }
        });

        window.history.pushState({}, title || document.title, url);
    }

    /**
     * Local storage helpers with error handling
     */
    storage = {
        set: (key, value) => {
            try {
                localStorage.setItem(key, JSON.stringify(value));
                return true;
            } catch (error) {
                console.warn('Failed to set localStorage:', error);
                return false;
            }
        },

        get: (key, defaultValue = null) => {
            try {
                const item = localStorage.getItem(key);
                return item ? JSON.parse(item) : defaultValue;
            } catch (error) {
                console.warn('Failed to get localStorage:', error);
                return defaultValue;
            }
        },

        remove: (key) => {
            try {
                localStorage.removeItem(key);
                return true;
            } catch (error) {
                console.warn('Failed to remove localStorage:', error);
                return false;
            }
        },

        clear: () => {
            try {
                localStorage.clear();
                return true;
            } catch (error) {
                console.warn('Failed to clear localStorage:', error);
                return false;
            }
        }
    };

    /**
     * Device and browser detection
     */
    device = {
        isMobile: () => /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent),
        isTablet: () => /(tablet|ipad|playbook|silk)|(android(?!.*mobi))/i.test(navigator.userAgent),
        isDesktop: () => !this.device.isMobile() && !this.device.isTablet(),
        hasTouch: () => 'ontouchstart' in window || navigator.maxTouchPoints > 0,
        getBrowser: () => {
            const { userAgent } = navigator;
            if (userAgent.includes('Chrome')) return 'Chrome';
            if (userAgent.includes('Firefox')) return 'Firefox';
            if (userAgent.includes('Safari') && !userAgent.includes('Chrome')) return 'Safari';
            if (userAgent.includes('Edge')) return 'Edge';
            return 'Unknown';
        }
    };

    /**
     * Performance measurement
     */
    performance = {
        mark: (name) => {
            if (performance && performance.mark) {
                performance.mark(name);
            }
        },

        measure: (name, startMark, endMark) => {
            if (performance && performance.measure) {
                try {
                    performance.measure(name, startMark, endMark);
                    const entry = performance.getEntriesByName(name)[0];
                    return entry ? entry.duration : null;
                } catch (error) {
                    console.warn('Performance measurement failed:', error);
                    return null;
                }
            }
            return null;
        }
    };

    /**
     * Lazy load images
     */
    lazyLoadImages() {
        if (!('IntersectionObserver' in window)) return;

        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                }
            });
        });

        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }

    /**
     * Respect user's reduced motion preference
     */
    respectReducedMotion() {
        const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        if (prefersReducedMotion) {
            document.documentElement.classList.add('reduced-motion');

            // Add CSS to disable animations
            const style = document.createElement('style');
            style.innerHTML = `
                .reduced-motion * {
                    animation-duration: 0.01ms !important;
                    animation-iteration-count: 1 !important;
                    transition-duration: 0.01ms !important;
                }
            `;
            document.head.appendChild(style);
        }
    }

    /**
     * Setup global error handling
     */
    setupErrorHandling() {
        window.addEventListener('error', (event) => {
            console.error('JavaScript error:', event.error);

            // You could send errors to analytics service here
            this.logError('javascript_error', {
                message: event.message,
                filename: event.filename,
                lineno: event.lineno,
                colno: event.colno,
                stack: event.error?.stack
            });
        });

        window.addEventListener('unhandledrejection', (event) => {
            console.error('Unhandled promise rejection:', event.reason);

            this.logError('promise_rejection', {
                reason: event.reason?.toString(),
                stack: event.reason?.stack
            });
        });
    }

    /**
     * Log errors for debugging
     */
    logError(type, details) {
        const errorLog = {
            type,
            details,
            timestamp: Date.now(),
            url: window.location.href,
            userAgent: navigator.userAgent
        };

        // Store in localStorage for debugging
        const errors = this.storage.get('ducsu_error_log', []);
        errors.push(errorLog);

        // Keep only last 50 errors
        if (errors.length > 50) {
            errors.splice(0, errors.length - 50);
        }

        this.storage.set('ducsu_error_log', errors);
    }

    /**
     * Clear error log
     */
    clearErrorLog() {
        this.storage.remove('ducsu_error_log');
    }

    /**
     * Get error log
     */
    getErrorLog() {
        return this.storage.get('ducsu_error_log', []);
    }

    handleEscape() {
        // No specific escape handling for utils
    }

    destroy() {
        // Cleanup utility functions
        if (window.ducsuUtils) {
            delete window.ducsuUtils;
        }
    }
}