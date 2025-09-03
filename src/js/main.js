/**
 * Main JavaScript Entry Point for DUCSU JCD Theme
 * This file imports and initializes all feature modules
 */

import '../css/input.css'

// Import all modules
import { HeroSlider } from './modules/hero-slider.js';
import { SearchHandler } from './modules/search-handler.js';
import { CandidateModal } from './modules/candidate-modal.js';
import { ContactForms } from './modules/contact-forms.js';
//import { Utils } from './modules/utils.js';

/**
 * Main application class
 */
class DucsuApp {
    constructor() {
        this.modules = {};
        this.init();
    }

    /**
     * Initialize all modules
     */
    init() {
        // Wait for DOM to be ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.initModules());
        } else {
            this.initModules();
        }
    }

    /**
     * Initialize individual modules
     */
    initModules() {
        try {
            // Initialize modules in order of dependency
            // this.modules.utils = new Utils();
            this.modules.heroSlider = new HeroSlider();
            this.modules.searchHandler = new SearchHandler();
            this.modules.candidateModal = new CandidateModal();
            //this.modules.scrollAnimations = new ScrollAnimations();
            this.modules.contactForms = new ContactForms();

            // Initialize global features
            this.initGlobalFeatures();

            console.log('DUCSU App initialized successfully');
        } catch (error) {
            console.error('Error initializing DUCSU App:', error);
        }
    }

    /**
     * Initialize global features
     */
    initGlobalFeatures() {
        // Smooth scrolling for anchor links
        this.initSmoothScrolling();

        // Add custom CSS animations
        this.addCustomStyles();

        // Initialize keyboard navigation
        this.initKeyboardNavigation();
    }

    /**
     * Initialize smooth scrolling
     */
    initSmoothScrolling() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    }

    /**
     * Add custom CSS animations
     */
    addCustomStyles() {
        if (document.getElementById('ducsu-custom-styles')) return;

        const style = document.createElement('style');
        style.id = 'ducsu-custom-styles';
        style.textContent = `
            .animate-fade-in-up {
                animation: fadeInUp 0.7s ease-out forwards;
            }
            
            .animation-delay-200 {
                animation-delay: 0.2s;
            }
            
            .animation-delay-400 {
                animation-delay: 0.4s;
            }
            
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            .line-clamp-2 {
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
            
            .transition-all {
                transition: all 0.3s ease;
            }
            
            .hover-scale:hover {
                transform: scale(1.05);
            }
            
            .loading-spinner {
                animation: spin 1s linear infinite;
            }
            
            @keyframes spin {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
        `;
        document.head.appendChild(style);
    }

    /**
     * Initialize keyboard navigation
     */
    initKeyboardNavigation() {
        document.addEventListener('keydown', (e) => {
            // Global keyboard shortcuts
            switch (e.key) {
                case 'Escape':
                    this.handleEscapeKey();
                    break;
                case '/':
                    if (e.ctrlKey || e.metaKey) {
                        e.preventDefault();
                        this.modules.searchHandler?.openSearch();
                    }
                    break;
            }
        });
    }

    /**
     * Handle escape key press
     */
    handleEscapeKey() {
        // Close any open modals or overlays
        Object.values(this.modules).forEach(module => {
            if (typeof module.handleEscape === 'function') {
                module.handleEscape();
            }
        });
    }

    /**
     * Get module instance
     */
    getModule(name) {
        return this.modules[name] || null;
    }

    /**
     * Destroy all modules (cleanup)
     */
    destroy() {
        Object.values(this.modules).forEach(module => {
            if (typeof module.destroy === 'function') {
                module.destroy();
            }
        });
        this.modules = {};
    }
}

// Initialize the application
window.DucsuApp = new DucsuApp();

// Make app globally available for debugging
if (window.location.hostname === 'localhost' || window.location.hostname.includes('dev')) {
    window.ducsuDebug = window.DucsuApp;
}