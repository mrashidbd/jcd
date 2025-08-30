/**
 * Search Handler Module
 * Manages search functionality and UI
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
            html += `
                <a href="${result.link}" 
                   class="search-result-item block p-4 bg-white rounded-lg shadow hover:shadow-md transition-shadow duration-300"
                   data-index="${index}">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <h3 class="font-medium text-gray-800 truncate">${this.highlightQuery(result.title)}</h3>
                            <p class="text-sm text-gray-600">${result.type}</p>
                            ${result.excerpt ? `<p class="text-sm text-gray-500 mt-1 line-clamp-2">${this.highlightQuery(result.excerpt)}</p>` : ''}
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
                <button class="text-sm text-primary-green hover:text-primary-blue mt-2" onclick="this.closest('.search-results').innerHTML = ''">
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
            result.addEventListener('click', () => {
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
        return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }

    showSearchSuggestions() {
        if (!this.searchResults) return;

        const suggestions = [
            'কেন্দ্রীয় প্যানেল',
            'হল প্যানেল',
        ];
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