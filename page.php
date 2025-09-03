<?php

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
<?php endif; ?>



<?php get_footer(); ?>