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


/**
 * Additional Helper Functions for Enhanced Search
 * Add these functions to your existing helper-functions.php file
 */

/**
 * Get searchable meta fields for candidates
 */
function ducsu_get_searchable_meta_fields()
{
    return [
        '_candidate_name_bangla' => 'নাম',
        '_candidate_father_name' => 'পিতার নাম',
        '_candidate_mother_name' => 'মাতার নাম',
        '_candidate_department' => 'বিভাগ',
        '_candidate_hall' => 'হল',
        '_candidate_position' => 'পদ',
        '_candidate_ballot_number' => 'ব্যালট নম্বর',
        '_candidate_session' => 'সেশন',
        '_candidate_permanent_address' => 'ঠিকানা',
        '_candidate_special_achievements' => 'অর্জন',
        '_candidate_political_journey' => 'রাজনৈতিক অভিজ্ঞতা',
        '_candidate_vision' => 'দৃষ্টিভঙ্গি'
    ];
}

/**
 * Get candidate parent page URL based on post type
 */
function ducsu_get_candidate_parent_url($post_id)
{
    $post_type = get_post_type($post_id);

    if ($post_type === 'central_candidate') {
        return home_url('/central-panel');
    } elseif ($post_type === 'hall_candidate') {
        $base_url = home_url('/hall-panels');

        // Add hall parameter if candidate has hall
        $terms = get_the_terms($post_id, 'halls');
        if ($terms && !is_wp_error($terms)) {
            $base_url .= '?hall=' . $terms[0]->term_id;
        }

        return $base_url;
    }

    return get_permalink($post_id);
}

/**
 * Format candidate search excerpt
 */
function ducsu_format_candidate_search_excerpt($post_id)
{
    $excerpt_parts = [];

    $name_bangla = get_post_meta($post_id, '_candidate_name_bangla', true);
    $position = get_post_meta($post_id, '_candidate_position', true);
    $department = get_post_meta($post_id, '_candidate_department', true);
    $hall = get_post_meta($post_id, '_candidate_hall', true);
    $ballot_number = get_post_meta($post_id, '_candidate_ballot_number', true);

    if ($name_bangla) $excerpt_parts[] = $name_bangla;
    if ($position) $excerpt_parts[] = $position;
    if ($department) $excerpt_parts[] = $department;
    if ($hall) $excerpt_parts[] = $hall;
    if ($ballot_number) $excerpt_parts[] = 'ব্যালট: ' . $ballot_number;

    return implode(' • ', array_filter($excerpt_parts));
}

/**
 * Get candidate search title with ballot number
 */
function ducsu_get_candidate_search_title($post_id)
{
    $name_bangla = get_post_meta($post_id, '_candidate_name_bangla', true);
    $ballot_number = get_post_meta($post_id, '_candidate_ballot_number', true);

    $title = $name_bangla ?: get_the_title($post_id);

    if ($ballot_number) {
        $title .= ' (ব্যালট: ' . $ballot_number . ')';
    }

    return $title;
}

/**
 * Calculate search relevance score for candidates
 */
function ducsu_calculate_search_relevance($post_id, $query, $match_field)
{
    $score = 0;
    $query_lower = strtolower($query);

    // Higher score for exact matches in important fields
    $important_fields = [
        '_candidate_name_bangla' => 10,
        '_candidate_ballot_number' => 8,
        '_candidate_position' => 6,
        'title_content' => 5,
        '_candidate_father_name' => 4,
        '_candidate_mother_name' => 4,
        '_candidate_department' => 3,
        '_candidate_hall' => 3
    ];

    $base_score = isset($important_fields[$match_field]) ? $important_fields[$match_field] : 2;

    // Get field value
    $field_value = '';
    if ($match_field === 'title_content') {
        $field_value = strtolower(get_the_title($post_id) . ' ' . get_post_field('post_content', $post_id));
    } else {
        $field_value = strtolower(get_post_meta($post_id, $match_field, true));
    }

    // Calculate score based on match quality
    if ($field_value === $query_lower) {
        $score = $base_score * 3; // Exact match
    } elseif (strpos($field_value, $query_lower) === 0) {
        $score = $base_score * 2; // Starts with query
    } elseif (strpos($field_value, $query_lower) !== false) {
        $score = $base_score; // Contains query
    }

    // Bonus for shorter field values (more relevant)
    if ($score > 0 && strlen($field_value) > 0) {
        $relevance_ratio = strlen($query_lower) / strlen($field_value);
        $score += $relevance_ratio * 2;
    }

    return $score;
}

