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

        <!-- Decorative Elements -->
        <div class="absolute top-10 left-10 w-32 h-32 bg-white opacity-5 rounded-full"></div>
        <div class="absolute bottom-10 right-10 w-24 h-24 bg-white opacity-5 rounded-full"></div>
        <div class="absolute top-1/2 left-1/4 w-16 h-16 bg-white opacity-5 rounded-full"></div>
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
                                            class="accordion-button flex items-center justify-between w-full p-6 text-left bg-white hover:bg-gray-50 transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-primary-green focus:ring-inset"
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

                                        <!-- Optional: Add implementation timeline or priority -->
                                        <div class="mt-6 flex items-center space-x-4 text-sm">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-primary-green text-white font-medium">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        অগ্রাধিকার
                                    </span>

                                            <?php
                                            $implementation_period = get_post_meta(get_the_ID(), '_implementation_period', true);
                                            if ($implementation_period) :
                                                ?>
                                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-gray-100 text-gray-700">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <?php echo esc_html($implementation_period); ?>
                                        </span>
                                            <?php endif; ?>
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
            <div class="max-w-3xl mx-auto">
                <h2 class="text-3xl md:text-4xl font-bold mb-6">আপনার মতামত আমাদের কাছে গুরুত্বপূর্ণ</h2>
                <p class="text-lg md:text-xl mb-8 opacity-90">
                    আমাদের ইশতিহার সম্পর্কে আপনার কোনো প্রশ্ন বা পরামর্শ থাকলে আমাদের সাথে যোগাযোগ করুন।
                    আমরা সবসময় আপনাদের মতামতের জন্য প্রস্তুত।
                </p>
                <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-6">
                    <a href="/contact"
                       class="inline-block bg-white text-primary-blue font-bold py-3 px-8 rounded-lg hover:bg-gray-100 transition-all duration-300 transform hover:scale-105">
                        যোগাযোগ করুন
                    </a>
                    <a href="/central-panel"
                       class="inline-block border-2 border-white text-white font-bold py-3 px-8 rounded-lg hover:bg-white hover:text-primary-blue transition-all duration-300 transform hover:scale-105">
                        প্রার্থীদের দেখুন
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

                        // Smooth scroll to the accordion item
                        setTimeout(() => {
                            this.scrollIntoView({
                                behavior: 'smooth',
                                block: 'start',
                                inline: 'nearest'
                            });
                        }, 300);
                    }
                });
            });

            // Auto-expand first item on load
            if (accordionButtons.length > 0) {
                accordionButtons[0].click();
            }

            // Add smooth transitions
            const style = document.createElement('style');
            style.textContent = `
        .accordion-content {
            transition: all 0.3s ease;
        }

        .accordion-button:hover .accordion-icon {
            color: #0B8645;
        }

        .prose h3 {
            color: #1f2937;
            font-weight: 600;
            margin-top: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .prose ul {
            list-style-type: disc;
            padding-left: 1.5rem;
            margin: 1rem 0;
        }

        .prose li {
            margin-bottom: 0.5rem;
        }

        .prose p {
            margin-bottom: 1rem;
            line-height: 1.7;
        }
    `;
            document.head.appendChild(style);
        });
    </script>

<?php get_footer(); ?>
<?php get_footer(); ?>