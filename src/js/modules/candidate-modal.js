/**
 * Candidate Modal Module - Original Layout with Search Integration
 * Replace your existing src/js/modules/candidate-modal.js with this version
 */

export class CandidateModal {
    constructor() {
        this.modal = document.querySelector('#candidate-modal');
        this.modalContent = document.querySelector('.candidate-modal-content');
        this.closeBtn = document.querySelector('#close-modal');
        this.loadingSpinner = document.querySelector('#loading-spinner');

        this.currentRequest = null;
        this.cache = new Map();

        this.init();
    }

    init() {
        this.bindEvents();
        this.setupAccessibility();
        this.handleURLHash(); // Add search integration
    }

    bindEvents() {
        // Candidate card clicks - keep original functionality
        document.addEventListener('click', (e) => {
            const candidateCard = e.target.closest('.candidate-card');

            if (candidateCard) {
                e.preventDefault();
                const candidateElement = candidateCard.closest('[data-candidate-id]');
                const candidateId = candidateElement?.getAttribute('data-candidate-id');

                if (candidateId) {
                    this.openModal(candidateId);
                }
            }
        });

        // Close modal events
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

        // Add hash change listener for search integration
        window.addEventListener('hashchange', () => {
            this.handleURLHash();
        });

        // Close on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isModalOpen()) {
                this.closeModal();
            }
        });
    }

    setupAccessibility() {
        if (this.modal) {
            this.modal.setAttribute('role', 'dialog');
            this.modal.setAttribute('aria-modal', 'true');
            this.modal.setAttribute('aria-labelledby', 'modal-title');
            this.modal.setAttribute('aria-describedby', 'modal-content');
        }
    }

    /**
     * Handle URL hash for auto-opening candidate modals from search
     */
    handleURLHash() {
        const hash = window.location.hash;
        if (hash.startsWith('#candidate-')) {
            const candidateId = hash.replace('#candidate-', '');
            if (candidateId && !isNaN(candidateId)) {
                // Small delay to ensure page is loaded
                setTimeout(() => {
                    this.openModal(parseInt(candidateId), true);
                }, 100);
            }
        }
    }

    async openModal(candidateId, fromHash = false) {
        if (!candidateId) return;

        // Check if we're on a candidate page (central-panel or hall-panels)
        const currentPath = window.location.pathname;
        const isOnCandidatePage = currentPath.includes('/central-panel') || currentPath.includes('/hall-panels');

        if (!isOnCandidatePage && fromHash) {
            // If not on candidate page but trying to open from hash, we can't open modal
            console.log('Not on candidate page, cannot open modal');
            return;
        }

        this.showLoading();
        this.showModal();

        try {
            const candidateData = await this.fetchCandidateData(candidateId);

            // Update URL hash if not already set (for direct clicks, not search)
            if (!fromHash) {
                history.replaceState(null, null, window.location.pathname + window.location.search + '#candidate-' + candidateId);
            }

            this.displayCandidateDetails(candidateData);
            this.trapFocus();
        } catch (error) {
            console.error('Error loading candidate details:', error);
            this.showError('প্রার্থীর তথ্য লোড করতে সমস্যা হয়েছে');
        } finally {
            this.hideLoading();
        }
    }

    async fetchCandidateData(candidateId) {
        // Check cache first
        if (this.cache.has(candidateId)) {
            return this.cache.get(candidateId);
        }

        // Cancel previous request
        if (this.currentRequest) {
            this.currentRequest.abort();
        }

        const controller = new AbortController();
        this.currentRequest = controller;

        const formData = new FormData();
        formData.append('action', 'get_candidate_details');
        formData.append('candidate_id', candidateId);
        formData.append('nonce', window.ducsu_ajax.nonce);

        const response = await fetch(window.ducsu_ajax.ajax_url, {
            method: 'POST',
            body: formData,
            signal: controller.signal
        });

        const data = await response.json();

        if (!data.success) {
            throw new Error(data.data || 'Failed to fetch candidate data');
        }

        // Cache the result
        this.cache.set(candidateId, data.data);

        return data.data;
    }

    displayCandidateDetails(candidate) {
        if (!this.modalContent) return;

        // Use your ORIGINAL layout structure
        const content = `
            <div class="flex flex-col lg:grid lg:grid-cols-3 gap-8 max-h-[80vh] overflow-y-auto">
                <!-- Left Column - Image and Basic Info -->
                <div class="lg:col-span-1">
                    <div class="lg:sticky lg:top-8">
                        ${candidate.image ? `
                            <img src="${candidate.image}" alt="${candidate.name_bangla || candidate.title}" 
                                 class="w-full rounded-lg shadow-lg mb-6 object-cover aspect-square">
                        ` : `
                            <div class="w-full aspect-square rounded-lg bg-gradient-to-br from-primary-green to-primary-blue flex items-center justify-center mb-6 shadow-lg">
                                <svg class="w-24 h-24 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        `}
                        
                        <!-- Basic Info Card -->
                        <div class="bg-gray-50 rounded-lg p-6 mb-6">
                            <h2 class="text-2xl font-bold text-gray-800 mb-4">${candidate.name_bangla || candidate.title}</h2>
                            
                            ${candidate.position ? `<div class="mb-3">
                                <span class="text-sm font-medium text-gray-600">পদ:</span>
                                <span class="ml-2 px-3 py-1 bg-primary-green text-white rounded-full text-sm">${candidate.position}</span>
                            </div>` : ''}
                            
                            ${candidate.ballot_number ? `<div class="mb-3">
                                <span class="text-sm font-medium text-gray-600">ব্যালট নম্বর:</span>
                                <span class="ml-2 text-2xl font-bold text-primary-blue">${candidate.ballot_number}</span>
                            </div>` : ''}
                            
                            ${candidate.department ? `<div class="mb-3">
                                <span class="text-sm font-medium text-gray-600">বিভাগ:</span>
                                <span class="ml-2">${candidate.department}</span>
                            </div>` : ''}
                            
                            ${candidate.hall ? `<div class="mb-3">
                                <span class="text-sm font-medium text-gray-600">হল:</span>
                                <span class="ml-2">${candidate.hall}</span>
                            </div>` : ''}
                            
                            ${candidate.session ? `<div class="mb-3">
                                <span class="text-sm font-medium text-gray-600">সেশন:</span>
                                <span class="ml-2">${candidate.session}</span>
                            </div>` : ''}
                        </div>

                        <!-- Social Links -->
                        ${candidate.facebook_url || candidate.twitter_url || candidate.email ? `
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">যোগাযোগ</h3>
                                <div class="space-y-2">
                                    ${candidate.facebook_url ? `<a href="${candidate.facebook_url}" target="_blank" class="flex items-center text-blue-600 hover:text-blue-800 transition-colors">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                        </svg>
                                        Facebook
                                    </a>` : ''}
                                    
                                    ${candidate.twitter_url ? `<a href="${candidate.twitter_url}" target="_blank" class="flex items-center text-blue-400 hover:text-blue-600 transition-colors">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                        </svg>
                                        Twitter
                                    </a>` : ''}
                                </div>
                            </div>
                        ` : ''}
                    </div>
                </div>

                <!-- Right Column - Detailed Information -->
                <div class="lg:col-span-2">
                    <div class="space-y-8">
                        <!-- Personal Information -->
                        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                            <div class="bg-primary-green text-white px-6 py-4">
                                <h3 class="text-xl font-semibold">ব্যক্তিগত তথ্য</h3>
                            </div>
                            <div class="p-6 space-y-4">
                                ${candidate.father_name ? `<div>
                                    <span class="font-medium text-gray-700">পিতার নাম:</span>
                                    <span class="ml-2">${candidate.father_name}</span>
                                    ${candidate.father_profession ? ` (${candidate.father_profession})` : ''}
                                </div>` : ''}
                                
                                ${candidate.mother_name ? `<div>
                                    <span class="font-medium text-gray-700">মাতার নাম:</span>
                                    <span class="ml-2">${candidate.mother_name}</span>
                                    ${candidate.mother_profession ? ` (${candidate.mother_profession})` : ''}
                                </div>` : ''}
                                
                                ${candidate.permanent_address ? `<div>
                                    <span class="font-medium text-gray-700">স্থায়ী ঠিকানা:</span>
                                    <span class="ml-2">${candidate.permanent_address}</span>
                                </div>` : ''}
                            </div>
                        </div>

                        <!-- Educational Information -->
                        ${(candidate.ssc_school || candidate.hsc_college || candidate.graduation_university) ? `
                            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                                <div class="bg-primary-blue text-white px-6 py-4">
                                    <h3 class="text-xl font-semibold">শিক্ষাগত যোগ্যতা</h3>
                                </div>
                                <div class="p-6 space-y-6">
                                    ${candidate.ssc_school ? `<div>
                                        <h4 class="font-semibold text-gray-800 mb-2">এসএসসি</h4>
                                        <div class="bg-gray-50 p-4 rounded">
                                            <p><strong>প্রতিষ্ঠান:</strong> ${candidate.ssc_school}</p>
                                            ${candidate.ssc_gpa ? `<p><strong>জিপিএ:</strong> ${candidate.ssc_gpa}</p>` : ''}
                                            ${candidate.ssc_year ? `<p><strong>পাশের বছর:</strong> ${candidate.ssc_year}</p>` : ''}
                                        </div>
                                    </div>` : ''}
                                    
                                    ${candidate.hsc_college ? `<div>
                                        <h4 class="font-semibold text-gray-800 mb-2">এইচএসসি</h4>
                                        <div class="bg-gray-50 p-4 rounded">
                                            <p><strong>প্রতিষ্ঠান:</strong> ${candidate.hsc_college}</p>
                                            ${candidate.hsc_gpa ? `<p><strong>জিপিএ:</strong> ${candidate.hsc_gpa}</p>` : ''}
                                            ${candidate.hsc_year ? `<p><strong>পাশের বছর:</strong> ${candidate.hsc_year}</p>` : ''}
                                        </div>
                                    </div>` : ''}
                                    
                                    ${candidate.graduation_university ? `<div>
                                        <h4 class="font-semibold text-gray-800 mb-2">স্নাতক</h4>
                                        <div class="bg-gray-50 p-4 rounded">
                                            <p><strong>বিশ্ববিদ্যালয়:</strong> ${candidate.graduation_university}</p>
                                            ${candidate.graduation_subject ? `<p><strong>বিষয়:</strong> ${candidate.graduation_subject}</p>` : ''}
                                            ${candidate.graduation_cgpa ? `<p><strong>সিজিপিএ:</strong> ${candidate.graduation_cgpa}</p>` : ''}
                                            ${candidate.graduation_year ? `<p><strong>পাশের বছর:</strong> ${candidate.graduation_year}</p>` : ''}
                                        </div>
                                    </div>` : ''}
                                </div>
                            </div>
                        ` : ''}

                        <!-- Vision and Achievements -->
                        ${(candidate.vision || candidate.special_achievements || candidate.political_journey) ? `
                            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                                <div class="bg-primary-red text-white px-6 py-4">
                                    <h3 class="text-xl font-semibold">ভবিষ্যৎ পরিকল্পনা ও অভিজ্ঞতা</h3>
                                </div>
                                <div class="p-6 space-y-6">
                                    ${candidate.vision ? `<div>
                                        <h4 class="font-semibold text-gray-800 mb-3">দৃষ্টিভঙ্গি ও পরিকল্পনা</h4>
                                        <div class="prose max-w-none text-gray-700">
                                            ${candidate.vision.replace(/\n/g, '<br>')}
                                        </div>
                                    </div>` : ''}
                                    
                                    ${candidate.special_achievements ? `<div>
                                        <h4 class="font-semibold text-gray-800 mb-3">বিশেষ অর্জন</h4>
                                        <div class="prose max-w-none text-gray-700">
                                            ${candidate.special_achievements.replace(/\n/g, '<br>')}
                                        </div>
                                    </div>` : ''}
                                    
                                    ${candidate.political_journey ? `<div>
                                        <h4 class="font-semibold text-gray-800 mb-3">রাজনৈতিক অভিজ্ঞতা</h4>
                                        <div class="prose max-w-none text-gray-700">
                                            ${candidate.political_journey.replace(/\n/g, '<br>')}
                                        </div>
                                    </div>` : ''}
                                </div>
                            </div>
                        ` : ''}

                        <!-- Gallery -->
                        ${candidate.gallery && candidate.gallery.length > 0 ? `
                            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                                <div class="bg-gray-700 text-white px-6 py-4">
                                    <h3 class="text-xl font-semibold">গ্যালারি</h3>
                                </div>
                                <div class="p-6">
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                        ${candidate.gallery.map(img => `
                                            <img src="${img.url}" alt="Gallery Image" 
                                                 class="w-full h-32 object-cover rounded-lg cursor-pointer hover:opacity-75 transition-opacity gallery-image"
                                                 data-full-url="${img.full_url}">
                                        `).join('')}
                                    </div>
                                </div>
                            </div>
                        ` : ''}

                        <!-- Content/Bio -->
                        ${candidate.content ? `
                            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                                <div class="bg-gray-600 text-white px-6 py-4">
                                    <h3 class="text-xl font-semibold">বিস্তারিত</h3>
                                </div>
                                <div class="p-6">
                                    <div class="prose max-w-none text-gray-700">
                                        ${candidate.content}
                                    </div>
                                </div>
                            </div>
                        ` : ''}

                        <!-- Contact Form -->
                        ${candidate.email ? `
                            <div class="bg-gray-50 rounded-lg border border-gray-200 overflow-hidden">
                                <div class="bg-gray-600 text-white px-6 py-4">
                                    <h3 class="text-xl font-semibold">যোগাযোগ করুন</h3>
                                </div>
                                <div class="p-6">
                                    <form id="candidate-contact-form" class="space-y-4">
                                        <input type="hidden" name="candidate_id" value="${candidate.id}">
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label for="contact-name" class="block text-sm font-medium text-gray-700 mb-1">আপনার নাম *</label>
                                                <input type="text" id="contact-name" name="name" required
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-green focus:border-transparent">
                                            </div>
                                            <div>
                                                <label for="contact-email" class="block text-sm font-medium text-gray-700 mb-1">আপনার ইমেইল *</label>
                                                <input type="email" id="contact-email" name="email" required
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-green focus:border-transparent">
                                            </div>
                                        </div>
                                        
                                        <div>
                                            <label for="contact-phone" class="block text-sm font-medium text-gray-700 mb-1">ফোন নম্বর</label>
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
                                                class="w-full bg-primary-green hover:bg-primary-blue text-white font-bold py-3 px-6 rounded-md transition-colors duration-300">
                                            বার্তা পাঠান
                                        </button>
                                    </form>
                                </div>
                            </div>
                        ` : ''}
                    </div>
                </div>
            </div>
        `;

        this.modalContent.innerHTML = content;

        // Initialize any interactive elements (keep original functionality)
        this.initializeModalContent(candidate.id, !!candidate.email);
    }

    initializeModalContent(candidateId, hasContactForm) {
        // Initialize gallery images
        this.initGalleryImages();

        // Initialize contact form if present
        if (hasContactForm) {
            this.initContactForm();
        }
    }

    initGalleryImages() {
        const galleryImages = this.modalContent?.querySelectorAll('.gallery-image');
        galleryImages?.forEach(img => {
            img.addEventListener('click', () => {
                const fullUrl = img.getAttribute('data-full-url');
                if (fullUrl) {
                    this.showLightbox(fullUrl);
                }
            });
        });
    }

    showLightbox(imageUrl) {
        const lightbox = document.createElement('div');
        lightbox.className = 'fixed inset-0 z-70 bg-black bg-opacity-90 flex items-center justify-center p-4';
        lightbox.innerHTML = `
        <div class="relative max-w-auto max-h-[90vh]">
            <button class="absolute top-2 right-2 border-2 border-slate-100/80 bg-slate-800/60 hover:bg-slate-800/100 rounded text-slate-100 hover:text-slate-200 px-4 py-2 text-2xl cursor-pointer font-bold leading-none">&times;</button>
            <img src="${imageUrl}" alt="Gallery Image" class="max-w-auto max-h-[90vh] object-contain rounded-lg">
        </div>
    `;

        this.modal.appendChild(lightbox);

        // Close on click anywhere or button
        lightbox.addEventListener('click', (e) => {
            if (e.target === lightbox || e.target.closest('button')) {
                this.modal.removeChild(lightbox);
            }
        });
    }

    initContactForm() {
        const form = this.modalContent?.querySelector('#candidate-contact-form');
        if (form) {
            form.addEventListener('submit', (e) => this.handleContactFormSubmit(e));
        }
    }

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
            formData.append('nonce', window.ducsu_ajax.nonce);

            const response = await fetch(window.ducsu_ajax.ajax_url, {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                this.showNotification(data.data, 'success');
                form.reset();
            } else {
                this.showNotification(data.data, 'error');
            }
        } catch (error) {
            console.error('Contact form error:', error);
            this.showNotification('বার্তা পাঠাতে সমস্যা হয়েছে। পরে চেষ্টা করুন।', 'error');
        } finally {
            // Re-enable submit button
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        }
    }

    openImageModal(imageUrl) {
        // Create and show image modal
        const imageModal = document.createElement('div');
        imageModal.className = 'fixed inset-0 z-60 bg-black bg-opacity-75 flex items-center justify-center p-4';
        imageModal.innerHTML = `
            <div class="relative max-w-4xl max-h-full">
                <button class="absolute top-4 right-4 text-white hover:text-gray-300 z-10 bg-black bg-opacity-50 rounded-full p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                <img src="${imageUrl}" alt="Gallery Image" class="max-w-full max-h-full object-contain">
            </div>
        `;

        document.body.appendChild(imageModal);

        // Close on click
        imageModal.addEventListener('click', (e) => {
            if (e.target === imageModal || e.target.closest('button')) {
                document.body.removeChild(imageModal);
            }
        });
    }

    showModal() {
        if (this.modal) {
            this.modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    }

    closeModal() {
        if (this.modal) {
            this.modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        if (this.modalContent) {
            this.modalContent.innerHTML = '';
        }

        // Remove hash from URL when closing
        if (window.location.hash.startsWith('#candidate-')) {
            history.replaceState(null, null, window.location.pathname + window.location.search);
        }

        // Cancel any pending request
        if (this.currentRequest) {
            this.currentRequest.abort();
            this.currentRequest = null;
        }

        // Remove focus trap
        this.removeFocusTrap();
    }

    showLoading() {
        if (this.loadingSpinner) {
            this.loadingSpinner.classList.remove('hidden');
            this.loadingSpinner.classList.add('flex');
        }
    }

    hideLoading() {
        if (this.loadingSpinner) {
            this.loadingSpinner.classList.remove('flex');
            this.loadingSpinner.classList.add('hidden');
        }
    }

    showError(message) {
        if (this.modalContent) {
            this.modalContent.innerHTML = `
                <div class="text-center py-16">
                    <svg class="w-16 h-16 text-red-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">সমস্যা হয়েছে</h3>
                    <p class="text-gray-600 mb-4">${message}</p>
                    <button onclick="this.closest('.fixed').querySelector('#close-modal').click()" 
                            class="bg-primary-green hover:bg-primary-blue text-white px-6 py-2 rounded-lg transition-colors">
                        বন্ধ করুন
                    </button>
                </div>
            `;
        }
    }

    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-60 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full ${
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
                <button class="ml-4 hover:opacity-75" type="button">
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

    trapFocus() {
        if (!this.modal) return;

        const focusableElements = this.modal.querySelectorAll(
            'a[href], button, textarea, input[type="text"], input[type="email"], input[type="tel"], input[type="radio"], input[type="checkbox"], select, [tabindex]:not([tabindex="-1"])'
        );

        if (focusableElements.length === 0) return;

        const firstFocusableElement = focusableElements[0];
        const lastFocusableElement = focusableElements[focusableElements.length - 1];

        // Focus the first element
        firstFocusableElement.focus();

        this.handleTabKey = (e) => {
            if (e.key === 'Tab') {
                if (e.shiftKey) {
                    if (document.activeElement === firstFocusableElement) {
                        lastFocusableElement.focus();
                        e.preventDefault();
                    }
                } else {
                    if (document.activeElement === lastFocusableElement) {
                        firstFocusableElement.focus();
                        e.preventDefault();
                    }
                }
            }
        };

        document.addEventListener('keydown', this.handleTabKey);
    }

    removeFocusTrap() {
        if (this.handleTabKey) {
            document.removeEventListener('keydown', this.handleTabKey);
            this.handleTabKey = null;
        }
    }

    handleEscape() {
        if (this.isModalOpen()) {
            this.closeModal();
        }
    }

    // Public API methods
    isModalOpen() {
        return this.modal && !this.modal.classList.contains('hidden');
    }

    getCurrentCandidateId() {
        const form = this.modalContent?.querySelector('#candidate-contact-form');
        const candidateIdInput = form?.querySelector('input[name="candidate_id"]');
        if (candidateIdInput) {
            return parseInt(candidateIdInput.value);
        }

        // Fallback to hash
        const hash = window.location.hash;
        if (hash.startsWith('#candidate-')) {
            return parseInt(hash.replace('#candidate-', ''));
        }

        return null;
    }

    clearCache() {
        this.cache.clear();
    }

    destroy() {
        // Cancel any pending request
        if (this.currentRequest) {
            this.currentRequest.abort();
        }

        // Clear cache
        this.clearCache();

        // Remove focus trap
        this.removeFocusTrap();

        // Close modal
        this.closeModal();
    }
}