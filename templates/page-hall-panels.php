<?php
/**
 * Template Name: Hall Panels
 * Page template for displaying hall panels and candidates
 */

get_header();

// Check if a specific hall is requested
$selected_hall_id = isset($_GET['hall']) ? intval($_GET['hall']) : 0;
$selected_hall = $selected_hall_id ? get_term($selected_hall_id, 'halls') : null;
?>

<?php if ($selected_hall && !is_wp_error($selected_hall)) : ?>
    <!-- Single Hall View -->
    <section class="page-header bg-cover bg-center bg-no-repeat py-20"
             style="background-image: linear-gradient(135deg, rgba(237, 159, 249, 0.85), rgba(52, 148, 215, 0.8)), url('/ducsu/wp-content/themes/jcd-ducsu/assets/images/central-bg.jpg');">
        <div class="container mx-auto px-4 text-center text-slate-700">
            <h1 class="text-4xl md:text-6xl font-bold mb-4"><?php echo esc_html($selected_hall->name); ?></h1>
            <p class="text-lg md:text-xl">
                <?php
                $candidate_count = convertEngToBn($selected_hall->count);
                echo sprintf('%s জন প্রার্থী', $candidate_count);
                ?>
            </p>
        </div>
    </section>

    <!-- Hall Candidates -->
    <section class="candidates-section py-8 bg-slate-100">
        <div class="container mx-auto px-4">

            <!-- back button -->

            <div class="mb-8">
                <a href="<?php echo remove_query_arg('hall'); ?>"
                   class="inline-flex items-center min-w-16 px-8 py-2 rounded text-yellow-50 bg-lime-600 hover:bg-lime-800 transition-all group">
                    <svg class="rotate-180 w-6 h-6 mr-2 group-hover:-translate-x-2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="currentColor">
                        <g>
                            <path d="M10.22,9.28a.75.75,0,0,1,0-1.06l2.72-2.72H.75A.75.75,0,0,1,.75,4H12.938L10.22,1.281A.75.75,0,1,1,11.281.22l4,4a.749.749,0,0,1,0,1.06l-4,4a.75.75,0,0,1-1.061,0Z" transform="translate(4.25 7.25)"/>
                        </g>
                    </svg>
                    <span class="text-xl text-lime-50">সকল হল</span>
                </a>
            </div>

            <div class="candidates-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                <?php
                $hall_candidates = ducsu_get_hall_candidates($selected_hall_id);

                if ($hall_candidates->have_posts()) :
                    while ($hall_candidates->have_posts()) : $hall_candidates->the_post();
                        $name_bangla = get_post_meta(get_the_ID(), '_candidate_name_bangla', true);
                        $position = get_post_meta(get_the_ID(), '_candidate_position', true);
                        $ballot_number = get_post_meta(get_the_ID(), '_candidate_ballot_number', true);
                        $department = get_post_meta(get_the_ID(), '_candidate_department', true);
                        $session = get_post_meta(get_the_ID(), '_candidate_session', true);
                        ?>
                        <div class="hall-candidate-card bg-white rounded-xl overflow-hidden shadow-xs hover:shadow-sm transform hover:scale-105 transition-all duration-300 group"
                             data-candidate-id="<?php echo get_the_ID(); ?>">

                            <div class="relative overflow-hidden">
                                <?php if (has_post_thumbnail()) : ?>
                                    <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'medium'); ?>"
                                         alt="<?php echo esc_attr($name_bangla ?: get_the_title()); ?>"
                                         class="w-full h-56 object-cover group-hover:scale-110 transition-transform duration-300">
                                <?php else : ?>
                                    <img src="<?php echo home_url() . '/wp-content/uploads/2025/09/All_Gents.png'; ?>"
                                         alt="<?php echo esc_attr($name_bangla ?: get_the_title()); ?>"
                                         class="w-full h-56 object-cover group-hover:scale-110 transition-transform duration-300">
                                <?php endif; ?>
                            </div>

                            <div class="bg-white p-4">
                                <div>
                                    <p class="text-center bg-sky-100 text-sky-950 py-2 !mb-4">
                                        <?php echo esc_html($position); ?>
                                    </p>
                                </div>
                                <div class="mb-4 text-center">
                                    <h3 class="text-xl font-bold text-sky-950 line-clamp-2">
                                        <?php echo esc_html($name_bangla ?: get_the_title()); ?>
                                    </h3>
                                    <p class="!mb-1 text-sky-950"><?php echo esc_html('('.$session.')'); ?></p>
                                </div>
                                <!-- Ballot Number Badge -->
                                <?php if ($ballot_number) : ?>
                                    <div class="flex flex-col mx-auto max-w-32 text-center font-semibold">
                                        <p class="!mb-0 px-4 py-1 text-lg bg-purple-800 rounded-t-lg text-white">ব্যালট নম্বর</p>
                                        <p class="!mb-0 px-4 py-2 text-4xl bg-purple-100 rounded-b-lg text-purple-900"><?php echo esc_html($ballot_number); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php
                    endwhile;
                else :
                    ?>
                    <div class="col-span-full text-center py-12">
                        <div class="bg-slate-100 rounded-lg p-8 max-w-md mx-auto">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-800 mb-2">কোনো প্রার্থী পাওয়া যায়নি</h3>
                            <p class="text-gray-600">এই হলে এখনো কোনো প্রার্থী যোগ করা হয়নি।</p>
                        </div>
                    </div>
                <?php
                endif; ?>
            </div>
                <?php
                //show only if it has more than 8 posts
                if ($hall_candidates->have_posts() && $hall_candidates->post_count > 8) :
                ?>
                <div class="mt-8">
                    <a href="<?php echo remove_query_arg('hall'); ?>"
                       class="inline-flex items-center min-w-16 px-8 py-2 rounded text-yellow-50 bg-teal-700 hover:bg-teal-800 transition-all group">
                        <svg class="rotate-180 w-6 h-6 mr-2 group-hover:-translate-x-2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="currentColor">
                            <g>
                                <path d="M10.22,9.28a.75.75,0,0,1,0-1.06l2.72-2.72H.75A.75.75,0,0,1,.75,4H12.938L10.22,1.281A.75.75,0,1,1,11.281.22l4,4a.749.749,0,0,1,0,1.06l-4,4a.75.75,0,0,1-1.061,0Z" transform="translate(4.25 7.25)"/>
                            </g>
                        </svg>
                        <span class="text-xl">সকল হল</span>
                    </a>
                </div>
                <?php
                endif;
                wp_reset_postdata(); ?>
    </section>

