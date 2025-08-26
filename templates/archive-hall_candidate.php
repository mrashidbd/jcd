<?php
/**
 * Archive template for hall_candidate post type
 * Displays candidates filtered by hall taxonomy
 */

get_header();

// Get current hall if filtering by taxonomy
$current_hall = null;
if (is_tax('halls')) {
    $current_hall = get_queried_object();
}
?>

    <!-- Page Header -->
    <section class="page-header bg-gradient-to-r from-primary-green to-primary-blue py-20">
        <div class="container mx-auto px-4 text-center text-white">
            <?php if ($current_hall) : ?>
                <nav class="flex justify-center mb-6">
                    <div class="flex items-center text-sm">
                        <a href="/" class="hover:text-gray-200 transition-colors">হোম</a>
                        <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        <a href="/hall-panels" class="hover:text-gray-200 transition-colors">হল প্যানেল</a>
                        <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        <span class="text-gray-200"><?php echo esc_html($current_hall->name); ?></span>
                    </div>
                </nav>
                <h1 class="text-4xl md:text-6xl font-bold mb-4"><?php echo esc_html($current_hall->name); ?></h1>
                <p class="text-lg md:text-xl max-w-2xl mx-auto">হল প্যানেলের প্রার্থীবৃন্দ</p>
            <?php else : ?>
                <h1 class="text-4xl md:text-6xl font-bold mb-4">হল প্যানেল প্রার্থী</h1>
                <p class="text-lg md:text-xl max-w-2xl mx-auto">সকল হল প্যানেলের প্রার্থীদের তালিকা</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Candidates Archive -->
    <section class="candidates-archive py-16 bg-gray-50">
        <div class="container mx-auto px-4">

            <!-- Archive Info -->
            <div class="max-w-4xl mx-auto text-center mb-12">
                <?php if ($current_hall && $current_hall->description) : ?>
                    <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
                        <h2 class="text-2xl font-bold text-gray-800 mb-4">হল সম্পর্কে</h2>
                        <p class="text-gray-600 leading-relaxed"><?php echo esc_html($current_hall->description); ?></p>
                    </div>
                <?php endif; ?>

                <div class="flex flex-col sm:flex-row justify-between items-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4 sm:mb-0">
                        মোট <?php echo $wp_query->found_posts; ?>টি প্রার্থী
                        <?php if ($current_hall) : ?>
                            - <?php echo esc_html($current_hall->name); ?>
                        <?php endif; ?>
                    </h2>

                    <!-- Hall Filter -->
                    <?php if (!$current_hall) : ?>
                        <div class="filter-dropdown">
                            <select id="hall-filter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-green focus:border-transparent">
                                <option value="">সব হল</option>
                                <?php
                                $halls = get_terms([
                                    'taxonomy' => 'halls',
                                    'hide_empty' => true,
                                    'orderby' => 'name',
                                    'order' => 'ASC'
                                ]);

                                if ($halls && !is_wp_error($halls)) {
                                    foreach ($halls as $hall) {
                                        echo '<option value="' . esc_attr($hall->slug) . '">' . esc_html($hall->name) . ' (' . $hall->count . ')</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Candidates Grid -->
            <div class="candidates-grid">
                <?php if (have_posts()) : ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                        <?php while (have_posts()) : the_post();
                            $name_bangla = get_post_meta(get_the_ID(), '_candidate_name_bangla', true);
                            $position = get_post_meta(get_the_ID(), '_candidate_position', true);
                            $ballot_number = get_post_meta(get_the_ID(), '_candidate_ballot_number', true);
                            $department = get_post_meta(get_the_ID(), '_candidate_department', true);
                            $candidate_hall = get_post_meta(get_the_ID(), '_candidate_hall', true);
                            $session = get_post_meta(get_the_ID(), '_candidate_session', true);

                            // Get hall taxonomy terms
                            $hall_terms = get_the_terms(get_the_ID(), 'halls');
                            $hall_name = '';
                            if ($hall_terms && !is_wp_error($hall_terms)) {
                                $hall_name = $hall_terms[0]->name;
                            } elseif ($candidate_hall) {
                                $hall_name = $candidate_hall;
                            }
                            ?>
                            <article class="candidate-card bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transform hover:scale-105 transition-all duration-300 group">
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

                                    <!-- Hall Badge -->
                                    <?php if ($hall_name) : ?>
                                        <div class="absolute top-4 right-4 bg-black/50 text-white px-2 py-1 rounded text-xs">
                                            <?php echo esc_html($hall_name); ?>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Hover Overlay -->
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center">
                                        <a href="<?php the_permalink(); ?>"
                                           class="bg-white text-primary-blue px-6 py-2 rounded-full font-medium opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-4 group-hover:translate-y-0">
                                            বিস্তারিত দেখুন
                                        </a>
                                    </div>
                                </div>

                                <div class="p-6">
                                    <h2 class="text-xl font-bold text-gray-800 mb-2 line-clamp-2">
                                        <a href="<?php the_permalink(); ?>" class="hover:text-primary-green transition-colors">
                                            <?php echo esc_html($name_bangla ?: get_the_title()); ?>
                                        </a>
                                    </h2>

                                    <div class="space-y-2 mb-4">
                                        <p class="text-primary-green font-semibold text-lg"><?php echo esc_html($position); ?></p>
                                        <?php if ($department) : ?>
                                            <p class="text-gray-600 text-sm"><?php echo esc_html($department); ?></p>
                                        <?php endif; ?>
                                        <?php if ($session) : ?>
                                            <p class="text-gray-500 text-sm">সেশন: <?php echo esc_html($session); ?></p>
                                        <?php endif; ?>
                                    </div>

                                    <div class="flex justify-between items-center">
                                        <a href="<?php the_permalink(); ?>"
                                           class="text-primary-green hover:text-primary-red font-medium text-sm transition-colors duration-300 flex items-center">
                                            <span>আরও জানুন</span>
                                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>

                                        <button class="bg-primary-green hover:bg-primary-red text-white px-4 py-2 rounded-full text-sm font-medium transition-all duration-300 contact-candidate-btn"
                                                data-candidate-id="<?php echo get_the_ID(); ?>">
                                            যোগাযোগ
                                        </button>
                                    </div>
                                </div>
                            </article>
                        <?php endwhile; ?>
                    </div>

                    <!-- Pagination -->
                    <div class="pagination-wrapper mt-16">
                        <?php
                        $pagination = paginate_links([
                            'prev_text' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>',
                            'next_text' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>',
                            'type' => 'array',
                            'end_size' => 1,
                            'mid_size' => 2
                        ]);

                        if ($pagination) : ?>
                            <nav class="flex justify-center" role="navigation" aria-label="Pagination">
                                <div class="flex space-x-2">
                                    <?php foreach ($pagination as $page) :
                                        $page = str_replace('page-numbers', 'page-numbers px-4 py-2 border border-gray-300 text-gray-700 hover:bg-primary-green hover:text-white hover:border-primary-green transition-all duration-300', $page);
                                        $page = str_replace('current', 'current bg-primary-green text-white border-primary-green', $page);
                                        echo $page;
                                    endforeach; ?>
                                </div>
                            </nav>
                        <?php endif; ?>
                    </div>

                <?php else : ?>
                    <!-- No candidates found -->
                    <div class="text-center py-16">
                        <div class="max-w-md mx-auto bg-white rounded-xl shadow-lg p-8">
                            <svg class="w-24 h-24 text-gray-400 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            <h2 class="text-2xl font-bold text-gray-800 mb-4">কোনো প্রার্থী পাওয়া যায়নি</h2>
                            <?php if ($current_hall) : ?>
                                <p class="text-gray-600 mb-6">এই হলের জন্য এখনো কোনো প্রার্থী যোগ করা হয়নি।</p>
                                <a href="/hall-panels"
                                   class="inline-flex items-center bg-primary-green hover:bg-primary-blue text-white font-medium py-2 px-6 rounded-lg transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                    সব হল দেখুন
                                </a>
                            <?php else : ?>
                                <p class="text-gray-600 mb-6">এই মুহূর্তে কোনো হল প্যানেলের প্রার্থী পাওয়া যাচ্ছে না।</p>
                                <a href="/"
                                   class="inline-flex items-center bg-primary-green hover:bg-primary-blue text-white font-medium py-2 px-6 rounded-lg transition-colors">
                                    হোম পেজে ফিরুন
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Quick Stats -->
<?php if ($current_hall) : ?>
    <section class="hall-stats py-12 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="bg-primary-green text-white rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800"><?php echo $wp_query->found_posts; ?></h3>
                        <p class="text-gray-600">মোট প্রার্থী</p>
                    </div>

                    <div class="text-center">
                        <div class="bg-primary-blue text-white rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.84L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.84l-7-3z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800"><?php echo esc_html($current_hall->name); ?></h3>
                        <p class="text-gray-600">হল</p>
                    </div>

                    <div class="text-center">
                        <div class="bg-primary-red text-white rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800">২০২৫</h3>
                        <p class="text-gray-600">নির্বাচন</p>
                    </div>
                </div>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Hall filter functionality
            const hallFilter = document.getElementById('hall-filter');
            if (hallFilter) {
                hallFilter.addEventListener('change', function() {
                    const selectedHall = this.value;
                    if (selectedHall) {
                        window.location.href = '/halls/' + selectedHall + '/';
                    } else {
                        window.location.href = '/hall-candidate/';
                    }
                });
            }
        });
    </script>

<?php get_footer(); ?>