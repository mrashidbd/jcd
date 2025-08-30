<?php
/**
 * Handle script and style enqueuing
 */

if (!defined('ABSPATH')) {
    exit;
}

class DUCSU_Enqueue_Scripts {

    public function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_filter('script_loader_tag', [$this, 'add_module_type_attribute'], 10, 3);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts']);
    }

    /**
     * Enqueue frontend scripts and styles
     */
    public function enqueue_assets() {
        $theme_uri = DUCSU_THEME_URL;
        $theme_version = DUCSU_THEME_VERSION;

        if (defined('WP_DEV_SERVER') && WP_DEV_SERVER) {
            // Development mode with Vite HMR
            wp_enqueue_script('vite-client', 'http://localhost:5173/@vite/client', [], null, true);
            wp_enqueue_script('mytheme-main-js', 'http://localhost:5173/src/js/main.js', [], null, true);
            wp_enqueue_style('mytheme-main-css', 'http://localhost:5173/src/css/input.css', [], null);
        } else {
            // Production mode
            $manifest_path = DUCSU_THEME_PATH . '/dist/manifest.json';
            if (file_exists($manifest_path)) {
                $manifest = json_decode(file_get_contents($manifest_path), true);
                $css_path = $manifest['src/css/input.css']['file'];
                $js_path = $manifest['src/js/main.js']['file'];

                wp_enqueue_style('mytheme-style', $theme_uri . '/dist/' . $css_path, [], $theme_version);
                wp_enqueue_script('mytheme-script', $theme_uri . '/dist/' . $js_path, [], $theme_version, true);
            }
        }

        // Always add AJAX data
        $this->localize_scripts();
    }

    /**
     * Localize scripts with AJAX data
     */
    private function localize_scripts() {
        $ajax_data = [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('ducsu_nonce'),
            'theme_url' => DUCSU_THEME_URL
        ];

        // For development mode
        if (defined('WP_DEV_SERVER') && WP_DEV_SERVER) {
            wp_add_inline_script('mytheme-main-js',
                'window.ducsu_ajax = ' . json_encode($ajax_data) . ';', 'before');
        }

        // For production mode
        if (wp_script_is('mytheme-script', 'enqueued')) {
            wp_add_inline_script('mytheme-script',
                'window.ducsu_ajax = ' . json_encode($ajax_data) . ';', 'before');
        }
    }

    /**
     * Add type="module" to Vite client script
     */
    public function add_module_type_attribute($tag, $handle, $src) {
        if ('vite-client' === $handle || 'mytheme-main-js' === $handle) {
            $tag = str_replace('<script', '<script type="module"', $tag);
        }
        return $tag;
    }

    /**
     * Enqueue admin scripts
     */
    public function enqueue_admin_scripts($hook) {
        global $post_type, $taxonomy;

        // Enqueue media scripts for post edit screens
        if (in_array($hook, ['post-new.php', 'post.php'])) {
            if (in_array($post_type, ['central_candidate', 'hall_candidate', 'slider', 'manifesto'])) {
                wp_enqueue_media();
                wp_enqueue_script('jquery');

                // Add custom admin CSS for better styling
                wp_add_inline_style('wp-admin', '
                    .gallery-image {
                        display: inline-block !important;
                        margin: 5px !important;
                        position: relative !important;
                        border: 1px solid #ddd !important;
                        padding: 5px !important;
                        background: #fff;
                        border-radius: 4px;
                    }
                    .gallery-image img {
                        display: block !important;
                        max-width: 100px !important;
                        max-height: 100px !important;
                        object-fit: cover;
                    }
                    .remove-image {
                        position: absolute !important;
                        top: -5px !important;
                        right: -5px !important;
                        background: #dc3232 !important;
                        color: white !important;
                        border: none !important;
                        border-radius: 50% !important;
                        width: 20px !important;
                        height: 20px !important;
                        font-size: 12px !important;
                        cursor: pointer !important;
                        line-height: 1 !important;
                    }
                    .remove-image:hover {
                        background: #a00 !important;
                    }
                    #candidate-gallery {
                        min-height: 50px;
                        padding: 10px;
                        border: 1px dashed #ccc;
                        background: #f9f9f9;
                        margin-bottom: 15px;
                    }
                    #candidate-gallery:empty:before {
                        content: "No images selected yet. Click the button below to add images.";
                        color: #666;
                        font-style: italic;
                    }
                ');
            }
        }

        // Enqueue for taxonomy edit screens
        if ($hook === 'term.php' && $taxonomy === 'halls') {
            wp_enqueue_media();
            wp_enqueue_script('jquery');
        }
    }
}