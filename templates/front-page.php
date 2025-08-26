<?php
/**
 * Template Name: Front Page
 * The main landing page template
 */

get_header(); ?>

    <!-- Hero Slider Section -->
    <section class="hero-slider relative overflow-hidden min-h-screen">
        <div class="slider-container relative w-full h-screen">
            <?php
            $sliders = ducsu_get_featured_sliders(5);
            $slider_count = 0;

            if ($sliders->have_posts()) :
                while ($sliders->have_posts()) : $sliders->the_post();
                    $subtitle = get_post_meta(get_the_ID(), '_slider_subtitle', true);
                    $cta_text = get_post_meta(get_the_ID(), '_slider_cta_text', true);
                    $cta_url = get_post_meta(get_the_ID(), '_slider_cta_url', true);
                    $slider_count++;
                    ?>
                    <div class="slider-item absolute inset-0 bg-cover bg-center bg-no-repeat <?php echo $slider_count === 1 ? 'active opacity-100' : 'opacity-0'; ?> transition-opacity duration-1000"
                         style="background-image: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('<?php echo get_the_post_thumbnail_url(get_the_ID(), 'full'); ?>');">

                        <div class="container mx-auto px-4 h-full flex items-center justify-center">
                            <div class="text-center text-white max-w-4xl">
                                <h1 class="text-5xl md:text-7xl font-bold mb-6 animate-fade-in-up"><?php the_title(); ?></h1>

                                <?php if ($subtitle) : ?>
                                    <p class="text-xl md:text-2xl mb-8 animate-fade-in-up animation-delay-200"><?php echo esc_html($subtitle); ?></p>
                                <?php endif; ?>

                                <?php if ($cta_text && $cta_url) : ?>
                                    <a href="<?php echo esc_url($cta_url); ?>"
                                       class="inline-block bg-primary-green hover:bg-primary-red text-white font-bold py-4 px-8 rounded-lg text-lg transition-all duration-300 transform hover:scale-105 animate-fade-in-up animation-delay-400">
                                        <?php echo esc_html($cta_text); ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>

        <!-- Slider Navigation -->
        <?php if ($slider_count > 1) : ?>
            <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 flex space-x-3">
                <?php for ($i = 1; $i <= $slider_count; $i++) : ?>
                    <button class="slider-dot w-3 h-3 rounded-full bg-white bg-opacity-50 hover:bg-opacity-100 transition-all duration-300 <?php echo $i === 1 ? 'bg-opacity-100' : ''; ?>"
                            data-slide="<?php echo $i; ?>"></button>
                <?php endfor; ?>
            </div>

            <button class="slider-prev absolute left-4 top-1/2 transform -translate-y-1/2 text-white hover:text-primary-green transition-colors duration-300">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>

            <button class="slider-next absolute right-4 top-1/2 transform -translate-y-1/2 text-white hover:text-primary-green transition-colors duration-300">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        <?php endif; ?>
    </section>

    <!-- Central Panel Section -->
    <section class="central-panel-section py-20 bg-cover bg-center relative"
             style="background-image: linear-gradient(rgba(43, 20, 115, 0.8), rgba(43, 20, 115, 0.8)), url('/ducsu/wp-content/themes/jcd-ducsu/assets/images/central-bg.jpg');">
        <div class="container mx-auto px-4">
            <div class="text-center text-white mb-12">
                <h2 class="text-4xl md:text-5xl font-bold mb-4">কেন্দ্রীয় প্যানেল</h2>
                <p class="text-lg md:text-xl mb-8">আমাদের দক্ষ এবং অভিজ্ঞ কেন্দ্রীয় প্যানেলের প্রার্থীদের সাথে পরিচিত হন</p>
                <a href="/central-panel"
                   class="inline-block bg-primary-red hover:bg-primary-green text-white font-bold py-3 px-8 rounded-lg text-lg transition-all duration-300 transform hover:scale-105">
                    সব প্রার্থী দেখুন
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-12">
                <?php
                $featured_candidates = ducsu_get_featured_central_candidates(6);

                if ($featured_candidates->have_posts()) :
                    while ($featured_candidates->have_posts()) : $featured_candidates->the_post();
                        $name_bangla = get_post_meta(get_the_ID(), '_candidate_name_bangla', true);
                        $position = get_post_meta(get_the_ID(), '_candidate_position', true);
                        $ballot_number = get_post_meta(get_the_ID(), '_candidate_ballot_number', true);
                        ?>
                        <div class="candidate-card bg-white rounded-lg shadow-lg overflow-hidden transform hover:scale-105 transition-all duration-300 cursor-pointer"
                             data-candidate-id="<?php echo get_the_ID(); ?>">
                            <div class="aspect-w-4 aspect-h-3">
                                <?php if (has_post_thumbnail()) : ?>
                                    <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'medium'); ?>"
                                         alt="<?php echo esc_attr($name_bangla); ?>"
                                         class="w-full h-48 object-cover">
                                <?php endif; ?>
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-800 mb-2"><?php echo esc_html($name_bangla ?: get_the_title()); ?></h3>
                                <p class="text-primary-blue font-medium mb-1"><?php echo esc_html($position); ?></p>
                                <?php if ($ballot_number) : ?>
                                    <p class="text-gray-600 text-sm">ব্যালট নং: <?php echo esc_html($ballot_number); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php
                    endwhile;
                    wp_reset_postdata();
                endif;
                ?>
            </div>
        </div>
    </section>

    <!-- Hall Panel Section -->
    <section class="hall-panel-section py-20 bg-cover bg-center relative"
             style="background-image: linear-gradient(rgba(11, 134, 69, 0.8), rgba(11, 134, 69, 0.8)), url('/ducsu/wp-content/themes/jcd-ducsu/assets/images/hall-bg.jpg');">
        <div class="container mx-auto px-4">
            <div class="text-center text-white mb-12">
                <h2 class="text-4xl md:text-5xl font-bold mb-4">হল প্যানেল</h2>
                <p class="text-lg md:text-xl mb-8">আপনার হলের প্রার্থীদের সাথে পরিচিত হন এবং তাদের ভবিষ্যৎ পরিকল্পনা জানুন</p>
                <a href="/hall-panels"
                   class="inline-block bg-primary-red hover:bg-primary-blue text-white font-bold py-3 px-8 rounded-lg text-lg transition-all duration-300 transform hover:scale-105">
                    সব হল দেখুন
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-12">
                <?php
                $featured_halls = ducsu_get_featured_halls(8);

                if ($featured_halls && !is_wp_error($featured_halls)) :
                    foreach ($featured_halls as $hall) :
                        $candidate_count = $hall->count;
                        ?>
                        <div class="hall-card bg-white rounded-lg shadow-lg p-6 text-center transform hover:scale-105 transition-all duration-300">
                            <div class="bg-primary-green text-white rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.84L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.84l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800 mb-2"><?php echo esc_html($hall->name); ?></h3>
                            <p class="text-gray-600 text-sm mb-4"><?php echo $candidate_count; ?>টি প্রার্থী</p>
                            <a href="/hall-panels/?hall=<?php echo $hall->term_id; ?>"
                               class="inline-block bg-primary-green hover:bg-primary-red text-white font-medium py-2 px-4 rounded transition-all duration-300">
                                বিস্তারিত দেখুন
                            </a>
                        </div>
                    <?php
                    endforeach;
                endif;
                ?>
            </div>
        </div>
    </section>

    <!-- Manifesto Section -->
    <section class="manifesto-section py-20 bg-cover bg-center relative"
             style="background-image: linear-gradient(rgba(232, 37, 44, 0.8), rgba(232, 37, 44, 0.8)), url('/ducsu/wp-content/themes/jcd-ducsu/assets/images/manifesto-bg.jpg');">
        <div class="container mx-auto px-4">
            <div class="text-center text-white mb-12">
                <h2 class="text-4xl md:text-5xl font-bold mb-4">আমাদের ইশতেহার</h2>
                <p class="text-lg md:text-xl mb-8">শিক্ষার্থীদের উন্নয়ন এবং কল্যাণের জন্য আমাদের প্রতিশ্রুতি ও পরিকল্পনা</p>
                <a href="/manifesto"
                   class="inline-block bg-primary-blue hover:bg-primary-green text-white font-bold py-3 px-8 rounded-lg text-lg transition-all duration-300 transform hover:scale-105">
                    সম্পূর্ণ ইশতেহার পড়ুন
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12">
                <?php
                $manifesto_items = ducsu_get_manifesto_items(3);

                if ($manifesto_items->have_posts()) :
                    while ($manifesto_items->have_posts()) : $manifesto_items->the_post();
                        ?>
                        <div class="manifesto-card bg-white rounded-lg shadow-lg p-6 transform hover:scale-105 transition-all duration-300">
                            <div class="bg-primary-red text-white rounded-full w-12 h-12 flex items-center justify-center mb-4">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-3"><?php the_title(); ?></h3>
                            <p class="text-gray-600 text-sm"><?php echo wp_trim_words(get_the_content(), 20, '...'); ?></p>
                        </div>
                    <?php
                    endwhile;
                    wp_reset_postdata();
                endif;
                ?>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">যোগাযোগ করুন</h2>
                <p class="text-lg md:text-xl text-gray-600 mb-8">আমাদের সাথে যোগাযোগ করুন এবং আপনার মতামত জানান</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 max-w-6xl mx-auto">
                <div class="contact-info">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6">আমাদের সাথে যোগাযোগ</h3>

                    <div class="contact-item flex items-start mb-6">
                        <div class="bg-primary-green text-white rounded-full w-10 h-10 flex items-center justify-center mr-4 mt-1">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800">ইমেইল</h4>
                            <p class="text-gray-600">info@jcdducsu.org</p>
                        </div>
                    </div>

                    <div class="contact-item flex items-start mb-6">
                        <div class="bg-primary-blue text-white rounded-full w-10 h-10 flex items-center justify-center mr-4 mt-1">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800">ফোন</h4>
                            <p class="text-gray-600">+৮৮০ ১৭১২ ৩৪৫৬৭৮</p>
                        </div>
                    </div>

                    <div class="contact-item flex items-start">
                        <div class="bg-primary-red text-white rounded-full w-10 h-10 flex items-center justify-center mr-4 mt-1">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800">ঠিকানা</h4>
                            <p class="text-gray-600">ঢাকা বিশ্ববিদ্যালয়<br>ঢাকা ১০০০, বাংলাদেশ</p>
                        </div>
                    </div>
                </div>

                <div class="contact-form bg-white rounded-lg shadow-lg p-8">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6">বার্তা পাঠান</h3>

                    <form id="general-contact-form" class="space-y-6">
                        <div>
                            <label for="contact-name" class="block text-sm font-medium text-gray-700 mb-2">নাম *</label>
                            <input type="text" id="contact-name" name="name" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent">
                        </div>

                        <div>
                            <label for="contact-email" class="block text-sm font-medium text-gray-700 mb-2">ইমেইল *</label>
                            <input type="email" id="contact-email" name="email" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent">
                        </div>

                        <div>
                            <label for="contact-phone" class="block text-sm font-medium text-gray-700 mb-2">ফোন</label>
                            <input type="tel" id="contact-phone" name="phone"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent">
                        </div>

                        <div>
                            <label for="contact-message" class="block text-sm font-medium text-gray-700 mb-2">বার্তা *</label>
                            <textarea id="contact-message" name="message" rows="5" required
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-green focus:border-transparent"></textarea>
                        </div>

                        <button type="submit"
                                class="w-full bg-primary-green hover:bg-primary-red text-white font-bold py-3 px-6 rounded-md transition-all duration-300 transform hover:scale-105">
                            বার্তা পাঠান
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Candidate Details Modal -->
    <div id="candidate-modal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="candidate-modal-content">
                <!-- Modal content will be loaded via AJAX -->
            </div>
            <button id="close-modal" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>

<?php get_footer(); ?>