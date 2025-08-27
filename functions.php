<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme Functions for DUCSU JCD WordPress Theme
 */

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
function mytheme_enqueue_assets()
{
    $theme_uri = get_template_directory_uri();
    $theme_version = wp_get_theme()->get('Version');

    if (defined('WP_DEV_SERVER') && WP_DEV_SERVER) {
        // Enqueue Vite client script for HMR
        wp_enqueue_script('vite-client', 'http://localhost:5173/@vite/client', array(), null, true);
        wp_enqueue_script('mytheme-main-js', 'http://localhost:5173/src/js/main.js', array(), null, true);
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

// Ensure ducsu_ajax is always available
    wp_add_inline_script('mytheme-main-js', 'window.ducsu_ajax = ' . json_encode(array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('ducsu_nonce')
        )) . ';', 'before');

// For production mode, use the production script handle
    if (!(defined('WP_DEV_SERVER') && WP_DEV_SERVER)) {
        wp_add_inline_script('mytheme-script', 'window.ducsu_ajax = ' . json_encode(array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('ducsu_nonce')
            )) . ';', 'before');
    }
}

add_action('wp_enqueue_scripts', 'mytheme_enqueue_assets');

/**
 * Add "type=module" to the Vite client script tag.
 */
function jcd_ducsu_add_module_type_attribute($tag, $handle, $src)
{
    if ('vite-client' === $handle) {
        $tag = str_replace('<script', '<script type="module"', $tag);
    }
    return $tag;
}

add_filter('script_loader_tag', 'jcd_ducsu_add_module_type_attribute', 10, 3);

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
        'show_in_rest' => true,
    ]);

    // ---------------- Central Panel Candidates ----------------
    register_post_type('central_candidate', [
        'labels' => [
            'name' => __('Central Panel Candidates', 'ducsu-jcd'),
            'singular_name' => __('Central Candidate', 'ducsu-jcd')
        ],
        'public' => true,
        'menu_icon' => 'dashicons-groups',
        'supports' => ['title', 'thumbnail', 'editor'],
        'show_in_rest' => true,
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
        'show_in_rest' => true,
    ]);

    // ---------------- Hall Candidates ----------------
    register_post_type('hall_candidate', [
        'labels' => [
            'name' => __('Hall Panel Candidates', 'ducsu-jcd'),
            'singular_name' => __('Hall Candidate', 'ducsu-jcd')
        ],
        'public' => true,
        'menu_icon' => 'dashicons-building',
        'supports' => ['title', 'thumbnail', 'editor'],
        'taxonomies' => ['halls'],
        'show_in_rest' => true,
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
        'show_in_rest' => true,
    ]);
}

add_action('init', 'ducsu_jcd_register_cpts');

/**
 * Pre-populate Halls Taxonomy with Bangla Names
 */
function ducsu_jcd_insert_default_halls()
{
    $halls = [
        'মুক্তিযোদ্ধা জিয়াউর রহমান হল', 'বিজয় একাত্তর হল', 'কবি জসিমউদদীন হল', 'মাস্টারদা সূর্যসেন হল', 'সলিমুল্লাহ মুসলিম হল', 'শহীদ সার্জেন্ট জহুরুল হক হল', 'শেখ মুজিবুর রহমান হল', 'হাজী মুহম্মদ মুহসীন হল', 'স্যার এ এফ রহমান হল', 'রোকেয়া হল', 'শামসুন্নাহার হল', 'ফজিলাতুন্নেছা মুজিব হল', 'বাংলাদেশ কুয়েত মৈত্রী হল', 'কবি সুফিয়া কামাল হল', 'জগন্নাথ হল', 'ফজলুল হক মুসলিম হল', 'ড. মুহম্মদ শহীদুল্লাহ হল', 'অমর একুশে হল'
    ];

    foreach ($halls as $hall) {
        if (!term_exists($hall, 'halls')) {
            wp_insert_term($hall, 'halls');
        }
    }
}

/**  add_action('init', 'ducsu_jcd_insert_default_halls'); */

/**
 * Add Meta Boxes for Custom Post Types
 */
function ducsu_jcd_add_meta_boxes()
{
    // Slider Meta Box
    add_meta_box(
        'slider_details',
        'Slider Details',
        'ducsu_jcd_slider_meta_box_callback',
        'slider'
    );

    // Candidate Meta Box (for both central and hall candidates)
    add_meta_box(
        'candidate_details',
        'Candidate Details',
        'ducsu_jcd_candidate_meta_box_callback',
        ['central_candidate', 'hall_candidate']
    );
}

