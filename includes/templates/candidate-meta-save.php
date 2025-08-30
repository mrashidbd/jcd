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
    '_candidate_twitter_url', '_candidate_email',
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

        update_post_meta($post_id, $field, $value);
    }
}

// Handle gallery images separately
if (isset($_POST['candidate_gallery'])) {
    $gallery_value = sanitize_text_field($_POST['candidate_gallery']);

    // Clean up the gallery value - remove empty entries and validate image IDs
    if (!empty($gallery_value)) {
        $image_ids = explode(',', $gallery_value);
        $cleaned_ids = [];

        foreach ($image_ids as $image_id) {
            $image_id = trim($image_id);
            if (!empty($image_id) && is_numeric($image_id)) {
                // Verify the attachment exists
                if (wp_get_attachment_url($image_id)) {
                    $cleaned_ids[] = $image_id;
                }
            }
        }

        // Limit to 5 images maximum
        $cleaned_ids = array_slice($cleaned_ids, 0, 5);
        $final_value = implode(',', $cleaned_ids);
    } else {
        $final_value = '';
    }

    update_post_meta($post_id, '_candidate_gallery', $final_value);
}

// Handle featured candidate checkbox
if (isset($_POST['featured_nonce']) && wp_verify_nonce($_POST['featured_nonce'], 'ducsu_featured_nonce')) {
    if (isset($_POST['candidate_featured']) && $_POST['candidate_featured'] === '1') {
        update_post_meta($post_id, '_candidate_featured', '1');
    } else {
        update_post_meta($post_id, '_candidate_featured', '0');
    }
}