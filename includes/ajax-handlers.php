<?php
/**
 * AJAX Handlers for Frontend Interactions
 */

if (!defined('ABSPATH')) {
    exit;
}

class DUCSU_AJAX_Handlers {

    public function __construct() {
        // Search functionality
        add_action('wp_ajax_ducsu_search', [$this, 'handle_search']);
        add_action('wp_ajax_nopriv_ducsu_search', [$this, 'handle_search']);

        // Candidate details
        add_action('wp_ajax_get_candidate_details', [$this, 'get_candidate_details']);
        add_action('wp_ajax_nopriv_get_candidate_details', [$this, 'get_candidate_details']);

        // Contact forms
        add_action('wp_ajax_ducsu_contact', [$this, 'handle_candidate_contact']);
        add_action('wp_ajax_nopriv_ducsu_contact', [$this, 'handle_candidate_contact']);

        add_action('wp_ajax_ducsu_general_contact', [$this, 'handle_general_contact']);
        add_action('wp_ajax_nopriv_ducsu_general_contact', [$this, 'handle_general_contact']);

        // Newsletter
        add_action('wp_ajax_ducsu_newsletter_subscribe', [$this, 'handle_newsletter_subscribe']);
        add_action('wp_ajax_nopriv_ducsu_newsletter_subscribe', [$this, 'handle_newsletter_subscribe']);
    }

    /**
     * Handle search requests
     */
    public function handle_search() {
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

    /**
     * Get candidate details for modal
     */
    public function get_candidate_details() {
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
        $candidate_data = $this->get_candidate_meta_data($candidate_id);
        $candidate_data['id'] = $candidate->ID;
        $candidate_data['title'] = $candidate->post_title;
        $candidate_data['content'] = $candidate->post_content;
        $candidate_data['image'] = get_the_post_thumbnail_url($candidate->ID, 'large');

        // Get gallery images
        $candidate_data['gallery'] = $this->get_candidate_gallery($candidate_id);

        wp_send_json_success($candidate_data);
    }

    /**
     * Handle candidate contact form
     */
    public function handle_candidate_contact() {
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

        // Send email
        $sent = $this->send_candidate_contact_email($candidate_id, $name, $email, $phone, $message, $candidate_email);

        if ($sent) {
            wp_send_json_success('আপনার বার্তা সফলভাবে পাঠানো হয়েছে');
        } else {
            wp_send_json_error('বার্তা পাঠাতে সমস্যা হয়েছে। পরে চেষ্টা করুন।');
        }
    }

    /**
     * Handle general contact form
     */
    public function handle_general_contact() {
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

        // Send email
        $sent = $this->send_general_contact_email($name, $email, $phone, $subject, $student_id, $message);

        if ($sent) {
            wp_send_json_success('আপনার বার্তা সফলভাবে পাঠানো হয়েছে। শীঘ্রই আমরা আপনার সাথে যোগাযোগ করব।');
        } else {
            wp_send_json_error('বার্তা পাঠাতে সমস্যা হয়েছে। পরে চেষ্টা করুন।');
        }
    }

    /**
     * Handle newsletter subscription
     */
    public function handle_newsletter_subscribe() {
        check_ajax_referer('ducsu_nonce', 'nonce');

        $email = sanitize_email($_POST['email'] ?? '');

        if (empty($email)) {
            wp_send_json_error('ইমেইল ঠিকানা প্রয়োজন');
        }

        if (!is_email($email)) {
            wp_send_json_error('সঠিক ইমেইল ঠিকানা দিন');
        }

        // Check if email already exists
        $existing_subscribers = get_option('ducsu_newsletter_subscribers', []);
        if (in_array($email, $existing_subscribers)) {
            wp_send_json_error('এই ইমেইল ইতিমধ্যে সাবস্ক্রাইব করা আছে');
        }

        // Add to subscribers list
        $existing_subscribers[] = $email;
        update_option('ducsu_newsletter_subscribers', $existing_subscribers);

        // Send confirmation email
        $this->send_newsletter_confirmation_email($email);

        wp_send_json_success('সফলভাবে সাবস্ক্রাইব হয়েছে! আপনার ইমেইল চেক করুন।');
    }

    /**
     * Get candidate meta data
     */
    private function get_candidate_meta_data($candidate_id) {
        $meta_fields = [
            'name_bangla' => '_candidate_name_bangla',
            'position' => '_candidate_position',
            'ballot_number' => '_candidate_ballot_number',
            'department' => '_candidate_department',
            'hall' => '_candidate_hall',
            'session' => '_candidate_session',
            'father_name' => '_candidate_father_name',
            'father_profession' => '_candidate_father_profession',
            'mother_name' => '_candidate_mother_name',
            'mother_profession' => '_candidate_mother_profession',
            'permanent_address' => '_candidate_permanent_address',
            'special_achievements' => '_candidate_special_achievements',
            'political_journey' => '_candidate_political_journey',
            'vision' => '_candidate_vision',
            'facebook_url' => '_candidate_facebook_url',
            'twitter_url' => '_candidate_twitter_url',
            'email' => '_candidate_email',
            // Education fields
            'ssc_school' => '_candidate_ssc_school',
            'ssc_gpa' => '_candidate_ssc_gpa',
            'ssc_year' => '_candidate_ssc_year',
            'hsc_college' => '_candidate_hsc_college',
            'hsc_gpa' => '_candidate_hsc_gpa',
            'hsc_year' => '_candidate_hsc_year',
            'graduation_university' => '_candidate_graduation_university',
            'graduation_cgpa' => '_candidate_graduation_cgpa',
            'graduation_year' => '_candidate_graduation_year',
            'graduation_subject' => '_candidate_graduation_subject'
        ];

        $data = [];
        foreach ($meta_fields as $key => $meta_key) {
            $data[$key] = get_post_meta($candidate_id, $meta_key, true);
        }

        return $data;
    }

    /**
     * Get candidate gallery images
     */
    private function get_candidate_gallery($candidate_id) {
        $gallery_ids = get_post_meta($candidate_id, '_candidate_gallery', true);
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

        return $gallery_images;
    }

    /**
     * Send candidate contact email
     */
    private function send_candidate_contact_email($candidate_id, $name, $email, $phone, $message, $candidate_email) {
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

        return wp_mail($candidate_email, $subject, $email_message, $headers);
    }

    /**
     * Send general contact email
     */
    private function send_general_contact_email($name, $email, $phone, $subject, $student_id, $message) {
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

        $admin_email = get_option('admin_email', 'info@jcdducsu.org');
        return wp_mail($admin_email, $email_subject, $email_message, $headers);
    }

    /**
     * Send newsletter confirmation email
     */
    private function send_newsletter_confirmation_email($email) {
        $subject = 'নিউজলেটার সাবস্ক্রিপশন নিশ্চিতকরণ - JCD DUCSU';
        $message = "ধন্যবাদ!\n\n";
        $message .= "আপনি সফলভাবে JCD DUCSU নিউজলেটার সাবস্ক্রাইব করেছেন।\n";
        $message .= "আমরা নির্বাচনের সর্বশেষ খবর ও আপডেট আপনার ইমেইলে পাঠাব।\n\n";
        $message .= "বাংলাদেশ জাতীয়তাবাদী ছাত্রদল\nঢাকা বিশ্ববিদ্যালয় শাখা";

        $headers = [
            'Content-Type: text/plain; charset=UTF-8',
            'From: JCD DUCSU <info@jcdducsu.org>'
        ];

        return wp_mail($email, $subject, $message, $headers);
    }
}