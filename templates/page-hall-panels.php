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
    <section class="page-header bg-gradient-to-r from-primary-green to-primary-blue py-20">
        <div class="container mx-auto px-4 text-center text-white">
            <div class="mb-4">
                <a href="<?php echo remove_query_arg('hall'); ?>"
                   class="inline-flex items-center text-white hover:text-gray-200 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    সব হল দেখুন
                </a>
            </div>
            <h1 class="text-4xl md:text-6xl font-bold mb-4"><?php echo esc_html($selected_hall->name); ?></h1>
            <p class="text-lg md:text-xl">
                <?php
                $candidate_count = $selected_hall->count;
                echo sprintf('%dটি প্রার্থী', $candidate_count);
                ?>
            </p>
        </div>
    </section>

    <!-- Hall Candidates -->
    <section class="candidates-section py-16 bg-gray-50">
        <div class="container mx-auto px-4">
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
                        <div class="candidate-card bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transform hover:scale-105 transition-all duration-300 cursor-pointer group"
                             data-candidate-id="<?php echo get_the_ID(); ?>">

                            <div class="relative overflow-hidden">
                                <?php if (has_post_thumbnail()) : ?>
                                    <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'medium'); ?>"
                                         alt="<?php echo esc_attr($name_bangla ?: get_the_title()); ?>"
                                         class="w-full h-56 object-cover group-hover:scale-110 transition-transform duration-300">
                                <?php else : ?>
                                    <div class="w-full h-56 bg-gradient-to-br from-primary-green to-primary-blue flex items-center justify-center">
                                        <svg class="w-20 h-20 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                <?php endif; ?>

                                <!-- Ballot Number Badge -->
                                <?php if ($ballot_number) : ?>
                                    <div class="absolute top-4 left-4 bg-primary-red text-white px-3 py-1 rounded-full text-sm font-bold">
                                        <?php echo esc_html($ballot_number); ?>
                                    </div>
                                <?php endif; ?>

                                <!-- Hover Overlay -->
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center">
                                    <button class="bg-white text-primary-green px-6 py-2 rounded-full font-medium opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-4 group-hover:translate-y-0">
                                        বিস্তারিত দেখুন
                                    </button>
                                </div>
                            </div>

                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-800 mb-2 line-clamp-2">
                                    <?php echo esc_html($name_bangla ?: get_the_title()); ?>
                                </h3>

                                <div class="space-y-2 mb-4">
                                    <p class="text-primary-green font-semibold text-lg"><?php echo esc_html($position); ?></p>
                                    <p class="text-gray-600 text-sm"><?php echo esc_html($department); ?></p>
                                    <?php if ($session) : ?>
                                        <p class="text-gray-500 text-sm">সেশন: <?php echo esc_html($session); ?></p>
                                    <?php endif; ?>
                                </div>

                                <div class="flex justify-between items-center">
                                    <button class="text-primary-green hover:text-primary-red font-medium text-sm transition-colors duration-300 flex items-center candidate-details-btn"
                                            data-candidate-id="<?php echo get_the_ID(); ?>">
                                        <span>আরও জানুন</span>
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </button>

                                    <button class="bg-primary-green hover:bg-primary-red text-white px-4 py-2 rounded-full text-sm font-medium transition-all duration-300 contact-candidate-btn"
                                            data-candidate-id="<?php echo get_the_ID(); ?>">
                                        যোগাযোগ
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php
                    endwhile;
                else :
                    ?>
                    <div class="col-span-full text-center py-12">
                        <div class="bg-gray-100 rounded-lg p-8 max-w-md mx-auto">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-800 mb-2">কোনো প্রার্থী পাওয়া যায়নি</h3>
                            <p class="text-gray-600">এই হলে এখনো কোনো প্রার্থী যোগ করা হয়নি।</p>
                        </div>
                    </div>
                <?php
                endif;
                wp_reset_postdata();
                ?>
            </div>
        </div>
    </section>

