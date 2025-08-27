<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <?php wp_head(); ?>
</head>
<body <?php body_class('bg-gray-100 antialiased font-bangla'); ?>>

<!-- Header -->
<header class="site-header bg-white shadow-lg sticky top-0 z-40">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between py-4">

            <!-- Logo -->
            <div class="site-logo flex items-center space-x-3">
                <?php if (has_custom_logo()) : ?>
                    <?php the_custom_logo(); ?>
                <?php else : ?>
                    <div class="flex items-center space-x-3">
                        <div class="bg-gradient-to-br from-primary-green to-primary-blue rounded-full w-12 h-12 flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-gray-800">JCD DUCSU</h1>
                            <p class="text-xs text-gray-600">২০২৫ নির্বাচন</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Desktop Navigation -->
            <nav class="primary-navigation hidden lg:block">
                <?php
                wp_nav_menu([
                    'theme_location' => 'primary_menu',
                    'menu_class' => 'flex items-center space-x-8',
                    'container' => false,
                    'fallback_cb' => 'ducsu_jcd_fallback_menu',
                    'walker' => new Ducsu_Nav_Walker()
                ]);
                ?>
            </nav>

            <!-- Header Actions -->
            <div class="header-actions flex items-center space-x-4">

                <!-- Search Toggle -->
                <button id="search-toggle-btn" class="search-toggle text-gray-700 hover:text-primary-green transition-colors duration-300 p-2" type="button">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>

                <!-- Mobile Menu Toggle -->
                <button id="mobile-menu-toggle-btn" class="mobile-menu-toggle lg:hidden text-gray-700 hover:text-primary-green transition-colors duration-300 p-2" type="button">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation -->
    <div id="mobile-menu" class="mobile-menu hidden lg:hidden bg-white border-t border-gray-200">
        <div class="container mx-auto px-4 py-4">
            <?php
            wp_nav_menu([
                'theme_location' => 'primary_menu',
                'menu_class' => 'mobile-nav-list space-y-2',
                'container' => false,
                'fallback_cb' => 'ducsu_jcd_mobile_fallback_menu'
            ]);
            ?>
        </div>
    </div>
</header>

<!-- Search Overlay -->
<div id="search-overlay" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-start justify-center pt-20">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4">
        <div class="p-6">
            <div class="flex items-center space-x-4 mb-6">
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input type="text" id="search-input"
                       placeholder="প্রার্থী, হল বা ইশতেহার খুঁজুন..."
                       class="flex-1 text-lg border-none outline-none">
                <button id="search-close-btn" class="search-close text-gray-400 hover:text-gray-600 transition-colors" type="button">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Search Results -->
            <div id="search-results" class="max-h-96 overflow-y-auto">
                <!-- Results will be populated via AJAX -->
            </div>

            <!-- Search Suggestions -->
            <div class="border-t border-gray-200 pt-4 mt-4">
                <p class="text-sm text-gray-500 mb-3">দ্রুত অনুসন্ধান:</p>
                <div class="flex flex-wrap gap-2">
                    <button class="search-suggestion bg-gray-100 hover:bg-primary-green hover:text-white text-gray-700 px-3 py-1 rounded-full text-sm transition-colors" data-query="ভিপি" type="button">ভিপি</button>
                    <button class="search-suggestion bg-gray-100 hover:bg-primary-green hover:text-white text-gray-700 px-3 py-1 rounded-full text-sm transition-colors" data-query="জিএস" type="button">জিএস</button>
                    <button class="search-suggestion bg-gray-100 hover:bg-primary-green hover:text-white text-gray-700 px-3 py-1 rounded-full text-sm transition-colors" data-query="শহীদুল্লাহ হল" type="button">শহীদুল্লাহ হল</button>
                    <button class="search-suggestion bg-gray-100 hover:bg-primary-green hover:text-white text-gray-700 px-3 py-1 rounded-full text-sm transition-colors" data-query="রোকেয়া হল" type="button">রোকেয়া হল</button>
                    <button class="search-suggestion bg-gray-100 hover:bg-primary-green hover:text-white text-gray-700 px-3 py-1 rounded-full text-sm transition-colors" data-query="শিক্ষা" type="button">শিক্ষা</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mobile Menu Overlay -->
