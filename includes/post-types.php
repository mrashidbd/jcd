<?php
/**
 * Custom Post Types Registration
 */

if (!defined('ABSPATH')) {
    exit;
}

class DUCSU_Post_Types {

    public function __construct() {
        add_action('init', [$this, 'register_post_types']);
    }

    /**
     * Register all custom post types
     */
    public function register_post_types() {
        $this->register_sliders();
        $this->register_central_candidates();
        $this->register_hall_candidates();
        $this->register_manifesto();
    }

    /**
     * Register Sliders post type
     */
    private function register_sliders() {
        register_post_type('slider', [
            'labels' => [
                'name' => __('Sliders', 'ducsu-jcd'),
                'singular_name' => __('Slider', 'ducsu-jcd')
            ],
            'public' => true,
            'menu_icon' => 'dashicons-images-alt2',
            'supports' => ['title', 'thumbnail'], // thumbnail = featured image
            'show_in_rest' => true,
        ]);
    }

    /**
     * Register Central Candidates post type
     */
    private function register_central_candidates() {
        register_post_type('central_candidate', [
            'labels' => [
                'name' => __('Central Panel Candidates', 'ducsu-jcd'),
                'singular_name' => __('Central Candidate', 'ducsu-jcd')
            ],
            'public' => true,
            'menu_icon' => 'dashicons-groups',
            'supports' => ['title', 'thumbnail', 'editor'], // thumbnail = featured image
            'show_in_rest' => true,
            'has_archive' => true,
            'rewrite' => ['slug' => 'central-candidate']
        ]);
    }

    /**
     * Register Hall Candidates post type
     */
    private function register_hall_candidates() {
        register_post_type('hall_candidate', [
            'labels' => [
                'name' => __('Hall Panel Candidates', 'ducsu-jcd'),
                'singular_name' => __('Hall Candidate', 'ducsu-jcd')
            ],
            'public' => true,
            'menu_icon' => 'dashicons-building',
            'supports' => ['title', 'thumbnail', 'editor'], // thumbnail = featured image
            'taxonomies' => ['halls'],
            'show_in_rest' => true,
            'has_archive' => true,
            'rewrite' => ['slug' => 'hall-candidate']
        ]);
    }

    /**
     * Register Manifesto post type
     */
    private function register_manifesto() {
        register_post_type('manifesto', [
            'labels' => [
                'name' => __('Manifesto Items', 'ducsu-jcd'),
                'singular_name' => __('Manifesto', 'ducsu-jcd')
            ],
            'public' => true,
            'menu_icon' => 'dashicons-yes',
            'supports' => ['title', 'editor', 'thumbnail'], // Added thumbnail support
            'show_in_rest' => true,
        ]);
    }
}