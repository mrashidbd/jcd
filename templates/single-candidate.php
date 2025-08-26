<?php
/**
 * Template for single candidate posts
 * Used for both central_candidate and hall_candidate post-types
 */

get_header();

if (have_posts()) :
    while (have_posts()) : the_post();

        $post_type = get_post_type();
        $is_central = ($post_type === 'central_candidate');
        $panel_type = $is_central ? 'কেন্দ্রীয় প্যানেল' : 'হল প্যানেল';
        $back_url = $is_central ? '/central-panel' : '/hall-panels';

        // Get all candidate meta
        $name_bangla = get_post_meta(get_the_ID(), '_candidate_name_bangla', true);
        $position = get_post_meta(get_the_ID(), '_candidate_position', true);
        $ballot_number = get_post_meta(get_the_ID(), '_candidate_ballot_number', true);
        $department = get_post_meta(get_the_ID(), '_candidate_department', true);
        $hall = get_post_meta(get_the_ID(), '_candidate_hall', true);
        $session = get_post_meta(get_the_ID(), '_candidate_session', true);
        $father_name = get_post_meta(get_the_ID(), '_candidate_father_name', true);
        $father_profession = get_post_meta(get_the_ID(), '_candidate_father_profession', true);
        $mother_name = get_post_meta(get_the_ID(), '_candidate_mother_name', true);
        $mother_profession = get_post_meta(get_the_ID(), '_candidate_mother_profession', true);
        $permanent_address = get_post_meta(get_the_ID(), '_candidate_permanent_address', true);
        $ssc_info = get_post_meta(get_the_ID(), '_candidate_ssc_info', true);
        $hsc_info = get_post_meta(get_the_ID(), '_candidate_hsc_info', true);
        $graduation_info = get_post_meta(get_the_ID(), '_candidate_graduation_info', true);
        $special_achievements = get_post_meta(get_the_ID(), '_candidate_special_achievements', true);
        $political_journey = get_post_meta(get_the_ID(), '_candidate_political_journey', true);
        $vision = get_post_meta(get_the_ID(), '_candidate_vision', true);
        $facebook_url = get_post_meta(get_the_ID(), '_candidate_facebook_url', true);
        $twitter_url = get_post_meta(get_the_ID(), '_candidate_twitter_url', true);
        $candidate_email = get_post_meta(get_the_ID(), '_candidate_email', true);
        $gallery_ids = get_post_meta(get_the_ID(), '_candidate_gallery', true);
        ?>

        <!-- Breadcrumb -->
        <section class="breadcrumb bg-gray-100 py-4">
            <div class="container mx-auto px-4">
                <nav class="flex items-center text-sm text-gray-600">
                    <a href="/" class="hover:text-primary-green transition-colors">হোম</a>
                    <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                    <a href="<?php echo $back_url; ?>" class="hover:text-primary-green transition-colors"><?php echo $panel_type; ?></a>
                    <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                    <span class="text-gray-800 font-medium"><?php echo esc_html($name_bangla ?: get_the_title()); ?></span>
                </nav>
            </div>
        </section>

        <!-- Hero Section -->
        <section class="candidate-hero bg-gradient-to-r from-primary-blue to-primary-green py-16">
            <div class="container mx-auto px-4">
                <div class="max-w-6xl mx-auto">
                    <div class="flex items-center mb-8">
                        <a href="<?php echo $back_url; ?>"
                           class="text-white hover:text-gray-200 transition-colors mr-4 p-2 rounded-full hover:bg-white/20">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </a>
                        <span class="bg-white/20 text-white px-4 py-2 rounded-full text-sm font-medium">
                    <?php echo $panel_type; ?>
                </span>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-center">
                        <div class="lg:col-span-2">
                            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                                <?php echo esc_html($name_bangla ?: get_the_title()); ?>
                            </h1>
                            <p class="text-xl md:text-2xl text-white/90 mb-4"><?php echo esc_html($position); ?></p>

                            <div class="flex flex-wrap items-center gap-4 mb-6">
                                <?php if ($ballot_number) : ?>
                                    <div class="bg-primary-red text-white px-6 py-2 rounded-full font-bold text-lg">
                                        ব্যালট নং: <?php echo esc_html($ballot_number); ?>
                                    </div>
                                <?php endif; ?>

                                <?php if ($department) : ?>
                                    <div class="bg-white/20 text-white px-4 py-2 rounded-full text-sm">
                                        <?php echo esc_html($department); ?>
                                    </div>
                                <?php endif; ?>

                                <?php if ($hall) : ?>
                                    <div class="bg-white/20 text-white px-4 py-2 rounded-full text-sm">
                                        <?php echo esc_html($hall); ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Social Links -->
                            <?php if ($facebook_url || $twitter_url) : ?>
                                <div class="flex space-x-4">
                                    <?php if ($facebook_url) : ?>
                                        <a href="<?php echo esc_url($facebook_url); ?>" target="_blank"
                                           class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors flex items-center">
                                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                            </svg>
                                            Facebook
                                        </a>
                                    <?php endif; ?>

                                    <?php if ($twitter_url) : ?>
                                        <a href="<?php echo esc_url($twitter_url); ?>" target="_blank"
                                           class="bg-blue-400 hover:bg-blue-500 text-white px-6 py-3 rounded-lg font-medium transition-colors flex items-center">
                                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                            </svg>
                                            Twitter
                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="lg:col-span-1 flex justify-center lg:justify-end">
                            <div class="relative">
                                <?php if (has_post_thumbnail()) : ?>
                                    <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'large'); ?>"
                                         alt="<?php echo esc_attr($name_bangla ?: get_the_title()); ?>"
                                         class="w-80 h-80 object-cover rounded-2xl shadow-2xl border-4 border-white/20">
                                <?php else : ?>
                                    <div class="w-80 h-80 bg-white/20 rounded-2xl shadow-2xl border-4 border-white/20 flex items-center justify-center">
                                        <svg class="w-32 h-32 text-white/50" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Content -->
        <section class="candidate-details py-16">
            <div class="container mx-auto px-4">
                <div class="max-w-6xl mx-auto">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

                        <!-- Main Content -->
                        <div class="lg:col-span-2 space-y-12">

                            <!-- Biography -->
                            <?php if (get_the_content()) : ?>
                                <div class="content-section">
                                    <h2 class="text-3xl font-bold text-gray-800 mb-6">জীবনী</h2>
                                    <div class="prose prose-lg max-w-none text-gray-700">
                                        <?php the_content(); ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Vision -->
                            <?php if ($vision) : ?>
                                <div class="content-section">
                                    <h2 class="text-3xl font-bold text-gray-800 mb-6">ভবিষ্যৎ পরিকল্পনা ও লক্ষ্য</h2>
                                    <div class="bg-gradient-to-r from-primary-green/10 to-primary-blue/10 rounded-2xl p-8">
                                        <p class="text-gray-700 leading-relaxed text-lg">
                                            <?php echo nl2br(esc_html($vision)); ?>
                                        </p>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Political Journey -->
                            <?php if ($political_journey) : ?>
                                <div class="content-section">
                                    <h2 class="text-3xl font-bold text-gray-800 mb-6">রাজনৈতিক যাত্রা</h2>
                                    <div class="bg-white rounded-xl shadow-lg p-8">
                                        <p class="text-gray-700 leading-relaxed">
                                            <?php echo nl2br(esc_html($political_journey)); ?>
                                        </p>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Special Achievements -->
                            <?php if ($special_achievements) : ?>
                                <div class="content-section">
                                    <h2 class="text-3xl font-bold text-gray-800 mb-6">বিশেষ অর্জন</h2>
                                    <div class="bg-white rounded-xl shadow-lg p-8">
                                        <p class="text-gray-700 leading-relaxed">
                                            <?php echo nl2br(esc_html($special_achievements)); ?>
                                        </p>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Gallery -->
                            <?php if ($gallery_ids) : ?>
                                <div class="content-section">
                                    <h2 class="text-3xl font-bold text-gray-800 mb-6">ছবি গ্যালারি</h2>
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                        <?php
                                        $ids_array = explode(',', $gallery_ids);
                                        foreach ($ids_array as $image_id) :
                                            if ($image_id) :
                                                $image_url = wp_get_attachment_image_src($image_id, 'medium')[0];
                                                $full_url = wp_get_attachment_image_src($image_id, 'full')[0];
                                                ?>
                                                <div class="gallery-item cursor-pointer group">
                                                    <img src="<?php echo esc_url($image_url); ?>"
                                                         alt="Gallery Image"
                                                         class="w-full h-40 object-cover rounded-lg shadow-lg group-hover:shadow-xl transition-shadow duration-300"
                                                         onclick="openImageModal('<?php echo esc_url($full_url); ?>')">
                                                </div>
                                            <?php
                                            endif;
                                        endforeach;
                                        ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Sidebar -->
                        <div class="lg:col-span-1">
                            <div class="sticky top-8 space-y-8">

                                <!-- Quick Info Card -->
                                <div class="bg-white rounded-xl shadow-lg p-6">
                                    <h3 class="text-xl font-bold text-gray-800 mb-4">দ্রুত তথ্য</h3>
                                    <div class="space-y-3">
                                        <?php if ($department) : ?>
                                            <div class="flex items-center">
                                                <svg class="w-5 h-5 text-primary-green mr-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.84L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.84l-7-3z"></path>
                                                </svg>
                                                <div>
                                                    <p class="text-sm text-gray-500">বিভাগ</p>
                                                    <p class="font-medium text-gray-800"><?php echo esc_html($department); ?></p>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <?php if ($hall) : ?>
                                            <div class="flex items-center">
                                                <svg class="w-5 h-5 text-primary-green mr-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.84L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.84l-7-3z"></path>
                                                </svg>
                                                <div>
                                                    <p class="text-sm text-gray-500">হল</p>
                                                    <p class="font-medium text-gray-800"><?php echo esc_html($hall); ?></p>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <?php if ($session) : ?>
                                            <div class="flex items-center">
                                                <svg class="w-5 h-5 text-primary-green mr-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                                </svg>
                                                <div>
                                                    <p class="text-sm text-gray-500">সেশন</p>
                                                    <p class="font-medium text-gray-800"><?php echo esc_html($session); ?></p>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Educational Background -->
                                <?php if ($ssc_info || $hsc_info || $graduation_info) : ?>
                                    <div class="bg-white rounded-xl shadow-lg p-6">
                                        <h3 class="text-xl font-bold text-gray-800 mb-4">শিক্ষাগত যোগ্যতা</h3>
                                        <div class="space-y-4">
                                            <?php if ($graduation_info) : ?>
                                                <div class="border-l-4 border-primary-green pl-4">
                                                    <h4 class="font-semibold text-gray-800">স্নাতক</h4>
                                                    <p class="text-gray-600 text-sm"><?php echo esc_html($graduation_info); ?></p>
                                                </div>
                                            <?php endif; ?>

                                            <?php if ($hsc_info) : ?>
                                                <div class="border-l-4 border-primary-blue pl-4">
                                                    <h4 class="font-semibold text-gray-800">এইচএসসি</h4>
                                                    <p class="text-gray-600 text-sm"><?php echo esc_html($hsc_info); ?></p>
                                                </div>
                                            <?php endif; ?>

                                            <?php if ($ssc_info) : ?>
                                                <div class="border-l-4 border-primary-red pl-4">
                                                    <h4 class="font-semibold text-gray-800">এসএসসি</h4>
                                                    <p class="text-gray-600 text-sm"><?php echo esc_html($ssc_info); ?></p>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <!-- Family Info -->
                                <?php if ($father_name || $mother_name || $permanent_address) : ?>
                                    <div class="bg-white rounded-xl shadow-lg p-6">
                                        <h3 class="text-xl font-bold text-gray-800 mb-4">পারিবারিক তথ্য</h3>
                                        <div class="space-y-3">
                                            <?php if ($father_name) : ?>
                                                <div>
                                                    <p class="text-sm text-gray-500">পিতার নাম</p>
                                                    <p class="font-medium text-gray-800"><?php echo esc_html($father_name); ?></p>
                                                    <?php if ($father_profession) : ?>
                                                        <p class="text-xs text-gray-600">পেশা: <?php echo esc_html($father_profession); ?></p>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>

                                            <?php if ($mother_name) : ?>
                                                <div>
                                                    <p class="text-sm text-gray-500">মাতার নাম</p>
                                                    <p class="font-medium text-gray-800"><?php echo esc_html($mother_name); ?></p>
                                                    <?php if ($mother_profession) : ?>
                                                        <p class="text-xs text-gray-600">পেশা: <?php echo esc_html($mother_profession); ?></p>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>

                                            <?php if ($permanent_address) : ?>
                                                <div>
                                                    <p class="text-sm text-gray-500">স্থায়ী ঠিকানা</p>
                                                    <p class="font-medium text-gray-800"><?php echo esc_html($permanent_address); ?></p>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <!-- Contact Form -->
                                <?php if ($candidate_email) : ?>
                                    <div class="bg-gradient-to-br from-primary-green/5 to-primary-blue/5 rounded-xl p-6">
                                        <h3 class="text-xl font-bold text-gray-800 mb-4">যোগাযোগ করুন</h3>
                                        <form id="single-candidate-contact-form" class="space-y-4">
                                            <input type="hidden" name="candidate_id" value="<?php echo get_the_ID(); ?>">

                                            <div>
                                                <input type="text" name="name" placeholder="আপনার নাম" required
                                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent">
                                            </div>

                                            <div>
                                                <input type="email" name="email" placeholder="ইমেইল ঠিকানা" required
                                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent">
                                            </div>

                                            <div>
                                                <input type="tel" name="phone" placeholder="ফোন নম্বর"
                                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent">
                                            </div>

                                            <div>
                                        <textarea name="message" rows="4" placeholder="আপনার বার্তা" required
                                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent resize-none"></textarea>
                                            </div>

                                            <button type="submit"
                                                    class="w-full bg-primary-green hover:bg-primary-blue text-white font-bold py-3 px-6 rounded-lg transition-all duration-300 flex items-center justify-center">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                                </svg>
                                                বার্তা পাঠান
                                            </button>
                                        </form>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Related Candidates -->
        <section class="related-candidates py-16 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="max-w-6xl mx-auto">
                    <h2 class="text-3xl font-bold text-gray-800 text-center mb-12">অন্যান্য প্রার্থী</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <?php
                        $related_args = [
                            'post_type' => $post_type,
                            'posts_per_page' => 3,
                            'post_status' => 'publish',
                            'post__not_in' => [get_the_ID()],
                            'orderby' => 'rand'
                        ];

                        $related_query = new WP_Query($related_args);

                        if ($related_query->have_posts()) :
                            while ($related_query->have_posts()) : $related_query->the_post();
                                $related_name = get_post_meta(get_the_ID(), '_candidate_name_bangla', true);
                                $related_position = get_post_meta(get_the_ID(), '_candidate_position', true);
                                $related_ballot = get_post_meta(get_the_ID(), '_candidate_ballot_number', true);
                                ?>
                                <div class="candidate-card bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transform hover:scale-105 transition-all duration-300">
                                    <div class="aspect-w-4 aspect-h-3">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'medium'); ?>"
                                                 alt="<?php echo esc_attr($related_name ?: get_the_title()); ?>"
                                                 class="w-full h-48 object-cover">
                                        <?php else : ?>
                                            <div class="w-full h-48 bg-gradient-to-br from-primary-green to-primary-blue flex items-center justify-center">
                                                <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="p-6">
                                        <h3 class="text-xl font-bold text-gray-800 mb-2"><?php echo esc_html($related_name ?: get_the_title()); ?></h3>
                                        <p class="text-primary-green font-medium mb-4"><?php echo esc_html($related_position); ?></p>

                                        <div class="flex justify-between items-center">
                                            <?php if ($related_ballot) : ?>
                                                <span class="text-sm text-gray-600">ব্যালট নং: <?php echo esc_html($related_ballot); ?></span>
                                            <?php endif; ?>
                                            <a href="<?php the_permalink(); ?>"
                                               class="bg-primary-green hover:bg-primary-red text-white px-4 py-2 rounded-full text-sm font-medium transition-colors">
                                                বিস্তারিত
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            endwhile;
                            wp_reset_postdata();
                        endif;
                        ?>
                    </div>

                    <div class="text-center mt-12">
                        <a href="<?php echo $back_url; ?>"
                           class="inline-flex items-center bg-primary-blue hover:bg-primary-green text-white font-bold py-3 px-8 rounded-lg transition-all duration-300 transform hover:scale-105">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            সব প্রার্থী দেখুন
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Image Modal -->
        <div id="image-modal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-75 flex items-center justify-center p-4">
            <div class="relative max-w-4xl max-h-full">
                <button id="close-image-modal" class="absolute top-4 right-4 text-white hover:text-gray-300 z-10">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                <img id="modal-image" src="" alt="Gallery Image" class="max-w-full max-h-full object-contain">
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Single candidate contact form
                const contactForm = document.getElementById('single-candidate-contact-form');
                if (contactForm) {
                    contactForm.addEventListener('submit', async function(e) {
                        e.preventDefault();

                        const submitBtn = this.querySelector('button[type="submit"]');
                        const originalText = submitBtn.innerHTML;

                        submitBtn.disabled = true;
                        submitBtn.innerHTML = `
                <svg class="animate-spin w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                পাঠানো হচ্ছে...
            `;

                        try {
                            const formData = new FormData(this);
                            formData.append('action', 'ducsu_contact');
                            formData.append('nonce', ducsu_ajax.nonce);

                            const response = await fetch(ducsu_ajax.ajax_url, {
                                method: 'POST',
                                body: formData
                            });

                            const data = await response.json();

                            if (data.success) {
                                showNotification(data.data, 'success');
                                this.reset();
                            } else {
                                showNotification(data.data, 'error');
                            }
                        } catch (error) {
                            console.error('Contact form error:', error);
                            showNotification('বার্তা পাঠাতে সমস্যা হয়েছে। পরে চেষ্টা করুন।', 'error');
                        } finally {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalText;
                        }
                    });
                }

                // Image modal functionality
                const imageModal = document.getElementById('image-modal');
                const modalImage = document.getElementById('modal-image');
                const closeImageModal = document.getElementById('close-image-modal');

                window.openImageModal = function(imageUrl) {
                    modalImage.src = imageUrl;
                    imageModal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                };

                function closeModal() {
                    imageModal.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                }

                if (closeImageModal) {
                    closeImageModal.addEventListener('click', closeModal);
                }

                if (imageModal) {
                    imageModal.addEventListener('click', function(e) {
                        if (e.target === imageModal) {
                            closeModal();
                        }
                    });
                }

                // Close modal on escape key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && !imageModal.classList.contains('hidden')) {
                        closeModal();
                    }
                });

                // Notification function
                function showNotification(message, type = 'info') {
                    const notification = document.createElement('div');
                    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full ${
                        type === 'error' ? 'bg-red-500 text-white' :
                            type === 'success' ? 'bg-green-500 text-white' :
                                'bg-blue-500 text-white'
                    }`;

                    notification.innerHTML = `
            <div class="flex items-center space-x-3">
                ${type === 'error' ?
                        '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>' :
                        '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>'
                    }
                <span>${message}</span>
                <button class="ml-4 hover:opacity-75">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        `;

                    document.body.appendChild(notification);

                    // Show notification
                    setTimeout(() => {
                        notification.classList.remove('translate-x-full');
                    }, 100);

                    // Auto hide after 5 seconds
                    setTimeout(() => {
                        notification.classList.add('translate-x-full');
                        setTimeout(() => {
                            if (notification.parentNode) {
                                notification.parentNode.removeChild(notification);
                            }
                        }, 300);
                    }, 5000);

                    // Close button functionality
                    notification.querySelector('button').addEventListener('click', () => {
                        notification.classList.add('translate-x-full');
                        setTimeout(() => {
                            if (notification.parentNode) {
                                notification.parentNode.removeChild(notification);
                            }
                        }, 300);
                    });
                }
            });
        </script>

    <?php
    endwhile;
endif;

get_footer(); ?>