/**
 * Enhanced Search Handler Module with Modal Auto-Open
 * Replace your existing search-handler.js content
 */

export class SearchHandler {
    constructor() {
        this.searchInput = document.querySelector('#search-input');
        this.searchResults = document.querySelector('#search-results');
        this.searchOverlay = document.querySelector('#search-overlay');
        this.searchToggleBtn = document.querySelector('#search-toggle-btn');
        this.searchCloseBtn = document.querySelector('#search-close-btn');

        this.debounceTimer = null;
        this.currentRequest = null;
        this.cache = new Map();
        this.cacheExpiry = 5 * 60 * 1000; // 5 minutes

        this.init();
    }

    init() {
        if (!this.searchInput) return;

        this.bindEvents();
        this.loadSearchSuggestions();
        this.handleURLHash(); // Check for candidate hash on page load
    }

    bindEvents() {
        if (this.searchInput) {
            this.searchInput.addEventListener('input', (e) => {
                this.handleSearchInput(e.target.value);
            });

            this.searchInput.addEventListener('keydown', (e) => {
                this.handleSearchKeydown(e);
            });

            this.searchInput.addEventListener('focus', () => {
                this.handleSearchFocus();
            });
        }

        if (this.searchToggleBtn) {
            this.searchToggleBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.openSearch();
            });
        }

        if (this.searchCloseBtn) {
            this.searchCloseBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.closeSearch();
            });
        }

        if (this.searchOverlay) {
            this.searchOverlay.addEventListener('click', (e) => {
                if (e.target === this.searchOverlay) {
                    this.closeSearch();
                }
            });
        }

        this.bindSearchSuggestions();

        // Listen for hash changes for modal auto-open
        window.addEventListener('hashchange', () => {
            this.handleURLHash();
        });
    }

    /**
     * Handle URL hash for auto-opening candidate modals
     */
    handleURLHash() {
        const hash = window.location.hash;
        if (hash.startsWith('#candidate-')) {
            const candidateId = hash.replace('#candidate-', '');
            if (candidateId && !isNaN(candidateId)) {
                // Small delay to ensure page is loaded
                setTimeout(() => {
                    this.openCandidateModal(parseInt(candidateId));
                }, 100);
            }
        }
    }

    /**
     * Open candidate modal automatically
     */
    async openCandidateModal(candidateId) {
        try {
            // Check if we're on a candidate page (central-panel or hall-panels)
            const currentPath = window.location.pathname;
            const isOnCandidatePage = currentPath.includes('/central-panel') || currentPath.includes('/hall-panels');

            if (!isOnCandidatePage) {
                // If not on candidate page, we can't open modal
                console.log('Not on candidate page, cannot open modal');
                return;
            }

            // Check if modal elements exist
            const modal = document.getElementById('candidate-modal');
            const modalContent = document.querySelector('.candidate-modal-content');

            if (!modal || !modalContent) {
                console.log('Modal elements not found');
                return;
            }

            // Show loading spinner
            const loadingSpinner = document.getElementById('loading-spinner');
            if (loadingSpinner) {
                loadingSpinner.classList.remove('hidden');
            }

            // Make AJAX request to get candidate details
            const formData = new FormData();
            formData.append('action', 'get_candidate_details');
            formData.append('candidate_id', candidateId);
            formData.append('nonce', window.ducsu_ajax.nonce);

            const response = await fetch(window.ducsu_ajax.ajax_url, {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                // Populate modal with candidate data
                this.populateCandidateModal(data.data);

                // Show modal
                modal.classList.remove('hidden');

                // Bind modal close events
                this.bindModalCloseEvents(modal);
            } else {
                console.error('Failed to load candidate details:', data.data);
                this.showNotification('প্রার্থীর তথ্য লোড করতে সমস্যা হয়েছে', 'error');
            }

        } catch (error) {
            console.error('Error opening candidate modal:', error);
            this.showNotification('প্রার্থীর তথ্য লোড করতে সমস্যা হয়েছে', 'error');
        } finally {
            // Hide loading spinner
            const loadingSpinner = document.getElementById('loading-spinner');
            if (loadingSpinner) {
                loadingSpinner.classList.add('hidden');
            }
        }
    }

    /**
     * Populate candidate modal with data
     */
    populateCandidateModal(candidateData) {
        const modalContent = document.querySelector('.candidate-modal-content');
        if (!modalContent) return;

        const {
            id,
            title,
            content,
            image,
            name_bangla,
            position,
            ballot_number,
            department,
            hall,
            session,
            father_name,
            father_profession,
            mother_name,
            mother_profession,
            permanent_address,
            special_achievements,
            political_journey,
            vision,
            facebook_url,
            twitter_url,
            email,
            ssc_school,
            ssc_gpa,
            ssc_year,
            hsc_college,
            hsc_gpa,
            hsc_year,
            graduation_university,
            graduation_cgpa,
            graduation_year,
            graduation_subject,
            gallery
        } = candidateData;

        let modalHTML = `
            <div class="flex flex-col lg:grid lg:grid-cols-3 gap-8 max-h-[80vh] overflow-y-auto">
                <!-- Left Column - Image and Basic Info -->
                <div class="lg:col-span-1">
                    <div class="sticky top-8">
                        ${image ? `
                            <img src="${image}" alt="${name_bangla || title}" 
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
                            <h2 class="text-2xl font-bold text-gray-800 mb-4">${name_bangla || title}</h2>
                            
                            ${position ? `<div class="mb-3">
                                <span class="text-sm font-medium text-gray-600">পদ:</span>
                                <span class="ml-2 px-3 py-1 bg-primary-green text-white rounded-full text-sm">${position}</span>
                            </div>` : ''}
                            
                            ${ballot_number ? `<div class="mb-3">
                                <span class="text-sm font-medium text-gray-600">ব্যালট নম্বর:</span>
                                <span class="ml-2 text-2xl font-bold text-primary-blue">${ballot_number}</span>
                            </div>` : ''}
                            
                            ${department ? `<div class="mb-3">
                                <span class="text-sm font-medium text-gray-600">বিভাগ:</span>
                                <span class="ml-2">${department}</span>
                            </div>` : ''}
                            
                            ${hall ? `<div class="mb-3">
                                <span class="text-sm font-medium text-gray-600">হল:</span>
                                <span class="ml-2">${hall}</span>
                            </div>` : ''}
                            
                            ${session ? `<div class="mb-3">
                                <span class="text-sm font-medium text-gray-600">সেশন:</span>
                                <span class="ml-2">${session}</span>
                            </div>` : ''}
                        </div>

                        <!-- Social Links -->
                        ${facebook_url || twitter_url || email ? `
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">যোগাযোগ</h3>
                                <div class="space-y-2">
                                    ${facebook_url ? `<a href="${facebook_url}" target="_blank" class="flex items-center text-blue-600 hover:text-blue-800 transition-colors">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                        </svg>
                                        Facebook
                                    </a>` : ''}
                                    
                                    ${twitter_url ? `<a href="${twitter_url}" target="_blank" class="flex items-center text-blue-400 hover:text-blue-600 transition-colors">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                        </svg>
                                        Twitter
                                    </a>` : ''}
                                    
                                    ${email ? `<a href="mailto:${email}" class="flex items-center text-gray-600 hover:text-gray-800 transition-colors">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                        </svg>
                                        ইমেইল
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
                                ${father_name ? `<div>
                                    <span class="font-medium text-gray-700">পিতার নাম:</span>
                                    <span class="ml-2">${father_name}</span>
                                    ${father_profession ? ` (${father_profession})` : ''}
                                </div>` : ''}
                                
                                ${mother_name ? `<div>
                                    <span class="font-medium text-gray-700">মাতার নাম:</span>
                                    <span class="ml-2">${mother_name}</span>
                                    ${mother_profession ? ` (${mother_profession})` : ''}
                                </div>` : ''}
                                
                                ${permanent_address ? `<div>
                                    <span class="font-medium text-gray-700">স্থায়ী ঠিকানা:</span>
                                    <span class="ml-2">${permanent_address}</span>
                                </div>` : ''}
                            </div>
                        </div>

                        <!-- Educational Information -->
                        ${(ssc_school || hsc_college || graduation_university) ? `
                            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                                <div class="bg-primary-blue text-white px-6 py-4">
                                    <h3 class="text-xl font-semibold">শিক্ষাগত যোগ্যতা</h3>
                                </div>
                                <div class="p-6 space-y-6">
                                    ${ssc_school ? `<div>
                                        <h4 class="font-semibold text-gray-800 mb-2">এসএসসি</h4>
                                        <div class="bg-gray-50 p-4 rounded">
                                            <p><strong>প্রতিষ্ঠান:</strong> ${ssc_school}</p>
                                            ${ssc_gpa ? `<p><strong>জিপিএ:</strong> ${ssc_gpa}</p>` : ''}
                                            ${ssc_year ? `<p><strong>পাশের বছর:</strong> ${ssc_year}</p>` : ''}
                                        </div>
                                    </div>` : ''}
                                    
                                    ${hsc_college ? `<div>
                                        <h4 class="font-semibold text-gray-800 mb-2">এইচএসসি</h4>
                                        <div class="bg-gray-50 p-4 rounded">
                                            <p><strong>প্রতিষ্ঠান:</strong> ${hsc_college}</p>
                                            ${hsc_gpa ? `<p><strong>জিপিএ:</strong> ${hsc_gpa}</p>` : ''}
                                            ${hsc_year ? `<p><strong>পাশের বছর:</strong> ${hsc_year}</p>` : ''}
                                        </div>
                                    </div>` : ''}
                                    
                                    ${graduation_university ? `<div>
                                        <h4 class="font-semibold text-gray-800 mb-2">স্নাতক</h4>
                                        <div class="bg-gray-50 p-4 rounded">
                                            <p><strong>বিশ্ববিদ্যালয়:</strong> ${graduation_university}</p>
                                            ${graduation_subject ? `<p><strong>বিষয়:</strong> ${graduation_subject}</p>` : ''}
                                            ${graduation_cgpa ? `<p><strong>সিজিপিএ:</strong> ${graduation_cgpa}</p>` : ''}
                                            ${graduation_year ? `<p><strong>পাশের বছর:</strong> ${graduation_year}</p>` : ''}
                                        </div>
                                    </div>` : ''}
                                </div>
                            </div>
                        ` : ''}

                        <!-- Vision and Achievements -->
                        ${(vision || special_achievements || political_journey) ? `
                            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                                <div class="bg-primary-red text-white px-6 py-4">
                                    <h3 class="text-xl font-semibold">ভবিষ্যৎ পরিকল্পনা ও অভিজ্ঞতা</h3>
                                </div>
                                <div class="p-6 space-y-6">
                                    ${vision ? `<div>
                                        <h4 class="font-semibold text-gray-800 mb-3">দৃষ্টিভঙ্গি ও পরিকল্পনা</h4>
                                        <div class="prose max-w-none text-gray-700">
                                            ${vision.replace(/\n/g, '<br>')}
                                        </div>
                                    </div>` : ''}
                                    
                                    ${special_achievements ? `<div>
                                        <h4 class="font-semibold text-gray-800 mb-3">বিশেষ অর্জন</h4>
                                        <div class="prose max-w-none text-gray-700">
                                            ${special_achievements.replace(/\n/g, '<br>')}
                                        </div>
                                    </div>` : ''}
                                    
                                    ${political_journey ? `<div>
                                        <h4 class="font-semibold text-gray-800 mb-3">রাজনৈতিক অভিজ্ঞতা</h4>
                                        <div class="prose max-w-none text-gray-700">
                                            ${political_journey.replace(/\n/g, '<br>')}
                                        </div>
                                    </div>` : ''}
                                </div>
                            </div>
                        ` : ''}

                        <!-- Gallery -->
                        ${gallery && gallery.length > 0 ? `
                            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                                <div class="bg-gray-700 text-white px-6 py-4">
                                    <h3 class="text-xl font-semibold">গ্যালারি</h3>
                                </div>
                                <div class="p-6">
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                        ${gallery.map(img => `
                                            <img src="${img.url}" alt="Gallery Image" 
                                                 class="w-full h-32 object-cover rounded-lg cursor-pointer hover:opacity-75 transition-opacity"
                                                 data-full-url="${img.full_url}">
                                        `).join('')}
                                    </div>
                                </div>
                            </div>
                        ` : ''}

                        <!-- Content/Bio -->
                        ${content ? `
                            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                                <div class="bg-gray-600 text-white px-6 py-4">
                                    <h3 class="text-xl font-semibold">বিস্তারিত</h3>
                                </div>
                                <div class="p-6">
                                    <div class="prose max-w-none text-gray-700">
                                        ${content}
                                    </div>
                                </div>
                            </div>
                        ` : ''}
                    </div>
                </div>
            </div>
        `;

        modalContent.innerHTML = modalHTML;
    }

    /**
     * Bind modal close events
     */
    bindModalCloseEvents(modal) {
        const closeBtn = modal.querySelector('#close-modal');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                this.closeCandidateModal(modal);
            });
        }

        // Close on escape key
        const escapeHandler = (e) => {
            if (e.key === 'Escape') {
                this.closeCandidateModal(modal);
                document.removeEventListener('keydown', escapeHandler);
            }
        };
        document.addEventListener('keydown', escapeHandler);

        // Close on backdrop click
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                this.closeCandidateModal(modal);
            }
        });
    }

    /**
     * Close candidate modal
     */
    closeCandidateModal(modal) {
        modal.classList.add('hidden');
        // Remove hash from URL
        if (window.location.hash.startsWith('#candidate-')) {
            history.replaceState(null, null, window.location.pathname + window.location.search);
        }
    }

    /**
     * Show notification
     */
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

    handleSearchInput(query) {
        if (this.debounceTimer) {
            clearTimeout(this.debounceTimer);
        }

        this.debounceTimer = setTimeout(() => {
            this.performSearch(query);
        }, 300);
    }

    handleSearchKeydown(e) {
        const results = this.searchResults?.querySelectorAll('a');

        switch (e.key) {
            case 'Escape':
                this.closeSearch();
                break;
            case 'ArrowDown':
                e.preventDefault();
                this.navigateResults(results, 'down');
                break;
            case 'ArrowUp':
                e.preventDefault();
                this.navigateResults(results, 'up');
                break;
            case 'Enter':
                e.preventDefault();
                this.selectFocusedResult(results);
                break;
        }
    }

    handleSearchFocus() {
        const query = this.searchInput.value.trim();
        if (query.length >= 2) {
            this.performSearch(query);
        } else {
            this.showSearchSuggestions();
        }
    }

    async performSearch(query) {
        if (!query || query.length < 2) {
            this.displayNoResults();
            return;
        }

        const cachedResult = this.getCachedResult(query);
        if (cachedResult) {
            this.displaySearchResults(cachedResult);
            return;
        }

        if (this.currentRequest) {
            this.currentRequest.abort();
        }

        this.showLoadingState();

        try {
            const controller = new AbortController();
            this.currentRequest = controller;

            const formData = new FormData();
            formData.append('action', 'ducsu_search');
            formData.append('query', query);
            formData.append('nonce', window.ducsu_ajax.nonce);

            const response = await fetch(window.ducsu_ajax.ajax_url, {
                method: 'POST',
                body: formData,
                signal: controller.signal
            });

            const data = await response.json();

            if (data.success) {
                this.cacheResult(query, data.data);
                this.displaySearchResults(data.data);
            } else {
                this.displayError(data.data || 'Search failed');
            }
        } catch (error) {
            if (error.name !== 'AbortError') {
                console.error('Search error:', error);
                this.displayError('অনুসন্ধানে সমস্যা হয়েছে');
            }
        } finally {
            this.currentRequest = null;
        }
    }

    displaySearchResults(results) {
        if (!this.searchResults) return;

        if (results.length === 0) {
            this.displayNoResults();
            return;
        }

        let html = '<div class="space-y-2">';
        results.forEach((result, index) => {
            // Enhanced result display with special handling for candidates
            const resultClass = result.is_candidate ?
                'search-result-item candidate-result block p-4 bg-white rounded-lg shadow hover:shadow-md transition-shadow duration-300' :
                'search-result-item block p-4 bg-white rounded-lg shadow hover:shadow-md transition-shadow duration-300';

            html += `
                <a href="${result.link}" 
                   class="${resultClass}"
                   data-index="${index}"
                   ${result.is_candidate ? `data-candidate-id="${result.candidate_id}"` : ''}
                   ${result.parent_url ? `data-parent-url="${result.parent_url}"` : ''}>
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <h3 class="font-medium text-gray-800 truncate">${this.highlightQuery(result.title)}</h3>
                            <p class="text-sm text-gray-600">${result.type}</p>
                            ${result.excerpt ? `<p class="text-sm text-gray-500 mt-1 line-clamp-2">${this.highlightQuery(result.excerpt)}</p>` : ''}
                            ${result.is_candidate ? '<p class="text-xs text-primary-green mt-1">ক্লিক করে বিস্তারিত দেখুন</p>' : ''}
                        </div>
                        <svg class="w-5 h-5 text-gray-400 flex-shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </a>
            `;
        });
        html += '</div>';

        this.searchResults.innerHTML = html;
        this.bindResultEvents();
    }

    displayNoResults() {
        if (!this.searchResults) return;

        this.searchResults.innerHTML = `
            <div class="text-center py-8">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <p class="text-gray-600">কোনো ফলাফল পাওয়া যায়নি</p>
                <p class="text-sm text-gray-500 mt-2">অন্য কিছু দিয়ে খোঁজ করে দেখুন</p>
                <div class="mt-4 text-xs text-gray-400">
                    <p>আপনি খোঁজ করতে পারেন:</p>
                    <p>• প্রার্থীর নাম • পিতা/মাতার নাম • বিভাগ • হল • ব্যালট নম্বর</p>
                </div>
            </div>
        `;
    }

    displayError(message = 'অনুসন্ধানে সমস্যা হয়েছে') {
        if (!this.searchResults) return;

        this.searchResults.innerHTML = `
            <div class="text-center py-8">
                <svg class="w-12 h-12 text-red-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                <p class="text-red-600">${message}</p>
                <button class="text-sm text-primary-green hover:text-primary-blue mt-2" onclick="this.closest('#search-results').innerHTML = ''">
                    আবার চেষ্টা করুন
                </button>
            </div>
        `;
    }

    showLoadingState() {
        if (!this.searchResults) return;

        this.searchResults.innerHTML = `
            <div class="text-center py-8">
                <div class="loading-spinner w-8 h-8 border-2 border-gray-300 border-t-primary-green rounded-full mx-auto mb-4"></div>
                <p class="text-gray-600">খোঁজা হচ্ছে...</p>
            </div>
        `;
    }

    showSearchSuggestions() {
        if (!this.searchResults) return;

        const suggestions = [
            'কেন্দ্রীয় প্যানেল',
            'হল প্যানেল',
            'ইশতিহার',
            'প্রার্থী',
            'নির্বাচন'
        ];

        let html = '<div class="py-4"><p class="text-gray-600 text-sm mb-4">জনপ্রিয় অনুসন্ধান:</p><div class="flex flex-wrap gap-2">';

        suggestions.forEach(suggestion => {
            html += '<button class="search-suggestion px-3 py-1 bg-gray-100 hover:bg-primary-green hover:text-white rounded-full text-sm transition-colors duration-300" data-query="' + suggestion + '">' + suggestion + '</button>';
        });

        html += '</div></div>';
        this.searchResults.innerHTML = html;

        this.bindSearchSuggestions();
    }

    loadSearchSuggestions() {
        const stored = localStorage.getItem('ducsu_search_suggestions');
        if (stored) {
            try {
                this.suggestions = JSON.parse(stored);
            } catch (e) {
                console.warn('Failed to load search suggestions');
            }
        }
    }

    bindSearchSuggestions() {
        const suggestions = this.searchResults?.querySelectorAll('.search-suggestion');
        suggestions?.forEach(suggestion => {
            suggestion.addEventListener('click', (e) => {
                e.preventDefault();
                const query = suggestion.getAttribute('data-query');
                if (query && this.searchInput) {
                    this.searchInput.value = query;
                    this.performSearch(query);
                    this.searchInput.focus();
                }
            });
        });
    }

    bindResultEvents() {
        const results = this.searchResults?.querySelectorAll('.search-result-item');
        results?.forEach(result => {
            result.addEventListener('click', (e) => {
                e.preventDefault();

                const candidateId = result.getAttribute('data-candidate-id');
                const parentUrl = result.getAttribute('data-parent-url');

                if (candidateId && parentUrl) {
                    // Handle candidate result - redirect with hash
                    const targetUrl = parentUrl + '#candidate-' + candidateId;

                    // Check if we're already on the target page
                    const currentPath = window.location.pathname + window.location.search;
                    const targetPath = new URL(parentUrl, window.location.origin).pathname + new URL(parentUrl, window.location.origin).search;

                    if (currentPath === targetPath) {
                        // Same page, just update hash and trigger modal
                        window.location.hash = '#candidate-' + candidateId;
                        this.handleURLHash();
                    } else {
                        // Different page, redirect
                        window.location.href = targetUrl;
                    }
                } else {
                    // Regular result, just follow the link
                    window.location.href = result.getAttribute('href');
                }

                this.closeSearch();
            });
        });
    }

    navigateResults(results, direction) {
        if (!results?.length) return;

        const currentFocus = document.activeElement;
        const currentIndex = Array.from(results).findIndex(result => result === currentFocus);

        let nextIndex;
        if (direction === 'down') {
            nextIndex = currentIndex < 0 ? 0 : (currentIndex + 1) % results.length;
        } else {
            nextIndex = currentIndex <= 0 ? results.length - 1 : currentIndex - 1;
        }

        results[nextIndex]?.focus();
    }

    selectFocusedResult(results) {
        const focused = document.activeElement;
        if (focused && Array.from(results).includes(focused)) {
            focused.click();
        } else if (results?.length > 0) {
            results[0].click();
        }
    }

    highlightQuery(text) {
        if (!this.searchInput?.value) return text;

        const query = this.searchInput.value.trim();
        if (!query) return text;

        const regex = new RegExp(`(${this.escapeRegExp(query)})`, 'gi');
        return text.replace(regex, '<mark class="bg-yellow-200 px-1 rounded">$1</mark>');
    }

    escapeRegExp(string) {
        return string.replace(/[.*+?^${}()|[\]\\]/g, '\\        suggestions.');
    }

    cacheResult(query, results) {
        this.cache.set(query.toLowerCase(), {
            results,
            timestamp: Date.now()
        });

        this.cleanCache();
    }

    getCachedResult(query) {
        const cached = this.cache.get(query.toLowerCase());
        if (!cached) return null;

        const isExpired = Date.now() - cached.timestamp > this.cacheExpiry;
        if (isExpired) {
            this.cache.delete(query.toLowerCase());
            return null;
        }

        return cached.results;
    }

    cleanCache() {
        const now = Date.now();
        for (const [key, value] of this.cache.entries()) {
            if (now - value.timestamp > this.cacheExpiry) {
                this.cache.delete(key);
            }
        }
    }

    openSearch() {
        if (this.searchOverlay) {
            this.searchOverlay.classList.remove('hidden');
            if (this.searchInput) {
                setTimeout(() => {
                    this.searchInput.focus();
                    this.showSearchSuggestions();
                }, 100);
            }
        }
    }

    closeSearch() {
        if (this.searchOverlay) {
            this.searchOverlay.classList.add('hidden');
        }
        if (this.searchInput) {
            this.searchInput.value = '';
        }
        if (this.searchResults) {
            this.searchResults.innerHTML = '';
        }

        if (this.currentRequest) {
            this.currentRequest.abort();
            this.currentRequest = null;
        }

        if (this.debounceTimer) {
            clearTimeout(this.debounceTimer);
            this.debounceTimer = null;
        }
    }

    handleEscape() {
        this.closeSearch();
    }

    getSearchQuery() {
        return this.searchInput?.value || '';
    }

    setSearchQuery(query) {
        if (this.searchInput) {
            this.searchInput.value = query;
            this.performSearch(query);
        }
    }

    clearCacheMethod() {
        this.cache.clear();
    }

    destroy() {
        if (this.currentRequest) {
            this.currentRequest.abort();
        }

        if (this.debounceTimer) {
            clearTimeout(this.debounceTimer);
        }

        this.clearCacheMethod();
    }
}