/**
 * Check if search result already exists in array
 */
function ducsu_search_result_exists($results, $post_id)
{
    foreach ($results as $result) {
        if (isset($result['id']) && $result['id'] === $post_id) {
            return true;
        }
    }
    return false;
}

/**
 * Sort search results by relevance score
 */
function ducsu_sort_search_results_by_relevance($results)
{
    usort($results, function ($a, $b) {
        $score_a = isset($a['relevance_score']) ? $a['relevance_score'] : 0;
        $score_b = isset($b['relevance_score']) ? $b['relevance_score'] : 0;

        if ($score_a === $score_b) {
            // If scores are equal, sort alphabetically
            return strcmp($a['title'], $b['title']);
        }

        return $score_b - $score_a; // Descending order
    });

    return $results;
}

/**
 * Get popular search terms for suggestions
 */
function ducsu_get_popular_search_terms()
{
    return [
        'কেন্দ্রীয় প্যানেল',
        'হল প্যানেল',
        'ইশতিহার',
        'সভাপতি',
        'সাধারণ সম্পাদক',
        'সহ-সভাপতি',
        'কোষাধ্যক্ষ',
        'প্রচার সম্পাদক',
        'সাংস্কৃতিক সম্পাদক',
        'ক্রীড়া সম্পাদক',
        'সমাজকল্যাণ সম্পাদক'
    ];
}

/**
 * Get search context based on match field
 */
function ducsu_get_search_match_context($post_id, $match_field, $query)
{
    $searchable_fields = ducsu_get_searchable_meta_fields();

    if (!isset($searchable_fields[$match_field])) {
        return '';
    }

    $field_label = $searchable_fields[$match_field];
    $field_value = get_post_meta($post_id, $match_field, true);

    if (empty($field_value)) {
        return '';
    }

    // Highlight the matching part
    $highlighted_value = str_ireplace($query, '<strong>' . $query . '</strong>', $field_value);

    return $field_label . ': ' . $highlighted_value;
}

/**
 * Log search queries for analytics
 */
function ducsu_log_search_query($query, $results_count = 0)
{
    if (empty($query)) return;

    $searches = get_option('ducsu_search_log', []);
    $today = date('Y-m-d');

    if (!isset($searches[$today])) {
        $searches[$today] = [];
    }

    $query_key = strtolower(trim($query));

    if (!isset($searches[$today][$query_key])) {
        $searches[$today][$query_key] = [
            'query' => $query,
            'count' => 0,
            'results' => 0
        ];
    }

    $searches[$today][$query_key]['count']++;
    $searches[$today][$query_key]['results'] = $results_count;

    // Keep only last 30 days of data
    $cutoff_date = date('Y-m-d', strtotime('-30 days'));
    foreach ($searches as $date => $data) {
        if ($date < $cutoff_date) {
            unset($searches[$date]);
        }
    }

    update_option('ducsu_search_log', $searches);
}

/**
 * Get popular search queries from log
 */
function ducsu_get_popular_searches($days = 7, $limit = 10)
{
    $searches = get_option('ducsu_search_log', []);
    $popular = [];

    $cutoff_date = date('Y-m-d', strtotime("-{$days} days"));

    foreach ($searches as $date => $queries) {
        if ($date >= $cutoff_date) {
            foreach ($queries as $query_data) {
                $query = $query_data['query'];
                if (!isset($popular[$query])) {
                    $popular[$query] = 0;
                }
                $popular[$query] += $query_data['count'];
            }
        }
    }

    arsort($popular);

    return array_slice(array_keys($popular), 0, $limit);
}