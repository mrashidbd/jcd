<?php
/**
 * Candidate Meta Save Template
 */

if (!defined('ABSPATH')) {
    exit;
}

$candidate_fields = [
    '_candidate_name_bangla', '_candidate_position', '_candidate_ballot_number',
    '_candidate_department', '_candidate_hall', '_candidate_session',
    '_candidate_father_name', '_candidate_father_profession',
    '_candidate_mother_name', '_candidate_mother_profession',
    '_candidate_permanent_address', '_candidate_special_achievements',
    '_candidate_political_journey', '_candidate_vision', '_candidate_facebook_url',
    '_candidate_twitter_url', '_candidate_email', 'candidate_gallery',
    '_candidate_ssc_school', '_candidate_ssc_gpa', '_candidate_ssc_year',
    '_candidate_hsc_college', '_candidate_hsc_gpa', '_candidate_hsc_year',
    '_candidate_graduation_university', '_candidate_graduation_cgpa',
    '_candidate_graduation_year', '_candidate_graduation_subject'
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

// Handle featured candidate checkbox
if (isset($_POST['candidate_featured'])) {
    update_post_meta($post_id, '_candidate_featured', '1');
} else {
    update_post_meta($post_id, '_candidate_featured', '0');
}