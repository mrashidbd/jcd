<?php
/**
 * Meta Boxes for Custom Post Types
 */

if (!defined('ABSPATH')) {
    exit;
}

class DUCSU_Meta_Boxes {

    public function __construct() {
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_action('save_post', [$this, 'save_meta_boxes']);
    }

    /**
     * Add meta boxes for different post types
     */
    public function add_meta_boxes() {
        // Slider meta box
        add_meta_box(
            'slider_details',
            'Slider Details',
            [$this, 'slider_meta_box_callback'],
            'slider'
        );

        // Candidate meta box
        add_meta_box(
            'candidate_details',
            'Candidate Details',
            [$this, 'candidate_meta_box_callback'],
            ['central_candidate', 'hall_candidate']
        );

        // Featured candidate meta box
        add_meta_box(
            'featured_candidate',
            'Featured Candidate',
            [$this, 'featured_candidate_callback'],
            'central_candidate'
        );
    }

    /**
     * Slider meta box callback
     */
    public function slider_meta_box_callback($post) {
        wp_nonce_field('ducsu_slider_meta_nonce', 'slider_meta_nonce');

        $subtitle = get_post_meta($post->ID, '_slider_subtitle', true);
        $cta_text = get_post_meta($post->ID, '_slider_cta_text', true);
        $cta_url = get_post_meta($post->ID, '_slider_cta_url', true);

        echo '<table class="form-table">';
        echo '<tr><th><label for="slider_subtitle">Subtitle (Bangla)</label></th>';
        echo '<td><input type="text" id="slider_subtitle" name="slider_subtitle" value="' . esc_attr($subtitle) . '" class="widefat" /></td></tr>';
        echo '<tr><th><label for="slider_cta_text">CTA Text (Bangla)</label></th>';
        echo '<td><input type="text" id="slider_cta_text" name="slider_cta_text" value="' . esc_attr($cta_text) . '" class="widefat" /></td></tr>';
        echo '<tr><th><label for="slider_cta_url">CTA URL</label></th>';
        echo '<td><input type="url" id="slider_cta_url" name="slider_cta_url" value="' . esc_attr($cta_url) . '" class="widefat" /></td></tr>';
        echo '</table>';
    }

    /**
     * Candidate meta box callback with tabs
     */
    public function candidate_meta_box_callback($post) {
        wp_nonce_field('ducsu_candidate_meta_nonce', 'candidate_meta_nonce');

        // Include the candidate meta box template
        include DUCSU_THEME_PATH . '/includes/templates/candidate-meta-box.php';
    }

    /**
     * Featured candidate meta box callback
     */
    public function featured_candidate_callback($post) {
        wp_nonce_field('ducsu_featured_nonce', 'featured_nonce');
        $featured = get_post_meta($post->ID, '_candidate_featured', true);

        echo '<label>';
        echo '<input type="checkbox" name="candidate_featured" value="1" ' . checked($featured, '1', false) . ' />';
        echo ' Make this candidate featured on homepage';
        echo '</label>';
    }



    /**
     * Save meta box data
     */
    public function save_meta_boxes($post_id) {
        // Check permissions and nonces
        if (!$this->can_save_meta($post_id)) {
            return;
        }

        // Save different meta based on post type
        $post_type = get_post_type($post_id);

        switch ($post_type) {
            case 'slider':
                $this->save_slider_meta($post_id);
                break;
            case 'hall_candidate':
            case 'central_candidate':
                $this->save_candidate_meta($post_id);
                break;
        }
    }

    /**
     * Check if we can save meta
     */
    private function can_save_meta($post_id) {
        // Don't save if this is an autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return false;
        }

        // Check user permissions
        if (!current_user_can('edit_post', $post_id)) {
            return false;
        }

        return true;
    }

    /**
     * Save slider meta
     */
    private function save_slider_meta($post_id) {
        if (!isset($_POST['slider_meta_nonce']) ||
            !wp_verify_nonce($_POST['slider_meta_nonce'], 'ducsu_slider_meta_nonce')) {
            return;
        }

        $fields = ['slider_subtitle', 'slider_cta_text', 'slider_cta_url'];

        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                $value = $field === 'slider_cta_url' ?
                    esc_url_raw($_POST[$field]) :
                    sanitize_text_field($_POST[$field]);
                update_post_meta($post_id, '_' . $field, $value);
            }
        }
    }

    /**
     * Save candidate meta
     */
    private function save_candidate_meta($post_id) {
        if (!isset($_POST['candidate_meta_nonce']) ||
            !wp_verify_nonce($_POST['candidate_meta_nonce'], 'ducsu_candidate_meta_nonce')) {
            return;
        }

        // Include candidate meta save logic
        include DUCSU_THEME_PATH . '/includes/templates/candidate-meta-save.php';
    }

}