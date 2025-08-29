<?php
/**
 * Template Name: Central Panel
 * Page template for displaying central panel candidates
 */

get_header(); ?>

    <!-- Page Header -->
    <section class="page-header bg-cover bg-center bg-no-repeat py-20"
             style="background-image: linear-gradient(135deg, rgba(0, 213, 190, 0.8), rgba(255, 240, 133, 0.9)), url('/ducsu/wp-content/themes/jcd-ducsu/assets/images/central-bg.jpg');">>
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-4 text-slate-700">কেন্দ্রীয় সংসদ</h1>
            <p class="text-lg md:text-xl max-w-2xl mx-auto">ঢাকা বিশ্ববিদ্যালয় কেন্দ্রীয় ছাত্র সংসদ (ডাকসু) ও হল সংসদ নির্বাচন ২০২৫ - এর কেন্দ্রীয় সংসদে বাংলাদেশ জাতীয়তাবাদী ছাত্রদল সমর্থিত ২৭ জন পদপ্রার্থীর বিস্তারিত</p>
        </div>
    </section>

    <!-- Candidates Grid -->
    <section class="candidates-section py-16 bg-gray-50">
        <div class="container mx-auto px-4">

            <!-- Total Candidates Count -->
            <div class="text-center mb-12">
                <h2 class="text-2xl font-bold text-gray-800">মোট <?php
                    $total_candidates = wp_count_posts('central_candidate');
                    echo $total_candidates->publish;
                    ?>টি প্রার্থী</h2>
                <p class="text-gray-600 mt-2">প্রার্থীর বিস্তারিত দেখতে কার্ডে ক্লিক করুন</p>
            </div>

            <!-- Candidates Grid -->
            <div id="candidates-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                <?php
                $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

                $args = [
                    'post_type' => 'central_candidate',
                    'posts_per_page' => 12,
                    'paged' => $paged,
                    'post_status' => 'publish',
                    'orderby' => 'menu_order',
                    'order' => 'ASC'
                ];

                $candidates = new WP_Query($args);

                if ($candidates->have_posts()) :
                    while ($candidates->have_posts()) : $candidates->the_post();
                        $name_bangla = get_post_meta(get_the_ID(), '_candidate_name_bangla', true);
                        $position = get_post_meta(get_the_ID(), '_candidate_position', true);
                        $ballot_number = get_post_meta(get_the_ID(), '_candidate_ballot_number', true);
                        $department = get_post_meta(get_the_ID(), '_candidate_department', true);
                        $hall = get_post_meta(get_the_ID(), '_candidate_hall', true);
                        $session = get_post_meta(get_the_ID(), '_candidate_session', true);
                        ?>
                        <div class="candidate-card shadow-xs hover:shadow-sm rounded-lg overflow-hidden transform hover:scale-105 transition-all duration-300 cursor-pointer group"
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
                            </div>

                            <div class="p-6 bg-white">
                                <div class="space-y-2 mb-4">
                                    <p class="text-primary-blue font-semibold text-lg"><?php echo esc_html($position); ?></p>
                                </div>
                                <div class="relative">
                                    <h3 class="text-xl font-bold text-gray-800 mb-2 line-clamp-2">
                                        <?php echo esc_html($name_bangla ?: get_the_title()); ?>
                                    </h3>
                                </div>
                                <!-- Ballot Number Badge -->
                                <?php if ($ballot_number) : ?>
                                    <div class="bg-primary-red text-white px-3 py-1 rounded-full text-sm font-bold">
                                        <span>ব্যালট নম্বর: <?php echo esc_html($ballot_number); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php
                    endwhile;
                else :
                    ?>
                    <div class="col-span-full text-center py-12">
                        <div class="bg-gray-100 p-8 max-w-md mx-auto">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-800 mb-2">কোনো প্রার্থী পাওয়া যায়নি</h3>
                            <p class="text-gray-600">এই মুহূর্তে কোনো কেন্দ্রীয় প্যানেলের প্রার্থী যোগ করা হয়নি।</p>
                        </div>
                    </div>
                <?php
                endif;
                ?>
            </div>

            <!-- Pagination -->
            <?php if ($candidates->max_num_pages > 1) : ?>
                <div class="pagination-wrapper mt-12 flex justify-center">
                    <div class="flex space-x-2">
                        <?php
                        $pagination = paginate_links([
                            'total' => $candidates->max_num_pages,
                            'current' => $paged,
                            'format' => '?paged=%#%',
                            'show_all' => false,
                            'end_size' => 1,
                            'mid_size' => 2,
                            'prev_next' => true,
                            'prev_text' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>',
                            'next_text' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>',
                            'type' => 'array'
                        ]);

                        if ($pagination) {
                            foreach ($pagination as $page) {
                                $page = str_replace('page-numbers', 'page-numbers px-4 py-2 border border-gray-300 text-gray-700 hover:bg-primary-green hover:text-white hover:border-primary-green transition-all duration-300', $page);
                                $page = str_replace('current', 'current bg-primary-green text-white border-primary-green', $page);
                                echo $page;
                            }
                        }
                        ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php wp_reset_postdata(); ?>
        </div>
    </section>

    <!-- Candidate Details Modal -->
    <div id="candidate-modal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-6xl w-full max-h-[90vh] overflow-y-auto relative">
            <button id="close-modal" class="absolute top-4 right-4 z-10 cursor-pointer text-gray-500 bg-white border-2 border-gray-500 rounded-full p-2 hover:text-red-500 hover:border-red-500">
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