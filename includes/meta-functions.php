<?php
/**
 * Fixed SEO Meta Functions for DUCSU JCD WordPress Theme
 * Replace the previous functions with this error-free version
 */

/**
 * Add SEO Meta Tags to Head
 */
function ducsu_seo_meta_tags() {
    // Get current page/post data
    global $post, $wp;

    // Safely get current URL
    $current_url = '';
    if (isset($wp->request) && $wp->request) {
        $current_url = home_url(add_query_arg(array(), $wp->request));
    } else {
        $current_url = home_url($_SERVER['REQUEST_URI']);
    }

    $site_name = get_bloginfo('name');
    $site_description = get_bloginfo('description');

    // Default meta values
    $meta_title = '';
    $meta_description = '';
    $meta_keywords = '';
    $og_image = '';
    $canonical_url = '';

    // Page-specific meta data
    if (is_front_page()) {
        $meta_title = 'বাংলাদেশ জাতীয়তাবাদী ছাত্রদল - ঢাকা বিশ্ববিদ্যালয় কেন্দ্রীয় ছাত্র সংসদ (ডাকসু)';
        $meta_description = 'বাংলাদেশ জাতীয়তাবাদী ছাত্রদল ঢাকা বিশ্ববিদ্যালয় কেন্দ্রীয় ছাত্র সংসদ (ডাকসু) নির্বাচন ২০২৫। আমাদের প্রার্থী, ইশতেহার এবং ভবিষ্যৎ পরিকল্পনা সম্পর্কে জানুন।';
        $meta_keywords = 'বিএনপি ছাত্রদল, ডাকসু নির্বাচন, ঢাকা বিশ্ববিদ্যালয়, কেন্দ্রীয় ছাত্র সংসদ, ছাত্র রাজনীতি, ডাকসু নির্বাচন ২০২৫,বাংলাদেশ জাতীয়তাবাদী ছাত্রদল,ঢাকা বিশ্ববিদ্যালয় ছাত্র সংসদ,কেন্দ্রীয় প্যানেল,হল প্যানেল,ছাত্র অধিকার,নির্বাচনী ইশতেহার,ডাকসু নির্বাচনের প্রার্থী তালিকা,ঢাকা বিশ্ববিদ্যালয় হল প্রার্থী,বিএনপি ছাত্রদল কেন্দ্রীয় প্রার্থী,ডাকসু ভিপি,ডাকসু ভিপি প্রার্থী';
        $canonical_url = home_url();
        $og_image = get_template_directory_uri() . '/assets/images/ducsu-jcd-og.png';

    } elseif (is_page('central-panel')) {
        $meta_title = 'কেন্দ্রীয় প্যানেল - ' . $site_name;
        $meta_description = 'বাংলাদেশ জাতীয়তাবাদী ছাত্রদলের ডাকসু নির্বাচনের কেন্দ্রীয় প্যানেলের প্রার্থীদের তালিকা ও তাদের পরিচিতি দেখুন।';
        $meta_keywords = 'কেন্দ্রীয় প্যানেল, ডাকসু প্রার্থী, ভিপি, জিএস, কেন্দ্রীয় প্রার্থী তালিকা, ডাকসু নির্বাচন ২০২৫,বাংলাদেশ জাতীয়তাবাদী ছাত্রদল,ঢাকা বিশ্ববিদ্যালয় ছাত্র সংসদ,কেন্দ্রীয় প্যানেল,হল প্যানেল,ছাত্র অধিকার,নির্বাচনী ইশতেহার,ডাকসু নির্বাচনের প্রার্থী তালিকা,ঢাকা বিশ্ববিদ্যালয় হল প্রার্থী,বিএনপি ছাত্রদল কেন্দ্রীয় প্রার্থী,ডাকসু ভিপি,ডাকসু ভিপি প্রার্থী';

    } elseif (is_page('hall-panels')) {
        $meta_title = 'হল প্যানেল - ' . $site_name;
        $meta_description = 'ঢাকা বিশ্ববিদ্যালয়ের সকল আবাসিক হলের জাতীয়তাবাদী ছাত্রদলের প্রার্থীদের তালিকা ও পরিচিতি দেখুন।';
        $meta_keywords = 'হল প্যানেল, আবাসিক হল, হল প্রার্থী, ছাত্রদল হল প্রার্থী, ডাকসু নির্বাচন ২০২৫,বাংলাদেশ জাতীয়তাবাদী ছাত্রদল,ঢাকা বিশ্ববিদ্যালয় ছাত্র সংসদ,কেন্দ্রীয় প্যানেল,হল প্যানেল,ছাত্র অধিকার,নির্বাচনী ইশতেহার,ডাকসু নির্বাচনের প্রার্থী তালিকা,ঢাকা বিশ্ববিদ্যালয় হল প্রার্থী,বিএনপি ছাত্রদল কেন্দ্রীয় প্রার্থী,ডাকসু ভিপি,ডাকসু ভিপি প্রার্থী';

    } elseif (is_page('manifesto')) {
        $meta_title = 'ইশতেহার - ' . $site_name;
        $meta_description = 'বাংলাদেশ জাতীয়তাবাদী ছাত্রদলের ডাকসু নির্বাচনী ইশতেহার। ছাত্র-ছাত্রীদের অধিকার ও কল্যাণের জন্য আমাদের প্রতিশ্রুতি।';
        $meta_keywords = 'নির্বাচনী ইশতেহার, ছাত্র অধিকার, শিক্ষা সংস্কার, ক্যাম্পাস উন্নয়ন, ডাকসু নির্বাচন ২০২৫,বাংলাদেশ জাতীয়তাবাদী ছাত্রদল,ঢাকা বিশ্ববিদ্যালয় ছাত্র সংসদ,কেন্দ্রীয় প্যানেল,হল প্যানেল,ছাত্র অধিকার,নির্বাচনী ইশতেহার,ডাকসু নির্বাচনের প্রার্থী তালিকা,ঢাকা বিশ্ববিদ্যালয় হল প্রার্থী,বিএনপি ছাত্রদল কেন্দ্রীয় প্রার্থী,ডাকসু ভিপি,ডাকসু ভিপি প্রার্থী';

    } elseif (is_page('contact')) {
        $meta_title = 'যোগাযোগ - ' . $site_name;
        $meta_description = 'বাংলাদেশ জাতীয়তাবাদী ছাত্রদলের সাথে যোগাযোগ করুন। আপনার মতামত ও পরামর্শ আমাদের কাছে পৌঁছে দিন।';
        $meta_keywords = 'যোগাযোগ, ছাত্রদল যোগাযোগ, ফিডব্যাক, মতামত, ডাকসু নির্বাচন ২০২৫,বাংলাদেশ জাতীয়তাবাদী ছাত্রদল,ঢাকা বিশ্ববিদ্যালয় ছাত্র সংসদ,কেন্দ্রীয় প্যানেল,হল প্যানেল,ছাত্র অধিকার,নির্বাচনী ইশতেহার,ডাকসু নির্বাচনের প্রার্থী তালিকা,ঢাকা বিশ্ববিদ্যালয় হল প্রার্থী,বিএনপি ছাত্রদল কেন্দ্রীয় প্রার্থী,ডাকসু ভিপি,ডাকসু ভিপি প্রার্থী';

    } elseif (is_single() && get_post_type() == 'central_candidate') {
        $candidate_name = get_post_meta(get_the_ID(), '_candidate_name', true);
        $position = get_post_meta(get_the_ID(), '_candidate_position', true);
        $candidate_name = $candidate_name ? $candidate_name : get_the_title();
        $position = $position ? $position : '';

        $meta_title = $candidate_name . ($position ? ' - ' . $position . ' প্রার্থী' : '') . ' - ' . $site_name;
        $meta_description = $candidate_name . ' বাংলাদেশ জাতীয়তাবাদী ছাত্রদলের ' . ($position ? $position . ' পদের ' : '') . 'প্রার্থী। তার শিক্ষাগত যোগ্যতা, রাজনৈতিক পরিচয় ও দৃষ্টিভঙ্গি সম্পর্কে জানুন।';
        $meta_keywords = $candidate_name . ($position ? ', ' . $position : '') . ', কেন্দ্রীয় প্রার্থী, ছাত্রদল প্রার্থী';
        $og_image_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
        $og_image = $og_image_url ? $og_image_url : '';

    } elseif (is_single() && get_post_type() == 'hall_candidate') {
        $candidate_name = get_post_meta(get_the_ID(), '_candidate_name', true);
        $hall_name = get_post_meta(get_the_ID(), '_candidate_hall', true);
        $candidate_name = $candidate_name ? $candidate_name : get_the_title();
        $hall_name = $hall_name ? $hall_name : '';

        $meta_title = $candidate_name . ($hall_name ? ' - ' . $hall_name . ' প্রার্থী' : '') . ' - ' . $site_name;
        $meta_description = $candidate_name . ' বাংলাদেশ জাতীয়তাবাদী ছাত্রদলের ' . ($hall_name ? $hall_name . ' এর ' : '') . 'প্রার্থী। তার পরিচিতি ও পরিকল্পনা সম্পর্কে জানুন।';
        $meta_keywords = $candidate_name . ($hall_name ? ', ' . $hall_name : '') . ', হল প্রার্থী, ছাত্রদল';
        $og_image_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
        $og_image = $og_image_url ? $og_image_url : '';

    } else {
        // Default fallback
        if (is_object($post) && isset($post->post_title)) {
            $meta_title = $post->post_title ? $post->post_title . ' - ' . $site_name : $site_name;
        } else {
            $meta_title = $site_name;
        }

        if (is_object($post) && isset($post->post_excerpt) && $post->post_excerpt) {
            $meta_description = wp_trim_words(strip_tags($post->post_excerpt), 25);
        } else {
            $meta_description = $site_description;
        }
    }

    // Check for custom SEO meta fields
    if (is_object($post) && isset($post->ID)) {
        $custom_title = get_post_meta($post->ID, '_seo_title', true);
        $custom_description = get_post_meta($post->ID, '_seo_description', true);
        $custom_keywords = get_post_meta($post->ID, '_seo_keywords', true);
        $custom_canonical = get_post_meta($post->ID, '_seo_canonical', true);

        if ($custom_title) {
            $meta_title = $custom_title;
        }
        if ($custom_description) {
            $meta_description = $custom_description;
        }
        if ($custom_keywords) {
            $meta_keywords = $custom_keywords;
        }
        if ($custom_canonical) {
            $canonical_url = $custom_canonical;
        }
    }

    // Set canonical URL if not set
    if (empty($canonical_url)) {
        if (is_object($post) && isset($post->ID)) {
            $canonical_url = get_permalink($post->ID);
        } else {
            $canonical_url = $current_url;
        }
    }

    // Set default OG image if not set
    if (empty($og_image)) {
        $og_image = get_template_directory_uri() . '/assets/images/ducsu-default-og.jpg';
    }

    // Sanitize all values
    $meta_title = sanitize_text_field($meta_title);
    $meta_description = sanitize_text_field($meta_description);
    $meta_keywords = sanitize_text_field($meta_keywords);
    $canonical_url = esc_url($canonical_url);
    $og_image = esc_url($og_image);

    // Check for noindex
    $noindex = false;
    if (is_object($post) && isset($post->ID)) {
        $noindex = get_post_meta($post->ID, '_seo_noindex', true);
    }

    // Output meta tags
    ?>
    <!-- SEO Meta Tags -->
    <?php if (!empty($meta_title)): ?>
        <title><?php echo esc_html($meta_title); ?></title>
    <?php endif; ?>

    <?php if (!empty($meta_description)): ?>
        <meta name="description" content="<?php echo esc_attr($meta_description); ?>">
    <?php endif; ?>

    <?php if (!empty($meta_keywords)): ?>
        <meta name="keywords" content="<?php echo esc_attr($meta_keywords); ?>">
    <?php endif; ?>

    <meta name="robots" content="<?php echo $noindex ? 'noindex, nofollow' : 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1'; ?>">

    <?php if (!empty($canonical_url)): ?>
        <link rel="canonical" href="<?php echo esc_url($canonical_url); ?>">
    <?php endif; ?>

    <!-- Open Graph Meta Tags -->
    <meta property="og:locale" content="bn_BD">
    <meta property="og:type" content="website">

    <?php if (!empty($meta_title)): ?>
        <meta property="og:title" content="<?php echo esc_attr($meta_title); ?>">
    <?php endif; ?>

    <?php if (!empty($meta_description)): ?>
        <meta property="og:description" content="<?php echo esc_attr($meta_description); ?>">
    <?php endif; ?>

    <?php if (!empty($canonical_url)): ?>
        <meta property="og:url" content="<?php echo esc_url($canonical_url); ?>">
    <?php endif; ?>

    <?php if (!empty($site_name)): ?>
        <meta property="og:site_name" content="<?php echo esc_attr($site_name); ?>">
    <?php endif; ?>

    <?php if (!empty($og_image)): ?>
        <meta property="og:image" content="<?php echo esc_url($og_image); ?>">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">
        <meta property="og:image:alt" content="<?php echo esc_attr($meta_title); ?>">
    <?php endif; ?>

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">

    <?php if (!empty($meta_title)): ?>
        <meta name="twitter:title" content="<?php echo esc_attr($meta_title); ?>">
    <?php endif; ?>

    <?php if (!empty($meta_description)): ?>
        <meta name="twitter:description" content="<?php echo esc_attr($meta_description); ?>">
    <?php endif; ?>

    <?php if (!empty($og_image)): ?>
        <meta name="twitter:image" content="<?php echo esc_url($og_image); ?>">
    <?php endif; ?>

    <!-- Additional SEO Tags -->
    <meta name="author" content="বাংলাদেশ জাতীয়তাবাদী ছাত্রদল">
    <meta name="publisher" content="বাংলাদেশ জাতীয়তাবাদী ছাত্রদল">
    <meta name="language" content="Bengali">
    <meta name="geo.region" content="BD">
    <meta name="geo.placename" content="Dhaka, Bangladesh">

    <!-- Schema.org Structured Data -->
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Organization",
            "name": "বাংলাদেশ জাতীয়তাবাদী ছাত্রদল",
            "alternateName": "Bangladesh Jatiyotabadi Chatradol",
            "url": "<?php echo esc_url(home_url()); ?>",
        "logo": "<?php echo esc_url(get_template_directory_uri() . '/assets/images/jcd-logo.png'); ?>",
        "description": "বাংলাদেশ জাতীয়তাবাদী ছাত্রদল ঢাকা বিশ্ববিদ্যালয় শাখা",
        "address": {
            "@type": "PostalAddress",
            "addressLocality": "Dhaka",
            "addressCountry": "Bangladesh"
        },
        "sameAs": [
            "https://www.facebook.com/JCDChatradol",
            "https://twitter.com/JCDChatradol"
        ]
    }
    </script>

    <?php if (is_single() && get_post_type() == 'central_candidate'): ?>
        <?php
        $candidate_name_js = get_post_meta(get_the_ID(), '_candidate_name', true);
        $position_js = get_post_meta(get_the_ID(), '_candidate_position', true);
        $candidate_name_js = $candidate_name_js ? $candidate_name_js : get_the_title();
        ?>
        <script type="application/ld+json">
            {
                "@context": "https://schema.org",
                "@type": "Person",
                "name": "<?php echo esc_js($candidate_name_js); ?>",
        "description": "<?php echo esc_js($meta_description); ?>",
            <?php if (!empty($og_image)): ?>
        "image": "<?php echo esc_url($og_image); ?>",
        <?php endif; ?>
        <?php if ($position_js): ?>
        "jobTitle": "<?php echo esc_js($position_js); ?> প্রার্থী",
        <?php endif; ?>
            "affiliation": {
                "@type": "Organization",
                "name": "বাংলাদেশ জাতীয়তাবাদী ছাত্রদল"
            },
            "url": "<?php echo esc_url(get_permalink()); ?>"
    }
        </script>
    <?php endif; ?>

    <?php
}
add_action('wp_head', 'ducsu_seo_meta_tags', 1);