<div id="mobile-menu-overlay" class="mobile-menu-overlay fixed inset-0 z-30 hidden bg-black bg-opacity-50 lg:hidden"></div>

<?php
/**
 * Custom Navigation Walker for Desktop Menu
 */
class Ducsu_Nav_Walker extends Walker_Nav_Menu
{
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
    {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';

        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';

        $indent = ($depth) ? str_repeat("\t", $depth) : '';

        $output .= $indent . '<li' . $id . $class_names . '>';

        $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
        $attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
        $attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
        $attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';

        $item_output = isset($args->before) ? $args->before : '';
        $item_output .= '<a' . $attributes . ' class="text-gray-700 hover:text-primary-green font-medium transition-colors duration-300 py-2 px-4 rounded-lg hover:bg-gray-50">';
        $item_output .= (isset($args->link_before) ? $args->link_before : '') . apply_filters('the_title', $item->title, $item->ID) . (isset($args->link_after) ? $args->link_after : '');
        $item_output .= '</a>';
        $item_output .= isset($args->after) ? $args->after : '';

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
}

/**
 * Fallback Menu for Desktop
 */
function ducsu_jcd_fallback_menu()
{
    echo '<ul class="flex items-center space-x-8">';
    echo '<li><a href="/" class="text-gray-700 hover:text-primary-green font-medium transition-colors duration-300 py-2 px-4 rounded-lg hover:bg-gray-50">হোম</a></li>';
    echo '<li><a href="/central-panel" class="text-gray-700 hover:text-primary-green font-medium transition-colors duration-300 py-2 px-4 rounded-lg hover:bg-gray-50">কেন্দ্রীয় প্যানেল</a></li>';
    echo '<li><a href="/hall-panels" class="text-gray-700 hover:text-primary-green font-medium transition-colors duration-300 py-2 px-4 rounded-lg hover:bg-gray-50">হল প্যানেল</a></li>';
    echo '<li><a href="/manifesto" class="text-gray-700 hover:text-primary-green font-medium transition-colors duration-300 py-2 px-4 rounded-lg hover:bg-gray-50">ইশতেহার</a></li>';
    echo '<li><a href="/contact" class="text-gray-700 hover:text-primary-green font-medium transition-colors duration-300 py-2 px-4 rounded-lg hover:bg-gray-50">যোগাযোগ</a></li>';
    echo '</ul>';
}

/**
 * Fallback Menu for Mobile
 */
function ducsu_jcd_mobile_fallback_menu()
{
    echo '<ul class="mobile-nav-list space-y-2">';
    echo '<li><a href="/" class="block text-gray-700 hover:text-primary-green font-medium transition-colors duration-300 py-3 px-4 rounded-lg hover:bg-gray-50">হোম</a></li>';
    echo '<li><a href="/central-panel" class="block text-gray-700 hover:text-primary-green font-medium transition-colors duration-300 py-3 px-4 rounded-lg hover:bg-gray-50">কেন্দ্রীয় প্যানেল</a></li>';
    echo '<li><a href="/hall-panels" class="block text-gray-700 hover:text-primary-green font-medium transition-colors duration-300 py-3 px-4 rounded-lg hover:bg-gray-50">হল প্যানেল</a></li>';
    echo '<li><a href="/manifesto" class="block text-gray-700 hover:text-primary-green font-medium transition-colors duration-300 py-3 px-4 rounded-lg hover:bg-gray-50">ইশতেহার</a></li>';
    echo '<li><a href="/contact" class="block text-gray-700 hover:text-primary-green font-medium transition-colors duration-300 py-3 px-4 rounded-lg hover:bg-gray-50">যোগাযোগ</a></li>';
    echo '</ul>';
}

// Initialize header functionality with proper event delegation
?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Search functionality with proper event binding
        const searchToggleBtn = document.getElementById('search-toggle-btn');
        const searchOverlay = document.getElementById('search-overlay');
        const searchInput = document.getElementById('search-input');
        const searchCloseBtn = document.getElementById('search-close-btn');
        const searchSuggestions = document.querySelectorAll('.search-suggestion');

