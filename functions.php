<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Theme Functions for DUCSU JCD WordPress Theme
 */

function mytheme_enqueue_assets() {
    $theme_uri = get_template_directory_uri();
    $theme_version = wp_get_theme()->get('Version');

    if (defined('WP_DEV_SERVER') && WP_DEV_SERVER) {
        // Enqueue Vite client script for HMR
        wp_enqueue_script('vite-client', 'http://localhost:5173/@vite/client', array(), null, true);

        // Enqueue main Vite script
        wp_enqueue_script('mytheme-main-js', 'http://localhost:5173/src/js/main.js', array(), null, true);

        // Enqueue main Vite stylesheet
        wp_enqueue_style('mytheme-main-css', 'http://localhost:5173/src/css/input.css', array(), null);

    } else {
        // Enqueue production assets from the dist folder using manifest.json
        $manifest_path = get_template_directory() . '/dist/manifest.json';
        if (file_exists($manifest_path)) {
            $manifest = json_decode(file_get_contents($manifest_path), true);
            $css_path = $manifest['src/css/input.css']['file'];
            $js_path = $manifest['src/js/main.js']['file'];

            wp_enqueue_style('mytheme-style', $theme_uri . '/dist/' . $css_path, array(), $theme_version, 'all');
            wp_enqueue_script('mytheme-script', $theme_uri . '/dist/' . $js_path, array(), $theme_version, true);
        }
    }
}
add_action('wp_enqueue_scripts', 'mytheme_enqueue_assets');


/**
 * Add "type=module" to the Vite client script tag.
 */
function jcd_ducsu_add_module_type_attribute($tag, $handle, $src) {
    if ('vite-client' === $handle) {
        // If the script is the Vite client, add the type="module" attribute.
        $tag = str_replace('<script', '<script type="module"', $tag);
    }
    return $tag;
}
add_filter('script_loader_tag', 'jcd_ducsu_add_module_type_attribute', 10, 3);


/**
 * Theme Setup
 */
function ducsu_jcd_theme_setup()
{
    // Enable theme support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', [
        'height' => 100,
        'width' => 300,
        'flex-height' => true,
        'flex-width' => true,
    ]);
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption']);

    // Register Main Menu
    register_nav_menus([
        'primary_menu' => __('Primary Menu', 'ducsu-jcd')
    ]);
}

add_action('after_setup_theme', 'ducsu_jcd_theme_setup');

/**
 * Enqueue Scripts & Styles (Vite + Tailwind)
 */
function ducsu_jcd_enqueue_assets()
{
    // Vite Dev Mode
    if (defined('WP_DEBUG') && WP_DEBUG) {
        wp_enqueue_script('ducsu-jcd-main-js', get_stylesheet_directory_uri() . '/dist/main.js', [], null, true);
        wp_enqueue_style('ducsu-jcd-main-css', get_stylesheet_directory_uri() . '/dist/main.css', [], null);
    } else {
        // Production Build
        wp_enqueue_script('ducsu-jcd-main-js', get_stylesheet_directory_uri() . '/dist/main.js', [], null, true);
        wp_enqueue_style('ducsu-jcd-main-css', get_stylesheet_directory_uri() . '/dist/main.css', [], null);
    }
}

add_action('wp_enqueue_scripts', 'ducsu_jcd_enqueue_assets');

/**
 * Register Custom Post Types & Taxonomies
 */
