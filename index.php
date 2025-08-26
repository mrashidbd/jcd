<?php get_header();?>

    <main id="primary" class="site-main">

        <?php
        if ( have_posts() ) {

            while ( have_posts() ) {
                the_post();

                // Display post content here
                echo '<div class="container mx-auto p-4">';
                the_title('<h1 class="text-3xl font-bold mb-4">', '</h1>');
                the_content();
                echo '</div>';
            }

        } else {
            // No posts found
            echo '<p class="text-center">No content found.</p>';
        }
        ?>

    </main>

<?php get_footer();?>