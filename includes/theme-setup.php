<?php
/**
 * Theme Setup and Configuration
 */

if (!defined('ABSPATH')) {
    exit;
}

class DUCSU_Theme_Setup {

    public function __construct() {
        // Call setup immediately in constructor
        $this->theme_setup();
        add_action('init', [$this, 'register_menus']);
    }

    /**
     * Setup theme features and support
     */
    public function theme_setup() {
        // Enable theme support
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails'); // This should work now
        add_theme_support('custom-logo', [
            'height' => '80px',
            'width' => 'auto',
            'flex-height' => true,
            'flex-width' => true,
        ]);
        add_theme_support('html5', [
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption'
        ]);

        // Set content width
        $GLOBALS['content_width'] = 1200;
    }

    /**
     * Register navigation menus
     */
    public function register_menus() {
        register_nav_menus([
            'primary_menu' => __('Primary Menu', 'ducsu-jcd'),
            'footer_menu' => __('Footer Menu', 'ducsu-jcd')
        ]);
    }
}