function ducsu_jcd_register_cpts()
{

    // ---------------- Sliders ----------------
    register_post_type('slider', [
        'labels' => [
            'name' => __('Sliders', 'ducsu-jcd'),
            'singular_name' => __('Slider', 'ducsu-jcd')
        ],
        'public' => true,
        'menu_icon' => 'dashicons-images-alt2',
        'supports' => ['title', 'thumbnail'],
    ]);

    // ---------------- Central Panel Candidates ----------------
    register_post_type('central_candidate', [
        'labels' => [
            'name' => __('Central Panel Candidates', 'ducsu-jcd'),
            'singular_name' => __('Central Candidate', 'ducsu-jcd')
        ],
        'public' => true,
        'menu_icon' => 'dashicons-groups',
        'supports' => ['title', 'thumbnail'],
    ]);

    // ---------------- Halls Taxonomy ----------------
    register_taxonomy('halls', 'hall_candidate', [
        'labels' => [
            'name' => __('Halls', 'ducsu-jcd'),
            'singular_name' => __('Hall', 'ducsu-jcd'),
        ],
        'hierarchical' => true,
        'public' => true,
        'show_admin_column' => true,
    ]);

    // ---------------- Hall Candidates ----------------
    register_post_type('hall_candidate', [
        'labels' => [
            'name' => __('Hall Panel Candidates', 'ducsu-jcd'),
            'singular_name' => __('Hall Candidate', 'ducsu-jcd')
        ],
        'public' => true,
        'menu_icon' => 'dashicons-building',
        'supports' => ['title', 'thumbnail'],
        'taxonomies' => ['halls'],
    ]);

    // ---------------- Manifesto ----------------
    register_post_type('manifesto', [
        'labels' => [
            'name' => __('Manifesto Items', 'ducsu-jcd'),
            'singular_name' => __('Manifesto', 'ducsu-jcd')
        ],
        'public' => true,
        'menu_icon' => 'dashicons-yes',
        'supports' => ['title', 'editor'],
    ]);

}

add_action('init', 'ducsu_jcd_register_cpts');

/**
 * Pre-populate Halls Taxonomy with Bangla Names (Run once)
 */
function ducsu_jcd_insert_default_halls()
{
    $halls = [
        'শহীদুল্লাহ হল', 'সালিমুল্লাহ মুসলিম হল', 'ফজলুল হক মুসলিম হল',
        'আবদুল্লাহ হল', 'মুক্তিযোদ্ধা জিয়াউর রহমান হল', 'অলিম্পিয়া হল',
        'শহীদ সার্জেন্ট জহুরুল হক হল', 'বঙ্গবন্ধু শেখ মুজিবুর রহমান হল',
        'নবাব ফয়জুন্নেসা হল', 'শামসুন্নাহার হল', 'রোকেয়া হল',
        'সাধনা হল', 'আফসার উদ্দিন হল', 'মির্জা আজিজুল হল',
        'ইকবাল হল', 'ফজিলাতুন নেছা হল', 'সুলতানা রাজিয়া হল',
        'প্রধানমন্ত্রী শেখ হাসিনা হল', 'বেগম সুফিয়া কামাল হল', 'বেগম ফজিলাতুন্নেছা মুজিব হল',
        'শহীদ জননী জাহানারা ইমাম হল', 'নূরজাহান হল', 'আলবেরুনি হল'
    ];
    foreach ($halls as $hall) {
        if (!term_exists($hall, 'halls')) {
            wp_insert_term($hall, 'halls');
        }
    }
}

add_action('init', 'ducsu_jcd_insert_default_halls');

/**
 * AJAX Search (Initial Placeholder)
 */
function ducsu_jcd_ajax_search()
{
    $query = sanitize_text_field($_GET['q'] ?? '');
    $args = [
        'post_type' => ['central_candidate', 'hall_candidate', 'manifesto'],
        's' => $query,
        'posts_per_page' => 10
    ];
    $results = new WP_Query($args);
    $output = [];
    while ($results->have_posts()) {
        $results->the_post();
        $output[] = [
            'title' => get_the_title(),
            'link' => get_permalink(),
            'type' => get_post_type()
        ];
    }
    wp_send_json($output);
}

add_action('wp_ajax_ducsu_jcd_search', 'ducsu_jcd_ajax_search');
add_action('wp_ajax_nopriv_ducsu_jcd_search', 'ducsu_jcd_ajax_search');