add_action('add_meta_boxes', 'ducsu_jcd_add_meta_boxes');

/**
 * Slider Meta Box Callback
 */
function ducsu_jcd_slider_meta_box_callback($post)
{
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
 * Candidate Meta Box Callback
 */
function ducsu_jcd_candidate_meta_box_callback($post)
{
    wp_nonce_field('ducsu_candidate_meta_nonce', 'candidate_meta_nonce');

    $fields = [
        '_candidate_name_bangla' => 'Name (Bangla)',
        '_candidate_position' => 'Position (Bangla)',
        '_candidate_ballot_number' => 'Ballot Number (Bangla)',
        '_candidate_department' => 'Department',
        '_candidate_hall' => 'Hall',
        '_candidate_session' => 'Session',
        '_candidate_father_name' => "Father's Name (Bangla)",
        '_candidate_father_profession' => "Father's Profession (Bangla)",
        '_candidate_mother_name' => "Mother's Name (Bangla)",
        '_candidate_mother_profession' => "Mother's Profession (Bangla)",
        '_candidate_permanent_address' => 'Permanent Address (Bangla)',
        '_candidate_ssc_info' => 'SSC Information',
        '_candidate_hsc_info' => 'HSC Information',
        '_candidate_graduation_info' => 'Graduation Information',
        '_candidate_special_achievements' => 'Special Achievements (Bangla)',
        '_candidate_political_journey' => 'Political Journey (Bangla)',
        '_candidate_vision' => 'Vision (Bangla)',
        '_candidate_facebook_url' => 'Facebook URL',
        '_candidate_twitter_url' => 'Twitter URL',
        '_candidate_email' => 'Candidate Email'
    ];

    echo '<table class="form-table">';
    foreach ($fields as $key => $label) {
        $value = get_post_meta($post->ID, $key, true);
        $type = (strpos($key, 'url') !== false || strpos($key, 'email') !== false) ?
            (strpos($key, 'email') !== false ? 'email' : 'url') : 'text';

        if (in_array($key, ['_candidate_special_achievements', '_candidate_political_journey', '_candidate_vision', '_candidate_permanent_address'])) {
            echo '<tr><th><label for="' . esc_attr($key) . '">' . esc_html($label) . '</label></th>';
            echo '<td><textarea id="' . esc_attr($key) . '" name="' . esc_attr($key) . '" class="widefat" rows="4">' . esc_textarea($value) . '</textarea></td></tr>';
        } else {
            echo '<tr><th><label for="' . esc_attr($key) . '">' . esc_html($label) . '</label></th>';
            echo '<td><input type="' . $type . '" id="' . esc_attr($key) . '" name="' . esc_attr($key) . '" value="' . esc_attr($value) . '" class="widefat" /></td></tr>';
        }
    }
    echo '</table>';

    // Image Gallery Section
    echo '<h3>Image Gallery (Max 5 images)</h3>';
    echo '<div id="candidate-gallery">';
    $gallery_images = get_post_meta($post->ID, '_candidate_gallery', true);
    if ($gallery_images) {
        $images = explode(',', $gallery_images);
        foreach ($images as $image_id) {
            if ($image_id) {
                $image_url = wp_get_attachment_image_src($image_id, 'thumbnail')[0];
                echo '<div class="gallery-image" data-id="' . $image_id . '">';
                echo '<img src="' . $image_url . '" width="100" height="100" />';
                echo '<button type="button" class="remove-image">Remove</button>';
                echo '</div>';
            }
        }
    }
    echo '</div>';
    echo '<input type="hidden" id="candidate_gallery" name="candidate_gallery" value="' . esc_attr($gallery_images) . '" />';
    echo '<button type="button" id="add-gallery-image" class="button">Add Image</button>';

    // Add JavaScript for gallery management
    echo '<script>
    jQuery(document).ready(function($) {
        $("#add-gallery-image").click(function() {
            var frame = wp.media.frames.gallery = wp.media({
                title: "Select Images",
                multiple: true,
                library: { type: "image" }
            });
            
            frame.on("select", function() {
                var selection = frame.state().get("selection");
                var gallery_ids = $("#candidate_gallery").val();
                var ids_array = gallery_ids ? gallery_ids.split(",") : [];
                
                selection.each(function(attachment) {
                    if (ids_array.length < 5) {
                        ids_array.push(attachment.id);
                        $("#candidate-gallery").append(
                            "<div class=\"gallery-image\" data-id=\"" + attachment.id + "\">" +
                            "<img src=\"" + attachment.attributes.sizes.thumbnail.url + "\" width=\"100\" height=\"100\" />" +
                            "<button type=\"button\" class=\"remove-image\">Remove</button>" +
                            "</div>"
                        );
                    }
                });
                
                $("#candidate_gallery").val(ids_array.join(","));
            });
            
            frame.open();
        });
        
        $(document).on("click", ".remove-image", function() {
            var image_div = $(this).parent();
            var image_id = image_div.data("id");
            var gallery_ids = $("#candidate_gallery").val();
            var ids_array = gallery_ids.split(",");
            var index = ids_array.indexOf(image_id.toString());
            
            if (index > -1) {
                ids_array.splice(index, 1);
            }
            
            $("#candidate_gallery").val(ids_array.join(","));
            image_div.remove();
        });
    });
    </script>';
}

/**
 * Save Meta Box Data
 */
function ducsu_jcd_save_meta_boxes($post_id)
{
    // Check if nonce is set and verify it
    if (!isset($_POST['slider_meta_nonce']) && !isset($_POST['candidate_meta_nonce'])) {
        return;
    }

    if (isset($_POST['slider_meta_nonce']) && !wp_verify_nonce($_POST['slider_meta_nonce'], 'ducsu_slider_meta_nonce')) {
        return;
    }

    if (isset($_POST['candidate_meta_nonce']) && !wp_verify_nonce($_POST['candidate_meta_nonce'], 'ducsu_candidate_meta_nonce')) {
        return;
    }

    // Check if user has permissions to edit posts
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Don't save if this is an autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Save slider meta
    if (isset($_POST['slider_subtitle'])) {
        update_post_meta($post_id, '_slider_subtitle', sanitize_text_field($_POST['slider_subtitle']));
    }
    if (isset($_POST['slider_cta_text'])) {
        update_post_meta($post_id, '_slider_cta_text', sanitize_text_field($_POST['slider_cta_text']));
    }
    if (isset($_POST['slider_cta_url'])) {
        update_post_meta($post_id, '_slider_cta_url', esc_url_raw($_POST['slider_cta_url']));
    }

    // Save candidate meta
    $candidate_fields = [
        '_candidate_name_bangla', '_candidate_position', '_candidate_ballot_number',
        '_candidate_department', '_candidate_hall', '_candidate_session',
        '_candidate_father_name', '_candidate_father_profession',
        '_candidate_mother_name', '_candidate_mother_profession',
        '_candidate_permanent_address', '_candidate_ssc_info',
        '_candidate_hsc_info', '_candidate_graduation_info',
        '_candidate_special_achievements', '_candidate_political_journey',
        '_candidate_vision', '_candidate_facebook_url', '_candidate_twitter_url',
        '_candidate_email', 'candidate_gallery'
    ];

    foreach ($candidate_fields as $field) {
        if (isset($_POST[$field])) {
            $value = $_POST[$field];

            if (strpos($field, 'url') !== false) {
                $value = esc_url_raw($value);
            } elseif (strpos($field, 'email') !== false) {
                $value = sanitize_email($value);
            } elseif (in_array($field, ['_candidate_special_achievements', '_candidate_political_journey', '_candidate_vision', '_candidate_permanent_address'])) {
                $value = sanitize_textarea_field($value);
            } else {
                $value = sanitize_text_field($value);
            }

            $meta_key = $field === 'candidate_gallery' ? '_candidate_gallery' : $field;
            update_post_meta($post_id, $meta_key, $value);
        }
    }
}

add_action('save_post', 'ducsu_jcd_save_meta_boxes');

/**
 * AJAX Search Handler
 */
function ducsu_jcd_ajax_search()
{
    check_ajax_referer('ducsu_nonce', 'nonce');

    $query = sanitize_text_field($_POST['query'] ?? '');

    if (empty($query)) {
        wp_send_json_error('Empty search query');
    }

    $args = [
        'post_type' => ['central_candidate', 'hall_candidate', 'manifesto'],
        's' => $query,
        'posts_per_page' => 10,
        'post_status' => 'publish'
    ];

    $search_query = new WP_Query($args);
    $results = [];

    if ($search_query->have_posts()) {
        while ($search_query->have_posts()) {
            $search_query->the_post();

            $post_type_labels = [
                'central_candidate' => 'কেন্দ্রীয় প্যানেল',
                'hall_candidate' => 'হল প্যানেল',
                'manifesto' => 'ইশতেহার'
            ];

            $results[] = [
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'link' => get_permalink(),
                'type' => $post_type_labels[get_post_type()],
                'excerpt' => get_the_excerpt()
            ];
        }
        wp_reset_postdata();
    }

    wp_send_json_success($results);
}

add_action('wp_ajax_ducsu_search', 'ducsu_jcd_ajax_search');
add_action('wp_ajax_nopriv_ducsu_search', 'ducsu_jcd_ajax_search');

/**
 * AJAX Candidate Details Handler
 */
function ducsu_jcd_get_candidate_details()
{
    check_ajax_referer('ducsu_nonce', 'nonce');

    $candidate_id = intval($_POST['candidate_id'] ?? 0);

    if (!$candidate_id) {
        wp_send_json_error('Invalid candidate ID');
    }

    $candidate = get_post($candidate_id);

    if (!$candidate || !in_array($candidate->post_type, ['central_candidate', 'hall_candidate'])) {
        wp_send_json_error('Candidate not found');
    }

    // Get all candidate meta
    $candidate_data = [
        'id' => $candidate->ID,
        'title' => $candidate->post_title,
        'content' => $candidate->post_content,
        'image' => get_the_post_thumbnail_url($candidate->ID, 'large'),
        'name_bangla' => get_post_meta($candidate->ID, '_candidate_name_bangla', true),
        'position' => get_post_meta($candidate->ID, '_candidate_position', true),
        'ballot_number' => get_post_meta($candidate->ID, '_candidate_ballot_number', true),
        'department' => get_post_meta($candidate->ID, '_candidate_department', true),
        'hall' => get_post_meta($candidate->ID, '_candidate_hall', true),
        'session' => get_post_meta($candidate->ID, '_candidate_session', true),
        'father_name' => get_post_meta($candidate->ID, '_candidate_father_name', true),
        'father_profession' => get_post_meta($candidate->ID, '_candidate_father_profession', true),
        'mother_name' => get_post_meta($candidate->ID, '_candidate_mother_name', true),
        'mother_profession' => get_post_meta($candidate->ID, '_candidate_mother_profession', true),
        'permanent_address' => get_post_meta($candidate->ID, '_candidate_permanent_address', true),
        'ssc_info' => get_post_meta($candidate->ID, '_candidate_ssc_info', true),
        'hsc_info' => get_post_meta($candidate->ID, '_candidate_hsc_info', true),
        'graduation_info' => get_post_meta($candidate->ID, '_candidate_graduation_info', true),
        'special_achievements' => get_post_meta($candidate->ID, '_candidate_special_achievements', true),
        'political_journey' => get_post_meta($candidate->ID, '_candidate_political_journey', true),
        'vision' => get_post_meta($candidate->ID, '_candidate_vision', true),
        'facebook_url' => get_post_meta($candidate->ID, '_candidate_facebook_url', true),
        'twitter_url' => get_post_meta($candidate->ID, '_candidate_twitter_url', true),
        'email' => get_post_meta($candidate->ID, '_candidate_email', true)
    ];

    // Get gallery images
    $gallery_ids = get_post_meta($candidate->ID, '_candidate_gallery', true);
    $gallery_images = [];

    if ($gallery_ids) {
        $ids_array = explode(',', $gallery_ids);
        foreach ($ids_array as $image_id) {
            if ($image_id) {
                $gallery_images[] = [
                    'id' => $image_id,
                    'url' => wp_get_attachment_image_src($image_id, 'medium')[0],
                    'full_url' => wp_get_attachment_image_src($image_id, 'full')[0]
                ];
            }
        }
    }

    $candidate_data['gallery'] = $gallery_images;

    wp_send_json_success($candidate_data);
}

add_action('wp_ajax_get_candidate_details', 'ducsu_jcd_get_candidate_details');
add_action('wp_ajax_nopriv_get_candidate_details', 'ducsu_jcd_get_candidate_details');

/**
 * AJAX Contact Form Handler
 */
function ducsu_jcd_contact_form_handler()
{
    check_ajax_referer('ducsu_nonce', 'nonce');

    $candidate_id = intval($_POST['candidate_id'] ?? 0);
    $name = sanitize_text_field($_POST['name'] ?? '');
    $email = sanitize_email($_POST['email'] ?? '');
    $phone = sanitize_text_field($_POST['phone'] ?? '');
    $message = sanitize_textarea_field($_POST['message'] ?? '');

    // Validation
    if (empty($name) || empty($email) || empty($message)) {
        wp_send_json_error('সব ফিল্ড পূরণ করুন');
    }

    if (!is_email($email)) {
        wp_send_json_error('সঠিক ইমেইল ঠিকানা দিন');
    }

    if (!$candidate_id) {
        wp_send_json_error('অবৈধ প্রার্থী আইডি');
    }

    // Get candidate details
    $candidate = get_post($candidate_id);
    $candidate_email = get_post_meta($candidate_id, '_candidate_email', true);

    if (!$candidate_email) {
        wp_send_json_error('প্রার্থীর ইমেইল পাওয়া যায়নি');
    }

    // Prepare email
    $subject = 'নতুন যোগাযোগ বার্তা - ' . get_the_title($candidate_id);

    $email_message = "প্রার্থী: " . get_the_title($candidate_id) . "\n\n";
    $email_message .= "প্রেরক: " . $name . "\n";
    $email_message .= "ইমেইল: " . $email . "\n";
    $email_message .= "ফোন: " . $phone . "\n\n";
    $email_message .= "বার্তা:\n" . $message . "\n\n";
    $email_message .= "পাঠানোর সময়: " . current_time('mysql') . "\n";

    $headers = [
        'Content-Type: text/plain; charset=UTF-8',
        'Reply-To: ' . $name . ' <' . $email . '>'
    ];

    // Send email
    $sent = wp_mail($candidate_email, $subject, $email_message, $headers);

    if ($sent) {
        wp_send_json_success('আপনার বার্তা সফলভাবে পাঠানো হয়েছে');
    } else {
        wp_send_json_error('বার্তা পাঠাতে সমস্যা হয়েছে। পরে চেষ্টা করুন।');
    }
}

add_action('wp_ajax_ducsu_contact', 'ducsu_jcd_contact_form_handler');
add_action('wp_ajax_nopriv_ducsu_contact', 'ducsu_jcd_contact_form_handler');

/**
 * Helper Functions for Dynamic Content
 */

/**
 * Get Featured Sliders for Homepage
 */
function ducsu_get_featured_sliders($limit = 5)
{
    $args = [
        'post_type' => 'slider',
        'posts_per_page' => $limit,
        'post_status' => 'publish',
        'orderby' => 'menu_order',
        'order' => 'ASC'
    ];

    return new WP_Query($args);
}

/**
 * Get Featured Central Candidates
 */
function ducsu_get_featured_central_candidates($limit = 6)
{
    $args = [
        'post_type' => 'central_candidate',
        'posts_per_page' => $limit,
        'post_status' => 'publish',
        'meta_query' => [
            [
                'key' => '_candidate_featured',
                'value' => '1',
                'compare' => '='
            ]
        ],
        'orderby' => 'menu_order',
        'order' => 'ASC'
    ];

    $query = new WP_Query($args);

    // If no featured candidates, get latest ones
    if (!$query->have_posts()) {
        $args['meta_query'] = [];
        $args['orderby'] = 'date';
        $args['order'] = 'DESC';
        $query = new WP_Query($args);
    }

    return $query;
}

/**
 * Get Featured Halls
 */
function ducsu_get_featured_halls($limit = 8)
{
    $args = [
        'taxonomy' => 'halls',
        'number' => $limit,
        'hide_empty' => true,
        'orderby' => 'count',
        'order' => 'DESC'
    ];

    return get_terms($args);
}

/**
 * Get Hall Candidates by Hall ID
 */
function ducsu_get_hall_candidates($hall_id, $limit = -1)
{
    $args = [
        'post_type' => 'hall_candidate',
        'posts_per_page' => $limit,
        'post_status' => 'publish',
        'tax_query' => [
            [
                'taxonomy' => 'halls',
                'field' => 'term_id',
                'terms' => $hall_id
            ]
        ],
        'orderby' => 'menu_order',
        'order' => 'ASC'
    ];

    return new WP_Query($args);
}

/**
 * Get Manifesto Items
 */
function ducsu_get_manifesto_items($limit = -1)
{
    $args = [
        'post_type' => 'manifesto',
        'posts_per_page' => $limit,
        'post_status' => 'publish',
        'orderby' => 'menu_order',
        'order' => 'ASC'
    ];

    return new WP_Query($args);
}

/**
 * Add Featured Candidate Meta Box
 */
function ducsu_add_featured_meta_box()
{
    add_meta_box(
        'featured_candidate',
        'Featured Candidate',
        'ducsu_featured_candidate_callback',
        'central_candidate'
    );
}

add_action('add_meta_boxes', 'ducsu_add_featured_meta_box');

function ducsu_featured_candidate_callback($post)
{
    wp_nonce_field('ducsu_featured_nonce', 'featured_nonce');
    $featured = get_post_meta($post->ID, '_candidate_featured', true);

    echo '<label>';
    echo '<input type="checkbox" name="candidate_featured" value="1" ' . checked($featured, '1', false) . ' />';
    echo ' Make this candidate featured on homepage';
    echo '</label>';
}

function ducsu_save_featured_meta($post_id)
{
    if (!isset($_POST['featured_nonce']) || !wp_verify_nonce($_POST['featured_nonce'], 'ducsu_featured_nonce')) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $featured = isset($_POST['candidate_featured']) ? '1' : '0';
    update_post_meta($post_id, '_candidate_featured', $featured);
}

add_action('save_post', 'ducsu_save_featured_meta');

/**
 * AJAX Newsletter Subscription Handler
 */
function ducsu_jcd_newsletter_subscribe()
{
    check_ajax_referer('ducsu_nonce', 'nonce');

    $email = sanitize_email($_POST['email'] ?? '');

    if (empty($email)) {
        wp_send_json_error('ইমেইল ঠিকানা প্রয়োজন');
    }

    if (!is_email($email)) {
        wp_send_json_error('সঠিক ইমেইল ঠিকানা দিন');
    }

    // Check if email already exists
    $existing_subscriber = get_option('ducsu_newsletter_subscribers', []);
    if (in_array($email, $existing_subscriber)) {
        wp_send_json_error('এই ইমেইল ইতিমধ্যে সাবস্ক্রাইব করা আছে');
    }

    // Add to subscribers list
    $existing_subscriber[] = $email;
    update_option('ducsu_newsletter_subscribers', $existing_subscriber);

    // Send confirmation email
    $subject = 'নিউজলেটার সাবস্ক্রিপশন নিশ্চিতকরণ - JCD DUCSU';
    $message = "ধন্যবাদ!\n\n";
    $message .= "আপনি সফলভাবে JCD DUCSU নিউজলেটার সাবস্ক্রাইব করেছেন।\n";
    $message .= "আমরা নির্বাচনের সর্বশেষ খবর ও আপডেট আপনার ইমেইলে পাঠাব।\n\n";
    $message .= "বাংলাদেশ জাতীয়তাবাদী ছাত্রদল\nঢাকা বিশ্ববিদ্যালয় শাখা";

    $headers = [
        'Content-Type: text/plain; charset=UTF-8',
        'From: JCD DUCSU <info@jcdducsu.org>'
    ];

    wp_mail($email, $subject, $message, $headers);

    wp_send_json_success('সফলভাবে সাবস্ক্রাইব হয়েছে! আপনার ইমেইল চেক করুন।');
}

add_action('wp_ajax_ducsu_newsletter_subscribe', 'ducsu_jcd_newsletter_subscribe');
add_action('wp_ajax_nopriv_ducsu_newsletter_subscribe', 'ducsu_jcd_newsletter_subscribe');

/**
 * AJAX General Contact Form Handler
 */
function ducsu_jcd_general_contact_handler()
{
    check_ajax_referer('ducsu_nonce', 'nonce');

    $name = sanitize_text_field($_POST['name'] ?? '');
    $email = sanitize_email($_POST['email'] ?? '');
    $phone = sanitize_text_field($_POST['phone'] ?? '');
    $subject = sanitize_text_field($_POST['subject'] ?? '');
    $student_id = sanitize_text_field($_POST['student_id'] ?? '');
    $message = sanitize_textarea_field($_POST['message'] ?? '');

    // Validation
    if (empty($name) || empty($email) || empty($message)) {
        wp_send_json_error('সব প্রয়োজনীয় ফিল্ড পূরণ করুন');
    }

    if (!is_email($email)) {
        wp_send_json_error('সঠিক ইমেইল ঠিকানা দিন');
    }

    // Prepare email
    $email_subject = 'নতুন যোগাযোগ বার্তা - ' . ($subject ?: 'সাধারণ তথ্য');

    $email_message = "নতুন যোগাযোগ বার্তা পেয়েছেন:\n\n";
    $email_message .= "প্রেরক: " . $name . "\n";
    $email_message .= "ইমেইল: " . $email . "\n";
    $email_message .= "ফোন: " . ($phone ?: 'দেওয়া হয়নি') . "\n";
    $email_message .= "বিষয়: " . ($subject ?: 'সাধারণ তথ্য') . "\n";
    $email_message .= "শিক্ষার্থী আইডি: " . ($student_id ?: 'দেওয়া হয়নি') . "\n\n";
    $email_message .= "বার্তা:\n" . $message . "\n\n";
    $email_message .= "পাঠানোর সময়: " . current_time('mysql') . "\n";
    $email_message .= "আইপি ঠিকানা: " . $_SERVER['REMOTE_ADDR'] . "\n";

    $headers = [
        'Content-Type: text/plain; charset=UTF-8',
        'Reply-To: ' . $name . ' <' . $email . '>'
    ];

    // Send email to admin
    $admin_email = get_option('admin_email', 'info@jcdducsu.org');
    $sent = wp_mail($admin_email, $email_subject, $email_message, $headers);

    if ($sent) {
        wp_send_json_success('আপনার বার্তা সফলভাবে পাঠানো হয়েছে। শীঘ্রই আমরা আপনার সাথে যোগাযোগ করব।');
    } else {
        wp_send_json_error('বার্তা পাঠাতে সমস্যা হয়েছে। পরে চেষ্টা করুন।');
    }
}

add_action('wp_ajax_ducsu_general_contact', 'ducsu_jcd_general_contact_handler');
add_action('wp_ajax_nopriv_ducsu_general_contact', 'ducsu_jcd_general_contact_handler');

/**
 * Add Admin Menu for Newsletter Subscribers
 */
function ducsu_add_newsletter_admin_menu()
{
    add_submenu_page(
        'edit.php?post_type=central_candidate',
        'Newsletter Subscribers',
        'Newsletter Subscribers',
        'manage_options',
        'newsletter-subscribers',
        'ducsu_newsletter_subscribers_page'
    );
}

add_action('admin_menu', 'ducsu_add_newsletter_admin_menu');

/**
 * Newsletter Subscribers Admin Page
 */
function ducsu_newsletter_subscribers_page()
{
    $subscribers = get_option('ducsu_newsletter_subscribers', []);
    ?>
    <div class="wrap">
        <h1>Newsletter Subscribers</h1>
        <p>Total subscribers: <?php echo count($subscribers); ?></p>

        <?php if (!empty($subscribers)) : ?>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                <tr>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($subscribers as $index => $email) : ?>
                    <tr>
                        <td><?php echo esc_html($email); ?></td>
                        <td>
                            <a href="?page=newsletter-subscribers&action=remove&index=<?php echo $index; ?>&nonce=<?php echo wp_create_nonce('remove_subscriber'); ?>"
                               onclick="return confirm('Are you sure you want to remove this subscriber?')"
                               class="button button-small">Remove</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>No subscribers yet.</p>
        <?php endif; ?>

        <h2>Export Subscribers</h2>
        <p>
            <a href="?page=newsletter-subscribers&action=export&nonce=<?php echo wp_create_nonce('export_subscribers'); ?>"
               class="button button-primary">Export as CSV</a>
        </p>
    </div>
    <?php

    // Handle actions
    if (isset($_GET['action']) && isset($_GET['nonce'])) {
        if ($_GET['action'] === 'remove' && wp_verify_nonce($_GET['nonce'], 'remove_subscriber')) {
            $index = intval($_GET['index']);
            if (isset($subscribers[$index])) {
                unset($subscribers[$index]);
                update_option('ducsu_newsletter_subscribers', array_values($subscribers));
                echo '<div class="notice notice-success"><p>Subscriber removed successfully.</p></div>';
            }
        }

        if ($_GET['action'] === 'export' && wp_verify_nonce($_GET['nonce'], 'export_subscribers')) {
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="newsletter-subscribers.csv"');

            $output = fopen('php://output', 'w');
            fputcsv($output, ['Email', 'Subscribed Date']);

            foreach ($subscribers as $email) {
                fputcsv($output, [$email, date('Y-m-d H:i:s')]);
            }

            fclose($output);
            exit;
        }
    }
}

/**
 * Add Custom Meta Fields for Manifesto
 */
function ducsu_add_manifesto_meta_boxes()
{
    add_meta_box(
        'manifesto_details',
        'Manifesto Details',
        'ducsu_manifesto_meta_box_callback',
        'manifesto'
    );
}

add_action('add_meta_boxes', 'ducsu_add_manifesto_meta_boxes');

/**
 * Manifesto Meta Box Callback
 */
function ducsu_manifesto_meta_box_callback($post)
{
    wp_nonce_field('ducsu_manifesto_meta_nonce', 'manifesto_meta_nonce');

    $timeline = get_post_meta($post->ID, '_manifesto_timeline', true);
    $priority = get_post_meta($post->ID, '_manifesto_priority', true);

    echo '<table class="form-table">';
    echo '<tr><th><label for="manifesto_timeline">Implementation Timeline</label></th>';
    echo '<td><input type="text" id="manifesto_timeline" name="manifesto_timeline" value="' . esc_attr($timeline) . '" class="widefat" placeholder="e.g., First 6 months, Within 1 year, etc." /></td></tr>';

    echo '<tr><th><label for="manifesto_priority">Priority Level</label></th>';
    echo '<td><select id="manifesto_priority" name="manifesto_priority" class="widefat">';
    echo '<option value="">Select Priority</option>';
    echo '<option value="high"' . selected($priority, 'high', false) . '>High</option>';
    echo '<option value="medium"' . selected($priority, 'medium', false) . '>Medium</option>';
    echo '<option value="normal"' . selected($priority, 'normal', false) . '>Normal</option>';
    echo '</select></td></tr>';
    echo '</table>';
}

/**
 * Save Manifesto Meta Fields
 */
function ducsu_save_manifesto_meta($post_id)
{
    if (!isset($_POST['manifesto_meta_nonce']) || !wp_verify_nonce($_POST['manifesto_meta_nonce'], 'ducsu_manifesto_meta_nonce')) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (isset($_POST['manifesto_timeline'])) {
        update_post_meta($post_id, '_manifesto_timeline', sanitize_text_field($_POST['manifesto_timeline']));
    }

    if (isset($_POST['manifesto_priority'])) {
        update_post_meta($post_id, '_manifesto_priority', sanitize_text_field($_POST['manifesto_priority']));
    }
}

add_action('save_post', 'ducsu_save_manifesto_meta');

/**
 * Add Hall Image Field to Halls Taxonomy
 */
function ducsu_add_hall_image_field($term)
{
    $image_id = get_term_meta($term->term_id, 'hall_image', true);
    ?>
    <tr class="form-field">
        <th scope="row">
            <label for="hall_image">Hall Image</label>
        </th>
        <td>
            <input type="hidden" id="hall_image" name="hall_image" value="<?php echo esc_attr($image_id); ?>"/>
            <div id="hall_image_preview">
                <?php if ($image_id) : ?>
                    <?php echo wp_get_attachment_image($image_id, 'medium'); ?>
                <?php endif; ?>
            </div>
            <button type="button" id="upload_hall_image" class="button">Upload Image</button>
            <button type="button" id="remove_hall_image" class="button"
                    style="<?php echo $image_id ? '' : 'display:none;'; ?>">Remove Image
            </button>
        </td>
    </tr>

    <script>
        jQuery(document).ready(function ($) {
            $('#upload_hall_image').click(function () {
                var frame = wp.media.frames.hall_image = wp.media({
                    title: 'Select Hall Image',
                    button: {text: 'Use Image'},
                    library: {type: 'image'},
                    multiple: false
                });

                frame.on('select', function () {
                    var attachment = frame.state().get('selection').first().toJSON();
                    $('#hall_image').val(attachment.id);
                    $('#hall_image_preview').html('<img src="' + attachment.sizes.medium.url + '" style="max-width: 300px;" />');
                    $('#remove_hall_image').show();
                });

                frame.open();
            });

            $('#remove_hall_image').click(function () {
                $('#hall_image').val('');
                $('#hall_image_preview').html('');
                $(this).hide();
            });
        });
    </script>
    <?php
}

add_action('halls_edit_form_fields', 'ducsu_add_hall_image_field');

/**
 * Save Hall Image Field
 */
function ducsu_save_hall_image($term_id)
{
    if (isset($_POST['hall_image'])) {
        update_term_meta($term_id, 'hall_image', absint($_POST['hall_image']));
    }
}

add_action('edited_halls', 'ducsu_save_hall_image');

/**
 * Enqueue Media Uploader for Admin
 */
function ducsu_enqueue_admin_scripts($hook)
{
    global $post_type, $taxonomy;

    if (in_array($hook, ['post-new.php', 'post.php']) && in_array($post_type, ['central_candidate', 'hall_candidate'])) {
        wp_enqueue_media();
        wp_enqueue_script('jquery');
    }

    if ($hook === 'term.php' && $taxonomy === 'halls') {
        wp_enqueue_media();
        wp_enqueue_script('jquery');
    }
}

add_action('admin_enqueue_scripts', 'ducsu_enqueue_admin_scripts');