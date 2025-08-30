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

        if (in_array($hook, ['post-new.php', 'post.php']) &&
            in_array($post_type, ['central_candidate', 'hall_candidate'])) {
            wp_enqueue_media();
            wp_enqueue_script('jquery');
        }

        if ($hook === 'term.php' && $taxonomy === 'halls') {
            wp_enqueue_media();
            wp_enqueue_script('jquery');
        }
    }
}