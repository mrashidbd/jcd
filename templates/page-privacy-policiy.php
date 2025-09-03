<?php
/**
 * Template Name: Privacy Policy Page
 * Page template for displaying hall panels and candidates
 */

get_header(); ?>

<?php
if ( have_posts() ):
    while ( have_posts() ):
        the_post();
        ?>
        <!-- Page Header -->
        <section class="page-header py-20 relative overflow-hidden bg-no-repeat bg-center bg-cover"
                 style="background-image: linear-gradient(135deg, rgba(0, 213, 190, 0.8), rgba(255, 240, 133, 0.9)), url('<?php echo home_url();?>/wp-content/themes/jcd-ducsu/assets/images/central-bg.jpg');">
            <div class="container mx-auto px-4 text-center relative z-10">
                <div class="max-w-4xl text-slate-700 mx-auto">
                    <?php the_title('<h1 class="text-4xl md:text-6xl font-bold mb-6">', '</h1>'); ?>
                </div>
            </div>
        </section>
        <!-- Main Page -->
        <section class="faq-content py-16 bg-slate-100">
            <div class="container mx-auto">
                <div class="max-w-5xl mx-auto bg-white px-8 py-4 rounded-lg shadow-sm text-xl">
                    <?php the_content(); ?>
                </div>
            </div>
        </section>

    <?php endwhile; ?>

    <!-- Call to Action -->
    <section class="cta-section py-16 bg-gradient-to-r from-green-100 to-blue-100">
        <div class="container mx-auto px-4 text-center text-white">
            <div class="max-w-3xl mx-auto text-slate-700">
                <h2 class="text-3xl md:text-4xl font-bold mb-6">আপনার মতামত আমাদের কাছে গুরুত্বপূর্ণ</h2>
                <p class="text-lg md:text-xl mb-8 opacity-90">
                    এই গোপনীয়তা নীতি সম্পর্কিত কোনো প্রশ্ন থাকলে অনুগ্রহ করে আমাদের সাথে যোগাযোগ করুন।
                </p>
                <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-6">
                    <a href="<?php echo home_url(); ?>/contact"
                       class="inline-block font-bold py-3 px-8 rounded-md text-slate-800 bg-gray-200 hover:bg-gray-400 transition-all duration-300 transform">
                        যোগাযোগ করুন
                    </a>
                </div>
            </div>
        </div>
    </section>

<?php else: ?>

<?php endif; ?>



<?php get_footer(); ?>