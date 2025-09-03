<?php
/**
 * Template Name: Front Page
 * The main landing page template
 */

get_header(); ?>

    <!-- Hero Slider Section -->
    <section class="hero-slider relative overflow-hidden" style="min-height: calc(100% / (1920 / 700));">
        <div class="slider-container relative w-full" style="padding-top: calc(100% / (1920 / 700));">
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
                    <div class="slider-item absolute inset-0 bg-cover bg-bottom bg-no-repeat <?php echo $slider_count === 1 ? 'active opacity-100' : 'opacity-0'; ?> transition-opacity duration-1000"
                         style="background-image:url('<?php echo get_the_post_thumbnail_url(get_the_ID(), 'full'); ?>');">
                    </div>
                <?php
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>

        <!-- Slider Navigation -->
        <?php if ($slider_count > 1) : ?>
            <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 space-x-3 hidden">
                <?php for ($i = 1; $i <= $slider_count; $i++) : ?>
                    <button class="slider-dot w-3 h-3 rounded-full bg-green-900/10 over:bg-green-900/100 transition-all duration-300"
                            data-slide="<?php echo $i; ?>"></button>
                <?php endfor; ?>
            </div>

            <button class="slider-prev absolute p-2 md:p-4 xl:p-6 cursor-pointer bg-indigo-900/20 left-4 top-1/2 transform -translate-y-1/2 text-white hover:text-purple-500 transition-colors duration-300">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>

            <button class="slider-next absolute p-2 md:p-4 xl:p-6 cursor-pointer bg-indigo-900/20 right-4 top-1/2 transform -translate-y-1/2 text-white hover:text-purple-500 transition-colors duration-300">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        <?php endif; ?>
    </section>


    <!-- Special CTA Section -->
    <section class="my-4 md:my-8">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-2xl md:text-4xl font-bold mb-4 text-green-900">"প্রতিশ্রুতি নয়, পরিবর্তনে প্রতিজ্ঞাবদ্ধ"</h2>
                <p class="text-lg md:text-xl mb-8 max-w-3xl mx-auto">
                    ঢাকা বিশ্ববিদ্যালয় কেন্দ্রীয় ছাত্র সংসদ (ডাকসু) ও হল সংসদ নির্বাচন ২০২৫ - এ বাংলাদেশ জাতীয়তাবাদী ছাত্রদল সমর্থিত প্যানেলকে নির্বাচিত করুন।
                </p>
                <svg class="w-8 h-8 rotate-90 mx-auto text-green-800/35" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </div>
        </div>
    </section>

    <!-- Central Panel Section -->
    <section
            class="hall-panel-section md:min-h-[600px] py-20 bg-cover bg-center relative flex items-center justify-center bg-transparent">
        <div class="container mx-auto px-4">
            <div class="flex flex-row">
                <div class="h-full w-full hidden md:block"></div>
                <div class="text-center mb-12 h-full w-full z-20">
                    <h2 class="text-4xl md:text-5xl font-bold mb-4 text-white/90">শিক্ষার্থীবান্ধব ক্যাম্পাস</h2>
                    <p class="text-lg md:text-xl px-8 mb-8 text-white/80">ঢাকা বিশ্ববিদ্যালয় কেবল একটি শিক্ষা প্রতিষ্ঠান নয়। এটি বাংলাদেশের ইতিহাস, সংস্কৃতি, সংগ্রাম ও অর্জনের অবিচ্ছেদ্য অংশ। সেই গৌরবময় ধারাবাহিকতায় আমাদের প্রতিশ্রুতি একটি আধুনিক, নিরাপদ, গণতান্ত্রিক ও শিক্ষার্থীবান্ধব ক্যাম্পাস বিনির্মাণ।</p>
                    <a href="<?php echo home_url(); ?>/central-panel"
                       class="inline-block bg-purple-700/80 hover:bg-purple-800 text-gray-50/90 font-bold py-3 px-8 rounded-lg text-lg transition-all duration-300 transform hover:scale-105">
                        কেন্দ্রীয় সংসদ
                    </a>
                </div>
            </div>
        </div>

        <div class="absolute h-full w-full flex flex-row bg-gradient-to-r from-indigo-500/85 md:from-indigo-500/5 from-10% via-indigo-500/80 via-40% to-teal-500 to-90%">
            <div class="-z-10 h-full w-full bg-cover bg-center"
                 style="background-image:url('<?php echo home_url(); ?>/wp-content/themes/jcd-ducsu/assets/images/tsc.png');"></div>
            <div class="h-full w-full hidden md:block">
            </div>
        </div>
    </section>

    <!-- Hall Panel Section -->
    <section
            class="hall-panel-section md:min-h-[600px] py-20 bg-cover bg-center relative flex items-center justify-center bg-transparent">
        <div class="container mx-auto px-4">
            <div class="flex flex-row">
                <div class="text-center mb-12 h-full w-full z-20">
                    <h2 class="text-4xl md:text-5xl font-bold mb-4 text-white/90">আমাদের প্রতিজ্ঞা</h2>
                    <p class="text-lg md:text-xl px-8 mb-8 text-white/80">প্রতিশ্রুতি নয়, বাংলাদেশ জাতীয়তাবাদী ছাত্রদল পরিবর্তনে প্রতিজ্ঞাবদ্ধ। আমরা বিশ্বাস করি, আপনাদের সহযোগিতা, অংশগ্রহণ ও সমর্থনই ঢাকা বিশ্ববিদ্যালয়কে দেশের গণতান্ত্রিক মূল্যবোধ ও প্রগতির এক অটুট সূতিকাগার হিসেবে প্রতিষ্ঠিত করবে।</p>
                    <a href="<?php echo home_url(); ?>/hall-panels"
                       class="inline-block bg-teal-700/80 hover:bg-teal-800 text-gray-50/90 font-bold py-3 px-8 rounded-lg text-lg transition-all duration-300 transform hover:scale-105">
                        হল সংসদ
                    </a>
                </div>
                <div class="h-full w-full hidden md:block"></div>
            </div>
        </div>

        <div class="absolute h-full w-full flex flex-row bg-gradient-to-l from-indigo-500/5 from-10% via-indigo-400/80 via-40% to-violet-500 to-90%">
            <div class="h-full w-full hidden md:block">
            </div>
            <div class="-z-10 h-full w-full bg-cover bg-center"
                 style="background-image:url('<?php echo home_url(); ?>/wp-content/themes/jcd-ducsu/assets/images/oporajeo_bangla.png');"></div>
        </div>
    </section>

    <!-- Manifesto Section -->
    <section class="manifesto-section md:min-h-[400px] py-20 bg-cover bg-center relative"
             style="background-image: linear-gradient(to bottom right, rgba(0, 95, 89, 0.9) 20%, rgba(69, 85, 108, 0.95) 90%), url('<?php echo home_url(); ?>/wp-content/themes/jcd-ducsu/assets/images/razu.png');">
        <div class="container mx-auto px-4">
            <div class="text-center text-white/85 mb-12">
                <h2 class="text-4xl md:text-5xl font-bold mb-4">আমাদের ইশতেহার</h2>
                <p class="text-lg md:text-xl mb-8 px-2 md:max-w-6xl mx-auto">
                    ফ্যাসিবাদবিরোধী আন্দোলনে নজিরবিহীন দমন-নিপীড়নের শিকার হওয়া এবং সর্বদা অগ্রণী নেতৃত্ব প্রদানকারী সংগঠন হিসেবে, আমরা ১০টি মূল অঙ্গীকার সম্বলিত নির্বাচনী ইশতেহার ঘোষণা করছি, যা ঢাকা বিশ্ববিদ্যালয়ের শিক্ষা ব্যবস্থাকে আরও যুগোপযোগী ও আধুনিক করে তুলবে এবং গড়ে তুলবে একটি নিরাপদ, শিক্ষার্থীবান্ধব ক্যাম্পাস। আমরা বিশ্বাস করি, এই ১০টি প্রধান পরিকল্পনায় শিক্ষার্থীদের আশা-আকাঙ্ক্ষার প্রতিফলন ঘটবে এবং আমরা সবাই মিলে তা বাস্তবায়নে কাজ করব, ইনশাআল্লাহ।
                </p>
                <a href="<?php echo home_url(); ?>/manifesto"
                   class="inline-block bg-yellow-600/80 hover:bg-yellow-800 text-gray-50/90 font-bold py-3 px-8 rounded-lg text-lg transition-all duration-300 transform hover:scale-105">
                    সম্পূর্ণ ইশতেহার পড়ুন
                </a>
            </div>
        </div>
    </section>

<?php get_footer(); ?>