/**
 * Add Custom Meta Fields for SEO in Admin
 */
function ducsu_add_seo_meta_box() {
    $post_types = array('page', 'central_candidate', 'hall_candidate', 'manifesto');

    foreach ($post_types as $post_type) {
        add_meta_box(
            'ducsu_seo_meta',
            'SEO Settings',
            'ducsu_seo_meta_callback',
            $post_type,
            'normal',
            'high'
        );
    }
}
add_action('add_meta_boxes', 'ducsu_add_seo_meta_box');

/**
 * SEO Meta Box Callback
 */
function ducsu_seo_meta_callback($post) {
    wp_nonce_field('ducsu_seo_meta_nonce', 'seo_meta_nonce');

    $seo_title = get_post_meta($post->ID, '_seo_title', true);
    $seo_description = get_post_meta($post->ID, '_seo_description', true);
    $seo_keywords = get_post_meta($post->ID, '_seo_keywords', true);
    $seo_canonical = get_post_meta($post->ID, '_seo_canonical', true);
    $seo_noindex = get_post_meta($post->ID, '_seo_noindex', true);

    ?>
    <table class="form-table">
        <tr>
            <th><label for="seo_title">SEO Title</label></th>
            <td>
                <input type="text" id="seo_title" name="seo_title" value="<?php echo esc_attr($seo_title); ?>"
                       class="widefat" maxlength="60" placeholder="Maximum 60 characters">
                <p class="description">Leave empty to use default title</p>
            </td>
        </tr>
        <tr>
            <th><label for="seo_description">Meta Description</label></th>
            <td>
                <textarea id="seo_description" name="seo_description" class="widefat" rows="3"
                          maxlength="160" placeholder="Maximum 160 characters"><?php echo esc_textarea($seo_description); ?></textarea>
                <p class="description">Leave empty to use excerpt or auto-generated description</p>
            </td>
        </tr>
        <tr>
            <th><label for="seo_keywords">Meta Keywords</label></th>
            <td>
                <input type="text" id="seo_keywords" name="seo_keywords" value="<?php echo esc_attr($seo_keywords); ?>"
                       class="widefat" placeholder="keyword1, keyword2, keyword3">
                <p class="description">Comma-separated keywords (optional)</p>
            </td>
        </tr>
        <tr>
            <th><label for="seo_canonical">Canonical URL</label></th>
            <td>
                <input type="url" id="seo_canonical" name="seo_canonical" value="<?php echo esc_attr($seo_canonical); ?>"
                       class="widefat" placeholder="https://example.com/page">
                <p class="description">Leave empty to use default permalink</p>
            </td>
        </tr>
        <tr>
            <th><label for="seo_noindex">Search Engine Visibility</label></th>
            <td>
                <label>
                    <input type="checkbox" id="seo_noindex" name="seo_noindex" value="1" <?php checked($seo_noindex, 1); ?>>
                    Hide from search engines (noindex)
                </label>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Save SEO Meta Fields
 */
function ducsu_save_seo_meta($post_id) {
    // Check nonce
    if (!isset($_POST['seo_meta_nonce']) || !wp_verify_nonce($_POST['seo_meta_nonce'], 'ducsu_seo_meta_nonce')) {
        return;
    }

    // Check permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Avoid infinite loops
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    $fields = array('seo_title', 'seo_description', 'seo_keywords', 'seo_canonical', 'seo_noindex');

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            $value = sanitize_text_field($_POST[$field]);
            update_post_meta($post_id, '_' . $field, $value);
        } else {
            // Handle checkbox (seo_noindex)
            if ($field === 'seo_noindex') {
                delete_post_meta($post_id, '_' . $field);
            }
        }
    }
}
add_action('save_post', 'ducsu_save_seo_meta');

