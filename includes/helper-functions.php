<?php
/**
 * Helper Functions for Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get Featured Sliders for Homepage
 */
function ducsu_get_featured_sliders($limit = 5) {
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
function ducsu_get_featured_central_candidates($limit = 6) {
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
function ducsu_get_featured_halls($limit = 8) {
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
function ducsu_get_hall_candidates($hall_id, $limit = -1) {
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
function ducsu_get_manifesto_items($limit = -1) {
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
 * Convert English numbers to Bengali
 */
function convertEngToBn($englishNumber) {
    $englishDigits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    $banglaDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];

    return str_replace($englishDigits, $banglaDigits, $englishNumber);
}

/**
 * Get candidate meta with default values
 */
function ducsu_get_candidate_meta($post_id, $key, $default = '') {
    $value = get_post_meta($post_id, '_candidate_' . $key, true);
    return !empty($value) ? $value : $default;
}

/**
 * Format candidate education info
 */
function ducsu_format_education_info($school, $gpa, $year) {
    $info_parts = array_filter([$school, $gpa, $year]);
    return implode(' - ', $info_parts);
}

/**
 * Get candidate social links
 */
function ducsu_get_candidate_social_links($post_id) {
    $social_links = [];

    $facebook = get_post_meta($post_id, '_candidate_facebook_url', true);
    if ($facebook) {
        $social_links['facebook'] = $facebook;
    }

    $twitter = get_post_meta($post_id, '_candidate_twitter_url', true);
    if ($twitter) {
        $social_links['twitter'] = $twitter;
    }

    return $social_links;
}

/**
 * Check if candidate has complete education info
 */
function ducsu_candidate_has_education_info($post_id) {
    $ssc_school = get_post_meta($post_id, '_candidate_ssc_school', true);
    $hsc_college = get_post_meta($post_id, '_candidate_hsc_college', true);
    $graduation_university = get_post_meta($post_id, '_candidate_graduation_university', true);

    return !empty($ssc_school) || !empty($hsc_college) || !empty($graduation_university);
}

/**
 * Get candidate gallery count
 */
function ducsu_get_candidate_gallery_count($post_id) {
    $gallery_ids = get_post_meta($post_id, '_candidate_gallery', true);
    if (empty($gallery_ids)) {
        return 0;
    }

    $ids_array = explode(',', $gallery_ids);
    return count(array_filter($ids_array));
}

/**
 * Format phone number for display
 */
function ducsu_format_phone_number($phone) {
    if (empty($phone)) {
        return '';
    }

    // Remove all non-digit characters
    $phone = preg_replace('/\D/', '', $phone);

    // Format Bangladesh phone number
    if (strlen($phone) === 11 && substr($phone, 0, 2) === '01') {
        return '+৮৮০ ' . substr($phone, 0, 4) . ' ' . substr($phone, 4, 3) . ' ' . substr($phone, 7);
    }

    return $phone;
}

/**
 * Get post type display name
 */
function ducsu_get_post_type_display_name($post_type) {
    $display_names = [
        'central_candidate' => 'কেন্দ্রীয় প্যানেল',
        'hall_candidate' => 'হল প্যানেল',
        'manifesto' => 'ইশতেহার',
        'slider' => 'স্লাইডার'
    ];

    return isset($display_names[$post_type]) ? $display_names[$post_type] : $post_type;
}

/**
 * Get candidate by ballot number
 */
function ducsu_get_candidate_by_ballot_number($ballot_number, $post_type = 'central_candidate') {
    $args = [
        'post_type' => $post_type,
        'posts_per_page' => 1,
        'meta_query' => [
            [
                'key' => '_candidate_ballot_number',
                'value' => $ballot_number,
                'compare' => '='
            ]
        ]
    ];

    $query = new WP_Query($args);
    return $query->have_posts() ? $query->posts[0] : null;
}

/**
 * Check if user can view candidate details
 */
function ducsu_can_view_candidate_details($post_id) {
    // Add any access control logic here
    return true; // For now, everyone can view
}

/**
 * Get related candidates
 */
function ducsu_get_related_candidates($post_id, $limit = 3) {
    $post_type = get_post_type($post_id);
    $department = get_post_meta($post_id, '_candidate_department', true);

    $args = [
        'post_type' => $post_type,
        'posts_per_page' => $limit,
        'post__not_in' => [$post_id],
        'orderby' => 'rand'
    ];

    // If candidate has department, try to get candidates from same department
    if (!empty($department)) {
        $args['meta_query'] = [
            [
                'key' => '_candidate_department',
                'value' => $department,
                'compare' => '='
            ]
        ];

        $query = new WP_Query($args);

        // If no candidates from same department, get random ones
        if (!$query->have_posts()) {
            unset($args['meta_query']);
            $query = new WP_Query($args);
        }

        return $query;
    }

    return new WP_Query($args);
}

/**
 * Get newsletter subscriber count
 */
function ducsu_get_newsletter_subscriber_count() {
    $subscribers = get_option('ducsu_newsletter_subscribers', []);
    return count($subscribers);
}

/**
 * Check if email is subscribed to newsletter
 */
function ducsu_is_email_subscribed($email) {
    $subscribers = get_option('ducsu_newsletter_subscribers', []);
    return in_array($email, $subscribers);
}

/**
 * Generate breadcrumb for candidate pages
 */
function ducsu_generate_candidate_breadcrumb($post_id) {
    $post_type = get_post_type($post_id);
    $breadcrumb = [];

    $breadcrumb[] = [
        'title' => 'হোম',
        'url' => home_url('/')
    ];

    if ($post_type === 'central_candidate') {
        $breadcrumb[] = [
            'title' => 'কেন্দ্রীয় প্যানেল',
            'url' => home_url('/central-panel')
        ];
    } elseif ($post_type === 'hall_candidate') {
        $breadcrumb[] = [
            'title' => 'হল প্যানেল',
            'url' => home_url('/hall-panels')
        ];

        // Add hall if available
        $terms = get_the_terms($post_id, 'halls');
        if ($terms && !is_wp_error($terms)) {
            $breadcrumb[] = [
                'title' => $terms[0]->name,
                'url' => get_term_link($terms[0])
            ];
        }
    }

    $breadcrumb[] = [
        'title' => get_the_title($post_id),
        'url' => get_permalink($post_id)
    ];

    return $breadcrumb;
}