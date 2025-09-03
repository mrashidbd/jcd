<?php
/**
 * Main Functions File for DUCSU JCD WordPress Theme
 * This file includes all feature-specific modules
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define theme constants
define('DUCSU_THEME_VERSION', '1.0.0');
define('DUCSU_THEME_PATH', get_template_directory());
define('DUCSU_THEME_URL', get_template_directory_uri());
define('DUCSU_INCLUDES_PATH', DUCSU_THEME_PATH . '/includes');

/**
 * Include all feature modules
 */
require_once DUCSU_INCLUDES_PATH . '/theme-setup.php';
require_once DUCSU_INCLUDES_PATH . '/enqueue-scripts.php';
require_once DUCSU_INCLUDES_PATH . '/post-types.php';
require_once DUCSU_INCLUDES_PATH . '/taxonomies.php';
require_once DUCSU_INCLUDES_PATH . '/meta-boxes.php';
require_once DUCSU_INCLUDES_PATH . '/ajax-handlers.php';
require_once DUCSU_INCLUDES_PATH . '/helper-functions.php';
require_once DUCSU_INCLUDES_PATH . '/admin-functions.php';
require_once DUCSU_INCLUDES_PATH . '/walker-classes.php';
require_once DUCSU_INCLUDES_PATH . '/meta-functions.php';

/**
 * Initialize theme features immediately
 */
function ducsu_jcd_init() {
    // Initialize all components
    new DUCSU_Theme_Setup();
    new DUCSU_Enqueue_Scripts();
    new DUCSU_Post_Types();
    new DUCSU_Taxonomies();
    new DUCSU_Meta_Boxes();
    new DUCSU_AJAX_Handlers();
    new DUCSU_Admin_Functions();
}

// Initialize theme - CHANGE THIS LINE
add_action('after_setup_theme', 'ducsu_jcd_init');

// Allow external resources for iframe content
function modify_csp_for_iframe() {
    if (is_page_template('page-iframe.php')) {
        header("Content-Security-Policy: frame-src 'self' https://ducsu.du.ac.bd; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://code.jquery.com; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://code.jquery.com https://ducsu.du.ac.bd;");
    }
}
add_action('send_headers', 'modify_csp_for_iframe');