/**
 * Generate XML Sitemap
 */
function ducsu_generate_sitemap() {
    if (get_query_var('sitemap') == 'xml') {
        header('Content-Type: application/xml; charset=utf-8');

        echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // Homepage
        echo '<url>';
        echo '<loc>' . esc_url(home_url()) . '</loc>';
        echo '<lastmod>' . date('Y-m-d\TH:i:s+00:00') . '</lastmod>';
        echo '<changefreq>daily</changefreq>';
        echo '<priority>1.0</priority>';
        echo '</url>';

        // Pages
        $pages = get_pages(array('post_status' => 'publish'));
        if ($pages) {
            foreach ($pages as $page) {
                echo '<url>';
                echo '<loc>' . esc_url(get_permalink($page->ID)) . '</loc>';
                echo '<lastmod>' . date('Y-m-d\TH:i:s+00:00', strtotime($page->post_modified)) . '</lastmod>';
                echo '<changefreq>weekly</changefreq>';
                echo '<priority>0.8</priority>';
                echo '</url>';
            }
        }

        // Central Candidates
        $central_candidates = get_posts(array(
            'post_type' => 'central_candidate',
            'post_status' => 'publish',
            'numberposts' => -1
        ));
        if ($central_candidates) {
            foreach ($central_candidates as $candidate) {
                echo '<url>';
                echo '<loc>' . esc_url(get_permalink($candidate->ID)) . '</loc>';
                echo '<lastmod>' . date('Y-m-d\TH:i:s+00:00', strtotime($candidate->post_modified)) . '</lastmod>';
                echo '<changefreq>monthly</changefreq>';
                echo '<priority>0.7</priority>';
                echo '</url>';
            }
        }

        // Hall Candidates
        $hall_candidates = get_posts(array(
            'post_type' => 'hall_candidate',
            'post_status' => 'publish',
            'numberposts' => -1
        ));
        if ($hall_candidates) {
            foreach ($hall_candidates as $candidate) {
                echo '<url>';
                echo '<loc>' . esc_url(get_permalink($candidate->ID)) . '</loc>';
                echo '<lastmod>' . date('Y-m-d\TH:i:s+00:00', strtotime($candidate->post_modified)) . '</lastmod>';
                echo '<changefreq>monthly</changefreq>';
                echo '<priority>0.6</priority>';
                echo '</url>';
            }
        }

        echo '</urlset>';
        exit;
    }
}
add_action('template_redirect', 'ducsu_generate_sitemap');

