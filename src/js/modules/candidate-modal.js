/**
 * Candidate Modal Module
 * Handles candidate detail modals and interactions
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
    }

    bindEvents() {
        // Candidate card clicks
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

        // Close on escape key (handled by main app)
    }

    setupAccessibility() {
        if (this.modal) {
            this.modal.setAttribute('role', 'dialog');
            this.modal.setAttribute('aria-modal', 'true');
            this.modal.setAttribute('aria-labelledby', 'modal-title');
            this.modal.setAttribute('aria-describedby', 'modal-content');
        }
    }

    async openModal(candidateId) {
        if (!candidateId) return;

        this.showLoading();
        this.showModal();

        try {
            const candidateData = await this.fetchCandidateData(candidateId);
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

        const content = this.generateCandidateContent(candidate);
        this.modalContent.innerHTML = content;

        // Initialize any interactive elements
        this.initializeModalContent(candidate.id);
    }

    generateCandidateContent(candidate) {
        return `
            <div class="space-y-8 overflow-y-auto max-h-[800px]">
                ${this.generateHeaderSection(candidate)}
                ${this.generateEducationSection(candidate)}
                ${this.generateFamilySection(candidate)}
                ${this.generateContentSection(candidate)}
                ${this.generateVisionSection(candidate)}
                ${this.generatePoliticalJourneySection(candidate)}
                ${this.generateAchievementsSection(candidate)}
                ${this.generateGallerySection(candidate)}
                ${this.generateSocialLinksSection(candidate)}
                ${this.generateContactFormSection(candidate)}
            </div>
        `;
    }

    generateHeaderSection(candidate) {
        return `
            <div class="flex flex-col lg:flex-row gap-8 items-start">
                <div class="w-full lg:w-80 flex-shrink-0">
                    <div class="text-center">
                        ${candidate.image ?
            `<img src="${candidate.image}" alt="${candidate.name_bangla || candidate.title}" 
                             class="w-full lg:w-80 h-80 object-cover rounded-xl shadow-xl mx-auto">` :
            `<div class="w-full lg:w-80 h-80 bg-gradient-to-br from-green-800 to-blue-800 rounded-xl shadow-xl mx-auto flex items-center justify-center">
                                <svg class="w-20 h-20 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                </svg>
                            </div>`
        }
                    </div>
                </div>
                
                <div class="flex-1">
                    <div class="bg-gradient-to-br from-slate-600 to-cyan-900 text-white rounded-xl p-6 mb-6">
                        <h2 id="modal-title" class="text-3xl lg:text-4xl font-bold mb-2">${candidate.name_bangla || candidate.title}</h2>
                        <p class="text-xl lg:text-2xl mb-2">${candidate.position || ''}</p>
                        ${candidate.ballot_number ? `<p class="text-lg lg:text-xl">ব্যালট নং: ${candidate.ballot_number}</p>` : ''}
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        ${this.generateInfoCard('বিভাগ', candidate.department)}
                        ${this.generateInfoCard('হল', candidate.hall)}
                        ${this.generateInfoCard('সেশন', candidate.session)}
                        ${this.generateInfoCard('স্থায়ী ঠিকানা', candidate.permanent_address)}
                    </div>
                </div>
            </div>
        `;
    }

    generateInfoCard(label, value) {
        if (!value) return '';

        return `
            <div class="bg-slate-100 rounded-lg p-4">
                <h4 class="font-semibold text-gray-700 mb-1">${label}</h4>
                <p class="text-gray-800">${value}</p>
            </div>
        `;
    }

    generateEducationSection(candidate) {
        const hasEducation = candidate.ssc_school || candidate.hsc_college || candidate.graduation_university;
        if (!hasEducation) return '';

        return `
            <div class="content-section">
                <h3 class="text-2xl font-bold text-gray-800 mb-6">শিক্ষাজীবন</h3>
                <div class="space-y-6">
                    ${this.generateEducationCard('এসএসসি', candidate.ssc_school, candidate.ssc_gpa, candidate.ssc_year, 'from-green-200 to-green-100 border-green-500', 'border-green-100')}
                    ${this.generateEducationCard('এইচএসসি', candidate.hsc_college, candidate.hsc_gpa, candidate.hsc_year, 'from-blue-200 to-blue-100 border-blue-500', 'border-blue-100')}
                    ${this.generateEducationCard('স্নাতক', candidate.graduation_university, candidate.graduation_cgpa, candidate.graduation_year, 'from-purple-200 to-purple-100 border-purple-500', 'border-purple-100', candidate.graduation_subject)}
                </div>
            </div>
        `;
    }

    generateEducationCard(level, institution, grade, year, colorClass, borderClass, subject = null) {
        if (!institution && !grade && !year) return '';

        return `
            <div class="bg-gradient-to-r ${colorClass} rounded-lg p-6 border-l-4">
                <h4 class="text-xl font-bold mb-4 text-slate-700 border-b ${borderClass}">${level}</h4>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-2 text-lg">
                    ${institution ? `
                        <div>
                            <span class="font-semibold text-gray-700">প্রতিষ্ঠান:</span>
                            <p class="text-gray-800">${institution}</p>
                        </div>
                    ` : ''}
                    ${grade ? `
                        <div>
                            <span class="font-semibold text-gray-700">ফলাফল:</span>
                            <p class="text-gray-800">${grade}</p>
                        </div>
                    ` : ''}
                    ${year ? `
                        <div>
                            <span class="font-semibold text-gray-700">বছর:</span>
                            <p class="text-gray-800">${year}</p>
                        </div>
                    ` : ''}
                    ${subject ? `
                        <div>
                            <span class="font-semibold text-gray-700">বিষয়:</span>
                            <p class="text-gray-800">${subject}</p>
                        </div>
                    ` : ''}
                </div>
            </div>
        `;
    }

    generateFamilySection(candidate) {
        const hasFamily = candidate.father_name || candidate.mother_name;
        if (!hasFamily) return '';

        return `
            <div class="content-section">
                <h3 class="text-2xl font-bold text-gray-800 mb-6">পারিবারিক তথ্য</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    ${candidate.father_name ? `
                        <div class="bg-slate-100 rounded-lg p-6">
                            <h4 class="font-semibold text-gray-700 mb-2">পিতার নাম</h4>
                            <p class="text-gray-800 mb-1">${candidate.father_name}</p>
                            ${candidate.father_profession ? `<p class="text-lg text-gray-600">পেশা: ${candidate.father_profession}</p>` : ''}
                        </div>
                    ` : ''}
                    
                    ${candidate.mother_name ? `
                        <div class="bg-slate-100 rounded-lg p-6">
                            <h4 class="font-semibold text-gray-700 mb-2">মাতার নাম</h4>
                            <p class="text-gray-800 mb-1">${candidate.mother_name}</p>
                            ${candidate.mother_profession ? `<p class="text-lg text-gray-600">পেশা: ${candidate.mother_profession}</p>` : ''}
                        </div>
                    ` : ''}
                </div>
            </div>
        `;
    }

    generateContentSection(candidate) {
        if (!candidate.content) return '';

        return `
            <div class="content-section">
                <h3 class="text-2xl font-bold text-gray-800 mb-6">জীবনী</h3>
                <div class="bg-white rounded-lg p-6 prose prose-lg max-w-none">
                    ${candidate.content.replace(/\n/g, '<br>')}
                </div>
            </div>
        `;
    }

    generateVisionSection(candidate) {
        if (!candidate.vision) return '';

        return `
            <div class="content-section">
                <h3 class="text-2xl font-bold text-gray-800 mb-6">ভবিষ্যৎ পরিকল্পনা ও দৃষ্টিভঙ্গি</h3>
                <div class="bg-slate-100 rounded-lg p-6">
                    <p class="text-gray-700 leading-relaxed">${candidate.vision.replace(/\n/g, '<br>')}</p>
                </div>
            </div>
        `;
    }

    generatePoliticalJourneySection(candidate) {
        if (!candidate.political_journey) return '';

        return `
            <div class="content-section">
                <h3 class="text-2xl font-bold text-gray-800 mb-6">রাজনৈতিক যাত্রা</h3>
                <div class="bg-slate-100 rounded-lg p-6">
                    <p class="text-gray-700 leading-relaxed">${candidate.political_journey.replace(/\n/g, '<br>')}</p>
                </div>
            </div>
        `;
    }

    generateAchievementsSection(candidate) {
        if (!candidate.special_achievements) return '';

        return `
            <div class="content-section">
                <h3 class="text-2xl font-bold text-gray-800 mb-6">বিশেষ অর্জন</h3>
                <div class="bg-slate-100 rounded-lg p-6">
                    <p class="text-gray-700 leading-relaxed">${candidate.special_achievements.replace(/\n/g, '<br>')}</p>
                </div>
            </div>
        `;
    }

    generateGallerySection(candidate) {
        if (!candidate.gallery || candidate.gallery.length === 0) return '';

        const galleryHtml = candidate.gallery.map(image => `
            <img src="${image.url}" alt="Gallery Image" 
                 class="w-full h-48 object-cover rounded-lg cursor-pointer hover:opacity-80 transition-opacity shadow-md hover:shadow-lg gallery-image"
                 data-full-url="${image.full_url}">
        `).join('');

        return `
            <div class="content-section">
                <h3 class="text-2xl font-bold text-gray-800 mb-6">ছবি গ্যালারি</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    ${galleryHtml}
                </div>
            </div>
        `;
    }

    generateSocialLinksSection(candidate) {
        const hasSocial = candidate.facebook_url || candidate.twitter_url;
        if (!hasSocial) return '';

        return `
            <div class="content-section">
                <h3 class="text-2xl font-bold text-gray-800 mb-6">সামাজিক যোগাযোগ</h3>
                <div class="flex flex-wrap gap-4">
                    ${candidate.facebook_url ? `
                        <a href="${candidate.facebook_url}" target="_blank" 
                           class="flex items-center space-x-2 bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                            <span>Facebook</span>
                        </a>
                    ` : ''}
                    
                    ${candidate.twitter_url ? `
                        <a href="${candidate.twitter_url}" target="_blank" 
                           class="flex items-center space-x-2 bg-blue-400 text-white px-6 py-3 rounded-lg hover:bg-blue-500 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                            <span>Twitter</span>
                        </a>
                    ` : ''}
                </div>
            </div>
        `;
    }

    generateContactFormSection(candidate) {
        if (!candidate.email) return '';

        return `
            <div class="bg-gray-50 rounded-lg p-6 mb-16">
                <h3 class="text-xl font-bold text-gray-800 mb-4">যোগাযোগ করুন</h3>
                <form id="candidate-contact-form" class="space-y-4">
                    <input type="hidden" name="candidate_id" value="${candidate.id}">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="contact-name" class="block text-lg font-medium text-gray-700 mb-1">নাম *</label>
                            <input type="text" id="contact-name" name="name" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-green focus:border-transparent">
                        </div>
                        <div>
                            <label for="contact-email" class="block text-lg font-medium text-gray-700 mb-1">ইমেইল *</label>
                            <input type="email" id="contact-email" name="email" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-green focus:border-transparent">
                        </div>
                    </div>
                    
                    <div>
                        <label for="contact-phone" class="block text-lg font-medium text-gray-700 mb-1">ফোন</label>
                        <input type="tel" id="contact-phone" name="phone"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-green focus:border-transparent">
                    </div>
                    
                    <div>
                        <label for="contact-message" class="block text-lg font-medium text-gray-700 mb-1">বার্তা *</label>
                        <textarea id="contact-message" name="message" rows="4" required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-green focus:border-transparent"
                                  placeholder="আপনার বার্তা লিখুন..."></textarea>
                    </div>
                    
                    <button type="submit" 
                            class="w-full bg-primary-green hover:bg-primary-red cursor-pointer inline-block text-white font-bold py-3 px-6 rounded-md transition-all duration-300">
                        বার্তা পাঠান
                    </button>
                </form>
            </div>
        `;
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
                    this.openImageModal(fullUrl);
                }
            });
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
        }
    }

    hideLoading() {
        if (this.loadingSpinner) {
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
            'a[href], button, textarea, input[type="text"], input[type="radio"], input[type="checkbox"], select, [tabindex]:not([tabindex="-1"])'
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
        this.closeModal();
    }

    // Public API methods
    isModalOpen() {
        return this.modal && !this.modal.classList.contains('hidden');
    }

    getCurrentCandidateId() {
        const form = this.modalContent?.querySelector('#candidate-contact-form');
        return form?.querySelector('input[name="candidate_id"]')?.value || null;
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

        // Remove event listeners would go here in a full implementation
    }
}