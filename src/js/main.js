/**
 * Main JavaScript file for DUCSU JCD Theme
 * Handles slider, modals, AJAX, and interactive elements
 */

document.addEventListener('DOMContentLoaded', function() {

    // ===========================
    // HERO SLIDER FUNCTIONALITY
    // ===========================
    const heroSlider = {
        init() {
            this.sliderContainer = document.querySelector('.slider-container');
            this.slides = document.querySelectorAll('.slider-item');
            this.dots = document.querySelectorAll('.slider-dot');
            this.prevBtn = document.querySelector('.slider-prev');
            this.nextBtn = document.querySelector('.slider-next');

            if (this.slides.length <= 1) return;

            this.currentSlide = 0;
            this.totalSlides = this.slides.length;

            this.bindEvents();
            this.startAutoPlay();
        },

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
                if (e.key === 'ArrowLeft') this.previousSlide();
                if (e.key === 'ArrowRight') this.nextSlide();
            });

            // Pause autoplay on hover
            if (this.sliderContainer) {
                this.sliderContainer.addEventListener('mouseenter', () => this.pauseAutoPlay());
                this.sliderContainer.addEventListener('mouseleave', () => this.startAutoPlay());
            }
        },

        goToSlide(index) {
            // Remove active class from current slide
            this.slides[this.currentSlide].classList.remove('opacity-100');
            this.slides[this.currentSlide].classList.add('opacity-0');
            this.dots[this.currentSlide].classList.remove('bg-opacity-100');
            this.dots[this.currentSlide].classList.add('bg-opacity-50');

            // Set new current slide
            this.currentSlide = index;

            // Add active class to new slide
            this.slides[this.currentSlide].classList.remove('opacity-0');
            this.slides[this.currentSlide].classList.add('opacity-100');
            this.dots[this.currentSlide].classList.remove('bg-opacity-50');
            this.dots[this.currentSlide].classList.add('bg-opacity-100');
        },

        nextSlide() {
            const nextIndex = (this.currentSlide + 1) % this.totalSlides;
            this.goToSlide(nextIndex);
        },

        previousSlide() {
            const prevIndex = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides;
            this.goToSlide(prevIndex);
        },

        startAutoPlay() {
            this.pauseAutoPlay(); // Clear existing interval
            this.autoPlayInterval = setInterval(() => {
                this.nextSlide();
            }, 5000);
        },

        pauseAutoPlay() {
            if (this.autoPlayInterval) {
                clearInterval(this.autoPlayInterval);
            }
        }
    };

    // ===========================
    // SEARCH FUNCTIONALITY
    // ===========================
    const searchHandler = {
        init() {
            this.searchToggle = document.querySelector('.search-toggle');
            this.searchInput = document.querySelector('#search-input');
            this.searchResults = document.querySelector('#search-results');
            this.searchOverlay = document.querySelector('#search-overlay');

            this.bindEvents();
        },

        bindEvents() {
            // Search toggle
            if (this.searchToggle) {
                this.searchToggle.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.toggleSearch();
                });
            }

            // Search input
            if (this.searchInput) {
                this.searchInput.addEventListener('input', this.debounce((e) => {
                    this.performSearch(e.target.value);
                }, 300));

                this.searchInput.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape') {
                        this.closeSearch();
                    }
                });
            }

            // Close search overlay
            if (this.searchOverlay) {
                this.searchOverlay.addEventListener('click', (e) => {
                    if (e.target === this.searchOverlay) {
                        this.closeSearch();
                    }
                });
            }

            // Close search on escape
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    this.closeSearch();
                }
            });
        },

        toggleSearch() {
            if (this.searchOverlay) {
                this.searchOverlay.classList.toggle('hidden');
                if (!this.searchOverlay.classList.contains('hidden')) {
                    this.searchInput.focus();
                }
            }
        },

        closeSearch() {
            if (this.searchOverlay) {
                this.searchOverlay.classList.add('hidden');
                this.searchInput.value = '';
                if (this.searchResults) {
                    this.searchResults.innerHTML = '';
                }
            }
        },

        async performSearch(query) {
            if (!query || query.length < 2) {
                if (this.searchResults) {
                    this.searchResults.innerHTML = '';
                }
                return;
            }

            try {
                const formData = new FormData();
                formData.append('action', 'ducsu_search');
                formData.append('query', query);
                formData.append('nonce', ducsu_ajax.nonce);

                const response = await fetch(ducsu_ajax.ajax_url, {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    this.displaySearchResults(data.data);
                } else {
                    this.displayNoResults();
                }
            } catch (error) {
                console.error('Search error:', error);
                this.displayError();
            }
        },

        displaySearchResults(results) {
            if (!this.searchResults) return;

            if (results.length === 0) {
                this.displayNoResults();
                return;
            }

            let html = '<div class="space-y-2">';
            results.forEach(result => {
                html += `
                    <a href="${result.link}" class="block p-4 bg-white rounded-lg shadow hover:shadow-md transition-shadow duration-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-medium text-gray-800">${result.title}</h3>
                                <p class="text-sm text-gray-600">${result.type}</p>
                                ${result.excerpt ? `<p class="text-sm text-gray-500 mt-1">${result.excerpt}</p>` : ''}
                            </div>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </a>
                `;
            });
            html += '</div>';

            this.searchResults.innerHTML = html;
        },

        displayNoResults() {
            if (!this.searchResults) return;

            this.searchResults.innerHTML = `
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <p class="text-gray-600">কোনো ফলাফল পাওয়া যায়নি</p>
                </div>
            `;
        },

        displayError() {
            if (!this.searchResults) return;

            this.searchResults.innerHTML = `
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-red-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <p class="text-red-600">অনুসন্ধানে সমস্যা হয়েছে</p>
                </div>
            `;
        },

        debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }
    };

    // ===========================
    // CANDIDATE MODAL FUNCTIONALITY
    // ===========================
    const candidateModal = {
        init() {
            this.modal = document.querySelector('#candidate-modal');
            this.modalContent = document.querySelector('.candidate-modal-content');
            this.closeBtn = document.querySelector('#close-modal');
            this.loadingSpinner = document.querySelector('#loading-spinner');

            this.bindEvents();
        },

        bindEvents() {
            // Candidate card clicks
            document.addEventListener('click', (e) => {
                if (e.target.closest('.candidate-card') || e.target.closest('.candidate-details-btn')) {
                    e.preventDefault();
                    const candidateId = e.target.closest('[data-candidate-id]').getAttribute('data-candidate-id');
                    this.openModal(candidateId);
                }
            });

            // Contact candidate button
            document.addEventListener('click', (e) => {
                if (e.target.closest('.contact-candidate-btn')) {
                    e.preventDefault();
                    const candidateId = e.target.closest('[data-candidate-id]').getAttribute('data-candidate-id');
                    this.openModal(candidateId, true);
                }
            });

            // Close modal
            if (this.closeBtn) {
                this.closeBtn.addEventListener('click', () => this.closeModal());
            }

            // Close on overlay click
            if (this.modal) {
                this.modal.addEventListener('click', (e) => {
                    if (e.target === this.modal) {
                        this.closeModal();
                    }
                });
            }

            // Close on escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && !this.modal.classList.contains('hidden')) {
                    this.closeModal();
                }
            });
        },

        showLoading() {
            if (this.loadingSpinner) {
                this.loadingSpinner.classList.remove('hidden');
            }
        },

        hideLoading() {
            if (this.loadingSpinner) {
                this.loadingSpinner.classList.add('hidden');
            }
        },

        async openModal(candidateId, showContactForm = false) {
            this.showLoading();

            try {
                const formData = new FormData();
                formData.append('action', 'get_candidate_details');
                formData.append('candidate_id', candidateId);
                formData.append('nonce', ducsu_ajax.nonce);

                const response = await fetch(ducsu_ajax.ajax_url, {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    this.displayCandidateDetails(data.data, showContactForm);
                    if (this.modal) {
                        this.modal.classList.remove('hidden');
                    }
                } else {
                    this.showError('প্রার্থীর তথ্য লোড করতে সমস্যা হয়েছে');
                }
            } catch (error) {
                console.error('Error loading candidate details:', error);
                this.showError('প্রার্থীর তথ্য লোড করতে সমস্যা হয়েছে');
            } finally {
                this.hideLoading();
            }
        },

        displayCandidateDetails(candidate, showContactForm = false) {
            if (!this.modalContent) return;

            let galleryHtml = '';
            if (candidate.gallery && candidate.gallery.length > 0) {
                galleryHtml = `
                    <div class="mb-8">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">ছবি গ্যালারি</h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            ${candidate.gallery.map(image => `
                                <img src="${image.url}" alt="Gallery Image" 
                                     class="w-full h-32 object-cover rounded-lg cursor-pointer hover:opacity-80 transition-opacity"
                                     onclick="window.open('${image.full_url}', '_blank')">
                            `).join('')}
                        </div>
                    </div>
                `;
            }

            const contactFormHtml = candidate.email ? `
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">যোগাযোগ করুন</h3>
                    <form id="candidate-contact-form" class="space-y-4">
                        <input type="hidden" name="candidate_id" value="${candidate.id}">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="contact-name" class="block text-sm font-medium text-gray-700 mb-1">নাম *</label>
                                <input type="text" id="contact-name" name="name" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-green focus:border-transparent">
                            </div>
                            <div>
                                <label for="contact-email" class="block text-sm font-medium text-gray-700 mb-1">ইমেইল *</label>
                                <input type="email" id="contact-email" name="email" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-green focus:border-transparent">
                            </div>
                        </div>
                        
                        <div>
                            <label for="contact-phone" class="block text-sm font-medium text-gray-700 mb-1">ফোন</label>
                            <input type="tel" id="contact-phone" name="phone"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-green focus:border-transparent">
                        </div>
                        
                        <div>
                            <label for="contact-message" class="block text-sm font-medium text-gray-700 mb-1">বার্তা *</label>
                            <textarea id="contact-message" name="message" rows="4" required
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-green focus:border-transparent"
                                      placeholder="আপনার বার্তা লিখুন..."></textarea>
                        </div>
                        
                        <button type="submit" 
                                class="w-full bg-primary-green hover:bg-primary-red text-white font-bold py-3 px-6 rounded-md transition-all duration-300">
                            বার্তা পাঠান
                        </button>
                    </form>
                </div>
            ` : '';

            this.modalContent.innerHTML = `
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left Column: Image and Basic Info -->
                    <div class="lg:col-span-1">
                        <div class="sticky top-8">
                            ${candidate.image ?
                `<img src="${candidate.image}" alt="${candidate.name_bangla || candidate.title}" 
                                     class="w-full h-80 object-cover rounded-xl shadow-lg mb-6">` :
                `<div class="w-full h-80 bg-gradient-to-br from-primary-green to-primary-blue rounded-xl shadow-lg mb-6 flex items-center justify-center">
                                    <svg class="w-20 h-20 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>`
            }
                            
                            <div class="bg-gradient-to-br from-primary-blue to-primary-green text-white rounded-xl p-6">
                                <h2 class="text-2xl font-bold mb-2">${candidate.name_bangla || candidate.title}</h2>
                                <p class="text-xl mb-2">${candidate.position || ''}</p>
                                ${candidate.ballot_number ? `<p class="text-lg">ব্যালট নং: ${candidate.ballot_number}</p>` : ''}
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right Column: Detailed Information -->
                    <div class="lg:col-span-2 space-y-8">
                        <!-- Basic Information -->
                        <div>
                            <h3 class="text-xl font-bold text-gray-800 mb-4">মৌলিক তথ্য</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                ${candidate.department ? `<div class="bg-gray-50 rounded-lg p-4">
                                    <h4 class="font-semibold text-gray-700">বিভাগ</h4>
                                    <p class="text-gray-600">${candidate.department}</p>
                                </div>` : ''}
                                
                                ${candidate.hall ? `<div class="bg-gray-50 rounded-lg p-4">
                                    <h4 class="font-semibold text-gray-700">হল</h4>
                                    <p class="text-gray-600">${candidate.hall}</p>
                                </div>` : ''}
                                
                                ${candidate.session ? `<div class="bg-gray-50 rounded-lg p-4">
                                    <h4 class="font-semibold text-gray-700">সেশন</h4>
                                    <p class="text-gray-600">${candidate.session}</p>
                                </div>` : ''}
                            </div>
                        </div>
                        
                        <!-- Family Information -->
                        ${(candidate.father_name || candidate.mother_name) ? `
                        <div>
                            <h3 class="text-xl font-bold text-gray-800 mb-4">পারিবারিক তথ্য</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                ${candidate.father_name ? `<div class="bg-gray-50 rounded-lg p-4">
                                    <h4 class="font-semibold text-gray-700">পিতার নাম</h4>
                                    <p class="text-gray-600">${candidate.father_name}</p>
                                    ${candidate.father_profession ? `<p class="text-sm text-gray-500 mt-1">পেশা: ${candidate.father_profession}</p>` : ''}
                                </div>` : ''}
                                
                                ${candidate.mother_name ? `<div class="bg-gray-50 rounded-lg p-4">
                                    <h4 class="font-semibold text-gray-700">মাতার নাম</h4>
                                    <p class="text-gray-600">${candidate.mother_name}</p>
                                    ${candidate.mother_profession ? `<p class="text-sm text-gray-500 mt-1">পেশা: ${candidate.mother_profession}</p>` : ''}
                                </div>` : ''}
                            </div>
                        </div>` : ''}
                        
                        <!-- Educational Information -->
                        ${(candidate.ssc_info || candidate.hsc_info || candidate.graduation_info) ? `
                        <div>
                            <h3 class="text-xl font-bold text-gray-800 mb-4">শিক্ষাগত যোগ্যতা</h3>
                            <div class="space-y-3">
                                ${candidate.ssc_info ? `<div class="bg-gray-50 rounded-lg p-4">
                                    <h4 class="font-semibold text-gray-700">এসএসসি</h4>
                                    <p class="text-gray-600">${candidate.ssc_info}</p>
                                </div>` : ''}
                                
                                ${candidate.hsc_info ? `<div class="bg-gray-50 rounded-lg p-4">
                                    <h4 class="font-semibold text-gray-700">এইচএসসি</h4>
                                    <p class="text-gray-600">${candidate.hsc_info}</p>
                                </div>` : ''}
                                
                                ${candidate.graduation_info ? `<div class="bg-gray-50 rounded-lg p-4">
                                    <h4 class="font-semibold text-gray-700">স্নাতক</h4>
                                    <p class="text-gray-600">${candidate.graduation_info}</p>
                                </div>` : ''}
                            </div>
                        </div>` : ''}
                        
                        <!-- Special Achievements -->
                        ${candidate.special_achievements ? `
                        <div>
                            <h3 class="text-xl font-bold text-gray-800 mb-4">বিশেষ অর্জন</h3>
                            <div class="bg-gray-50 rounded-lg p-6">
                                <p class="text-gray-700 leading-relaxed">${candidate.special_achievements.replace(/\n/g, '<br>')}</p>
                            </div>
                        </div>` : ''}
                        
                        <!-- Political Journey -->
                        ${candidate.political_journey ? `
                        <div>
                            <h3 class="text-xl font-bold text-gray-800 mb-4">রাজনৈতিক যাত্রা</h3>
                            <div class="bg-gray-50 rounded-lg p-6">
                                <p class="text-gray-700 leading-relaxed">${candidate.political_journey.replace(/\n/g, '<br>')}</p>
                            </div>
                        </div>` : ''}
                        
                        <!-- Vision -->
                        ${candidate.vision ? `
                        <div>
                            <h3 class="text-xl font-bold text-gray-800 mb-4">ভবিষ্যৎ পরিকল্পনা</h3>
                            <div class="bg-gradient-to-r from-primary-green/10 to-primary-blue/10 rounded-lg p-6">
                                <p class="text-gray-700 leading-relaxed">${candidate.vision.replace(/\n/g, '<br>')}</p>
                            </div>
                        </div>` : ''}
                        
                        <!-- Gallery -->
                        ${galleryHtml}
                        
                        <!-- Social Links -->
                        ${(candidate.facebook_url || candidate.twitter_url) ? `
                        <div>
                            <h3 class="text-xl font-bold text-gray-800 mb-4">সামাজিক যোগাযোগ</h3>
                            <div class="flex space-x-4">
                                ${candidate.facebook_url ? `
                                <a href="${candidate.facebook_url}" target="_blank" 
                                   class="flex items-center space-x-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                    </svg>
                                    <span>Facebook</span>
                                </a>` : ''}
                                
                                ${candidate.twitter_url ? `
                                <a href="${candidate.twitter_url}" target="_blank" 
                                   class="flex items-center space-x-2 bg-blue-400 text-white px-4 py-2 rounded-lg hover:bg-blue-500 transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                    </svg>
                                    <span>Twitter</span>
                                </a>` : ''}
                            </div>
                        </div>` : ''}
                        
                        <!-- Contact Form -->
                        ${showContactForm ? contactFormHtml : ''}
                    </div>
                </div>
            `;

            // Initialize contact form if shown
            if (showContactForm && candidate.email) {
                this.initContactForm();
            }
        },

        initContactForm() {
            const contactForm = document.querySelector('#candidate-contact-form');
            if (contactForm) {
                contactForm.addEventListener('submit', this.handleContactFormSubmit.bind(this));
            }
        },

        async handleContactFormSubmit(e) {
            e.preventDefault();

            const form = e.target;
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;

            // Disable submit button
            submitBtn.disabled = true;
            submitBtn.textContent = 'পাঠানো হচ্ছে...';

            try {
                const formData = new FormData(form);
                formData.append('action', 'ducsu_contact');
                formData.append('nonce', ducsu_ajax.nonce);

                const response = await fetch(ducsu_ajax.ajax_url, {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    this.showSuccess(data.data);
                    form.reset();
                } else {
                    this.showError(data.data);
                }
            } catch (error) {
                console.error('Contact form error:', error);
                this.showError('বার্তা পাঠাতে সমস্যা হয়েছে। পরে চেষ্টা করুন।');
            } finally {
                // Re-enable submit button
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        },

        closeModal() {
            if (this.modal) {
                this.modal.classList.add('hidden');
                if (this.modalContent) {
                    this.modalContent.innerHTML = '';
                }
            }
        },

        showError(message) {
            this.showNotification(message, 'error');
        },

        showSuccess(message) {
            this.showNotification(message, 'success');
        },

        showNotification(message, type = 'info') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full ${
                type === 'error' ? 'bg-red-500 text-white' :
                    type === 'success' ? 'bg-green-500 text-white' :
                        'bg-blue-500 text-white'
            }`;

            notification.innerHTML = `
                <div class="flex items-center space-x-3">
                    ${type === 'error' ?
                '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>' :
                '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>'
            }
                    <span>${message}</span>
                    <button class="ml-4 hover:opacity-75">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
            `;

            document.body.appendChild(notification);

            // Show notification
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);

            // Auto hide after 5 seconds
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }, 5000);

            // Close button functionality
            notification.querySelector('button').addEventListener('click', () => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            });
        }
    };

    // ===========================
    // MOBILE MENU FUNCTIONALITY
    // ===========================
    const mobileMenu = {
        init() {
            this.menuToggle = document.querySelector('.mobile-menu-toggle');
            this.mobileMenu = document.querySelector('.mobile-menu');
            this.menuOverlay = document.querySelector('.mobile-menu-overlay');

            this.bindEvents();
        },

        bindEvents() {
            if (this.menuToggle) {
                this.menuToggle.addEventListener('click', () => this.toggleMenu());
            }

            if (this.menuOverlay) {
                this.menuOverlay.addEventListener('click', () => this.closeMenu());
            }

            // Close menu on escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    this.closeMenu();
                }
            });
        },

        toggleMenu() {
            if (this.mobileMenu) {
                this.mobileMenu.classList.toggle('hidden');
            }
        },

        closeMenu() {
            if (this.mobileMenu) {
                this.mobileMenu.classList.add('hidden');
            }
        }
    };

    // ===========================
    // SCROLL ANIMATIONS
    // ===========================
    const scrollAnimations = {
        init() {
            this.observeElements();
        },

        observeElements() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-fade-in-up');
                        entry.target.classList.remove('opacity-0', 'translate-y-8');
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            });

            // Observe elements with animation classes
            document.querySelectorAll('.animate-on-scroll').forEach(el => {
                el.classList.add('opacity-0', 'translate-y-8', 'transition-all', 'duration-700');
                observer.observe(el);
            });
        }
    };

    // ===========================
    // GENERAL CONTACT FORM (Homepage)
    // ===========================
    const generalContactForm = {
        init() {
            this.form = document.querySelector('#general-contact-form');
            if (this.form) {
                this.form.addEventListener('submit', this.handleSubmit.bind(this));
            }
        },

        async handleSubmit(e) {
            e.preventDefault();

            const submitBtn = this.form.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;

            submitBtn.disabled = true;
            submitBtn.textContent = 'পাঠানো হচ্ছে...';

            try {
                const formData = new FormData(this.form);
                formData.append('action', 'ducsu_general_contact');
                formData.append('nonce', ducsu_ajax.nonce);

                const response = await fetch(ducsu_ajax.ajax_url, {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    candidateModal.showSuccess(data.data);
                    this.form.reset();
                } else {
                    candidateModal.showError(data.data);
                }
            } catch (error) {
                console.error('General contact form error:', error);
                candidateModal.showError('বার্তা পাঠাতে সমস্যা হয়েছে। পরে চেষ্টা করুন।');
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        }
    };

    // ===========================
    // INITIALIZATION
    // ===========================

    // Initialize all components
    heroSlider.init();
    searchHandler.init();
    candidateModal.init();
    mobileMenu.init();
    scrollAnimations.init();
    generalContactForm.init();

    // Add smooth scrolling for anchor links
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

    // Add custom CSS animations
    const style = document.createElement('style');
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
    `;
    document.head.appendChild(style);
});