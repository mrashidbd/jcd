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
            <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 flex space-x-3">
                <?php for ($i = 1; $i <= $slider_count; $i++) : ?>
                    <button class="slider-dot w-3 h-3 rounded-full bg-green-900/10 over:bg-green-900/100 transition-all duration-300"
                            data-slide="<?php echo $i; ?>"></button>
                <?php endfor; ?>
            </div>

            <button class="slider-prev absolute p-2 md:p-4 xl:p-6 cursor-pointer bg-indigo-900/10 left-4 top-1/2 transform -translate-y-1/2 text-white hover:text-primary-green transition-colors duration-300">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>

            <button class="slider-next absolute p-2 md:p-4 xl:p-6 cursor-pointer bg-indigo-900/10 right-4 top-1/2 transform -translate-y-1/2 text-white hover:text-primary-green transition-colors duration-300">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        <?php endif; ?>
    </section>

<section class="my-48"></section>

    <!-- Central Panel Section -->
    <section
            class="central-panel-section min-h-[700px] py-20 bg-cover bg-center relative flex items-center justify-center"
            style="background-image: linear-gradient(135deg, rgba(30, 183, 224, 0.4), rgba(166, 169, 135, 0.3)), url('/ducsu/wp-content/themes/jcd-ducsu/assets/images/central-bg.jpg');">
        <div class="container mx-auto px-4">
            <div class="text-center text-white mb-12">
                <h2 class="text-4xl md:text-5xl font-bold mb-4">কেন্দ্রীয় প্যানেল</h2>
                <p class="text-lg md:text-xl mb-8">আমাদের দক্ষ এবং অভিজ্ঞ কেন্দ্রীয় প্যানেলের প্রার্থীদের সাথে পরিচিত
                    হন</p>
                <a href="/central-panel"
                   class="inline-block bg-primary-red hover:bg-primary-green text-white font-bold py-3 px-8 rounded-lg text-lg transition-all duration-300 transform hover:scale-105">
                    সব প্রার্থী দেখুন
                </a>
            </div>
        </div>
    </section>

    <!-- Hall Panel Section -->
    <section
            class="hall-panel-section min-h-[700px] py-20 bg-cover bg-center relative flex items-center justify-center bg-transparent">
        <div class="container mx-auto px-4">
            <div class="flex flex-row">
                <div class="h-full w-full"></div>
                <div class="text-center text-white mb-12 h-full w-full">
                    <h2 class="text-4xl md:text-5xl font-bold mb-4">হল প্যানেল</h2>
                    <p class="text-lg md:text-xl mb-8">আপনার হলের প্রার্থীদের সাথে পরিচিত হন এবং তাদের ভবিষ্যৎ পরিকল্পনা
                        জানুন</p>
                    <a href="/hall-panels"
                       class="inline-block bg-primary-red hover:bg-primary-blue text-white font-bold py-3 px-8 rounded-lg text-lg transition-all duration-300 transform hover:scale-105">
                        সব হল দেখুন
                    </a>
                </div>
            </div>
        </div>

        <div class="absolute -z-10 h-full w-full flex flex-row">
            <div class="z-10 h-full w-full bg-cover"
                 style="background-image:url('/ducsu/wp-content/themes/jcd-ducsu/assets/images/central-bg.jpg');"></div>
            <div class="h-full w-full" style="background-image: linear-gradient(135deg, rgba(30, 183, 224, 0.4), rgba(166, 169, 135, 0.3))">

            </div>
        </div>
    </section>

    <!-- Manifesto Section -->
    <section class="manifesto-section py-20 bg-cover bg-center relative"
             style="background-image: linear-gradient(rgba(232, 37, 44, 0.8), rgba(232, 37, 44, 0.8)), url('/ducsu/wp-content/themes/jcd-ducsu/assets/images/manifesto-bg.jpg');">
        <div class="container mx-auto px-4">
            <div class="text-center text-white mb-12">
                <h2 class="text-4xl md:text-5xl font-bold mb-4">আমাদের ইশতেহার</h2>
                <p class="text-lg md:text-xl mb-8">শিক্ষার্থীদের উন্নয়ন এবং কল্যাণের জন্য আমাদের প্রতিশ্রুতি ও
                    পরিকল্পনা</p>
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
                                    <path fill-rule="evenodd"
                                          d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                          clip-rule="evenodd"></path>
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

<?php get_footer(); ?>