<?php else : ?>
    <!-- All Halls View -->
    <section class="page-header bg-gradient-to-r from-primary-green to-primary-blue py-20">
        <div class="container mx-auto px-4 text-center text-white">
            <h1 class="text-4xl md:text-6xl font-bold mb-4">হল প্যানেল</h1>
            <p class="text-lg md:text-xl max-w-2xl mx-auto">বাংলাদেশ জাতীয়তাবাদী ছাত্রদল ডাকসু নির্বাচন ২০২৫ হল প্যানেলসমূহ</p>
        </div>
    </section>

    <!-- Halls Grid -->
    <section class="halls-section py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="mb-12 text-center">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">সকল হল</h2>
                <p class="text-lg text-gray-600">আপনার হল নির্বাচন করে প্রার্থীদের দেখুন</p>
            </div>

            <div class="halls-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                <?php
                $all_halls = get_terms([
                    'taxonomy' => 'halls',
                    'hide_empty' => false,
                    'orderby' => 'name',
                    'order' => 'ASC'
                ]);

                if ($all_halls && !is_wp_error($all_halls)) :
                    foreach ($all_halls as $hall) :
                        $candidate_count = $hall->count;
                        $hall_candidates_query = ducsu_get_hall_candidates($hall->term_id, 3);
                        $sample_candidates = [];

                        if ($hall_candidates_query->have_posts()) {
                            while ($hall_candidates_query->have_posts()) {
                                $hall_candidates_query->the_post();
                                $sample_candidates[] = [
                                    'name' => get_post_meta(get_the_ID(), '_candidate_name_bangla', true) ?: get_the_title(),
                                    'position' => get_post_meta(get_the_ID(), '_candidate_position', true),
                                    'image' => get_the_post_thumbnail_url(get_the_ID(), 'thumbnail')
                                ];
                            }
                            wp_reset_postdata();
                        }
                        ?>
                        <div class="hall-card bg-white rounded-xl shadow-lg hover:shadow-2xl transform hover:scale-105 transition-all duration-300 overflow-hidden">
                            <div class="bg-gradient-to-br from-primary-green to-primary-blue text-white p-6 text-center">
                                <div class="bg-white bg-opacity-20 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.84L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.84l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold mb-2"><?php echo esc_html($hall->name); ?></h3>
                                <p class="text-sm opacity-90">
                                    <?php echo sprintf('%dটি প্রার্থী', $candidate_count); ?>
                                </p>
                            </div>

                            <!-- Sample Candidates Preview -->
                            <?php if (!empty($sample_candidates)) : ?>
                                <div class="p-4">
                                    <h4 class="text-sm font-semibold text-gray-700 mb-3">কিছু প্রার্থী:</h4>
                                    <div class="space-y-2">
                                        <?php foreach (array_slice($sample_candidates, 0, 2) as $candidate) : ?>
                                            <div class="flex items-center space-x-3">
                                                <?php if ($candidate['image']) : ?>
                                                    <img src="<?php echo esc_url($candidate['image']); ?>"
                                                         alt="<?php echo esc_attr($candidate['name']); ?>"
                                                         class="w-8 h-8 rounded-full object-cover">
                                                <?php else : ?>
                                                    <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                                        <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                                        </svg>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-800 truncate"><?php echo esc_html($candidate['name']); ?></p>
                                                    <p class="text-xs text-gray-500 truncate"><?php echo esc_html($candidate['position']); ?></p>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php else : ?>
                                <div class="p-4">
                                    <p class="text-sm text-gray-500 text-center">এখনো কোনো প্রার্থী যোগ করা হয়নি</p>
                                </div>
                            <?php endif; ?>

                            <div class="p-4 pt-0">
                                <a href="?hall=<?php echo $hall->term_id; ?>"
                                   class="block w-full bg-primary-green hover:bg-primary-red text-white text-center font-medium py-3 px-4 rounded-lg transition-all duration-300 transform hover:scale-105">
                                    সব প্রার্থী দেখুন
                                </a>
                            </div>
                        </div>
                    <?php
                    endforeach;
                else :
                    ?>
                    <div class="col-span-full text-center py-12">
                        <div class="bg-gray-100 rounded-lg p-8 max-w-md mx-auto">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
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
    <div id="candidate-modal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-6xl w-full max-h-[90vh] overflow-y-auto relative">
            <button id="close-modal" class="absolute top-4 right-4 z-10 text-gray-500 hover:text-gray-700 bg-white rounded-full p-2 shadow-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
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
            <svg class="animate-spin h-8 w-8 text-primary-green" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-gray-700">লোড হচ্ছে...</span>
        </div>
    </div>

<?php get_footer(); ?>