<?php else : ?>
    <!-- All Halls View -->
    <section class="page-header bg-cover bg-center bg-no-repeat py-20"
             style="background-image: linear-gradient(135deg, rgba(0, 213, 190, 0.8), rgba(255, 240, 133, 0.9)), url('/ducsu/wp-content/themes/jcd-ducsu/assets/images/central-bg.jpg');">
        <div class="container mx-auto px-4 text-center text-slate-800">
            <h1 class="text-4xl md:text-6xl font-bold mb-4">হল সংসদ</h1>
            <p class="text-lg md:text-xl max-w-2xl mx-auto">ঢাকা বিশ্ববিদ্যালয় কেন্দ্রীয় ছাত্র সংসদ (ডাকসু) ও হল সংসদ নির্বাচন ২০২৫ - এর হল সংসদে বাংলাদেশ জাতীয়তাবাদী ছাত্রদল সমর্থিত পদপ্রার্থীদের বিস্তারিত।</p>
        </div>
    </section>

    <!-- Halls Grid -->
    <section class="halls-section py-16 bg-slate-100">
        <div class="container mx-auto px-4">
            <div class="halls-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php
                $all_halls = get_terms([
                    'taxonomy' => 'halls',
                    'hide_empty' => false,
                    'orderby' => 'id',
                    'order' => 'ASC'
                ]);

                if ($all_halls && !is_wp_error($all_halls)) :
                    foreach ($all_halls as $hall) :
                        $candidate_count = convertEngToBn($hall->count);

                        // Get the image URL from the term meta
                        $hall_image_id = get_term_meta($hall->term_id, 'hall_image', true);
                        $hall_image_url = wp_get_attachment_url($hall_image_id);
                        ?>

                        <a href="?hall=<?php echo $hall->term_id; ?>"
                           class="block w-full transition-all duration-500 ease-out transform group relative z-10">
                            <div class="hall-card rounded-xl flex flex-col shadow hover:drop-shadow-xs min-h-[320px] transform group-hover:scale-105 transition-all duration-500 ease-out overflow-hidden relative">
                                <div class="absolute inset-0 grayscale group-hover:grayscale-0 transition-all duration-700 ease-in-out"
                                     style="background-image: url('<?php echo esc_url($hall_image_url); ?>'); background-size: cover; background-position: center;"></div>
                                <!-- Glossy sweep effect -->
                                <div class="absolute inset-0 -translate-x-full group-hover:translate-x-full transition-transform duration-700 ease-out bg-gradient-to-r from-transparent via-white/20 to-transparent opacity-0 group-hover:opacity-100"></div>
                                <div class="grow relative z-10">
                                    <p class="absolute right-2 top-2 text-sm text-green-800 font-bold px-3 py-2 rounded-md opacity-90 bg-white">
                                        <?php echo sprintf('%s জন প্রার্থী', $candidate_count); ?>
                                    </p>
                                </div>
                                <div class="relative flex-none z-10">
                                    <h3 class="text-xl bg-green-800 block font-bold m-0 py-4 text-center transition-all duration-300 text-green-100 group-hover:bg-green-700"><?php echo esc_html($hall->name); ?></h3>
                                </div>
                            </div>
                        </a>
                    <?php
                    endforeach;
                else :
                    ?>
                    <div class="col-span-full text-center py-12">
                        <div class="bg-slate-100 rounded-lg p-8 max-w-md mx-auto">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-800 mb-2">কোনো হল পাওয়া যায়নি</h3>
                            <p class="text-gray-600">এই মুহূর্তে কোনো হল যোগ করা হয়নি।</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

    <!-- Candidate Details Modal -->
    <div id="candidate-modal"
         class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-6xl w-full max-h-[90vh] overflow-y-auto relative">
            <button id="close-modal"
                    class="absolute top-4 right-4 z-10 text-gray-500 cursor-pointer hover:text-gray-700 bg-white rounded-full p-2 shadow-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <div class="candidate-modal-content p-8">
                <!-- Modal content will be loaded via AJAX -->
            </div>
        </div>
    </div>

    <!-- Loading Spinner -->
    <div id="loading-spinner" class="fixed inset-0 z-40 hidden bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg p-8 flex items-center space-x-4">
            <svg class="animate-spin h-8 w-8 text-primary-green" xmlns="http://www.w3.org/2000/svg" fill="none"
                 viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                      d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-gray-700">লোড হচ্ছে...</span>
        </div>
    </div>

<?php get_footer(); ?>