/**
 * Add Sitemap Rewrite Rule
 */
function ducsu_sitemap_rewrite() {
    add_rewrite_rule('^sitemap\.xml$', 'index.php?sitemap=xml', 'top');
}
add_action('init', 'ducsu_sitemap_rewrite');

/**
 * Add Sitemap Query Var
 */
function ducsu_sitemap_query_vars($vars) {
    $vars[] = 'sitemap';
    return $vars;
}
add_filter('query_vars', 'ducsu_sitemap_query_vars');

/**
 * Add robots.txt
 */
function ducsu_robots_txt() {
    if (get_query_var('robots') == 'txt') {
        header('Content-Type: text/plain; charset=utf-8');

        echo "User-agent: *\n";
        echo "Allow: /\n";
        echo "Disallow: /wp-admin/\n";
        echo "Disallow: /wp-includes/\n";
        echo "Disallow: /wp-content/plugins/\n";
        echo "Disallow: /wp-content/themes/\n";
        echo "Allow: /wp-content/uploads/\n";
        echo "\n";
        echo "Sitemap: " . esc_url(home_url()) . "/sitemap.xml\n";

        exit;
    }
}
add_action('template_redirect', 'ducsu_robots_txt');

/**
 * Add robots.txt Rewrite Rule
 */
function ducsu_robots_rewrite() {
    add_rewrite_rule('^robots\.txt$', 'index.php?robots=txt', 'top');
}
add_action('init', 'ducsu_robots_rewrite');

/**
 * Add robots Query Var
 */
function ducsu_robots_query_vars($vars) {
    $vars[] = 'robots';
    return $vars;
}
add_filter('query_vars', 'ducsu_robots_query_vars');

/**
 * Flush rewrite rules on activation (add this to your theme activation)
 */
function ducsu_flush_rewrite_rules() {
    ducsu_sitemap_rewrite();
    ducsu_robots_rewrite();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'ducsu_flush_rewrite_rules');
?>