        // Mobile menu functionality with proper event binding
        const mobileMenuToggleBtn = document.getElementById('mobile-menu-toggle-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');

        // Search toggle functionality
        if (searchToggleBtn && searchOverlay) {
            searchToggleBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                searchOverlay.classList.remove('hidden');
                if (searchInput) {
                    setTimeout(() => searchInput.focus(), 100);
                }
            });
        }

        // Search close functionality
        if (searchCloseBtn && searchOverlay) {
            searchCloseBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                closeSearchOverlay();
            });
        }

        // Close search on overlay click
        if (searchOverlay) {
            searchOverlay.addEventListener('click', function(e) {
                if (e.target === searchOverlay) {
                    closeSearchOverlay();
                }
            });
        }

        // Search suggestions functionality
        if (searchSuggestions && searchInput) {
            searchSuggestions.forEach(suggestion => {
                suggestion.addEventListener('click', function(e) {
                    e.preventDefault();
                    const query = this.getAttribute('data-query');
                    if (query) {
                        searchInput.value = query;
                        // Trigger search
                        searchInput.dispatchEvent(new Event('input', { bubbles: true }));
                    }
                });
            });
        }

        // Mobile menu toggle functionality
        if (mobileMenuToggleBtn && mobileMenu && mobileMenuOverlay) {
            mobileMenuToggleBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                toggleMobileMenu();
            });
        }

        // Mobile menu overlay close
        if (mobileMenuOverlay && mobileMenu) {
            mobileMenuOverlay.addEventListener('click', function(e) {
                e.preventDefault();
                closeMobileMenu();
            });
        }

        // Close mobile menu when clicking on navigation links
        const mobileNavLinks = document.querySelectorAll('#mobile-menu a');
        if (mobileNavLinks.length > 0) {
            mobileNavLinks.forEach(link => {
                link.addEventListener('click', function() {
                    closeMobileMenu();
                });
            });
        }

        // Close search and mobile menu on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                if (searchOverlay && !searchOverlay.classList.contains('hidden')) {
                    closeSearchOverlay();
                }
                if (mobileMenu && !mobileMenu.classList.contains('hidden')) {
                    closeMobileMenu();
                }
            }
        });

        // Helper functions
        function closeSearchOverlay() {
            if (searchOverlay) {
                searchOverlay.classList.add('hidden');
            }
            if (searchInput) {
                searchInput.value = '';
            }
            const searchResults = document.getElementById('search-results');
            if (searchResults) {
                searchResults.innerHTML = '';
            }
        }

        function toggleMobileMenu() {
            if (mobileMenu && mobileMenuOverlay) {
                const isHidden = mobileMenu.classList.contains('hidden');

                if (isHidden) {
                    // Show mobile menu
                    mobileMenu.classList.remove('hidden');
                    mobileMenuOverlay.classList.remove('hidden');
                    // Change hamburger to X
                    updateMobileMenuIcon(true);
                } else {
                    // Hide mobile menu
                    closeMobileMenu();
                }
            }
        }

        function closeMobileMenu() {
            if (mobileMenu && mobileMenuOverlay) {
                mobileMenu.classList.add('hidden');
                mobileMenuOverlay.classList.add('hidden');
                // Change X back to hamburger
                updateMobileMenuIcon(false);
            }
        }

        function updateMobileMenuIcon(isOpen) {
            const icon = mobileMenuToggleBtn ? mobileMenuToggleBtn.querySelector('svg') : null;
            if (icon) {
                if (isOpen) {
                    // X icon
                    icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>';
                } else {
                    // Hamburger icon
                    icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>';
                }
            }
        }
    });
</script>

</body>
</html>