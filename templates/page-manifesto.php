<?php
/**
 * Template Name: Manifesto
 * Page template for displaying manifesto items
 */

get_header(); ?>

    <!-- Page Header -->
    <section class="page-header py-20 relative overflow-hidden bg-no-repeat bg-center bg-cover" style="background-image: linear-gradient(135deg, rgba(0, 213, 190, 0.8), rgba(255, 240, 133, 0.9)), url('/ducsu/wp-content/themes/jcd-ducsu/assets/images/central-bg.jpg');">
        <div class="container mx-auto px-4 text-center relative z-10">
            <div class="max-w-4xl mx-auto">
                <h1 class="text-4xl md:text-6xl font-bold mb-6 text-slate-700">আমাদের ইশতিহার</h1>
                <p class="text-lg md:text-xl leading-relaxed text-slate-800">"প্রতিশ্রুতি নয়, পরিবর্তনে প্রতিজ্ঞাবদ্ধ"</p>
            </div>
        </div>
    </section>

    <!-- Manifesto Items -->
    <section class="manifesto-content py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">

                <div id="manifesto-accordion" class="space-y-4">
                    <?php
                    $manifesto_items = ducsu_get_manifesto_items();

                    if ($manifesto_items->have_posts()) :
                        $item_count = 0;
                        while ($manifesto_items->have_posts()) : $manifesto_items->the_post();
                            $item_count++;
                            $accordion_id = 'accordion-item-' . $item_count;
                            ?>
                            <div class="accordion-item bg-white rounded-lg shadow-md overflow-hidden">
                                <div class="accordion-header">
                                    <button type="button"
                                            class="accordion-button flex cursor-pointer items-center justify-between w-full p-6 text-left bg-white hover:bg-gray-100 transition-colors duration-300 active:bg-gray-200 focus:outline-none"
                                            data-target="#<?php echo $accordion_id; ?>"
                                            aria-expanded="false"
                                            aria-controls="<?php echo $accordion_id; ?>">
                                        <div class="flex items-center space-x-4">
                                            <div class="bg-primary-green text-white w-10 h-10 rounded-full flex items-center justify-center font-bold">
                                                <?php echo str_pad($item_count, 2, '0', STR_PAD_LEFT); ?>
                                            </div>
                                            <h3 class="text-lg md:text-xl font-semibold text-gray-800">
                                                <?php the_title(); ?>
                                            </h3>
                                        </div>
                                        <svg class="accordion-icon w-6 h-6 text-gray-500 transition-transform duration-300"
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>
                                </div>

                                <div id="<?php echo $accordion_id; ?>"
                                     class="accordion-content hidden"
                                     aria-labelledby="accordion-button-<?php echo $item_count; ?>">
                                    <div class="p-6 pt-0 border-t border-gray-100">
                                        <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                                            <?php the_content(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        endwhile;
                    else :
                        ?>
                        <div class="text-center py-12">
                            <div class="bg-white rounded-lg shadow-md p-8 max-w-md mx-auto">
                                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-800 mb-2">ইশতিহার আপডেট হচ্ছে</h3>
                                <p class="text-gray-600">আমাদের বিস্তারিত ইশতিহার শীঘ্রই প্রকাশিত হবে।</p>
                            </div>
                        </div>
                    <?php
                    endif;
                    wp_reset_postdata();
                    ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="cta-section py-16 bg-gradient-to-r from-primary-blue to-primary-green">
        <div class="container mx-auto px-4 text-center text-white">
            <div class="max-w-3xl mx-auto text-slate-700">
                <h2 class="text-3xl md:text-4xl font-bold mb-6">আপনার মতামত আমাদের কাছে গুরুত্বপূর্ণ</h2>
                <p class="text-lg md:text-xl mb-8 opacity-90">
                    আমাদের ইশতিহার সম্পর্কে আপনার কোনো প্রশ্ন বা পরামর্শ থাকলে আমাদের সাথে যোগাযোগ করুন।
                    আমরা সবসময় আপনাদের মতামতের জন্য প্রস্তুত।
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Accordion functionality
            const accordionButtons = document.querySelectorAll('.accordion-button');

            accordionButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const target = document.querySelector(this.getAttribute('data-target'));
                    const icon = this.querySelector('.accordion-icon');
                    const isExpanded = this.getAttribute('aria-expanded') === 'true';

                    // Close all accordion items
                    accordionButtons.forEach(btn => {
                        const content = document.querySelector(btn.getAttribute('data-target'));
                        const btnIcon = btn.querySelector('.accordion-icon');

                        btn.setAttribute('aria-expanded', 'false');
                        content.classList.add('hidden');
                        btnIcon.style.transform = 'rotate(0deg)';
                    });

                    // If the clicked item was not expanded, expand it
                    if (!isExpanded) {
                        this.setAttribute('aria-expanded', 'true');
                        target.classList.remove('hidden');
                        icon.style.transform = 'rotate(180deg)';
                    }
                });
            });

            // Auto-expand first item on load
            if (accordionButtons.length > 0) {
                accordionButtons[0].click();
            }
        });
    </script>

<?php get_footer(); ?>